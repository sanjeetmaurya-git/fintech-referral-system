<?php

use App\Models\NotificationModel;

/**
 * Get unread notification count for the current user
 */
if (!function_exists('get_unread_notification_count')) {
    function get_unread_notification_count()
    {
        $userId = session()->get('user_id');
        if (!$userId) return 0;

        $model = new NotificationModel();
        return $model->countUnread($userId);
    }
}

/**
 * Get recent unread notifications for the dropdown
 */
if (!function_exists('get_recent_notifications')) {
    function get_recent_notifications($limit = 5)
    {
        $userId = session()->get('user_id');
        if (!$userId) return [];

        $model = new NotificationModel();
        return $model->where('user_id', $userId)
                     ->where('is_read', 0)
                     ->orderBy('id', 'DESC')
                     ->limit($limit)
                     ->findAll();
    }
}
