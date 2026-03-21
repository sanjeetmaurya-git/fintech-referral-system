<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Notifications</h4>
        </div>

        <?php if (empty($notifications)): ?>
            <div class="card p-5 text-center shadow-sm rounded-4">
                <div class="text-muted mb-3">
                    <i class="bi bi-bell-slash display-4"></i>
                </div>
                <h5>All quiet here!</h5>
                <p class="text-muted">You don't have any notifications at the moment.</p>
            </div>
        <?php else: ?>
            <div class="list-group shadow-sm rounded-4 overflow-hidden border-0">
                <?php foreach ($notifications as $n): ?>
                    <div class="list-group-item list-group-item-action border-0 py-3 border-bottom <?= $n['is_read'] ? 'bg-white' : 'bg-light' ?>" style="transition: all 0.2s;">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="icon-box rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 45px; height: 45px; background: <?= $n['type'] === 'reward' ? '#dcfce7' : ($n['type'] === 'support' ? '#dbeafe' : '#f1f5f9') ?>;">
                                    <i class="bi <?= esc($n['icon']) ?> fs-5" 
                                       style="color: <?= $n['type'] === 'reward' ? '#16a34a' : ($n['type'] === 'support' ? '#2563eb' : '#64748b') ?>;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-1 fw-bold"><?= esc($n['title']) ?></h6>
                                    <small class="text-muted"><?= date('d M, h:i A', strtotime($n['created_at'])) ?></small>
                                </div>
                                <p class="mb-0 text-muted small"><?= esc($n['message']) ?></p>
                            </div>
                            <?php if (!$n['is_read']): ?>
                                <div class="flex-shrink-0 align-self-center">
                                    <span class="badge bg-primary rounded-circle p-1" style="width: 8px; height: 8px; display: block;"></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
