<?= $this->extend('user/layout') ?>

<?= $this->section('content') ?>
<div class="container py-4">

    <!-- Back + Header -->
    <div class="mb-4">
        <a href="<?= base_url('services') ?>" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-3">
            <i class="bi bi-arrow-left"></i> Back to Services
        </a>
        <h2 class="fw-bold mb-1"><?= ($type === 'd2h') ? '📺 D2H Recharge' : '📱 Mobile Recharge' ?></h2>
        <p class="text-muted mb-0">Select your <?= ($type === 'd2h') ? 'DTH operator' : 'mobile operator' ?>, enter details and earn coins instantly after verification.</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center gap-2" style="border-radius: 12px;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center gap-2" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Left: Recharge Form -->
        <div class="col-lg-7">
            <!-- Step 1: Select Operator -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="step-badge">1</div>
                        <h6 class="fw-bold mb-0">Select Operator</h6>
                    </div>

                    <?php if (empty($operators)): ?>
                        <div class="text-center py-4 text-muted small">
                            <i class="bi bi-broadcast fs-3 mb-2 d-block"></i>
                            No operators configured yet.
                        </div>
                    <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($operators as $op): ?>
                        <div class="col-6 col-sm-3">
                            <div class="operator-item p-3 border text-center rounded-3 h-100 d-flex flex-column align-items-center justify-content-center position-relative"
                                 onclick="selectOperator(<?= $op['id'] ?>, '<?= esc($op['name']) ?>', '<?= base_url($op['logo_url']) ?>', <?= json_encode($op) ?>)"
                                 id="op-<?= $op['id'] ?>"
                                 style="cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-color: #e5e7eb !important; min-height: 100px; background: white;">
                                
                                <div class="selection-check">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>

                                <?php if ($op['logo_url']): ?>
                                    <img src="<?= base_url($op['logo_url']) ?>" alt="<?= esc($op['name']) ?>" class="mb-2 op-logo" style="max-height: 42px; max-width: 80%; object-fit: contain; transition: transform 0.3s;">
                                <?php else: ?>
                                    <i class="bi bi-broadcast text-primary fs-3 mb-2"></i>
                                <?php endif; ?>
                                <div class="small fw-semibold"><?= esc($op['name']) ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Step 2: Details Form -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;" id="rechargeFormCard">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="step-badge">2</div>
                        <h6 class="fw-bold mb-0">Enter Recharge Details</h6>
                    </div>

                    <form action="<?= base_url('services/recharge/submit') ?>" method="post" id="rechargeForm">
                        <?= csrf_field() ?>
                        <input type="hidden" name="operator_id" id="selected_op_id">

                        <!-- Selected Operator Badge -->
                        <div id="selectedOpBadge" class="d-none mb-3 p-3 rounded-3 d-flex align-items-center gap-3" style="background: linear-gradient(135deg, #ede9fe, #f0f4ff);">
                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-white rounded-3 shadow-sm" style="width: 50px; height: 50px;">
                                <img id="selectedOpLogo" src="" alt="" style="max-width: 70%; max-height: 70%; object-fit: contain;">
                            </div>
                            <div>
                                <div class="small text-muted">Selected Operator</div>
                                <div class="fw-bold" id="selectedOpName">—</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Mobile Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="border-radius: 10px 0 0 10px;"><i class="bi bi-phone"></i></span>
                                <input type="tel" class="form-control" name="mobile" placeholder="10-digit mobile number"
                                       pattern="[6-9][0-9]{9}" maxlength="10" required
                                       style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Recharge Amount (₹) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="border-radius: 10px 0 0 10px;">₹</span>
                                <input type="number" class="form-control" name="amount" id="rechargeAmount" placeholder="e.g. 199"
                                       min="10" step="1" required onchange="updateCoinPreview()" oninput="updateCoinPreview()"
                                       style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>

                        <!-- Coin Preview -->
                        <div id="coinPreview" class="mb-4 d-none p-3 rounded-3 text-center" style="background: linear-gradient(135deg, #fef9c3, #fef3c7);">
                            <div class="small text-muted mb-1">You will earn approximately</div>
                            <div class="fs-4 fw-bold" style="color: #b45309;">🪙 <span id="coinEarnedText">0</span> Coins</div>
                            <div class="x-small text-muted mt-1">Final amount credited after admin approval.</div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100" style="border-radius: 12px; font-weight: 600;" id="submitBtn" disabled>
                            <i class="bi bi-send-fill me-2"></i>Submit Recharge Request
                        </button>
                        <p class="text-muted x-small text-center mt-2 mb-0">
                            <i class="bi bi-info-circle me-1"></i>Coins are credited after successful recharge verification.
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right: Reward Tiers Info -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm sticky-lg-top" style="border-radius: 16px; top: 80px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-1">💰 Earn Coins on Recharge</h6>
                    <p class="text-muted small mb-4">Coin rewards are operator-specific. Select an operator to see their reward tiers.</p>

                    <div id="operatorTiers">
                        <!-- Placeholder -->
                        <div class="text-center py-4 text-muted" id="tierPlaceholder">
                            <i class="bi bi-hand-index-thumb fs-3 mb-2 d-block"></i>
                            <span class="small">Select an operator to<br>view reward tiers</span>
                        </div>
                        <div id="tierCards" class="d-none d-flex flex-column gap-3">
                            <!-- Filled by JS -->
                        </div>
                    </div>

                    <hr class="my-3" style="border-color: #f0f0f0;">

                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <i class="bi bi-lightning-fill text-warning"></i> Instant request submission
                        </div>
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <i class="bi bi-shield-check text-success"></i> Verified before coin credit
                        </div>
                        <div class="d-flex align-items-center gap-2 small text-muted">
                            <i class="bi bi-clock text-primary"></i> Typically reviewed within 24h
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-badge {
    width: 28px; height: 28px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; font-weight: 700;
    flex-shrink: 0;
}
.operator-item {
    position: relative;
    overflow: hidden;
}
.operator-item:hover {
    border-color: #6366f1 !important;
    background: #f8faff !important;
    transform: translateY(-5px);
    box-shadow: 0 10px 20px -5px rgba(99,102,241,0.15);
}
.operator-item:hover .op-logo { transform: scale(1.1); }

.operator-item.selected {
    border-color: #6366f1 !important;
    background: rgba(99, 102, 241, 0.05) !important;
    backdrop-filter: blur(10px) saturate(180%);
    -webkit-backdrop-filter: blur(10px) saturate(180%);
    box-shadow: 0 8px 32px 0 rgba(79, 70, 229, 0.1) !important;
    transform: scale(0.98);
}

.selection-check {
    position: absolute;
    top: 8px;
    right: 8px;
    color: #6366f1;
    font-size: 1.1rem;
    opacity: 0;
    transform: scale(0.5);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    z-index: 2;
}

.operator-item.selected .selection-check {
    opacity: 1;
    transform: scale(1);
}

.operator-item.selected::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.4), rgba(255,255,255,0.1));
    z-index: -1;
}
.tier-card { padding: 12px 14px; border-radius: 12px; border: 1.5px solid transparent; }
.x-small { font-size: 0.72rem; }
@media (max-width: 991px) { .sticky-lg-top { position: static !important; } }
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let selectedOp = null;

function selectOperator(id, name, logoUrl, opData) {
    // Remove previous selection
    document.querySelectorAll('.operator-item').forEach(el => el.classList.remove('selected'));
    document.getElementById('op-' + id).classList.add('selected');

    selectedOp = opData;
    document.getElementById('selected_op_id').value = id;

    // Update badge
    document.getElementById('selectedOpName').textContent = name;
    if (logoUrl && logoUrl !== '<?= base_url() ?>/') {
        document.getElementById('selectedOpLogo').src = logoUrl;
        document.getElementById('selectedOpBadge').classList.remove('d-none');
    }

    // Enable submit
    document.getElementById('submitBtn').disabled = false;

    // Show tier cards
    renderTiers(opData);

    // Update coin preview
    updateCoinPreview();
}

function renderTiers(op) {
    const tierPlaceholder = document.getElementById('tierPlaceholder');
    const tierCards = document.getElementById('tierCards');
    tierPlaceholder.classList.add('d-none');
    tierCards.classList.remove('d-none');

    const tiers = [
        { label: 'Tier 1', range: 'Up to ₹' + parseInt(op.tier_1_max).toLocaleString('en-IN'), coins: parseFloat(op.tier_1_coins), color: '#6366f1', bg: '#ede9fe' },
        { label: 'Tier 2', range: 'Up to ₹' + parseInt(op.tier_2_max).toLocaleString('en-IN'), coins: parseFloat(op.tier_2_coins), color: '#f59e0b', bg: '#fef9c3' },
        { label: 'Tier 3', range: 'Above ₹' + parseInt(op.tier_2_max).toLocaleString('en-IN'), coins: parseFloat(op.tier_3_coins), color: '#10b981', bg: '#dcfce7' },
    ];

    tierCards.innerHTML = tiers.map(t => `
        <div class="tier-card d-flex justify-content-between align-items-center" style="background: ${t.bg}; border-color: ${t.bg};">
            <div>
                <span class="badge me-2" style="background:${t.color}20; color:${t.color}; border-radius:6px; font-size:.7rem;">${t.label}</span>
                <span class="small text-muted">${t.range}</span>
            </div>
            <div class="fw-bold" style="color:${t.color};">🪙 ${t.coins}</div>
        </div>
    `).join('');
}

function updateCoinPreview() {
    if (!selectedOp) return;
    const amount = parseFloat(document.getElementById('rechargeAmount').value) || 0;
    if (amount <= 0) {
        document.getElementById('coinPreview').classList.add('d-none');
        return;
    }

    let coins = 0;
    if (amount <= parseFloat(selectedOp.tier_1_max)) {
        coins = parseFloat(selectedOp.tier_1_coins);
    } else if (amount <= parseFloat(selectedOp.tier_2_max)) {
        coins = parseFloat(selectedOp.tier_2_coins);
    } else {
        coins = parseFloat(selectedOp.tier_3_coins);
    }

    document.getElementById('coinEarnedText').textContent = coins;
    document.getElementById('coinPreview').classList.remove('d-none');
}
</script>
<?= $this->endSection() ?>
