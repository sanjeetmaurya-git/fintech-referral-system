<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'FinTech User' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .btn-primary { background-color: #6366f1; border-color: #6366f1; }
        .btn-primary:hover { background-color: #4f46e5; border-color: #4f46e5; }
        .navbar { background-color: #fff; border-bottom: 1px solid #e2e8f0; }
        .referral-card { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); color: white; }
        .navbar-nav{ text-align: center; padding-top: 10px; gap: 4px;}
        .navbar-nav .fin-nav-item{
            background: #faf5ef;
            transition:all 0.3s ease; 
            border-radius: 5px;
            font-weight: 600;
        }
        .navbar-nav .fin-nav-item:hover{
            background: #f0e5d8; 
            border-radius: 20px;
            font-weight: 700;
        }
        .bell-icon:hover .bi-bell-fill{
            animation: ring 0.6s ease-in-out;
        }

        @keyframes ring {
            0% { transform: rotate(0); }
            15% { transform: rotate(-20deg); }
            30% { transform: rotate(18deg); }
            45% { transform: rotate(-15deg); }
            60% { transform: rotate(10deg); }
            75% { transform: rotate(-5deg); }
            100% { transform: rotate(0); }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg mb-4 py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="<?= base_url('dashboard') ?>">
            <i class="bi bi-wallet2 me-2"></i>FinTech Rewards
        </a>
        <?php if (session()->get('isLoggedIn')): ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item fin-nav-item">
                        <a class="nav-link <?= ($active ?? '') === 'home' ? 'active fw-bold text-primary' : '' ?>" href="<?= base_url('/') ?>">Home</a>
                    </li>
                    <li class="nav-item fin-nav-item">
                        <a class="nav-link <?= ($active ?? '') === 'dashboard' ? 'active fw-bold text-primary' : '' ?>" href="<?= base_url('dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item fin-nav-item">
                        <a class="nav-link <?= ($active ?? '') === 'services' ? 'active fw-bold text-primary' : '' ?>" href="<?= base_url('services') ?>">Services Hub</a>
                    </li>
                    <li class="nav-item fin-nav-item">
                        <a class="nav-link <?= ($active ?? '') === 'profile' ? 'active fw-bold text-primary' : '' ?>" href="<?= base_url('profile') ?>">My Profile</a>
                    </li>
                    <li class="nav-item fin-nav-item">
                        <a class="nav-link <?= ($active ?? '') === 'support' ? 'active fw-bold text-primary' : '' ?>" href="<?= base_url('support') ?>">
                            Support
                            <?php if (($count = get_unread_support_count()) > 0): ?>
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.6rem; vertical-align: top; margin-left: -5px;">+<?= $count ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item position-relative me-2 fin-nav-item bell-icon">
                        <a class="nav-link <?= ($active ?? '') === 'notifications' ? 'active fw-bold text-primary' : '' ?>" href="<?= base_url('notifications') ?>">
                            <i class="bi bi-bell-fill"></i>
                            <?php 
                                helper('notification');
                                if (($nCount = get_unread_notification_count()) > 0): 
                            ?>
                                <span class="badge bg-danger border border-light rounded-circle position-absolute top-0 start-100 translate-middle-x" style="font-size: 0.5rem; padding: 0.25em 0.5em;">
                                    <?= $nCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-danger btn-sm rounded-pill px-3" href="<?= base_url('logout') ?>">Logout</a>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</nav>

<div class="container mb-5">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show w-50" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show w-50" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
