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
        // 添加活动
        $title = trim($_POST['title']);
        $category = trim($_POST['category']);
        $description = trim($_POST['description']);
        $image_url = trim($_POST['image_url']);
        $location = trim($_POST['location']);
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $sort_order = intval($_POST['sort_order']);
        
        $stmt = $db->prepare("INSERT INTO activities (title, category, description, image_url, location, start_date, end_date, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $category, $description, $image_url, $location, $start_date, $end_date, $sort_order])) {
            $_SESSION['message'] = '活动添加成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '添加失败！';
            $_SESSION['message_type'] = 'danger';
        }
    } elseif ($action === 'edit') {
        // 编辑活动
        $id = intval($_POST['id']);
        $title = trim($_POST['title']);
        $category = trim($_POST['category']);
        $description = trim($_POST['description']);
        $image_url = trim($_POST['image_url']);
        $location = trim($_POST['location']);
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $sort_order = intval($_POST['sort_order']);
        $status = $_POST['status'];
        
        $stmt = $db->prepare("UPDATE activities SET title=?, category=?, description=?, image_url=?, location=?, start_date=?, end_date=?, sort_order=?, status=? WHERE id=?");
        if ($stmt->execute([$title, $category, $description, $image_url, $location, $start_date, $end_date, $sort_order, $status, $id])) {
            $_SESSION['message'] = '活动更新成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '更新失败！';
            $_SESSION['message_type'] = 'danger';
        }
    } elseif ($action === 'delete') {
        // 删除活动
        $id = intval($_POST['id']);
        $stmt = $db->prepare("DELETE FROM activities WHERE id=?");
        if ($stmt->execute([$id])) {
            $_SESSION['message'] = '活动删除成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '删除失败！';
            $_SESSION['message_type'] = 'danger';
        }
    }
    
    header('Location: activities.php');
    exit;
}

// 获取活动列表
$activities = $db->query("SELECT * FROM activities ORDER BY sort_order ASC, start_date DESC")->fetchAll();

// 获取编辑的活动数据
$edit_activity = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $db->prepare("SELECT * FROM activities WHERE id=?");
    $stmt->execute([$id]);
    $edit_activity = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>活动管理 - 艾瑞斯教育</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">活动管理</h3>
                <a href="activities.php?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> 添加活动
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

        <?php if (isset($_GET['action']) && $_GET['action'] === 'add' || $edit_activity): ?>
            <!-- 添加/编辑表单 -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><?php echo $edit_activity ? '编辑活动' : '添加活动'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="<?php echo $edit_activity ? 'edit' : 'add'; ?>">
                        <?php if ($edit_activity): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_activity['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">活动名称</label>
                            <input type="text" class="form-control" name="title" 
                                   value="<?php echo $edit_activity ? $edit_activity['title'] : ''; ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">类别</label>
                                    <select class="form-control" name="category" required>
                                        <option value="">请选择</option>
                                        <option value="节日庆典" <?php echo ($edit_activity && $edit_activity['category'] === '节日庆典') ? 'selected' : ''; ?>>节日庆典</option>
                                        <option value="比赛竞赛" <?php echo ($edit_activity && $edit_activity['category'] === '比赛竞赛') ? 'selected' : ''; ?>>比赛竞赛</option>
                                        <option value="户外探索" <?php echo ($edit_activity && $edit_activity['category'] === '户外探索') ? 'selected' : ''; ?>>户外探索</option>
                                        <option value="艺术展演" <?php echo ($edit_activity && $edit_activity['category'] === '艺术展演') ? 'selected' : ''; ?>>艺术展演</option>
                                        <option value="其他" <?php echo ($edit_activity && $edit_activity['category'] === '其他') ? 'selected' : ''; ?>>其他</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">活动地点</label>
                                    <input type="text" class="form-control" name="location" 
                                           value="<?php echo $edit_activity ? $edit_activity['location'] : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">活动描述</label>
                            <textarea class="form-control" name="description" rows="3" required><?php echo $edit_activity ? $edit_activity['description'] : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">图片URL</label>
                            <input type="text" class="form-control" name="image_url" 
                                   value="<?php echo $edit_activity ? $edit_activity['image_url'] : ''; ?>">
                            <?php if ($edit_activity && $edit_activity['image_url']): ?>
                                <img src="<?php echo $edit_activity['image_url']; ?>" class="preview-image" alt="预览">
                            <?php endif; ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">开始日期</label>
                                    <input type="date" class="form-control" name="start_date" 
                                           value="<?php echo $edit_activity ? $edit_activity['start_date'] : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">结束日期</label>
                                    <input type="date" class="form-control" name="end_date" 
                                           value="<?php echo $edit_activity ? $edit_activity['end_date'] : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">排序</label>
                            <input type="number" class="form-control" name="sort_order" 
                                   value="<?php echo $edit_activity ? $edit_activity['sort_order'] : '0'; ?>" required>
                        </div>
                        
                        <?php if ($edit_activity): ?>
                            <div class="mb-3">
                                <label class="form-label">状态</label>
                                <select class="form-control" name="status">
                                    <option value="active" <?php echo $edit_activity['status'] === 'active' ? 'selected' : ''; ?>>激活</option>
                                    <option value="inactive" <?php echo $edit_activity['status'] === 'inactive' ? 'selected' : ''; ?>>禁用</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">保存</button>
                            <a href="activities.php" class="btn btn-secondary">取消</a>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- 活动列表 -->
            <div class="card">
                <div class="card-body">
                    <?php if (empty($activities)): ?>
                        <p class="text-muted text-center">暂无活动数据</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>活动名称</th>
                                        <th>类别</th>
                                        <th>地点</th>
                                        <th>开始日期</th>
                                        <th>结束日期</th>
                                        <th>排序</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($activities as $activity): ?>
                                        <tr>
                                            <td><?php echo $activity['title']; ?></td>
                                            <td><?php echo $activity['category']; ?></td>
                                            <td><?php echo $activity['location']; ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($activity['start_date'])); ?></td>
                                            <td><?php echo $activity['end_date'] ? date('Y-m-d', strtotime($activity['end_date'])) : '同开始日期'; ?></td>
                                            <td><?php echo $activity['sort_order']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $activity['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo $activity['status'] === 'active' ? '激活' : '禁用'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="activities.php?edit=<?php echo $activity['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" style="display: inline-block;" onsubmit="return confirm('确定删除这个活动吗？')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $activity['id']; ?>">
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