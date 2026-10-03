<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

require_login();

$totalProducts = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$totalUnits = (int) $pdo->query('SELECT COALESCE(SUM(quantity_in_stock), 0) FROM products')->fetchColumn();
$lowStock = (int) $pdo->query(
    'SELECT COUNT(*) FROM products WHERE quantity_in_stock <= reorder_level'
)->fetchColumn();

$recent = $pdo->query(
    'SELECT t.transaction_type, t.quantity, t.created_at,
            p.product_name, u.username
     FROM transactions t
     INNER JOIN products p ON p.product_id = t.product_id
     INNER JOIN users u ON u.user_id = t.user_id
     ORDER BY t.created_at DESC
     LIMIT 8'
)->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | PHP Inventory Demo</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav>
    <strong>PHP Inventory Demo</strong>
    <span>
        <a href="dashboard.php">Dashboard</a>
        <a href="products/index.php">Products</a>
        <a href="transactions/history.php">Transactions</a>
        <a href="auth/logout.php">Logout</a>
    </span>
</nav>

<main class="container">
    <h1>Dashboard</h1>
    <p class="muted">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>.</p>

    <section class="cards">
        <div class="card"><strong><?= $totalProducts ?></strong><span>Products</span></div>
        <div class="card"><strong><?= $totalUnits ?></strong><span>Units in Stock</span></div>
        <div class="card"><strong><?= $lowStock ?></strong><span>Low Stock Items</span></div>
    </section>

    <div class="actions">
        <a class="button" href="products/add.php">Add Product</a>
        <a class="button secondary" href="transactions/stock_in.php">Stock In</a>
        <a class="button secondary" href="transactions/stock_out.php">Stock Out</a>
    </div>

    <h2>Recent Transactions</h2>
    <table>
        <thead>
        <tr>
            <th>Product</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>User</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($recent as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['transaction_type']) ?></td>
                <td><?= (int) $row['quantity'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
