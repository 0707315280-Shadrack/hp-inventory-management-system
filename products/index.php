<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

require_login();

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare(
        'SELECT * FROM products
         WHERE product_name LIKE ? OR sku_code LIKE ?
         ORDER BY product_name'
    );
    $like = "%{$search}%";
    $stmt->execute([$like, $like]);
    $products = $stmt->fetchAll();
} else {
    $products = $pdo->query('SELECT * FROM products ORDER BY product_name')->fetchAll();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products | PHP Inventory Demo</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav>
    <strong>PHP Inventory Demo</strong>
    <span>
        <a href="../dashboard.php">Dashboard</a>
        <a href="index.php">Products</a>
        <a href="../transactions/history.php">Transactions</a>
        <a href="../auth/logout.php">Logout</a>
    </span>
</nav>

<main class="container">
    <div class="heading-row">
        <h1>Products</h1>
        <a class="button" href="add.php">Add Product</a>
    </div>

    <form class="search" method="get">
        <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by product or SKU">
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
        <tr>
            <th>SKU</th>
            <th>Product</th>
            <th>Stock</th>
            <th>Reorder Level</th>
            <th>Unit Price</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['sku_code']) ?></td>
                <td><?= htmlspecialchars($product['product_name']) ?></td>
                <td><?= (int) $product['quantity_in_stock'] ?></td>
                <td><?= (int) $product['reorder_level'] ?></td>
                <td><?= number_format((float) $product['unit_price'], 2) ?></td>
                <td>
                    <a href="edit.php?id=<?= (int) $product['product_id'] ?>">Edit</a>
                    |
                    <a href="delete.php?id=<?= (int) $product['product_id'] ?>"
                       onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
