<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\Controller;

class NotificationController extends BaseController
{
    protected $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    /**
     * List all notifications for the user
     */
    public function index()
    {
        $userId = session()->get('user_id');
        
        $data = [
            'title'         => 'My Notifications',
            'active'        => 'notifications',
            'notifications' => $this->notificationModel->getAll($userId)
        ];

        // Mark all as read when they visit the page
        $this->notificationModel->markAllRead($userId);

        return view('user/notifications/index', $data);
    }

    /**
     * Mark a single notification as read (AJAX or direct)
     */
    public function markAsRead(int $id)
    {
        $userId = session()->get('user_id');
        $this->notificationModel->where('user_id', $userId)->where('id', $id)->set('is_read', 1)->update();
        
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'success']);
        }
        
        return redirect()->back();
    }
}
