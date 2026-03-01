<?php
require_once __DIR__ . '/../config/database.php';

// Sanitize input
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Generate slug
function createSlug($string) {
    $string = preg_replace('/[^a-z0-9-]+/', '-', strtolower($string));
    return trim($string, '-');
}

// Upload file
function uploadFile($file, $targetDir, $oldFile = null) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // Create directory if not exists
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Validate file type
    if (!in_array($extension, ALLOWED_EXTENSIONS)) {
        throw new Exception('Invalid file type.');
    }
    
    // Validate file size
    if ($file['size'] > UPLOAD_MAX_SIZE) {
        throw new Exception('File too large.');
    }
    
    // Generate unique filename
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $targetPath = $targetDir . '/' . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Delete old file if exists
        if ($oldFile && file_exists($targetDir . '/' . $oldFile)) {
            unlink($targetDir . '/' . $oldFile);
        }
        return $filename;
    }
    
    return false;
}

// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        throw new Exception('Invalid CSRF token');
    }
    return true;
}

// Get projects with pagination
function getProjects($page = 1, $perPage = 9, $category = null, $featured = null) {
    $db = Database::getInstance()->getConnection();
    $offset = ($page - 1) * $perPage;
    $params = [];
    $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug FROM projects p LEFT JOIN categories c ON p.category_id = c.id";
    $where = [];
    if ($category) {
        $where[] = "c.slug = ?";
        $params[] = $category;
    }
    if ($featured !== null) {
        $where[] = "p.is_featured = ?";
        $params[] = $featured ? 1 : 0;
    }
    if ($where) {
        $sql .= " WHERE " . implode(' AND ', $where);
    }
    $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $perPage;
    $params[] = $offset;
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// Get total projects count
function getTotalProjects($category = null) {
    $db = Database::getInstance()->getConnection();
    
    $sql = "SELECT COUNT(*) as total FROM projects p";
    $params = [];
    
    if ($category) {
        $sql .= " LEFT JOIN categories c ON p.category_id = c.id WHERE c.slug = ?";
        $params[] = $category;
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetch();
    
    return $result['total'];
}

// Get project by slug
function getProjectBySlug($slug) {
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->prepare("
        SELECT p.*, c.name as category_name 
        FROM projects p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.slug = ?
    ");
    $stmt->execute([$slug]);
    
    return $stmt->fetch();
}

// Get project images
function getProjectImages($projectId) {
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->prepare("
        SELECT * FROM project_images 
        WHERE project_id = ? 
        ORDER BY sort_order ASC
    ");
    $stmt->execute([$projectId]);
    
    return $stmt->fetchAll();
}

// Get all categories
function getCategories() {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll();
}

// Get team members
function getTeamMembers() {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM team ORDER BY sort_order ASC");
    return $stmt->fetchAll();
}

// Get contact submissions
function getContactSubmissions($page = 1, $perPage = 20) {
    $db = Database::getInstance()->getConnection();
    
    $offset = ($page - 1) * $perPage;
    
    $stmt = $db->prepare("
        SELECT * FROM contact_submissions 
        ORDER BY created_at DESC 
        LIMIT ? OFFSET ?
    ");
    $stmt->execute([$perPage, $offset]);
    
    return $stmt->fetchAll();
}

// Save contact submission
function saveContactSubmission($name, $email, $message) {
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->prepare("
        INSERT INTO contact_submissions (name, email, message) 
        VALUES (?, ?, ?)
    ");
    
    return $stmt->execute([$name, $email, $message]);
}
// Get previous project's slug by ID
function getPreviousProjectSlug($currentId) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT slug FROM projects WHERE id < ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$currentId]);
    $result = $stmt->fetch();
    return $result ? $result['slug'] : null;
}

// Get next project's slug by ID
function getNextProjectSlug($currentId) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT slug FROM projects WHERE id > ? ORDER BY id ASC LIMIT 1");
    $stmt->execute([$currentId]);
    $result = $stmt->fetch();
    return $result ? $result['slug'] : null;
}
?>