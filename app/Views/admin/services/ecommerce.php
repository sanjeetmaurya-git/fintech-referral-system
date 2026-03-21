<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/services') ?>" class="text-decoration-none">Services</a></li>
                    <li class="breadcrumb-item active">Ecommerce Platforms</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0">🛍️ Ecommerce Platforms</h4>
            <p class="text-muted small mb-0">Manage affiliate platforms like Flipkart, Amazon with 3-tier coin rewards.</p>
        </div>
        <button type="button" class="btn btn-success px-4" id="addPlatformBtn" style="border-radius: 10px;">
            <i class="bi bi-plus-lg me-2"></i>Add Platform
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

    <!-- Platforms Grid -->
    <?php if (empty($platforms)): ?>
        <div class="text-center py-5 my-4">
            <div class="mb-3" style="font-size: 4rem;">🏪</div>
            <h5 class="fw-bold text-muted">No Platforms Yet</h5>
            <p class="text-muted small">Click "Add Platform" to configure your first affiliate shopping platform.</p>
        </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($platforms as $plat): ?>
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 platform-card" style="border-radius: 16px; transition: transform 0.2s ease, box-shadow 0.2s ease;">
                <div class="card-body p-4">
                    <!-- Platform Header -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="platform-logo-wrap me-3 d-flex align-items-center justify-content-center bg-light rounded-3 flex-shrink-0" style="width: 56px; height: 56px;">
                            <?php if ($plat['logo_url']): ?>
                                <img src="<?= base_url($plat['logo_url']) ?>" alt="<?= esc($plat['name']) ?>" style="max-width: 80%; max-height: 80%; object-fit: contain;">
                            <?php else: ?>
                                <i class="bi bi-shop text-success fs-3"></i>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <h5 class="fw-bold mb-0 text-truncate"><?= esc($plat['name']) ?></h5>
                            <span class="small text-muted"><?= esc($plat['category']) ?></span><br>
                            <span class="badge <?= $plat['is_active'] ? 'bg-success' : 'bg-secondary' ?> bg-opacity-15 text-<?= $plat['is_active'] ? 'success' : 'secondary' ?> small px-2 py-1" style="border-radius: 6px;">
                                <?= $plat['is_active'] ? '● Active' : '○ Inactive' ?>
                            </span>
                        </div>
                        <button class="btn btn-light btn-sm ms-2 flex-shrink-0 edit-btn"
                                onclick="editPlatform(<?= htmlspecialchars(json_encode($plat)) ?>)"
                                title="Edit Platform"
                                style="border-radius: 8px; width: 36px; height: 36px; padding: 0;">
                            <i class="bi bi-pencil-fill text-success small"></i>
                        </button>
                    </div>

                    <!-- Affiliate URL Preview -->
                    <?php if ($plat['affiliate_url']): ?>
                    <div class="mb-3 p-2 bg-light rounded-2 d-flex align-items-center gap-2" style="overflow: hidden;">
                        <i class="bi bi-link-45deg text-muted flex-shrink-0 small"></i>
                        <span class="small text-muted text-truncate"><?= esc($plat['affiliate_url']) ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- 3-Tier Reward Summary -->
                    <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #f0fdf4 0%, #e8faf0 100%);">
                        <div class="small fw-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Coin Reward Tiers</div>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-primary bg-opacity-15 text-primary px-2" style="border-radius: 6px; font-size: 0.7rem;">T1</span>
                                    <span class="small text-muted">Up to ₹<?= number_format($plat['tier_1_max']) ?></span>
                                </div>
                                <span class="fw-bold small text-primary"><?= $plat['tier_1_coins'] ?> <span class="text-muted fw-normal">coins</span></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-warning bg-opacity-15 text-warning px-2" style="border-radius: 6px; font-size: 0.7rem;">T2</span>
                                    <span class="small text-muted">Up to ₹<?= number_format($plat['tier_2_max']) ?></span>
                                </div>
                                <span class="fw-bold small text-warning"><?= $plat['tier_2_coins'] ?> <span class="text-muted fw-normal">coins</span></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-success bg-opacity-15 text-success px-2" style="border-radius: 6px; font-size: 0.7rem;">T3</span>
                                    <span class="small text-muted">Above ₹<?= number_format($plat['tier_2_max']) ?></span>
                                </div>
                                <span class="fw-bold small text-success"><?= $plat['tier_3_coins'] ?> <span class="text-muted fw-normal">coins</span></span>
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

<!-- Add/Edit Platform Modal -->
<div class="modal fade" id="platformModal" tabindex="-1" aria-labelledby="platformModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold" id="platformModalLabel">Add Platform</h5>
                    <p class="text-muted small mb-0">Configure platform logo, affiliate link, and 3-tier coin rewards.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="platformForm" action="<?= base_url('admin/services/ecommerce/save') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="plat_id">
                <input type="hidden" name="current_logo" id="plat_current_logo">
                <input type="hidden" name="is_active" value="1">

                <div class="modal-body px-4 py-3">
                    <!-- Platform Info -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="plat_name" class="form-label fw-semibold small">Platform Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="plat_name" name="name" placeholder="e.g. Amazon, Flipkart" required style="border-radius: 10px; border: 1.5px solid #e5e7eb;">
                        </div>
                        <div class="col-md-6">
                            <label for="plat_category" class="form-label fw-semibold small">Category</label>
                            <input type="text" class="form-control" id="plat_category" name="category" placeholder="e.g. E-Commerce, Fashion" style="border-radius: 10px; border: 1.5px solid #e5e7eb;">
                        </div>
                        <div class="col-12">
                            <label for="plat_affiliate_url" class="form-label fw-semibold small">Affiliate URL</label>
                            <input type="url" class="form-control" id="plat_affiliate_url" name="affiliate_url" placeholder="https://www.flipkart.com/?affid=YOUR_ID&uid=[USER_ID]" style="border-radius: 10px; border: 1.5px solid #e5e7eb;">
                            <div class="form-text small"><i class="bi bi-info-circle me-1"></i>Use <code>[USER_ID]</code> as a placeholder — it will be replaced with the user's ID on redirect.</div>
                        </div>
                    </div>

                    <!-- Logo Upload -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Platform Logo</label>
                        <div id="platCurrentLogoWrap" class="mb-2 d-none">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded-2">
                                <img id="plat_current_logo_preview" src="" alt="Current Logo" style="max-height: 40px; max-width: 60px; object-fit: contain; border-radius: 4px;">
                                <div>
                                    <div class="small fw-semibold text-muted">Current Logo</div>
                                    <div class="x-small text-muted">Upload new to replace</div>
                                </div>
                            </div>
                        </div>
                        <div class="upload-zone" id="platLogoUploadZone" onclick="document.getElementById('plat_logo').click()" style="border: 2px dashed #d1d5db; border-radius: 10px; padding: 14px; text-align: center; cursor: pointer; transition: border-color 0.2s;">
                            <input class="d-none" type="file" id="plat_logo" name="logo_url" accept=".jpg,.jpeg,.webp,.png,.svg" onchange="handlePlatLogoChange(this)">
                            <div id="platUploadPlaceholder">
                                <i class="bi bi-cloud-upload text-muted fs-4"></i>
                                <div class="small text-muted mt-1">Click or drag to upload</div>
                                <div class="x-small text-muted">JPG, JPEG, WEBP, PNG, SVG · Max 2MB</div>
                            </div>
                            <div id="platNewLogoPreviewWrap" class="d-none">
                                <img id="plat_new_logo_preview" src="" alt="Preview" style="max-height: 60px; max-width: 100%; object-fit: contain; border-radius: 6px;">
                                <div class="small text-success mt-1" id="platNewLogoName"></div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3" style="border-color: #f0f0f0;">

                    <!-- 3-Tier Rewards -->
                    <div class="fw-semibold small mb-3">💰 Coin Reward Tiers</div>
                    <div class="d-flex flex-column gap-3">
                        <?php
                        $tiers = [
                            1 => ['color' => 'primary', 'label' => 'Tier 1', 'hint' => 'Smallest purchase range'],
                            2 => ['color' => 'warning', 'label' => 'Tier 2', 'hint' => 'Mid-range purchase'],
                            3 => ['color' => 'success', 'label' => 'Tier 3', 'hint' => 'Highest purchase (above Tier 2)'],
                        ];
                        foreach ($tiers as $i => $tier): ?>
                        <div class="p-3 rounded-3 border" style="border-color: #f0f0f0 !important; background: #fafafa;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-<?= $tier['color'] ?> bg-opacity-15 text-<?= $tier['color'] ?> px-2 py-1" style="border-radius: 6px;"><?= $tier['label'] ?></span>
                                <span class="small text-muted"><?= $tier['hint'] ?></span>
                            </div>
                            <div class="row g-2">
                                <?php if ($i < 3): ?>
                                <div class="col-6">
                                    <label class="form-label x-small text-muted mb-1">Max Purchase (₹) <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text" style="border-radius: 8px 0 0 8px;">₹</span>
                                        <input type="number" class="form-control" name="tier_<?= $i ?>_max" id="plat_tier_<?= $i ?>_max"
                                               placeholder="e.g. <?= $i === 1 ? '500' : '2000' ?>" min="1" required
                                               style="border-radius: 0 8px 8px 0;">
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="col-6">
                                    <label class="form-label x-small text-muted mb-1">Max Purchase (₹)</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text" style="border-radius: 8px 0 0 8px;">₹</span>
                                        <input type="text" class="form-control bg-light text-muted" value="Any amount" readonly style="border-radius: 0 8px 8px 0;">
                                    </div>
                                    <input type="hidden" name="tier_3_max" id="plat_tier_3_max" value="999999">
                                </div>
                                <?php endif; ?>
                                <div class="col-6">
                                    <label class="form-label x-small text-muted mb-1">Coins Earned <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control" name="tier_<?= $i ?>_coins" id="plat_tier_<?= $i ?>_coins"
                                               placeholder="e.g. <?= $i * 5 ?>" min="0" step="0.5" required
                                               style="border-radius: 8px 0 0 8px;">
                                        <span class="input-group-text" style="border-radius: 0 8px 8px 0;">🪙</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Cancel</button>
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 10px;">
                        <i class="bi bi-check-lg me-1"></i> Save Platform
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.platform-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.12) !important;
}
.edit-btn:hover { background-color: #f0fdf4 !important; }
.upload-zone:hover { border-color: #16a34a !important; background: #f0fdf4; }
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
let platformModalEl, platformModal;
document.addEventListener('DOMContentLoaded', function () {
    platformModalEl = document.getElementById('platformModal');
    platformModal = new bootstrap.Modal(platformModalEl);
});

function handlePlatLogoChange(input) {
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
        document.getElementById('platUploadPlaceholder').classList.add('d-none');
        document.getElementById('platNewLogoPreviewWrap').classList.remove('d-none');
        document.getElementById('plat_new_logo_preview').src = e.target.result;
        document.getElementById('platNewLogoName').textContent = '✓ ' + file.name;
    };
    reader.readAsDataURL(file);
}

function editPlatform(plat) {
    // Reset form
    document.getElementById('platformForm').reset();
    document.getElementById('platformModalLabel').textContent = 'Edit Platform';

    // Populate main fields
    document.getElementById('plat_id').value = plat.id;
    document.getElementById('plat_name').value = plat.name || '';
    document.getElementById('plat_category').value = plat.category || '';
    document.getElementById('plat_affiliate_url').value = plat.affiliate_url || '';
    document.getElementById('plat_current_logo').value = plat.logo_url || '';

    // Tier values
    for (let i = 1; i <= 3; i++) {
        if (i < 3) {
            const maxEl = document.getElementById('plat_tier_' + i + '_max');
            if (maxEl) maxEl.value = plat['tier_' + i + '_max'] || '';
        }
        const coinsEl = document.getElementById('plat_tier_' + i + '_coins');
        if (coinsEl) coinsEl.value = plat['tier_' + i + '_coins'] || '';
    }

    // Show current logo preview
    if (plat.logo_url) {
        const fullUrl = plat.logo_url.startsWith('http') ? plat.logo_url : '<?= rtrim(base_url(), '/') ?>/' + plat.logo_url;
        document.getElementById('plat_current_logo_preview').src = fullUrl;
        document.getElementById('platCurrentLogoWrap').classList.remove('d-none');
    } else {
        document.getElementById('platCurrentLogoWrap').classList.add('d-none');
    }

    // Reset upload zone
    document.getElementById('platUploadPlaceholder').classList.remove('d-none');
    document.getElementById('platNewLogoPreviewWrap').classList.add('d-none');

    platformModal.show();
}

document.getElementById('addPlatformBtn').addEventListener('click', function () {
    document.getElementById('platformForm').reset();
    document.getElementById('platformModalLabel').textContent = 'Add Platform';
    document.getElementById('plat_id').value = '';
    document.getElementById('plat_current_logo').value = '';
    document.getElementById('platCurrentLogoWrap').classList.add('d-none');
    document.getElementById('platUploadPlaceholder').classList.remove('d-none');
    document.getElementById('platNewLogoPreviewWrap').classList.add('d-none');
    platformModal.show();
});

// Drag & drop support
const platZone = document.getElementById('platLogoUploadZone');
if (platZone) {
    platZone.addEventListener('dragover', e => { e.preventDefault(); platZone.style.borderColor = '#16a34a'; });
    platZone.addEventListener('dragleave', () => { platZone.style.borderColor = '#d1d5db'; });
    platZone.addEventListener('drop', e => {
        e.preventDefault();
        platZone.style.borderColor = '#d1d5db';
        const fileInput = document.getElementById('plat_logo');
        if (e.dataTransfer.files.length) {
            const dt = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            fileInput.files = dt.files;
            handlePlatLogoChange(fileInput);
        }
    });
}
</script>
<?= $this->endSection() ?>
