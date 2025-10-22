-- 艾瑞斯教育网站数据库初始化脚本

CREATE DATABASE IF NOT EXISTS iris_education CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE iris_education;

-- 轮播图表
CREATE TABLE IF NOT EXISTS carousel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(500) NOT NULL,
    link_url VARCHAR(500),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 教育特色表
CREATE TABLE IF NOT EXISTS features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    icon VARCHAR(100),
    image_url VARCHAR(500),
    sort_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 教师团队表
CREATE TABLE IF NOT EXISTS teachers (
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
);

-- 学习成果表
CREATE TABLE IF NOT EXISTS achievements (
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
);

-- 活动展示表
CREATE TABLE IF NOT EXISTS activities (
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
);

-- 管理员表
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role ENUM('super', 'admin', 'editor') DEFAULT 'editor',
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 插入默认管理员账号 (密码: admin123)
INSERT INTO admins (username, password, email, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@iris.edu', 'super');

-- 插入默认轮播图数据
INSERT INTO carousel (title, description, image_url, link_url, sort_order) VALUES 
('现代化教室环境', '配备先进教学设备的现代化教室', 'resources/hero-classroom.png', '#', 1),
('丰富的教学活动', '多样化的教学活动激发学习兴趣', 'resources/activities-montage.png', '#', 2),
('专业师资团队', '经验丰富的专业教师团队', 'resources/teachers-group.png', '#', 3);

-- 插入默认教育特色数据
INSERT INTO features (title, description, icon, sort_order) VALUES 
('个性化教学', '根据每个学生的特点制定个性化教学方案，因材施教', 'fas fa-user-graduate', 1),
('国际化课程', '采用国际先进教育理念，融合中西教育精华', 'fas fa-globe', 2),
('专业师资', '拥有海内外名校毕业的专业教师团队', 'fas fa-chalkboard-teacher', 3),
('丰富活动', '定期举办各类文化活动，拓展学生视野', 'fas fa-calendar-alt', 4);

-- 插入默认教师数据
INSERT INTO teachers (name, subject, experience, description, certificates, specialties) VALUES 
('张老师', '英语', 8, '拥有8年英语教学经验，擅长少儿英语启蒙教育', 'TESOL, TEFL', '少儿英语, 口语训练'),
('李老师', '数学', 10, '10年数学教学经验，培养多名数学竞赛获奖学生', '教师资格证, 奥数教练证', '奥数培训, 逻辑思维'),
('王老师', '科学', 6, '科学教育专家，擅长实验教学和科学探究', '科学教育硕士', '实验教学, 科学探究');

-- 插入默认学习成果数据
INSERT INTO achievements (title, category, student_name, description, achievement_date) VALUES 
('英语演讲比赛一等奖', '竞赛获奖', '小明', '在全市英语演讲比赛中获得一等奖，展现出色的英语表达能力', '2024-03-15'),
('数学奥赛省级二等奖', '竞赛获奖', '小红', '在省级数学奥林匹克竞赛中获得二等奖，数学思维能力突出', '2024-02-20');

-- 插入默认活动数据
INSERT INTO activities (title, category, description, location, start_date, end_date) VALUES 
('春季英语角活动', '节日庆典', '春季英语角活动，让学生在轻松愉快的氛围中练习英语口语', '校园操场', '2024-04-10', '2024-04-10'),
('科学实验展示日', '艺术展演', '学生展示自己设计的科学实验，培养科学探究能力', '科学实验室', '2024-03-25', '2024-03-25');