<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-person-badge me-2 text-primary"></i>My Profile</h5>
                <p class="text-muted small mb-0">Update your banking details for seamless withdrawals.</p>
            </div>
            <div class="card-body p-4">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success border-0 small mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger border-0 small mb-4">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('profile/update') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4 row">
                        <!-- user name -->
                        <div class="col-md-6">
                        <label class="form-label small fw-bold">Full Name (As per Bank)</label>
                        <input type="text" name="full_name" class="form-control form-control-lg" 
                               value="<?= esc($profile['full_name'] ?? '') ?>" placeholder="Enter your full name">
                        </div>

                        <!-- logged user phone number -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Mobile Number</label>
                            <input type="text" class="form-control form-control-lg bg-light" 
                               value="<?= esc($user['phone'] ?? '') ?>" readonly>
                            <div class="text-muted" style="font-size: 0.7rem;">Verified Mobile Number</div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">UPI ID</label>
                            <input type="text" name="upi_id" class="form-control form-control-lg" 
                                   value="<?= esc($profile['upi_id'] ?? '') ?>" placeholder="username@upi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control form-control-lg" 
                                   value="<?= esc($profile['bank_name'] ?? '') ?>" placeholder="e.g. HDFC Bank">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Bank Account Number</label>
                            <input type="text" name="bank_account_no" class="form-control form-control-lg" 
                                   value="<?= esc($profile['bank_account_no'] ?? '') ?>" placeholder="Enter account number">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">IFSC Code</label>
                            <input type="text" name="ifsc_code" class="form-control form-control-lg" 
                                   value="<?= esc($profile['ifsc_code'] ?? '') ?>" placeholder="HDFC0001234">
                        </div>
                    </div>

                    <div class="d-grid pt-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-3">
                            <i class="bi bi-save me-2"></i>Update Secure Profile
                        </button>
                    </div>
                </form>

                <div class="mt-4 p-3 bg-light rounded-3 border-start border-4 border-info">
                    <p class="small text-muted mb-0">
                        <i class="bi bi-shield-lock-fill text-info me-2"></i>
                        <strong>Privacy Note:</strong> Your banking details are stored securely and only accessible by the administration for payout processing.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
