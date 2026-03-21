<?= $this->extend('user/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex align-items-center mb-4">
    <a href="<?= base_url('support') ?>" class="btn btn-light btn-sm rounded-circle me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h5 class="fw-bold mb-0"><?= esc($ticket['subject']) ?></h5>
        <span class="text-muted small">Status: <?= ucwords($ticket['status']) ?> | CID: #<?= $ticket['id'] ?></span>
    </div>
</div>

<div class="row">
    <div class="col-md-10 mx-auto">
        <!-- Message Thread -->
        <div class="chat-container mb-4">
            <?php foreach ($messages as $msg): ?>
                <div class="d-flex <?= $msg['is_admin_reply'] ? 'justify-content-start' : 'justify-content-end' ?> mb-3">
                    <div class="message-box p-3 rounded-4 shadow-sm <?= $msg['is_admin_reply'] ? 'bg-white border' : 'bg-primary text-white' ?>" style="max-width: 80%;">
                        <div class="small fw-bold mb-1">
                            <?= $msg['is_admin_reply'] ? '<i class="bi bi-headset me-1"></i> Support Team' : 'You' ?>
                        </div>
                        <div class="message-content"><?= nl2br(esc($msg['message'])) ?></div>
                        <div class="text-end small opacity-75 mt-2" style="font-size: 0.7rem;">
                            <?= date('h:i A', strtotime($msg['created_at'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Reply Form -->
        <?php if ($ticket['status'] !== 'resolved' && $ticket['status'] !== 'closed'): ?>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <form action="<?= base_url('support/reply/' . $ticket['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="input-group">
                        <textarea name="message" class="form-control border-0 bg-light w-75 w-sm-100" rows="2" placeholder="Write your reply..."></textarea>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-send-fill"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <?php else: ?>
            <div class="alert alert-light text-center border-0 small">This ticket has been marked as resolved.</div>
        <?php endif; ?>
    </div>
</div>

<style>
.chat-container {
    max-height: 500px;
    overflow-y: auto;
    padding: 10px;
}
</style>

<?= $this->endSection() ?>
