<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table         = 'notifications';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['user_id', 'type', 'title', 'message', 'icon', 'is_read'];
    protected $useTimestamps = true;

    /**
     * Create a notification for a user.
     */
    public function notify(int $userId, string $title, string $message, string $type = 'info', string $icon = 'bi-bell')
    {
        $this->insert([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'icon'    => $icon,
        ]);
    }

    /**
     * Get unread notifications for a user.
     */
    public function getUnread(int $userId): array
    {
        return $this->where('user_id', $userId)->where('is_read', 0)->orderBy('id', 'DESC')->findAll();
    }

    /**
     * Get all notifications for a user.
     */
    public function getAll(int $userId): array
    {
        return $this->where('user_id', $userId)->orderBy('id', 'DESC')->limit(30)->findAll();
    }

    /**
     * Mark all as read for a user.
     */
    public function markAllRead(int $userId)
    {
        $this->where('user_id', $userId)->set('is_read', 1)->update();
    }

    /**
     * Count unread count.
     */
    public function countUnread(int $userId): int
    {
        return $this->where('user_id', $userId)->where('is_read', 0)->countAllResults();
    }
}
