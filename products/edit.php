<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

require_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    exit('Invalid product ID.');
}

$stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = ?');
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    exit('Product not found.');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sku = trim($_POST['sku_code'] ?? '');
    $name = trim($_POST['product_name'] ?? '');
    $reorder = filter_input(INPUT_POST, 'reorder_level', FILTER_VALIDATE_INT);
    $price = filter_input(INPUT_POST, 'unit_price', FILTER_VALIDATE_FLOAT);

    if ($sku === '' || $name === '' || $reorder === false || $reorder < 0 ||
        $price === false || $price < 0) {
        $error = 'Please provide valid information.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'UPDATE products
                 SET sku_code = ?, product_name = ?, reorder_level = ?, unit_price = ?
                 WHERE product_id = ?'
            );
            $stmt->execute([$sku, $name, $reorder, $price, $id]);

            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Unable to update product.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<main class="container narrow">
    <h1>Edit Product</h1>

    <?php if ($error): ?>
        <div class="alert danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>SKU Code</label>
        <input name="sku_code" value="<?= htmlspecialchars($product['sku_code']) ?>" required>

        <label>Product Name</label>
        <input name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>

        <label>Reorder Level</label>
        <input type="number" name="reorder_level" min="0"
               value="<?= (int) $product['reorder_level'] ?>" required>

        <label>Unit Price</label>
        <input type="number" name="unit_price" min="0" step="0.01"
               value="<?= htmlspecialchars($product['unit_price']) ?>" required>

        <button type="submit">Update Product</button>
        <a class="button secondary" href="index.php">Cancel</a>
    </form>
</main>
</body>
</html>
