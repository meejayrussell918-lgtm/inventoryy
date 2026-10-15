<?php
include "db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($name == "" || $username == "" || $password == "") {
        $error = "All fields are required.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password != $confirm) {
        $error = "Passwords do not match.";
    } else {

        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username is already taken.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, username, password, role) VALUES (?, ?, ?, 'staff')");
            mysqli_stmt_bind_param($stmt, "sss", $name, $username, $hash);
            mysqli_stmt_execute($stmt);
            header("Location: index.php");
            exit;
        }
    }
}

$auth_page = true;
include "header.php";
?>
<aside class="auth-brand">
    <a class="brand" href="index.php"><span class="brand-mark"><i class="bi bi-box-seam"></i></span> Inventory</a>
    <div>
        <h1>Join your team<br>in under a minute.</h1>
        <p class="lead">Create a staff account to view, add and update products right away.</p>
        <ul class="auth-points">
            <li><i class="bi bi-lightning-charge"></i> Quick setup, no extra steps</li>
            <li><i class="bi bi-search"></i> Instant product search</li>
            <li><i class="bi bi-lock"></i> Passwords are securely hashed</li>
        </ul>
    </div>
    <div class="auth-foot">&copy; <?= date('Y') ?> Inventory Management System</div>
</aside>

<section class="auth-panel">
    <div class="auth-card">
        <a class="brand auth-mobile-brand" href="index.php"><span class="brand-mark"><i class="bi bi-box-seam"></i></span> Inventory</a>
        <h2>Create your account</h2>
        <p class="sub">Fill in your details to get started.</p>

        <?php if ($error != "") { ?>
            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?= e($error) ?>
            </div>
        <?php } ?>

        <form method="post" class="needs-validation" novalidate>
            <div class="mb-3">
                <label class="form-label" for="full_name">Full name</label>
                <div class="input-icon">
                    <i class="bi bi-person-badge lead-icon"></i>
                    <input type="text" id="full_name" name="full_name" class="form-control" placeholder="Juan Dela Cruz"
                           value="<?= e($_POST['full_name'] ?? '') ?>" autocomplete="name" autofocus required>
                    <div class="invalid-feedback">Full name is required.</div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <div class="input-icon">
                    <i class="bi bi-at lead-icon"></i>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Choose a username"
                           value="<?= e($_POST['username'] ?? '') ?>" autocomplete="username" required>
                    <div class="invalid-feedback">Username is required.</div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <div class="input-icon">
                    <i class="bi bi-lock lead-icon"></i>
                    <input type="password" id="password" name="password" class="form-control has-toggle" placeholder="At least 6 characters"
                           minlength="6" autocomplete="new-password" required>
                    <button type="button" class="toggle-pass" aria-label="Show password"><i class="bi bi-eye"></i></button>
                    <div class="invalid-feedback">At least 6 characters.</div>
                </div>
                <div class="strength"><span id="strengthBar"></span></div>
                <div class="strength-text" id="strengthText"></div>
            </div>
            <div class="mb-4">
                <label class="form-label" for="confirm">Confirm password</label>
                <div class="input-icon">
                    <i class="bi bi-shield-lock lead-icon"></i>
                    <input type="password" id="confirm" name="confirm" class="form-control" placeholder="Repeat your password"
                           autocomplete="new-password" required>
                    <div class="invalid-feedback">Passwords must match.</div>
                </div>
            </div>
            <button class="btn btn-primary w-100 py-2">Create account</button>
        </form>

        <p class="mt-4 mb-0 text-center text-muted">Already registered? <a href="index.php" class="fw-semibold">Sign in</a></p>
    </div>
</section>
<?php include "footer.php"; ?>
