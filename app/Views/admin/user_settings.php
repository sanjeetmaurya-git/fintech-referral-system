<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h4 class="fw-bold mb-0">User Membership Settings</h4>
        <p class="text-muted small">Manage user statuses and view network connections (up to Level 8).</p>
    </div>
    <div class="col-md-6">
        <input type="text" id="userSearch" class="form-control" placeholder="Search by phone or referral code...">
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="usersTable">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">User (Phone)</th>
                        <th>Referral Code</th>
                        <th>Membership</th>
                        <th>Network Count (L8)</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold"><?= esc($u['phone']) ?></div>
                                <small class="text-muted">UID #<?= $u['id'] ?></small>
                            </td>
                            <td><code class="fw-bold text-primary"><?= esc($u['referral_code']) ?></code></td>
                            <td>
                                <?php if ($u['is_premium']): ?>
                                    <span class="badge bg-primary px-3 rounded-pill">Premium</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary-subtle text-secondary border px-3 rounded-pill">Free</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold"><?= number_format($u['network_count']) ?></div>
                                <small class="text-muted small">Total Connections</small>
                            </td>
                            <td class="text-end pe-4">
                                <form action="<?= base_url('admin/user-settings/update-premium') ?>" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <input type="hidden" name="is_premium" value="<?= $u['is_premium'] ? '0' : '1' ?>">
                                    <button type="submit" class="btn btn-sm <?= $u['is_premium'] ? 'btn-outline-danger' : 'btn-primary' ?> px-3">
                                        <?= $u['is_premium'] ? 'Demote to Free' : 'Promote to Premium' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.getElementById('userSearch').addEventListener('keyup', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
<?= $this->endSection() ?>
