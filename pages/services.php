<?php
$pageTitle = 'Services';
$metaDescription = 'Explore our comprehensive architecture and design services';
include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="height: 50vh; position: relative; background: url('https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: var(--overlay);"></div>
    <div style="position: absolute; bottom: 15%; left: 80px; color: #ffffff;">
        <h1 style="font-size: 72px; margin-bottom: 20px;">Our Services</h1>
        <p style="font-size: 18px;">Comprehensive architectural solutions</p>
    </div>
</section>

<!-- Services Grid -->
<section class="section">
    <div class="container">
        <div class="services-grid">
            <div class="service-card" data-aos="fade-up">
                <div class="service-icon">
                    <i class="fa-solid fa-draw-polygon"></i>
                </div>
                <h3 class="service-title">Architectural Design</h3>
                <p class="service-description">From concept to completion, we create spaces that inspire and endure. Our designs respond to context while pushing creative boundaries.</p>
            </div>
            
            <div class="service-card" data-aos="fade-up" data-aos-delay="100">
                <div class="service-icon">
                    <i class="fa-solid fa-couch"></i>
                </div>
                <h3 class="service-title">Interior Architecture</h3>
                <p class="service-description">Creating seamless transitions between interior and exterior spaces, with attention to materiality, light, and human experience.</p>
            </div>
            
            <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                <div class="service-icon">
                    <i class="fa-solid fa-tree"></i>
                </div>
                <h3 class="service-title">Landscape Design</h3>
                <p class="service-description">Integrating architecture with nature through thoughtful landscape design that enhances both building and environment.</p>
            </div>
            
            <div class="service-card" data-aos="fade-up">
                <div class="service-icon">
                    <i class="fa-solid fa-city"></i>
                </div>
                <h3 class="service-title">Urban Planning</h3>
                <p class="service-description">Shaping communities through master planning that balances density, green space, and human-scale design.</p>
            </div>
            
            <div class="service-card" data-aos="fade-up" data-aos-delay="100">
                <div class="service-icon">
                    <i class="fa-solid fa-solar-panel"></i>
                </div>
                <h3 class="service-title">Sustainable Design</h3>
                <p class="service-description">Leading the way in green architecture with passive design strategies and innovative sustainable technologies.</p>
            </div>
            
            <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                <div class="service-icon">
                    <i class="fa-solid fa-pencil-ruler"></i>
                </div>
                <h3 class="service-title">Project Management</h3>
                <p class="service-description">Comprehensive project oversight from initial concept through construction administration and project delivery.</p>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="section" style="background: var(--light-gray);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <span class="section-subtitle">Our Approach</span>
            <h2 class="section-title">The Design Process</h2>
        </div>
        
        <div class="process-grid">
            <div style="text-align: center;" data-aos="fade-up">
                <div style="font-size: 48px; color: var(--accent-color); margin-bottom: 20px;">01</div>
                <h3 style="margin-bottom: 10px;">Discovery</h3>
                <p style="color: var(--secondary-color);">Understanding your vision, needs, and site context</p>
            </div>
            <div style="text-align: center;" data-aos="fade-up" data-aos-delay="100">
                <div style="font-size: 48px; color: var(--accent-color); margin-bottom: 20px;">02</div>
                <h3 style="margin-bottom: 10px;">Concept Design</h3>
                <p style="color: var(--secondary-color);">Exploring ideas and developing initial design concepts</p>
            </div>
            <div style="text-align: center;" data-aos="fade-up" data-aos-delay="200">
                <div style="font-size: 48px; color: var(--accent-color); margin-bottom: 20px;">03</div>
                <h3 style="margin-bottom: 10px;">Design Development</h3>
                <p style="color: var(--secondary-color);">Refining design, materials, and technical details</p>
            </div>
            <div style="text-align: center;" data-aos="fade-up" data-aos-delay="300">
                <div style="font-size: 48px; color: var(--accent-color); margin-bottom: 20px;">04</div>
                <h3 style="margin-bottom: 10px;">Construction</h3>
                <p style="color: var(--secondary-color);">Overseeing construction to ensure design intent</p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>