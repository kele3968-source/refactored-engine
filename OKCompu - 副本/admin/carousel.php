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
        // 添加轮播图
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $image_url = trim($_POST['image_url']);
        $link_url = trim($_POST['link_url']);
        $sort_order = intval($_POST['sort_order']);
        
        $stmt = $db->prepare("INSERT INTO carousel (title, description, image_url, link_url, sort_order) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $image_url, $link_url, $sort_order])) {
            $_SESSION['message'] = '轮播图添加成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '添加失败！';
            $_SESSION['message_type'] = 'danger';
        }
    } elseif ($action === 'edit') {
        // 编辑轮播图
        $id = intval($_POST['id']);
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $image_url = trim($_POST['image_url']);
        $link_url = trim($_POST['link_url']);
        $sort_order = intval($_POST['sort_order']);
        $status = $_POST['status'];
        
        $stmt = $db->prepare("UPDATE carousel SET title=?, description=?, image_url=?, link_url=?, sort_order=?, status=? WHERE id=?");
        if ($stmt->execute([$title, $description, $image_url, $link_url, $sort_order, $status, $id])) {
            $_SESSION['message'] = '轮播图更新成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '更新失败！';
            $_SESSION['message_type'] = 'danger';
        }
    } elseif ($action === 'delete') {
        // 删除轮播图
        $id = intval($_POST['id']);
        $stmt = $db->prepare("DELETE FROM carousel WHERE id=?");
        if ($stmt->execute([$id])) {
            $_SESSION['message'] = '轮播图删除成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '删除失败！';
            $_SESSION['message_type'] = 'danger';
        }
    }
    
    header('Location: carousel.php');
    exit;
}

// 获取轮播图列表
$carousels = $db->query("SELECT * FROM carousel ORDER BY sort_order ASC, id DESC")->fetchAll();

// 获取编辑的轮播图数据
$edit_carousel = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $db->prepare("SELECT * FROM carousel WHERE id=?");
    $stmt->execute([$id]);
    $edit_carousel = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>轮播图管理 - 艾瑞斯教育</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #2C3E50;
        }
        
        .sidebar {
            background: var(--secondary-color);
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .table img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        
        .preview-image {
            max-width: 200px;
            max-height: 150px;
            border-radius: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">轮播图管理</h3>
                <a href="carousel.php?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> 添加轮播图
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

        <?php if (isset($_GET['action']) && $_GET['action'] === 'add' || $edit_carousel): ?>
            <!-- 添加/编辑表单 -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><?php echo $edit_carousel ? '编辑轮播图' : '添加轮播图'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="<?php echo $edit_carousel ? 'edit' : 'add'; ?>">
                        <?php if ($edit_carousel): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_carousel['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">标题</label>
                                    <input type="text" class="form-control" name="title" 
                                           value="<?php echo $edit_carousel ? $edit_carousel['title'] : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">排序</label>
                                    <input type="number" class="form-control" name="sort_order" 
                                           value="<?php echo $edit_carousel ? $edit_carousel['sort_order'] : '0'; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">描述</label>
                            <textarea class="form-control" name="description" rows="3"><?php echo $edit_carousel ? $edit_carousel['description'] : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">图片URL</label>
                                    <input type="text" class="form-control" name="image_url" 
                                           value="<?php echo $edit_carousel ? $edit_carousel['image_url'] : ''; ?>" required>
                                    <?php if ($edit_carousel && $edit_carousel['image_url']): ?>
                                        <img src="<?php echo $edit_carousel['image_url']; ?>" class="preview-image" alt="预览">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">链接URL</label>
                                    <input type="text" class="form-control" name="link_url" 
                                           value="<?php echo $edit_carousel ? $edit_carousel['link_url'] : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($edit_carousel): ?>
                            <div class="mb-3">
                                <label class="form-label">状态</label>
                                <select class="form-control" name="status">
                                    <option value="active" <?php echo $edit_carousel['status'] === 'active' ? 'selected' : ''; ?>>激活</option>
                                    <option value="inactive" <?php echo $edit_carousel['status'] === 'inactive' ? 'selected' : ''; ?>>禁用</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">保存</button>
                            <a href="carousel.php" class="btn btn-secondary">取消</a>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- 轮播图列表 -->
            <div class="card">
                <div class="card-body">
                    <?php if (empty($carousels)): ?>
                        <p class="text-muted text-center">暂无轮播图数据</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>图片</th>
                                        <th>标题</th>
                                        <th>描述</th>
                                        <th>排序</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($carousels as $carousel): ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo $carousel['image_url']; ?>" alt="<?php echo $carousel['title']; ?>">
                                            </td>
                                            <td><?php echo $carousel['title']; ?></td>
                                            <td><?php echo mb_strlen($carousel['description']) > 50 ? mb_substr($carousel['description'], 0, 50) . '...' : $carousel['description']; ?></td>
                                            <td><?php echo $carousel['sort_order']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $carousel['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo $carousel['status'] === 'active' ? '激活' : '禁用'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="carousel.php?edit=<?php echo $carousel['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" style="display: inline-block;" onsubmit="return confirm('确定删除这个轮播图吗？')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $carousel['id']; ?>">
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
    <script>
        // 图片预览功能
        document.querySelector('input[name="image_url"]')?.addEventListener('input', function(e) {
            const preview = this.parentNode.querySelector('.preview-image') || document.createElement('img');
            if (!preview.classList.contains('preview-image')) {
                preview.className = 'preview-image';
                this.parentNode.appendChild(preview);
            }
            preview.src = e.target.value;
        });
    </script>
</body>
</html>