<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Services Management</h2>
            <p class="text-muted">Manage B2C services, operator tiers, and platform rewards.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recharge Service Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="icon-box bg-primary bg-opacity-10 mb-3 mx-auto" style="width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-phone-vibrate text-primary fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Mobile Recharge</h5>
                    <p class="text-muted small">Configure Jio, Airtel, VI, and BSNL coin reward tiers.</p>
                    <div class="d-flex justify-content-between align-items-center mt-3 bg-light p-2 rounded">
                        <span class="text-muted small">Operators:</span>
                        <span class="badge bg-primary rounded-pill"><?= $counts['operators'] ?></span>
                    </div>
                    <a href="<?= base_url('admin/services/recharge') ?>" class="btn btn-outline-primary w-100 mt-4">Manage Operators</a>
                </div>
            </div>
        </div>

        <!-- Ecommerce Service Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="icon-box bg-success bg-opacity-10 mb-3 mx-auto" style="width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-shop text-success fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Ecommerce Partners</h5>
                    <p class="text-muted small">Affiliate platforms like Amazon, Flipkart, and more.</p>
                    <div class="d-flex justify-content-between align-items-center mt-3 bg-light p-2 rounded">
                        <span class="text-muted small">Platforms:</span>
                        <span class="badge bg-success rounded-pill"><?= $counts['platforms'] ?></span>
                    </div>
                    <a href="<?= base_url('admin/services/ecommerce') ?>" class="btn btn-outline-success w-100 mt-4">Manage Platforms</a>
                </div>
            </div>
        </div>

        <!-- Pending Approvals Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="icon-box bg-warning bg-opacity-10 mb-3 mx-auto" style="width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-clock-history text-warning fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Pending Approvals</h5>
                    <p class="text-muted small">Review and approve service transactions to release coins.</p>
                    <div class="d-flex justify-content-between align-items-center mt-3 bg-light p-2 rounded">
                        <span class="text-muted small">To Review:</span>
                        <span class="badge bg-warning text-dark rounded-pill"><?= $counts['pending'] ?></span>
                    </div>
                    <a href="<?= base_url('admin/services/transactions') ?>" class="btn btn-warning w-100 mt-4">Review Transactions</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
