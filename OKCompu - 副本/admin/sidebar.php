<!-- 侧边栏组件 -->
<div class="sidebar">
    <div class="p-4">
        <h4 class="mb-4">
            <i class="fas fa-graduation-cap"></i>
            艾瑞斯教育后台
        </h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>" href="index.php">
                    <i class="fas fa-tachometer-alt"></i>
                    仪表盘
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'carousel.php' ? 'active' : ''; ?>" href="carousel.php">
                    <i class="fas fa-images"></i>
                    轮播图管理
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'features.php' ? 'active' : ''; ?>" href="features.php">
                    <i class="fas fa-star"></i>
                    教育特色
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'teachers.php' ? 'active' : ''; ?>" href="teachers.php">
                    <i class="fas fa-chalkboard-teacher"></i>
                    教师团队
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'achievements.php' ? 'active' : ''; ?>" href="achievements.php">
                    <i class="fas fa-trophy"></i>
                    学习成果
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'activities.php' ? 'active' : ''; ?>" href="activities.php">
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

<style>
.sidebar {
    background: #2C3E50;
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
    border-left-color: #FF6B35;
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

.btn-primary {
    background: #FF6B35;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
}

.btn-primary:hover {
    background: #e55a2b;
}
</style>