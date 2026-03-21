<?php

namespace App\Services;

use App\Models\WalletLedgerModel;
use App\Models\WalletModel;
use Config\Database;

class LedgerService
{
    protected $ledgerModel;
    protected $walletModel;

    public function __construct()
    {
        $this->ledgerModel = new WalletLedgerModel();
        $this->walletModel = new WalletModel();
    }

    /**
     * Record a transaction in the ledger and update the wallet cache.
     * 
     * @param int|string $userId
     * @param string $type 'credit' or 'debit'
     * @param float $amount
     * @param string $referenceId (e.g., transaction ID, withdrawal ID)
     * @param string $description
     * @param string $account 'balance' or 'coins'
     * @return bool
     */
    public function record($userId, string $type, float $amount, string $referenceId = '', string $description = '', string $account = 'balance')
    {
        $db = Database::connect();
        $db->transStart();

        // 1. Get current state from wallet (or default if not exists)
        $wallet = $this->walletModel->where('user_id', $userId)->first();
        
        if ($account === 'coins') {
            $currentValue = $wallet ? (float)$wallet['coins'] : 0.00;
        } else {
            $currentValue = $wallet ? (float)$wallet['balance'] : 0.00;
        }

        // 2. Calculate new value
        if ($type === 'credit') {
            $newValue = $currentValue + $amount;
        } else {
            $newValue = $currentValue - $amount;
        }

        // 3. Insert Ledger Entry
        // Use description to denote account if ledger table doesn't have an account column
        $finalDesc = ($account === 'coins' ? "[COINS] " : "") . $description;

        $this->ledgerModel->insert([
            'user_id'       => $userId,
            'type'          => $type,
            'amount'        => $amount,
            'balance_after' => $newValue,
            'reference_id'  => $referenceId,
            'description'   => $finalDesc,
        ]);

        // 4. Update Wallet Cache
        $updateData = [$account => $newValue];
        if ($wallet) {
            $this->walletModel->update($wallet['id'], $updateData);
        } else {
            $insertData = ['user_id' => $userId, $account => $newValue];
            $this->walletModel->insert($insertData);
        }

        $db->transComplete();

        return $db->transStatus();
    }

    /**
     * Verify ledger integrity for a user.
     * Check if sum(credits) - sum(debits) matches current wallet balance.
     */
    public function verifyIntegrity($userId)
    {
        $credits = $this->ledgerModel->where(['user_id' => $userId, 'type' => 'credit'])->selectSum('amount')->first()['amount'] ?? 0;
        $debits  = $this->ledgerModel->where(['user_id' => $userId, 'type' => 'debit'])->selectSum('amount')->first()['amount'] ?? 0;
        
        $expectedBalance = (float)$credits - (float)$debits;
        
        $wallet = $this->walletModel->where('user_id', $userId)->first();
        $actualBalance = $wallet ? (float)$wallet['balance'] : 0.00;

        return [
            'expected' => $expectedBalance,
            'actual'   => $actualBalance,
            'is_valid' => abs($expectedBalance - $actualBalance) < 0.0001
        ];
    }
}
