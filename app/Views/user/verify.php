<?= $this->extend('user/layout') ?>

<?= $this->section('content') ?>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm w-100" style="max-width: 400px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <img src="<?= base_url('assets/images/your-logo.png') ?>" alt="Logo" style="max-height: 40px;">
            </div>
            <form method="post" action="<?= base_url('login/verify') ?>">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label class="form-label small fw-bold">Enter OTP</label>
                    <input type="text" name="otp" class="form-control text-center fs-2 fw-bold" placeholder="000000" required maxlength="6" autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Account Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Create or Enter Password" required minlength="6">
                    <div class="small text-muted mt-1">If you are new, this will be your password.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Login</button>
            </form>
            
            <div class="mt-4 text-center">
                <p class="small text-muted mb-0">Didn't receive code?</p>
                <a href="<?= base_url('login') ?>" class="small fw-bold text-decoration-none">Resend OTP</a>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
