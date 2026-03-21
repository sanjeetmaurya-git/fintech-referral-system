<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Support Inbox</h4>
    <div class="small text-muted">Manage user queries and technical issues.</div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Ticket</th>
                        <th>User (Phone)</th>
                        <th>Subject</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $t): ?>
                    <tr>
                        <td class="ps-4">#<?= $t['id'] ?></td>
                        <td><div class="fw-bold"><?= esc($t['phone']) ?></div></td>
                        <td><?= esc($t['subject']) ?></td>
                        <td>
                            <?php 
                                $prioClass = 'secondary';
                                if ($t['priority'] === 'high') $prioClass = 'warning';
                                if ($t['priority'] === 'urgent') $prioClass = 'danger';
                            ?>
                            <span class="badge bg-<?= $prioClass ?> small text-uppercase" style="font-size: 0.6rem;">
                                <?= $t['priority'] ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                                $statusClass = 'secondary';
                                if ($t['status'] === 'open') $statusClass = 'danger';
                                if ($t['status'] === 'resolved') $statusClass = 'success';
                                if ($t['status'] === 'pending_user') $statusClass = 'info';
                            ?>
                            <span class="badge bg-<?= $statusClass ?> rounded-pill px-3">
                                <?= ucwords(str_replace('_', ' ', $t['status'])) ?>
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button type="button" 
                                    class="btn btn-primary btn-sm rounded-pill px-3 open-ticket" 
                                    data-id="<?= $t['id'] ?>"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#replyModal">
                                Reply
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($tickets)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Inbox is empty. Great job!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalSubject">Ticket Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2 small text-muted">
                    User: <span id="modalUserPhone" class="fw-bold text-dark"></span> | 
                    Status: <span id="modalStatus" class="badge bg-light text-dark border"></span>
                </div>
                
                <div id="modalChatHistory" class="p-3 mb-3 bg-light rounded-4 overflow-auto" style="max-height: 400px; display: flex; flex-direction: column;">
                    <!-- Loading Spinner -->
                    <div class="text-center py-5" id="modalLoading">
                        <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                        <div class="mt-2 small text-muted">Loading thread...</div>
                    </div>
                </div>

                <form id="replyForm" method="POST" action="">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <textarea name="message" class="form-control border-0 bg-light" rows="3" placeholder="Type your response..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="mark_resolved" value="1" id="modalResolveCheck">
                            <label class="form-check-label small" for="modalResolveCheck">Mark as Resolved</label>
                        </div>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold">Send Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
#modalChatHistory::-webkit-scrollbar { width: 4px; }
#modalChatHistory::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
.msg-bubble { max-width: 85%; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('replyModal');
    const loading = document.getElementById('modalLoading');
    const chatHistory = document.getElementById('modalChatHistory');
    const subject = document.getElementById('modalSubject');
    const userPhone = document.getElementById('modalUserPhone');
    const status = document.getElementById('modalStatus');
    const replyForm = document.getElementById('replyForm');

    document.querySelectorAll('.open-ticket').forEach(button => {
        button.addEventListener('click', function() {
            const ticketId = this.getAttribute('data-id');
            
            // Set basic info
            loading.style.display = 'block';
            chatHistory.querySelectorAll('.d-flex').forEach(e => e.remove());
            replyForm.action = `<?= base_url('admin/support/reply/') ?>/${ticketId}`;

            // Fetch thread
            fetch(`<?= base_url('admin/support/get_thread/') ?>/${ticketId}`)
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    subject.innerText = data.ticket.subject;
                    userPhone.innerText = data.ticket.user_phone;
                    status.innerText = data.ticket.status.toUpperCase();
                    
                    data.messages.forEach(msg => {
                        const is_admin = parseInt(msg.is_admin_reply);
                        const wrapper = document.createElement('div');
                        wrapper.className = `d-flex mt-2 ${is_admin ? 'justify-content-end' : 'justify-content-start'}`;
                        
                        const date = new Date(msg.created_at).toLocaleString('en-US', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });

                        wrapper.innerHTML = `
                            <div class="msg-bubble p-2 px-3 rounded-4 ${is_admin ? 'bg-primary text-white' : 'bg-white border text-dark'}">
                                <div class="small fw-bold opacity-75" style="font-size: 0.65rem;">${is_admin ? 'SUPPORT' : 'USER'}</div>
                                <div class="small">${msg.message.replace(/\n/g, '<br>')}</div>
                                <div class="text-end opacity-50" style="font-size: 0.55rem;">${date}</div>
                            </div>
                        `;
                        chatHistory.appendChild(wrapper);
                    });
                    
                    chatHistory.scrollTop = chatHistory.scrollHeight;
                });
        });
    });
});
</script>

<?= $this->endSection() ?>
