<?php

namespace App\Services;

use App\Models\UserModel;

class RewardService
{
    /**
     * Notify user when a reward is approved
     */
    public function notifyRewardApproved(int $userId, float $amount)
    {
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if ($user) {
            $notificationService = new NotificationService();
            $notificationService->sendRewardUpdate($user, $amount, 'approved');
        }
    }

    /**
     * Notify user when a reward is rejected
     */
    public function notifyRewardRejected(int $userId, float $amount)
    {
        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if ($user) {
            $notificationService = new NotificationService();
            $notificationService->sendRewardUpdate($user, $amount, 'rejected');
        }
    }
}
