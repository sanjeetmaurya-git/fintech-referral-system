<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0" style="color:#0f172a;">Withdrawal Requests</h5>
        <small class="text-muted">Review and process user payouts</small>
    </div>
</div>

<form action="<?= base_url('admin/withdrawals/batch') ?>" method="POST" id="batchForm">
    <?= csrf_field() ?>
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll">
                <label class="form-check-label small fw-bold text-muted ms-2" for="selectAll">SELECT ALL</label>
            </div>
            <div id="batchActions" style="display:none;">
                <button type="submit" name="action" value="approve" class="btn btn-success btn-sm rounded-pill px-3 me-2" onclick="return confirm('Approve all selected requests?')">
                    <i class="bi bi-check-circle me-1"></i> Approve Selected
                </button>
                <button type="submit" name="action" value="export" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    <i class="bi bi-download me-1"></i> Export Selected (CSV)
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="40"></th>
                            <th>#ID</th>
                            <th>User ID</th>
                            <th>Amount</th>
                            <th>Payment Details</th>
                            <th>Status</th>
                            <th>Requested On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($withdrawals)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No withdrawal requests found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($withdrawals as $w): ?>
                            <tr>
                                <td>
                                    <?php if ($w['status'] === 'pending'): ?>
                                        <input class="form-check-input row-select" type="checkbox" name="ids[]" value="<?= $w['id'] ?>">
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted"><?= esc($w['id']) ?></td>
                                <td class="fw-semibold"><?= esc($w['user_id']) ?></td>
                                <td class="fw-bold">₹<?= number_format($w['amount'], 2) ?></td>
                                <td class="small">
                                    <span class="text-muted"><?= nl2br(esc($w['payment_details'])) ?></span>
                                </td>
                                <td>
                                    <?php
                                    $statusMap = [
                                        'pending'   => 'warning',
                                        'completed' => 'success',
                                        'rejected'  => 'danger',
                                    ];
                                    $color = $statusMap[$w['status']] ?? 'secondary';
                                    ?>
                                    <span class="badge rounded-pill bg-<?= $color ?>-subtle text-<?= $color ?> border border-<?= $color ?>-subtle">
                                        <?= ucfirst(esc($w['status'])) ?>
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    <?= date('d M Y, H:i', strtotime($w['created_at'])) ?>
                                </td>
                                <td>
                                    <?php if ($w['status'] === 'pending'): ?>
                                        <button type="button" class="btn btn-success btn-sm py-0 px-2" title="Approve" onclick="approveSingle(<?= $w['id'] ?>)">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
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
</form>

<script>
document.getElementById('selectAll').addEventListener('change', function() {
    const isChecked = this.checked;
    document.querySelectorAll('.row-select').forEach(cb => {
        cb.checked = isChecked;
    });
    toggleBatchActions();
});

document.querySelectorAll('.row-select').forEach(cb => {
    cb.addEventListener('change', toggleBatchActions);
});

function toggleBatchActions() {
    const checkedCount = document.querySelectorAll('.row-select:checked').length;
    document.getElementById('batchActions').style.display = checkedCount > 0 ? 'block' : 'none';
}

function approveSingle(id) {
    if(confirm('Approve this request?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('admin/withdrawals/batch') ?>';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'ids[]';
        idInput.value = id;
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'approve';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';

        form.appendChild(idInput);
        form.appendChild(actionInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php $this->endSection(); ?>
