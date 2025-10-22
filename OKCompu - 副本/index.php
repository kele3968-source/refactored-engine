<?php
require_once 'config/database.php';

$db = Database::getInstance()->getConnection();

// 获取轮播图数据
$carousels = $db->query("SELECT * FROM carousel WHERE status='active' ORDER BY sort_order ASC")->fetchAll();

// 获取教育特色数据
$features = $db->query("SELECT * FROM features WHERE status='active' ORDER BY sort_order ASC LIMIT 4")->fetchAll();

// 获取教师数据
$teachers = $db->query("SELECT * FROM teachers WHERE status='active' ORDER BY sort_order ASC LIMIT 6")->fetchAll();

// 获取学习成果数据
$achievements = $db->query("SELECT * FROM achievements WHERE status='active' ORDER BY achievement_date DESC LIMIT 6")->fetchAll();

// 获取活动数据
$activities = $db->query("SELECT * FROM activities WHERE status='active' ORDER BY start_date DESC LIMIT 3")->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>艾瑞斯青少儿国际教育中心 - 专业青少儿教育培训机构</title>
    <meta name="description" content="艾瑞斯青少儿国际教育中心致力于为3-16岁孩子提供优质的国际化教育培训，包括英语、数学、科学、艺术等课程，拥有专业师资团队和丰富的教学成果。">
    
    <!-- External Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@300;400;500;700&family=Noto+Serif+SC:wght@400;500;700&family=ZCOOL+KuaiLe&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #2C3E50;
            --accent-color: #27AE60;
            --light-gray: #F8F9FA;
            --dark-gray: #343A40;
            --warm-orange: #FF8A50;
            --deep-blue: #34495E;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Sans SC', sans-serif;
            line-height: 1.6;
            color: var(--dark-gray);
            overflow-x: hidden;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-family: 'ZCOOL KuaiLe', cursive;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            font-weight: 700;
        }

        .nav-link {
            color: var(--dark-gray) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .hero-section {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            background: rgba(0, 0, 0, 0.4);
            padding: 0 20px;
        }

        .hero-title {
            font-family: 'Noto Serif SC', serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .hero-description {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.8;
            max-width: 600px;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0 10px;
        }

        .btn-primary-custom:hover {
            background: var(--warm-orange);
            transform: translateY(-2px);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid white;
            color: white;
            background: transparent;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0 10px;
        }

        .btn-outline-custom:hover {
            background: white;
            color: var(--primary-color);
        }

        .section-padding {
            padding: 80px 0;
        }

        .section-title {
            font-family: 'Noto Serif SC', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 1rem;
            text-align: center;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #666;
            text-align: center;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            color: white;
            font-size: 2rem;
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        .teacher-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .teacher-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .teacher-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .teacher-info {
            padding: 25px;
        }

        .teacher-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }

        .teacher-subject {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 10px;
        }

        .teacher-experience {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
        }

        .achievement-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .achievement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .achievement-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .achievement-info {
            padding: 20px;
        }

        .achievement-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }

        .achievement-description {
            color: #666;
            line-height: 1.6;
        }

        .activity-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .activity-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .activity-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        .activity-meta {
            color: #666;
            margin-bottom: 15px;
        }

        .cta-section {
            background: linear-gradient(135deg, var(--primary-color), var(--warm-orange));
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .cta-title {
            font-family: 'Noto Serif SC', serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer {
            background: var(--secondary-color);
            color: white;
            padding: 60px 0 30px;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .cta-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">艾瑞斯教育</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">首页</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="teachers.php">师资力量</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="achievements.php">学习成果</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">关于我们</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking.php">预约试听</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="activities.php">活动展示</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Carousel -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="splide" id="hero-carousel">
                <div class="splide__track">
                    <ul class="splide__list">
                        <?php foreach ($carousels as $carousel): ?>
                            <li class="splide__slide">
                                <img src="<?php echo $carousel['image_url']; ?>" alt="<?php echo $carousel['title']; ?>">
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="hero-content">
            <h1 class="hero-title">艾瑞斯青少儿国际教育中心</h1>
            <p class="hero-subtitle">培养未来国际化人才，成就每一个孩子的独特潜能</p>
            <p class="hero-description">专业师资团队 · 个性化教学方案 · 国际化课程体系 · 卓越教学成果</p>
            <div class="cta-buttons">
                <a href="booking.php" class="btn btn-primary-custom">立即预约试听</a>
                <a href="about.php" class="btn btn-outline-custom">了解更多</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section-padding">
        <div class="container">
            <h2 class="section-title">我们的教育特色</h2>
            <p class="section-subtitle">融合国际先进教育理念，为每个孩子提供最适合的成长路径</p>
            
            <div class="row">
                <?php foreach ($features as $feature): ?>
                    <div class="col-md-3 mb-4">
                        <div class="feature-card">
                            <?php if ($feature['icon']): ?>
                                <div class="feature-icon">
                                    <i class="<?php echo $feature['icon']; ?>"></i>
                                </div>
                            <?php endif; ?>
                            <h4 class="feature-title"><?php echo $feature['title']; ?></h4>
                            <p><?php echo $feature['description']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Teachers Section -->
    <section class="section-padding" style="background: var(--light-gray);">
        <div class="container">
            <h2 class="section-title">专业师资团队</h2>
            <p class="section-subtitle">拥有经验丰富的专业教师团队，平均教龄8年以上</p>
            
            <div class="row">
                <?php foreach ($teachers as $teacher): ?>
                    <div class="col-md-4 mb-4">
                        <div class="teacher-card">
                            <img src="<?php echo $teacher['avatar_url'] ?: 'resources/teachers-group.png'; ?>" alt="<?php echo $teacher['name']; ?>" class="teacher-image">
                            <div class="teacher-info">
                                <h4 class="teacher-name"><?php echo $teacher['name']; ?></h4>
                                <p class="teacher-subject"><?php echo $teacher['subject']; ?>教师</p>
                                <p class="teacher-experience"><?php echo $teacher['experience']; ?>年教学经验</p>
                                <p><?php echo $teacher['description']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="teachers.php" class="btn btn-primary-custom">查看全部教师</a>
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section class="section-padding">
        <div class="container">
            <h2 class="section-title">优秀学习成果</h2>
            <p class="section-subtitle">见证每个孩子的成长足迹，展示卓越的教学成果</p>
            
            <div class="row">
                <?php foreach ($achievements as $achievement): ?>
                    <div class="col-md-4 mb-4">
                        <div class="achievement-card">
                            <img src="<?php echo $achievement['image_url'] ?: 'resources/achievements-showcase.png'; ?>" alt="<?php echo $achievement['title']; ?>" class="achievement-image">
                            <div class="achievement-info">
                                <h4 class="achievement-title"><?php echo $achievement['title']; ?></h4>
                                <p class="achievement-description"><?php echo $achievement['description']; ?></p>
                                <small class="text-muted">学生：<?php echo $achievement['student_name']; ?> | 日期：<?php echo date('Y-m-d', strtotime($achievement['achievement_date'])); ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Activities Section -->
    <section class="section-padding" style="background: var(--light-gray);">
        <div class="container">
            <h2 class="section-title">近期活动</h2>
            <p class="section-subtitle">丰富多彩的校园活动，让学习更加生动有趣</p>
            
            <div class="row">
                <?php foreach ($activities as $activity): ?>
                    <div class="col-md-4 mb-4">
                        <div class="activity-card">
                            <h4 class="activity-title"><?php echo $activity['title']; ?></h4>
                            <div class="activity-meta">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                <?php echo date('Y-m-d', strtotime($activity['start_date'])); ?>
                                <i class="fas fa-map-marker-alt text-primary ms-3 me-2"></i>
                                <?php echo $activity['location']; ?>
                            </div>
                            <p><?php echo $activity['description']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">开启孩子的精彩学习之旅</h2>
            <p class="hero-description">现在就预约试听，体验我们的专业教学服务</p>
            <a href="booking.php" class="btn btn-outline-custom">立即预约试听</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>艾瑞斯教育</h5>
                    <p>致力于为3-16岁孩子提供优质的国际化教育培训，培养未来国际化人才。</p>
                </div>
                <div class="col-md-4">
                    <h5>联系我们</h5>
                    <p>电话：400-123-4567</p>
                    <p>邮箱：info@iris.edu</p>
                    <p>地址：北京市朝阳区教育路123号</p>
                </div>
                <div class="col-md-4">
                    <h5>快速链接</h5>
                    <p><a href="teachers.php" class="text-light">师资力量</a></p>
                    <p><a href="achievements.php" class="text-light">学习成果</a></p>
                    <p><a href="activities.php" class="text-light">活动展示</a></p>
                </div>
            </div>
            <div class="text-center mt-4">
                <p>&copy; 2024 艾瑞斯青少儿国际教育中心. 保留所有权利.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script>
        // 初始化轮播图
        document.addEventListener('DOMContentLoaded', function() {
            new Splide('#hero-carousel', {
                type: 'fade',
                rewind: true,
                autoplay: true,
                interval: 3000,
                pagination: false
            }).mount();
        });
    </script>
</body>
</html>