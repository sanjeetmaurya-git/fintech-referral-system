<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/services') ?>" class="text-decoration-none">Services</a></li>
                    <li class="breadcrumb-item active">Recharge Operators</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0">📱 Recharge Operators</h4>
            <p class="text-muted small mb-0">Manage mobile recharge operators and their 3-tier coin reward structure.</p>
        </div>
        <button type="button" class="btn btn-primary px-4" id="addOperatorBtn" style="border-radius: 10px;">
            <i class="bi bi-plus-lg me-2"></i>Add Operator
        </button>
    </div>

    <!-- Alerts -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center gap-2" style="border-radius: 10px;">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center gap-2" style="border-radius: 10px;">
            <i class="bi bi-exclamation-triangle-fill text-danger"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Operators Grid -->
    <?php if (empty($operators)): ?>
        <div class="text-center py-5 my-4">
            <div class="mb-3" style="font-size: 4rem;">📡</div>
            <h5 class="fw-bold text-muted">No Operators Yet</h5>
            <p class="text-muted small">Click "Add Operator" to configure your first recharge operator with coin rewards.</p>
        </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($operators as $op): ?>
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 operator-card" style="border-radius: 16px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                <div class="card-body p-4">
                    <!-- Operator Header -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="operator-logo-wrap me-3 d-flex align-items-center justify-content-center bg-light rounded-3 flex-shrink-0" style="width: 56px; height: 56px;">
                            <?php if ($op['logo_url']): ?>
                                <img src="<?= base_url($op['logo_url']) ?>" alt="<?= esc($op['name']) ?>" style="max-width: 80%; max-height: 80%; object-fit: contain;">
                            <?php else: ?>
                                <i class="bi bi-broadcast text-primary fs-3"></i>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <h5 class="fw-bold mb-0 text-truncate"><?= esc($op['name']) ?></h5>
                            <span class="badge <?= $op['is_active'] ? 'bg-success' : 'bg-secondary' ?> bg-opacity-15 text-<?= $op['is_active'] ? 'success' : 'secondary' ?> small px-2 py-1 mt-1" style="border-radius: 6px;">
                                <?= $op['is_active'] ? '● Active' : '○ Inactive' ?>
                            </span>
                        </div>
                        <button class="btn btn-light btn-sm ms-2 flex-shrink-0 edit-btn" 
                                onclick="editOperator(<?= htmlspecialchars(json_encode($op)) ?>)"
                                title="Edit Operator"
                                style="border-radius: 8px; width: 36px; height: 36px; padding: 0;">
                            <i class="bi bi-pencil-fill text-primary small"></i>
                        </button>
                    </div>

                    <!-- 3-Tier Reward Table -->
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);">
                        <div class="small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Coin Reward Tiers</div>
                        <div class="d-flex flex-column gap-2">
                            <!-- Tier 1 -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary bg-opacity-15 text-primary px-2" style="border-radius: 6px; font-size: 0.7rem;">T1</span>
                                    <span class="small text-muted">Up to ₹<?= number_format($op['tier_1_max']) ?></span>
                                </div>
                                <span class="fw-bold small text-primary"><?= $op['tier_1_coins'] ?> <span class="text-muted fw-normal">coins</span></span>
                            </div>
                            <!-- Tier 2 -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-warning bg-opacity-15 text-warning px-2" style="border-radius: 6px; font-size: 0.7rem;">T2</span>
                                    <span class="small text-muted">Up to ₹<?= number_format($op['tier_2_max']) ?></span>
                                </div>
                                <span class="fw-bold small text-warning"><?= $op['tier_2_coins'] ?> <span class="text-muted fw-normal">coins</span></span>
                            </div>
                            <!-- Tier 3 -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success bg-opacity-15 text-success px-2" style="border-radius: 6px; font-size: 0.7rem;">T3</span>
                                    <span class="small text-muted">Above ₹<?= number_format($op['tier_2_max']) ?></span>
                                </div>
                                <span class="fw-bold small text-success"><?= $op['tier_3_coins'] ?> <span class="text-muted fw-normal">coins</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Add/Edit Operator Modal -->
<div class="modal fade" id="operatorModal" tabindex="-1" aria-labelledby="operatorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold" id="operatorModalLabel">Add Operator</h5>
                    <p class="text-muted small mb-0">Configure operator logo and 3-tier coin reward structure.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="operatorForm" action="<?= base_url('admin/services/recharge/save') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="op_id">
                <input type="hidden" name="current_logo" id="current_logo">
                <input type="hidden" name="is_active" value="1">

                <div class="modal-body px-4 py-3">
                    <!-- Operator Name & Logo -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label for="op_name" class="form-label fw-semibold small">Operator Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="op_name" name="name" placeholder="e.g. Jio, Airtel, VI, BSNL" required style="border-radius: 10px; border: 1.5px solid #e5e7eb;">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold small">Operator Logo</label>
                            <!-- Current logo preview -->
                            <div id="currentLogoWrap" class="mb-2 d-none">
                                <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-2">
                                    <img id="current_logo_preview" src="" alt="Current Logo" style="max-height: 40px; max-width: 60px; object-fit: contain; border-radius: 4px;">
                                    <div>
                                        <div class="small fw-semibold text-muted">Current Logo</div>
                                        <div class="x-small text-muted">Upload new to replace</div>
                                    </div>
                                </div>
                            </div>
                            <div class="upload-zone" id="logoUploadZone" onclick="document.getElementById('op_logo').click()" style="border: 2px dashed #d1d5db; border-radius: 10px; padding: 12px; text-align: center; cursor: pointer; transition: border-color 0.2s;">
                                <input class="d-none" type="file" id="op_logo" name="logo_url" accept=".jpg,.jpeg,.webp,.png,.svg" onchange="handleLogoChange(this)">
                                <div id="uploadPlaceholder">
                                    <i class="bi bi-cloud-upload text-muted fs-4"></i>
                                    <div class="small text-muted mt-1">Click to upload</div>
                                    <div class="x-small text-muted">JPG, JPEG, WEBP, PNG, SVG · Max 2MB</div>
                                </div>
                                <div id="newLogoPreviewWrap" class="d-none">
                                    <img id="new_logo_preview" src="" alt="Preview" style="max-height: 60px; max-width: 100%; object-fit: contain; border-radius: 6px;">
                                    <div class="small text-success mt-1" id="newLogoName"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3" style="border-color: #f0f0f0;">

                    <!-- 3-Tier Rewards -->
                    <div class="mb-1">
                        <div class="fw-semibold small mb-3">💰 Coin Reward Tiers <span class="text-muted fw-normal">(Admin-defined, applied on recharge success)</span></div>
                        <div class="d-flex flex-column gap-3">
                            <?php 
                            $tiers = [
                                1 => ['color' => 'primary', 'label' => 'Tier 1', 'hint' => 'Smallest recharge range'],
                                2 => ['color' => 'warning', 'label' => 'Tier 2', 'hint' => 'Mid-range recharge'],
                                3 => ['color' => 'success', 'label' => 'Tier 3', 'hint' => 'Highest recharge (above Tier 2 max)'],
                            ];
                            foreach ($tiers as $i => $tier): ?>
                            <div class="p-3 rounded-3 border" style="border-color: #f0f0f0 !important; background: #fafafa;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-<?= $tier['color'] ?> bg-opacity-15 text-<?= $tier['color'] ?> px-2 py-1" style="border-radius: 6px;"><?= $tier['label'] ?></span>
                                    <span class="small text-muted"><?= $tier['hint'] ?></span>
                                </div>
                                <div class="row g-2">
                                    <?php if ($i < 3): // No "max amount" input for tier 3 (it's "above tier 2") ?>
                                    <div class="col-6">
                                        <label class="form-label x-small text-muted mb-1">Max Amount (₹) <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text" style="border-radius: 8px 0 0 8px;">₹</span>
                                            <input type="number" class="form-control" name="tier_<?= $i ?>_max" id="tier_<?= $i ?>_max" 
                                                   placeholder="e.g. <?= $i === 1 ? '199' : '499' ?>" min="1" required
                                                   style="border-radius: 0 8px 8px 0;">
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="col-6">
                                        <label class="form-label x-small text-muted mb-1">Max Amount (₹)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text" style="border-radius: 8px 0 0 8px;">₹</span>
                                            <input type="text" class="form-control bg-light text-muted" value="Any amount" readonly style="border-radius: 0 8px 8px 0;">
                                        </div>
                                        <input type="hidden" name="tier_3_max" id="tier_3_max" value="999999">
                                    </div>
                                    <?php endif; ?>
                                    <div class="col-6">
                                        <label class="form-label x-small text-muted mb-1">Coins Earned <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control" name="tier_<?= $i ?>_coins" id="tier_<?= $i ?>_coins" 
                                                   placeholder="e.g. <?= $i * 3 ?>" min="0" step="0.5" required
                                                   style="border-radius: 8px 0 0 8px;">
                                            <span class="input-group-text" style="border-radius: 0 8px 8px 0;">🪙</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">
                        <i class="bi bi-check-lg me-1"></i> Save Operator
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.operator-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12) !important;
}
.edit-btn:hover { background-color: #e8f0fe !important; }
.upload-zone:hover { border-color: #4f46e5 !important; background: #f8f9ff; }
.x-small { font-size: 0.72rem; }
@media (max-width: 576px) {
    .modal-dialog { margin: 0.5rem; }
    .modal-content { border-radius: 12px !important; }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Bootstrap 5 Modal instance
let operatorModalEl, operatorModal;
document.addEventListener('DOMContentLoaded', function () {
    operatorModalEl = document.getElementById('operatorModal');
    operatorModal = new bootstrap.Modal(operatorModalEl);
});

function handleLogoChange(input) {
    const file = input.files[0];
    if (!file) return;

    const allowed = ['image/jpeg', 'image/jpg', 'image/webp', 'image/png', 'image/svg+xml'];
    if (!allowed.includes(file.type)) {
        alert('❌ Invalid file type!\nAllowed formats: JPG, JPEG, WEBP, PNG, SVG');
        input.value = '';
        return;
    }
    if (file.size > 2 * 1024 * 1024) {
        alert('❌ File too large! Max 2MB.');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('uploadPlaceholder').classList.add('d-none');
        document.getElementById('newLogoPreviewWrap').classList.remove('d-none');
        document.getElementById('new_logo_preview').src = e.target.result;
        document.getElementById('newLogoName').textContent = '✓ ' + file.name;
    };
    reader.readAsDataURL(file);
}

function editOperator(operator) {
    // Reset form
    document.getElementById('operatorForm').reset();
    document.getElementById('operatorModalLabel').textContent = 'Edit Operator';

    // Populate fields
    document.getElementById('op_id').value = operator.id;
    document.getElementById('op_name').value = operator.name;
    document.getElementById('current_logo').value = operator.logo_url || '';

    // Tier values (3 tiers)
    for (let i = 1; i <= 3; i++) {
        if (i < 3) {
            const maxEl = document.getElementById('tier_' + i + '_max');
            if (maxEl) maxEl.value = operator['tier_' + i + '_max'] || '';
        }
        const coinsEl = document.getElementById('tier_' + i + '_coins');
        if (coinsEl) coinsEl.value = operator['tier_' + i + '_coins'] || '';
    }

    // Show current logo preview
    const logoUrl = operator.logo_url;
    if (logoUrl) {
        const fullUrl = logoUrl.startsWith('http') ? logoUrl : '<?= rtrim(base_url(), '/') ?>/' + logoUrl;
        document.getElementById('current_logo_preview').src = fullUrl;
        document.getElementById('currentLogoWrap').classList.remove('d-none');
    } else {
        document.getElementById('currentLogoWrap').classList.add('d-none');
    }

    // Reset upload zone
    document.getElementById('uploadPlaceholder').classList.remove('d-none');
    document.getElementById('newLogoPreviewWrap').classList.add('d-none');

    operatorModal.show();
}

document.getElementById('addOperatorBtn').addEventListener('click', function () {
    document.getElementById('operatorForm').reset();
    document.getElementById('operatorModalLabel').textContent = 'Add Operator';
    document.getElementById('op_id').value = '';
    document.getElementById('current_logo').value = '';
    document.getElementById('currentLogoWrap').classList.add('d-none');
    document.getElementById('uploadPlaceholder').classList.remove('d-none');
    document.getElementById('newLogoPreviewWrap').classList.add('d-none');
    operatorModal.show();
});

// Drag & drop support
const zone = document.getElementById('logoUploadZone');
if (zone) {
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = '#4f46e5'; });
    zone.addEventListener('dragleave', () => { zone.style.borderColor = '#d1d5db'; });
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.style.borderColor = '#d1d5db';
        const fileInput = document.getElementById('op_logo');
        if (e.dataTransfer.files.length) {
            // DataTransfer to file input
            const dt = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            fileInput.files = dt.files;
            handleLogoChange(fileInput);
        }
    });
}
</script>
<?= $this->endSection() ?>