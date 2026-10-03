<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

require_login();

$transactions = $pdo->query(
    'SELECT t.transaction_type, t.quantity, t.created_at,
            p.product_name, p.sku_code, u.username
     FROM transactions t
     INNER JOIN products p ON p.product_id = t.product_id
     INNER JOIN users u ON u.user_id = t.user_id
     ORDER BY t.created_at DESC'
)->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transactions</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav>
    <strong>PHP Inventory Demo</strong>
    <span>
        <a href="../dashboard.php">Dashboard</a>
        <a href="../products/index.php">Products</a>
        <a href="history.php">Transactions</a>
        <a href="../auth/logout.php">Logout</a>
    </span>
</nav>

<main class="container">
    <h1>Transaction History</h1>

    <table>
        <thead>
        <tr>
            <th>SKU</th>
            <th>Product</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>User</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($transactions as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['sku_code']) ?></td>
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
