// Main JS: 通用交互与工具函数
(function(){
  function ready(fn){ if(document.readyState!=='loading'){ fn(); } else { document.addEventListener('DOMContentLoaded', fn); } }

  // 统一：锚点平滑滚动
  function enableSmoothAnchors(){
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (!href || href === '#') return; 
        const target = document.querySelector(href);
        if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
      });
    });
  }

  // 统一：Navbar 背景滚动调整
  function enableNavbarScroll(){
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    const baseBg = getComputedStyle(navbar).background;
    window.addEventListener('scroll', () => {
      navbar.style.background = window.scrollY > 100 ? 'rgba(255, 255, 255, 0.98)' : baseBg || 'rgba(255, 255, 255, 0.95)';
    });
  }

  // 图片懒加载（补充未声明的图片）
  function enforceLazyImages(){
    document.querySelectorAll('img:not([loading])').forEach(img => { img.setAttribute('loading', 'lazy'); });
  }

  // 可视检测工具
  function onVisible(selector, options, onEnter){
    const els = typeof selector === 'string' ? document.querySelectorAll(selector) : selector;
    const obs = new IntersectionObserver((entries)=>{
      entries.forEach(entry=>{ if(entry.isIntersecting){ onEnter(entry.target, entry); obs.unobserve(entry.target); } });
    }, options || { threshold: 0.2, rootMargin: '0px 0px -50px 0px' });
    els.forEach(el=>obs.observe(el));
    return obs;
  }

  // 暴露到全局
  window.OKC = { onVisible };

  ready(function(){
    enableSmoothAnchors();
    enableNavbarScroll();
    enforceLazyImages();
  });
})();