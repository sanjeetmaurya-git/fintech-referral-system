<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="row g-4 mb-4">
    <!-- Welcome Header -->
    <div class="col-12" data-aos="fade-down">
        <div class="glass-card p-4 rounded-5 shadow-sm border-0 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle bg-white shadow-sm d-flex align-items-center justify-content-center rounded-circle" style="width: 60px; height: 60px;">
                    <i class="bi bi-person-circle fs-2 text-primary"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">Welcome, <?= esc($profile['full_name'] ?? 'User') ?>!</h3>
                    <p class="text-muted small mb-0"><i class="bi bi-phone me-1"></i><?= esc($user['phone']) ?></p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <?php if ($user['is_premium'] == 1): ?>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold"><i class="bi bi-star-fill me-1"></i>PREMIUM</span>
                <?php else: ?>
                    <button class="btn premium-btn btn-sm fw-bold px-4 py-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#upgradeModal">
                        <i class="bi bi-lightning-charge-fill me-1"></i> Upgrade to Premium
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Balance Card -->
    <div class="col-12 col-md-6" data-aos="fade-right" data-aos-delay="100">
        <div class="card p-4 h-100 bg-white border-0 shadow-sm rounded-5 overflow-hidden position-relative">
            <div class="position-absolute top-0 end-0 p-4 opacity-10">
                <i class="bi bi-wallet2 display-1 text-primary"></i>
            </div>
            <span class="text-muted text-uppercase small fw-bold tracking-wider">Wallet Balance</span>
            <div class="mt-2">
                <h1 class="display-5 fw-bold mb-0 text-primary">₹<?= number_format($wallet['balance'] ?? 0, 2) ?></h1>
            </div>
            <div class="mt-4 d-flex gap-2">
                <a href="<?= base_url('withdraw') ?>" class="btn btn-primary px-4">Withdraw Now</a>
            </div>
            <div class="mt-3 small text-muted">
                <i class="bi bi-info-circle me-1"></i> Updates instantly after approval.
            </div>
        </div>
    </div>

    <!-- Referral Link Card -->
    <div class="col-12 col-md-6" data-aos="fade-left" data-aos-delay="200">
        <div class="card p-4 h-100 border-0 shadow-sm rounded-5 text-white" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
            <div class="position-absolute top-0 end-0 p-4 opacity-20">
                <i class="bi bi-share display-1"></i>
            </div>
            <span class="text-white-50 text-uppercase small fw-bold">Share & Earn</span>
            <h3 class="fw-bold mt-2 mb-3"><?= esc($user['referral_code']) ?></h3>
            <p class="small opacity-75 mb-3">Copy your referral link and start building your network!</p>
            <div class="input-group glass-input-group">
                <input type="text" class="form-control bg-white bg-opacity-20 border-0 rounded-start-pill px-3" value="<?= base_url('join/' . $user['referral_code']) ?>" id="refLink" readonly>
                <button class="btn btn-light rounded-end-pill fw-bold px-3" onclick="copyLink()">
                    <i class="bi bi-copy me-1"></i>Copy
                </button>
            </div>
        </div>
    </div>

    <!-- Coin Balance Card -->
    <div class="col-12" data-aos="zoom-in" data-aos-delay="300">
        <div class="position-relative">
            <div class="card p-4 shadow-sm <?= $user['is_premium'] == 0 ? 'blur-content' : '' ?>" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; border: none; border-radius: 30px;">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <span class="text-white-50 small fw-bold text-uppercase">Coin Balance</span>
                        <h2 class="fw-bold mb-0"><i class="bi bi-coin me-2"></i><?= number_format($wallet['coins'] ?? 0, 2) ?> Coins</h2>
                        <div class="small fw-medium opacity-75 mt-1">5 Coins = ₹1 (Min 20 Coins)</div>
                    </div>
                    <?php if ($user['is_premium'] == 1): ?>
                        <button class="btn btn-light fw-bold px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#redeemModal">
                            <i class="bi bi-arrow-repeat me-2"></i>Redeem Now
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($user['is_premium'] == 0): ?>
                <div class="locked-overlay d-flex flex-column align-items-center justify-content-center rounded-5">
                    <div class="text-center p-3">
                        <div class="bg-white bg-opacity-20 rounded-circle d-inline-block p-2 mb-2">
                            <i class="bi bi-lock-fill fs-2 text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Premium Feature Locked</h5>
                        <p class="small opacity-75 mb-3">Upgrade to Premium to redeem coins for cash.</p>
                        <button class="btn premium-btn btn-sm fw-bold px-5 py-2 mt-2 rounded-pill shadow" data-bs-toggle="modal" data-bs-target="#upgradeModal" style="margin: 6pxpx;">
                            <i class="bi bi-lightning-charge-fill me-1"></i> Get Premium
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- My Network -->
    <div class="col-12 col-lg-8" data-aos="fade-up" data-aos-delay="400">
        <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
            <div class="px-4 py-3 border-bottom bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">My Network</h6>
                <span class="badge bg-primary rounded-pill px-3"><?= count($referrals) ?> Referrals</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 text-muted small border-0">Phone Number</th>
                            <th class="px-4 py-3 text-muted small border-0">Joined Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($referrals)): ?>
                            <tr><td colspan="2" class="text-center py-5 text-muted">No referrals yet. Start sharing to earn!</td></tr>
                        <?php else: ?>
                            <?php foreach ($referrals as $ref): ?>
                                <tr>
                                    <td class="px-4 py-3 fw-medium">XXXXXX<?= substr($ref['phone'], -4) ?></td>
                                    <td class="px-4 py-3 text-muted small"><?= date('d M Y', strtotime($ref['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent History -->
    <div class="col-12 col-lg-4" data-aos="fade-up" data-aos-delay="500">
        <div class="card border-0 shadow-sm rounded-5 overflow-hidden">
            <div class="px-4 py-3 border-bottom bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Recent Activity</h6>
                <a href="<?= base_url('wallet-history') ?>" class="small text-decoration-none fw-bold">View All</a>
            </div>
            <div class="list-group list-group-flush">
                <?php if (empty($withdrawals) && empty($transactions)): ?>
                    <div class="p-5 text-center text-muted small">No recent activity.</div>
                <?php else: ?>
                    <?php 
                        $all_actions = array_merge(
                            array_map(function($w) { $w['type'] = 'withdrawal'; return $w; }, $withdrawals),
                            array_map(function($t) { $t['type'] = 'transaction'; return $t; }, $transactions)
                        );
                        usort($all_actions, function($a, $b) { return strtotime($b['created_at']) - strtotime($a['created_at']); });
                        $latest_actions = array_slice($all_actions, 0, 5);
                    ?>
                    <?php foreach ($latest_actions as $action): ?>
                        <div class="list-group-item px-4 py-3 border-bottom-0">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="small fw-bold d-block"><?= $action['type'] === 'withdrawal' ? 'Withdrawal' : 'Reward' ?></span>
                                    <span class="text-muted" style="font-size: 0.7rem;"><?= date('d M, Y', strtotime($action['created_at'])) ?></span>
                                </div>
                                <div class="text-end">
                                    <span class="small fw-bold <?= $action['type'] === 'withdrawal' ? 'text-danger' : 'text-success' ?>">
                                        <?= $action['type'] === 'withdrawal' ? '-' : '+' ?>₹<?= number_format($action['amount'], 2) ?>
                                    </span>
                                    <span class="d-block badge rounded-pill <?= $action['status'] === 'completed' ? 'bg-success' : ($action['status'] === 'rejected' ? 'bg-danger' : 'bg-warning') ?>" style="font-size: 0.6rem;">
                                        <?= ucfirst($action['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Upgrade Modal -->
<div class="modal fade" id="upgradeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Upgrade to Premium</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('upgrade-premium') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body px-4 model-style">
                    <p class="text-muted small mb-4">Unlock <b>unlimited withdrawals</b> and <b>instant coin redemptions</b>.</p>
                    
                    <div class="form-check p-3 mb-3 bg-light rounded-4 pay-check-div">
                        <input class="form-check-input ms-0 me-3 pay-check" type="radio" name="payment_type" value="wallet" id="payWallet" checked>
                        <label class="form-check-label w-100 cursor-pointer" for="payWallet">
                            <div class="fw-bold">Pay via Wallet</div>
                            <div class="small text-muted">Cost: ₹200.00</div>
                        </label>
                    </div>

                    <div class="form-check p-3 mb-3 bg-light rounded-4 pay-check-div">
                        <input class="form-check-input ms-0 me-3 pay-check" type="radio" name="payment_type" value="coins" id="payCoins">
                        <label class="form-check-label w-100 cursor-pointer" for="payCoins">
                            <div class="fw-bold">Pay via Coins</div>
                            <div class="small text-muted">Cost: 1,000 Coins</div>
                        </label>
                    </div>

                    <div class="form-check p-3 bg-light rounded-4">
                        <input class="form-check-input ms-0 me-3 pay-check pay-check-div" type="radio" name="payment_type" value="razorpay" id="payRazorpay">
                        <label class="form-check-label w-100 cursor-pointer" for="payRazorpay">
                            <div class="fw-bold text-primary">Pay via Razorpay</div>
                            <div class="small text-muted">Cost: ₹200.00 (Instant)</div>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Later</button>
                    <button type="submit" id="upgradeBtn" class="btn btn-primary rounded-pill px-4">Upgrade Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Redeem Modal -->
<div class="modal fade" id="redeemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-5 shadow">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Redeem Coins</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('redeem-coins') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body px-4 text-center">
                    <div class="bg-warning bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                        <i class="bi bi-coin fs-1 text-warning"></i>
                    </div>
                    <h3 class="fw-bold text-warning mb-1"><?= number_format($wallet['coins'] ?? 0, 2) ?></h3>
                    <p class="text-muted small mb-4">Available Coins</p>
                    <div class="text-start">
                        <label class="form-label small fw-bold">Amount to Redeem</label>
                        <input type="number" name="coins" class="form-control rounded-4 p-3" min="20" max="<?= (int)$wallet['coins'] ?>" step="1" required placeholder="Min 20 coins">
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Redeem Coins</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.tracking-wider { letter-spacing: 0.1em; }
.blur-content {
    filter: blur(1px);
    pointer-events: none;
    user-select: none;
}
.locked-overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(1px);
    color: white;
    z-index: 10;
}
.cursor-pointer { cursor: pointer; }
.form-check-input:checked { background-color: var(--primary-500); border-color: var(--primary-500); }

.pay-check{
    border: 2px solid black;
}

/* Premium Glowing Button */
.premium-btn {
    background: linear-gradient(45deg, #f59e0b, #fbbf24, #f59e0b);
    background-size: 200% auto;
    color: #fff !important;
    border: none;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
    transition: 0.5s;
    animation: gradientShift 3s ease infinite;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.pay-check-div:hover{
    background-color: #b1b1d2cd;
}
.premium-btn:hover {
    background-position: right center;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.6);
}
@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function copyLink() {
    var copyText = document.getElementById("refLink");
    copyText.select();
    document.execCommand("copy");
    alert("Referral link copied to clipboard!");
}

// Move modals to body to prevent Bootstrap backdrop overlay overlapping issues
document.addEventListener('DOMContentLoaded', function() {
    const upgradeModal = document.getElementById('upgradeModal');
    const redeemModal = document.getElementById('redeemModal');
    if (upgradeModal) document.body.appendChild(upgradeModal);
    if (redeemModal) document.body.appendChild(redeemModal);
});

document.querySelector('#upgradeModal form').addEventListener('submit', function(e) {
    const paymentType = this.querySelector('input[name="payment_type"]:checked').value;
    if (paymentType === 'razorpay') {
        e.preventDefault();
        const btn = document.getElementById('upgradeBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Wait...';

        const formData = new FormData(this);
        
        fetch('<?= base_url('upgrade-premium') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                var options = {
                    "key": data.key_id,
                    "amount": data.amount,
                    "currency": "INR",
                    "name": "SmartLead Rewards",
                    "description": "Premium Upgrade",
                    "order_id": data.order_id,
                    "handler": function (response){ verifyPayment(response); },
                    "prefill": { "contact": data.user.phone },
                    "theme": { "color": "#6366f1" }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
                btn.disabled = false;
                btn.innerHTML = originalText;
            } else {
                alert(data.message || 'Error initiating payment');
                location.reload();
            }
        });
    }
});

function verifyPayment(response) {
    const formData = new FormData();
    formData.append('razorpay_payment_id', response.razorpay_payment_id);
    formData.append('razorpay_order_id', response.razorpay_order_id);
    formData.append('razorpay_signature', response.razorpay_signature);

    fetch('<?= base_url('verify-razorpay-payment') ?>', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert(data.message || 'Payment verification failed');
            location.reload();
        }
    });
}
</script>

<?php $this->endSection(); ?>
