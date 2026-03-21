<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
            <div class="card-header bg-white py-4 px-4 border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                        <i class="bi bi-wallet2 fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Withdraw Funds</h5>
                        <p class="text-muted small mb-0">Securely transfer your earnings to your bank.</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 px-lg-5">
                <div class="alert bg-light rounded-4 border-0 p-3 mb-4 d-flex align-items-center justify-content-between">
                    <span class="text-muted small fw-bold text-uppercase">Current Balance</span>
                    <h4 class="fw-bold mb-0 text-primary">₹<?= number_format($wallet['balance'] ?? 0, 2) ?></h4>
                </div>

                <form action="<?= base_url('withdraw/submit') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-uppercase tracking-wider">Amount to Withdraw</label>
                        <div class="input-group input-group-lg glass-input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-4 px-4">₹</span>
                            <input type="number" name="amount" class="form-control bg-light border-0 rounded-end-4 px-3" 
                                   placeholder="Min. 100" min="100" max="<?= $wallet['balance'] ?>" step="0.01" required>
                        </div>
                        <div class="mt-2 text-muted" style="font-size: 0.75rem;">
                            <i class="bi bi-info-circle me-1"></i> Minimum withdrawal amount is ₹100.
                        </div>
                    </div>

                    <hr class="my-4 opacity-5">

                    <h6 class="fw-bold mb-4 text-primary text-uppercase small tracking-wider">Review Destination</h6>
                    
                    <div class="row g-3 mb-5">
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-4 border border-primary border-opacity-10">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Payee Name</span>
                                    <span class="fw-bold small"><?= esc($profile['full_name'] ?? 'Not Set') ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">UPI / Account</span>
                                    <span class="fw-bold small"><?= esc($profile['upi_id'] ?? ($profile['bank_account_no'] ?? 'Not Set')) ?></span>
                                </div>
                                <div class="text-end">
                                    <a href="<?= base_url('profile') ?>" class="text-decoration-none small fw-bold"><i class="bi bi-pencil-square me-1"></i>Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid pt-2">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold" <?= ($wallet['balance'] < 100) ? 'disabled' : '' ?>>
                            <i class="bi bi-arrow-up-right-circle me-2"></i>Process Withdrawal
                        </button>
                    </div>

                    <?php if ($wallet['balance'] < 100): ?>
                        <div class="text-center mt-3">
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-medium" style="font-size: 0.75rem;">
                                <i class="bi bi-exclamation-triangle me-1"></i> Insufficient balance for withdrawal.
                            </span>
                        </div>
                    <?php endif; ?>
                </form>

                <div class="mt-5 p-4 bg-primary bg-opacity-5 rounded-5">
                    <div class="d-flex gap-3">
                        <i class="bi bi-clock-history text-primary fs-4"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Processing Time</h6>
                            <p class="small text-muted mb-0">Requests are usually processed within 24-48 business hours. You'll receive a notification as soon as the funds are settled.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= base_url('dashboard') ?>" class="text-decoration-none small text-muted hover-primary">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>

<?php $this->endSection(); ?>
