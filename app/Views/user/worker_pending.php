<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="container py-5 min-vh-75 d-flex align-items-center justify-content-center">
    <div class="glass-card p-5 rounded-5 shadow-lg border-0 text-center" style="max-width: 600px;" data-aos="zoom-in">
        <div class="mb-4">
            <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto" style="width: 100px; height: 100px; font-size: 3rem;">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
        
        <h2 class="fw-bold mb-3">Verification Pending</h2>
        <p class="text-muted fs-5 mb-4">
            Your application is currently under review by our administration team. Please wait while we verify your documents and skills.
        </p>

        <div class="alert alert-info rounded-4 border-0 shadow-sm text-start mb-4 py-3">
            <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Status Update</h6>
            <p class="mb-0 small text-secondary">
                We usually process applications within 24 hours. You will be notified once your profile is approved.
            </p>
        </div>

        <div class="d-flex flex-column gap-3">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-primary btn-lg rounded-pill fw-bold">
                Go to User Dashboard
            </a>
            <a href="<?= base_url('/') ?>" class="btn btn-light btn-lg rounded-pill fw-bold">
                Return Home
            </a>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
