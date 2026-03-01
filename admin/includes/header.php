<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Admin <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="/architecture-firm/assets/css/admin.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php
    // Fetch unread contact submissions count for sidebar
    $db = Database::getInstance()->getConnection();
    $unreadCount = $db->query("SELECT COUNT(*) FROM contact_submissions WHERE is_read = 0")->fetchColumn();
    ?>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <img src="/architecture-firm/assets/images/logo-white.png" alt="Logo" class="sidebar-logo">
                <h2>TerraLine Design Ltd Admin</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php"><i class="fas fa-dashboard"></i> Dashboard</a></li>
                    <li><a href="projects.php"><i class="fas fa-building"></i> Projects</a></li>
                    <li><a href="categories.php"><i class="fas fa-tags"></i> Categories</a></li>
                    <!-- <li><a href="team.php"><i class="fas fa-users"></i> Team</a></li> -->
                    <li>
                        <a href="submissions.php"><i class="fas fa-envelope"></i> Contact
                            <?php if ($unreadCount > 0): ?>
                                <span style="background:#c00;color:#fff;border-radius:10px;padding:2px 4px;font-size:12px;position:absolute;right:18px;top:20px;width:20px;height:20px;display:flex;align-items:center;justify-content:center;" title="<?php echo $unreadCount; ?> unread submissions">
                                    <?php echo $unreadCount; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li><a href="logout.php"><i class="fas fa-sign-out"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        
        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                <div class="topbar-right">
                    <span>Welcome, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?></span>
                </div>
            </header>
            
            <div class="admin-container">