<?php

if (!function_exists('get_unread_support_count')) {
    function get_unread_support_count() {
        $userId = session()->get('user_id');
        if (!$userId) return 0;

        $db = \Config\Database::connect();
        return $db->table('support_tickets')
                  ->where('user_id', $userId)
                  ->where('status', 'pending_user')
                  ->countAllResults();
    }
}
