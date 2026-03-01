<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
Auth::requireLogin();

require_once __DIR__ . '/../includes/functions.php';

$db = Database::getInstance()->getConnection();

// Mark as read
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $stmt = $db->prepare("UPDATE contact_submissions SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: submissions.php?filter=' . $filter);
    exit();
}

// Mark as unread
if (isset($_GET['unread'])) {
    $id = (int)$_GET['unread'];
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $stmt = $db->prepare("UPDATE contact_submissions SET is_read = 0 WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: submissions.php?filter=' . $filter);
    exit();
}

// Delete submission
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $stmt = $db->prepare("DELETE FROM contact_submissions WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: submissions.php?filter=' . $filter);
    exit();
}

// Build filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$whereClause = '';
if ($filter === 'unread') {
    $whereClause = 'WHERE is_read = 0';
} elseif ($filter === 'read') {
    $whereClause = 'WHERE is_read = 1';
}

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 20;
$offset = ($page - 1) * $perPage;

$submissions = $db->query("SELECT * FROM contact_submissions $whereClause ORDER BY created_at DESC LIMIT $perPage OFFSET $offset")->fetchAll();
$total       = $db->query("SELECT COUNT(*) FROM contact_submissions $whereClause")->fetchColumn();
$totalPages  = ceil($total / $perPage);

$unreadCount = $db->query("SELECT COUNT(*) FROM contact_submissions WHERE is_read = 0")->fetchColumn();
$readCount   = $db->query("SELECT COUNT(*) FROM contact_submissions WHERE is_read = 1")->fetchColumn();
$allCount    = $db->query("SELECT COUNT(*) FROM contact_submissions")->fetchColumn();

$pageTitle = 'Contact Submissions';
include __DIR__ . '/includes/header.php';
?>

<div class="admin-content">
    <div class="admin-header">
        <a href="/architecture-firm/admin/index.php" class="btn-secondary" style="float:left;margin-right:16px;">&larr; Back</a>
        <h1 style="display:inline-block;">Contact Submissions</h1>
        <div class="submissions-meta">
            <?php echo $allCount; ?> total &nbsp;&middot;&nbsp; <?php echo $unreadCount; ?> unread
        </div>
    </div>

    <div class="submissions-filters">
        <a href="?filter=all"    class="filter-btn <?php echo $filter==='all'    ? 'active':''; ?>">All <span class="filter-count"><?php echo $allCount; ?></span></a>
        <a href="?filter=unread" class="filter-btn <?php echo $filter==='unread' ? 'active':''; ?>">Unread <span class="filter-count"><?php echo $unreadCount; ?></span></a>
        <a href="?filter=read"   class="filter-btn <?php echo $filter==='read'   ? 'active':''; ?>">Read <span class="filter-count"><?php echo $readCount; ?></span></a>
    </div>

    <?php if (empty($submissions)): ?>
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>No submissions found.</p>
    </div>
    <?php else: ?>
    <table class="data-table submissions-table">
        <thead>
            <tr>
                <th style="width:90px;">Status</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th style="width:140px;">Date</th>
                <th style="width:160px; text-align:right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($submissions as $sub): ?>
            <tr class="<?php echo !$sub['is_read'] ? 'unread' : ''; ?>">
                <td data-label="Status">
                    <?php if (!$sub['is_read']): ?>
                        <span class="status-badge status-new"><i class="fas fa-circle" style="font-size:7px;"></i> New</span>
                    <?php else: ?>
                        <span class="status-badge status-read"><i class="fas fa-check" style="font-size:9px;"></i> Read</span>
                    <?php endif; ?>
                </td>
                <td data-label="Name"><strong style="font-weight:500;"><?php echo htmlspecialchars($sub['name']); ?></strong></td>
                <td data-label="Email"><a href="mailto:<?php echo htmlspecialchars($sub['email']); ?>" style="color:var(--gold);text-decoration:none;"><?php echo htmlspecialchars($sub['email']); ?></a></td>
                <td data-label="Message" class="message-cell">
                    <div>
                        <?php echo htmlspecialchars(substr($sub['message'], 0, 80)); ?><?php echo strlen($sub['message'])>80?'…':''; ?>
                    </div>
                    <button class="btn-link" onclick="showMsg('<?php echo htmlspecialchars(addslashes($sub['name'])); ?>','<?php echo htmlspecialchars(addslashes($sub['message'])); ?>')" style="font-size:11.5px;margin-top:3px;display:block;">
                        <i class="fas fa-expand-alt" style="font-size:10px;"></i> View full message
                    </button>
                </td>
                <td data-label="Date" class="date-cell">
                    <?php echo date('M d, Y', strtotime($sub['created_at'])); ?><br>
                    <span class="date-time"><?php echo date('H:i', strtotime($sub['created_at'])); ?></span>
                </td>
                <td class="actions">
                    <?php if(!$sub['is_read']): ?>
                    <a href="?read=<?php echo $sub['id']; ?>&filter=<?php echo $filter; ?>" class="btn-small"><i class="fas fa-check"></i> Read</a>
                    <?php else: ?>
                    <a href="?unread=<?php echo $sub['id']; ?>&filter=<?php echo $filter; ?>" class="btn-small"><i class="fas fa-undo"></i> Unread</a>
                    <?php endif; ?>
                    <a href="?delete=<?php echo $sub['id']; ?>&filter=<?php echo $filter; ?>" class="btn-small danger" onclick="return confirm('Delete this submission?')"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if($totalPages>1): ?>
    <div class="pagination">
        <?php for($i=1;$i<=$totalPages;$i++): ?>
        <a href="?filter=<?php echo $filter; ?>&page=<?php echo $i; ?>" class="<?php echo $i===$page?'active':''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<div id="msgModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('msgModal').style.display='none'">&times;</span>
        <h3 id="modalName" style="margin-bottom:16px;"></h3>
        <p id="modalMsg" class="modal-message"></p>
    </div>
</div>



<script>
function showMsg(name,msg){
    document.getElementById('modalName').textContent='Message from '+name;
    document.getElementById('modalMsg').textContent=msg;
    document.getElementById('msgModal').style.display='block';
}
window.onclick=function(e){if(e.target===document.getElementById('msgModal'))document.getElementById('msgModal').style.display='none';}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>