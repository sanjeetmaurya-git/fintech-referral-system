<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="container py-5 min-vh-75 d-flex align-items-center justify-content-center">
    <div class="glass-card p-5 rounded-5 shadow-lg border-0 text-center" style="max-width: 600px;" data-aos="zoom-in">
        <div class="mb-4">
            <div class="icon-box bg-success bg-opacity-10 text-success mx-auto" style="width: 100px; height: 100px; font-size: 3rem;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
        </div>
        
        <h2 class="fw-bold mb-3">Application Submitted!</h2>
        <p class="text-muted fs-5 mb-4">
            Thank you for registering as a professional worker. Your application has been sent for verification.
        </p>

        <div class="alert alert-info rounded-4 border-0 shadow-sm text-start mb-4 py-3">
            <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>What's Next?</h6>
            <ul class="mb-0 small text-secondary">
                <li class="mb-1">Our admin team will review your documents and skill profile.</li>
                <li class="mb-1">This process typically takes 12-24 hours.</li>
                <li>Once approved, you will be able to access your worker dashboard.</li>
            </ul>
        </div>

        <div class="d-flex flex-column gap-3">
            <a href="<?= base_url('dashboard') ?>" class="btn btn-primary btn-lg rounded-pill fw-bold">
                Go to Dashboard
            </a>
            <a href="<?= base_url('/') ?>" class="btn btn-light btn-lg rounded-pill fw-bold">
                Return Home
            </a>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
