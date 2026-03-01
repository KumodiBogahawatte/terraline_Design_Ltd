<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
if (Auth::isLoggedIn()) {
    header('Location: index.php');
    exit();
}


$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (Auth::login($email, $password)) {
        header('Location: index.php');
        exit();
    } else {
        $error = 'Invalid email or password';
    }
}

$pageTitle = 'Admin Login';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/architecture-firm/assets/css/admin.css">
</head>
<body class="login-page">
    <div class="login-container enhanced-login">
        <main class="login-box modern-card">
            <div class="login-logo">
                <img src="/architecture-firm/assets/images/logo-white.png" alt="Logo">
            </div>
            <h1 class="login-title">TerraLine Design Ltd</h1>
            <h2 class="login-subtitle">Admin Login</h2>
            <?php if ($error !== null && $_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="alert error"><i class="fas fa-exclamation-circle" aria-hidden="true"></i> <?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope" aria-hidden="true"></i> Email</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock" aria-hidden="true"></i> Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>
                <button type="submit" class="btn-primary btn-login"><i class="fas fa-sign-in-alt" aria-hidden="true"></i> Login</button>
            </form>
        </main>
    </div>
</body>
</html>