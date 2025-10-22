// 后台管理系统 JavaScript

// 数据存储
let teachers = [];
let achievements = [];
let activities = [];

// 初始化数据
function initData() {
    // 从localStorage加载数据，如果没有则使用默认数据
    const savedTeachers = localStorage.getItem('iris_teachers');
    const savedAchievements = localStorage.getItem('iris_achievements');
    const savedActivities = localStorage.getItem('iris_activities');
    
    if (savedTeachers) {
        teachers = JSON.parse(savedTeachers);
    } else {
        // 默认教师数据
        teachers = [
            {
                id: 1,
                name: "张老师",
                subject: "英语",
                experience: 8,
                certificates: "TESOL, TEFL",
                description: "拥有8年英语教学经验，擅长少儿英语启蒙教育",
                avatar: "resources/teachers-group.png",
                status: "active",
                createdAt: new Date().toISOString()
            },
            {
                id: 2,
                name: "李老师",
                subject: "数学",
                experience: 10,
                certificates: "教师资格证, 奥数教练证",
                description: "10年数学教学经验，培养多名数学竞赛获奖学生",
                avatar: "resources/teachers-group.png",
                status: "active",
                createdAt: new Date().toISOString()
            },
            {
                id: 3,
                name: "王老师",
                subject: "科学",
                experience: 6,
                certificates: "科学教育硕士",
                description: "科学教育专家，擅长实验教学和科学探究",
                avatar: "resources/teachers-group.png",
                status: "active",
                createdAt: new Date().toISOString()
            }
        ];
        saveTeachers();
    }
    
    if (savedAchievements) {
        achievements = JSON.parse(savedAchievements);
    } else {
        // 默认学习成果数据
        achievements = [
            {
                id: 1,
                title: "英语演讲比赛一等奖",
                category: "竞赛获奖",
                student: "小明",
                description: "在全市英语演讲比赛中获得一等奖，展现出色的英语表达能力",
                image: "resources/achievements-showcase.png",
                date: "2024-03-15",
                status: "active",
                createdAt: new Date().toISOString()
            },
            {
                id: 2,
                title: "数学奥赛省级二等奖",
                category: "竞赛获奖",
                student: "小红",
                description: "在省级数学奥林匹克竞赛中获得二等奖，数学思维能力突出",
                image: "resources/achievements-showcase.png",
                date: "2024-02-20",
                status: "active",
                createdAt: new Date().toISOString()
            }
        ];
        saveAchievements();
    }
    
    if (savedActivities) {
        activities = JSON.parse(savedActivities);
    } else {
        // 默认活动数据
        activities = [
            {
                id: 1,
                title: "春季英语角活动",
                category: "节日庆典",
                location: "校园操场",
                description: "春季英语角活动，让学生在轻松愉快的氛围中练习英语口语",
                image: "resources/activities-montage.png",
                startDate: "2024-04-10",
                endDate: "2024-04-10",
                status: "active",
                createdAt: new Date().toISOString()
            },
            {
                id: 2,
                title: "科学实验展示日",
                category: "艺术展演",
                location: "科学实验室",
                description: "学生展示自己设计的科学实验，培养科学探究能力",
                image: "resources/activities-montage.png",
                startDate: "2024-03-25",
                endDate: "2024-03-25",
                status: "active",
                createdAt: new Date().toISOString()
            }
        ];
        saveActivities();
    }
    
    updateStats();
    renderTeachers();
    renderAchievements();
    renderActivities();
    renderRecentUpdates();
}

// 保存数据到localStorage
function saveTeachers() {
    localStorage.setItem('iris_teachers', JSON.stringify(teachers));
}

function saveAchievements() {
    localStorage.setItem('iris_achievements', JSON.stringify(achievements));
}

function saveActivities() {
    localStorage.setItem('iris_activities', JSON.stringify(activities));
}

// 更新统计信息
function updateStats() {
    document.getElementById('teacherCount').textContent = teachers.length;
    document.getElementById('achievementCount').textContent = achievements.length;
    document.getElementById('activityCount').textContent = activities.length;
    document.getElementById('totalViews').textContent = 
        Math.floor(Math.random() * 1000) + 500; // 模拟访问量
}

// 渲染最近更新
function renderRecentUpdates() {
    const container = document.getElementById('recentUpdates');
    const allItems = [
        ...teachers.map(t => ({...t, type: 'teacher'})),
        ...achievements.map(a => ({...a, type: 'achievement'})),
        ...activities.map(a => ({...a, type: 'activity'}))
    ].sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt)).slice(0, 5);
    
    container.innerHTML = allItems.map(item => `
        <div class="d-flex align-items-center mb-3 p-3 border rounded">
            <div class="flex-shrink-0">
                <i class="fas fa-${getItemIcon(item.type)} text-primary fs-4"></i>
            </div>
            <div class="flex-grow-1 ms-3">
                <div class="fw-bold">${item.name || item.title}</div>
                <div class="text-muted small">
                    ${getItemTypeText(item.type)} • ${formatDate(item.createdAt)}
                </div>
            </div>
        </div>
    `).join('');
}

function getItemIcon(type) {
    switch(type) {
        case 'teacher': return 'chalkboard-teacher';
        case 'achievement': return 'trophy';
        case 'activity': return 'calendar-alt';
        default: return 'file';
    }
}

function getItemTypeText(type) {
    switch(type) {
        case 'teacher': return '教师';
        case 'achievement': return '学习成果';
        case 'activity': return '活动';
        default: return '项目';
    }
}

// 渲染教师表格
function renderTeachers() {
    const container = document.getElementById('teacherTable');
    container.innerHTML = teachers.map(teacher => `
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <img src="${teacher.avatar}" alt="${teacher.name}" class="rounded-circle me-3" width="40" height="40">
                    <span>${teacher.name}</span>
                </div>
            </td>
            <td>${teacher.subject}</td>
            <td>${teacher.experience}年</td>
            <td>${teacher.certificates}</td>
            <td>
                <span class="badge badge-success">${teacher.status === 'active' ? '活跃' : '停用'}</span>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="editTeacher(${teacher.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteTeacher(${teacher.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// 渲染学习成果表格
function renderAchievements() {
    const container = document.getElementById('achievementTable');
    container.innerHTML = achievements.map(achievement => `
        <tr>
            <td>${achievement.title}</td>
            <td>${achievement.category}</td>
            <td>${achievement.student}</td>
            <td>${formatDate(achievement.date)}</td>
            <td>
                <span class="badge badge-success">${achievement.status === 'active' ? '展示中' : '隐藏'}</span>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="editAchievement(${achievement.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteAchievement(${achievement.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// 渲染活动表格
function renderActivities() {
    const container = document.getElementById('activityTable');
    container.innerHTML = activities.map(activity => `
        <tr>
            <td>${activity.title}</td>
            <td>${activity.category}</td>
            <td>${formatDate(activity.startDate)}</td>
            <td>${activity.location}</td>
            <td>
                <span class="badge badge-success">${activity.status === 'active' ? '进行中' : '已结束'}</span>
            </td>
            <td>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="editActivity(${activity.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteActivity(${activity.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// 日期格式化
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('zh-CN');
}

// 导航切换
function switchTab(tabName) {
    // 隐藏所有内容
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    // 显示选中的内容
    document.getElementById(tabName).style.display = 'block';
    
    // 更新导航激活状态
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.classList.remove('active');
    });
    event.target.classList.add('active');
}

// 模态框显示函数
function showAddTeacherModal() {
    const modal = new bootstrap.Modal(document.getElementById('addTeacherModal'));
    document.getElementById('teacherForm').reset();
    modal.show();
}

function showAddAchievementModal() {
    const modal = new bootstrap.Modal(document.getElementById('addAchievementModal'));
    document.getElementById('achievementForm').reset();
    modal.show();
}

function showAddActivityModal() {
    const modal = new bootstrap.Modal(document.getElementById('addActivityModal'));
    document.getElementById('activityForm').reset();
    modal.show();
}

// 保存教师
function saveTeacher() {
    const form = document.getElementById('teacherForm');
    const formData = new FormData(form);
    
    const teacher = {
        id: teachers.length > 0 ? Math.max(...teachers.map(t => t.id)) + 1 : 1,
        name: formData.get('name'),
        subject: formData.get('subject'),
        experience: parseInt(formData.get('experience')),
        certificates: formData.get('certificates'),
        description: formData.get('description'),
        avatar: formData.get('avatar') || 'resources/teachers-group.png',
        status: 'active',
        createdAt: new Date().toISOString()
    };
    
    teachers.push(teacher);
    saveTeachers();
    renderTeachers();
    updateStats();
    renderRecentUpdates();
    
    bootstrap.Modal.getInstance(document.getElementById('addTeacherModal')).hide();
    showAlert('教师添加成功！', 'success');
}

// 保存学习成果
function saveAchievement() {
    const form = document.getElementById('achievementForm');
    const formData = new FormData(form);
    
    const achievement = {
        id: achievements.length > 0 ? Math.max(...achievements.map(a => a.id)) + 1 : 1,
        title: formData.get('title'),
        category: formData.get('category'),
        student: formData.get('student'),
        description: formData.get('description'),
        image: formData.get('image') || 'resources/achievements-showcase.png',
        date: formData.get('date'),
        status: 'active',
        createdAt: new Date().toISOString()
    };
    
    achievements.push(achievement);
    saveAchievements();
    renderAchievements();
    updateStats();
    renderRecentUpdates();
    
    bootstrap.Modal.getInstance(document.getElementById('addAchievementModal')).hide();
    showAlert('学习成果添加成功！', 'success');
}

// 保存活动
function saveActivity() {
    const form = document.getElementById('activityForm');
    const formData = new FormData(form);
    
    const activity = {
        id: activities.length > 0 ? Math.max(...activities.map(a => a.id)) + 1 : 1,
        title: formData.get('title'),
        category: formData.get('category'),
        location: formData.get('location'),
        description: formData.get('description'),
        image: formData.get('image') || 'resources/activities-montage.png',
        startDate: formData.get('startDate'),
        endDate: formData.get('endDate') || formData.get('startDate'),
        status: 'active',
        createdAt: new Date().toISOString()
    };
    
    activities.push(activity);
    saveActivities();
    renderActivities();
    updateStats();
    renderRecentUpdates();
    
    bootstrap.Modal.getInstance(document.getElementById('addActivityModal')).hide();
    showAlert('活动添加成功！', 'success');
}

// 删除功能
function deleteTeacher(id) {
    if (confirm('确定要删除这位教师吗？')) {
        teachers = teachers.filter(t => t.id !== id);
        saveTeachers();
        renderTeachers();
        updateStats();
        showAlert('教师删除成功！', 'success');
    }
}

function deleteAchievement(id) {
    if (confirm('确定要删除这个学习成果吗？')) {
        achievements = achievements.filter(a => a.id !== id);
        saveAchievements();
        renderAchievements();
        updateStats();
        showAlert('学习成果删除成功！', 'success');
    }
}

function deleteActivity(id) {
    if (confirm('确定要删除这个活动吗？')) {
        activities = activities.filter(a => a.id !== id);
        saveActivities();
        renderActivities();
        updateStats();
        showAlert('活动删除成功！', 'success');
    }
}

// 编辑功能（简化版，实际项目中需要更完整的实现）
function editTeacher(id) {
    const teacher = teachers.find(t => t.id === id);
    if (teacher) {
        // 填充表单数据
        document.querySelector('input[name="name"]').value = teacher.name;
        document.querySelector('input[name="subject"]').value = teacher.subject;
        document.querySelector('input[name="experience"]').value = teacher.experience;
        document.querySelector('input[name="certificates"]').value = teacher.certificates;
        document.querySelector('textarea[name="description"]').value = teacher.description;
        document.querySelector('input[name="avatar"]').value = teacher.avatar;
        
        showAddTeacherModal();
        
        // 删除原记录
        deleteTeacher(id);
    }
}

function editAchievement(id) {
    const achievement = achievements.find(a => a.id === id);
    if (achievement) {
        document.querySelector('input[name="title"]').value = achievement.title;
        document.querySelector('select[name="category"]').value = achievement.category;
        document.querySelector('input[name="student"]').value = achievement.student;
        document.querySelector('textarea[name="description"]').value = achievement.description;
        document.querySelector('input[name="image"]').value = achievement.image;
        document.querySelector('input[name="date"]').value = achievement.date;
        
        showAddAchievementModal();
        deleteAchievement(id);
    }
}

function editActivity(id) {
    const activity = activities.find(a => a.id === id);
    if (activity) {
        document.querySelector('input[name="title"]').value = activity.title;
        document.querySelector('select[name="category"]').value = activity.category;
        document.querySelector('input[name="location"]').value = activity.location;
        document.querySelector('textarea[name="description"]').value = activity.description;
        document.querySelector('input[name="image"]').value = activity.image;
        document.querySelector('input[name="startDate"]').value = activity.startDate;
        document.querySelector('input[name="endDate"]').value = activity.endDate;
        
        showAddActivityModal();
        deleteActivity(id);
    }
}

// 显示提示信息
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 3000);
}

// 页面加载完成后初始化
document.addEventListener('DOMContentLoaded', function() {
    initData();
    
    // 设置导航点击事件
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const tabName = this.getAttribute('href').substring(1);
            switchTab(tabName);
        });
    });
    
    // 默认显示仪表盘
    switchTab('dashboard');
});

// 导出数据功能（可选）
function exportData() {
    const data = {
        teachers: teachers,
        achievements: achievements,
        activities: activities,
        exportDate: new Date().toISOString()
    };
    
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `iris_data_${new Date().toISOString().split('T')[0]}.json`;
    a.click();
    URL.revokeObjectURL(url);
}

// 导入数据功能（可选）
function importData(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const data = JSON.parse(e.target.result);
                if (data.teachers) teachers = data.teachers;
                if (data.achievements) achievements = data.achievements;
                if (data.activities) activities = data.activities;
                
                saveTeachers();
                saveAchievements();
                saveActivities();
                
                initData();
                showAlert('数据导入成功！', 'success');
            } catch (error) {
                showAlert('数据导入失败，请检查文件格式！', 'danger');
            }
        };
        reader.readAsText(file);
    }
}