class ActivityCalendar {
    constructor() { this.currentDate = new Date(); this.events = this.generateEvents(); this.init(); }
    init() { this.renderCalendar(); this.bindEvents(); }
    generateEvents() {
        return {
            '2024-10-25': { title: '万圣节主题活动', type: 'festival' },
            '2024-11-15': { title: '数学竞赛', type: 'competition' },
            '2024-11-28': { title: '感恩节活动', type: 'festival' },
            '2024-12-20': { title: '圣诞派对', type: 'festival' },
            '2025-01-15': { title: '科学实验展示', type: 'science' },
            '2025-01-25': { title: '春节文化活动', type: 'festival' },
            '2025-02-14': { title: '户外探索活动', type: 'outdoor' },
            '2025-03-15': { title: '英语演讲比赛', type: 'competition' },
            '2025-04-20': { title: '艺术作品展', type: 'art' }
        };
    }
    renderCalendar() {
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();
        const monthNames = ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'];
        document.getElementById('calendarTitle').textContent = `${year}年${monthNames[month]}`;
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeekSundayZero = firstDay.getDay();
        const startingDayOfWeek = (startingDayOfWeekSundayZero + 6) % 7; // 周一为第一列
        const grid = document.getElementById('calendarGrid');
        grid.innerHTML = '';
        ['一','二','三','四','五','六','日'].forEach(day => {
            const h = document.createElement('div'); h.className = 'calendar-day'; h.style.fontWeight = '600'; h.style.color = '#666'; h.textContent = day; grid.appendChild(h);
        });
        for (let i = 0; i < startingDayOfWeek; i++) { const e = document.createElement('div'); e.className = 'calendar-day disabled'; grid.appendChild(e); }
        for (let day = 1; day <= daysInMonth; day++) {
            const el = document.createElement('div');
            const dateStr = `${year}-${String(month + 1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
            el.className = 'calendar-day'; el.textContent = day;
            if (this.events[dateStr]) { el.classList.add('has-event'); el.title = this.events[dateStr].title; el.addEventListener('click', () => this.showEventDetails(dateStr)); }
            const today = new Date(); const current = new Date(year, month, day);
            if (current < today) el.classList.add('disabled');
            grid.appendChild(el);
        }
    }
    showEventDetails(dateStr) { const e = this.events[dateStr]; if (e) alert(`${dateStr}: ${e.title}\n\n点击活动卡片可查看详细信息和更多图片。`); }
    bindEvents() {
        document.getElementById('prevMonth').addEventListener('click', () => { this.currentDate.setMonth(this.currentDate.getMonth() - 1); this.renderCalendar(); });
        document.getElementById('nextMonth').addEventListener('click', () => { this.currentDate.setMonth(this.currentDate.getMonth() + 1); this.renderCalendar(); });
    }
}

const activityDetails = {
    'christmas-2023': { title: '圣诞主题派对', description: '...（省略原文，保持结构）...', gallery: ['resources/activities-montage.png','resources/hero-classroom.png','resources/facility-exterior.png'] },
    'math-olympics': { title: '数学思维竞赛', description: '...（省略原文，保持结构）...', gallery: ['resources/achievements-showcase.png','resources/hero-classroom.png','resources/facility-exterior.png'] },
    'art-exhibition': { title: '儿童艺术作品展览', description: '...（省略原文，保持结构）...', gallery: ['resources/teachers-group.png','resources/activities-montage.png','resources/facility-exterior.png'] },
};

document.addEventListener('DOMContentLoaded', function() {
    const calendar = new ActivityCalendar();
    const filterTabs = document.querySelectorAll('.filter-tab');
    const cards = document.querySelectorAll('.activity-card');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            cards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    card.style.display = 'block';
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    requestAnimationFrame(() => { card.style.opacity = '1'; card.style.transform = 'translateY(0)'; });
                } else {
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(-30px)';
                    setTimeout(() => { card.style.display = 'none'; }, 300);
                }
            });
        });
    });

    // Modal
    const modal = document.getElementById('activityModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalDescription = document.getElementById('modalDescription');
    const modalGallery = document.getElementById('modalGallery');
    const closeBtn = document.querySelector('.modal-close');

    cards.forEach(card => {
        card.addEventListener('click', function() {
            const id = this.getAttribute('data-activity');
            const cover = this.querySelector('.activity-image');
            const title = this.querySelector('.activity-title').textContent;
            const desc = this.querySelector('.activity-description').textContent;
            modal.style.display = 'block';
            modalImage.src = cover.src;
            modalTitle.textContent = title;
            if (activityDetails[id]) {
                const details = activityDetails[id];
                modalTitle.textContent = details.title;
                modalDescription.textContent = details.description;
                modalGallery.innerHTML = '';
                details.gallery.forEach(src => {
                    const img = document.createElement('img');
                    img.src = src; img.className = 'gallery-image'; img.alt = '活动照片';
                    modalGallery.appendChild(img);
                });
            } else {
                modalDescription.textContent = desc + '\n\n更多详细信息和图片正在整理中，敬请期待！';
                modalGallery.innerHTML = '<p style="text-align:center;color:#999;">更多图片即将更新</p>';
            }
        });
    });

    closeBtn.addEventListener('click', () => { modal.style.display = 'none'; });
    modal.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

    // Scroll animations
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                scrollObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(50px)';
        scrollObserver.observe(card);
    });
});