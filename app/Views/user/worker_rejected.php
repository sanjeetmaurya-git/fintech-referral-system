<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="container py-5 min-vh-75 d-flex align-items-center justify-content-center">
    <div class="glass-card p-5 rounded-5 shadow-lg border-0 text-center" style="max-width: 600px;" data-aos="zoom-in">
        <div class="mb-4">
            <div class="icon-box bg-danger bg-opacity-10 text-danger mx-auto" style="width: 100px; height: 100px; font-size: 3rem;">
                <i class="bi bi-x-circle-fill"></i>
            </div>
        </div>
        
        <h2 class="fw-bold mb-3">Verification Rejected</h2>
        <p class="text-muted fs-5 mb-4">
            Regrettably, your worker application has been rejected as it did not meet our verification criteria.
        </p>

        <div class="alert alert-danger rounded-4 border-0 shadow-sm text-start mb-4 py-3">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Common Reasons</h6>
            <ul class="mb-0 small text-secondary">
                <li>Information mismatch with Aadhaar/PAN.</li>
                <li>Blurred or invalid document uploads.</li>
                <li>Insufficient experience for selected category.</li>
            </ul>
        </div>

        <p class="small text-muted mb-4">You can contact support if you believe this is an error.</p>

        <div class="d-flex flex-column gap-3">
            <a href="<?= base_url('support') ?>" class="btn btn-danger btn-lg rounded-pill fw-bold">
                Contact Support
            </a>
            <a href="<?= base_url('dashboard') ?>" class="btn btn-light btn-lg rounded-pill fw-bold">
                Go to Dashboard
            </a>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
