<?php

include "db.php";
require_login();

$id = (int)($_GET['id'] ?? 0);
$error = "";

$product = ['sku' => '', 'name' => '', 'category_id' => '', 'price' => '', 'quantity' => 0, 'reorder_level' => 10];

if ($id > 0) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $product = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if (!$product) {
        die("Product not found.");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sku = trim($_POST['sku']);
    $name = trim($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $reorder = $_POST['reorder_level'];

    if ($sku == "" || $name == "") {
        $error = "SKU and name are required.";
    } elseif (!is_numeric($price) || $price < 0) {
        $error = "Price must be a positive number.";
    } elseif (!ctype_digit($quantity) || !ctype_digit($reorder)) {
        $error = "Quantity and reorder level must be whole numbers.";
    } else {
  
        $stmt = mysqli_prepare($conn, "SELECT id FROM products WHERE sku = ? AND id != ?");
        mysqli_stmt_bind_param($stmt, "si", $sku, $id);
        mysqli_stmt_execute($stmt);
        if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0) {
            $error = "That SKU already exists.";
        } else {
            $cat = $category_id > 0 ? $category_id : null;
            if ($id > 0) {
                $stmt = mysqli_prepare($conn, "UPDATE products SET sku=?, name=?, category_id=?, price=?, quantity=?, reorder_level=? WHERE id=?");
                mysqli_stmt_bind_param($stmt, "ssidiii", $sku, $name, $cat, $price, $quantity, $reorder, $id);
            } else {
                $stmt = mysqli_prepare($conn, "INSERT INTO products (sku, name, category_id, price, quantity, reorder_level) VALUES (?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ssidii", $sku, $name, $cat, $price, $quantity, $reorder);
            }
            mysqli_stmt_execute($stmt);
            header("Location: products.php");
            exit;
        }
    }

    $product = ['sku' => $sku, 'name' => $name, 'category_id' => $category_id, 'price' => $price, 'quantity' => $quantity, 'reorder_level' => $reorder];
}

$categories = mysqli_query($conn, "SELECT id, name FROM categories ORDER BY name");

include "header.php";
?>
<div class="form-card">
    <div class="page-head">
        <div>
            <h3><?= $id > 0 ? "Edit product" : "Add product" ?></h3>
            <p><?= $id > 0 ? "Update the details below and save." : "Enter the details of the new item." ?></p>
        </div>
        <a href="products.php" class="btn btn-light-soft"><i class="bi bi-arrow-left"></i> Back to products</a>
    </div>
    <div class="card">
        <div class="card-body">
            <?php if ($error != "") { ?>
                <div class="alert alert-danger d-flex align-items-center gap-2"><i class="bi bi-exclamation-circle"></i> <?= e($error) ?></div>
            <?php } ?>
            <form method="post" class="needs-validation" novalidate>
                <div class="form-section">Basic information</div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control" placeholder="ELC-003" value="<?= e($product['sku']) ?>" required>
                        <div class="invalid-feedback">Required.</div>
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Product name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. USB-C Cable" value="<?= e($product['name']) ?>" required>
                        <div class="invalid-feedback">Required.</div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="0">No category</option>
                        <?php while ($c = mysqli_fetch_assoc($categories)) { ?>
                            <option value="<?= (int)$c['id'] ?>" <?= $product['category_id'] == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-section">Pricing &amp; stock</div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:10px 0 0 10px">₱</span>
                            <input type="number" step="0.01" min="0" name="price" class="form-control" value="<?= e($product['price']) ?>" required>
                            <div class="invalid-feedback">Enter a valid price.</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" min="0" name="quantity" class="form-control" value="<?= e($product['quantity']) ?>" required>
                        <div class="invalid-feedback">Enter a whole number.</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Reorder level</label>
                        <input type="number" min="0" name="reorder_level" class="form-control" value="<?= e($product['reorder_level']) ?>" required>
                        <div class="invalid-feedback">Enter a whole number.</div>
                        <div class="hint">Flag as low at or below this.</div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-primary px-4"><i class="bi bi-check2"></i> Save product</button>
                    <a href="products.php" class="btn btn-light-soft">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
