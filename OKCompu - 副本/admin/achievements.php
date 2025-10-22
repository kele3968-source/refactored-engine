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
        // 添加学习成果
        $title = trim($_POST['title']);
        $category = trim($_POST['category']);
        $student_name = trim($_POST['student_name']);
        $description = trim($_POST['description']);
        $image_url = trim($_POST['image_url']);
        $achievement_date = $_POST['achievement_date'];
        $sort_order = intval($_POST['sort_order']);
        
        $stmt = $db->prepare("INSERT INTO achievements (title, category, student_name, description, image_url, achievement_date, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $category, $student_name, $description, $image_url, $achievement_date, $sort_order])) {
            $_SESSION['message'] = '学习成果添加成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '添加失败！';
            $_SESSION['message_type'] = 'danger';
        }
    } elseif ($action === 'edit') {
        // 编辑学习成果
        $id = intval($_POST['id']);
        $title = trim($_POST['title']);
        $category = trim($_POST['category']);
        $student_name = trim($_POST['student_name']);
        $description = trim($_POST['description']);
        $image_url = trim($_POST['image_url']);
        $achievement_date = $_POST['achievement_date'];
        $sort_order = intval($_POST['sort_order']);
        $status = $_POST['status'];
        
        $stmt = $db->prepare("UPDATE achievements SET title=?, category=?, student_name=?, description=?, image_url=?, achievement_date=?, sort_order=?, status=? WHERE id=?");
        if ($stmt->execute([$title, $category, $student_name, $description, $image_url, $achievement_date, $sort_order, $status, $id])) {
            $_SESSION['message'] = '学习成果更新成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '更新失败！';
            $_SESSION['message_type'] = 'danger';
        }
    } elseif ($action === 'delete') {
        // 删除学习成果
        $id = intval($_POST['id']);
        $stmt = $db->prepare("DELETE FROM achievements WHERE id=?");
        if ($stmt->execute([$id])) {
            $_SESSION['message'] = '学习成果删除成功！';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '删除失败！';
            $_SESSION['message_type'] = 'danger';
        }
    }
    
    header('Location: achievements.php');
    exit;
}

// 获取学习成果列表
$achievements = $db->query("SELECT * FROM achievements ORDER BY sort_order ASC, achievement_date DESC")->fetchAll();

// 获取编辑的学习成果数据
$edit_achievement = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $db->prepare("SELECT * FROM achievements WHERE id=?");
    $stmt->execute([$id]);
    $edit_achievement = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学习成果管理 - 艾瑞斯教育</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">学习成果管理</h3>
                <a href="achievements.php?action=add" class="btn btn-primary">
                    <i class="fas fa-plus"></i> 添加学习成果
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

        <?php if (isset($_GET['action']) && $_GET['action'] === 'add' || $edit_achievement): ?>
            <!-- 添加/编辑表单 -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><?php echo $edit_achievement ? '编辑学习成果' : '添加学习成果'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="<?php echo $edit_achievement ? 'edit' : 'add'; ?>">
                        <?php if ($edit_achievement): ?>
                            <input type="hidden" name="id" value="<?php echo $edit_achievement['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">成果标题</label>
                            <input type="text" class="form-control" name="title" 
                                   value="<?php echo $edit_achievement ? $edit_achievement['title'] : ''; ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">类别</label>
                                    <select class="form-control" name="category" required>
                                        <option value="">请选择</option>
                                        <option value="艺术作品" <?php echo ($edit_achievement && $edit_achievement['category'] === '艺术作品') ? 'selected' : ''; ?>>艺术作品</option>
                                        <option value="科学实验" <?php echo ($edit_achievement && $edit_achievement['category'] === '科学实验') ? 'selected' : ''; ?>>科学实验</option>
                                        <option value="竞赛获奖" <?php echo ($edit_achievement && $edit_achievement['category'] === '竞赛获奖') ? 'selected' : ''; ?>>竞赛获奖</option>
                                        <option value="考试成绩" <?php echo ($edit_achievement && $edit_achievement['category'] === '考试成绩') ? 'selected' : ''; ?>>考试成绩</option>
                                        <option value="其他" <?php echo ($edit_achievement && $edit_achievement['category'] === '其他') ? 'selected' : ''; ?>>其他</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">学生姓名</label>
                                    <input type="text" class="form-control" name="student_name" 
                                           value="<?php echo $edit_achievement ? $edit_achievement['student_name'] : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">成果描述</label>
                            <textarea class="form-control" name="description" rows="3" required><?php echo $edit_achievement ? $edit_achievement['description'] : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">图片URL</label>
                                    <input type="text" class="form-control" name="image_url" 
                                           value="<?php echo $edit_achievement ? $edit_achievement['image_url'] : ''; ?>">
                                    <?php if ($edit_achievement && $edit_achievement['image_url']): ?>
                                        <img src="<?php echo $edit_achievement['image_url']; ?>" class="preview-image" alt="预览">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">成果日期</label>
                                    <input type="date" class="form-control" name="achievement_date" 
                                           value="<?php echo $edit_achievement ? $edit_achievement['achievement_date'] : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">排序</label>
                            <input type="number" class="form-control" name="sort_order" 
                                   value="<?php echo $edit_achievement ? $edit_achievement['sort_order'] : '0'; ?>" required>
                        </div>
                        
                        <?php if ($edit_achievement): ?>
                            <div class="mb-3">
                                <label class="form-label">状态</label>
                                <select class="form-control" name="status">
                                    <option value="active" <?php echo $edit_achievement['status'] === 'active' ? 'selected' : ''; ?>>激活</option>
                                    <option value="inactive" <?php echo $edit_achievement['status'] === 'inactive' ? 'selected' : ''; ?>>禁用</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">保存</button>
                            <a href="achievements.php" class="btn btn-secondary">取消</a>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <!-- 学习成果列表 -->
            <div class="card">
                <div class="card-body">
                    <?php if (empty($achievements)): ?>
                        <p class="text-muted text-center">暂无学习成果数据</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>标题</th>
                                        <th>类别</th>
                                        <th>学生</th>
                                        <th>日期</th>
                                        <th>排序</th>
                                        <th>状态</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($achievements as $achievement): ?>
                                        <tr>
                                            <td><?php echo $achievement['title']; ?></td>
                                            <td><?php echo $achievement['category']; ?></td>
                                            <td><?php echo $achievement['student_name']; ?></td>
                                            <td><?php echo date('Y-m-d', strtotime($achievement['achievement_date'])); ?></td>
                                            <td><?php echo $achievement['sort_order']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $achievement['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                    <?php echo $achievement['status'] === 'active' ? '激活' : '禁用'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="achievements.php?edit=<?php echo $achievement['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" style="display: inline-block;" onsubmit="return confirm('确定删除这个学习成果吗？')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $achievement['id']; ?>">
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