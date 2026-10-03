<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

require_login();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sku = trim($_POST['sku_code'] ?? '');
    $name = trim($_POST['product_name'] ?? '');
    $quantity = filter_input(INPUT_POST, 'quantity_in_stock', FILTER_VALIDATE_INT);
    $reorder = filter_input(INPUT_POST, 'reorder_level', FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, 'unit_price', FILTER_VALIDATE_FLOAT);

    if ($sku === '' || $name === '' || $quantity === false || $quantity < 0 ||
        $reorder === false || $reorder < 0 || $price === false || $price < 0) {
        $error = 'Please provide valid product information.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO products
                (sku_code, product_name, quantity_in_stock, reorder_level, unit_price)
                VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$sku, $name, $quantity, $reorder, $price]);

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Unable to save product. Check that the SKU is unique.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<main class="container narrow">
    <h1>Add Product</h1>

    <?php if ($error): ?>
        <div class="alert danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>SKU Code</label>
        <input name="sku_code" required>

        <label>Product Name</label>
        <input name="product_name" required>

        <label>Opening Stock</label>
        <input type="number" name="quantity_in_stock" min="0" required>

        <label>Reorder Level</label>
        <input type="number" name="reorder_level" min="0" required>

        <label>Unit Price</label>
        <input type="number" name="unit_price" min="0" step="0.01" required>

        <button type="submit">Save Product</button>
        <a class="button secondary" href="index.php">Cancel</a>
    </form>
</main>
</body>
</html>
