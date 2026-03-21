<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <a href="<?= base_url('dashboard') ?>" class="text-decoration-none small mb-3 d-inline-block"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        <div class="card p-4">
            <h4 class="fw-bold mb-3">Withdraw Funds</h4>
            <div class="alert alert-light border small text-muted">
                Current Balance: <strong>₹<?= number_format($wallet['balance'] ?? 0, 2) ?></strong>
            </div>

            <form action="<?= base_url('withdraw/submit') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Amount to Withdraw</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" name="amount" class="form-control" placeholder="Min. 100" min="100" max="<?= $wallet['balance'] ?>" step="0.01" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold">Select Payout Method</label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">UPI ID</label>
                            <input type="text" name="upi_id" class="form-control" 
                                   placeholder="username@upi" value="<?= esc($profile['upi_id'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" 
                                   placeholder="e.g. ICICI Bank" value="<?= esc($profile['bank_name'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-8">
                        <label class="form-label small fw-bold">Bank Account Number</label>
                        <input type="text" name="bank_account_no" class="form-control" 
                               placeholder="Account Number" value="<?= esc($profile['bank_account_no'] ?? '') ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">IFSC Code</label>
                        <input type="text" name="ifsc_code" class="form-control" 
                               placeholder="IFSC" value="<?= esc($profile['ifsc_code'] ?? '') ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" <?= ($wallet['balance'] < 100) ? 'disabled' : '' ?>>
                    Submit Request
                </button>
                <?php if ($wallet['balance'] < 100): ?>
                    <p class="text-danger small mt-2 text-center">You need at least ₹100 to withdraw.</p>
                <?php endif; ?>
            </form>
        </div>
        
        <div class="mt-4 small text-muted">
            <p><strong>Note:</strong> Withdrawals are processed within 24-48 business hours. You will receive an notification once approved.</p>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
