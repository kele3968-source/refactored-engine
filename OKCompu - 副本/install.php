<?php
/**
 * 数据库安装脚本
 * 访问此页面自动创建数据库表
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 数据库配置 - 请根据你的实际情况修改
$config = [
    'host' => 'localhost',
    'username' => 'iris1_dvdv_top',
    'password' => 'iris1_dvdv_top', // 请修改为你的实际密码
    'dbname' => 'iris1_dvdv_top'
];

// 创建数据库连接
function connectDB($config) {
    try {
        $dsn = "mysql:host={$config['host']}";
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("数据库连接失败: " . $e->getMessage());
    }
}

// 创建数据库
function createDatabase($pdo, $dbname) {
    try {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$dbname`");
        return true;
    } catch (PDOException $e) {
        die("创建数据库失败: " . $e->getMessage());
    }
}

// 创建数据表
function createTables($pdo) {
    $sql = [
        // 管理员表
        "CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100),
            role ENUM('super', 'admin', 'editor') DEFAULT 'editor',
            status ENUM('active', 'inactive') DEFAULT 'active',
            last_login TIMESTAMP NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        // 轮播图表
        "CREATE TABLE IF NOT EXISTS carousel (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image_url VARCHAR(500) NOT NULL,
            link_url VARCHAR(500),
            sort_order INT DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // 教育特色表
        "CREATE TABLE IF NOT EXISTS features (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            icon VARCHAR(100),
            image_url VARCHAR(500),
            sort_order INT DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // 教师团队表
        "CREATE TABLE IF NOT EXISTS teachers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            subject VARCHAR(100) NOT NULL,
            experience INT DEFAULT 0,
            description TEXT,
            certificates TEXT,
            avatar_url VARCHAR(500),
            specialties TEXT,
            sort_order INT DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // 学习成果表
        "CREATE TABLE IF NOT EXISTS achievements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            category VARCHAR(100) NOT NULL,
            student_name VARCHAR(100) NOT NULL,
            description TEXT,
            image_url VARCHAR(500),
            achievement_date DATE,
            sort_order INT DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        // 活动展示表
        "CREATE TABLE IF NOT EXISTS activities (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            category VARCHAR(100) NOT NULL,
            description TEXT,
            image_url VARCHAR(500),
            location VARCHAR(255),
            start_date DATE,
            end_date DATE,
            sort_order INT DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($sql as $query) {
        try {
            $pdo->exec($query);
        } catch (PDOException $e) {
            die("创建表失败: " . $e->getMessage());
        }
    }
}

// 插入默认数据
function insertDefaultData($pdo) {
    // 插入默认管理员账号 (密码: admin123)
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    $admin_sql = "INSERT IGNORE INTO admins (username, password, email, role) VALUES ('admin', ?, 'admin@iris.edu', 'super')";
    
    // 默认轮播图数据
    $carousel_sql = "INSERT IGNORE INTO carousel (title, description, image_url, link_url, sort_order) VALUES 
        ('现代化教室环境', '配备先进教学设备的现代化教室', 'resources/hero-classroom.png', '#', 1),
        ('丰富的教学活动', '多样化的教学活动激发学习兴趣', 'resources/activities-montage.png', '#', 2),
        ('专业师资团队', '经验丰富的专业教师团队', 'resources/teachers-group.png', '#', 3)";
    
    // 默认教育特色数据
    $features_sql = "INSERT IGNORE INTO features (title, description, icon, sort_order) VALUES 
        ('个性化教学', '根据每个学生的特点制定个性化教学方案，因材施教', 'fas fa-user-graduate', 1),
        ('国际化课程', '采用国际先进教育理念，融合中西教育精华', 'fas fa-globe', 2),
        ('专业师资', '拥有海内外名校毕业的专业教师团队', 'fas fa-chalkboard-teacher', 3),
        ('丰富活动', '定期举办各类文化活动，拓展学生视野', 'fas fa-calendar-alt', 4)";
    
    // 默认教师数据
    $teachers_sql = "INSERT IGNORE INTO teachers (name, subject, experience, description, certificates, specialties) VALUES 
        ('张老师', '英语', 8, '拥有8年英语教学经验，擅长少儿英语启蒙教育', 'TESOL, TEFL', '少儿英语, 口语训练'),
        ('李老师', '数学', 10, '10年数学教学经验，培养多名数学竞赛获奖学生', '教师资格证, 奥数教练证', '奥数培训, 逻辑思维'),
        ('王老师', '科学', 6, '科学教育专家，擅长实验教学和科学探究', '科学教育硕士', '实验教学, 科学探究')";
    
    try {
        // 插入管理员
        $stmt = $pdo->prepare($admin_sql);
        $stmt->execute([$password_hash]);
        
        // 插入其他数据
        $pdo->exec($carousel_sql);
        $pdo->exec($features_sql);
        $pdo->exec($teachers_sql);
        
        return true;
    } catch (PDOException $e) {
        die("插入默认数据失败: " . $e->getMessage());
    }
}

// 主安装流程
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = connectDB($config);
    createDatabase($pdo, $config['dbname']);
    createTables($pdo);
    insertDefaultData($pdo);
    
    echo "<div class='alert alert-success'>数据库安装成功！</div>";
    echo "<p>默认管理员账号：admin / 密码：admin123</p>";
    echo "<p><a href='admin/login.php'>点击这里登录后台</a></p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>数据库安装 - 艾瑞斯教育</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">数据库安装</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">点击下面的按钮自动创建数据库表结构</p>
                        
                        <div class="alert alert-warning">
                            <strong>重要提示：</strong>
                            <ul>
                                <li>请确保数据库用户有创建表的权限</li>
                                <li>安装前请备份现有数据</li>
                                <li>安装完成后请删除此文件以保证安全</li>
                            </ul>
                        </div>
                        
                        <form method="POST">
                            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('确定要安装数据库吗？')">
                                开始安装数据库
                            </button>
                        </form>
                        
                        <hr>
                        
                        <h5>数据库配置信息：</h5>
                        <table class="table table-sm">
                            <tr><td>主机</td><td><?php echo $config['host']; ?></td></tr>
                            <tr><td>数据库名</td><td><?php echo $config['dbname']; ?></td></tr>
                            <tr><td>用户名</td><td><?php echo $config['username']; ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>