<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center mb-4">
    <a href="<?= base_url('support') ?>" class="btn btn-light btn-sm rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Open New Ticket</h4>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="<?= base_url('support/store') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Subject / Issue</label>
                        <input type="text" name="subject" class="form-control" placeholder="e.g. Missing reward for User ID 123" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Priority</label>
                        <select name="priority" class="form-control">
                            <option value="low">Low - General query</option>
                            <option value="medium" selected>Medium - Most common</option>
                            <option value="high">High - Urgent issue</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Message / Description</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Please provide as much detail as possible..." required></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-3">Submit Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
