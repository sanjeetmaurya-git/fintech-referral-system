<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Support Helpdesk</h4>
    <a href="<?= base_url('support/create') ?>" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-circle me-2"></i>New Ticket
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0 shadow-sm small mb-4"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Ticket ID</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td class="ps-4">#<?= $t['id'] ?></td>
                        <td>
                            <div class="fw-bold"><?= esc($t['subject']) ?></div>
                            <span class="badge bg-<?= $t['priority'] === 'high' || $t['priority'] === 'urgent' ? 'danger' : 'secondary' ?> small-badge" style="font-size: 0.65rem;">
                                <?= strtoupper($t['priority']) ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                                $statusClass = 'secondary';
                                if ($t['status'] === 'open') $statusClass = 'warning';
                                if ($t['status'] === 'resolved') $statusClass = 'success';
                                if ($t['status'] === 'pending_user') $statusClass = 'primary';
                            ?>
                            <span class="badge bg-<?= $statusClass ?> rounded-pill px-3">
                                <?= ucwords(str_replace('_', ' ', $t['status'])) ?>
                            </span>
                        </td>
                        <td class="text-muted small"><?= date('d M, h:i A', strtotime($t['updated_at'])) ?></td>
                        <td class="text-end pe-4">
                            <a href="<?= base_url('support/view/' . $t['id']) ?>" class="btn btn-light btn-sm rounded-pill px-3">View Chat</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($tickets)): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">No support tickets found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
