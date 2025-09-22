// Smooth scroll for internal links
(function(){
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', (e) => {
      const id = a.getAttribute('href');
      if (id.length > 1) {
        const el = document.querySelector(id);
        if (el) {
          e.preventDefault();
          el.scrollIntoView({behavior: 'smooth', block: 'start'});
        }
      }
    });
  });
})();

// Back to top button
(function(){
  const btn = document.getElementById('backToTop');
  const toggle = () => {
    if (window.scrollY > 400) btn.classList.add('show'); else btn.classList.remove('show');
  };
  window.addEventListener('scroll', toggle);
  btn.addEventListener('click', () => window.scrollTo({top:0, behavior:'smooth'}));
  toggle();
})();

// Scroll progress bar + header scrolled state
(function(){
  const bar = document.getElementById('scrollProgress');
  const header = document.querySelector('.site-header');
  if (!bar && !header) return;
  const onScroll = () => {
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const docH = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
    const winH = window.innerHeight;
    const max = docH - winH;
    if (bar) bar.style.width = Math.max(0, Math.min(100, (scrollTop / (max || 1)) * 100)) + '%';
    if (header) {
      if (scrollTop > 10) header.classList.add('scrolled'); else header.classList.remove('scrolled');
    }
  };
  window.addEventListener('scroll', onScroll, {passive:true});
  onScroll();
})();

// Reveal on scroll
(function(){
  const items = document.querySelectorAll('.reveal-on-scroll');
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(ent => {
      if (ent.isIntersecting) {
        ent.target.classList.add('revealed');
        // Animate skill bars when skills section reveals
        if (ent.target.id === 'skills') {
          document.querySelectorAll('.skill-bar').forEach(bar => {
            const lvl = parseInt(bar.getAttribute('data-level')||'0', 10);
            const fill = bar.querySelector('.skill-bar-fill');
            requestAnimationFrame(() => fill.style.width = Math.max(0, Math.min(100, lvl)) + '%');
          });
        }
        obs.unobserve(ent.target);
      }
    })
  }, {threshold: 0.2});
  items.forEach(i => obs.observe(i));
})();

// Tilt effect for project cards
(function(){
  const cards = document.querySelectorAll('[data-tilt]');
  const damp = 20; // lower is stronger tilt
  cards.forEach(card => {
    let rAF;
    const onMove = (e) => {
      const rect = card.getBoundingClientRect();
      const cx = rect.left + rect.width/2;
      const cy = rect.top + rect.height/2;
      const dx = (e.clientX - cx) / rect.width;
      const dy = (e.clientY - cy) / rect.height;
      const rx = (+dy * damp).toFixed(2);
      const ry = (-dx * damp).toFixed(2);
      if (rAF) cancelAnimationFrame(rAF);
      rAF = requestAnimationFrame(() => {
        card.style.transform = `perspective(700px) rotateX(${rx}deg) rotateY(${ry}deg)`;
        // update shine position
        card.style.setProperty('--mx', (e.clientX - rect.left) + 'px');
        card.style.setProperty('--my', (e.clientY - rect.top) + 'px');
      });
    };
    const reset = () => card.style.transform = '';
    card.addEventListener('mousemove', onMove);
    card.addEventListener('mouseleave', reset);
  });
})();

// Particle canvas background
(function(){
  const canvas = document.getElementById('particle-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  let w, h, dpr, particles;
  const P = 110; // number of particles

  function resize(){
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    w = canvas.clientWidth = canvas.offsetWidth;
    h = canvas.clientHeight = canvas.offsetHeight;
    canvas.width = Math.floor(w * dpr);
    canvas.height = Math.floor(h * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }
  window.addEventListener('resize', resize);
  resize();

  function init(){
    particles = new Array(P).fill(0).map(() => ({
      x: Math.random()*w,
      y: Math.random()*h,
      vx: (Math.random()*2-1)*0.6,
      vy: (Math.random()*2-1)*0.6,
      r: Math.random()*2 + 0.6,
    }));
  }
  init();

  function draw(){
    ctx.clearRect(0,0,w,h);
    // glow backdrop
    const g = ctx.createLinearGradient(0,0,w,h);
    g.addColorStop(0, 'rgba(106,17,203,0.08)');
    g.addColorStop(1, 'rgba(37,117,252,0.06)');
    ctx.fillStyle = g;
    ctx.fillRect(0,0,w,h);

    // update and draw particles
    for (let i=0;i<P;i++){
      const p = particles[i];
      p.x += p.vx; p.y += p.vy;
      if (p.x < 0 || p.x > w) p.vx *= -1;
      if (p.y < 0 || p.y > h) p.vy *= -1;
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
      ctx.fillStyle = 'rgba(255,255,255,0.7)';
      ctx.fill();
    }

    // connect nearby
    for (let i=0;i<P;i++){
      const p1 = particles[i];
      for (let j=i+1;j<P;j++){
        const p2 = particles[j];
        const dx = p1.x - p2.x, dy = p1.y - p2.y;
        const dist2 = dx*dx + dy*dy;
        if (dist2 < 140*140){
          const alpha = 1 - (dist2 / (140*140));
          ctx.strokeStyle = `rgba(144, 202, 249, ${alpha*0.25})`;
          ctx.beginPath();
          ctx.moveTo(p1.x, p1.y);
          ctx.lineTo(p2.x, p2.y);
          ctx.stroke();
        }
      }
    }

    requestAnimationFrame(draw);
  }
  requestAnimationFrame(draw);
})();

// Secret admin access (hidden): 5 rapid clicks on brand or Ctrl+Alt+A
(function(){
  const brand = document.querySelector('.brand');
  let clicks = 0, timer = null;
  function goAdmin(){
    window.location.href = 'admin/index.php';
  }
  if (brand){
    brand.addEventListener('click', () => {
      clicks++;
      if (!timer){
        timer = setTimeout(() => { clicks = 0; timer = null; }, 1200);
      }
      if (clicks >= 5){
        clearTimeout(timer); timer = null; clicks = 0;
        goAdmin();
      }
    });
  }
  window.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.altKey && (e.key || '').toLowerCase() === 'a'){
      goAdmin();
    }
  });
})();
