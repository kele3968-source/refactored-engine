document.addEventListener('DOMContentLoaded', function() {
    // Timeline animation
    const timelineItems = document.querySelectorAll('.timeline-item');
    const timelineObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateX(0)';
                timelineObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });
    timelineItems.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = item.matches(':nth-child(odd)') ? 'translateX(-50px)' : 'translateX(50px)';
        timelineObserver.observe(item);
    });

    // Value cards animation
    const valueCards = document.querySelectorAll('.value-card');
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                cardObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });
    valueCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(50px)';
        cardObserver.observe(card);
    });

    // Facility gallery animation
    const facilityItems = document.querySelectorAll('.facility-item');
    const facilityObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'scale(1)';
                facilityObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });
    facilityItems.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'scale(0.8)';
        facilityObserver.observe(item);
    });

    // 锚点平滑滚动
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
});