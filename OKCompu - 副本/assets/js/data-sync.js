// 数据同步脚本 - 将后台数据同步到前端页面

// 从localStorage加载数据
function loadDataFromStorage() {
    try {
        const teachers = JSON.parse(localStorage.getItem('iris_teachers') || '[]');
        const achievements = JSON.parse(localStorage.getItem('iris_achievements') || '[]');
        const activities = JSON.parse(localStorage.getItem('iris_activities') || '[]');
        
        return { teachers, achievements, activities };
    } catch (error) {
        console.error('数据加载失败:', error);
        return { teachers: [], achievements: [], activities: [] };
    }
}

// 同步教师数据到teachers.html
function syncTeachersData() {
    if (!window.location.pathname.includes('teachers.html')) return;
    
    const data = loadDataFromStorage();
    const teachers = data.teachers.filter(t => t.status === 'active');
    
    // 更新教师卡片
    const teacherContainer = document.querySelector('.teachers-grid');
    if (teacherContainer) {
        teacherContainer.innerHTML = teachers.map(teacher => `
            <div class="col-md-4 mb-4">
                <div class="teacher-card">
                    <img src="${teacher.avatar}" alt="${teacher.name}" class="teacher-image">
                    <div class="teacher-info">
                        <h4 class="teacher-name">${teacher.name}</h4>
                        <p class="teacher-subject">${teacher.subject}</p>
                        <div class="teacher-experience">
                            <span class="experience-badge">${teacher.experience}年教龄</span>
                        </div>
                        <p class="teacher-description">${teacher.description}</p>
                        <div class="teacher-certificates">
                            ${teacher.certificates.split(',').map(cert => 
                                `<span class="certificate-badge">${cert.trim()}</span>`
                            ).join('')}
                        </div>
                        <div class="teacher-stats">
                            <div class="stat-item">
                                <span class="stat-number">${Math.floor(Math.random() * 100) + 50}</span>
                                <span class="stat-label">学生数</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">${Math.floor(Math.random() * 20) + 80}%</span>
                                <span class="stat-label">满意度</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    // 更新教师统计信息
    updateTeacherStats(teachers);
}

// 更新教师统计信息
function updateTeacherStats(teachers) {
    const statsElement = document.querySelector('.teacher-stats-overview');
    if (statsElement) {
        const totalTeachers = teachers.length;
        const avgExperience = teachers.reduce((sum, t) => sum + t.experience, 0) / totalTeachers;
        const certifiedTeachers = teachers.filter(t => t.certificates).length;
        
        statsElement.innerHTML = `
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">${totalTeachers}</span>
                    <span class="stat-label">专业教师</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">${avgExperience.toFixed(1)}</span>
                    <span class="stat-label">平均教龄</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">${certifiedTeachers}</span>
                    <span class="stat-label">持证教师</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">${Math.floor(Math.random() * 1000) + 500}</span>
                    <span class="stat-label">培养学员</span>
                </div>
            </div>
        `;
    }
}

// 同步学习成果数据到achievements.html
function syncAchievementsData() {
    if (!window.location.pathname.includes('achievements.html')) return;
    
    const data = loadDataFromStorage();
    const achievements = data.achievements.filter(a => a.status === 'active');
    
    // 更新成果画廊
    const galleryContainer = document.querySelector('.achievement-gallery');
    if (galleryContainer) {
        galleryContainer.innerHTML = achievements.map(achievement => `
            <div class="achievement-item" onclick="openAchievementModal('${achievement.id}')">
                <img src="${achievement.image}" alt="${achievement.title}" class="achievement-image">
                <div class="achievement-info">
                    <span class="achievement-category">${achievement.category}</span>
                    <h4 class="achievement-title">${achievement.title}</h4>
                    <p class="achievement-description">${achievement.description}</p>
                    <div class="achievement-meta">
                        <span class="achievement-author">${achievement.student}</span>
                        <span class="achievement-date">${formatDate(achievement.date)}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    // 更新统计概览
    updateAchievementStats(achievements);
    
    // 更新分类标签
    updateAchievementCategories(achievements);
}

// 更新学习成果统计信息
function updateAchievementStats(achievements) {
    const statsElement = document.querySelector('.stats-overview');
    if (statsElement) {
        const totalAchievements = achievements.length;
        const categories = [...new Set(achievements.map(a => a.category))];
        const recentAchievements = achievements.filter(a => 
            new Date(a.date) > new Date(Date.now() - 30 * 24 * 60 * 60 * 1000)
        ).length;
        
        statsElement.innerHTML = `
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">${totalAchievements}</span>
                    <span class="stat-label">优秀成果</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">${categories.length}</span>
                    <span class="stat-label">成果类别</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">${recentAchievements}</span>
                    <span class="stat-label">近期成果</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">${Math.floor(Math.random() * 50) + 20}</span>
                    <span class="stat-label">获奖学生</span>
                </div>
            </div>
        `;
    }
}

// 更新成果分类
function updateAchievementCategories(achievements) {
    const categories = [...new Set(achievements.map(a => a.category))];
    const filterTabs = document.querySelector('.filter-tabs');
    
    if (filterTabs) {
        filterTabs.innerHTML = `
            <div class="filter-tab active" onclick="filterAchievements('all')">全部</div>
            ${categories.map(category => 
                `<div class="filter-tab" onclick="filterAchievements('${category}')">${category}</div>`
            ).join('')}
        `;
    }
}

// 同步活动数据到activities.html
function syncActivitiesData() {
    if (!window.location.pathname.includes('activities.html')) return;
    
    const data = loadDataFromStorage();
    const activities = data.activities.filter(a => a.status === 'active');
    
    // 更新活动网格
    const activitiesGrid = document.querySelector('.activities-grid');
    if (activitiesGrid) {
        activitiesGrid.innerHTML = activities.map(activity => `
            <div class="activity-card" onclick="openActivityModal('${activity.id}')">
                <img src="${activity.image}" alt="${activity.title}" class="activity-image">
                <div class="activity-content">
                    <span class="activity-category">${activity.category}</span>
                    <h4 class="activity-title">${activity.title}</h4>
                    <p class="activity-description">${activity.description}</p>
                    <div class="activity-meta">
                        <span><i class="fas fa-calendar"></i> ${formatDate(activity.startDate)}</span>
                        <span><i class="fas fa-map-marker-alt"></i> ${activity.location}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    // 更新特色活动
    updateFeaturedActivity(activities);
    
    // 更新活动日历
    updateActivityCalendar(activities);
    
    // 更新分类标签
    updateActivityCategories(activities);
}

// 更新特色活动
function updateFeaturedActivity(activities) {
    const featuredElement = document.querySelector('.featured-activity');
    if (featuredElement && activities.length > 0) {
        const featured = activities[0]; // 取第一个作为特色活动
        featuredElement.innerHTML = `
            <img src="${featured.image}" alt="${featured.title}" class="featured-image">
            <div class="featured-content">
                <span class="featured-tag">特色活动</span>
                <h3 class="featured-title">${featured.title}</h3>
                <p class="featured-description">${featured.description}</p>
                <div class="featured-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>${formatDate(featured.startDate)}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${featured.location}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span>${Math.floor(Math.random() * 100) + 50}人参与</span>
                    </div>
                </div>
            </div>
        `;
    }
}

// 更新活动日历
function updateActivityCalendar(activities) {
    const calendarElement = document.querySelector('.calendar-container');
    if (calendarElement) {
        // 简化版日历实现
        const currentDate = new Date();
        const monthNames = ['一月', '二月', '三月', '四月', '五月', '六月', 
                           '七月', '八月', '九月', '十月', '十一月', '十二月'];
        
        calendarElement.innerHTML = `
            <div class="calendar-header">
                <h4 class="calendar-title">
                    ${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}
                </h4>
                <div>
                    <button class="calendar-nav"><i class="fas fa-chevron-left"></i></button>
                    <button class="calendar-nav ms-2"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="calendar-grid">
                ${generateCalendarDays(currentDate, activities)}
            </div>
        `;
    }
}

// 生成日历天数
function generateCalendarDays(date, activities) {
    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDay = firstDay.getDay();
    
    let calendarHTML = '';
    
    // 添加空白天
    for (let i = 0; i < startingDay; i++) {
        calendarHTML += '<div class="calendar-day disabled"></div>';
    }
    
    // 添加月份天数
    for (let day = 1; day <= daysInMonth; day++) {
        const currentDate = new Date(year, month, day);
        const hasEvent = activities.some(activity => 
            new Date(activity.startDate).toDateString() === currentDate.toDateString()
        );
        
        calendarHTML += `
            <div class="calendar-day ${hasEvent ? 'has-event' : ''}">
                ${day}
                ${hasEvent ? '<div class="event-dot"></div>' : ''}
            </div>
        `;
    }
    
    return calendarHTML;
}

// 更新活动分类
function updateActivityCategories(activities) {
    const categories = [...new Set(activities.map(a => a.category))];
    const filterTabs = document.querySelector('.filter-tabs');
    
    if (filterTabs) {
        filterTabs.innerHTML = `
            <div class="filter-tab active" onclick="filterActivities('all')">全部</div>
            ${categories.map(category => 
                `<div class="filter-tab" onclick="filterActivities('${category}')">${category}</div>`
            ).join('')}
        `;
    }
}

// 日期格式化函数
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('zh-CN', options);
}

// 模态框函数（简化版）
function openAchievementModal(id) {
    const data = loadDataFromStorage();
    const achievement = data.achievements.find(a => a.id === parseInt(id));
    
    if (achievement) {
        alert(`成果详情：\n标题：${achievement.title}\n学生：${achievement.student}\n描述：${achievement.description}`);
    }
}

function openActivityModal(id) {
    const data = loadDataFromStorage();
    const activity = data.activities.find(a => a.id === parseInt(id));
    
    if (activity) {
        alert(`活动详情：\n标题：${activity.title}\n时间：${formatDate(activity.startDate)}\n地点：${activity.location}\n描述：${activity.description}`);
    }
}

// 过滤函数
function filterAchievements(category) {
    const data = loadDataFromStorage();
    let achievements = data.achievements.filter(a => a.status === 'active');
    
    if (category !== 'all') {
        achievements = achievements.filter(a => a.category === category);
    }
    
    // 更新显示
    const galleryContainer = document.querySelector('.achievement-gallery');
    if (galleryContainer) {
        galleryContainer.innerHTML = achievements.map(achievement => `
            <div class="achievement-item" onclick="openAchievementModal('${achievement.id}')">
                <img src="${achievement.image}" alt="${achievement.title}" class="achievement-image">
                <div class="achievement-info">
                    <span class="achievement-category">${achievement.category}</span>
                    <h4 class="achievement-title">${achievement.title}</h4>
                    <p class="achievement-description">${achievement.description}</p>
                    <div class="achievement-meta">
                        <span class="achievement-author">${achievement.student}</span>
                        <span class="achievement-date">${formatDate(achievement.date)}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    // 更新标签激活状态
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    event.target.classList.add('active');
}

function filterActivities(category) {
    const data = loadDataFromStorage();
    let activities = data.activities.filter(a => a.status === 'active');
    
    if (category !== 'all') {
        activities = activities.filter(a => a.category === category);
    }
    
    // 更新显示
    const activitiesGrid = document.querySelector('.activities-grid');
    if (activitiesGrid) {
        activitiesGrid.innerHTML = activities.map(activity => `
            <div class="activity-card" onclick="openActivityModal('${activity.id}')">
                <img src="${activity.image}" alt="${activity.title}" class="activity-image">
                <div class="activity-content">
                    <span class="activity-category">${activity.category}</span>
                    <h4 class="activity-title">${activity.title}</h4>
                    <p class="activity-description">${activity.description}</p>
                    <div class="activity-meta">
                        <span><i class="fas fa-calendar"></i> ${formatDate(activity.startDate)}</span>
                        <span><i class="fas fa-map-marker-alt"></i> ${activity.location}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    // 更新标签激活状态
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    event.target.classList.add('active');
}

// 页面加载完成后同步数据
document.addEventListener('DOMContentLoaded', function() {
    syncTeachersData();
    syncAchievementsData();
    syncActivitiesData();
});

// 监听localStorage变化（用于实时同步）
window.addEventListener('storage', function(e) {
    if (e.key === 'iris_teachers' || e.key === 'iris_achievements' || e.key === 'iris_activities') {
        syncTeachersData();
        syncAchievementsData();
        syncActivitiesData();
    }
});