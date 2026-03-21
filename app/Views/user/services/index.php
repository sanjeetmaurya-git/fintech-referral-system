<?= $this->extend('user/layout') ?>

<?= $this->section('content') ?>

<!-- Hero Banner — Enhanced with wave and floating icons -->
<div class="services-hero text-white py-5 mb-5 position-relative overflow-hidden"
     style="background: linear-gradient(145deg, #4f46e5 0%, #7c3aed 40%, #c2410c 100%);
            border-radius: 0 0 3rem 3rem;
            margin-top: -1.5rem;
            margin-left: -12px;
            margin-right: -12px;">
    <!-- Animated wave SVG -->
    <svg class="position-absolute bottom-0 start-0 w-100" style="color: #f8fafc; transform: scale(1.02);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none">
        <path fill="currentColor" fill-opacity="0.2" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        <path fill="currentColor" fill-opacity="0.4" d="M0,96L80,90.7C160,85,320,75,480,80C640,85,800,107,960,112C1120,117,1280,107,1360,101.3L1440,96L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
    </svg>
    <!-- Floating icons -->
    <div class="position-absolute top-0 start-0 translate-middle-y opacity-25 d-none d-md-block" style="font-size: 5rem; left: 5% !important;">📱</div>
    <div class="position-absolute bottom-0 end-0 translate-middle-y opacity-25 d-none d-md-block" style="font-size: 5rem; right: 5% !important;">🛍️</div>
    
    <div class="container text-center py-4 position-relative" style="z-index: 2;">
        <div class="mb-3 animate__animated animate__bounceIn" style="font-size: 3.5rem; line-height: 1; filter: drop-shadow(0 8px 12px rgba(0,0,0,0.2));">
            ⚡️🚀
        </div>
        <h1 class="display-5 fw-bold mb-3 text-shadow">B2C Services Hub</h1>
        <p class="lead mb-0 opacity-90 fs-4 fw-light">Recharge, shop & earn coins - all in one place!</p>
    </div>
</div>

<div class="container pb-5">
    <!-- Service Cards Grid - Now with 3 columns -->
    <div class="row g-4 justify-content-center">

        <!-- Mobile Recharge Card -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-lg h-100 service-card overflow-hidden" style="border-radius: 28px;">
                <div class="card-body p-0">
                    <div class="service-icon-header p-4 p-xl-5 text-center position-relative" style="background: linear-gradient(135deg, #dbeafe, #ede9fe);">
                        <div class="service-icon-glow"></div>
                        <div class="icon-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: white; border-radius: 50%; box-shadow: 0 12px 28px rgba(79,70,229,0.3);">
                            <i class="bi bi-phone-vibrate text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="fw-bold text-primary mb-0">Mobile Recharge</h3>
                    </div>
                    <div class="p-4 text-center">
                        <p class="text-muted mb-4">Jio, Airtel, VI, BSNL – instant rewards on every recharge. Higher recharge = more coins!</p>

                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            <span class="badge px-3 py-2 rounded-pill" style="background: #e0e7ff; color: #4f46e5;">
                                <i class="bi bi-lightning-fill me-1"></i>Instant
                            </span>
                            <span class="badge px-3 py-2 rounded-pill" style="background: #e0e7ff; color: #4f46e5;">
                                <i class="bi bi-coin me-1"></i>Tiered Coins
                            </span>
                        </div>

                        <?php if (session()->get('isLoggedIn')): ?>
                            <a href="<?= base_url('services/recharge') ?>" class="btn btn-primary btn-lg w-100 py-3 rounded-pill fw-semibold hover-lift">
                                Open Recharge Portal <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('login') ?>" class="btn btn-outline-primary btn-lg w-100 py-3 rounded-pill fw-semibold hover-lift">
                                Login to Recharge <i class="bi bi-lock-fill ms-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- D2H Recharge Card (New) -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card border-0 shadow-lg h-100 service-card overflow-hidden" style="border-radius: 28px;">
                <div class="card-body p-0">
                    <div class="service-icon-header p-4 p-xl-5 text-center position-relative" style="background: linear-gradient(135deg, #fff3cd, #ffe4cc);">
                        <div class="service-icon-glow"></div>
                        <div class="icon-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: white; border-radius: 50%; box-shadow: 0 12px 28px rgba(249,115,22,0.3);">
                            <i class="bi bi-tv text-warning" style="font-size: 2.5rem; color: #f97316;"></i>
                        </div>
                        <h3 class="fw-bold mb-0" style="color: #f97316;">D2H Recharge</h3>
                    </div>
                    <div class="p-4 text-center">
                        <p class="text-muted mb-4">Tata Play, Airtel DTH, Dish TV & more. Renew and earn coins instantly.</p>

                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            <span class="badge px-3 py-2 rounded-pill" style="background: #ffedd5; color: #f97316;">
                                <i class="bi bi-satellite me-1"></i>All Operators
                            </span>
                            <span class="badge px-3 py-2 rounded-pill" style="background: #ffedd5; color: #f97316;">
                                <i class="bi bi-percent me-1"></i>5% Coins Back
                            </span>
                        </div>

                        <?php if (session()->get('isLoggedIn')): ?>
                            <a href="<?= base_url('services/recharge?type=d2h') ?>" class="btn btn-warning btn-lg w-100 py-3 rounded-pill fw-semibold hover-lift" style="background: #f97316; border-color: #f97316; color: white;">
                                Pay D2H Bills <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('login') ?>" class="btn btn-outline-warning btn-lg w-100 py-3 rounded-pill fw-semibold hover-lift" style="border-color: #f97316; color: #f97316;">
                                Login to Pay <i class="bi bi-lock-fill ms-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Affiliate Shopping Card -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card border-0 shadow-lg h-100 service-card overflow-hidden" style="border-radius: 28px;">
                <div class="card-body p-0">
                    <div class="service-icon-header p-4 p-xl-5 text-center position-relative" style="background: linear-gradient(135deg, #dcfce7, #d1fae5);">
                        <div class="service-icon-glow"></div>
                        <div class="icon-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: white; border-radius: 50%; box-shadow: 0 12px 28px rgba(16,185,129,0.3);">
                            <i class="bi bi-bag-heart text-success" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-0">Affiliate Shopping</h3>
                    </div>
                    <div class="p-4 text-center">
                        <p class="text-muted mb-4">Shop on Amazon, Flipkart, Myntra via our links & earn coins directly in your wallet.</p>

                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            <span class="badge px-3 py-2 rounded-pill" style="background: #dcfce7; color: #16a34a;">
                                <i class="bi bi-shop me-1"></i>100+ Brands
                            </span>
                            <span class="badge px-3 py-2 rounded-pill" style="background: #dcfce7; color: #16a34a;">
                                <i class="bi bi-graph-up-arrow me-1"></i>Exclusive Deals
                            </span>
                        </div>

                        <?php if (session()->get('isLoggedIn')): ?>
                            <a href="<?= base_url('services/ecommerce') ?>" class="btn btn-success btn-lg w-100 py-3 rounded-pill fw-semibold hover-lift">
                                Browse Platforms <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('login') ?>" class="btn btn-outline-success btn-lg w-100 py-3 rounded-pill fw-semibold hover-lift">
                                Login to Shop <i class="bi bi-lock-fill ms-2"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Feature Strip (Enhanced) -->
    <div class="mt-5 pt-3">
        <div class="row g-3 g-md-4 text-center">
            <?php $features = [
                ['icon' => 'bi-coin', 'color' => '#f59e0b', 'bg' => '#fef3c7', 'title' => 'Earn Coins', 'desc' => 'Every transaction earns you coins'],
                ['icon' => 'bi-shield-check', 'color' => '#10b981', 'bg' => '#d1fae5', 'title' => 'Secure & Verified', 'desc' => 'All rewards verified before credit'],
                ['icon' => 'bi-lightning-charge', 'color' => '#6366f1', 'bg' => '#e0e7ff', 'title' => 'Fast Processing', 'desc' => 'Rewards credited within 24h'],
                ['icon' => 'bi-gift', 'color' => '#ec4899', 'bg' => '#fce7f3', 'title' => 'Refer & Earn', 'desc' => 'Invite friends, earn more coins'],
            ]; ?>
            <?php foreach ($features as $f): ?>
            <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="50">
                <div class="p-3 p-md-4 rounded-4 h-100 d-flex flex-column align-items-center justify-content-center feature-card" style="background: <?= $f['bg'] ?>; transition: all 0.2s;">
                    <div class="fs-2 mb-2"><i class="bi <?= $f['icon'] ?>" style="color: <?= $f['color'] ?>;"></i></div>
                    <div class="fw-bold small text-dark"><?= $f['title'] ?></div>
                    <div class="text-muted small text-center" style="font-size: 0.75rem;"><?= $f['desc'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    /* Smooth hover lift effect */
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 30px -8px rgba(0,0,0,0.2) !important;
    }

    .service-card {
        transition: transform 0.3s cubic-bezier(0.2, 0.9, 0.3, 1.1), box-shadow 0.3s ease;
        will-change: transform;
    }
    .service-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 60px -15px rgba(79,70,229,0.3) !important;
    }

    .service-icon-glow {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.5) 0%, rgba(255,255,255,0) 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
        transform: rotate(-20deg);
    }
    .service-card:hover .service-icon-glow {
        opacity: 0.4;
    }

    .feature-card {
        transition: all 0.2s;
    }
    .feature-card:hover {
        transform: scale(1.05);
        background: white !important;
        box-shadow: 0 12px 24px -12px rgba(0,0,0,0.2);
    }

    .text-shadow {
        text-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    @media (max-width: 576px) {
        .services-hero {
            border-radius: 0 0 2rem 2rem !important;
        }
        .service-card {
            border-radius: 20px !important;
        }
        .feature-card {
            padding: 1rem !important;
        }
    }
</style>

<!-- Add AOS (Animate on Scroll) if not already in layout -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 600,
            once: true,
            offset: 50,
        });
    });
</script>

<?= $this->endSection() ?>