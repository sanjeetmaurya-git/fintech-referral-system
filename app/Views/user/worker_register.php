<?php $this->extend('user/layout'); ?>
<?php $this->section('content'); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="glass-card p-4 p-md-5 rounded-5 shadow-lg border-0" data-aos="fade-up">
                <div class="text-center mb-5">
                    <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                        <i class="bi bi-briefcase"></i>
                    </div>
                    <h2 class="fw-bold">Worker Registration</h2>
                    <p class="text-muted fs-5">Join our platform and start earning by providing your professional services.</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('worker/store') ?>" method="POST" enctype="multipart/form-data" id="workerRegForm">
                    <?= csrf_field() ?>

                    <!-- Section 1: Basic Details -->
                    <div class="mb-5">
                        <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-person-circle me-2"></i>Basic Details</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Full Name</label>
                                <input type="text" name="full_name" class="form-control rounded-pill px-4" value="<?= old('full_name') ?>" placeholder="As per Aadhaar" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" name="email" class="form-control rounded-pill px-4" value="<?= old('email') ?>" placeholder="example@mail.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mobile Number</label>
                                <input type="text" name="phone" class="form-control rounded-pill px-4" value="<?= old('phone', $phone) ?>" placeholder="10 Digit Mobile" required <?= $isLoggedIn ? 'readonly' : '' ?>>
                                <small class="text-muted ms-3">This will be your login ID</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alternate Mobile</label>
                                <input type="text" name="alternate_mobile" class="form-control rounded-pill px-4" value="<?= old('alternate_mobile') ?>" placeholder="Optional">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control rounded-pill px-4" placeholder="Min 6 characters" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control rounded-pill px-4" placeholder="Repeat password" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Highest Qualification</label>
                                <select name="highest_qualification" class="form-select rounded-pill px-4" required>
                                    <option value="">Select Qualification</option>
                                    <option value="10th">10th Pass</option>
                                    <option value="12th">12th Pass</option>
                                    <option value="ITI/Diploma">ITI / Diploma</option>
                                    <option value="Graduate">Graduate</option>
                                    <option value="Post Graduate">Post Graduate</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Address -->
                    <div class="mb-5">
                        <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-geo-alt-fill me-2"></i>Address Details</h5>
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Full Address</label>
                                <textarea name="address" class="form-control rounded-4 px-4 py-3" rows="2" placeholder="House No, Street, Landmark..." required><?= old('address') ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">District</label>
                                <input type="text" name="district" class="form-control rounded-pill px-4" value="<?= old('district') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">State</label>
                                <input type="text" name="state" class="form-control rounded-pill px-4" value="<?= old('state') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Pincode</label>
                                <input type="text" name="pincode" class="form-control rounded-pill px-4" value="<?= old('pincode') ?>" placeholder="6 Digits" required>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Skills -->
                    <div class="mb-5">
                        <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-tools me-2"></i>Skills & Experience</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select name="category_id" id="category_id" class="form-select rounded-pill px-4" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subcategory</label>
                                <select name="subcategory_id" id="subcategory_id" class="form-select rounded-pill px-4" required>
                                    <option value="">Select Subcategory</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Describe your Skills</label>
                                <textarea name="skills" class="form-control rounded-4 px-4 py-3" rows="3" placeholder="Explain what you can do (Max 250 words)..." required><?= old('skills') ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Years of Experience</label>
                                <input type="number" name="experience" class="form-control rounded-pill px-4" value="<?= old('experience') ?>" placeholder="e.g. 5" required>
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Verification -->
                    <div class="mb-5">
                        <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-shield-check me-2"></i>Identity Verification</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Aadhaar Number</label>
                                <input type="text" name="aadhar_number" class="form-control rounded-pill px-4" value="<?= old('aadhar_number') ?>" placeholder="12 Digit Aadhaar" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">PAN Number</label>
                                <input type="text" name="pan_number" class="form-control rounded-pill px-4" value="<?= old('pan_number') ?>" placeholder="10 Digit PAN" required>
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Documents -->
                    <div class="mb-5">
                        <h5 class="fw-bold mb-4 text-primary"><i class="bi bi-cloud-arrow-up me-2"></i>Upload Documents</h5>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Profile Image</label>
                                <input type="file" name="profile_image" class="form-control rounded-4" accept="image/*" required>
                                <small class="text-muted">High quality passport size photo</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Aadhaar Front</label>
                                <input type="file" name="aadhar_front" class="form-control rounded-4" accept="image/*" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Aadhaar Back</label>
                                <input type="file" name="aadhar_back" class="form-control rounded-4" accept="image/*" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">PAN Card Copy</label>
                                <input type="file" name="pan_card" class="form-control rounded-4" accept="image/*" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Certificate (Optional)</label>
                                <input type="file" name="certificate" class="form-control rounded-4" accept="image/*,application/pdf">
                                <small class="text-muted">Any trade or skill certificate</small>
                            </div>
                        </div>
                    </div>

                    <!-- Declaration -->
                    <div class="mb-5">
                        <div class="form-check p-3 glass-card rounded-4 border-primary border-opacity-25">
                            <input class="form-check-input ms-0 me-3" type="checkbox" name="declaration" id="declaration" required>
                            <label class="form-check-label fw-semibold" for="declaration">
                                I hereby declare that all the information provided above is true and correct to the best of my knowledge. I understand that any false document or info can lead to permanent ban.
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-lg">
                            Submit Application <i class="bi bi-send ms-2"></i>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('category_id').addEventListener('change', function() {
    const catId = this.value;
    const subcatSelect = document.getElementById('subcategory_id');
    subcatSelect.innerHTML = '<option value="">Loading...</option>';
    
    if (catId) {
        fetch('<?= base_url('worker/subcategories/') ?>' + catId)
            .then(response => response.json())
            .then(data => {
                subcatSelect.innerHTML = '<option value="">Select Subcategory</option>';
                data.forEach(sub => {
                    subcatSelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
                });
            });
    } else {
        subcatSelect.innerHTML = '<option value="">Select Subcategory</option>';
    }
});
</script>

<?php $this->endSection(); ?>
