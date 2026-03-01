<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Projects';
$metaDescription = 'Explore our portfolio of luxury architectural projects';
include __DIR__ . '/../includes/header.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$category = isset($_GET['category']) ? $_GET['category'] : null;

$projects = getProjects($page, 9, $category);
$totalProjects = getTotalProjects($category);
$totalPages = ceil($totalProjects / 9);
$categories = getCategories();
?>

<!-- Page Header -->
<section class="page-header" style="height: 60vh; position: relative; background: url('https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: var(--overlay);"></div>
    <div style="position: absolute; bottom: 15%; left: 80px; color: #ffffff;">
        <h1 style="font-size: 72px; margin-bottom: 20px;">Our Portfolio</h1>
        <p style="font-size: 18px;">A collection of our finest work</p>
    </div>
</section>

<!-- Projects Section -->
<section class="section">
    <div class="container">
        <!-- Filter Buttons -->
        <div class="filter-container">
            <button class="filter-btn <?php echo !$category ? 'active' : ''; ?>" data-filter="all">All</button>
            <?php foreach ($categories as $cat): ?>
            <button class="filter-btn <?php echo $category === $cat['slug'] ? 'active' : ''; ?>" 
                    data-filter="<?php echo $cat['slug']; ?>">
                <?php echo $cat['name']; ?>
            </button>
            <?php endforeach; ?>
        </div>

        <!-- Projects Grid -->
        <div class="projects-grid">
            <?php foreach ($projects as $project): ?>
            <div class="project-card" data-aos="fade-up" data-category="<?php echo $project['category_slug'] ?? ''; ?>">
                <a href="/architecture-firm/pages/project-detail.php?slug=<?php echo $project['slug']; ?>" style="text-decoration: none;">
                    <div class="project-image">
                        <img src="/architecture-firm/uploads/projects/<?php echo $project['featured_img']; ?>" 
                             alt="<?php echo $project['title']; ?>"
                             loading="lazy">
                        <div class="project-overlay">
                            <span class="project-category"><?php echo $project['category_name'] ?? 'Architecture'; ?></span>
                            <h3 class="project-title"><?php echo $project['title']; ?></h3>
                            <span class="project-location"><?php echo $project['location']; ?></span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?><?php echo $category ? '&category=' . $category : ''; ?>">&laquo; Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $page): ?>
            <span class="active"><?php echo $i; ?></span>
            <?php else: ?>
            <a href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . $category : ''; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?><?php echo $category ? '&category=' . $category : ''; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>