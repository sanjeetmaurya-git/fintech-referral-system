<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center mb-4">
    <a href="<?= base_url('admin/support') ?>" class="btn btn-light btn-sm rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-bold mb-0"><?= esc($ticket['subject']) ?></h5>
        <span class="text-muted small">User: <?= esc($ticket['user_phone']) ?> | CID: #<?= $ticket['id'] ?></span>
    </div>
</div>

<div class="row">
    <div class="col-md-9 mx-auto">
        <!-- Message Thread -->
        <div class="chat-container mb-4" id="chatbox">
            <?php foreach ($messages as $msg): ?>
                <div class="d-flex <?= $msg['is_admin_reply'] ? 'justify-content-end' : 'justify-content-start' ?> mb-3">
                    <div class="message-box p-3 rounded-4 shadow-sm <?= $msg['is_admin_reply'] ? 'bg-primary text-white' : 'bg-white border' ?>" style="max-width: 80%;">
                        <div class="small fw-bold mb-1">
                            <?= $msg['is_admin_reply'] ? 'You (Support)' : 'User' ?>
                        </div>
                        <div class="message-content"><?= nl2br(esc($msg['message'])) ?></div>
                        <div class="text-end small opacity-75 mt-2" style="font-size: 0.7rem;">
                            <?= date('d M, h:i A', strtotime($msg['created_at'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Admin Reply Form -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <form action="<?= base_url('admin/support/reply/' . $ticket['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <textarea name="message" class="form-control border-0 bg-light" rows="3" placeholder="Type your response to the user..."></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-switch" type="checkbox" name="mark_resolved" value="1" id="resolveCheck">
                            <label class="form-check-label small" for="resolveCheck">Mark as Resolved</label>
                        </div>
                        <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">Send Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.chat-container {
    max-height: 550px;
    overflow-y: auto;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 1rem;
}
</style>

<?= $this->endSection() ?>
