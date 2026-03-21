<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h4 class="fw-bold mb-0">Membership Orders</h4>
        <p class="text-muted small">Approve or reject premium membership upgrade requests.</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">#ID</th>
                        <th>User (Phone)</th>
                        <th>Ref Code</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Transaction Info</th>
                        <th>Status</th>
                        <th>Requested On</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">No pending orders found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $o): ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?= $o['id'] ?></td>
                            <td class="fw-bold"><?= esc($o['phone']) ?></td>
                            <td><code class="small"><?= esc($o['referral_code']) ?></code></td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?= ucfirst(esc($o['payment_type'])) ?>
                                </span>
                            </td>
                            <td>
                                <?= $o['payment_type'] === 'coins' ? esc($o['amount']) . ' Coins' : '₹' . esc($o['amount']) ?>
                            </td>
                            <td class="small">
                                <?php if ($o['payment_type'] === 'razorpay'): ?>
                                    <div class="text-nowrap">Order: <code class="small"><?= esc($o['razorpay_order_id']) ?></code></div>
                                    <div class="text-nowrap text-muted">Pay ID: <?= esc($o['razorpay_payment_id']) ?></div>
                                    <div class="text-nowrap text-muted">Payment Method: <?= esc($o['payment_type']) ?></div>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($o['status'] === 'pending'): ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3">Pending</span>
                                <?php elseif ($o['status'] === 'approved'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3">Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted"><?= date('d M Y, H:i', strtotime($o['created_at'])) ?></td>
                            <td class="text-end pe-4">
                                <?php if ($o['status'] === 'pending'): ?>
                                    <div class="d-flex justify-content-end gap-2">
                                        <form action="<?= base_url('admin/membership-order/approve/' . $o['id']) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-success px-3">Approve</button>
                                        </form>
                                        <form action="<?= base_url('admin/membership-order/reject/' . $o['id']) ?>" method="POST">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-3">Reject</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="small text-muted">Processed</span>
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

<?= $this->endSection() ?>
