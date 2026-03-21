<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
            <div class="card-header bg-white py-4 px-4 border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                        <i class="bi bi-person-badge fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">My Profile</h5>
                        <p class="text-muted small mb-0">Update your account and banking details.</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4 px-lg-5">
                <form action="<?= base_url('profile/update') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase tracking-wider">Full Name</label>
                            <input type="text" name="full_name" class="form-control form-control-lg rounded-4 border-0 bg-light px-4" 
                                   value="<?= esc($profile['full_name'] ?? '') ?>" placeholder="Enter full name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-uppercase tracking-wider">Phone Number</label>
                            <input type="text" class="form-control form-control-lg rounded-4 border-0 bg-light px-4 opacity-75" 
                                   value="<?= esc($user['phone']) ?>" readonly>
                        </div>
                    </div>

                    <hr class="my-4 opacity-5">

                    <h6 class="fw-bold mb-4 text-primary text-uppercase small tracking-wider">Banking Details (For Withdrawals)</h6>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">UPI ID</label>
                            <input type="text" name="upi_id" class="form-control form-control-lg rounded-4 border-0 bg-light px-4" 
                                   value="<?= esc($profile['upi_id'] ?? '') ?>" placeholder="username@upi">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control form-control-lg rounded-4 border-0 bg-light px-4" 
                                   value="<?= esc($profile['bank_name'] ?? '') ?>" placeholder="e.g. HDFC Bank">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label small fw-bold">Account Number</label>
                            <input type="text" name="bank_account_no" class="form-control form-control-lg rounded-4 border-0 bg-light px-4" 
                                   value="<?= esc($profile['bank_account_no'] ?? '') ?>" placeholder="Enter account number">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">IFSC Code</label>
                            <input type="text" name="ifsc_code" class="form-control form-control-lg rounded-4 border-0 bg-light px-4" 
                                   value="<?= esc($profile['ifsc_code'] ?? '') ?>" placeholder="HDFC0001234">
                        </div>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold">
                            <i class="bi bi-shield-check me-2"></i>Save Secure Profile
                        </button>
                    </div>
                </form>

                <div class="mt-5 p-4 bg-primary bg-opacity-5 rounded-5 border border-primary border-opacity-10">
                    <div class="d-flex gap-3">
                        <i class="bi bi-shield-lock-fill text-primary fs-4"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Secure Storage</h6>
                            <p class="small text-muted mb-0">Your banking details are encrypted and used only for processing withdrawal requests. We never share your data with third parties.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
