<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar / Profile Card -->
        <div class="col-lg-4">
            <div class="glass-card p-4 rounded-5 shadow-sm border-0 mb-4" data-aos="fade-right">
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                        <img src="<?= base_url($worker['profile_image'] ?? 'assets/images/default-avatar.png') ?>" 
                             class="rounded-circle shadow border p-1" width="120" height="120" style="object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 p-2 bg-<?= $worker['is_online'] ? 'success' : 'secondary' ?> border border-white rounded-circle shadow-sm" style="width: 25px; height: 25px;"></span>
                    </div>
                    <h4 class="fw-bold mt-3 mb-1"><?= esc($worker['full_name']) ?></h4>
                    <p class="text-muted small mb-3"><?= esc($worker['category_name']) ?> | <?= esc($worker['experience']) ?> Years Exp.</p>
                    
                    <div class="d-flex justify-content-center align-items-center gap-3 py-2 px-3 glass-card rounded-pill bg-light border-0">
                        <span class="fw-bold small text-<?= $worker['is_online'] ? 'success' : 'secondary' ?>">
                            <?= $worker['is_online'] ? 'ACTIVE & ONLINE' : 'OFFLINE' ?>
                        </span>
                        <div class="form-check form-switch p-0 m-0">
                            <input class="form-check-input ms-0" type="checkbox" id="statusSwitch" <?= $worker['is_online'] ? 'checked' : '' ?> style="width: 3rem; height: 1.5rem; cursor: pointer;">
                        </div>
                    </div>
                </div>

                <hr class="opacity-10">

                <div class="list-group list-group-flush gap-2">
                    <div class="d-flex justify-content-between small px-2">
                        <span class="text-muted">ID:</span>
                        <span class="fw-bold">#WRK-<?= $worker['id'] ?></span>
                    </div>
                    <div class="d-flex justify-content-between small px-2">
                        <span class="text-muted">Region:</span>
                        <span class="fw-bold"><?= esc($worker['district']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between small px-2">
                        <span class="text-muted">Sub Skill:</span>
                        <span class="fw-bold"><?= esc($worker['subcategory_name']) ?></span>
                    </div>
                </div>
            </div>

            <div class="glass-card p-4 rounded-5 shadow-sm border-0" data-aos="fade-right" data-aos-delay="100">
                <h6 class="fw-bold mb-3"><i class="bi bi-award me-2"></i>Professional Badges</h6>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill">Verified Expert</span>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Top Rated</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Stats -->
            <div class="row g-4 mb-4">
                <div class="col-sm-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="glass-card p-4 rounded-5 shadow-sm border-0 d-flex align-items-center gap-3">
                        <div class="icon-box bg-purple bg-opacity-10 text-purple" style="width: 50px; height: 50px; font-size: 1.5rem; color:#6366f1;">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">0</h3>
                            <p class="text-muted small mb-0">Jobs Completed</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="glass-card p-4 rounded-5 shadow-sm border-0 d-flex align-items-center gap-3">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning" style="width: 50px; height: 50px; font-size: 1.5rem;">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">5.0</h3>
                            <p class="text-muted small mb-0">Avg. Rating</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Requests -->
            <div class="glass-card p-4 rounded-5 shadow-sm border-0" data-aos="fade-up" data-aos-delay="400">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0"><i class="bi bi-bell-fill me-2 text-primary"></i>Nearby Job Requests</h5>
                    <a href="#" class="btn btn-light btn-sm rounded-pill px-3">View History</a>
                </div>

                <?php if (empty($jobRequests)): ?>
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-search fs-1 text-muted opacity-25"></i>
                        </div>
                        <h6 class="text-muted">Waiting for your first job request...</h6>
                        <p class="small text-muted px-4">Keep your status <span class="badge bg-success">Online</span> to receive new job notifications.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="small text-muted text-uppercase">
                                    <th>Customer</th>
                                    <th>Requirement</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jobRequests as $job): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold">Client #<?= $job['user_id'] ?></div>
                                            <div class="small text-muted"><?= date('d M, H:i', strtotime($job['created_at'])) ?></div>
                                        </td>
                                        <td><?= esc(substr($job['description'], 0, 50)) ?>...</td>
                                        <td>
                                            <span class="badge rounded-pill bg-<?= $job['status'] === 'requested' ? 'warning text-dark' : 'success' ?>">
                                                <?= ucfirst($job['status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3">View</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('statusSwitch').addEventListener('change', function() {
    fetch('<?= base_url('worker/toggle-status') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
});
</script>

<?php $this->endSection(); ?>
