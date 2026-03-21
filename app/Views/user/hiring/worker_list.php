<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="container py-5">
    <div class="mb-5" data-aos="fade-up">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('hire') ?>">Hire Workers</a></li>
                <li class="breadcrumb-item active"><?= esc($category['name']) ?></li>
            </ol>
        </nav>
        <h2 class="fw-bold">Available <?= esc($category['name']) ?></h2>
        <p class="text-muted">Browse through top-rated professionals in this category.</p>
    </div>

    <div class="row g-4">
        <?php if (empty($workers)): ?>
            <div class="col-12 text-center py-5">
                <div class="glass-card p-5 rounded-5 shadow-sm border-0">
                    <i class="bi bi-person-slash fs-1 text-muted opacity-25 mb-3 d-block"></i>
                    <h4 class="text-muted">No active workers found in this category.</h4>
                    <p class="text-muted">Please check back later or try another category.</p>
                    <a href="<?= base_url('hire') ?>" class="btn btn-primary rounded-pill mt-3 px-4">Back to Categories</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($workers as $w): ?>
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="glass-card p-4 rounded-5 shadow-sm border-0 h-100">
                        <div class="d-flex gap-4">
                            <img src="<?= base_url($w['profile_image'] ?? 'assets/images/default-avatar.png') ?>" 
                                 class="rounded-circle shadow-sm border" width="100" height="100" style="object-fit: cover;">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="fw-bold mb-1"><?= esc($w['full_name']) ?></h5>
                                    <div class="text-warning">
                                        <i class="bi bi-star-fill"></i> 5.0
                                    </div>
                                </div>
                                <p class="text-muted small mb-2"><i class="bi bi-geo-alt me-1"></i><?= esc($w['district']) ?>, <?= esc($w['state']) ?></p>
                                <p class="small mb-3"><?= esc(substr($w['skills'], 0, 100)) ?>...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-3 py-2 rounded-pill small">
                                        <?= esc($w['experience']) ?> Years Exp.
                                    </span>
                                    <a href="<?= base_url('hire/details/' . $w['id']) ?>" class="btn btn-primary rounded-pill px-4 btn-sm fw-bold">Hire Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php $this->endSection(); ?>
