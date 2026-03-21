
<!-- Deepseek code  -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= $title ?? 'FinTech Rewards - Recharge & Earn Smartly' ?></title>
    <meta name="description" content="Recharge mobile, D2H, shop online & earn instant rewards. Join India's fastest growing fintech referral network.">
    <meta name="keywords" content="recharge, fintech, referral, rewards, ecommerce, d2h, cashback, coins">
    <meta name="author" content="FinTech Rewards">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="FinTech Rewards - Recharge & Earn">
    <meta property="og:description" content="Get instant coins on every recharge and purchase. Join our referral network today!">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?= base_url('assets/images/og-image.jpg') ?>">
    <meta name="twitter:card" content="summary_large_image">
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- Bootstrap Icons & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300..700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Bootstrap core CSS (lightweight) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-400: #818cf8;
            --primary-500: #6366f1;
            --primary-600: #4f46e5;
            --secondary-400: #c084fc;
            --secondary-500: #a855f7;
            --accent-gold: #fbbf24;
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 0.5);
            --card-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 30px 60px -12px rgba(79, 70, 229, 0.25);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            color: #0f172a;
            overflow-x: hidden;
            line-height: 1.5;
        }

        /* Navbar & Header */
        .main-nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: all 0.4s ease;
            padding: 20px 0;
        }

        .main-nav.scrolled {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 12px 0;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }

        .main-nav .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white;
            transition: color 0.3s;
        }

        .main-nav.scrolled .navbar-brand {
            color: var(--primary-600);
        }

        .nav-link {
            color: rgba(255,255,255,0.9);
            font-weight: 500;
            transition: all 0.3s;
            padding: 8px 20px !important;
            border-radius: 50px;
        }

        .main-nav.scrolled .nav-link {
            color: #1e293b;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.2);
            color: white !important;
        }

        .main-nav.scrolled .nav-link:hover {
            background-color: var(--primary-500);
            color: white !important;
        }

        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white !important;
        }

        .main-nav.scrolled .nav-link.active {
            background: var(--primary-500);
            color: white !important;
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Animated gradient background for hero */
        .hero-section {
            position: relative;
            background: linear-gradient(145deg, #4f46e5, #7c3aed, #c2410c);
            background-size: 200% 200%;
            animation: gradientFlow 12s ease infinite;
            padding: 140px 0 140px;
            border-radius: 0 0 4rem 4rem;
            color: white;
            text-align: center;
            isolation: isolate;
            overflow: hidden;
        }

        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 50%, rgba(255,255,255,0.15) 0%, transparent 50%);
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        /* Floating coins animation */
        .floating-coin {
            position: absolute;
            font-size: 2.5rem;
            opacity: 0.2;
            color: rgba(255,255,255,0.3);
            animation: float 8s infinite ease-in-out;
            pointer-events: none;
        }

        .coin-1 { top: 20%; left: 5%; animation-delay: 0s; }
        .coin-2 { bottom: 15%; right: 8%; animation-delay: 1.5s; font-size: 3rem; }
        .coin-3 { top: 60%; left: 12%; animation-delay: 0.7s; font-size: 2rem; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        /* User card - glassmorphism premium */
        .user-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            padding: 28px;
            margin-top: -80px;
            box-shadow: var(--card-shadow);
            transition: all 0.4s cubic-bezier(0.2, 0.9, 0.3, 1.1);
            position: relative;
            z-index: 10;
        }

        .user-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--hover-shadow);
            border-color: white;
        }

        .avatar-circle {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            background: linear-gradient(135deg, white, #f1f5f9);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px rgba(0,0,0,0.08);
            border: 3px solid white;
        }

        .avatar-circle i {
            font-size: 2.5rem;
            color: var(--primary-500);
        }

        .avatar-circle img {
            object-fit: cover;
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .wallet-pill {
            background: white;
            border-radius: 100px;
            padding: 10px 22px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            border: 1px solid rgba(99,102,241,0.2);
            transition: all 0.2s;
        }

        .wallet-pill:hover {
            border-color: var(--primary-500);
            background: #fafaff;
        }

        .btn-premium {
            background: linear-gradient(135deg, #4f46e5, #a855f7);
            border: none;
            color: white;
            border-radius: 40px;
            padding: 12px 32px;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: all 0.3s;
            box-shadow: 0 10px 20px -8px rgba(79,70,229,0.5);
        }

        .btn-premium:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 30px -8px rgba(79,70,229,0.7);
            color: white;
        }

        .btn-outline-modern {
            border: 2px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(4px);
            border-radius: 40px;
            color: white;
            padding: 10px 24px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-outline-modern:hover {
            background: white;
            color: #4f46e5;
            border-color: white;
        }

        /* Service cards */
        .service-grid {
            margin-top: 70px;
        }

        .service-card {
            background: white;
            border-radius: 32px;
            border: none;
            padding: 36px 24px;
            text-align: center;
            transition: all 0.4s;
            box-shadow: 0 15px 30px -12px rgba(0,0,0,0.05);
            cursor: pointer;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: inherit;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .service-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(145deg, white, #fafafa);
            z-index: -1;
            transition: opacity 0.4s;
        }

        .service-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(145deg, #eef2ff, #f5f3ff);
            opacity: 0;
            z-index: -1;
            transition: opacity 0.4s;
        }

        .service-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 30px 60px -15px rgba(79,70,229,0.3);
        }

        .service-card:hover::after {
            opacity: 1;
        }

        .icon-box {
            width: 90px;
            height: 90px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            font-size: 2.4rem;
            transition: all 0.4s;
            background: white;
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.08);
        }

        .service-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        .recharge-icon { color: #3b82f6; }
        .d2h-icon { color: #f97316; }
        .shop-icon { color: #22c55e; }

        .section-title {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 40px;
            position: relative;
            display: inline-block;
            background: linear-gradient(135deg, #1e293b, #4f46e5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 70px;
            height: 4px;
            background: linear-gradient(90deg, #4f46e5, #a855f7);
            border-radius: 4px;
        }

        /* Premium banner */
        .premium-banner {
            background: linear-gradient(145deg, #0f172a, #1e293b);
            border-radius: 48px;
            padding: 60px;
            margin: 80px 0 40px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 40px 60px -20px rgba(0,0,0,0.4);
        }

        .premium-banner .content {
            position: relative;
            z-index: 2;
        }

        .premium-banner .bg-icon {
            position: absolute;
            right: 20px;
            bottom: 20px;
            font-size: 12rem;
            color: rgba(255,255,255,0.03);
            transform: rotate(-10deg);
        }

        .premium-banner .btn-light {
            border-radius: 40px;
            padding: 14px 40px;
            font-weight: 700;
            background: white;
            color: #0f172a;
            border: none;
            transition: all 0.3s;
            box-shadow: 0 20px 30px -10px rgba(0,0,0,0.3);
        }

        .premium-banner .btn-light:hover {
            transform: scale(1.05);
            background: #f8fafc;
            box-shadow: 0 25px 35px -8px #00000066;
        }

        /* Footer */
        .footer {
            padding: 40px 0 20px;
            border-top: 1px solid rgba(0,0,0,0.05);
            color: #64748b;
        }

        .footer a {
            color: #475569;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer a:hover {
            color: #4f46e5;
        }

        /* Responsive fine-tuning */
        @media (max-width: 991px) {
            .main-nav { padding: 12px 0; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
            .main-nav .navbar-brand, .main-nav.scrolled .navbar-brand { color: var(--primary-600); }
            .nav-link, .main-nav.scrolled .nav-link { color: #1e293b; padding: 10px 20px !important; width: 100%; text-align: center; }
            .navbar-toggler { border: none; padding: 0; color: var(--primary-600); font-size: 1.5rem; }
            .navbar-toggler:focus { box-shadow: none; }
            .navbar-collapse { background: white; border-radius: 20px; margin-top: 15px; padding: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        }

        @media (max-width: 768px) {
            .hero-section { padding: 100px 0 80px; border-radius: 0 0 2rem 2rem; }
            .hero-section h1 { font-size: 2.5rem; }
            .hero-section p { font-size: 1.1rem !important; }
            .user-card { padding: 24px; margin-top: -40px; }
            .avatar-circle { width: 60px; height: 60px; }
            .avatar-circle i { font-size: 2rem; }
            .wallet-pill { width: 100%; justify-content: center; }
            .premium-banner { padding: 40px 24px; border-radius: 32px; }
            .premium-banner h2 { font-size: 1.75rem; }
            .floating-coin { display: none; }
            .service-card { padding: 28px 16px; }
        }

        /* Animation classes */
        [data-aos] {
            pointer-events: none;
        }
        [data-aos].aos-animate {
            pointer-events: auto;
        }
    </style>
</head>
<body>

    <!-- Hero with floating coins -->
    <!-- Hero with floating coins -->
    <nav class="navbar navbar-expand-lg main-nav">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="bi bi-rocket-takeoff-fill me-2"></i>SmartLead
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <i class="bi bi-list"></i>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <div class="navbar-nav ms-auto gap-2">
                    <a class="nav-link active" href="<?= base_url() ?>">Home</a>
                    <?php if ($isLoggedIn): ?>
                        <a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a>
                        <a class="nav-link" href="<?= base_url('profile') ?>">Profile</a>
                        <a class="nav-link position-relative" href="<?= base_url('notifications') ?>">
                            <i class="bi bi-bell"></i>
                            <?php 
                                helper('notification');
                                if (($nCount = get_unread_notification_count()) > 0): 
                            ?>
                                <span class="badge bg-danger rounded-circle position-absolute top-1 rotate-0 translate-middle-x" style="font-size: 0.5rem; padding: 0.25em 0.4em;">
                                    <?= $nCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    <?php else: ?>
                        <a class="nav-link" href="<?= base_url('login') ?>">Login</a>
                        <a class="nav-link partner-btn" href="<?= base_url('login') ?>">Become Partner</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <!-- <h1><?php var_dump(base_url()); ?></h1> -->

    <header class="hero-section">
        <div class="floating-coin coin-1"><i class="bi bi-coin"></i></div>
        <div class="floating-coin coin-2"><i class="bi bi-cash-stack"></i></div>
        <div class="floating-coin coin-3"><i class="bi bi-gem "></i></div>
        
        <div class="container hero-content" data-aos="fade-down" data-aos-duration="1000">
            <span class="badge bg-white text-dark px-4 py-2 rounded-pill mb-4 fw-semibold">
                <i class="bi bi-stars text-warning me-2"></i>Trusted by 10k+ users
            </span>
            <h1 class="display-4 fw-bold mb-3">FinTech Rewards</h1>
            <p class="lead fs-4 mb-0 opacity-90">Recharge • Shop • Earn Coins Instantly</p>
            <div class="mt-4 d-flex gap-3 justify-content-center flex-wrap">
                <a href="#services" class="btn btn-outline-modern rounded-pill px-4 py-2">
                    Explore Services <i class="bi bi-arrow-down ms-2"></i>
                </a>
            </div>
        </div>
        <svg class="position-absolute bottom-0 start-0 w-100" style="color: #f8fafc; transform: scale(1.02);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none">
        <path fill="currentColor" fill-opacity="0.2" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        <path fill="currentColor" fill-opacity="0.4" d="M0,96L80,90.7C160,85,320,75,480,80C640,85,800,107,960,112C1120,117,1280,107,1360,101.3L1440,96L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
    </svg>
    </header>

    <div class="container">
        <!-- User Status Card (glass morph) -->
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="150">
            <div class="col-lg-10">
                <div class="user-card">
                    <div class="d-flex align-items-center flex-wrap justify-content-between gap-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle">
                                <?php if ($isLoggedIn && !empty($user['profile_image'])): ?>
                                    <img src="<?= base_url($user['profile_image']) ?>" alt="<?= esc($profile['full_name'] ?? 'User') ?>" loading="lazy">
                                <?php else: ?>
                                    <i class="bi bi-person-circle"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1">Welcome, <?= $isLoggedIn ? esc($profile['full_name'] ?? 'User') : 'Guest' ?></h4>
                                <p class="text-secondary-emphasis small mb-0">
                                    <?= $isLoggedIn ? esc($user['phone']) : 'Login to unlock rewards & referral benefits' ?>
                                </p>
                            </div>
                        </div>
                        
                        <?php if ($isLoggedIn): ?>
                            <div class="d-flex gap-3 align-items-center">
                                <div class="wallet-pill">
                                    <i class="bi bi-wallet2 fs-5 text-primary"></i>
                                    <span class="fw-bold fs-5">₹<?= number_format($wallet['balance'] ?? 0, 2) ?></span>
                                </div>
                                <a href="<?= base_url('profile') ?>" class="btn btn-outline-primary rounded-circle p-3" style="width: 52px; height: 52px;" aria-label="Profile">
                                    <i class="bi bi-gear fs-5"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="d-flex gap-3">
                                <a href="<?= base_url('login') ?>" class="btn btn-premium px-5 py-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Login / Signup
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Grid (3 cards) -->
        <div id="services" class="service-grid">
            <h4 class="section-title" data-aos="fade-right">Our Services</h4>
            <div class="row g-4">
                <div class="col-md-4" data-aos="zoom-in-up" data-aos-delay="100">
                    <a href="<?= base_url('services/recharge') ?>" class="service-card">
                        <div class="icon-box recharge-icon">
                            <i class="bi bi-phone-vibrate"></i>
                        </div>
                        <h4 class="fw-bold mb-2">Mobile Recharge</h4>
                        <p class="text-secondary-emphasis mb-3">Instant prepaid/postpaid recharge with bonus coins.</p>
                        <span class="fw-semibold" style="color: #3b82f6;">Recharge Now <i class="bi bi-arrow-right ms-1"></i></span>
                    </a>
                </div>
                <div class="col-md-4" data-aos="zoom-in-up" data-aos-delay="200">
                    <a href="<?= base_url('services/recharge?type=d2h') ?>" class="service-card">
                        <div class="icon-box d2h-icon">
                            <i class="bi bi-tv"></i>
                        </div>
                        <h4 class="fw-bold mb-2">D2H Recharge</h4>
                        <p class="text-secondary-emphasis mb-3">Renew DTH & earn up to 5% back as coins.</p>
                        <span class="fw-semibold" style="color: #f97316;">Pay Bills <i class="bi bi-arrow-right ms-1"></i></span>
                    </a>
                </div>
                <div class="col-md-4" data-aos="zoom-in-up" data-aos-delay="300">
                    <a href="<?= base_url('services/ecommerce') ?>" class="service-card">
                        <div class="icon-box shop-icon">
                            <i class="bi bi-bag-heart"></i>
                        </div>
                        <h4 class="fw-bold mb-2">Ecommerce</h4>
                        <p class="text-secondary-emphasis mb-3">Shop 1000+ brands & get coins on every order.</p>
                        <span class="fw-semibold" style="color: #22c55e;">Explore Deals <i class="bi bi-arrow-right ms-1"></i></span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Worker Module Cards -->
        <div class="service-grid mt-5 pt-4">
            <h4 class="section-title" data-aos="fade-right">Professional Services</h4>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6" data-aos="fade-right" data-aos-delay="400">
                    <a href="<?= base_url('worker/register') ?>" class="service-card text-start align-items-start p-5">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <h3 class="fw-bold mb-3">👷 Work & Earn</h3>
                        <p class="text-secondary-emphasis mb-4 fs-5">Register your skills and get hired by customers near you. Get jobs from nearby customers easily.</p>
                        <span class="btn btn-primary rounded-pill px-4 py-2 fw-bold">Become a Worker <i class="bi bi-arrow-right ms-2"></i></span>
                    </a>
                </div>
                <div class="col-md-6" data-aos="fade-left" data-aos-delay="500">
                    <a href="<?= base_url('hire') ?>" class="service-card text-start align-items-start p-5">
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Find Trusted Skilled Professionals</h3>
                        <p class="text-secondary-emphasis mb-4 fs-5">Find skilled professionals for your daily needs. Quick, reliable, and nearby services.</p>
                        <span class="btn btn-success rounded-pill px-4 py-2 fw-bold">Find Workers <i class="bi bi-arrow-right ms-2"></i></span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Premium Banner (Enhanced) -->
        <div class="premium-banner" data-aos="flip-up" data-aos-duration="1200">
            <div class="row align-items-center content">
                <div class="col-md-8">
                    <h2 class="fw-bold text-white mb-3 display-6">Become a Premium Member</h2>
                    <p class="text-white-50 mb-4 lead fs-5">10x coins, exclusive rewards, and unlimited referrals. Upgrade in one click.</p>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-light btn-lg rounded-pill px-5 py-3">
                        <i class="bi bi-stars me-2"></i>Upgrade Today
                    </a>
                </div>
                <div class="col-md-4 text-end d-none d-md-block">
                    <i class="bi bi-shield-shaded bg-icon"></i>
                </div>
            </div>
            <div class="bg-icon"><i class="bi bi-star-fill"></i></div>
        </div>

        <!-- Footer with links -->
        <footer class="footer">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0">&copy; <?= date('Y') ?> FinTech Rewards. Built for <i class="bi bi-heart-fill text-danger"></i> in India.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="me-4">Privacy</a>
                    <a href="#" class="me-4">Terms</a>
                    <a href="#" class="me-4">Support</a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            once: true,
            offset: 80,
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('.main-nav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
