<?php
http_response_code(404);
$pageTitle = 'Page Not Found';
include __DIR__ . '/includes/header.php';
?>

<section style="height: 80vh; display: flex; align-items: center; justify-content: center; text-align: center;">
    <div class="container">
        <h1 style="font-size: 120px; color: var(--accent-color); margin-bottom: 20px;">404</h1>
        <h2 style="font-size: 36px; margin-bottom: 20px;">Page Not Found</h2>
        <p style="color: var(--secondary-color); margin-bottom: 40px;">The page you're looking for doesn't exist or has been moved.</p>
        <a href="/" class="btn">Return Home</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>