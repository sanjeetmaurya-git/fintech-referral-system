<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Worker Applications</h4>
        <p class="text-muted small">Manage professional worker registrations and certifications.</p>
    </div>
    <div class="btn-group shadow-sm">
        <a href="?status=all" class="btn btn-<?= $current_status === 'all' ? 'primary' : 'outline-primary' ?> btn-sm px-3">All</a>
        <a href="?status=pending" class="btn btn-<?= $current_status === 'pending' ? 'primary' : 'outline-primary' ?> btn-sm px-3">Pending</a>
        <a href="?status=approved" class="btn btn-<?= $current_status === 'approved' ? 'primary' : 'outline-primary' ?> btn-sm px-3">Approved</a>
        <a href="?status=rejected" class="btn btn-<?= $current_status === 'rejected' ? 'primary' : 'outline-primary' ?> btn-sm px-3">Rejected</a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th class="ps-4">Worker ID</th>
                    <th>Phone</th>
                    <th>Category</th>
                    <th>Experience</th>
                    <th>Status</th>
                    <th>Joined Date</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($workers)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <span class="text-muted">No worker applications found.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($workers as $w): ?>
                        <tr>
                            <td class="ps-4 fw-medium text-primary">#WRK-<?= $w['id'] ?></td>
                            <td><?= esc($w['phone']) ?></td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <?= esc($w['category_name'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td><?= esc($w['experience']) ?> Years</td>
                            <td>
                                <?php if ($w['status'] === 'pending'): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending</span>
                                <?php elseif ($w['status'] === 'approved'): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($w['created_at'])) ?></td>
                            <td class="text-end pe-4">
                                <a href="<?= base_url('admin/workers/view/' . $w['id']) ?>" class="btn btn-indigo btn-sm rounded-pill px-3" style="background: #e0e7ff; color: #4338ca;">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
