    </main>

    <!-- Footer -->
     <hr>
    <footer class="footer">
        <button id="backToTop" aria-label="Back to top" style="position:fixed;right:32px;bottom:32px;z-index:9999;background:var(--accent-color);color:#fff;border:none;border-radius:50%;width:48px;height:48px;box-shadow:0 2px 12px rgba(0,0,0,0.12);display:none;align-items:center;justify-content:center;cursor:pointer;font-size:22px;transition:background 0.2s;">
            <span style="display:inline-block;line-height:1;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:28px;height:28px;vertical-align:middle;">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </span>
        </button>
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-info">
                    <h3>TerraLine Design Ltd</h3>
                    <p>Creating timeless spaces that inspire and endure. Pushing the boundaries of architectural excellence since 2005.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="Pinterest"><i class="fab fa-pinterest"></i></a>
                        <a href="#" aria-label="Behance"><i class="fab fa-behance"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>Explore</h4>
                    <ul>
                        <li><a href="/architecture-firm/pages/projects">All Projects</a></li>
                        <li><a href="/architecture-firm/pages/about">Our Story</a></li>
                        <li><a href="/architecture-firm/pages/services">Services</a></li>
                        <li><a href="/architecture-firm/pages/team">Team</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Categories</h4>
                    <ul>
                        <li><a href="/architecture-firm/pages/projects?category=residential">Residential</a></li>
                        <li><a href="/architecture-firm/pages/projects?category=commercial">Commercial</a></li>
                        <li><a href="/architecture-firm/pages/projects?category=interior">Interior</a></li>
                        <li><a href="/architecture-firm/pages/projects?category=landscape">Landscape</a></li>
                    </ul>
                </div>
                
                <div class="footer-contact">
                    <h4>Contact</h4>
                    <p>200 Park Avenue<br>New York, NY 10022</p>
                    <p><a href="tel:+12125550123">+1 (212) 555-0123</a></p>
                    <p><a href="mailto:studio@architecture.com">studio@architecture.com</a></p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> TerraLine Design Ltd. All rights reserved.</p>
                <!-- <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div> -->
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="/architecture-firm/assets/js/lightbox.js"></script>
    <script src="/architecture-firm/assets/js/main.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: false,
            offset: 100
        });

        // Back to Top Button
        const backToTopBtn = document.getElementById('backToTop');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopBtn.style.display = 'flex';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>