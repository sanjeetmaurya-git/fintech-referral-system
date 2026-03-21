<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<style>
/* Document Lightbox */
#docLightbox { display:none; position:fixed; inset:0; background:rgba(0,0,0,.85); z-index:9999; align-items:center; justify-content:center; flex-direction:column; padding:1rem; }
#docLightbox.active { display:flex; }
#docLightbox img { max-width:90vw; max-height:82vh; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,.5); object-fit:contain; }
#docLightbox .lb-close { position:absolute; top:1rem; right:1.4rem; font-size:2.2rem; color:#fff; cursor:pointer; line-height:1; font-weight:300; }
#docLightbox .lb-title { color:#e8e8e8; font-size:.85rem; text-transform:uppercase; letter-spacing:.1em; margin-top:.8rem; }
#docLightbox .lb-open-new { color:#90cdf4; text-decoration:none; font-size:.8rem; margin-top:.3rem; }
#docLightbox .lb-open-new:hover { color:#fff; }

/* Doc Card */
.doc-card { position:relative; border-radius:16px; overflow:hidden; background:#fff; box-shadow:0 4px 20px rgba(0,0,0,.06); transition:.25s; }
.doc-card:hover { box-shadow:0 8px 32px rgba(0,0,0,.12); transform:translateY(-2px); }
.doc-card .doc-thumb { cursor:pointer; position:relative; background:#f8f9fa; }
.doc-card .doc-thumb img { width:100%; height:200px; object-fit:cover; display:block; }
.doc-card .doc-thumb .pdf-thumb { height:200px; display:flex; flex-direction:column; align-items:center; justify-content:center; background:#fff5f5; }
.doc-card .doc-overlay { position:absolute; inset:0; background:rgba(0,0,0,0); display:flex; align-items:center; justify-content:center; transition:.2s; }
.doc-card .doc-thumb:hover .doc-overlay { background:rgba(0,0,0,.35); }
.doc-card .doc-overlay i { color:#fff; font-size:2rem; opacity:0; transition:.2s; }
.doc-card .doc-thumb:hover .doc-overlay i { opacity:1; }
.doc-card .doc-footer { padding:.75rem 1rem; border-top:1px solid #f0f0f0; display:flex; align-items:center; justify-content:space-between; }

/* Verification Toggle */
.verify-toggle { display:flex; align-items:center; gap:.5rem; cursor:pointer; }
.verify-toggle input[type=checkbox] { width:18px; height:18px; accent-color:#28a745; cursor:pointer; }
.verified-badge { display:inline-flex; align-items:center; gap:.3rem; font-size:.75rem; font-weight:600; color:#28a745; }
.unverified-badge { font-size:.75rem; color:#999; }
.verify-spinner { width:16px; height:16px; border:2px solid #ccc; border-top-color:#28a745; border-radius:50%; animation:spin .6s linear infinite; display:none; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/workers') ?>">Worker Applications</a></li>
            <li class="breadcrumb-item active">Verification Detail</li>
        </ol>
    </nav>
    <h4 class="fw-bold">Worker Profile Verification</h4>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Profile Detail Card -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="d-flex align-items-center gap-4 mb-4">
                <?php
                    $profileImg = null;
                    foreach ($documents as $doc) {
                        if ($doc['document_type'] === 'profile_image') {
                            $profileImg = $doc['file_path'];
                            break;
                        }
                    }
                ?>
                <img src="<?= $profileImg ? base_url($profileImg) : 'https://placehold.co/100x100?text=Worker' ?>"
                     class="rounded-circle shadow-sm border p-1" width="100" height="100" style="object-fit: cover;">
                <div>
                    <h5 class="fw-bold mb-1"><?= esc($worker['full_name'] ?? 'Incomplete Profile') ?></h5>
                    <p class="text-muted mb-2">
                        <i class="bi bi-phone me-1"></i><?= esc($worker['phone']) ?> |
                        <i class="bi bi-envelope me-1"></i><?= esc($worker['email'] ?? 'N/A') ?>
                    </p>
                    <span class="badge bg-<?= $worker['status'] === 'approved' ? 'success' : ($worker['status'] === 'pending' ? 'warning text-dark' : 'danger') ?> px-3 py-2 rounded-pill">
                        Status: <?= strtoupper($worker['status']) ?>
                    </span>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase mb-1">Professional Skill</label>
                    <p class="fw-medium"><?= esc($worker['category_name']) ?> (<?= esc($worker['subcategory_name']) ?>)</p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase mb-1">Experience</label>
                    <p class="fw-medium"><?= esc($worker['experience']) ?> Years</p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase mb-1">Qualification</label>
                    <p class="fw-medium"><?= esc($worker['highest_qualification']) ?></p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small fw-bold text-uppercase mb-1">Identity Details</label>
                    <p class="fw-medium mb-0">Aadhaar: <?= esc($worker['aadhar_number']) ?></p>
                    <p class="fw-medium">PAN: <?= esc($worker['pan_number']) ?></p>
                </div>
                <div class="col-12">
                    <label class="text-muted small fw-bold text-uppercase mb-1">Service Region</label>
                    <p class="fw-medium"><?= esc($worker['address']) ?>, <?= esc($worker['district']) ?>, <?= esc($worker['state']) ?> - <?= esc($worker['pincode']) ?></p>
                </div>
                <div class="col-12">
                    <label class="text-muted small fw-bold text-uppercase mb-1">Skill Description</label>
                    <p class="fw-medium border rounded p-3 bg-light"><?= nl2br(esc($worker['skills'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Verification Documents -->
        <div class="d-flex align-items-center justify-content-between mb-3 mt-2">
            <h5 class="fw-bold mb-0">Submitted Documents</h5>
            <?php
                $verifiedCount = count(array_filter($documents, fn($d) => $d['document_type'] !== 'profile_image' && !empty($d['is_verified'])));
                $totalDocs     = count(array_filter($documents, fn($d) => $d['document_type'] !== 'profile_image'));
            ?>
            <span class="badge bg-<?= $verifiedCount === $totalDocs && $totalDocs > 0 ? 'success' : 'secondary' ?> rounded-pill px-3">
                <?= $verifiedCount ?>/<?= $totalDocs ?> Verified
            </span>
        </div>

        <div class="row g-3" id="docs-grid">
            <?php foreach ($documents as $doc): ?>
                <?php if ($doc['document_type'] === 'profile_image') continue; ?>
                <?php $isPdf = stripos($doc['file_path'], '.pdf') !== false; ?>
                <div class="col-md-6" id="doc-card-<?= $doc['id'] ?>">
                    <div class="doc-card">
                        <!-- Clickable thumbnail -->
                        <div class="doc-thumb"
                             onclick="openLightbox('<?= base_url($doc['file_path']) ?>', '<?= esc(str_replace('_', ' ', $doc['document_type'])) ?>', <?= $isPdf ? 'true' : 'false' ?>')"
                             title="Click to view full size">
                            <?php if ($isPdf): ?>
                                <div class="pdf-thumb">
                                    <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                                    <p class="mt-2 small text-muted">PDF Document</p>
                                </div>
                            <?php else: ?>
                                <img src="<?= base_url($doc['file_path']) ?>" alt="<?= esc($doc['document_type']) ?>">
                            <?php endif; ?>
                            <div class="doc-overlay"><i class="bi bi-arrows-fullscreen"></i></div>
                        </div>

                        <!-- Footer with doc name + verify checkbox -->
                        <div class="doc-footer">
                            <div>
                                <span class="fw-semibold small text-uppercase"><?= str_replace('_', ' ', $doc['document_type']) ?></span>
                                <div class="mt-1" id="status-<?= $doc['id'] ?>">
                                    <?php if (!empty($doc['is_verified'])): ?>
                                        <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified</span>
                                    <?php else: ?>
                                        <span class="unverified-badge">Not yet verified</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="verify-spinner" id="spin-<?= $doc['id'] ?>"></div>
                                <label class="verify-toggle mb-0" title="Mark as verified">
                                    <input type="checkbox"
                                           id="chk-<?= $doc['id'] ?>"
                                           data-doc-id="<?= $doc['id'] ?>"
                                           <?= !empty($doc['is_verified']) ? 'checked' : '' ?>
                                           onchange="toggleVerify(<?= $doc['id'] ?>)">
                                    <span class="small text-muted">Verify</span>
                                </label>
                                <a href="<?= base_url($doc['file_path']) ?>" target="_blank"
                                   class="btn btn-sm btn-outline-primary py-0 px-2" title="Open in new tab">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if ($totalDocs === 0): ?>
                <div class="col-12">
                    <div class="alert alert-warning border-0 rounded-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>No documents uploaded yet.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Actions Panel -->
        <div class="card border-0 shadow-sm rounded-4 p-4 sticky-top" style="top: 100px;">
            <h5 class="fw-bold mb-4">Application Action</h5>

            <?php if ($worker['status'] === 'pending'): ?>
                <div class="alert alert-warning border-0 small mb-4">
                    <i class="bi bi-info-circle me-2"></i>Verify documents using the checkboxes, then approve or reject this professional.
                </div>

                <form action="<?= base_url('admin/workers/approve/' . $worker['id']) ?>" method="POST" class="d-grid gap-2 mb-2">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold" onclick="return confirm('Confirm Approval?')">
                        <i class="bi bi-person-check me-2"></i>Approve Professional
                    </button>
                </form>

                <button type="button" class="btn btn-outline-danger btn-lg rounded-pill fw-bold w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle me-2"></i>Reject Application
                </button>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-shield-lock-fill fs-1 mb-3 d-block"></i>
                    <p>Application is currently <strong><?= strtoupper($worker['status']) ?></strong>.</p>
                </div>
            <?php endif; ?>

            <!-- Quick document stats -->
            <hr>
            <h6 class="fw-bold mb-3 small text-uppercase text-muted">Document Checklist</h6>
            <?php foreach ($documents as $doc): ?>
                <?php if ($doc['document_type'] === 'profile_image') continue; ?>
                <div class="d-flex align-items-center justify-content-between mb-2" id="sidebar-doc-<?= $doc['id'] ?>">
                    <span class="small"><?= ucwords(str_replace('_', ' ', $doc['document_type'])) ?></span>
                    <?php if (!empty($doc['is_verified'])): ?>
                        <span class="badge bg-success-subtle text-success" id="sidebar-badge-<?= $doc['id'] ?>"><i class="bi bi-check-circle-fill me-1"></i>Verified</span>
                    <?php else: ?>
                        <span class="badge bg-warning-subtle text-warning" id="sidebar-badge-<?= $doc['id'] ?>"><i class="bi bi-clock me-1"></i>Pending</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Reject Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/workers/reject/' . $worker['id']) ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body py-4">
                    <p class="text-muted mb-4">Are you sure you want to reject this application? Please specify the reason (optional).</p>
                    <textarea name="reason" class="form-control rounded-4 p-3" rows="3" placeholder="Document missing, Invalid location, etc..."></textarea>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Document Lightbox -->
<div id="docLightbox" onclick="closeLightbox(event)">
    <span class="lb-close" onclick="closeLightbox()">&times;</span>
    <img id="lbImg" src="" alt="">
    <div id="lbPdf" style="display:none; flex-direction:column; align-items:center;">
        <i class="bi bi-file-earmark-pdf" style="font-size:5rem; color:#fc8181;"></i>
        <p style="color:#e8e8e8; margin-top:.5rem;">PDF Document – open in new tab to view</p>
    </div>
    <p class="lb-title" id="lbTitle"></p>
    <a class="lb-open-new" id="lbOpenNew" href="#" target="_blank"><i class="bi bi-box-arrow-up-right me-1"></i>Open in new tab</a>
</div>

<script>
const VERIFY_URL = '<?= base_url('admin/workers/verify-document/') ?>';
const CSRF_NAME  = '<?= csrf_token() ?>';
let csrfHash     = '<?= csrf_hash() ?>';

function openLightbox(url, title, isPdf) {
    const lb = document.getElementById('docLightbox');
    document.getElementById('lbTitle').textContent = title;
    document.getElementById('lbOpenNew').href = url;

    if (isPdf) {
        document.getElementById('lbImg').style.display = 'none';
        document.getElementById('lbPdf').style.display = 'flex';
    } else {
        document.getElementById('lbImg').style.display = 'block';
        document.getElementById('lbImg').src            = url;
        document.getElementById('lbPdf').style.display = 'none';
    }
    lb.classList.add('active');
}

function closeLightbox(e) {
    if (!e || e.target === document.getElementById('docLightbox') || e.currentTarget.classList.contains('lb-close')) {
        document.getElementById('docLightbox').classList.remove('active');
        document.getElementById('lbImg').src = '';
    }
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

async function toggleVerify(docId) {
    const chk     = document.getElementById('chk-' + docId);
    const spinner = document.getElementById('spin-' + docId);
    const status  = document.getElementById('status-' + docId);
    const sbBadge = document.getElementById('sidebar-badge-' + docId);

    chk.disabled   = true;
    spinner.style.display = 'block';

    try {
        const res  = await fetch(VERIFY_URL + docId, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: CSRF_NAME + '=' + csrfHash
        });
        const data = await res.json();
        if (data.success) {
            csrfHash = data.csrf_hash ?? csrfHash; // refresh if returned
            if (data.is_verified) {
                status.innerHTML  = '<span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified</span>';
                sbBadge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Verified';
                sbBadge.className = 'badge bg-success-subtle text-success';
            } else {
                status.innerHTML  = '<span class="unverified-badge">Not yet verified</span>';
                sbBadge.innerHTML = '<i class="bi bi-clock me-1"></i>Pending';
                sbBadge.className = 'badge bg-warning-subtle text-warning';
            }
            chk.checked = data.is_verified;
        } else {
            chk.checked = !chk.checked; // revert
        }
    } catch (err) {
        chk.checked = !chk.checked; // revert on network error
    } finally {
        spinner.style.display = 'none';
        chk.disabled = false;
    }
}
</script>

<?= $this->endSection() ?>
