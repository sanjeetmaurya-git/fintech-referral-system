<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="container py-5">
    <div class="row g-4">
        <!-- Worker Profile -->
        <div class="col-lg-8">
            <div class="glass-card p-4 p-md-5 rounded-5 shadow-lg border-0 mb-4" data-aos="fade-up">
                <div class="d-flex flex-column flex-md-row gap-4 align-items-center align-items-md-start mb-5">
                    <img src="<?= base_url($worker['profile_image'] ?? 'assets/images/default-avatar.png') ?>" 
                         class="rounded-circle shadow border p-1" width="150" height="150" style="object-fit: cover;">
                    <div class="text-center text-md-start">
                        <div class="d-flex flex-column flex-md-row align-items-center gap-3 mb-2">
                            <h2 class="fw-bold mb-0"><?= esc($worker['full_name']) ?></h2>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill small">
                                <i class="bi bi-patch-check-fill me-1"></i> Verified Pro
                            </span>
                        </div>
                        <p class="text-muted fs-5 mb-3"><?= esc($worker['category_name']) ?> | <?= esc($worker['subcategory_name']) ?></p>
                        <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-4 small">
                            <span><i class="bi bi-star-fill text-warning me-1"></i> 5.0 (24 Reviews)</span>
                            <span><i class="bi bi-briefcase text-primary me-1"></i> <?= esc($worker['experience']) ?> Years Exp.</span>
                            <span><i class="bi bi-geo-alt text-danger me-1"></i> <?= esc($worker['district']) ?></span>
                        </div>
                    </div>
                </div>

                <div class="mb-5">
                    <h5 class="fw-bold mb-3">About the Expert</h5>
                    <p class="text-muted fs-6 lh-lg">
                        <?= nl2br(esc($worker['skills'])) ?>
                    </p>
                </div>

                <div class="mb-5">
                    <h5 class="fw-bold mb-3">Professional Qualifications</h5>
                    <div class="p-4 bg-light bg-opacity-50 rounded-4 border border-white">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Education</span>
                                <span class="fw-medium"><?= esc($worker['highest_qualification']) ?></span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted small fw-bold text-uppercase d-block mb-1">Mobile</span>
                                <span class="fw-medium"><?= substr($worker['phone'], 0, 2) . 'XXXXXX' . substr($worker['phone'], -2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info rounded-4 border-0 shadow-sm d-flex gap-3 p-4">
                    <i class="bi bi-info-circle-fill fs-3"></i>
                    <div>
                        <h6 class="fw-bold mb-1">Security Note</h6>
                        <p class="small mb-0 opacity-75">All payments should be discussed and agreed upon through the portal or via direct communication only after booking. Avoid sharing sensitive data beforehand.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hiring Form -->
        <div class="col-lg-4">
            <div class="glass-card p-4 rounded-5 shadow-lg border-0 sticky-top" style="top: 100px;" data-aos="fade-left">
                <h4 class="fw-bold mb-4">Book Service</h4>
                
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success rounded-4 border-0 mb-4">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger rounded-4 border-0 mb-4">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('hire/request') ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="worker_id" value="<?= $worker['id'] ?>">
                    <input type="hidden" name="category_id" value="<?= $worker['category_id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Project Description</label>
                        <textarea name="description" class="form-control rounded-4 p-3" rows="4" placeholder="Describe what you need help with..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase">Expected Budget (₹)</label>
                        <input type="number" name="budget" class="form-control rounded-pill px-4" placeholder="e.g. 500" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase">Service Location</label>
                        <input type="text" name="location" class="form-control rounded-pill px-4" placeholder="Street, Landmark..." required>
                    </div>

                    <div class="d-grid shadow-lg">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">
                            Send Booking Request <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                    
                    <p class="text-center text-muted small mt-4">
                        The expert will review your request and contact you directly.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
