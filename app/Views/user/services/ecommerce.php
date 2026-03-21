<?= $this->extend('user/layout') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <!-- Back Nav + Header -->
    <div class="mb-4">
        <a href="<?= base_url('services') ?>" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-1 mb-3">
            <i class="bi bi-arrow-left"></i> Back to Services
        </a>
        <h2 class="fw-bold mb-1">Affiliate Shopping Partners</h2>
        <p class="text-muted mb-0">Shop through our partner links and earn coin rewards on every verified purchase.</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Platforms Grid -->
    <?php if (empty($platforms)): ?>
        <div class="text-center py-5">
            <div class="mb-3" style="font-size: 4rem;">🏪</div>
            <h5 class="fw-bold text-muted">No Partners Available Yet</h5>
            <p class="text-muted small">Check back soon — new affiliate partners are being added!</p>
        </div>
    <?php else: ?>
    <div class="row g-4 mb-5">
        <?php foreach ($platforms as $plat): ?>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 partner-card" style="border-radius: 18px; transition: transform 0.25s ease, box-shadow 0.25s ease;">
                <div class="card-body p-4 text-center d-flex flex-column">
                    <!-- Logo -->
                    <div class="platform-logo mx-auto mb-3 d-flex align-items-center justify-content-center bg-light rounded-4" style="width: 90px; height: 90px; transition: transform 0.2s;">
                        <?php if ($plat['logo_url']): ?>
                            <img src="<?= base_url($plat['logo_url']) ?>" alt="<?= esc($plat['name']) ?>" style="max-width: 65%; max-height: 65%; object-fit: contain;">
                        <?php else: ?>
                            <i class="bi bi-shop text-success" style="font-size: 2.2rem;"></i>
                        <?php endif; ?>
                    </div>

                    <h4 class="fw-bold mb-1"><?= esc($plat['name']) ?></h4>
                    <p class="text-muted small mb-3"><?= esc($plat['category']) ?></p>

                    <!-- Max Reward Badge -->
                    <div class="d-inline-flex align-items-center gap-1 mb-4 px-3 py-2 rounded-pill mx-auto" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46;">
                        <i class="bi bi-coin small"></i>
                        <span class="fw-bold">Up to <?= number_format($plat['tier_3_coins']) ?> Coins</span>
                    </div>

                    <!-- Tier Table -->
                    <div class="reward-tiers mb-4 text-start flex-grow-1">
                        <div class="small fw-semibold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px; font-size: 0.7rem;">How Coins Are Earned</div>
                        <div class="d-flex flex-column gap-2">
                            <?php 
                            $tierDefs = [
                                ['max' => $plat['tier_1_max'], 'coins' => $plat['tier_1_coins'], 'label' => 'Up to ₹' . number_format($plat['tier_1_max']), 'color' => '#6366f1'],
                                ['max' => $plat['tier_2_max'], 'coins' => $plat['tier_2_coins'], 'label' => 'Up to ₹' . number_format($plat['tier_2_max']), 'color' => '#f59e0b'],
                                ['max' => 999999,               'coins' => $plat['tier_3_coins'], 'label' => 'Above ₹' . number_format($plat['tier_2_max']),  'color' => '#10b981'],
                            ];
                            foreach ($tierDefs as $t): ?>
                            <div class="d-flex justify-content-between align-items-center py-1 px-2 rounded-2" style="background: #f9fafb;">
                                <span class="small text-muted"><?= $t['label'] ?></span>
                                <span class="fw-bold small" style="color: <?= $t['color'] ?>;"><?= number_format($t['coins']) ?> 🪙</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <a href="<?= base_url('services/ecommerce/redirect/' . $plat['id']) ?>"
                       target="_blank"
                       class="btn btn-success btn-lg w-100 shop-btn mt-auto"
                       style="border-radius: 12px; font-weight: 600; letter-spacing: 0.3px;">
                        Shop Now <i class="bi bi-box-arrow-up-right ms-2 small"></i>
                    </a>
                </div>
                <div class="card-footer bg-transparent border-top px-4 py-2 text-center">
                    <span class="text-muted" style="font-size: 0.72rem;"><i class="bi bi-shield-check me-1 text-success"></i> Verified Affiliate Tracking Active</span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- How It Works Section -->
    <div class="p-4 p-md-5 shadow-sm" style="background: linear-gradient(135deg, #ffffff, #f8faff); border-radius: 20px;">
        <div class="row align-items-center g-4">
            <div class="col-md-8">
                <h5 class="fw-bold mb-4"><i class="bi bi-info-circle text-primary me-2"></i>How It Works</h5>
                <div class="d-flex flex-column gap-3">
                    <?php $steps = [
                        ['icon' => 'bi-cursor-fill', 'color' => '#6366f1', 'bg' => '#ede9fe', 'text' => 'Click <strong>Shop Now</strong> on any partner platform below.'],
                        ['icon' => 'bi-arrow-right-circle-fill', 'color' => '#f59e0b', 'bg' => '#fef9c3', 'text' => 'You\'ll be redirected to the official website through our affiliate link.'],
                        ['icon' => 'bi-bag-check-fill', 'color' => '#10b981', 'bg' => '#dcfce7', 'text' => 'Complete your purchase as usual — no extra steps needed.'],
                        ['icon' => 'bi-coin', 'color' => '#ec4899', 'bg' => '#fce7f3', 'text' => 'Coins are credited within <strong>48–72 hours</strong> after purchase verification.'],
                    ]; ?>
                    <?php foreach ($steps as $s): ?>
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0 d-flex align-items-center justify-content-center rounded-3" style="width: 40px; height: 40px; background: <?= $s['bg'] ?>;">
                            <i class="bi <?= $s['icon'] ?>" style="color: <?= $s['color'] ?>; font-size: 1.1rem;"></i>
                        </div>
                        <p class="mb-0 small text-muted pt-2"><?= $s['text'] ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-4 text-center d-none d-md-block">
                <div style="font-size: 6rem; line-height: 1;">🛒</div>
                <div class="text-muted small mt-2">Shop. Earn. Repeat.</div>
            </div>
        </div>
    </div>
</div>

<style>
.partner-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 48px rgba(0,0,0,.13) !important;
}
.partner-card:hover .platform-logo { transform: scale(1.08); }
.partner-card:hover .shop-btn { background-color: #15803d !important; }
@media (max-width: 576px) {
    .partner-card { border-radius: 14px !important; }
}
</style>
<?= $this->endSection() ?>
