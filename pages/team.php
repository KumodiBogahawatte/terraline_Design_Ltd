<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Our Team';
$metaDescription = 'Meet the talented architects and designers behind our studio';
$team = getTeamMembers();
include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="height: 50vh; position: relative; background: url('https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: var(--overlay);"></div>
    <div style="position: absolute; bottom: 15%; left: 80px; color: #ffffff;">
        <h1 style="font-size: 72px; margin-bottom: 20px;">Our Team</h1>
        <p style="font-size: 18px;">The creative minds behind our work</p>
    </div>
</section>

<!-- Team Grid -->
<section class="section">
    <div class="container">
        <div class="team-grid">
            <?php foreach ($team as $index => $member): ?>
            <div class="team-member" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                <div class="member-image">
                    <?php if ($member['image']): ?>
                    <img src="/uploads/team/<?php echo $member['image']; ?>" alt="<?php echo $member['name']; ?>">
                    <?php else: ?>
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=687&q=80" alt="<?php echo $member['name']; ?>">
                    <?php endif; ?>
                </div>
                <h3 class="member-name"><?php echo $member['name']; ?></h3>
                <p class="member-role"><?php echo $member['role']; ?></p>
                <p class="member-bio"><?php echo $member['bio']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Join Us Section -->
<section class="section" style="background: var(--primary-color); color: #ffffff; text-align: center;">
    <div class="container">
        <h2 style="font-size: 36px; margin-bottom: 20px;" data-aos="fade-up">Join Our Team</h2>
        <p style="margin-bottom: 30px; color: var(--dark-gray);" data-aos="fade-up" data-aos-delay="100">We're always looking for talented individuals to join our creative studio.</p>
        <a href="/pages/contact.php" class="btn btn-light" data-aos="fade-up" data-aos-delay="200">View Openings</a>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>