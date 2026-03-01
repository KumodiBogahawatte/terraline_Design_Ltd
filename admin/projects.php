<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
Auth::requireLogin();

require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance()->getConnection();
$message = '';
$error = '';

// Handle gallery image deletion
if (isset($_GET['delete_image']) && isset($_GET['project'])) {
    try {
        $imgId = (int)$_GET['delete_image'];
        $projectId = (int)$_GET['project'];

        $stmt = $db->prepare("SELECT img_url FROM project_images WHERE id = ? AND project_id = ?");
        $stmt->execute([$imgId, $projectId]);
        $img = $stmt->fetch();

        if ($img) {
            $filePath = __DIR__ . '/../uploads/projects/' . $img['img_url'];
            if (file_exists($filePath)) unlink($filePath);

            $stmt = $db->prepare("DELETE FROM project_images WHERE id = ?");
            $stmt->execute([$imgId]);
            $message = 'Image deleted successfully';
        }

        header('Location: ?edit=' . $projectId . '&message=' . urlencode($message));
        exit();
    } catch (Exception $e) {
        $error = 'Error deleting image: ' . $e->getMessage();
    }
}

// Handle project deletion
if (isset($_GET['delete'])) {
    try {
        $id = (int)$_GET['delete'];
        
        // Get project images to delete files
        $stmt = $db->prepare("SELECT img_url FROM project_images WHERE project_id = ?");
        $stmt->execute([$id]);
        $images = $stmt->fetchAll();
        
        // Delete image files
        foreach ($images as $image) {
            $filePath = __DIR__ . '/../uploads/projects/' . $image['img_url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Delete featured image
        $stmt = $db->prepare("SELECT featured_img FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $project = $stmt->fetch();
        if ($project && $project['featured_img']) {
            $filePath = __DIR__ . '/../uploads/projects/' . $project['featured_img'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Delete gallery images from DB then project
        $stmt = $db->prepare("DELETE FROM project_images WHERE project_id = ?");
        $stmt->execute([$id]);

        $stmt = $db->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        
        $message = 'Project deleted successfully';
    } catch (Exception $e) {
        $error = 'Error deleting project: ' . $e->getMessage();
    }
}

// Handle project addition/editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
            throw new Exception('Invalid security token');
        }
        $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
        $title = sanitize($_POST['title']);
        $slug = isset($_POST['slug']) ? sanitize($_POST['slug']) : createSlug($title);
        $category_id = (int)$_POST['category_id'];
        $location = sanitize($_POST['location']);
        $year = (int)$_POST['year'];
        $area = sanitize($_POST['area']);
        $client = sanitize($_POST['client']);
        $description = sanitize($_POST['description']);
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        // Handle featured image upload
        $featured_img = null;
        if (isset($_FILES['featured_img']) && $_FILES['featured_img']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../uploads/projects/';
            $featured_img = uploadFile($_FILES['featured_img'], $uploadDir);
        }
        if ($id) {
            // Update existing project
            $sql = "UPDATE projects SET 
                title = ?, slug = ?, category_id = ?, location = ?, 
                year = ?, area = ?, client = ?, description = ?, is_featured = ?";
            $params = [$title, $slug, $category_id, $location, $year, $area, $client, $description, $is_featured];
            if ($featured_img) {
                // Get old image to delete
                $stmt = $db->prepare("SELECT featured_img FROM projects WHERE id = ?");
                $stmt->execute([$id]);
                $old = $stmt->fetch();
                if ($old && $old['featured_img']) {
                    $oldPath = __DIR__ . '/../uploads/projects/' . $old['featured_img'];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $sql .= ", featured_img = ?";
                $params[] = $featured_img;
            }
            $sql .= " WHERE id = ?";
            $params[] = $id;
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $message = 'Project updated successfully';
        } else {
            // Insert new project
            $stmt = $db->prepare("
                INSERT INTO projects (title, slug, category_id, location, year, area, client, description, featured_img, is_featured) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$title, $slug, $category_id, $location, $year, $area, $client, $description, $featured_img, $is_featured]);
            $id = $db->lastInsertId();
            $message = 'Project added successfully';
        }
        // Handle multiple image uploads
        if (isset($_FILES['gallery_images'])) {
            $files = $_FILES['gallery_images'];
            $uploadDir = __DIR__ . '/../uploads/projects/';
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $files['name'][$i],
                        'type' => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error' => $files['error'][$i],
                        'size' => $files['size'][$i]
                    ];
                    $filename = uploadFile($file, $uploadDir);
                    if ($filename) {
                        $caption = isset($_POST['captions'][$i]) ? sanitize($_POST['captions'][$i]) : '';
                        $sortOrder = isset($_POST['sort_order'][$i]) ? (int)$_POST['sort_order'][$i] : $i;
                        $stmt = $db->prepare("
                            INSERT INTO project_images (project_id, img_url, caption, sort_order) 
                            VALUES (?, ?, ?, ?)
                        ");
                        $stmt->execute([$id, $filename, $caption, $sortOrder]);
                    }
                }
            }
        }
        // Redirect to projects list after save
        header('Location: projects.php?message=' . urlencode($message));
        exit();
    } catch (Exception $e) {
        $error = 'Error saving project: ' . $e->getMessage();
    }
}

// Get project for editing
$editProject = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editProject = $stmt->fetch();
    
    if ($editProject) {
        // Get project images
        $stmt = $db->prepare("SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order");
        $stmt->execute([$editProject['id']]);
        $editProject['images'] = $stmt->fetchAll();
    }
}

// Get all projects
$projects = $db->query("
    SELECT p.*, c.name as category_name 
    FROM projects p 
    LEFT JOIN categories c ON p.category_id = c.id 
    ORDER BY p.created_at DESC
")->fetchAll();

// Get categories for dropdown
$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$csrf_token = generateCSRFToken();
$pageTitle = $editProject ? 'Edit Project' : 'Projects';
include __DIR__ . '/includes/header.php';
?>

<div class="admin-content">
    <div class="admin-header">
        <a href="/architecture-firm/admin/index.php" class="btn-secondary" style="float:left;margin-right:16px;">&larr; Back</a>
        <h1 style="display:inline-block;">
            <?php echo $editProject ? 'Edit Project' : 'Projects'; ?>
        </h1>
        <?php if (!$editProject): ?>
        <button class="btn-primary" onclick="document.getElementById('addForm').classList.toggle('hidden')">
            Add New Project
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
    <div class="form-container <?php echo !$editProject ? 'hidden' : ''; ?>" id="addForm">
        <h2><?php echo $editProject ? 'Edit Project' : 'Add New Project'; ?></h2>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <?php if ($editProject): ?>
            <input type="hidden" name="id" value="<?php echo $editProject['id']; ?>">
            <?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group">
                                    <div class="form-group">
                                        <label for="is_featured">
                                            <input type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo ($editProject && $editProject['is_featured']) ? 'checked' : ''; ?>>
                                            <span>Mark as Featured Project</span>
                                        </label>
                                    </div>
                    <label for="title">Project Title *</label>
                    <input type="text" id="title" name="title" required 
                           value="<?php echo $editProject ? $editProject['title'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="slug">Slug (leave empty to auto-generate)</label>
                    <input type="text" id="slug" name="slug" 
                           value="<?php echo $editProject ? $editProject['slug'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="category_id">Category *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" 
                                <?php echo ($editProject && $editProject['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo $cat['name']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" 
                           value="<?php echo $editProject ? $editProject['location'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="number" id="year" name="year" 
                           value="<?php echo $editProject ? $editProject['year'] : date('Y'); ?>">
                </div>
                
                <div class="form-group">
                    <label for="area">Area</label>
                    <input type="text" id="area" name="area" placeholder="e.g., 5000 sq ft"
                           value="<?php echo $editProject ? $editProject['area'] : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="client">Client</label>
                    <input type="text" id="client" name="client" 
                           value="<?php echo $editProject ? $editProject['client'] : ''; ?>">
                </div>
                
                <div class="form-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="6"><?php echo $editProject ? $editProject['description'] : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="featured_img">Featured Image</label>
                    <input type="file" id="featured_img" name="featured_img" accept="image/*">
                    <?php if ($editProject && $editProject['featured_img']): ?>
                    <div class="current-image">
                        <img src="/architecture-firm/uploads/projects/<?php echo $editProject['featured_img']; ?>" alt="Current featured image" style="max-width: 200px; margin-top: 10px;">
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group full-width">
                    <label>Gallery Images</label>
                    <div id="gallery-container">
                        <div class="gallery-row">
                            <input type="file" name="gallery_images[]" accept="image/*" multiple>
                            <input type="text" name="captions[]" placeholder="Caption (optional)">
                        </div>
                    </div>
                    <button type="button" onclick="addGalleryRow()" class="btn-secondary">Add More Images</button>
                </div>
            </div>
            
            <?php if ($editProject && !empty($editProject['images'])): ?>
            <div class="existing-images">
                <h3>Existing Gallery Images</h3>
                <div class="image-grid">
                    <?php foreach ($editProject['images'] as $img): ?>
                    <div class="image-item">
                        <img src="/architecture-firm/uploads/projects/<?php echo $img['img_url']; ?>" alt="<?php echo $img['caption']; ?>">
                        <p><?php echo $img['caption']; ?></p>
                        <a href="?delete_image=<?php echo $img['id']; ?>&project=<?php echo $editProject['id']; ?>" 
                           class="btn-small danger" onclick="return confirm('Delete this image?')">Delete</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="form-actions">
                <button type="submit" class="btn-primary">Save Project</button>
                <a href="projects.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
    
    <!-- Projects List -->
    <?php if (!$editProject): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Location</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $project): ?>
            <tr>
                <td data-label="Image">
                    <?php if ($project['featured_img']): ?>
                    <img src="/architecture-firm/uploads/projects/<?php echo $project['featured_img']; ?>" 
                         alt="<?php echo $project['title']; ?>" style="width: 60px; height: 60px; object-fit: cover;">
                    <?php endif; ?>
                </td>
                <td data-label="Title"><?php echo $project['title']; ?></td>
                <td data-label="Category"><?php echo $project['category_name']; ?></td>
                <td data-label="Location"><?php echo $project['location']; ?></td>
                <td data-label="Year"><?php echo $project['year']; ?></td>
                <td class="actions">
                    <a href="?edit=<?php echo $project['id']; ?>" class="btn-small">Edit</a>
                    <a href="?delete=<?php echo $project['id']; ?>" class="btn-small danger" 
                       onclick="return confirm('Are you sure you want to delete this project?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<script>
function addGalleryRow() {
    const container = document.getElementById('gallery-container');
    const row = document.createElement('div');
    row.className = 'gallery-row';
    row.innerHTML = `
        <input type="file" name="gallery_images[]" accept="image/*">
        <input type="text" name="captions[]" placeholder="Caption (optional)">
        <button type="button" onclick="this.parentElement.remove()" class="btn-small danger">Remove</button>
    `;
    container.appendChild(row);
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>