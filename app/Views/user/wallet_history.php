<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<div class="row g-4 mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-0">My Coin Journey</h4>
        <p class="text-muted small">Tracking every coin and rupee you've earned.</p>
    </div>

    <!-- Summary Cards -->
    <div class="col-md-6 col-lg-4">
        <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
            <div class="d-flex justify-content-between mb-3">
                <i class="bi bi-wallet2 fs-2"></i>
                <span class="badge bg-white bg-opacity-25 rounded-pill">Total Balance</span>
            </div>
            <h2 class="fw-bold">₹<?= number_format($wallet['balance'], 2) ?></h2>
            <div class="small">Available for withdrawal</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
            <div class="d-flex justify-content-between mb-3">
                <i class="bi bi-coin fs-2"></i>
                <span class="badge bg-white bg-opacity-25 rounded-pill">Coin Stash</span>
            </div>
            <h2 class="fw-bold"><?= number_format($wallet['coins'], 2) ?></h2>
            <div class="small">Ready to be redeemed</div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 ps-4 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Unified Transaction History</h6>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Filter by Type
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="filterTx('all')">All Transactions</a></li>
                <li><a class="dropdown-item" href="#" onclick="filterTx('REF')">Referral Rewards</a></li>
                <li><a class="dropdown-item" href="#" onclick="filterTx('REDEEM')">Redemptions</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="txTable">
                <thead class="bg-light">
                    <tr class="small text-uppercase text-muted">
                        <th class="ps-4">Date</th>
                        <th>Type</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th class="text-end pe-4">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">No transactions found. Start earning!</td></tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $tx): ?>
                        <tr class="tx-row" data-ref="<?= esc($tx['reference_id']) ?>">
                            <td class="ps-4 small text-muted"><?= date('d M, Y h:i A', strtotime($tx['created_at'])) ?></td>
                            <td>
                                <?php 
                                    $isCoin = strpos($tx['description'] ?? '', 'Coins') !== false || strpos($tx['reference_id'] ?? '', 'REFC') !== false;
                                    $isDebit = $tx['type'] === 'debit';
                                ?>
                                <span class="badge rounded-pill <?= $isCoin ? 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' : 'bg-success-subtle text-success-emphasis border border-success-subtle' ?>" style="font-size: 0.7rem;">
                                    <?= $isCoin ? 'COIN' : 'CASH' ?>
                                </span>
                            </td>
                            <td class="small fw-medium"><?= esc($tx['reference_id']) ?></td>
                            <td class="small text-wrap" style="max-width: 250px;"><?= esc($tx['description']) ?></td>
                            <td class="text-end pe-4 fw-bold <?= $isDebit ? 'text-danger' : 'text-success' ?>">
                                <?= $isDebit ? '-' : '+' ?><?= number_format($tx['amount'], 2) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterTx(type) {
    const rows = document.querySelectorAll('.tx-row');
    rows.forEach(row => {
        if (type === 'all') {
            row.style.display = '';
        } else if (row.getAttribute('data-ref').includes(type)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

<?= $this->endSection() ?>
