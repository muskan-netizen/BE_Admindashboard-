
<style>

 .testimonial-section {
  width: 100%;
  background: #eae9e6;
  padding: 10px 0 20px;
  position: relative;
  overflow: hidden;
  padding: 20px !important;
}

/* Decorative circles */
.testimonial-section::before {
  content: '';
  position: absolute;
  width: 420px;
  height: 420px;
  border-radius: 50%;
  background: rgba(127,29,29,0.05);
  top: -160px;
  right: -100px;
  pointer-events: none;
}

.testimonial-section::after {
  content: '';
  position: absolute;
  width: 280px;
  height: 280px;
  border-radius: 50%;
  background: rgba(127,29,29,0.05);
  bottom: -90px;
  left: -70px;
  pointer-events: none;
}

/* Mobile Responsive */
@media (max-width: 769px) {

  .testimonial-section {
    padding: 20px 0;
  }

  .testimonial-section::before {
    width: 220px;
    height: 220px;
    top: -80px;
    right: -60px;
  }

  .testimonial-section::after {
    width: 160px;
    height: 160px;
    bottom: -50px;
    left: -40px;
  }

}
  .section-header { text-align: center; margin-bottom: 0px; padding: 0 20px; }
  .t-subtitle {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    color: #7f1d1d;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 4px;
    margin-bottom: 10px;
  }
  .t-subtitle::before,
  .t-subtitle::after {
    content: '';
    display: block;
    width: 36px; height: 1.5px;
    background: #7f1d1d; opacity: 0.55;
  }
  .t-title {
    font-size: 34px;
    font-weight: 800;
    color: #1a1a1a;
    letter-spacing: -0.5px;
  }

  /* Viewport */
  .slider-viewport {
    position: relative;
    overflow: hidden;
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
    mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
    min-height: min-content;
  }

  /* Track */
  .slider-track {
    display: flex;
    gap: 24px;
    align-items: stretch;
    padding: 15px 12px 12px;
    transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
    margin-top: 15px;
  }

  /* Card */
  .t-card {
    background: #fff;
    border-radius: 18px;
    padding: 20px 16px 18px;
    position: relative;
    box-shadow: 0 2px 14px rgba(0,0,0,0.06), 0 8px 32px rgba(0,0,0,0.05);
    transition: transform 0.32s ease, box-shadow 0.32s ease;
    display: flex;
    flex-direction: column;
    text-align: left;
    flex-shrink: 0;
    cursor: grab;
    user-select: none;
  }
  .t-card:active { cursor: grabbing; }
  .t-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 6px 28px rgba(127,29,29,0.12), 0 18px 50px rgba(0,0,0,0.09);
  }

  .quote-badge {
    width: 50px; height: 50px;
    background: linear-gradient(145deg, #9b1c1c, #7f1d1d);
    border-radius: 12px;
    position: absolute;
    top: -25px; left: 22px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 28px; font-family: Georgia, serif;
    line-height: 1;
    box-shadow: 0 6px 20px rgba(127,29,29,0.42);
    user-select: none;
  }

  .stars { display: flex; gap: 3px; margin-bottom: 14px; }
  .stars svg { width: 15px; height: 15px; fill: #f59e0b; }

  .t-text {
    color: #555; line-height: 1.8; font-size: 14px;
    font-style: italic; flex-grow: 1;
    color: #555;
  line-height: 1.8;
  font-size: 14px;
  font-style: italic;
  flex-grow: 1;
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  }

  /* .t-divider {
    width: 34px; height: 2px;
    background: #7f1d1d; opacity: 0.45;
    margin: 22px 0; border-radius: 2px;
  } */

  .t-user { display: flex; align-items: center; gap: 13px; }
  .avatar {
    width: 46px; height: 46px; border-radius: 50%;
    flex-shrink: 0; display: flex;
    align-items: center; justify-content: center;
    font-weight: 700; font-size: 15px; color: #fff;
    border: 2.5px solid rgba(255,255,255,0.6);
    transition: box-shadow 0.3s;
  }
  .t-card:hover .avatar { box-shadow: 0 0 0 3px rgba(127,29,29,0.25); }

  .av-red    { background: linear-gradient(135deg,#991b1b,#dc2626); }
  .av-blue   { background: linear-gradient(135deg,#1d4ed8,#60a5fa); }
  .av-green  { background: linear-gradient(135deg,#065f46,#10b981); }
  .av-purple { background: linear-gradient(135deg,#5b21b6,#a78bfa); }
  .av-orange { background: linear-gradient(135deg,#92400e,#f97316); }

  .u-name { font-size: 15px; font-weight: 700; color: #1a1a1a; }
  .u-role { font-size: 12.5px; color: #9ca3af; margin-top: 2px; }

  /* Controls */
  .slider-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    margin-top: 16px;
  }

  .nav-btn {
    width: 42px; height: 42px;
    background: #fff; border: none; border-radius: 50%;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: background 0.25s, transform 0.2s, color 0.25s;
    color: #7f1d1d; flex-shrink: 0;
  }
  .nav-btn:hover { background: #7f1d1d; color: #fff; transform: scale(1.08); }
  .nav-btn svg {
    width: 16px; height: 16px;
    stroke: currentColor; fill: none;
    stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
  }

  .dots { display: flex; align-items: center; gap: 6px; }
  .dot {
    width: 8px; height: 8px; border-radius: 10px;
    background: #c2bfb8; border: none; cursor: pointer;
    transition: all 0.3s; padding: 0;
  }
  .dot.active { background: #7f1d1d; width: 22px; }

  @media (max-width: 640px) { .t-title { font-size: 24px; } }
  @media (max-width: 768px) { .testimonial-section { padding-bottom: 40px; } }
  .container-testimonial-content{
    padding-right: 20px;
  }
  @media (max-width: 425px) { 
    .slider-viewport{
-webkit-mask-image:none;
mask-image:none;
}

</style>


<section class="testimonial-section">
  <div style="max-width:1200px;margin:0 auto;position:relative;z-index:1;" class="container-testimonial-content">

    <div class="section-header">
      <div class="t-subtitle">Testimonial</div>
      <h2 class="t-title">Happy Client Says About Us</h2>
    </div>

    <div class="slider-viewport" id="sliderViewport">
      <div class="slider-track" id="sliderTrack"></div>
    </div>

    <div class="slider-controls">
      <button class="nav-btn" id="btnPrev" aria-label="Previous">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      </button>
      <div class="dots" id="dotsContainer"></div>
      <button class="nav-btn" id="btnNext" aria-label="Next">
        <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg>
      </button>
    </div>

  </div>
</section>

<script>
(function () {

  const CARDS = [
    { initials: 'PM', avClass: 'av-red',    name: 'Prakash Mani',  role: 'Web Developer',    text: '"RestoCare transformed our restaurant\'s online presence. Their expertise in digital marketing helped us reach a wider audience and boost our reservations significantly."' },
    { initials: 'RS', avClass: 'av-blue',   name: 'Rohit Sharma',  role: 'Restaurant Owner', text: '"Amazing service and great support team. Highly recommended for businesses looking to grow digitally. The results exceeded all our expectations from day one."' },
    { initials: 'AV', avClass: 'av-green',  name: 'Anjali Verma',  role: 'Business Manager', text: '"Professional team with outstanding marketing strategies. Our sales increased within weeks of signing up. Their dedication and creativity are truly unmatched."' },
    { initials: 'NG', avClass: 'av-purple', name: 'Neha Gupta',    role: 'Cafe Owner',       text: '"Working with this team has been a game changer for our cafe. The social media campaigns they crafted brought us consistent new customers every single week."' },
    { initials: 'SK', avClass: 'av-orange', name: 'Suresh Kumar',  role: 'Hotel Manager',    text: '"Exceptional results every time. The team understood our brand vision perfectly and delivered a campaign that tripled our online bookings in just one month."' },
  ];

  const STAR_SVG = `<svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`.repeat(5);

  function buildCardHTML(d) {
    return `
      <div class="t-card">
        <div class="quote-badge">&#8220;</div>
        <div class="stars">${STAR_SVG}</div>
        <p class="t-text">${d.text}</p>
        <div class="t-divider"></div>
        <div class="t-user">
          <div class="avatar ${d.avClass}">${d.initials}</div>
          <div>
            <div class="u-name">${d.name}</div>
            <div class="u-role">${d.role}</div>
          </div>
        </div>
      </div>`;
  }

  const viewport  = document.getElementById('sliderViewport');
  const track     = document.getElementById('sliderTrack');
  const btnPrev   = document.getElementById('btnPrev');
  const btnNext   = document.getElementById('btnNext');
  const dotsWrap  = document.getElementById('dotsContainer');

  const GAP       = 24;
  const AUTO_MS   = 3000;   // auto-advance interval
  const TOTAL     = CARDS.length;

  let perView     = 3;
  let cardWidth   = 0;
  let currentIdx  = 0;      // index of leftmost visible original card
  let isAnimating = false;
  let autoTimer   = null;

  /* ── Build track with clones at the end for seamless wrap ── */
  function buildTrack() {
    track.innerHTML = '';
    // original cards
    CARDS.forEach(d => track.insertAdjacentHTML('beforeend', buildCardHTML(d)));
    // clone first `perView` cards at the end so wrap animation has room
    CARDS.slice(0, perView).forEach(d => track.insertAdjacentHTML('beforeend', buildCardHTML(d)));
  }

  function getPerView() {
    const w = window.innerWidth;
    if (w < 640)  return 1;
    if (w < 1024) return 2;
    return 3;
  }

  function calcCardWidth() {
    const vw = viewport.clientWidth;
    return (vw - GAP * (perView - 1)) / perView;
  }

  function setCardWidths() {
    Array.from(track.children).forEach(c => {
      c.style.width    = cardWidth + 'px';
      c.style.minWidth = cardWidth + 'px';
    });
  }

  /* ── Translate to index ── */
  function translateTo(idx, animate) {
    track.style.transition = animate
      ? 'transform 0.45s cubic-bezier(0.4, 0, 0.2, 1)'
      : 'none';
    track.style.transform = `translateX(-${idx * (cardWidth + GAP)}px)`;
  }

  /* ── Dots ── */
  function buildDots() {
    dotsWrap.innerHTML = '';
    for (let i = 0; i < TOTAL; i++) {
      const d = document.createElement('button');
      d.className = 'dot' + (i === 0 ? ' active' : '');
      d.setAttribute('aria-label', 'Slide ' + (i + 1));
      d.addEventListener('click', () => goTo(i));
      dotsWrap.appendChild(d);
    }
  }

  function updateDots(idx) {
    Array.from(dotsWrap.children).forEach((d, i) => {
      d.classList.toggle('active', i === idx);
    });
  }

  /* ── Navigate to a specific index (wraps around using clones) ── */
  function goTo(idx) {
    if (isAnimating) return;
    isAnimating = true;

    // idx can go beyond TOTAL (into clone zone) — we animate there, then silently snap back
    translateTo(idx, true);
    updateDots(((idx % TOTAL) + TOTAL) % TOTAL);

    setTimeout(() => {
      // Normalise into 0 … TOTAL-1 without animation
      const normalised = ((idx % TOTAL) + TOTAL) % TOTAL;
      currentIdx = normalised;
      translateTo(currentIdx, false);
      isAnimating = false;
    }, 460);
  }

  /* ── Auto-advance one card at a time ── */
  function startAuto() {
    clearInterval(autoTimer);
    autoTimer = setInterval(() => {
      const next = (currentIdx + 1) % TOTAL;
      goTo(next);
    }, AUTO_MS);
  }

  function stopAuto() { clearInterval(autoTimer); }

  /* ── Buttons ── */
  btnPrev.addEventListener('click', () => {
    stopAuto();
    goTo(currentIdx - 1);
    startAuto();
  });

  btnNext.addEventListener('click', () => {
    stopAuto();
    goTo(currentIdx + 1);
    startAuto();
  });

  /* ── Hover pause ── */
  viewport.addEventListener('mouseenter', stopAuto);
  viewport.addEventListener('mouseleave', startAuto);

  /* ── Touch / drag ── */
  let dragStartX = 0, dragDelta = 0, isDragging = false;

  viewport.addEventListener('pointerdown', e => {
    isDragging = true;
    dragStartX = e.clientX;
    dragDelta  = 0;
    stopAuto();
    viewport.setPointerCapture(e.pointerId);
  });

  viewport.addEventListener('pointermove', e => {
    if (!isDragging) return;
    dragDelta = e.clientX - dragStartX;
  });

  viewport.addEventListener('pointerup', () => {
    if (!isDragging) return;
    isDragging = false;
    if (dragDelta < -50) goTo(currentIdx + 1);
    else if (dragDelta > 50) goTo(currentIdx - 1);
    startAuto();
  });

  /* ── Init & resize ── */
  function init() {
    stopAuto();
    perView   = getPerView();
    buildTrack();
    cardWidth = calcCardWidth();
    setCardWidths();
    buildDots();
    currentIdx = 0;
    translateTo(0, false);
    updateDots(0);
    startAuto();
  }

  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(init, 220);
  });

  init();
})();
</script>


