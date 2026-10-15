<?php
include "db.php";
require_admin();

$error = "";
$edit_id = (int)($_GET['edit'] ?? 0);
$edit_name = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');

    if ($action == 'delete') {
        $stmt = mysqli_prepare($conn, "DELETE FROM categories WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        header("Location: categories.php");
        exit;
    }

    if ($name == "") {
        $error = "Category name is required.";
    } else {
        if ($action == 'update') {
            $stmt = mysqli_prepare($conn, "UPDATE categories SET name = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $name, $id);
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO categories (name) VALUES (?)");
            mysqli_stmt_bind_param($stmt, "s", $name);
        }
        mysqli_stmt_execute($stmt);
        header("Location: categories.php");
        exit;
    }
}

if ($edit_id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT name FROM categories WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    $edit_name = $row['name'] ?? '';
}

$categories = mysqli_query($conn, "SELECT c.id, c.name, COUNT(p.id) AS items FROM categories c LEFT JOIN products p ON p.category_id = c.id GROUP BY c.id, c.name ORDER BY c.name");

include "header.php";
?>
<div class="page-head">
    <div>
        <h3>Categories</h3>
        <p>Group your products to keep things organised.</p>
    </div>
</div>
<div class="row g-3 align-items-start">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-bold mb-3"><?= $edit_id > 0 ? "Edit category" : "Add category" ?></h6>
                <?php if ($error != "") { ?><div class="alert alert-danger"><?= e($error) ?></div><?php } ?>
                <form method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="action" value="<?= $edit_id > 0 ? 'update' : 'add' ?>">
                    <input type="hidden" name="id" value="<?= $edit_id ?>">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="<?= e($edit_name) ?>" placeholder="e.g. Electronics" required>
                        <div class="invalid-feedback">Required.</div>
                    </div>
                    <button class="btn btn-primary"><i class="bi bi-check2"></i> Save</button>
                    <?php if ($edit_id > 0) { ?><a href="categories.php" class="btn btn-light-soft">Cancel</a><?php } ?>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Name</th><th>Products</th><th class="text-end">Actions</th></tr></thead>
                    <tbody>
                    <?php if (mysqli_num_rows($categories) == 0) { ?>
                        <tr><td colspan="3" class="empty"><i class="bi bi-tags"></i>No categories yet.</td></tr>
                    <?php } ?>
                    <?php while ($c = mysqli_fetch_assoc($categories)) { ?>
                        <tr>
                            <td class="fw-semibold"><?= e($c['name']) ?></td>
                            <td><span class="pill"><?= (int)$c['items'] ?> item(s)</span></td>
                            <td class="text-end text-nowrap">
                                <a href="categories.php?edit=<?= (int)$c['id'] ?>" class="btn btn-sm btn-outline-primary icon-btn" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form method="post" class="d-inline" onsubmit="return confirm('Delete this category?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger icon-btn" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
