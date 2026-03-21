<?php
/**
 * @var array $data
 */
$title  = $title  ?? 'Admin Panel';
$active = $active ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> – FinTech Admin</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --sidebar-width: 240px;
            --brand-dark: #0f172a;
            --brand-accent: #6366f1;
            --brand-accent-hover: #4f46e5;
        }
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; }

        /* ── Sidebar ─────────────────────────────────────────────────── */
        #sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-width); height: 100vh; overflow-y: auto;
            background: var(--brand-dark); color: #cbd5e1;
            display: flex; flex-direction: column; z-index: 1000;
        }
        #sidebar .brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        #sidebar .brand h5 { color: #fff; font-weight: 700; font-size: 1.1rem; margin: 0; }
        #sidebar .brand small { font-size: .75rem; color: #94a3b8; }
        #sidebar nav { flex: 1; padding: 1rem 0; }
        #sidebar nav a {
            display: flex; align-items: center; gap: .75rem;
            padding: .65rem 1.25rem; color: #94a3b8;
            text-decoration: none; font-size: .9rem;
            border-left: 3px solid transparent;
            transition: all .2s;
        }
        #sidebar nav a:hover { background: rgba(255,255,255,.05); color: #e2e8f0; }
        #sidebar nav a.active {
            color: #fff; border-left-color: var(--brand-accent);
            background: rgba(99,102,241,.1);
        }
        #sidebar nav a i { font-size: 1.1rem; }
        #sidebar .sidebar-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,.08);
            font-size: .78rem; color: #475569;
        }

        /* ── Main content ────────────────────────────────────────────── */
        #main { margin-left: var(--sidebar-width); min-height: 100vh; }
        .topbar {
            background: #fff; padding: .85rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar h6 { margin: 0; font-weight: 600; color: #1e293b; }
        .content-area { padding: 1.75rem; }

        /* ── Cards ───────────────────────────────────────────────────── */
        .stat-card {
            border: none; border-radius: 14px;
            background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,.06);
            padding: 1.4rem 1.5rem;
            transition: box-shadow .2s;
        }
        .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.1); }
        .stat-card .icon-box {
            width: 44px; height: 44px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem; margin-bottom: .9rem;
        }
        .stat-card .value { font-size: 1.75rem; font-weight: 700; color: #0f172a; }
        .stat-card .label { font-size: .82rem; color: #64748b; margin-top: .15rem; }

        /* ── Table ───────────────────────────────────────────────────── */
        .table thead th {
            background: #f8fafc; font-size: .8rem;
            text-transform: uppercase; letter-spacing: .04em;
            color: #64748b; border-bottom: 1px solid #e2e8f0; font-weight: 600;
        }
        .table td { vertical-align: middle; font-size: .875rem; color: #334155; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <div class="brand">
        <h5><i class="bi bi-diagram-3-fill me-2" style="color:var(--brand-accent)"></i>FinTech Admin</h5>
        <small>Referral Management Panel</small>
    </div>
    <nav>
        <a href="<?= base_url('admin/') ?>"
           class="<?= $active === 'dashboard'     ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="<?= base_url('admin/users') ?>"
           class="<?= $active === 'users'         ? 'active' : '' ?>">
            <i class="bi bi-people-fill"></i> Users
        </a>
        <a href="<?= base_url('admin/transactions') ?>"
           class="<?= $active === 'transactions'  ? 'active' : '' ?>">
            <i class="bi bi-arrow-left-right"></i> Transactions
        </a>
        <a href="<?= base_url('admin/withdrawals') ?>"
           class="<?= $active === 'withdrawals'  ? 'active' : '' ?>">
            <i class="bi bi-cash-stack"></i> Withdrawals
        </a>
        <a href="<?= base_url('admin/services') ?>"
           class="<?= $active === 'services'     ? 'active' : '' ?>">
            <i class="bi bi-grid-3x3-gap-fill"></i> Services Hub
        </a>
        <a href="<?= base_url('admin/support') ?>"
           class="<?= $active === 'support'      ? 'active' : '' ?>">
            <i class="bi bi-headset"></i> Support Inbox
        </a>
        <a href="<?= base_url('admin/membership-orders') ?>"
           class="<?= $active === 'membership-orders' ? 'active' : '' ?>">
            <i class="bi bi-cart-check"></i> Membership Orders
        </a>
        <a href="<?= base_url('admin/user-settings') ?>"
           class="<?= $active === 'user-settings' ? 'active' : '' ?>">
            <i class="bi bi-person-gear"></i> User Settings
        </a>
        <a href="<?= base_url('admin/workers') ?>"
           class="<?= $active === 'workers'     ? 'active' : '' ?>">
            <i class="bi bi-briefcase-fill"></i> Worker Management
        </a>
        <a href="<?= base_url('admin/settings') ?>"
           class="<?= $active === 'settings'     ? 'active' : '' ?>">
            <i class="bi bi-gear-fill"></i> System Settings
        </a>
    </nav>
    <div class="sidebar-footer">FinTech Referral System v1.0</div>
</div>

<!-- Main -->
<div id="main">
    <div class="topbar">
        <h6><?= esc($title) ?></h6>
        <?php  ?>
        <span class="text-muted" style="font-size:.82rem">
            <i class="bi bi-clock me-1"></i><?php date_default_timezone_set('Asia/Kolkata'); echo date('d M Y, H:i') ?>
        </span>
    </div>
    <div class="content-area">

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>

    </div><!-- /content-area -->
</div><!-- /main -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
