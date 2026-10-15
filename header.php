<?php
// Layout v2: sidebar navigation. Set $auth_page = true before including for login/register.
$auth_page = !empty($auth_page);
$current = basename($_SERVER['PHP_SELF']);

if (!function_exists('nav_class')) {
    function nav_class($files) {
        global $current;
        return in_array($current, (array)$files) ? 'active' : '';
    }
}
$titles = [
    'dashboard.php' => 'Dashboard', 'products.php' => 'Products', 'product_form.php' => 'Products',
    'categories.php' => 'Categories', 'users.php' => 'Users',
];
$page_title = $titles[$current] ?? 'Inventory';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/layout2.css" rel="stylesheet">
</head>
<body class="<?= $auth_page ? 'is-auth' : 'is-app' ?>">
<?php if ($auth_page) { ?>
<div class="auth-shell">
<?php } else { ?>
<aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar">
    <div class="sidebar-top">
        <a class="brand" href="dashboard.php"><span class="brand-mark"><i class="bi bi-box-seam"></i></span> Inventory</a>
        <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close"></button>
    </div>

    <?php if (isset($_SESSION['user_id'])) { ?>
    <nav class="side-nav">
        <div class="side-label">Menu</div>
        <a class="side-link <?= nav_class('dashboard.php') ?>" href="dashboard.php"><i class="bi bi-grid-1x2"></i> Dashboard</a>
        <a class="side-link <?= nav_class(['products.php','product_form.php']) ?>" href="products.php"><i class="bi bi-boxes"></i> Products</a>
        <?php if ($_SESSION['role'] == 'admin') { ?>
        <div class="side-label">Administration</div>
        <a class="side-link <?= nav_class('categories.php') ?>" href="categories.php"><i class="bi bi-tags"></i> Categories</a>
        <a class="side-link <?= nav_class('users.php') ?>" href="users.php"><i class="bi bi-people"></i> Users</a>
        <?php } ?>
    </nav>

    <div class="side-user">
        <span class="avatar"><?= e(strtoupper(mb_substr($_SESSION['name'], 0, 1))) ?></span>
        <div class="side-user-meta">
            <strong><?= e($_SESSION['name']) ?></strong>
            <small><?= e($_SESSION['role']) ?></small>
        </div>
        <a href="logout.php" class="side-logout" title="Log out"><i class="bi bi-box-arrow-right"></i></a>
    </div>
    <?php } ?>
</aside>

<div class="app-body">
    <header class="topbar">
        <button class="btn btn-light-soft icon-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-label="Open menu">
            <i class="bi bi-list fs-5"></i>
        </button>
        <div class="topbar-title"><?= e($page_title) ?></div>
        <div class="topbar-date d-none d-md-block"><i class="bi bi-calendar3"></i> <?= date('l, F j, Y') ?></div>
    </header>
    <main class="app-content">
<?php } ?>
