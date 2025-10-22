# 艾瑞斯青少儿国际教育中心 - PbootCMS配置说明

## 概述
本文档详细说明了如何将艾瑞斯青少儿国际教育中心网站模板配置到PbootCMS系统中，包括数据库设置、标签使用和后台管理等内容。

## 环境要求
- PHP版本：7.0及以上
- 数据库：MySQL 5.6及以上
- Web服务器：Nginx/Apache
- 操作系统：Linux/Windows

## 安装步骤

### 1. 系统安装
1. 下载PbootCMS最新版本
2. 解压到网站根目录
3. 访问域名进行安装配置
4. 设置数据库连接信息
5. 完成安装

### 2. 模板文件配置
将以下文件复制到PbootCMS对应目录：

```
/ 网站根目录
├── template/ 模板目录
│   └── default/ 默认模板
│       ├── index.html (首页)
│       ├── teachers.html (师资页面)
│       ├── achievements.html (成果页面)
│       ├── about.html (关于页面)
│       ├── booking.html (预约页面)
│       └── activities.html (活动页面)
├── static/ 静态资源
│   ├── css/
│   ├── js/
│   └── images/
└── upload/ 上传文件
    └── images/
        ├── hero-classroom.png
        ├── teachers-group.png
        ├── achievements-showcase.png
        ├── facility-exterior.png
        └── activities-montage.png
```

## 数据库结构

### 核心表结构

#### 1. 师资表 (ay_teachers)
```sql
CREATE TABLE `ay_teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '教师姓名',
  `subject` varchar(100) NOT NULL COMMENT '教学科目',
  `experience` varchar(50) NOT NULL COMMENT '从业年限',
  `students` int(11) NOT NULL COMMENT '教授学员数',
  `description` text COMMENT '教师简介',
  `specialties` varchar(500) COMMENT '擅长领域',
  `certificates` varchar(500) COMMENT '证书资质',
  `satisfaction` int(11) COMMENT '学员满意度',
  `retention` int(11) COMMENT '续课率',
  `photo` varchar(255) COMMENT '教师照片',
  `sorting` int(11) DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) DEFAULT 1 COMMENT '状态',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### 2. 学习成果表 (ay_achievements)
```sql
CREATE TABLE `ay_achievements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL COMMENT '成果标题',
  `category` varchar(50) NOT NULL COMMENT '成果类别',
  `description` text COMMENT '成果描述',
  `author` varchar(100) COMMENT '作者/学员',
  `date` date COMMENT '完成日期',
  `image` varchar(255) COMMENT '成果图片',
  `sorting` int(11) DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) DEFAULT 1 COMMENT '状态',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### 3. 活动表 (ay_activities)
```sql
CREATE TABLE `ay_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL COMMENT '活动标题',
  `category` varchar(50) NOT NULL COMMENT '活动类别',
  `description` text COMMENT '活动描述',
  `date` date COMMENT '活动日期',
  `participants` varchar(100) COMMENT '参与人数',
  `location` varchar(100) COMMENT '活动地点',
  `image` varchar(255) COMMENT '活动图片',
  `gallery` text COMMENT '活动相册',
  `sorting` int(11) DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) DEFAULT 1 COMMENT '状态',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### 4. 预约表 (ay_bookings)
```sql
CREATE TABLE `ay_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_name` varchar(100) NOT NULL COMMENT '家长姓名',
  `phone` varchar(20) NOT NULL COMMENT '联系电话',
  `child_name` varchar(100) NOT NULL COMMENT '孩子姓名',
  `child_age` varchar(20) NOT NULL COMMENT '孩子年龄',
  `course_type` varchar(50) NOT NULL COMMENT '课程类型',
  `email` varchar(100) COMMENT '邮箱地址',
  `booking_date` date COMMENT '预约日期',
  `booking_time` varchar(20) COMMENT '预约时间',
  `notes` text COMMENT '备注信息',
  `status` tinyint(1) DEFAULT 0 COMMENT '预约状态 0:待确认 1:已确认 2:已完成',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## PbootCMS标签使用说明

### 1. 首页标签

#### 轮播图标签
```html
{pboot:slide gid=1 num=6}
<img src="[slide:src]" alt="[slide:title]">
{/pboot:slide}
```

#### 师资展示标签
```html
{pboot:sql sql="SELECT * FROM ay_teachers WHERE status=1 ORDER BY sorting ASC,id DESC LIMIT 3"}
<div class="teacher-card">
    <img src="[sql:photo]" alt="[sql:name]" class="teacher-image">
    <div class="teacher-info">
        <h3 class="teacher-name">[sql:name]</h3>
        <p class="teacher-subject">[sql:subject]</p>
        <p class="teacher-experience">从业年限：[sql:experience] | 已教授学员：[sql:students]+</p>
        <p class="teacher-description">[sql:description]</p>
        <div class="teacher-certificates">
            {pboot:sql sql="SELECT * FROM ay_teachers WHERE id=[sql:id]"}
            <span class="certificate-badge">[sql:certificates]</span>
            {/pboot:sql}
        </div>
    </div>
</div>
{/pboot:sql}
```

#### 学习成果标签
```html
{pboot:sql sql="SELECT * FROM ay_achievements WHERE status=1 ORDER BY sorting ASC,id DESC LIMIT 6"}
<div class="achievement-item">
    <img src="[sql:image]" alt="[sql:title]" class="achievement-image">
    <div class="achievement-info">
        <span class="achievement-category">[sql:category]</span>
        <h3 class="achievement-title">[sql:title]</h3>
        <p class="achievement-description">[sql:description]</p>
        <div class="achievement-meta">
            <span class="achievement-author">[sql:author]</span>
            <span class="achievement-date">[sql:date]</span>
        </div>
    </div>
</div>
{/pboot:sql}
```

### 2. 师资页面标签
```html
{pboot:sql sql="SELECT * FROM ay_teachers WHERE status=1 ORDER BY sorting ASC,id DESC"}
<div class="teacher-card" data-category="[sql:subject]">
    <!-- 教师信息展示 -->
</div>
{/pboot:sql}
```

### 3. 学习成果页面标签
```html
{pboot:sql sql="SELECT * FROM ay_achievements WHERE status=1 AND category='[category]' ORDER BY sorting ASC,id DESC"}
<div class="achievement-item" data-category="[sql:category]">
    <!-- 成果信息展示 -->
</div>
{/pboot:sql}
```

### 4. 活动页面标签
```html
{pboot:sql sql="SELECT * FROM ay_activities WHERE status=1 AND category='[category]' ORDER BY sorting ASC,id DESC"}
<div class="activity-card" data-category="[sql:category]">
    <!-- 活动信息展示 -->
</div>
{/pboot:sql}
```

## 后台管理配置

### 1. 站点配置
- 站点名称：艾瑞斯青少儿国际教育中心
- 站点关键词：青少儿教育,国际化教育,英语培训,数学思维,科学探索,艺术培养
- 站点描述：艾瑞斯青少儿国际教育中心致力于为3-16岁孩子提供优质的国际化教育培训

### 2. 栏目配置
```
首页
师资力量
学习成果
关于我们
预约试听
活动展示
```

### 3. 自定义字段配置
在PbootCMS后台添加以下自定义字段：

#### 师资自定义字段
- 教学科目 (subject)
- 从业年限 (experience)
- 教授学员数 (students)
- 擅长领域 (specialties)
- 证书资质 (certificates)
- 学员满意度 (satisfaction)
- 续课率 (retention)

#### 成果自定义字段
- 成果类别 (category)
- 作者学员 (author)
- 完成日期 (date)

#### 活动自定义字段
- 活动类别 (category)
- 活动日期 (date)
- 参与人数 (participants)
- 活动地点 (location)
- 活动相册 (gallery)

## 常用功能配置

### 1. 联系方式配置
在后台"系统设置"-"基本信息"中配置：
- 电话：400-888-8888
- 邮箱：info@aeris-edu.com
- 地址：北京市朝阳区教育园区88号

### 2. SEO配置
- 启用URL重写
- 设置伪静态规则
- 配置网站地图
- 设置robots.txt

### 3. 安全配置
- 设置后台登录验证码
- 配置IP黑名单
- 启用HTTPS
- 定期备份数据库

## 数据导入示例

### 师资数据导入
```sql
INSERT INTO `ay_teachers` (`name`, `subject`, `experience`, `students`, `description`, `specialties`, `certificates`, `satisfaction`, `retention`, `photo`) VALUES
('王梦琪', '英语教育专家', '8年', 500, '英国剑桥大学TESOL硕士，专注于少儿英语教育...', '少儿英语启蒙,口语训练,游戏化教学,剑桥英语', 'TESOL认证,剑桥认证,优秀教师', 98, 85, '/upload/images/teacher1.jpg'),
('李明华', '数学思维导师', '10年', 800, '北京大学数学系博士，奥林匹克数学竞赛金牌教练...', '数学思维,奥数竞赛,逻辑训练,解题技巧', '数学博士,奥赛教练,金牌教师', 96, 92, '/upload/images/teacher2.jpg');
```

### 成果数据导入
```sql
INSERT INTO `ay_achievements` (`title`, `category`, `description`, `author`, `date`, `image`) VALUES
('创意绘画作品集', '艺术作品', '5-8岁学员的创意绘画作品...', '小明同学，7岁', '2024-09-15', '/upload/images/artwork1.jpg'),
('数学竞赛金奖', '竞赛获奖', '我校学员在2024年全国少儿数学思维竞赛中获得金奖...', '小强同学，11岁', '2024-08-20', '/upload/images/competition1.jpg');
```

## 常见问题解决

### 1. 图片上传问题
- 检查upload目录权限
- 确认PHP GD库已安装
- 检查文件大小限制

### 2. 数据库连接问题
- 确认数据库配置信息正确
- 检查数据库用户权限
- 确认数据库服务正常运行

### 3. 页面显示问题
- 检查模板文件路径
- 确认标签使用正确
- 检查CSS/JS文件加载

## 维护和更新

### 1. 定期维护
- 每周备份数据库
- 每月更新系统补丁
- 定期检查服务器状态

### 2. 内容更新
- 定期更新师资信息
- 及时添加学习成果
- 发布最新活动信息

### 3. 性能优化
- 优化数据库查询
- 压缩图片文件
- 启用缓存功能

## 技术支持

如有技术问题，请联系：
- 技术QQ：123456789
- 技术邮箱：tech@aeris-edu.com
- 技术支持时间：工作日 9:00-18:00

## 更新日志

### v1.0.0 (2024-10-20)
- 初始版本发布
- 完成基础模板配置
- 添加师资管理功能
- 添加成果展示功能
- 添加活动管理功能
- 添加预约系统功能

---

**文档版本**：v1.0.0  
**更新时间**：2024年10月20日  
**适用系统**：PbootCMS v3.x