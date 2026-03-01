<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$successMsg = '';
$errorMsg = '';

$pageTitle = 'Contact';
$metaDescription = 'Get in touch with our team to discuss your project';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            throw new Exception('Invalid security token');
        }
        
        // Validate inputs
        $name = sanitize($_POST['name']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $message_text = sanitize($_POST['message']);
        
        if (!$name || !$email || !$message_text) {
            throw new Exception('Please fill in all fields correctly');
        }
        
        // Save to database
        if (saveContactSubmission($name, $email, $message_text)) {
            $message = 'Thank you for your message. We\'ll be in touch soon.';
        } else {
            throw new Exception('Error saving your message. Please try again.');
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$csrf_token = generateCSRFToken();
include __DIR__ . '/../includes/header.php';
?>

<!-- Page Header -->
<section class="page-header" style="height: 50vh; position: relative; background: url('/architecture-firm/assets/images/contact.jpg') center/cover;">
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: var(--overlay);"></div>
    <div style="position: absolute; bottom: 15%; left: 80px; color: #ffffff;">
        <h1 style="font-size: 72px; margin-bottom: 20px;">Contact Us</h1>
        <p style="font-size: 18px;">Let's start a conversation</p>
    </div>
</section>

<!-- Contact Section -->
<div class="ct-page">

    <!-- ── Body grid ────────────────────────────── -->
    <div class="ct-body">

        <!-- LEFT col -->
        <div class="ct-left">

            <!-- pull-quote -->
            <div class="ct-quote">
                <p>"Good architecture begins with a conversation."</p>
            </div>

            <!-- info rows -->
            <div class="ct-info">
                <div class="ct-info__row">
                    <span class="ct-info__ico"><i class="fas fa-map-marker-alt"></i></span>
                    <div>
                        <strong>Address</strong>
                        200 Park Avenue<br>New York, NY 10022
                    </div>
                </div>
                <div class="ct-info__row">
                    <span class="ct-info__ico"><i class="fas fa-phone"></i></span>
                    <div>
                        <strong>Phone</strong>
                        <a href="tel:+12125550123">+1 (212) 555-0123</a>
                    </div>
                </div>
                <div class="ct-info__row">
                    <span class="ct-info__ico"><i class="fas fa-envelope"></i></span>
                    <div>
                        <strong>Email</strong>
                        <a href="mailto:studio@architecture.com">studio@architecture.com</a>
                    </div>
                </div>
                <div class="ct-info__row">
                    <span class="ct-info__ico"><i class="fas fa-clock"></i></span>
                    <div>
                        <strong>Hours</strong>
                        Mon–Fri 9:00–18:00<br>Sat by appointment
                    </div>
                </div>
            </div>

            <!-- studio image -->
            <!-- <div class="ct-studio-img">
                <img src="/architecture-firm/assets/images/about.jpg"
                     onerror="this.src='/architecture-firm/assets/images/contact.jpg'"
                     alt="TerraLine Studio">
                <div class="ct-studio-img__cap">
                    <span>TerraLine Design</span><span>New York</span>
                </div>
            </div> -->

        </div>

        <!-- RIGHT col: form -->
        <div class="ct-right">

            <div class="ct-form-head">
                <h2>Send a Message</h2>
                <p>Tell us about your project and we'll be in touch soon.</p>
            </div>

            <?php if ($successMsg): ?>
            <div class="ct-notice ct-notice--ok">
                <i class="fas fa-check-circle"></i> <?php echo $successMsg; ?>
            </div>
            <?php endif; ?>
            <?php if ($errorMsg): ?>
            <div class="ct-notice ct-notice--err">
                <i class="fas fa-exclamation-circle"></i> <?php echo $errorMsg; ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="ct-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                <div class="ct-field">
                    <input type="text" id="ct_name" name="name" placeholder=" " required>
                    <label for="ct_name">Your Name</label>
                    <span class="ct-field__bar"></span>
                </div>

                <div class="ct-field">
                    <input type="email" id="ct_email" name="email" placeholder=" " required>
                    <label for="ct_email">Email Address</label>
                    <span class="ct-field__bar"></span>
                </div>

                <div class="ct-field">
                    <textarea id="ct_msg" name="message" placeholder=" " rows="5" required></textarea>
                    <label for="ct_msg">Your Message</label>
                    <span class="ct-field__bar"></span>
                </div>

                <button type="submit" class="ct-btn">
                    <span>Send Message</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>

    </div><!-- /ct-body -->

    <!-- ── Map bar ───────────────────────────────── -->
    <div class="ct-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9478108.126193948!2d-4.4737716!3d54.55127985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x25a3b1142c791a9%3A0xc4f8a0433288257a!2sUnited%20Kingdom!5e0!3m2!1sen!2slk!4v1772298047795!5m2!1sen!2slk"
            width="100%"
            height="100%"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        <!-- <div class="ct-map__grid"></div> -->
        <div class="ct-map__pin">
            <i class="fas fa-map-marker-alt"></i>
            <strong>TerraLine Design</strong>
            <span>200 Park Avenue, New York, NY 10022</span>
        </div>
    </div>

</div><!-- /ct-page -->

<?php include __DIR__ . '/../includes/footer.php'; ?>