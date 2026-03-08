<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
  <title>RestoCare · FAQ · refined mobile</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* ---------- global reset (body only minimal) ---------- */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      margin: 0;
      padding: 0;
      background: #ffffff;   /* neutral base, section covers it */
    }

    :root {
      --red: #991b1b;
      --red-light: #fef2f2;
      --red-mid: #dc2626;
      --ink: #111827;
      --smoke: #f9fafb;
      --mist: #e5e7eb;
      --steel: #6b7280;
      --white: #ffffff;

      --radius: 14px;
      --dur: 0.38s;
      --ease: cubic-bezier(0.22, 1, 0.36, 1);

      /* fluid spacing tokens */
      --space-xs: clamp(4px, 1.2vw, 8px);
      --space-sm: clamp(8px, 2vw, 14px);
      --space-md: clamp(12px, 3vw, 22px);
      --space-lg: clamp(16px, 4vw, 30px);
      --space-xl: clamp(10px, 5vw, 15px);
    }

    /* ---------- main section: all design lives here ---------- */
    .faq-section {
      font-family: 'DM Sans', sans-serif;
      background: #fff;
      color: var(--ink);
      -webkit-font-smoothing: antialiased;
      line-height: 1.5;

      padding: var(--space-xl) var(--space-lg);
      position: relative;
      overflow: hidden;
      /* min-height: 100dvh; */
      display: flex;
      align-items: center;
    }

    /* subtle background glow — stays behind content */
    .faq-section::before {
      content: '';
      position: absolute;
      width: min(500px, 80vw);
      background: radial-gradient(circle, #fee2e2 0%, transparent 70%);
      top: -20%;
      right: -10%;
      border-radius: 50%;
      filter: blur(70px);
      opacity: 0.5;
      pointer-events: none;
      z-index: 0;
    }

    .faq-container {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 1000px;   /* comfortable reading width */
      margin: 0 auto;
      padding: 10px
    }

    /* ------ HEADER (fully fluid) ------ */
    .faq-header {
      text-align: center;
      margin-bottom: clamp(15px, 6vw, 20px);
      animation: fadeUp 0.7s var(--ease) both;
    }

    .faq-tag {
      display: inline-block;
      font-size: clamp(10px, 2vw, 14px);
      font-weight: 800;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--red);
      background: var(--red-light);
      padding: 10px 20px;
      border-radius: 100px;
      margin-bottom: 8px;
      -webkit-tap-highlight-color: transparent;
    }

    .faq-header h2 {
      font-size: clamp(22px, 5vw, 32px);
      font-weight: 700;
      color: var(--ink);
      line-height: 1.2;
      margin-bottom: 8px;
    }

    .faq-header h2 span {
      color: var(--red-mid);
    }

    .faq-header p {
      font-size: clamp(13px, 3.5vw, 16px);
      color: var(--steel);
      line-height: 1.6;
      max-width: 520px;
      margin-left: auto;
      margin-right: auto;
      padding: 0 var(--space-xs);
    }

    /* ------ FAQ list ------ */
    .faq-list {
      display: flex;
      flex-direction: column;
      gap: clamp(6px, 1.8vw, 12px);
    }

    /* ------ accordion item ------ */
    .faq-item {
      background: var(--white);
      border-radius: var(--radius);
      border: 1.5px solid var(--mist);
      overflow: hidden;
      transition: border-color var(--dur) var(--ease),
                  box-shadow var(--dur) var(--ease),
                  transform 0.2s ease;
      animation: fadeUp 0.6s var(--ease) both;
      width: 100%;
    }

    /* staggered fade-in */
    .faq-item:nth-child(1) { animation-delay: 0.05s; }
    .faq-item:nth-child(2) { animation-delay: 0.1s; }
    .faq-item:nth-child(3) { animation-delay: 0.15s; }
    .faq-item:nth-child(4) { animation-delay: 0.2s; }
    .faq-item:nth-child(5) { animation-delay: 0.25s; }

    /* hover only on fine pointing devices */
    @media (hover: hover) and (pointer: fine) {
      .faq-item:hover {
        border-color: #fca5a5;
        box-shadow: 0 8px 28px rgba(153, 27, 27, 0.08);
        transform: translateY(-2px);
      }
    }

    .faq-item.active {
      border-color: var(--red);
      box-shadow: 0 8px 28px rgba(153, 27, 27, 0.12);
      transform: translateY(-1px);
    }

    /* ------ question row (touch optimized) ------ */
    .faq-question {
      padding: var(--space-md) var(--space-lg);
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: var(--space-sm);
      user-select: none;
      -webkit-tap-highlight-color: transparent;
      transition: background-color 0.2s;
    }

    .faq-item.active .faq-question {
      background-color: #fefcfc;
    }

    /* numeric badge */
    .faq-num {
      font-size: clamp(10px, 2vw, 12px);
      font-weight: 700;
      color: var(--red);
      background: var(--red-light);
      border-radius: 30px;
      padding: 4px 12px;
      flex-shrink: 0;
      letter-spacing: 0.2px;
      transition: background var(--dur), color var(--dur);
    }

    .faq-item.active .faq-num {
      background: var(--red);
      color: #fff;
    }

    /* question text */
    .faq-question h4 {
      flex: 1;
      font-size: clamp(14px, 3.5vw, 17px);
      font-weight: 600;
      color: var(--ink);
      line-height: 1.4;
      transition: color var(--dur);
      word-break: break-word;
      padding-right: var(--space-xs);
    }

    .faq-item.active .faq-question h4 {
      color: var(--red);
    }

    /* chevron icon */
    .faq-icon {
      width: clamp(28px, 5vw, 34px);
      aspect-ratio: 1 / 1;           /* keeps it square without fixed height */
      flex-shrink: 0;
      border-radius: 50%;
      background: var(--smoke);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background var(--dur) var(--ease),
                  transform var(--dur) var(--ease);
    }

    .faq-icon svg {
      width: 14px;
      height: 14px;
      stroke: var(--steel);
      stroke-width: 2.5;
      transition: stroke var(--dur);
    }

    .faq-item.active .faq-icon {
      background: var(--red);
      transform: rotate(180deg);
    }

    .faq-item.active .faq-icon svg {
      stroke: #fff;
    }

    /* divider line */
    .faq-divider {
      height: 1.5px;
      background: linear-gradient(90deg, var(--mist), #f3f4f6);
      margin: 0 var(--space-lg);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform var(--dur) var(--ease);
    }

    .faq-item.active .faq-divider {
      transform: scaleX(1);
    }

    /* answer container – smooth transition */
    .faq-answer {
      display: grid;
      grid-template-rows: 0fr;
      transition: grid-template-rows var(--dur) var(--ease);
    }

    .faq-answer-inner {
      overflow: hidden;
    }

    .faq-item.active .faq-answer {
      grid-template-rows: 1fr;
    }

    .faq-answer p {
      padding: var(--space-sm) var(--space-lg) var(--space-md);
      font-size: clamp(13px, 3vw, 15px);
      color: var(--steel);
      line-height: 1.7;
      max-width: 700px;
    }

    /* fade-up animation */
    @keyframes fadeUp {
      0% { opacity: 0; transform: translateY(18px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    /* ---------- MOBILE FIRST: extra left/right 5px padding on very small screens ---------- */
    @media (max-width: 420px) {
      .faq-section {
        padding-left: 5px !important;
        padding-right: 5px !important;
      }
    }

    /* adjust inner spaces for extremely narrow screens (below 360px) */
    @media (max-width: 360px) {
      .faq-question {
        padding: 12px 8px;
        gap: 6px;
      }
      .faq-num {
        display: none;      /* saves space, cleaner */
      }
      .faq-question h4 {
        font-size: 13px;
      }
      .faq-icon {
        width: 28px;
      }
      .faq-answer p {
        padding: 8px 10px 16px;
        font-size: 12px;
      }
    }

    /* for landscape small phones */
    @media (max-height: 450px) and (orientation: landscape) {
      .faq-section {
        min-height: auto;
        padding-top: 16px;
        padding-bottom: 16px;
      }
      .faq-list {
        gap: 6px;
      }
    }

    /* improve tap highlight on touch devices */
    .faq-question:active {
      background-color: #fafafa;
      transition: none;
    }

    /* performance hint */
    .faq-item,
    .faq-answer {
      will-change: border-color, box-shadow, grid-template-rows;
    }

    /* no hover effects on touch */
    @media (hover: none) {
      .faq-item:hover {
        border-color: var(--mist);
        box-shadow: none;
        transform: none;
      }
    }

    /* accessibility focus outline */
    .faq-question:focus-visible {
      outline: 3px solid var(--red-mid);
      outline-offset: 2px;
    }
  </style>
</head>
<body>
<section class="faq-section">
  <div class="faq-container">

    <div class="faq-header">
      <div class="faq-tag">Support</div>
      <h2>Frequently Asked <span>Questions</span></h2>
      <p>Everything you need to know about RestoCare — bookings, providers, and more.</p>
    </div>

    <div class="faq-list">

      <div class="faq-item">
        <div class="faq-question" tabindex="0" role="button" aria-expanded="false">
          <span class="faq-num">01</span>
          <h4>What is RestoCare?</h4>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
        </div>
        <div class="faq-divider"></div>
        <div class="faq-answer"><div class="faq-answer-inner">
          <p>RestoCare is a service marketplace platform connecting customers with verified professionals — chefs, plumbers, technicians, and more — all through one seamless booking experience.</p>
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-question" tabindex="0" role="button" aria-expanded="false">
          <span class="faq-num">02</span>
          <h4>How do I book a service?</h4>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
        </div>
        <div class="faq-divider"></div>
        <div class="faq-answer"><div class="faq-answer-inner">
          <p>Browse our service categories, select the service you need, pick a convenient time slot, and confirm your booking. You'll receive real-time status updates every step of the way.</p>
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-question" tabindex="0" role="button" aria-expanded="false">
          <span class="faq-num">03</span>
          <h4>Can I track my booking in real time?</h4>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
        </div>
        <div class="faq-divider"></div>
        <div class="faq-answer"><div class="faq-answer-inner">
          <p>Yes. Once your booking is confirmed, head to your personal dashboard to monitor its live status — from provider assignment all the way through job completion.</p>
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-question" tabindex="0" role="button" aria-expanded="false">
          <span class="faq-num">04</span>
          <h4>How can I register as a service provider?</h4>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
        </div>
        <div class="faq-divider"></div>
        <div class="faq-answer"><div class="faq-answer-inner">
          <p>Click "Become a Partner," complete your profile, and go through our quick verification process. Once approved, you can immediately start accepting jobs and growing your client base.</p>
        </div></div>
      </div>

      <div class="faq-item">
        <div class="faq-question" tabindex="0" role="button" aria-expanded="false">
          <span class="faq-num">05</span>
          <h4>Is my payment information secure?</h4>
          <span class="faq-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
        </div>
        <div class="faq-divider"></div>
        <div class="faq-answer"><div class="faq-answer-inner">
          <p>Yes. All transactions are encrypted end-to-end using industry-standard protocols. We never store raw card details — payments are handled by a certified third-party gateway.</p>
        </div></div>
      </div>

    </div>
  </div>
</section>

<script>
  (function() {
    const items = document.querySelectorAll('.faq-item');
    const questions = document.querySelectorAll('.faq-question');

    function closeAll() {
      items.forEach(item => {
        item.classList.remove('active');
        const btn = item.querySelector('.faq-question');
        if (btn) btn.setAttribute('aria-expanded', 'false');
      });
    }

    questions.forEach((btn, index) => {
      btn.setAttribute('aria-expanded', 'false');
      const answer = btn.closest('.faq-item')?.querySelector('.faq-answer');
      if (answer && !answer.id) answer.id = `faq-answer-${index+1}`;
      if (answer) btn.setAttribute('aria-controls', answer.id);

      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const item = this.closest('.faq-item');
        if (!item) return;

        const isActive = item.classList.contains('active');
        closeAll();

        if (!isActive) {
          item.classList.add('active');
          this.setAttribute('aria-expanded', 'true');
        }

        // gentle scroll on small screens if needed
        if (item.classList.contains('active')) {
          setTimeout(() => {
            const rect = item.getBoundingClientRect();
            const isVisible = rect.top >= 0 && rect.bottom <= window.innerHeight;
            if (!isVisible && window.innerWidth < 600) {
              item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
          }, 50);
        }
      });

      btn.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          this.click();
        }
      });
    });
  })();
</script>
</body>
</html>