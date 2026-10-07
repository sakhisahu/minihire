<?php
require_once "functions.php";
$pageTitle = $pageTitle ?? "MiniHire";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title><?= e($pageTitle) ?> | MiniHire</title>

    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<header class="header">

    <div class="container nav">

        <a href="index.php" class="logo">
            <span class="logo-box">M</span>
            MiniHire
        </a>

        <nav>

            <a href="index.php">Jobs</a>

            <?php if (isLoggedIn()): ?>

                <a href="dashboard.php">Dashboard</a>

                <?php if (isAdmin()): ?>
                    <a href="admin.php">Admin</a>
                <?php endif; ?>

                <a href="logout.php" class="nav-btn">
                    Logout
                </a>

            <?php else: ?>

                <a href="login.php">Login</a>

                <a href="register.php" class="nav-btn">
                    Register
                </a>

            <?php endif; ?>

        </nav>

    </div>

</header>

<main>