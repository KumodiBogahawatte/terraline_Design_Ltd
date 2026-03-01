<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
Auth::requireAdmin();

require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance()->getConnection();
$message = '';
$error = '';

// Handle category deletion
if (isset($_GET['delete'])) {
    try {
        $id = (int)$_GET['delete'];
        
        // Check if category has projects
        $stmt = $db->prepare("SELECT COUNT(*) FROM projects WHERE category_id = ?");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            throw new Exception('Cannot delete category with existing projects');
        }
        
        $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        
        $message = 'Category deleted successfully';
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Handle category addition/editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            throw new Exception('Invalid security token');
        }
        
        $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
        $name = sanitize($_POST['name']);
        $slug = isset($_POST['slug']) ? sanitize($_POST['slug']) : createSlug($name);
        
        if ($id) {
            $stmt = $db->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $id]);
            $message = 'Category updated successfully';
        } else {
            $stmt = $db->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
            $stmt->execute([$name, $slug]);
            $message = 'Category added successfully';
        }
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            $error = 'Category name or slug already exists';
        } else {
            $error = 'Error saving category: ' . $e->getMessage();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get category for editing
$editCategory = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editCategory = $stmt->fetch();
}

// Get all categories
$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$csrf_token = generateCSRFToken();
$pageTitle = 'Categories';
include __DIR__ . '/includes/header.php';
?>

<div class="admin-content">
    <div class="admin-header">
        <a href="/architecture-firm/admin/index.php" class="btn-secondary" style="float:left;margin-right:16px;">&larr; Back</a>
        <h1 style="display:inline-block;">Categories</h1>
    </div>
    
    <?php if ($message): ?>
    <div class="alert success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="form-container">
        <h2><?php echo $editCategory ? 'Edit Category' : 'Add New Category'; ?></h2>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <?php if ($editCategory): ?>
            <input type="hidden" name="id" value="<?php echo $editCategory['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="name">Category Name *</label>
                <input type="text" id="name" name="name" required 
                       value="<?php echo $editCategory ? $editCategory['name'] : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="slug">Slug (leave empty to auto-generate)</label>
                <input type="text" id="slug" name="slug" 
                       value="<?php echo $editCategory ? $editCategory['slug'] : ''; ?>">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-primary">Save Category</button>
                <?php if ($editCategory): ?>
                <a href="categories.php" class="btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Projects Count</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <?php
            $stmt = $db->prepare("SELECT COUNT(*) FROM projects WHERE category_id = ?");
            $stmt->execute([$category['id']]);
            $projectCount = $stmt->fetchColumn();
            ?>
            <tr>
                <td data-label="ID"><?php echo $category['id']; ?></td>
                <td data-label="Name"><?php echo $category['name']; ?></td>
                <td data-label="Slug"><?php echo $category['slug']; ?></td>
                <td data-label="Projects"><?php echo $projectCount; ?></td>
                <td class="actions">
                    <a href="?edit=<?php echo $category['id']; ?>" class="btn-small">Edit</a>
                    <?php if ($projectCount == 0): ?>
                    <a href="?delete=<?php echo $category['id']; ?>" class="btn-small danger" 
                       onclick="return confirm('Are you sure?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>