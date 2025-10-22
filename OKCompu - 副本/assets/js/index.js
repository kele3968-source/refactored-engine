document.addEventListener('DOMContentLoaded', function() {
    // 简易轮播：轮换显示 .splide__slide
    const slides = Array.from(document.querySelectorAll('#hero-carousel .splide__slide'));
    let current = 0;
    function showSlide(i) {
        slides.forEach((li, idx) => {
            li.style.opacity = idx === i ? '1' : '0';
            li.style.transition = 'opacity 0.8s ease';
            li.style.position = 'absolute';
            li.style.left = '0';
            li.style.top = '0';
            li.style.width = '100%';
            li.style.height = '100%';
        });
    }
    if (slides.length > 0) {
        showSlide(current);
        setInterval(() => {
            current = (current + 1) % slides.length;
            showSlide(current);
        }, 5000);
    }

    // 统计数字动画（保留原逻辑）
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
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 20);
    }

    // 锚点平滑滚动
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Navbar滚动背景调整
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        navbar.style.background = window.scrollY > 100 ? 'rgba(255, 255, 255, 0.98)' : 'rgba(255, 255, 255, 0.95)';
    });
});