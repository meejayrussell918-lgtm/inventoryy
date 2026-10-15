<?php
include "db.php";
require_login();

$total_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM products"))['n'];
$total_units = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(quantity),0) AS n FROM products"))['n'];
$total_value = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(quantity*price),0) AS n FROM products"))['n'];
$low_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM products WHERE quantity <= reorder_level"))['n'];

$low_items = mysqli_query($conn, "SELECT name, quantity, reorder_level FROM products WHERE quantity <= reorder_level ORDER BY quantity LIMIT 10");

include "header.php";
?>
<div class="page-head">
    <div>
        <h3>Hello, <?= e(explode(' ', $_SESSION['name'])[0]) ?> 👋</h3>
        <p>Here's what's happening with your stock today.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="product_form.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add product</a>
        <a href="products.php" class="btn btn-light-soft">View all products</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="card stat">
        <div class="stat-icon tone-indigo"><i class="bi bi-boxes"></i></div>
        <div><div class="stat-label">Products</div><div class="stat-value"><?= number_format($total_products) ?></div></div>
    </div></div>
    <div class="col-6 col-lg-3"><div class="card stat">
        <div class="stat-icon tone-sky"><i class="bi bi-stack"></i></div>
        <div><div class="stat-label">Total units</div><div class="stat-value"><?= number_format($total_units) ?></div></div>
    </div></div>
    <div class="col-6 col-lg-3"><div class="card stat">
        <div class="stat-icon tone-green"><i class="bi bi-cash-stack"></i></div>
        <div><div class="stat-label">Stock value</div><div class="stat-value">₱<?= number_format($total_value, 2) ?></div></div>
    </div></div>
    <div class="col-6 col-lg-3"><div class="card stat">
        <div class="stat-icon tone-red"><i class="bi bi-exclamation-triangle"></i></div>
        <div><div class="stat-label">Low stock</div><div class="stat-value"><?= number_format($low_count) ?></div></div>
    </div></div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bell text-warning me-1"></i> Items that need restocking</span>
        <a href="products.php" class="small fw-semibold">Manage stock &rarr;</a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Product</th><th>Stock level</th><th>Quantity</th><th>Reorder level</th></tr></thead>
            <tbody>
            <?php if (mysqli_num_rows($low_items) == 0) { ?>
                <tr><td colspan="4" class="empty"><i class="bi bi-check2-circle"></i>All items are well stocked.</td></tr>
            <?php } ?>
            <?php while ($row = mysqli_fetch_assoc($low_items)) {
                $pct = $row['reorder_level'] > 0 ? min(100, round($row['quantity'] / $row['reorder_level'] * 100)) : 0;
            ?>
                <tr>
                    <td class="fw-semibold"><?= e($row['name']) ?></td>
                    <td style="width:180px"><div class="stock-bar"><span class="<?= $row['quantity'] == 0 ? 'zero' : '' ?>" style="width: <?= max($pct, 4) ?>%"></span></div></td>
                    <td>
                        <?php if ($row['quantity'] == 0) { ?>
                            <span class="status status-out">Out of stock</span>
                        <?php } else { ?>
                            <span class="status status-low"><?= e($row['quantity']) ?> left</span>
                        <?php } ?>
                    </td>
                    <td class="text-muted"><?= e($row['reorder_level']) ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include "footer.php"; ?>
