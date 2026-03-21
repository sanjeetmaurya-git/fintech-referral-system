<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="fw-bold">Find Trusted Professionals</h2>
        <p class="text-muted fs-5">Select a category to find skilled workers near you.</p>
    </div>

    <div class="row g-4">
        <?php foreach ($categories as $cat): ?>
            <div class="col-lg-4 col-md-6" data-aos="zoom-in">
                <a href="<?= base_url('hire/workers/' . $cat['id']) ?>" class="text-decoration-none h-100 d-block">
                    <div class="glass-card p-4 rounded-5 shadow-sm border-0 h-100 text-center transition-all hover-translate-y">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-4" style="width: 70px; height: 70px; font-size: 1.8rem;">
                            <i class="bi <?= $cat['icon'] ?>"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2"><?= esc($cat['name']) ?></h4>
                        <p class="text-muted small mb-0">Verified professionals ready to help you.</p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.hover-translate-y { transition: transform 0.3s; }
.hover-translate-y:hover { transform: translateY(-10px); }
</style>

<?php $this->endSection(); ?>
