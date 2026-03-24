<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>


<style>
    .login-bg{
        background: #ceded8;
    }
    
</style>

<div class="row justify-content-center mt-5 ">
    <div class="col-md-5">
        <div class="card p-4 login-bg">
            <h4 class="fw-bold mb-3 text-center">Mobile Login</h4>
            <p class="text-muted text-center mb-4">Enter your registered mobile number to receive an OTP.</p>
            
            
            
            <form action="<?= base_url('login/send-otp') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text">+91</span>
                        <input type="text" name="phone"  class="form-control" placeholder="10-digit number" required maxlength="10">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Send OTP</button>
            </form>
            
            <div class="mt-4 text-center small text-muted">
                Don't have an account? <br>
                Please register using our mobile application.
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>

