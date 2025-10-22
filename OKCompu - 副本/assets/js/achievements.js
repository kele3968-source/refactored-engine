document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const achievementItems = document.querySelectorAll('.achievement-item');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            achievementItems.forEach(item => {
                const category = item.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    item.style.display = 'block';
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(30px)';
                    item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    requestAnimationFrame(() => { item.style.opacity = '1'; item.style.transform = 'translateY(0)'; });
                } else {
                    item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(-30px)';
                    setTimeout(() => { item.style.display = 'none'; }, 300);
                }
            });
        });
    });

    // 图片模态
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const closeBtn = document.querySelector('.modal-close');
    achievementItems.forEach(item => {
        item.addEventListener('click', function() {
            const img = this.querySelector('.achievement-image');
            modal.style.display = 'block';
            modalImg.src = img.src;
        });
    });
    closeBtn.addEventListener('click', () => { modal.style.display = 'none'; });
    modal.addEventListener('click', (e) => { if (e.target === modal) modal.style.display = 'none'; });

    // 统计数字动画
    const observerOptions = { threshold: 0.5, rootMargin: '0px 0px -100px 0px' };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const finalCount = parseInt(target.getAttribute('data-count'));
                animateCounter(target, finalCount);
                observer.unobserve(target);
            }
        });
    }, observerOptions);
    document.querySelectorAll('.stat-number').forEach(stat => observer.observe(stat));
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) { current = target; clearInterval(timer); }
            element.textContent = Math.floor(current);
        }, 20);
    }

    // 滚动进入时的卡片动画
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                scrollObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.achievement-item').forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(50px)';
        scrollObserver.observe(item);
    });
});