<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
Auth::requireLogin();

require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance()->getConnection();

// Get counts for dashboard
$projectsCount = $db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$teamCount = $db->query("SELECT COUNT(*) FROM team")->fetchColumn();
$submissionsCount = $db->query("SELECT COUNT(*) FROM contact_submissions WHERE is_read = 0")->fetchColumn();

$pageTitle = 'Dashboard';
include __DIR__ . '/includes/header.php';
?>

<div class="admin-content">
    <h1 class="dashboard-title">Dashboard</h1>
    <div class="stats-grid enhanced-stats">
        <div class="stat-card stat-projects">
            <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
            <div class="stat-details">
                <h3><?php echo $projectsCount; ?></h3>
                <p>Total Projects</p>
            </div>
        </div>
        <div class="stat-card stat-team">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-details">
                <h3><?php echo $teamCount; ?></h3>
                <p>Team Members</p>
            </div>
        </div>
        <div class="stat-card stat-messages">
            <div class="stat-icon"><i class="fas fa-envelope"></i></div>
            <div class="stat-details">
                <h3><?php echo $submissionsCount; ?></h3>
                <p>Unread Messages</p>
            </div>
        </div>
    </div>

    <!-- Recent Projects -->
    <div class="recent-section enhanced-table">
        <h2 class="section-title">Recent Projects</h2>
        <table class="data-table modern-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $recent = $db->query("
                    SELECT p.*, c.name as category_name 
                    FROM projects p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    ORDER BY p.created_at DESC 
                    LIMIT 5
                ")->fetchAll();
                foreach ($recent as $project):
                ?>
                <tr>
                    <td data-label="Title"><strong><?php echo htmlspecialchars($project['title']); ?></strong></td>
                    <td data-label="Category"><?php echo htmlspecialchars($project['category_name']); ?></td>
                    <td data-label="Location"><?php echo htmlspecialchars($project['location']); ?></td>
                    <td data-label="Year"><?php echo htmlspecialchars($project['year']); ?></td>
                    <td class="actions">
                        <a href="projects.php?edit=<?php echo $project['id']; ?>" class="btn-small btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="projects.php?delete=<?php echo $project['id']; ?>" class="btn-small btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>