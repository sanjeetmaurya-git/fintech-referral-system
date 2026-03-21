<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0" style="color:#0f172a;">All Users</h5>
        <small class="text-muted">Total: <?= count($users) ?> registered users</small>
    </div>
    <input type="text" id="userSearch" class="form-control form-control-sm w-auto"
           placeholder="&#xF52A; Search phone / referral code…" style="min-width:240px;">
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="usersTable">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Phone</th>
                        <th>Referral Code</th>
                        <th>Referred By (ID)</th>
                        <th>Device ID</th>
                        <th>IP Address</th>
                        <th>Status</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="text-muted"><?= esc($user['id']) ?></td>
                            <td class="fw-semibold"><?= esc($user['phone']) ?></td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    <?= esc($user['referral_code']) ?>
                                </span>
                            </td>
                            <td>
                                <?= $user['referred_by']
                                    ? '<span class="badge bg-indigo-subtle text-indigo">' . esc($user['referred_by']) . '</span>'
                                    : '<span class="text-muted">—</span>' ?>
                            </td>
                            <td>
                                <span class="text-muted small font-monospace">
                                    <?= $user['device_id']
                                        ? esc(substr($user['device_id'], 0, 16)) . '…'
                                        : '—' ?>
                                </span>
                            </td>
                            <td class="small text-muted"><?= esc($user['ip_address'] ?? '—') ?></td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle">Active</span>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted">
                                <?= date('d M Y', strtotime($user['created_at'])) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
document.getElementById('userSearch').addEventListener('keyup', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
<?php $this->endSection(); ?>
