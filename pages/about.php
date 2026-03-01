<?php
$pageTitle = 'About';
$metaDescription = 'Learn about our studio, philosophy, and achievements';
include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="height: 60vh; position: relative; background: url('https://images.unsplash.com/photo-1600585154526-990dced4db0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: var(--overlay);"></div>
    <div style="position: absolute; bottom: 15%; left: 80px; color: #ffffff;">
        <h1 style="font-size: 72px; margin-bottom: 20px;">Our Story</h1>
        <p style="font-size: 18px;">Two decades of architectural excellence</p>
    </div>
</section>

<!-- About Content -->
<section class="section">
    <div class="container">
        <div class="about-grid">
            <div data-aos="fade-right" class="philosophy-content">
                <span class="section-subtitle">Since 2005</span>
                <h2 class="section-title">Creating Timeless Architecture</h2>
                <p class="about-desc">Founded with a vision to push the boundaries of architectural design, TerraLine Design Ltd has grown into a globally recognized practice. Our work is characterized by a deep respect for context, innovative use of materials, and a commitment to creating spaces that inspire.</p>
            </div>
            <div data-aos="fade-left">
                <img src="/architecture-firm/assets/images/contact.jpg" 
                     alt="Studio" 
                     class="about-img-lg">
            </div>
        </div>
        <div class="about-grid" style="margin-top: 80px;">
            <div data-aos="fade-left" class="about-philosophy">
                <h3 class="about-philosophy-title">Our Philosophy</h3>
                <p class="about-desc">We believe that great architecture emerges from a deep understanding of place, people, and purpose. Each project is an opportunity to create something unique – a response to its specific context that transcends mere building to become an experience.</p>
                <p class="about-desc">Our design process is collaborative and iterative, involving close dialogue with clients, consultants, and craftspeople. We don't just design buildings; we create environments that enhance the human experience.</p>
            </div>
            <div data-aos="fade-right">
                <img src="/architecture-firm/assets/images/project-residential.jpg" 
                     alt="Philosophy" 
                     class="about-img-md">
            </div>
        </div>
    </div>
</section>

<!-- Awards Section -->
<section class="section" style="background: var(--light-gray);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Recognition</span>
            <h2 class="section-title">Awards & Honors</h2>
        </div>
        
        <div class="awards-grid">
            <div class="award-card" data-aos="fade-up">
                <div class="award-icon fa fa-trophy"></div>
                <h3 class="award-title">AIA National Award</h3>
                <p class="award-desc">2023 - Crystal Waters Villa</p>
            </div>
            <div class="award-card" data-aos="fade-up" data-aos-delay="100">
                <div class="award-icon fa fa-trophy"></div>
                <h3 class="award-title">ArchDaily Building of the Year</h3>
                <p class="award-desc">2022 - Horizon Tower</p>
            </div>
            <div class="award-card" data-aos="fade-up" data-aos-delay="200">
                <div class="award-icon fa fa-trophy"></div>
                <h3 class="award-title">International Design Award</h3>
                <p class="award-desc">2021 - Minimalist Loft</p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>