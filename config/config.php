<?php
// Load environment variables
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
    return true;
}

// Load .env file
loadEnv(__DIR__ . '/../.env');

// Define constants
define('SITE_NAME', getenv('SITE_NAME') ?: 'Studio Architecture');
define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost/architecture-firm');
define('UPLOAD_MAX_SIZE', getenv('MAX_FILE_SIZE') ?: 5242880);
define('ALLOWED_EXTENSIONS', explode(',', getenv('ALLOWED_EXTENSIONS') ?: 'jpg,jpeg,png,gif,webp'));

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');

// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>