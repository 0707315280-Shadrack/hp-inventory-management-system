<?php
declare(strict_types=1);

session_start();

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
} else {
    header('Location: auth/login.php');
}
exit;
