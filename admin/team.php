<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
Auth::requireLogin();

require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance()->getConnection();
$message = '';
$error = '';
$error = '';

// Handle team member deletion
if (isset($_GET['delete'])) {
    try {
        $id = (int)$_GET['delete'];
        
        // Get image to delete
        $stmt = $db->prepare("SELECT image FROM team WHERE id = ?");
        $stmt->execute([$id]);
        $member = $stmt->fetch();
        
        if ($member && $member['image']) {
            $filePath = __DIR__ . '/../uploads/team/' . $member['image'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $stmt = $db->prepare("DELETE FROM team WHERE id = ?");
        $stmt->execute([$id]);
        
        $message = 'Team member deleted successfully';
    } catch (Exception $e) {
        $error = 'Error deleting member: ' . $e->getMessage();
    }
}

// Handle team member addition/editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            throw new Exception('Invalid security token');
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
        $name = sanitize($_POST['name']);
        $role = sanitize($_POST['role']);
        $bio = sanitize($_POST['bio']);
        $sort_order = (int)$_POST['sort_order'];
        
        // Handle image upload
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/team/';
            $image = uploadFile($_FILES['image'], $uploadDir);
        }
        
        if ($id) {
            // Update existing
            $sql = "UPDATE team SET name = ?, role = ?, bio = ?, sort_order = ?";
            $params = [$name, $role, $bio, $sort_order];
            
            if ($image) {
                // Get old image to delete
                $stmt = $db->prepare("SELECT image FROM team WHERE id = ?");
                $stmt->execute([$id]);
                $old = $stmt->fetch();
                if ($old && $old['image']) {
                    $oldPath = __DIR__ . '/../uploads/team/' . $old['image'];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $sql .= ", image = ?";
                $params[] = $image;
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $message = 'Team member updated successfully';
        } else {
            // Insert new
            $stmt = $db->prepare("
                INSERT INTO team (name, role, bio, image, sort_order) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $role, $bio, $image, $sort_order]);
            $message = 'Team member added successfully';
        }
    } catch (Exception $e) {
        $error = 'Error saving member: ' . $e->getMessage();
    }
}

// Get member for editing
$editMember = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM team WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editMember = $stmt->fetch();
}

// Get all team members
$team = $db->query("SELECT * FROM team ORDER BY sort_order ASC")->fetchAll();

$csrf_token = generateCSRFToken();
$pageTitle = 'Team Management';
include __DIR__ . '/includes/header.php';
?>

<div class="admin-content">
    <div class="admin-header">
        <a href="/architecture-firm/admin/index.php" class="btn-secondary" style="float:left;margin-right:16px;">&larr; Back</a>
        <h1 style="display:inline-block;">Team Members</h1>
        <?php if (!$editMember): ?>
        <button class="btn-primary" onclick="document.getElementById('addForm').classList.toggle('hidden')">
            Add New Member
        </button>
        <?php endif; ?>
    </div>
    
    <?php if ($message): ?>
    <div class="alert success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- Add/Edit Form -->
    <div class="form-container <?php echo !$editMember ? 'hidden' : ''; ?>" id="addForm">
        <h2><?php echo $editMember ? 'Edit Member' : 'Add New Team Member'; ?></h2>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <?php if ($editMember): ?>
            <input type="hidden" name="id" value="<?php echo $editMember['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="name">Name *</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo $editMember ? $editMember['name'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="role">Role *</label>
                <input type="text" id="role" name="role" required 
                       value="<?php echo $editMember ? $editMember['role'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="4"><?php echo $editMember ? $editMember['bio'] : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" 
                       value="<?php echo $editMember ? $editMember['sort_order'] : '0'; ?>">
            </div>
            
            <div class="form-group">
                <label for="image">Profile Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if ($editMember && $editMember['image']): ?>
                <div class="current-image">
                    <img src="/architecture-firm/uploads/team/<?php echo $editMember['image']; ?>" alt="Current image" style="max-width: 100px; margin-top: 10px;">
                </div>
                <?php endif; ?>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-primary">Save Member</button>
                <a href="team.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
    
    <!-- Team Members List -->
    <?php if (!$editMember): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Role</th>
                <th>Sort Order</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($team as $member): ?>
            <tr>
                <td data-label="Image">
                    <?php if ($member['image']): ?>
                    <img src="/architecture-firm/uploads/team/<?php echo $member['image']; ?>" 
                         alt="<?php echo $member['name']; ?>" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                    <?php endif; ?>
                </td>
                <td data-label="Name"><?php echo $member['name']; ?></td>
                <td data-label="Role"><?php echo $member['role']; ?></td>
                <td data-label="Order"><?php echo $member['sort_order']; ?></td>
                <td class="actions">
                    <a href="?edit=<?php echo $member['id']; ?>" class="btn-small">Edit</a>
                    <a href="?delete=<?php echo $member['id']; ?>" class="btn-small danger" 
                       onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>