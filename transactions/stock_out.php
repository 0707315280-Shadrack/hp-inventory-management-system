<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

require_login();

$products = $pdo->query(
    'SELECT product_id, product_name, quantity_in_stock
     FROM products ORDER BY product_name'
)->fetchAll();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

    if (!$productId || !$quantity || $quantity <= 0) {
        $error = 'Select a product and enter a valid quantity.';
    } else {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                'UPDATE products
                 SET quantity_in_stock = quantity_in_stock - ?
                 WHERE product_id = ? AND quantity_in_stock >= ?'
            );
            $stmt->execute([$quantity, $productId, $quantity]);

            if ($stmt->rowCount() !== 1) {
                $pdo->rollBack();
                $error = 'Insufficient stock available.';
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO transactions
                     (product_id, user_id, transaction_type, quantity)
                     VALUES (?, ?, "OUT", ?)'
                );
                $stmt->execute([$productId, $_SESSION['user_id'], $quantity]);

                $pdo->commit();

                header('Location: ../dashboard.php');
                exit;
            }
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $error = 'Unable to complete the stock-out transaction.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock Out</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<main class="container narrow">
    <h1>Stock Out</h1>

    <?php if ($error): ?>
        <div class="alert danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Product</label>
        <select name="product_id" required>
            <option value="">Select product</option>
            <?php foreach ($products as $product): ?>
                <option value="<?= (int) $product['product_id'] ?>">
                    <?= htmlspecialchars($product['product_name']) ?>
                    (Available: <?= (int) $product['quantity_in_stock'] ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Quantity</label>
        <input type="number" name="quantity" min="1" required>

        <button type="submit">Record Stock Out</button>
        <a class="button secondary" href="../dashboard.php">Cancel</a>
    </form>
</main>
</body>
</html>
