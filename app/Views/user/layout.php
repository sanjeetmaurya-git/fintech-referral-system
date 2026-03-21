<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= $title ?? 'FinTech Rewards - User Panel' ?></title>
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- Bootstrap Icons & Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300..700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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

        .main-nav {
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 12px 0;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-600) !important;
        }

        .nav-link {
            color: #1e293b !important;
            font-weight: 500;
            transition: all 0.3s;
            padding: 8px 20px !important;
            border-radius: 50px;
        }

        .nav-link:hover, .nav-link.active {
            background-color: var(--primary-500) !important;
            color: white !important;
        }

        .card {
            border: none;
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid var(--glass-border);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-500), var(--secondary-500));
            border: none;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 10px 20px -8px rgba(99, 102, 241, 0.5);
        }

        .footer {
            padding: 40px 0 20px;
            color: #64748b;
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        @media (max-width: 991px) {
            .navbar-collapse { background: white; border-radius: 20px; margin-top: 15px; padding: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
            .nav-link { text-align: center; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg main-nav mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('dashboard') ?>">
            <i class="bi bi-rocket-takeoff-fill me-2"></i>SmartLead
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'home' ? 'active' : '' ?>" href="<?= base_url('#') ?>">Become Partner </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'home' ? 'active' : '' ?>" href="<?= base_url('/') ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'services' ? 'active' : '' ?>" href="<?= base_url('services') ?>">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($active ?? '') === 'profile' ? 'active' : '' ?>" href="<?= base_url('profile') ?>">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative <?= ($active ?? '') === 'notifications' ? 'active' : '' ?>" href="<?= base_url('notifications') ?>">
                        <i class="bi bi-bell-fill"></i>
                        <?php 
                            helper('notification');
                            if (($nCount = get_unread_notification_count()) > 0): 
                        ?>
                            <span class="badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle" style="font-size: 0.6rem;">
                                <?= $nCount ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                    <a class="btn btn-outline-danger btn-sm rounded-pill px-4" href="<?= base_url('logout') ?>">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mb-5 min-vh-100" data-aos="fade-up">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible border-0 shadow-sm fade show mb-4 rounded-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible border-0 shadow-sm fade show mb-4 rounded-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>

<footer class="footer mt-auto">
    <div class="container text-center">
        <p class="mb-0">&copy; <?= date('Y') ?> SmartLead Rewards. Built with <i class="bi bi-heart-fill text-danger"></i></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
