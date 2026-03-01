<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$project = getProjectBySlug($slug);

if (!$project) {
    header('HTTP/1.0 404 Not Found');
    include '404.php';
    exit;
}

$images = getProjectImages($project['id']);
$pageTitle = $project['title'];
$metaDescription = substr($project['description'], 0, 160);
include __DIR__ . '/../includes/header.php';
?>

<!-- Project Hero -->
<section class="project-hero" style="height: 80vh; position: relative;">
    <div class="project-hero-image" style="width: 100%; height: 100%; position: relative;">
        <img src="/architecture-firm/uploads/projects/<?php echo $project['featured_img']; ?>" 
             alt="<?php echo $project['title']; ?>"
             style="width: 100%; height: 100%; object-fit: cover;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: var(--overlay); z-index: 1;"></div>
    </div>
    <div style="position: absolute; bottom: 15%; left: 80px; color: #ffffff; z-index: 2;">
        <span class="section-subtitle"><?php echo $project['category_name']; ?></span>
        <h1 style="font-size: 72px; margin-bottom: 20px;"><?php echo $project['title']; ?></h1>
        <p style="font-size: 18px;"><?php echo $project['location']; ?> | <?php echo $project['year']; ?></p>
    </div>
</section>

<!-- Project Info -->
<section class="section">
    <div class="container">
        <div class="project-detail-grid">
            <!-- Project Details -->
            <div data-aos="fade-right">
                <h2 class="project-detail-title">Project Details</h2>
                <div class="project-detail-info">
                    <?php if ($project['location']): ?>
                    <div>
                        <strong class="project-detail-label">Location</strong>
                        <p><?php echo $project['location']; ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if ($project['year']): ?>
                    <div>
                        <strong class="project-detail-label">Year</strong>
                        <p><?php echo $project['year']; ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if ($project['area']): ?>
                    <div>
                        <strong class="project-detail-label">Area</strong>
                        <p><?php echo $project['area']; ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if ($project['client']): ?>
                    <div>
                        <strong class="project-detail-label">Client</strong>
                        <p><?php echo $project['client']; ?></p>
                    </div>
                    <?php endif; ?>
                    <div>
                        <strong class="project-detail-label">Category</strong>
                        <p><?php echo $project['category_name']; ?></p>
                    </div>
                </div>
            </div>
            <!-- Description -->
            <div data-aos="fade-left">
                <h2 class="project-detail-title">About the Project</h2>
                <p class="project-detail-desc"><?php echo nl2br($project['description']); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Gallery -->
<section class="section" style="background: var(--light-gray);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Gallery</span>
            <h2 class="section-title">Project Imagery</h2>
        </div>
        
        <div class="project-gallery-grid">
            <?php foreach ($images as $image): ?>
            <div class="gallery-item" data-aos="fade-up">
                <a href="/architecture-firm/uploads/projects/<?php echo $image['img_url']; ?>"
                   data-lightbox
                   data-caption="<?php echo htmlspecialchars($image['caption'] ?: $project['title']); ?>">
                    <img src="/architecture-firm/uploads/projects/<?php echo $image['img_url']; ?>"
                         alt="<?php echo htmlspecialchars($image['caption'] ?: $project['title']); ?>"
                         class="project-gallery-img"
                         loading="lazy">
                    <div class="gallery-hover">
                        <i class="fas fa-expand-alt"></i>
                        <?php if ($image['caption']): ?>
                        <span><?php echo htmlspecialchars($image['caption']); ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Next/Prev Navigation -->
<section class="section" style="padding: 60px 0;">
    <div class="container">
        <div class="project-nav-row">
            <?php
                $prevSlug = getPreviousProjectSlug($project['id']);
                $nextSlug = getNextProjectSlug($project['id']);
            ?>
            <a href="<?php echo $prevSlug ? '/architecture-firm/pages/project-detail.php?slug=' . $prevSlug : '#'; ?>" class="btn<?php if (!$prevSlug) echo ' btn-hidden'; ?>">&larr; Previous Project</a>
            <a href="/architecture-firm/pages/projects.php" class="btn">All Projects</a>
            <a href="<?php echo $nextSlug ? '/architecture-firm/pages/project-detail.php?slug=' . $nextSlug : '#'; ?>" class="btn<?php if (!$nextSlug) echo ' btn-hidden'; ?>">Next Project &rarr;</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>