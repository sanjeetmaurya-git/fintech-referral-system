<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\TicketMessageModel;
use App\Models\NotificationModel;
use CodeIgniter\Controller;

class SupportController extends BaseController
{
    protected $ticketModel;
    protected $messageModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->ticketModel  = new TicketModel();
        $this->messageModel = new TicketMessageModel();
        $this->notificationModel = new NotificationModel();
    }

    // User: List my tickets
    public function index()
    {
        $userId = session()->get('user_id');
        $data = [
            'title'   => 'Support Helpdesk',
            'active'  => 'support',
            'tickets' => $this->ticketModel->where('user_id', $userId)->orderBy('updated_at', 'DESC')->findAll()
        ];
        return view('user/support/index', $data);
    }

    // User: New ticket form
    public function create()
    {
        $data = [
            'title'  => 'Open Support Ticket',
            'active' => 'support',
        ];
        return view('user/support/create', $data);
    }

    // User: Submit ticket
    public function store()
    {
        $userId = session()->get('user_id');
        $rules = [
            'subject' => 'required|min_length[5]|max_length[255]',
            'message' => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Create Ticket
        $ticketId = $this->ticketModel->insert([
            'user_id'  => $userId,
            'subject'  => $this->request->getPost('subject'),
            'status'   => 'open',
            'priority' => $this->request->getPost('priority') ?? 'medium',
        ]);

        // 2. Create Initial Message
        $this->messageModel->insert([
            'ticket_id'      => $ticketId,
            'sender_id'      => $userId,
            'message'        => $this->request->getPost('message'),
            'is_admin_reply' => 0,
        ]);

        $db->transComplete();

        return redirect()->to(base_url('support'))->with('success', 'Ticket opened successfully. Our team will get back to you soon.');
    }

    // User/Admin: View ticket thread
    public function view(int $id)
    {
        $userIdScope = session()->get('user_id');
        $isAdmin = session()->get('is_admin');

        $ticket = $this->ticketModel->getFullTicket($id);

        if (!$ticket || (!$isAdmin && $ticket['user_id'] != $userIdScope)) {
            return redirect()->to(base_url('support'))->with('error', 'Unauthorized access.');
        }

        $data = [
            'title'    => 'Ticket: ' . esc($ticket['subject']),
            'active'   => 'support',
            'ticket'   => $ticket,
            'messages' => $this->messageModel->where('ticket_id', $id)->orderBy('created_at', 'ASC')->findAll()
        ];

        // If user views the ticket, and it's pending_user, we can mark it as open again or just let it be.
        // For simplicity, viewing clears the "new" status for the user.
        if (!$isAdmin && $ticket['status'] === 'pending_user') {
            $this->ticketModel->update($id, ['status' => 'open']);
        }

        $viewPath = $isAdmin ? 'admin/support_view' : 'user/support/view';
        return view($viewPath, $data);
    }

    // AJAX: Get thread for modal
    public function get_thread(int $id)
    {
        if (!session()->get('is_admin')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(403);
        }

        $ticket = $this->ticketModel->getFullTicket($id);
        if (!$ticket) {
            return $this->response->setJSON(['error' => 'Not found'])->setStatusCode(404);
        }

        $messages = $this->messageModel->where('ticket_id', $id)->orderBy('created_at', 'ASC')->findAll();

        return $this->response->setJSON([
            'ticket'   => $ticket,
            'messages' => $messages
        ]);
    }

    // User/Admin: Post reply
    public function reply(int $id)
    {
        $userId = session()->get('user_id', 0); // Handle admin session later if needed
        $isAdmin = session()->get('is_admin');

        $ticket = $this->ticketModel->find($id);
        if (!$ticket) return redirect()->back()->with('error', 'Ticket not found.');

        $message = $this->request->getPost('message');
        if (empty($message)) return redirect()->back();

        $this->messageModel->insert([
            'ticket_id'      => $id,
            'sender_id'      => $userId ?: 0, // 0 for Admin if no specific ID
            'message'        => $message,
            'is_admin_reply' => $isAdmin ? 1 : 0,
        ]);

        // Update ticket status
        $markResolved = $this->request->getPost('mark_resolved');
        $status = $markResolved ? 'resolved' : ($isAdmin ? 'pending_user' : 'open');
        $this->ticketModel->update($id, ['status' => $status]);

        // If Admin is replying, notify the user
        if ($isAdmin) {
            $this->notificationModel->notify(
                $ticket['user_id'],
                "New Support Reply",
                "An admin has replied to your ticket: " . esc($ticket['subject']),
                'support',
                'bi-chat-left-dots-fill'
            );
        }

        return redirect()->back()->with('success', 'Reply posted.');
    }

    // Admin: List all tickets
    public function adminIndex()
    {
        $data = [
            'title'   => 'Support Inbox',
            'active'  => 'support',
            'tickets' => $this->ticketModel->select('support_tickets.*, users.phone')
                                          ->join('users', 'users.id = support_tickets.user_id')
                                          ->orderBy('updated_at', 'DESC')
                                          ->findAll()
        ];
        return view('admin/support', $data);
    }
}
