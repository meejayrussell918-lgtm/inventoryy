<?php

include "db.php";
require_login();

$search = trim($_GET['search'] ?? '');
$like = "%" . $search . "%";

$stmt = mysqli_prepare($conn, "SELECT p.*, c.name AS category FROM products p
                               LEFT JOIN categories c ON c.id = p.category_id
                               WHERE p.name LIKE ? OR p.sku LIKE ?
                               ORDER BY p.name");
mysqli_stmt_bind_param($stmt, "ss", $like, $like);
mysqli_stmt_execute($stmt);
$products = mysqli_stmt_get_result($stmt);

include "header.php";
?>
<div class="page-head">
    <div>
        <h3>Products</h3>
        <p>Add, edit and track everything in stock.</p>
    </div>
    <a href="product_form.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add product</a>
</div>

<div class="card">
    <form method="get" class="toolbar">
        <div class="input-icon">
            <i class="bi bi-search lead-icon"></i>
            <input type="text" name="search" id="search" class="form-control" placeholder="Search by name or SKU..." value="<?= e($search) ?>">
        </div>
        <?php if ($search != '') { ?><a href="products.php" class="btn btn-light-soft btn-sm">Clear</a><?php } ?>
        <span class="count"><span id="rowCount"><?= mysqli_num_rows($products) ?></span> item(s)</span>
    </form>
    <div class="table-responsive">
        <table class="table table-hover" id="productTable">
            <thead>
                <tr><th>SKU</th><th>Name</th><th>Category</th><th>Price</th><th>Qty</th><th>Status</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($products) == 0) { ?>
                <tr class="empty-row"><td colspan="7" class="empty"><i class="bi bi-inbox"></i>No products found.</td></tr>
            <?php } ?>
            <?php while ($p = mysqli_fetch_assoc($products)) { ?>
                <tr>
                    <td><span class="sku"><?= e($p['sku']) ?></span></td>
                    <td class="fw-semibold"><?= e($p['name']) ?></td>
                    <td><?php if ($p['category']) { ?><span class="pill"><?= e($p['category']) ?></span><?php } else { ?><span class="text-muted">—</span><?php } ?></td>
                    <td>₱<?= number_format($p['price'], 2) ?></td>
                    <td><?= e($p['quantity']) ?></td>
                    <td>
                        <?php if ($p['quantity'] == 0) { ?>
                            <span class="status status-out">Out of stock</span>
                        <?php } elseif ($p['quantity'] <= $p['reorder_level']) { ?>
                            <span class="status status-low">Low</span>
                        <?php } else { ?>
                            <span class="status status-ok">In stock</span>
                        <?php } ?>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="product_form.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-outline-primary icon-btn" title="Edit"><i class="bi bi-pencil"></i></a>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                        <form method="post" action="product_delete.php" class="d-inline" onsubmit="return confirm('Delete this product?');">
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
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
