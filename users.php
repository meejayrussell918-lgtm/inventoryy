<?php

include "db.php";
require_admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];

    if ($id != $_SESSION['user_id']) {
        if ($_POST['action'] == 'delete') {
            $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
        } elseif ($_POST['action'] == 'role') {
            $role = $_POST['role'] == 'admin' ? 'admin' : 'staff';
            $stmt = mysqli_prepare($conn, "UPDATE users SET role = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $role, $id);
            mysqli_stmt_execute($stmt);
        }
    }
    header("Location: users.php");
    exit;
}

$users = mysqli_query($conn, "SELECT id, full_name, username, role FROM users ORDER BY id");

include "header.php";
?>
<div class="page-head">
    <div>
        <h3>Users</h3>
        <p>Manage who can access the system and what they can do.</p>
    </div>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>User</th><th>Username</th><th>Role</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
            <?php while ($u = mysqli_fetch_assoc($users)) { ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar"><?= e(strtoupper(mb_substr($u['full_name'], 0, 1))) ?></span>
                            <span class="fw-semibold"><?= e($u['full_name']) ?><?php if ($u['id'] == $_SESSION['user_id']) { ?> <span class="pill ms-1">You</span><?php } ?></span>
                        </div>
                    </td>
                    <td class="text-muted">@<?= e($u['username']) ?></td>
                    <td>
                        <?php if ($u['id'] == $_SESSION['user_id']) { ?>
                            <span class="status role-<?= e($u['role']) ?>"><?= e($u['role']) ?></span>
                        <?php } else { ?>
                        <form method="post" class="d-flex gap-1">
                            <input type="hidden" name="action" value="role">
                            <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                            <select name="role" class="form-select form-select-sm w-auto">
                                <option value="staff" <?= $u['role'] == 'staff' ? 'selected' : '' ?>>staff</option>
                                <option value="admin" <?= $u['role'] == 'admin' ? 'selected' : '' ?>>admin</option>
                            </select>
                            <button class="btn btn-sm btn-outline-primary">Save</button>
                        </form>
                        <?php } ?>
                    </td>
                    <td class="text-end">
                        <?php if ($u['id'] != $_SESSION['user_id']) { ?>
                        <form method="post" class="d-inline" onsubmit="return confirm('Delete this user?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger icon-btn" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include "footer.php"; ?>
