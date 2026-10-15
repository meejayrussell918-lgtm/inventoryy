<?php
include "db.php";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username == "" || $password == "") {
        $error = "Please fill in all fields.";
    } else {
    
        $stmt = mysqli_prepare($conn, "SELECT id, full_name, password, role FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Wrong username or password.";
        }
    }
}

$auth_page = true;
include "header.php";
?>
<aside class="auth-brand">
    <a class="brand" href="index.php"><span class="brand-mark"><i class="bi bi-box-seam"></i></span> Inventory</a>
    <div>
        <h1>Know what you have.<br>Know when to restock.</h1>
        <p class="lead">One simple place to track products, prices and stock levels for your whole team.</p>
        <ul class="auth-points">
            <li><i class="bi bi-boxes"></i> Manage products and categories</li>
            <li><i class="bi bi-bell"></i> Low-stock alerts on the dashboard</li>
            <li><i class="bi bi-shield-check"></i> Role-based access for admin and staff</li>
        </ul>
    </div>
    <div class="auth-foot">&copy; <?= date('Y') ?> Inventory Management System</div>
</aside>

<section class="auth-panel">
    <div class="auth-card">
        <a class="brand auth-mobile-brand" href="index.php"></span> Inventory Management System</a>
        <h2>Welcome Boss!</h2>
        <p class="sub">Sign in to manage your inventory.</p>

        <?php if ($error != "") { ?>
            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?= e($error) ?>
            </div>
        <?php } ?>

        <form method="post" class="needs-validation" novalidate>
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <div class="input-icon">
                    <i class="bi bi-person lead-icon"></i>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username"
                           value="<?= e($_POST['username'] ?? '') ?>" autocomplete="username" autofocus required>
                    <div class="invalid-feedback">Username is required.</div>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label" for="password">Password</label>
                <div class="input-icon">
                    <i class="bi bi-lock lead-icon"></i>
                    <input type="password" id="password" name="password" class="form-control has-toggle" placeholder="Enter your password"
                           autocomplete="current-password" required>
                    <button type="button" class="toggle-pass" aria-label="Show password"><i class="bi bi-eye"></i></button>
                    <div class="invalid-feedback">Password is required.</div>
                </div>
            </div>
            <button class="btn btn-primary w-100 py-2">Sign in</button>
        </form>

        <p class="mt-4 mb-0 text-center text-muted">New here? <a href="register.php" class="fw-semibold">Create an account</a></p>

      
        
    </div>
</section>
<?php include "footer.php"; ?>
