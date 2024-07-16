<?php 
require_once 'functions.php';
sessionStart(); // Ensure the session is started only once at the top of the script
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKémon - <?php echo $title ?? 'Home'; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>IKémon</h1>
    <nav>
        <ul>
            <li><a href="list.php">Home</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
                    <!-- Admin Panel button for admin users -->
                    <li><a href="admin-panel.php">Admin Panel</a></li>
                <?php endif; ?>
                <li><a href="user-detail.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <li>Balance: $<?php echo htmlspecialchars(number_format($_SESSION['balance'], 2)); ?></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
