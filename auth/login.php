<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

session_start();

if (!empty($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT user_id, username, password_hash, role
             FROM users
             WHERE username = ?
             LIMIT 1'
        );
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header('Location: ../dashboard.php');
            exit;
        }

        $error = 'Invalid username or password.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | PHP Inventory Demo</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<main class="auth-card">
    <h1>Inventory Management</h1>
    <p class="muted">PHP 8 + MySQL demonstration project</p>

    <?php if ($error): ?>
        <div class="alert danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Sign In</button>
    </form>

    <p class="hint">Demo: admin / password</p>
</main>
</body>
</html>
