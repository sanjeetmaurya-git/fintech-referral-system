<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="row g-4">
    <!-- Balance Card -->
    <div class="col-md-6">
        <div class="card p-4 h-100 position-relative">
            <?php if ($user['is_premium'] == 1): ?>
                <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3 fw-bold"><i class="bi bi-star-fill me-1"></i>PREMIUM</span>
            <?php else: ?>
                <span class="badge bg-secondary position-absolute top-0 end-0 m-3">FREE MEMBER</span>
            <?php endif; ?>
            
            <span class="text-muted small fw-bold text-uppercase">Wallet Balance</span>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <h1 class="fw-bold mb-0">₹<?= number_format($wallet['balance'] ?? 0, 2) ?></h1>
                <div class="d-flex gap-2">
                    <?php if ($user['is_premium'] == 0): ?>
                        <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#upgradeModal">Upgrade</button>
                    <?php endif; ?>
                    <a href="<?= base_url('withdraw') ?>" class="btn btn-primary px-4 fw-bold">Withdraw</a>
                </div>
            </div>
            <div class="mt-3 small text-muted">
                <i class="bi bi-info-circle me-1"></i> Balance is updated after admin approval.
            </div>
        </div>
    </div>

    <!-- Referral Link Card -->
    <div class="col-md-6">
        <div class="card referral-card p-4 h-100">
            <span class="small fw-bold text-uppercase text-white-50">Share & Earn</span>
            <h3 class="fw-bold mt-2"><?= esc($user['referral_code']) ?></h3>
            <p class="small mb-3">Share your unique link with friends to earn up to 8 levels of rewards!</p>
            <div class="input-group">
                <input type="text" class="form-control bg-white bg-opacity-10 border-0 text-white" value="<?= base_url('join/' . $user['referral_code']) ?>" id="refLink" readonly>
                <button class="btn btn-light fw-bold" onclick="copyLink()">Copy Link</button>
            </div>
        </div>
    </div>

    <!-- Upgrade Modal -->
    <div class="modal fade" id="upgradeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Upgrade to Premium</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('upgrade-premium') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <p class="small text-muted mb-4">Upgrade to Premium to unlock <b>unlimited withdrawals</b> and <b>coin redemptions</b>.</p>
                        
                        <div class="card p-3 mb-3 bg-light border-0">
                            <label class="d-flex align-items-center cursor-pointer">
                                <input type="radio" name="payment_type" value="wallet" class="me-3" checked>
                                <div>
                                    <div class="fw-bold">Pay via Wallet</div>
                                    <div class="small text-muted">Cost: ₹200.00</div>
                                </div>
                            </label>
                        </div>

                        <div class="card p-3 mb-3 bg-light border-0">
                            <label class="d-flex align-items-center cursor-pointer">
                                <input type="radio" name="payment_type" value="coins" class="me-3">
                                <div>
                                    <div class="fw-bold">Pay via Coins</div>
                                    <div class="small text-muted">Cost: 1,000 Coins</div>
                                </div>
                            </label>
                        </div>

                        <div class="card p-3 bg-light border-0">
                            <label class="d-flex align-items-center cursor-pointer">
                                <input type="radio" name="payment_type" value="razorpay" class="me-3">
                                <div>
                                    <div class="fw-bold text-primary">Pay via Razorpay</div>
                                    <div class="small text-muted">Cost: ₹200.00 (Instant Activation)</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Later</button>
                        <button type="submit" id="upgradeBtn" class="btn btn-primary fw-bold px-4">Upgrade Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Coin Balance Card -->
    <div class="col-md-12">
        <div class="position-relative">
            <div class="card p-4 shadow-sm <?= $user['is_premium'] == 0 ? 'blur-content' : '' ?>" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-white-50 small fw-bold text-uppercase">Coin Balance</span>
                        <h2 class="fw-bold mb-0"><i class="bi bi-coin me-2"></i><?= number_format($wallet['coins'] ?? 0, 2) ?> Coins</h2>
                        <div class="small text-white-50 mt-1">5 Coins = ₹1 (Min 20 Coins)</div>
                    </div>
                    <?php if ($user['is_premium'] == 1): ?>
                        <button class="btn btn-light fw-bold px-4" data-bs-toggle="modal" data-bs-target="#redeemModal">Redeem Coins</button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($user['is_premium'] == 0): ?>
                <div class="locked-overlay d-flex flex-column align-items-center justify-content-center">
                    <div class="text-center p-3">
                        <i class="bi bi-lock-fill fs-1 mb-2"></i>
                        <h5 class="fw-bold mb-1">Get Premium Member to unlock this</h5>
                        <button class="btn btn-light btn-sm fw-bold px-4 mt-2" data-bs-toggle="modal" data-bs-target="#upgradeModal">Get Membership</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Redeem Modal -->
    <div class="modal fade" id="redeemModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Redeem Coins for Cash</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('redeem-coins') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <h4 class="text-warning fw-bold mb-0"><?= number_format($wallet['coins'] ?? 0, 2) ?></h4>
                            <div class="text-muted small">Available Coins</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Coins to Redeem</label>
                            <input type="number" name="coins" class="form-control" min="20" max="<?= (int)$wallet['coins'] ?>" step="1" required placeholder="Min 20 coins">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-bold">Redeem Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Referral History -->
    <div class="col-md-8">
        <div class="card p-0 overflow-hidden">
            <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">My Network</h6>
                <span class="badge bg-primary rounded-pill"><?= count($referrals) ?> Referrals</span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small border-0">Phone</th>
                            <th class="small border-0">Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($referrals)): ?>
                            <tr><td colspan="2" class="text-center py-4 text-muted">No referrals yet. Start sharing!</td></tr>
                        <?php else: ?>
                            <?php foreach ($referrals as $ref): ?>
                                <tr>
                                    <td>XXXXXX<?= substr($ref['phone'], -4) ?></td>
                                    <td class="small"><?= date('d M Y', strtotime($ref['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-md-4">
        <div class="card p-0 overflow-hidden">
            <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Recent History</h6>
                <a href="<?= base_url('wallet-history') ?>" class="small text-decoration-none">View All</a>
            </div>
            <div class="list-group list-group-flush">
                <?php if (empty($transactions) && empty($withdrawals)): ?>
                    <div class="p-4 text-center text-muted small">No recent activity.</div>
                <?php else: ?>
                    <?php foreach ($withdrawals as $w): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span class="small fw-bold">Withdrawal</span>
                                <span class="small text-danger">-₹<?= number_format($w['amount'], 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <span class="badge <?= $w['status'] === 'completed' ? 'bg-success' : ($w['status'] === 'rejected' ? 'bg-danger' : 'bg-warning') ?> small" style="font-size: 10px;">
                                    <?= ucfirst($w['status']) ?>
                                </span>
                                <span class="text-muted" style="font-size: 10px;"><?= date('d M', strtotime($w['created_at'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php foreach ($transactions as $tx): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span class="small fw-bold">Reward</span>
                                <span class="small text-success">+₹<?= number_format($tx['amount'], 2) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <span class="text-muted" style="font-size: 10px;"><?= $tx['status'] ?></span>
                                <span class="text-muted" style="font-size: 10px;"><?= date('d M', strtotime($tx['created_at'])) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function copyLink() {
    var copyText = document.getElementById("refLink");
    copyText.select();
    document.execCommand("copy");
    alert("Referral link copied!");
}

document.querySelector('#upgradeModal form').addEventListener('submit', function(e) {
    const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
    
    if (paymentType === 'razorpay') {
        e.preventDefault();
        const btn = document.getElementById('upgradeBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        const formData = new FormData(this);
        
        fetch('<?= base_url('upgrade-premium') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var options = {
                    "key": data.key_id,
                    "amount": data.amount,
                    "currency": "INR",
                    "name": "Fintech Referral System",
                    "description": "Premium Membership Upgrade",
                    "order_id": data.order_id,
                    "handler": function (response){
                        verifyPayment(response);
                    },
                    "prefill": {
                        "contact": data.user.phone
                    },
                    "theme": {
                        "color": "#3399cc"
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.on('payment.failed', function (response){
                    alert("Payment Failed: " + response.error.description);
                    location.reload();
                });
                rzp1.open();
            } else {
                alert(data.message || 'Error initiating payment');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Something went wrong!');
            location.reload();
        });
    }
});

function verifyPayment(response) {
    const formData = new FormData();
    formData.append('razorpay_order_id', response.razorpay_order_id);
    formData.append('razorpay_payment_id', response.razorpay_payment_id);
    formData.append('razorpay_signature', response.razorpay_signature);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

    fetch('<?= base_url('verify-razorpay-payment') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.href = '<?= base_url('dashboard') ?>';
        } else {
            alert(data.message);
            location.reload();
        }
    });
}
</script>

<style>
.blur-content {
    filter: blur(2.5px);
    pointer-events: none;
    user-select: none;
}
.locked-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.45);
    border-radius: 12px;
    color: white;
    z-index: 10;
    backdrop-filter: blur(1px);
}
.cursor-pointer { cursor: pointer; }
</style>

<?php $this->endSection(); ?>
