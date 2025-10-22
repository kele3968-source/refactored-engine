<?php
session_start();
require_once '../config/database.php';

// 检查登录状态
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$db = Database::getInstance()->getConnection();

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        // 添加教师
        $name = trim($_POST['name']);
        $subject = trim($_POST['subject']);
        $experience = intval($_POST['experience']);
        $description = trim($_POST['description']);
        $certificates = trim($_POST['certificates']);
        $specialties = trim($_POST['specialties']);
        $avatar_url = trim($_POST['avatar_url']);
        $sort_order = intval($_POST['sort_order']);
        
        $stmt = $db->prepare("INSERT INTO teachers (name, subject, experience, description, certificates, specialties, avatar_url, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $subject, $experience, $description, $certificates, $specialties, $avatar_url, $sort_order])) {
            $_SESSION['message'] = '教师添加成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '添加失败！';
            $_SESSION['message_type'] = 'danger';
        }
    } elseif ($action === 'edit') {
        // 编辑教师
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $subject = trim($_POST['subject']);
        $experience = intval($_POST['experience']);
        $description = trim($_POST['description']);
        $certificates = trim($_POST['certificates']);
        $specialties = trim($_POST['specialties']);
        $avatar_url = trim($_POST['avatar_url']);
        $sort_order = intval($_POST['sort_order']);
        $status = $_POST['status'];
        
        $stmt = $db->prepare("UPDATE teachers SET name=?, subject=?, experience=?, description=?, certificates=?, specialties=?, avatar_url=?, sort_order=?, status=? WHERE id=?");
        if ($stmt->execute([$name, $subject, $experience, $description, $certificates, $specialties, $avatar_url, $sort_order, $status, $id])) {
            $_SESSION['message'] = '教师信息更新成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '更新失败！';
            $_SESSION['message_type'] = 'danger';
        }
    } elseif ($action === 'delete') {
        // 删除教师
        $id = intval($_POST['id']);
        $stmt = $db->prepare("DELETE FROM teachers WHERE id=?");
        if ($stmt->execute([$id])) {
            $_SESSION['message'] = '教师删除成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '删除失败！';
            $_SESSION['message_type'] = 'danger';
        }
    }
    
    header('Location: teachers.php');
    exit;
}

// 获取教师列表
$teachers = $db->query("SELECT * FROM teachers ORDER BY sort_order ASC, id DESC")->fetchAll();

// 获取编辑的教师数据
$edit_teacher = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $db->prepare("SELECT * FROM teachers WHERE id=?");
    $stmt->execute([$id]);
    $edit_teacher = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>教师团队管理 - 艾瑞斯教育</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">教师团队管理</h3>
                <a href="teachers.php?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> 添加教师
                </a>
            </div>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show">
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <?php if (isset($_GET['action']) && $_GET['action'] === 'add' || $edit_teacher): ?>
            <!-- 添加/编辑表单 -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><?php echo $edit_teacher ? '编辑教师信息' : '添加教师'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="<?php echo $edit_teacher ? 'edit' : 'add'; ?>">
                        <?php if ($edit_teacher): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_teacher['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">姓名</label>
                                    <input type="text" class="form-control" name="name" 
                                           value="<?php echo $edit_teacher ? $edit_teacher['name'] : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">科目</label>
                                    <input type="text" class="form-control" name="subject" 
                                           value="<?php echo $edit_teacher ? $edit_teacher['subject'] : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">教龄（年）</label>
                                    <input type="number" class="form-control" name="experience" 
                                           value="<?php echo $edit_teacher ? $edit_teacher['experience'] : '0'; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">排序</label>
                                    <input type="number" class="form-control" name="sort_order" 
                                           value="<?php echo $edit_teacher ? $edit_teacher['sort_order'] : '0'; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">头像URL</label>
                                    <input type="text" class="form-control" name="avatar_url" 
                                           value="<?php echo $edit_teacher ? $edit_teacher['avatar_url'] : ''; ?>">
                                    <?php if ($edit_teacher && $edit_teacher['avatar_url']): ?>
                                        <img src="<?php echo $edit_teacher['avatar_url']; ?>" class="preview-image" alt="预览">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">教师描述</label>
                            <textarea class="form-control" name="description" rows="3" required><?php echo $edit_teacher ? $edit_teacher['description'] : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">证书资质</label>
                                    <textarea class="form-control" name="certificates" rows="2" placeholder="用逗号分隔多个证书"><?php echo $edit_teacher ? $edit_teacher['certificates'] : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">教学专长</label>
                                    <textarea class="form-control" name="specialties" rows="2" placeholder="用逗号分隔多个专长"><?php echo $edit_teacher ? $edit_teacher['specialties'] : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($edit_teacher): ?>
                            <div class="mb-3">
                                <label class="form-label">状态</label>
                                <select class="form-control" name="status">
                                    <option value="active" <?php echo $edit_teacher['status'] === 'active' ? 'selected' : ''; ?>>激活</option>
                                    <option value="inactive" <?php echo $edit_teacher['status'] === 'inactive' ? 'selected' : ''; ?>>禁用</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">保存</button>
                            <a href="teachers.php" class="btn btn-secondary">取消</a>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- 教师列表 -->
            <div class="card">
                <div class="card-body">
                    <?php if (empty($teachers)): ?>
                        <p class="text-muted text-center">暂无教师数据</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>头像</th>
                                        <th>姓名</th>
                                        <th>科目</th>
                                        <th>教龄</th>
                                        <th>证书</th>
                                        <th>排序</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($teachers as $teacher): ?>
                                        <tr>
                                            <td>
                                                <?php if ($teacher['avatar_url']): ?>
                                                    <img src="<?php echo $teacher['avatar_url']; ?>" alt="<?php echo $teacher['name']; ?>" class="rounded-circle" width="50" height="50">
                                                <?php else: ?>
                                                    <i class="fas fa-user-circle fa-2x text-muted"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $teacher['name']; ?></td>
                                            <td><?php echo $teacher['subject']; ?></td>
                                            <td><?php echo $teacher['experience']; ?>年</td>
                                            <td><?php echo mb_strlen($teacher['certificates']) > 20 ? mb_substr($teacher['certificates'], 0, 20) . '...' : $teacher['certificates']; ?></td>
                                            <td><?php echo $teacher['sort_order']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $teacher['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo $teacher['status'] === 'active' ? '激活' : '禁用'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="teachers.php?edit=<?php echo $teacher['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" style="display: inline-block;" onsubmit="return confirm('确定删除这位教师吗？')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $teacher['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>