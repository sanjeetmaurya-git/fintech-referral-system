<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="icon-box" style="background:#ede9fe;">
                <i class="bi bi-people-fill" style="color:#7c3aed;"></i>
            </div>
            <div class="value"><?= number_format($stats['total_users']) ?></div>
            <div class="label">Total System Users</div>
            <?php $url = base_url();
            // echo $url; ?>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="icon-box w-100" style="background:#ede9fe;">
                <!-- <i class="bi bi-people-fill" style="color:#7c3aed;"></i> -->
                <i class="bi bi-star-fill me-1 text-warning">premium</i>
            </div>
            
            <div class="value"><?= number_format($stats['total_premium'] ?? 0) ?></div>
            <div class="label">Total Premium Users</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="icon-box" style="background:#dcfce7;">
                <i class="bi bi-currency-exchange" style="color:#16a34a;"></i>
            </div>
            <div class="value">₹<?= number_format($stats['total_revenue'], 2) ?></div>
            <div class="label">Total System Revenue</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="icon-box" style="background:#fef9c3;">
                <i class="bi bi-coin" style="color:#ca8a04;"></i>
            </div>
            <div class="value"><?= number_format($stats['total_coins'], 2) ?></div>
            <div class="label">Total Coins in Circulation</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="icon-box" style="background:#dbeafe;">
                <i class="bi bi-gift-fill" style="color:#2563eb;"></i>
            </div>
            <div class="value">₹<?= number_format($stats['total_rewards'], 2) ?></div>
            <div class="label">Total Rewards Issued</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Main Growth Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 ps-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Registration & Payout Trends (7 Days)</h6>
            </div>
            <div class="card-body">
                <canvas id="growthChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <!-- Referral Density -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 ps-4">
                <h6 class="fw-bold mb-0">Referral Distribution</h6>
            </div>
            <div class="card-body">
                <canvas id="densityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Fraud Alerts -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 ps-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Security Alerts (Recent Fraud Attempts)</h6>
                <span class="badge bg-danger">LIVE</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead>
                            <tr>
                                <th class="ps-4">Timestamp</th>
                                <th>User/Type</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fraud_attempts as $f): ?>
                            <tr>
                                <td class="ps-4 text-muted"><?= date('h:i A, d M', strtotime($f['created_at'])) ?></td>
                                <td>
                                    <?php 
                                        $type = explode(':', $f['description'])[0];
                                        $color = strpos($type, 'VELOCITY') !== false ? 'warning' : 'danger';
                                    ?>
                                    <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?>"><?= $type ?></span>
                                </td>
                                <td><?= esc(substr($f['description'], strpos($f['description'], ':') + 1)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($fraud_attempts)): ?>
                                <tr><td colspan="3" class="text-center py-4 text-muted">No security threats detected. System is safe.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Top Referrers -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 py-3 ps-4">
                <h6 class="fw-bold mb-0">Top Active Advocates</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($top_referrers as $top): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <div class="fw-bold">ID #<?= $top['id'] ?></div>
                            <small class="text-muted"><?= esc($top['phone']) ?></small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary rounded-pill"><?= $top['referral_count'] ?> Refs</span>
                            <div class="small text-success fw-bold" style="font-size: 0.7rem;"><?= $top['premium_count'] ?> Premium</div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Admin Activity Log -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 ps-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Admin Activity Log (Audit)</h6>
                    <span class="badge bg-secondary">INTERNAL</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead>
                                <tr>
                                    <th class="ps-4">Timestamp</th>
                                    <th>Action</th>
                                    <th>Administrator</th>
                                    <th>Description</th>
                                    <th class="pe-4">IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($audit_logs as $log): ?>
                                <tr>
                                    <td class="ps-4 text-muted small"><?= date('M d, h:i A', strtotime($log['created_at'])) ?></td>
                                    <td>
                                        <?php 
                                            $color = 'primary';
                                            if (strpos($log['action'], 'REJECT') !== false) $color = 'danger';
                                            if (strpos($log['action'], 'UPDATE') !== false) $color = 'warning';
                                        ?>
                                        <span class="badge bg-<?= $color ?>-subtle text-<?= $color ?>"><?= $log['action'] ?></span>
                                    </td>
                                    <td>Admin #<?= $log['admin_id'] ?></td>
                                    <td class="text-truncate" style="max-width: 300px;"><?= esc($log['description']) ?></td>
                                    <td class="pe-4 font-monospace small text-muted"><?= $log['ip_address'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($audit_logs)): ?>
                                    <tr><td colspan="5" class="text-center py-4 text-muted">No administrative activity recorded yet.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Growth & Revenue Chart
const ctxGrowth = document.getElementById('growthChart').getContext('2d');
new Chart(ctxGrowth, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_keys($charts['registration'])) ?>,
        datasets: [{
            label: 'New Registrations',
            data: <?= json_encode(array_values($charts['registration'])) ?>,
            borderColor: '#7c3aed',
            tension: 0.3,
            fill: true,
            backgroundColor: 'rgba(124, 58, 237, 0.1)'
        }, {
            label: 'Revenue (₹)',
            data: <?= json_encode(array_values($charts['revenue'])) ?>,
            borderColor: '#16a34a',
            tension: 0.3,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, title: { display: true, text: 'Users' } },
            y1: { position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Amount (₹)' } }
        }
    }
});

// Level Distribution Chart
const ctxDensity = document.getElementById('densityChart').getContext('2d');
new Chart(ctxDensity, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_keys($charts['distribution'])) ?>,
        datasets: [{
            data: <?= json_encode(array_values($charts['distribution'])) ?>,
            backgroundColor: ['#4f46e5', '#6366f1', '#818cf8', '#a5b4fc', '#c7d2fe', '#e0e7ff', '#f1f5f9', '#f8fafc']
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>

<?= $this->endSection() ?>
