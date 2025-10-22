<?php
session_start();
require_once '../config/database.php';

// 检查登录状态
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$db = Database::getInstance()->getConnection();

// 获取统计数据
$stats = [
    'carousel_count' => $db->query("SELECT COUNT(*) FROM carousel WHERE status='active'")->fetchColumn(),
    'teachers_count' => $db->query("SELECT COUNT(*) FROM teachers WHERE status='active'")->fetchColumn(),
    'achievements_count' => $db->query("SELECT COUNT(*) FROM achievements WHERE status='active'")->fetchColumn(),
    'activities_count' => $db->query("SELECT COUNT(*) FROM activities WHERE status='active'")->fetchColumn(),
    'features_count' => $db->query("SELECT COUNT(*) FROM features WHERE status='active'")->fetchColumn()
];

// 获取最近更新
$recent_updates = [];
$tables = ['carousel', 'teachers', 'achievements', 'activities', 'features'];
foreach ($tables as $table) {
    $stmt = $db->prepare("SELECT *, ? as type FROM $table ORDER BY updated_at DESC LIMIT 2");
    $stmt->execute([$table]);
    $recent_updates = array_merge($recent_updates, $stmt->fetchAll());
}

// 按更新时间排序
usort($recent_updates, function($a, $b) {
    return strtotime($b['updated_at']) - strtotime($a['updated_at']);
});

$recent_updates = array_slice($recent_updates, 0, 5);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理 - 艾瑞斯教育</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #2C3E50;
            --accent-color: #27AE60;
            --light-gray: #F8F9FA;
        }

        body {
            font-family: 'Noto Sans SC', sans-serif;
            background-color: #f5f7fa;
        }

        .sidebar {
            background: var(--secondary-color);
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 15px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: var(--primary-color);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 15px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 20px 25px;
            border-radius: 15px 15px 0 0 !important;
        }

        .card-title {
            color: var(--secondary-color);
            font-weight: 600;
            margin: 0;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: #e55a2b;
        }

        .stats-card {
            text-align: center;
            padding: 30px 20px;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .stats-label {
            color: #666;
            font-size: 0.9rem;
        }

        .recent-item {
            border-left: 3px solid var(--primary-color);
            padding-left: 15px;
            margin-bottom: 15px;
        }

        .recent-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .recent-meta {
            color: #666;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <!-- 侧边栏 -->
    <div class="sidebar">
        <div class="p-4">
            <h4 class="mb-4">
                <i class="fas fa-graduation-cap"></i>
                艾瑞斯教育后台
            </h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        <i class="fas fa-tachometer-alt"></i>
                        仪表盘
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="carousel.php">
                        <i class="fas fa-images"></i>
                        轮播图管理
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="features.php">
                        <i class="fas fa-star"></i>
                        教育特色
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="teachers.php">
                        <i class="fas fa-chalkboard-teacher"></i>
                        教师团队
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="achievements.php">
                        <i class="fas fa-trophy"></i>
                        学习成果
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="activities.php">
                        <i class="fas fa-calendar-alt"></i>
                        活动管理
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        退出登录
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 主内容区 -->
    <div class="main-content">
        <!-- 头部 -->
        <div class="header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">后台管理系统</h3>
                <div class="d-flex align-items-center">
                    <span class="me-3">欢迎，<?php echo $_SESSION['admin_username']; ?></span>
                    <a href="logout.php" class="btn btn-outline-secondary">
                        <i class="fas fa-sign-out-alt"></i> 退出
                    </a>
                </div>
            </div>
        </div>

        <!-- 统计卡片 -->
        <div class="row">
            <div class="col-md-2">
                <div class="card stats-card">
                    <div class="stats-number"><?php echo $stats['carousel_count']; ?></div>
                    <div class="stats-label">轮播图</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card">
                    <div class="stats-number"><?php echo $stats['features_count']; ?></div>
                    <div class="stats-label">教育特色</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card">
                    <div class="stats-number"><?php echo $stats['teachers_count']; ?></div>
                    <div class="stats-label">教师</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card">
                    <div class="stats-number"><?php echo $stats['achievements_count']; ?></div>
                    <div class="stats-label">学习成果</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card">
                    <div class="stats-number"><?php echo $stats['activities_count']; ?></div>
                    <div class="stats-label">活动</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card stats-card">
                    <div class="stats-number"><?php echo rand(500, 2000); ?></div>
                    <div class="stats-label">总访问量</div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">最近更新</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recent_updates)): ?>
                            <p class="text-muted">暂无更新记录</p>
                        <?php else: ?>
                            <?php foreach ($recent_updates as $item): ?>
                                <div class="recent-item">
                                    <div class="recent-title">
                                        <i class="fas fa-<?php echo getItemIcon($item['type']); ?> text-primary"></i>
                                        <?php echo $item['title'] ?? $item['name'] ?? '未命名'; ?>
                                    </div>
                                    <div class="recent-meta">
                                        <?php echo getItemTypeText($item['type']); ?> • 
                                        <?php echo date('Y-m-d H:i', strtotime($item['updated_at'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">快捷操作</h5>
                    </div>
                    <div class="card-body">
                        <a href="carousel.php?action=add" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-plus"></i> 添加轮播图
                        </a>
                        <a href="features.php?action=add" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-plus"></i> 添加教育特色
                        </a>
                        <a href="teachers.php?action=add" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-plus"></i> 添加教师
                        </a>
                        <a href="achievements.php?action=add" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-plus"></i> 添加学习成果
                        </a>
                        <a href="activities.php?action=add" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-plus"></i> 添加活动
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// 辅助函数
function getItemIcon($type) {
    switch($type) {
        case 'carousel': return 'images';
        case 'features': return 'star';
        case 'teachers': return 'chalkboard-teacher';
        case 'achievements': return 'trophy';
        case 'activities': return 'calendar-alt';
        default: return 'file';
    }
}

function getItemTypeText($type) {
    switch($type) {
        case 'carousel': return '轮播图';
        case 'features': return '教育特色';
        case 'teachers': return '教师';
        case 'achievements': return '学习成果';
        case 'activities': return '活动';
        default: return '项目';
    }
}
?>