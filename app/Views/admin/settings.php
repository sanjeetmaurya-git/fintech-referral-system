<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>

<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h4 class="fw-bold mb-0">System Settings</h4>
        <p class="text-muted small">Configure rewards, withdrawals, and general system parameters.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <button type="submit" form="settings-form" class="btn btn-primary px-4">
            <i class="bi bi-save me-1"></i> Save All Changes
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom-0 pt-3">
        <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active fw-bold" id="general-tab" data-bs-toggle="tab" href="#general" role="tab">1. General Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-bold" id="rewards-tab" data-bs-toggle="tab" href="#rewards" role="tab">2. Rewards Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-bold" id="withdrawals-tab" data-bs-toggle="tab" href="#withdrawals" role="tab">3. Withdrawals Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-bold" id="users-tab" href="<?= base_url('admin/user-settings') ?>">4. User Settings</a>
            </li>
        </ul>
    </div>
    <div class="card-body p-0">
        <form action="<?= base_url('admin/settings/update') ?>" method="POST" id="settings-form">
            <?= csrf_field() ?>
            <div class="tab-content" id="settingsTabsContent">
                
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    <?php renderSettingsGroup('general', $settings); ?>
                </div>

                <!-- Rewards Settings -->
                <div class="tab-pane fade" id="rewards" role="tabpanel">
                    <?php renderSettingsGroup('rewards', $settings); ?>

                    <div class="p-4 border-top">
                        <h6 class="fw-bold mb-3 text-uppercase small text-primary">Transaction-Based Coin Reward Tiers</h6>
                        <p class="text-muted small mb-4">Set dynamic coin rewards based on the transaction amount. Maximum 8 tiers.</p>
                        
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle" id="tiers-table">
                                <thead class="bg-light">
                                    <tr class="small text-uppercase">
                                        <th class="ps-3">Min Amount (₹)</th>
                                        <th>Max Amount (₹)</th>
                                        <th>Reward (Coins)</th>
                                        <th class="text-center" style="width: 50px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($tiers)): ?>
                                        <tr class="tier-row">
                                            <td><input type="number" name="tiers[0][min]" class="form-control form-control-sm border-0" value="0"></td>
                                            <td><input type="number" name="tiers[0][max]" class="form-control form-control-sm border-0" value="100"></td>
                                            <td><input type="number" name="tiers[0][reward_coins]" class="form-control form-control-sm border-0" value="0"></td>
                                            <td class="text-center"><button type="button" class="btn btn-link btn-sm text-danger remove-tier"><i class="bi bi-trash"></i></button></td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($tiers as $index => $tier): ?>
                                            <tr class="tier-row">
                                                <td><input type="number" name="tiers[<?= $index ?>][min]" class="form-control form-control-sm border-0" value="<?= $tier['min_amount'] ?>"></td>
                                                <td><input type="number" name="tiers[<?= $index ?>][max]" class="form-control form-control-sm border-0" value="<?= $tier['max_amount'] ?>"></td>
                                                <td><input type="number" name="tiers[<?= $index ?>][reward_coins]" class="form-control form-control-sm border-0" value="<?= $tier['reward_coins'] ?>"></td>
                                                <td class="text-center"><button type="button" class="btn btn-link btn-sm text-danger remove-tier"><i class="bi bi-trash"></i></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-2" id="add-tier-btn">
                            <i class="bi bi-plus-lg me-1"></i> Add Tier
                        </button>
                    </div>
                </div>

                <!-- Withdrawals Settings -->
                <div class="tab-pane fade" id="withdrawals" role="tabpanel">
                    <?php renderSettingsGroup('withdrawals', $settings); ?>
                    <div class="p-4 border-top bg-light">
                        <h6 class="fw-bold mb-3 text-uppercase small text-primary">Withdrawal Security & Gating</h6>
                        <p class="text-muted small mb-0">These rules define when a user can withdraw their <strong>referral earnings</strong>. Deposited funds are always unrestricted.</p>
                    </div>
                    <?php renderSettingsGroup('security', $settings); ?>
                </div>

            </div>
        </form>
    </div>
</div>

<?php
function renderSettingsGroup($group, $settings) {
    $filtered = array_filter($settings, function($s) use ($group) {
        return $s['group'] === $group;
    });
    ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3" style="width: 300px;">Setting Key</th>
                    <th class="py-3">Value</th>
                    <th class="py-3">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($filtered)): ?>
                    <tr><td colspan="3" class="text-center py-4 text-muted">No settings found in this category.</td></tr>
                <?php else: ?>
                    <?php foreach ($filtered as $s): ?>
                    <tr>
                        <td class="ps-4 py-3">
                            <label class="fw-semibold d-block mb-0"><?= esc(ucwords(str_replace('_', ' ', $s['key']))) ?></label>
                            <code class="small text-muted"><?= esc($s['key']) ?></code>
                        </td>
                        <td class="py-3" style="width: 250px;">
                            <div class="input-group input-group-sm">
                                <input type="text" 
                                       name="settings[<?= esc($s['key']) ?>]" 
                                       value="<?= esc($s['value']) ?>" 
                                       class="form-control <?= strpos($s['key'], 'reward_level_') !== false ? 'reward-input' : '' ?>"
                                       placeholder="Value">
                                <?php if (strpos($s['key'], 'level') !== false): ?>
                                    <span class="input-group-text">%</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-3 pe-4 text-muted small">
                            <?= esc($s['description']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.reward-input');
    const saveBtn = document.querySelector('button[type="submit"]');
    
    // Create a status bar for Reward distribution
    const rewardsTab = document.getElementById('rewards');
    const statusRow = document.createElement('div');
    statusRow.className = 'p-3 bg-light border-bottom d-flex justify-content-between align-items-center';
    statusRow.innerHTML = `
        <span class="small fw-bold text-muted text-uppercase">Total Distribution (Levels 1-8):</span>
        <span id="total-percent-badge" class="badge rounded-pill fs-6 px-3 bg-primary">0%</span>
    `;
    rewardsTab.insertBefore(statusRow, rewardsTab.firstChild);

    function calculateTotal() {
        let total = 0;
        inputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        const badge = document.getElementById('total-percent-badge');
        if (!badge) return;
        
        badge.innerText = total.toFixed(1) + '%';

        if (total > 100) {
            badge.className = 'badge rounded-pill fs-6 px-3 bg-danger';
            saveBtn.disabled = true;
            saveBtn.title = 'Total reward percentage cannot exceed 100%';
        } else if (total === 100) {
            badge.className = 'badge rounded-pill fs-6 px-3 bg-success';
            saveBtn.disabled = false;
        } else {
            badge.className = 'badge rounded-pill fs-6 px-3 bg-primary';
            saveBtn.disabled = false;
        }
    }

    inputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    // Dynamic Tier Management
    const tiersTable = document.getElementById('tiers-table').getElementsByTagName('tbody')[0];
    const addTierBtn = document.getElementById('add-tier-btn');

    addTierBtn.addEventListener('click', function() {
        const rowCount = tiersTable.getElementsByClassName('tier-row').length;
        if (rowCount >= 8) {
            alert('Maximum 8 tiers allowed.');
            return;
        }

        const newRow = document.createElement('tr');
        newRow.className = 'tier-row';
        newRow.innerHTML = `
            <td><input type="number" name="tiers[${rowCount}][min]" class="form-control form-control-sm border-0" value="0"></td>
            <td><input type="number" name="tiers[${rowCount}][max]" class="form-control form-control-sm border-0" value="0"></td>
            <td><input type="number" name="tiers[${rowCount}][reward_coins]" class="form-control form-control-sm border-0" value="0"></td>
            <td class="text-center"><button type="button" class="btn btn-link btn-sm text-danger remove-tier"><i class="bi bi-trash"></i></button></td>
        `;
        tiersTable.appendChild(newRow);
        attachRemoveEvent(newRow.querySelector('.remove-tier'));
    });

    function attachRemoveEvent(btn) {
        btn.addEventListener('click', function() {
            const rows = tiersTable.getElementsByClassName('tier-row');
            if (rows.length > 1) {
                btn.closest('tr').remove();
                // Re-index names to avoid gaps
                Array.from(rows).forEach((row, index) => {
                    row.querySelector('input[name*="[min]"]').name = `tiers[${index}][min]`;
                    row.querySelector('input[name*="[max]"]').name = `tiers[${index}][max]`;
                    row.querySelector('input[name*="[reward_coins]"]').name = `tiers[${index}][reward_coins]`;
                });
            } else {
                alert('At least one tier row must remain.');
            }
        });
    }

    document.querySelectorAll('.remove-tier').forEach(btn => attachRemoveEvent(btn));

    calculateTotal(); // Initial run
});
</script>
<?= $this->endSection() ?>
