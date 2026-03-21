<?php $this->extend('admin/layout') ?>

<?php $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="mb-4">
        <h2 class="fw-bold">Service Transaction Approvals</h2>
        <p class="text-muted">Verify and approve service usage to release coin rewards to users and their referrers.</p>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light border-0">
                        <tr>
                            <th class="px-4 py-3 border-0">User</th>
                            <th class="py-3 border-0">Service</th>
                            <th class="py-3 border-0">Platform/Operator</th>
                            <th class="py-3 border-0">Amount</th>
                            <th class="py-3 border-0">Coins To Earn</th>
                            <th class="py-3 border-0">Status</th>
                            <th class="px-4 py-3 border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-invoices fs-1 d-block mb-3"></i>
                                No pending service transactions found.
                            </td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php foreach ($transactions as $tx): ?>
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold"><?= $tx['username'] ?></div>
                                <div class="text-muted small"><?= $tx['user_phone'] ?></div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-opacity-10 <?= $tx['service_type'] === 'recharge' ? 'bg-primary text-primary' : 'bg-success text-success' ?> text-capitalize">
                                    <?= $tx['service_type'] ?>
                                </span>
                            </td>
                            <td class="py-3">
                                <?= $tx['service_type'] === 'recharge' ? $tx['operator_name'] : $tx['platform_name'] ?>
                            </td>
                            <td class="py-3 fw-bold">₹<?= number_format($tx['amount'], 2) ?></td>
                            <td class="py-3 text-primary fw-bold"><?= $tx['coins_earned'] ?> Coins</td>
                            <td class="py-3">
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Pending Verification</span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <form action="<?= base_url('admin/services/transactions/approve/' . $tx['id']) ?>" method="POST" class="d-inline">
                                    <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm" onclick="return confirm('Confirm reward distribution for this service?')">
                                        Approve & Credit
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
