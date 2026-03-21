<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>

<?php
$statusFilters = [
    'all'      => ['label' => 'All',      'color' => 'secondary'],
    'pending'  => ['label' => 'Pending',  'color' => 'warning'],
    'approved' => ['label' => 'Approved', 'color' => 'success'],
    'rejected' => ['label' => 'Rejected', 'color' => 'danger'],
];
$current = $current_status ?? 'all';
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0" style="color:#0f172a;">Transactions</h5>
        <small class="text-muted">Manage referral reward payments</small>
    </div>
    <!-- Status Filter Tabs -->
    <div class="btn-group btn-group-sm" role="group">
        <?php foreach ($statusFilters as $key => $meta): ?>
            <a href="<?= base_url('admin/transactions' . ($key !== 'all' ? '?status=' . $key : '')) ?>"
               class="btn btn-outline-<?= $meta['color'] ?> <?= $current === $key ? 'active' : '' ?>">
                <?= $meta['label'] ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>User ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No transactions found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $tx): ?>
                        <tr>
                            <td class="text-muted"><?= esc($tx['id']) ?></td>
                            <td><?= esc($tx['user_id']) ?></td>
                            <td>
                                <?php if ($tx['type'] === 'credit'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="bi bi-arrow-down-circle me-1"></i>Credit
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                        <i class="bi bi-arrow-up-circle me-1"></i>Debit
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="fw-semibold">₹<?= number_format($tx['amount'], 2) ?></td>
                            <td><span class="font-monospace small text-muted"><?= esc($tx['reference_id'] ?? '—') ?></span></td>
                            <td class="small text-muted" style="max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <?= esc($tx['description'] ?? '—') ?>
                            </td>
                            <td>
                                <?php
                                $statusMap = [
                                    'pending'  => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                ];
                                $color = $statusMap[$tx['status']] ?? 'secondary';
                                ?>
                                <span class="badge rounded-pill bg-<?= $color ?>-subtle text-<?= $color ?> border border-<?= $color ?>-subtle">
                                    <?= ucfirst(esc($tx['status'])) ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <?= isset($tx['created_at']) ? date('d M Y', strtotime($tx['created_at'])) : '—' ?>
                            </td>
                            <td>
                                <?php if ($tx['status'] === 'pending'): ?>
                                    <form method="post"
                                          action="<?= base_url('admin/approve/' . $tx['id']) ?>"
                                          class="d-inline"
                                          onsubmit="return confirm('Approve this reward?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-success btn-sm py-0 px-2">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form method="post"
                                          action="<?= base_url('admin/reject/' . $tx['id']) ?>"
                                          class="d-inline"
                                          onsubmit="return confirm('Reject this reward?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm py-0 px-2">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
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
