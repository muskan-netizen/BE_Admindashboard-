<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ServeEase — Premium Home Services On Demand</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;0,9..144,800;1,9..144,400&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    :root {
      --bg: #FAFAF7;
      --surface: #FFFFFF;
      --text: #1B1B18;
      --text-secondary: #7A7A72;
      --text-muted: #A3A39B;
      --accent: #E8A838;
      --accent-warm: #F4C256;
      --accent-deep: #C4872A;
      --accent-light: #FFF8EB;
      --accent-glow: rgba(232, 168, 56, .12);
      --navy: #1A2B4A;
      --navy-light: #243A5E;
      --navy-muted: #374E6F;
      --emerald: #2D8B6F;
      --emerald-light: #E8F5F0;
      --border: #EBEBEB;
      --border-light: #F5F5F2;
      --radius: 16px;
      --radius-sm: 10px;
      --radius-xs: 6px;
      --radius-lg: 24px;
      --radius-xl: 32px;
      --shadow-xs: 0 1px 2px rgba(27,27,24,.04);
      --shadow-sm: 0 2px 8px rgba(27,27,24,.06);
      --shadow-md: 0 8px 30px rgba(27,27,24,.08);
      --shadow-lg: 0 20px 60px rgba(27,27,24,.10);
      --shadow-xl: 0 32px 80px rgba(27,27,24,.14);
      --font: 'Outfit', sans-serif;
      --font-display: 'Fraunces', serif;
      --container: 1240px;
      --nav-h: 72px;
    }

    html { scroll-behavior: smooth; font-size: 16px; -webkit-font-smoothing: antialiased; }
    body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.65; overflow-x: hidden; }
    a { text-decoration: none; color: inherit; }
    img { max-width: 100%; display: block; }
    button { cursor: pointer; border: none; font-family: inherit; background: none; }
    .container { max-width: var(--container); margin: 0 auto; padding: 0 28px; }

    /* ═══════════════ NAVBAR ═══════════════ */
    .nav {
      position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;
      background: rgba(250,250,247,.88); backdrop-filter: blur(24px) saturate(1.4);
      border-bottom: 1px solid transparent; transition: all .35s;
    }
    .nav.scrolled { border-bottom-color: var(--border); box-shadow: var(--shadow-sm); }
    .nav-inner {
      display: flex; align-items: center; justify-content: space-between;
      height: var(--nav-h); max-width: var(--container); margin: 0 auto; padding: 0 28px;
    }
    .logo { display: flex; align-items: center; gap: 10px; font-family: var(--font-display); font-size: 1.4rem; font-weight: 700; color: var(--text); }
    .logo-mark {
      width: 38px; height: 38px; background: var(--accent); border-radius: 10px;
      display: grid; place-items: center; color: #fff; font-family: var(--font);
      font-size: .85rem; font-weight: 800; letter-spacing: -.5px;
    }
    .nav-links { display: flex; align-items: center; gap: 36px; }
    .nav-links a { font-size: .85rem; font-weight: 500; color: var(--text-secondary); transition: color .2s; letter-spacing: .2px; }
    .nav-links a:hover { color: var(--text); }
    .nav-actions { display: flex; align-items: center; gap: 10px; }

    .btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 8px;
      padding: 11px 26px; border-radius: 50px; font-size: .85rem; font-weight: 600;
      transition: all .25s; letter-spacing: .2px;
    }
    .btn-ghost { background: transparent; color: var(--text-secondary); }
    .btn-ghost:hover { color: var(--text); background: var(--border-light); }
    .btn-primary { background: var(--navy); color: #fff; }
    .btn-primary:hover { background: var(--navy-light); transform: translateY(-1px); box-shadow: 0 6px 24px rgba(26,43,74,.25); }
    .btn-accent { background: var(--accent); color: var(--navy); font-weight: 700; }
    .btn-accent:hover { background: var(--accent-warm); transform: translateY(-1px); box-shadow: 0 6px 24px rgba(232,168,56,.35); }
    .btn-outline { border: 1.5px solid var(--border); background: var(--surface); color: var(--text); }
    .btn-outline:hover { border-color: var(--accent); color: var(--accent-deep); }
    .btn-white { background: #fff; color: var(--navy); font-weight: 700; }
    .btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(0,0,0,.18); }
    .btn-sm { padding: 9px 20px; font-size: .8rem; }
    .btn-lg { padding: 16px 36px; font-size: .95rem; }

    .hamburger { display: none; flex-direction: column; gap: 5px; padding: 6px; }
    .hamburger span { display: block; width: 22px; height: 2px; background: var(--text); border-radius: 2px; transition: all .3s; }

    /* ═══════════════ HERO ═══════════════ */
    .hero {
      padding: 140px 0 100px;
      position: relative;
      background: linear-gradient(170deg, var(--bg) 0%, #FFF8EB 40%, var(--bg) 100%);
      overflow: hidden;
    }
    .hero::before {
      content: ''; position: absolute; top: -200px; right: -150px;
      width: 700px; height: 700px;
      background: radial-gradient(circle, rgba(232,168,56,.1) 0%, transparent 65%);
      pointer-events: none;
    }
    .hero::after {
      content: ''; position: absolute; bottom: -100px; left: -80px;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(45,139,111,.06) 0%, transparent 60%);
      pointer-events: none;
    }
    .hero .container {
      display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center;
    }
    .hero-eyebrow {
      display: inline-flex; align-items: center; gap: 8px;
      background: var(--accent-light); border: 1px solid rgba(232,168,56,.18);
      color: var(--accent-deep); padding: 7px 18px; border-radius: 50px;
      font-size: .76rem; font-weight: 600; margin-bottom: 24px; letter-spacing: .4px;
    }
    .hero-eyebrow .dot { width: 6px; height: 6px; background: var(--accent); border-radius: 50%; animation: blink 2s infinite; }
    @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

    .hero h1 {
      font-family: var(--font-display); font-size: 3.8rem; line-height: 1.1;
      font-weight: 800; letter-spacing: -.03em; margin-bottom: 22px; color: var(--navy);
    }
    .hero h1 em { font-style: italic; color: var(--accent-deep); }
    .hero-desc {
      font-size: 1.1rem; color: var(--text-secondary); max-width: 480px;
      margin-bottom: 36px; line-height: 1.75;
    }
    .hero-search {
      display: flex; background: var(--surface); border: 1.5px solid var(--border);
      border-radius: 60px; padding: 5px; box-shadow: var(--shadow-md); max-width: 540px;
    }
    .hero-search input {
      flex: 1; border: none; outline: none; padding: 14px 22px;
      font-size: .92rem; background: transparent; font-family: var(--font); color: var(--text);
    }
    .hero-search input::placeholder { color: var(--text-muted); }
    .hero-search .btn { padding: 14px 30px; border-radius: 50px; }

    .hero-trust {
      display: flex; align-items: center; gap: 32px; margin-top: 44px;
      padding-top: 32px; border-top: 1px solid var(--border);
    }
    .trust-item { text-align: left; }
    .trust-item strong { display: block; font-size: 1.55rem; font-weight: 800; color: var(--navy); letter-spacing: -.02em; }
    .trust-item span { font-size: .78rem; color: var(--text-muted); font-weight: 500; }

    .hero-visual { position: relative; }
    .hero-img {
      border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-xl);
      aspect-ratio: 4/5; position: relative;
    }
    .hero-img img { width: 100%; height: 100%; object-fit: cover; }
    .hero-img::after {
      content: ''; position: absolute; inset: 0; border-radius: var(--radius-xl);
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.1);
    }
    .hero-card {
      position: absolute; background: var(--surface); border-radius: var(--radius);
      padding: 16px 20px; box-shadow: var(--shadow-lg); display: flex; align-items: center; gap: 14px;
      animation: heroFloat 5s ease-in-out infinite; border: 1px solid var(--border-light);
    }
    @keyframes heroFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
    .hero-card-1 { top: 16%; left: -36px; animation-delay: 0s; }
    .hero-card-2 { bottom: 18%; right: -28px; animation-delay: 2s; }
    .hero-card .card-emoji { font-size: 1.5rem; }
    .hero-card .card-info strong { display: block; font-size: .82rem; font-weight: 700; }
    .hero-card .card-info span { font-size: .72rem; color: var(--text-muted); }

    /* ═══════════════ TICKER ═══════════════ */
    .ticker {
      background: var(--navy); padding: 16px 0; overflow: hidden;
    }
    .ticker-track {
      display: flex; gap: 56px; animation: scroll-ticker 28s linear infinite; width: max-content;
    }
    @keyframes scroll-ticker { from{transform:translateX(0)} to{transform:translateX(-50%)} }
    .ticker-item {
      display: flex; align-items: center; gap: 10px; white-space: nowrap;
      font-size: .82rem; font-weight: 600; color: rgba(255,255,255,.85);
    }
    .ticker-item .sep { width: 4px; height: 4px; background: var(--accent); border-radius: 50%; }

    /* ═══════════════ CATEGORIES ═══════════════ */
    .categories { padding: 100px 0 80px; }
    .section-eyebrow {
      display: inline-flex; align-items: center; gap: 8px;
      font-size: .72rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: 2px; color: var(--accent-deep); margin-bottom: 14px;
    }
    .section-eyebrow::before { content: ''; width: 20px; height: 2px; background: var(--accent); border-radius: 2px; }
    .section-title {
      font-family: var(--font-display); font-size: 2.6rem; font-weight: 700;
      margin-bottom: 14px; letter-spacing: -.02em; color: var(--navy);
    }
    .section-desc { color: var(--text-secondary); font-size: 1rem; max-width: 520px; margin-bottom: 52px; line-height: 1.7; }
    .section-header-row { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 52px; }

    .cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    .cat-card {
      background: var(--surface); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 30px 22px; text-align: center;
      transition: all .35s; cursor: pointer; position: relative; overflow: hidden;
    }
    .cat-card::after {
      content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
      background: var(--accent); transform: scaleX(0); transition: transform .35s; transform-origin: left;
    }
    .cat-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: var(--accent-light); }
    .cat-card:hover::after { transform: scaleX(1); }
    .cat-icon {
      width: 64px; height: 64px; background: var(--accent-light); border-radius: 16px;
      display: grid; place-items: center; margin: 0 auto 16px; font-size: 1.7rem; transition: all .3s;
    }
    .cat-card:hover .cat-icon { background: var(--accent); transform: scale(1.08); }
    .cat-card h3 { font-size: .92rem; font-weight: 700; margin-bottom: 4px; color: var(--navy); }
    .cat-card p { font-size: .78rem; color: var(--text-muted); }
    .cat-card .cat-arrow {
      display: inline-flex; align-items: center; gap: 4px; font-size: .75rem; font-weight: 600;
      color: var(--accent-deep); margin-top: 12px; opacity: 0; transform: translateY(6px); transition: all .3s;
    }
    .cat-card:hover .cat-arrow { opacity: 1; transform: translateY(0); }

    /* ═══════════════ VIDEO SHOWCASE ═══════════════ */
    .video-showcase {
      padding: 100px 0;
      background: linear-gradient(180deg, var(--bg) 0%, #F5F2ED 100%);
    }
    .video-grid {
      display: grid; grid-template-columns: 1.15fr 1fr; gap: 64px; align-items: center;
    }
    .video-frame {
      position: relative; border-radius: var(--radius-lg); overflow: hidden;
      box-shadow: var(--shadow-xl); border: 1px solid var(--border);
    }
    .video-frame iframe { width: 100%; height: 380px; display: block; }
    .video-float-badge {
      position: absolute; bottom: -14px; left: 24px;
      background: var(--accent); color: var(--navy); padding: 10px 22px;
      border-radius: 50px; font-size: .8rem; font-weight: 700; box-shadow: var(--shadow-md);
      display: flex; align-items: center; gap: 8px;
    }
    .video-info .check-list { display: flex; flex-direction: column; gap: 14px; margin: 28px 0 36px; }
    .check-item {
      display: flex; align-items: center; gap: 12px; font-size: .92rem; color: var(--text-secondary);
    }
    .check-dot {
      width: 28px; height: 28px; background: var(--emerald-light); border-radius: 50%;
      display: grid; place-items: center; flex-shrink: 0; color: var(--emerald); font-size: .7rem; font-weight: 800;
    }

    /* ═══════════════ HOW IT WORKS ═══════════════ */
    .how-it-works { padding: 100px 0; }
    .how-it-works .container { text-align: center; }
    .steps-row {
      display: grid; grid-template-columns: repeat(4, 1fr); gap: 28px; margin-top: 60px;
      position: relative;
    }
    .steps-row::before {
      content: ''; position: absolute; top: 44px; left: 12%; right: 12%;
      height: 2px; background: repeating-linear-gradient(90deg, var(--accent) 0, var(--accent) 6px, transparent 6px, transparent 14px);
      opacity: .25;
    }
    .step-card { text-align: center; position: relative; }
    .step-num {
      width: 56px; height: 56px; border-radius: 50%; display: grid; place-items: center;
      margin: 0 auto 24px; font-size: 1rem; font-weight: 800; position: relative; z-index: 2;
      background: var(--navy); color: var(--accent); box-shadow: 0 4px 20px rgba(26,43,74,.2);
    }
    .step-card h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 8px; color: var(--navy); }
    .step-card p { font-size: .85rem; color: var(--text-secondary); max-width: 240px; margin: 0 auto; line-height: 1.7; }

    /* ═══════════════ ROADMAP / PROCESS ═══════════════ */
    .process-section {
      padding: 100px 0;
      background: var(--navy);
      position: relative; overflow: hidden;
    }
    .process-section::before {
      content: ''; position: absolute; top: -200px; right: -100px;
      width: 500px; height: 500px;
      background: radial-gradient(circle, rgba(232,168,56,.08) 0%, transparent 60%);
    }
    .process-section .section-eyebrow { color: var(--accent); }
    .process-section .section-eyebrow::before { background: var(--accent); }
    .process-section .section-title { color: #fff; }
    .process-section .section-desc { color: rgba(255,255,255,.5); }
    .process-timeline {
      display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 52px;
    }
    .timeline-card {
      background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
      border-radius: var(--radius); padding: 32px 28px; transition: all .35s;
      display: flex; gap: 20px; align-items: flex-start;
    }
    .timeline-card:hover { background: rgba(255,255,255,.08); border-color: var(--accent); transform: translateY(-4px); }
    .timeline-num {
      width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
      background: rgba(232,168,56,.12); display: grid; place-items: center;
      font-size: .85rem; font-weight: 800; color: var(--accent);
    }
    .timeline-card h4 { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 6px; }
    .timeline-card p { font-size: .84rem; color: rgba(255,255,255,.45); line-height: 1.7; }

    /* ═══════════════ WHY US ═══════════════ */
    .why-us {
      padding: 100px 0;
      background: linear-gradient(180deg, var(--bg) 0%, #F0EDE6 100%);
    }
    .why-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
    .why-card {
      background: var(--surface); border: 1px solid var(--border);
      border-radius: var(--radius); padding: 36px 28px; transition: all .35s;
      position: relative; overflow: hidden;
    }
    .why-card::before {
      content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
      background: linear-gradient(90deg, var(--accent), var(--emerald));
      transform: scaleX(0); transition: transform .4s; transform-origin: left;
    }
    .why-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); }
    .why-card:hover::before { transform: scaleX(1); }
    .why-icon {
      width: 52px; height: 52px; background: var(--accent-light); border-radius: 14px;
      display: grid; place-items: center; font-size: 1.4rem; margin-bottom: 20px;
    }
    .why-card h3 { font-size: .98rem; font-weight: 700; color: var(--navy); margin-bottom: 10px; }
    .why-card p { font-size: .84rem; color: var(--text-secondary); line-height: 1.75; }

    /* ═══════════════ FEATURED ═══════════════ */
    .featured { padding: 100px 0; }
    .featured-layout {
      display: grid; grid-template-columns: 1.4fr 1fr; gap: 24px; margin-top: 0;
    }
    .featured-hero {
      position: relative; border-radius: var(--radius-lg); overflow: hidden;
      min-height: 480px; cursor: pointer;
    }
    .featured-hero > img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s; }
    .featured-hero:hover > img { transform: scale(1.04); }
    .featured-hero-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(to top, rgba(0,0,0,.82) 0%, rgba(0,0,0,.15) 50%, transparent 100%);
      display: flex; flex-direction: column; justify-content: flex-end; padding: 36px;
    }
    .featured-tag {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--accent); color: var(--navy); padding: 5px 14px;
      border-radius: 50px; font-size: .72rem; font-weight: 800;
      margin-bottom: 14px; width: fit-content; letter-spacing: .4px;
    }
    .featured-hero-overlay h3 {
      font-family: var(--font-display); font-size: 1.7rem; font-weight: 700;
      color: #fff; margin-bottom: 8px; line-height: 1.2;
    }
    .featured-hero-overlay p { font-size: .86rem; color: rgba(255,255,255,.7); margin-bottom: 20px; max-width: 380px; line-height: 1.6; }
    .featured-price { font-size: 1.4rem; font-weight: 800; color: var(--accent); }
    .featured-price span { font-size: .78rem; font-weight: 400; color: rgba(255,255,255,.5); }

    .featured-stack { display: flex; flex-direction: column; gap: 16px; }
    .featured-mini {
      background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
      overflow: hidden; display: flex; transition: all .3s; cursor: pointer;
    }
    .featured-mini:hover { transform: translateX(6px); box-shadow: var(--shadow-md); border-color: var(--accent); }
    .featured-mini img { width: 120px; height: 120px; object-fit: cover; flex-shrink: 0; }
    .featured-mini-body { padding: 16px 18px; display: flex; flex-direction: column; justify-content: center; }
    .featured-mini-body h4 { font-size: .92rem; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
    .featured-mini-body p { font-size: .76rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 10px; }
    .featured-mini-footer { display: flex; align-items: center; gap: 12px; }
    .mini-price { font-size: .98rem; font-weight: 800; color: var(--accent-deep); }
    .mini-rating {
      display: inline-flex; align-items: center; gap: 3px; background: #FFF8EB;
      color: var(--accent-deep); padding: 3px 10px; border-radius: 50px; font-size: .7rem; font-weight: 700;
    }

    /* ═══════════════ POPULAR SERVICES ═══════════════ */
    .popular { padding: 100px 0; background: var(--bg); }
    .services-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
    .service-card {
      background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
      overflow: hidden; transition: all .35s;
    }
    .service-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: transparent; }
    .service-img { position: relative; overflow: hidden; aspect-ratio: 16/10; }
    .service-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s; }
    .service-card:hover .service-img img { transform: scale(1.06); }
    .service-badge {
      position: absolute; top: 14px; left: 14px;
      background: rgba(255,255,255,.94); backdrop-filter: blur(8px);
      padding: 5px 14px; border-radius: 50px; font-size: .7rem; font-weight: 700; color: var(--accent-deep);
    }
    .service-rating {
      position: absolute; top: 14px; right: 14px;
      background: rgba(26,43,74,.85); backdrop-filter: blur(8px);
      padding: 5px 12px; border-radius: 50px; font-size: .74rem; font-weight: 700; color: #fff;
      display: flex; align-items: center; gap: 4px;
    }
    .service-rating::before { content: '★'; color: var(--accent); }
    .service-body { padding: 22px; }
    .service-body h3 { font-size: 1rem; font-weight: 700; margin-bottom: 6px; color: var(--navy); }
    .service-body .desc { font-size: .82rem; color: var(--text-secondary); margin-bottom: 16px; line-height: 1.65; }
    .service-footer { display: flex; justify-content: space-between; align-items: center; }
    .service-price { font-size: 1.15rem; font-weight: 800; color: var(--navy); }
    .service-price span { font-size: .76rem; font-weight: 400; color: var(--text-muted); }

    /* ═══════════════ PARTNER APP ═══════════════ */
    .partner-app {
      padding: 100px 0;
      background: linear-gradient(135deg, #1A2B4A 0%, #243A5E 60%, #2D4A7A 100%);
      position: relative; overflow: hidden;
    }
    .partner-app::before {
      content: ''; position: absolute; top: -100px; left: -100px;
      width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(232,168,56,.1), transparent 60%);
    }
    .partner-grid {
      display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center;
    }
    .partner-info .section-eyebrow { color: var(--accent); }
    .partner-info .section-eyebrow::before { background: var(--accent); }
    .partner-info .section-title { color: #fff; font-size: 2.4rem; }
    .partner-info .section-desc { color: rgba(255,255,255,.55); margin-bottom: 32px; }
    .partner-features { display: flex; flex-direction: column; gap: 20px; margin-bottom: 36px; }
    .pf-item {
      display: flex; gap: 16px; align-items: flex-start;
    }
    .pf-icon {
      width: 44px; height: 44px; background: rgba(232,168,56,.12); border-radius: 12px;
      display: grid; place-items: center; flex-shrink: 0; font-size: 1.1rem;
    }
    .pf-item h4 { font-size: .92rem; font-weight: 700; color: #fff; margin-bottom: 3px; }
    .pf-item p { font-size: .82rem; color: rgba(255,255,255,.45); line-height: 1.6; }
    .app-badges { display: flex; gap: 12px; flex-wrap: wrap; }
    .app-badge {
      display: inline-flex; align-items: center; gap: 10px;
      background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
      border-radius: var(--radius-sm); padding: 12px 20px; transition: all .3s;
    }
    .app-badge:hover { background: rgba(255,255,255,.14); border-color: var(--accent); }
    .app-badge .badge-icon { font-size: 1.3rem; }
    .app-badge .badge-text { font-size: .7rem; color: rgba(255,255,255,.5); line-height: 1.2; }
    .app-badge .badge-text strong { display: block; font-size: .88rem; color: #fff; font-weight: 700; }

    .partner-visual { position: relative; display: flex; justify-content: center; }
    .phone-mockup {
      width: 280px; height: 560px; background: #111; border-radius: 36px;
      box-shadow: 0 40px 80px rgba(0,0,0,.4); padding: 12px; position: relative;
      border: 2px solid rgba(255,255,255,.1);
    }
    .phone-screen {
      width: 100%; height: 100%; background: linear-gradient(180deg, #1A2B4A, #243A5E);
      border-radius: 26px; overflow: hidden; display: flex; flex-direction: column;
      align-items: center; justify-content: center; gap: 16px; padding: 20px;
    }
    .phone-screen .ps-logo { font-family: var(--font-display); font-size: 1.2rem; color: #fff; font-weight: 700; }
    .phone-screen .ps-sub { font-size: .7rem; color: rgba(255,255,255,.4); text-align: center; }
    .phone-screen .ps-icon { font-size: 3rem; margin: 20px 0; }
    .phone-screen .ps-btn {
      background: var(--accent); color: var(--navy); padding: 10px 24px; border-radius: 50px;
      font-size: .8rem; font-weight: 700;
    }
    .phone-float {
      position: absolute; background: var(--surface); border-radius: var(--radius-sm);
      padding: 12px 16px; box-shadow: var(--shadow-lg); display: flex; align-items: center; gap: 10px;
      animation: heroFloat 5s ease-in-out infinite;
    }
    .phone-float-1 { top: 20%; left: -40px; animation-delay: 0s; }
    .phone-float-2 { bottom: 25%; right: -40px; animation-delay: 1.8s; }
    .phone-float .pfl-text { font-size: .75rem; font-weight: 600; color: var(--navy); }
    .phone-float .pfl-sub { font-size: .65rem; color: var(--text-muted); }

    /* ═══════════════ VIDEO 2 ═══════════════ */
    .video-section-2 {
      padding: 100px 0;
      background: var(--bg);
    }
    .video-grid-2 {
      display: grid; grid-template-columns: 1fr 1.15fr; gap: 64px; align-items: center;
    }

    /* ═══════════════ STATS BAND ═══════════════ */
    .stats-band { padding: 80px 0; background: var(--surface); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px; text-align: center; }
    .stat-block {}
    .stat-block strong { display: block; font-family: var(--font-display); font-size: 2.8rem; font-weight: 800; color: var(--navy); letter-spacing: -.02em; }
    .stat-block span { font-size: .85rem; color: var(--text-muted); font-weight: 500; }

    /* ═══════════════ TESTIMONIALS ═══════════════ */
    .testimonials {
      padding: 100px 0;
      background: linear-gradient(180deg, #F5F2ED 0%, var(--bg) 100%);
    }
    .testimonials .container { text-align: center; }
    .test-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; margin-top: 52px; text-align: left; }
    .test-card {
      background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius);
      padding: 32px 28px; transition: all .35s; position: relative;
    }
    .test-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
    .test-card::before {
      content: '"'; position: absolute; top: 16px; right: 24px;
      font-family: var(--font-display); font-size: 4rem; color: var(--accent-light); line-height: 1;
    }
    .test-stars { color: var(--accent); font-size: .85rem; margin-bottom: 16px; letter-spacing: 2px; }
    .test-card .quote { font-size: .9rem; color: var(--text-secondary); line-height: 1.8; margin-bottom: 24px; font-style: italic; }
    .test-author { display: flex; align-items: center; gap: 14px; }
    .test-avatar { width: 46px; height: 46px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent-light); }
    .test-name { font-weight: 700; font-size: .88rem; color: var(--navy); }
    .test-role { font-size: .75rem; color: var(--text-muted); }

    /* ═══════════════ COVERAGE ═══════════════ */
    .coverage { padding: 100px 0; }
    .coverage .container { text-align: center; }
    .city-pills {
      display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 40px;
    }
    .city-pill {
      background: var(--surface); border: 1px solid var(--border); border-radius: 50px;
      padding: 10px 22px; font-size: .85rem; font-weight: 600; color: var(--navy);
      transition: all .25s; cursor: pointer;
    }
    .city-pill:hover { background: var(--accent-light); border-color: var(--accent); color: var(--accent-deep); }
    .city-pill.active { background: var(--navy); color: #fff; border-color: var(--navy); }

    /* ═══════════════ FAQ ═══════════════ */
    .faq { padding: 100px 0; background: var(--surface); }
    .faq .container { max-width: 780px; }
    .faq-list { margin-top: 44px; display: flex; flex-direction: column; gap: 10px; }
    .faq-item { border: 1px solid var(--border); border-radius: var(--radius-sm); overflow: hidden; transition: border-color .3s; }
    .faq-item.active { border-color: var(--accent); }
    .faq-q {
      display: flex; justify-content: space-between; align-items: center;
      padding: 22px 24px; cursor: pointer; font-weight: 700; font-size: .92rem;
      background: var(--surface); transition: background .2s; color: var(--navy);
    }
    .faq-q:hover { background: var(--accent-light); }
    .faq-q .icon {
      width: 30px; height: 30px; border-radius: 50%; background: var(--accent-light);
      color: var(--accent-deep); display: grid; place-items: center; font-size: 1.1rem;
      transition: all .3s; flex-shrink: 0; font-weight: 400;
    }
    .faq-item.active .faq-q .icon { background: var(--accent); color: #fff; transform: rotate(45deg); }
    .faq-a { max-height: 0; overflow: hidden; transition: max-height .4s ease; }
    .faq-a-inner { padding: 0 24px 22px; font-size: .88rem; color: var(--text-secondary); line-height: 1.8; }

    /* ═══════════════ CTA BANNER ═══════════════ */
    .cta { padding: 100px 0; }
    .cta-box {
      background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 50%, #2D4A7A 100%);
      border-radius: var(--radius-xl); padding: 72px; display: flex; align-items: center;
      justify-content: space-between; gap: 48px; position: relative; overflow: hidden;
    }
    .cta-box::before {
      content: ''; position: absolute; top: -80px; right: -60px;
      width: 350px; height: 350px; background: rgba(232,168,56,.08); border-radius: 50%;
    }
    .cta-box::after {
      content: ''; position: absolute; bottom: -60px; left: 25%;
      width: 200px; height: 200px; background: rgba(255,255,255,.03); border-radius: 50%;
    }
    .cta-box > * { position: relative; z-index: 1; }
    .cta-text h2 { font-family: var(--font-display); font-size: 2.4rem; color: #fff; margin-bottom: 14px; }
    .cta-text p { color: rgba(255,255,255,.6); font-size: 1rem; max-width: 420px; line-height: 1.7; }

    /* ═══════════════ FOOTER ═══════════════ */
    .footer { background: #111; color: rgba(255,255,255,.6); padding: 72px 0 0; }
    .footer-grid { display: grid; grid-template-columns: 1.6fr 1fr 1fr 1fr; gap: 48px; padding-bottom: 48px; border-bottom: 1px solid rgba(255,255,255,.07); }
    .footer-brand .logo { color: #fff; margin-bottom: 18px; }
    .footer-brand p { font-size: .84rem; line-height: 1.8; max-width: 300px; }
    .footer-col h4 { color: #fff; font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 22px; }
    .footer-col a { display: block; font-size: .84rem; padding: 5px 0; transition: color .2s; }
    .footer-col a:hover { color: var(--accent); }
    .footer-bottom { display: flex; justify-content: space-between; align-items: center; padding: 24px 0; font-size: .78rem; }
    .footer-socials { display: flex; gap: 10px; }
    .footer-socials a {
      width: 38px; height: 38px; border-radius: 50%; border: 1px solid rgba(255,255,255,.1);
      display: grid; place-items: center; font-size: .82rem; transition: all .3s;
    }
    .footer-socials a:hover { background: var(--accent); border-color: var(--accent); color: #fff; }

    /* ═══════════════ MOBILE MENU ═══════════════ */
    .mobile-menu {
      display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 999;
    }
    .mobile-menu-inner {
      position: absolute; right: 0; top: 0; bottom: 0; width: 300px; background: #fff;
      padding: 80px 28px 28px; display: flex; flex-direction: column; gap: 6px;
      transform: translateX(100%); transition: transform .35s;
    }
    .mobile-menu.open { display: block; }
    .mobile-menu.open .mobile-menu-inner { transform: translateX(0); }
    .mobile-menu a { padding: 14px 0; font-size: .95rem; font-weight: 500; border-bottom: 1px solid var(--border); color: var(--navy); }
    .mobile-close { position: absolute; top: 20px; right: 20px; background: none; font-size: 1.6rem; color: var(--text); }

    /* ═══════════════ SCROLL ANIMATIONS ═══════════════ */
    .reveal {
      opacity: 0; transform: translateY(28px); transition: all .7s cubic-bezier(.16,1,.3,1);
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .reveal-delay-1 { transition-delay: .1s; }
    .reveal-delay-2 { transition-delay: .2s; }
    .reveal-delay-3 { transition-delay: .3s; }

    /* ═══════════════ RESPONSIVE ═══════════════ */
    @media(max-width:1024px) {
      .hero .container { grid-template-columns: 1fr; }
      .hero-visual { display: none; }
      .hero h1 { font-size: 3rem; }
      .cat-grid { grid-template-columns: repeat(2, 1fr); }
      .services-grid { grid-template-columns: repeat(2, 1fr); }
      .test-grid { grid-template-columns: 1fr 1fr; }
      .footer-grid { grid-template-columns: 1fr 1fr; }
      .video-grid, .video-grid-2 { grid-template-columns: 1fr; }
      .video-info { order: -1; }
      .steps-row { grid-template-columns: repeat(2, 1fr); }
      .steps-row::before { display: none; }
      .featured-layout { grid-template-columns: 1fr; }
      .featured-hero { min-height: 340px; }
      .partner-grid { grid-template-columns: 1fr; }
      .partner-visual { order: -1; margin-bottom: 20px; }
      .process-timeline { grid-template-columns: 1fr; }
      .why-grid { grid-template-columns: repeat(2, 1fr); }
      .stats-row { grid-template-columns: repeat(2, 1fr); gap: 40px; }
      .cta-box { flex-direction: column; text-align: center; padding: 52px 36px; }
      .cta-text p { margin: 0 auto; }
      .section-title { font-size: 2.2rem; }
    }
    @media(max-width:768px) {
      .nav-links { display: none; }
      .hamburger { display: flex; }
      .hero { padding: 120px 0 70px; }
      .hero h1 { font-size: 2.4rem; }
      .hero-search { flex-direction: column; border-radius: var(--radius); }
      .hero-search .btn { border-radius: 10px; }
      .hero-trust { gap: 20px; flex-wrap: wrap; }
      .cat-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
      .services-grid { grid-template-columns: 1fr; }
      .test-grid { grid-template-columns: 1fr; }
      .steps-row { grid-template-columns: 1fr; }
      .why-grid { grid-template-columns: 1fr; }
      .stats-row { grid-template-columns: 1fr 1fr; gap: 32px; }
      .footer-grid { grid-template-columns: 1fr; gap: 28px; }
      .footer-bottom { flex-direction: column; gap: 16px; text-align: center; }
      .partner-features { gap: 16px; }
      .video-frame iframe { height: 260px; }
      .section-header-row { flex-direction: column; align-items: flex-start; gap: 16px; }
    }
    @media(max-width:480px) {
      .cat-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
      .cat-card { padding: 20px 14px; }
      .cat-icon { width: 52px; height: 52px; font-size: 1.4rem; }
      .featured-mini { flex-direction: column; }
      .featured-mini img { width: 100%; height: 160px; }
      .phone-mockup { width: 240px; height: 480px; }
      .phone-float { display: none; }
    }
  </style>
</head>
<body>

<!-- ═══════════ NAVBAR ═══════════ -->
<nav class="nav" id="nav">
  <div class="nav-inner">
    <a href="#" class="logo"><span class="logo-mark">SE</span> ServeEase</a>
    <div class="nav-links">
      <a href="#services">Services</a>
      <a href="#how">How It Works</a>
      <a href="#why">Why Us</a>
      <a href="#featured">Featured</a>
      <a href="#popular">Popular</a>
      <a href="#partner">Partner App</a>
      <a href="#reviews">Reviews</a>
      <a href="#faq">FAQ</a>
    </div>
    <div class="nav-actions">
      <a href="#" class="btn btn-ghost">Log In</a>
      <a href="#" class="btn btn-primary">Book Now</a>
    </div>
    <button class="hamburger" onclick="document.getElementById('mob').classList.add('open')">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- MOBILE MENU -->
<div class="mobile-menu" id="mob" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="mobile-menu-inner">
    <button class="mobile-close" onclick="document.getElementById('mob').classList.remove('open')">&times;</button>
    <a href="#services" onclick="document.getElementById('mob').classList.remove('open')">Services</a>
    <a href="#how" onclick="document.getElementById('mob').classList.remove('open')">How It Works</a>
    <a href="#why" onclick="document.getElementById('mob').classList.remove('open')">Why Us</a>
    <a href="#featured" onclick="document.getElementById('mob').classList.remove('open')">Featured</a>
    <a href="#popular" onclick="document.getElementById('mob').classList.remove('open')">Popular</a>
    <a href="#partner" onclick="document.getElementById('mob').classList.remove('open')">Partner App</a>
    <a href="#reviews" onclick="document.getElementById('mob').classList.remove('open')">Reviews</a>
    <a href="#faq" onclick="document.getElementById('mob').classList.remove('open')">FAQ</a>
    <a href="#" class="btn btn-accent" style="margin-top:20px;text-align:center;border-radius:12px">Book a Service</a>
  </div>
</div>

<!-- ═══════════ HERO ═══════════ -->
<section class="hero">
  <div class="container">
    <div class="hero-content">
      <div class="hero-eyebrow"><span class="dot"></span> Now serving 50+ cities across India</div>
      <h1>Expert Home<br>Services, <em>On Demand</em></h1>
      <p class="hero-desc">From plumbing to deep cleaning — book verified professionals in 60 seconds. Transparent pricing, real-time tracking, and guaranteed satisfaction.</p>
      <div class="hero-search">
        <input type="text" placeholder="What service do you need today?">
        <button class="btn btn-accent">Search</button>
      </div>
      <div class="hero-trust">
        <div class="trust-item"><strong>2M+</strong><span>Bookings Done</span></div>
        <div class="trust-item"><strong>50K+</strong><span>Verified Pros</span></div>
        <div class="trust-item"><strong>4.9 ★</strong><span>Avg Rating</span></div>
        <div class="trust-item"><strong>50+</strong><span>Cities</span></div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="hero-img">
        <img src="https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=600&h=750&fit=crop" alt="Professional Service">
      </div>
      <div class="hero-card hero-card-1">
        <span class="card-emoji">✅</span>
        <div class="card-info"><strong>Booking Confirmed</strong><span>Electrician · 2:30 PM</span></div>
      </div>
      <div class="hero-card hero-card-2">
        <span class="card-emoji">⭐</span>
        <div class="card-info"><strong>Rated 5.0</strong><span>Deep Cleaning Service</span></div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════ TICKER ═══════════ -->
<div class="ticker">
  <div class="ticker-track">
    <div class="ticker-item">✅ 2M+ Bookings Completed <span class="sep"></span></div>
    <div class="ticker-item">⭐ 4.9 Average Rating <span class="sep"></span></div>
    <div class="ticker-item">🔒 100% Verified Professionals <span class="sep"></span></div>
    <div class="ticker-item">🏙️ 50+ Cities Covered <span class="sep"></span></div>
    <div class="ticker-item">💰 Transparent Pricing <span class="sep"></span></div>
    <div class="ticker-item">🔄 Free Re-service Guarantee <span class="sep"></span></div>
    <div class="ticker-item">⚡ 60-Second Booking <span class="sep"></span></div>
    <div class="ticker-item">📍 Real-time Tracking <span class="sep"></span></div>
    <div class="ticker-item">✅ 2M+ Bookings Completed <span class="sep"></span></div>
    <div class="ticker-item">⭐ 4.9 Average Rating <span class="sep"></span></div>
    <div class="ticker-item">🔒 100% Verified Professionals <span class="sep"></span></div>
    <div class="ticker-item">🏙️ 50+ Cities Covered <span class="sep"></span></div>
    <div class="ticker-item">💰 Transparent Pricing <span class="sep"></span></div>
    <div class="ticker-item">🔄 Free Re-service Guarantee <span class="sep"></span></div>
    <div class="ticker-item">⚡ 60-Second Booking <span class="sep"></span></div>
    <div class="ticker-item">📍 Real-time Tracking <span class="sep"></span></div>
  </div>
</div>

<!-- ═══════════ CATEGORIES ═══════════ -->
<section class="categories" id="services">
  <div class="container">
    <div class="section-header-row">
      <div>
        <div class="section-eyebrow">Our Services</div>
        <h2 class="section-title reveal">What Do You Need?</h2>
        <p class="section-desc reveal">Browse verified professionals across all home service categories — available in your city right now.</p>
      </div>
      <a href="#" class="btn btn-outline">View All Services →</a>
    </div>
    <div class="cat-grid">
      <div class="cat-card reveal"><div class="cat-icon">🔧</div><h3>Plumbing</h3><p>Leaks, fittings & pipes</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">⚡</div><h3>Electrician</h3><p>Wiring, repairs & setup</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">🧹</div><h3>Deep Cleaning</h3><p>Home & office cleaning</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">🪵</div><h3>Carpenter</h3><p>Furniture & woodwork</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">👨‍🍳</div><h3>Chef</h3><p>Personal & party chefs</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">🐛</div><h3>Pest Control</h3><p>Safe & effective treatment</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">❄️</div><h3>AC & Appliance</h3><p>Service & installation</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">🤝</div><h3>Helpers & Staff</h3><p>Waiters, helpers & more</p><span class="cat-arrow">Explore →</span></div>
    </div>
  </div>
</section>



<section class="categories" id="services">
  <div class="container">
    <div class="section-header-row">
      <div>
        <div class="section-eyebrow">Our Services</div>
        <h2 class="section-title reveal">What Do You Need?</h2>
        <p class="section-desc reveal">Browse verified professionals across all home service categories — available in your city right now.</p>
      </div>
      <a href="#" class="btn btn-outline">View All Services →</a>
    </div>
    <div class="cat-grid">
      <div class="cat-card reveal"><div class="cat-icon">🔧</div><h3>Plumbing</h3><p>Leaks, fittings & pipes</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">⚡</div><h3>Electrician</h3><p>Wiring, repairs & setup</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">🧹</div><h3>Deep Cleaning</h3><p>Home & office cleaning</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">🪵</div><h3>Carpenter</h3><p>Furniture & woodwork</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">👨‍🍳</div><h3>Chef</h3><p>Personal & party chefs</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">🐛</div><h3>Pest Control</h3><p>Safe & effective treatment</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">❄️</div><h3>AC & Appliance</h3><p>Service & installation</p><span class="cat-arrow">Explore →</span></div>
      <div class="cat-card reveal"><div class="cat-icon">🤝</div><h3>Helpers & Staff</h3><p>Waiters, helpers & more</p><span class="cat-arrow">Explore →</span></div>
    </div>
  </div>
</section>






<!-- ═══════════ VIDEO SHOWCASE ═══════════ -->
<section class="video-showcase">
  <div class="container">
    <div class="video-grid">
      <div class="video-frame reveal">
        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="ServeEase Demo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <div class="video-float-badge">▶ Watch Demo</div>
      </div>
      <div class="video-info reveal">
        <div class="section-eyebrow">See It In Action</div>
        <h2 class="section-title">How ServeEase Works</h2>
        <p class="section-desc">Watch how easy it is to book trusted home services in just a few clicks. Real professionals, real results.</p>
        <div class="check-list">
          <div class="check-item"><span class="check-dot">✓</span> Book in under 60 seconds</div>
          <div class="check-item"><span class="check-dot">✓</span> Verified professionals only</div>
          <div class="check-item"><span class="check-dot">✓</span> Real-time service tracking</div>
          <div class="check-item"><span class="check-dot">✓</span> Satisfaction guaranteed or refund</div>
        </div>
        <a href="#" class="btn btn-accent btn-lg">Get Started →</a>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════ HOW IT WORKS ═══════════ -->
<section class="how-it-works" id="how">
  <div class="container">
    <div class="section-eyebrow">Simple Process</div>
    <h2 class="section-title reveal">Book a Service in 4 Easy Steps</h2>
    <p class="section-desc reveal" style="margin-left:auto;margin-right:auto;text-align:center">No hidden fees, no hassle. From search to satisfaction in minutes.</p>
    <div class="steps-row">
      <div class="step-card reveal">
        <div class="step-num">1</div>
        <h3>Search & Discover</h3>
        <p>Browse our wide range of services and find exactly what you need.</p>
      </div>
      <div class="step-card reveal reveal-delay-1">
        <div class="step-num">2</div>
        <h3>Choose & Customize</h3>
        <p>Pick your preferred time, view transparent pricing, and customize your service.</p>
      </div>
      <div class="step-card reveal reveal-delay-2">
        <div class="step-num">3</div>
        <h3>Book Instantly</h3>
        <p>Confirm your booking in seconds. Receive instant confirmation via SMS & app.</p>
      </div>
      <div class="step-card reveal reveal-delay-3">
        <div class="step-num">4</div>
        <h3>Enjoy & Review</h3>
        <p>Track your professional in real-time. Rate and review after completion.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════ OUR PROCESS / ROADMAP ═══════════ -->
<section class="process-section">
  <div class="container">
    <div style="text-align:center">
      <div class="section-eyebrow">Our Roadmap</div>
      <h2 class="section-title reveal">Built for Operational Excellence</h2>
      <p class="section-desc reveal" style="margin-left:auto;margin-right:auto">We follow a structured process designed for clarity, speed, and reliability at every stage.</p>
    </div>
    <div class="process-timeline">
      <div class="timeline-card reveal">
        <div class="timeline-num">01</div>
        <div>
          <h4>Onboarding & Verification</h4>
          <p>Every professional undergoes police verification, identity checks, and skill testing before their first job.</p>
        </div>
      </div>
      <div class="timeline-card reveal">
        <div class="timeline-num">02</div>
        <div>
          <h4>Intelligent Matching</h4>
          <p>Our AI matches the best-rated, nearest professional to your service request in real-time.</p>
        </div>
      </div>
      <div class="timeline-card reveal">
        <div class="timeline-num">03</div>
        <div>
          <h4>Live Execution & Tracking</h4>
          <p>Track your professional from dispatch to arrival. Get live updates and ETA notifications.</p>
        </div>
      </div>
      <div class="timeline-card reveal">
        <div class="timeline-num">04</div>
        <div>
          <h4>Quality Assurance</h4>
          <p>Post-service inspection checklist, customer rating, and automated feedback loop for continuous improvement.</p>
        </div>
      </div>
      <div class="timeline-card reveal">
        <div class="timeline-num">05</div>
        <div>
          <h4>Payment & Settlement</h4>
          <p>Secure digital payments with instant invoicing. Partners receive timely settlements with transparent breakdowns.</p>
        </div>
      </div>
      <div class="timeline-card reveal">
        <div class="timeline-num">06</div>
        <div>
          <h4>Retention & Growth</h4>
          <p>Loyalty rewards, re-service guarantees, and personalized recommendations keep customers coming back.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════ STATS BAND ═══════════ -->
<section class="stats-band">
  <div class="container">
    <div class="stats-row">
      <div class="stat-block reveal"><strong>2M+</strong><span>Bookings Completed</span></div>
      <div class="stat-block reveal reveal-delay-1"><strong>50K+</strong><span>Verified Professionals</span></div>
      <div class="stat-block reveal reveal-delay-2"><strong>50+</strong><span>Cities & Growing</span></div>
      <div class="stat-block reveal reveal-delay-3"><strong>98%</strong><span>Satisfaction Rate</span></div>
    </div>
  </div>
</section>

<!-- ═══════════ WHY US ═══════════ -->
<section class="why-us" id="why">
  <div class="container">
    <div style="text-align:center">
      <div class="section-eyebrow">Why ServeEase</div>
      <h2 class="section-title reveal">Why Thousands Choose Us</h2>
      <p class="section-desc reveal" style="margin-left:auto;margin-right:auto">We don't just connect you to workers — we connect you to trust, quality, and peace of mind.</p>
    </div>
    <div class="why-grid">
      <div class="why-card reveal"><div class="why-icon">🛡️</div><h3>Background-Verified Pros</h3><p>Every professional undergoes police verification, skill testing, and identity checks before accepting a single job.</p></div>
      <div class="why-card reveal"><div class="why-icon">💰</div><h3>Transparent Pricing</h3><p>No surprise charges. See the exact price before you book — including labour, materials, and taxes.</p></div>
      <div class="why-card reveal"><div class="why-icon">📍</div><h3>Real-time GPS Tracking</h3><p>Know exactly where your professional is. Live tracking from the moment they leave until the job is done.</p></div>
      <div class="why-card reveal"><div class="why-icon">🔄</div><h3>Satisfaction Guarantee</h3><p>Not happy? We'll re-do the service for free or issue a full refund — no questions asked, within 24 hours.</p></div>
      <div class="why-card reveal"><div class="why-icon">🕐</div><h3>On-time, Every Time</h3><p>We hold professionals to strict punctuality standards. Late arrival? You get an automatic discount.</p></div>
      <div class="why-card reveal"><div class="why-icon">🤝</div><h3>Fully Insured Services</h3><p>All services come with damage coverage. If something goes wrong during the job, we've got you covered.</p></div>
    </div>
  </div>
</section>

<!-- ═══════════ FEATURED SERVICES ═══════════ -->
<section class="featured" id="featured">
  <div class="container">
    <div class="section-header-row">
      <div>
        <div class="section-eyebrow">Editor's Pick</div>
        <h2 class="section-title reveal">Featured Services</h2>
      </div>
      <a href="#" class="btn btn-outline">Explore All →</a>
    </div>
    <div class="featured-layout">
      <div class="featured-hero reveal">
        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=800&h=600&fit=crop" alt="Deep Cleaning">
        <div class="featured-hero-overlay">
          <span class="featured-tag">⭐ Staff Pick</span>
          <h3>Premium Deep Cleaning Package</h3>
          <p>Full-home sanitization with hospital-grade eco-friendly products. Every corner, ceiling to floor.</p>
          <div style="display:flex;align-items:center;justify-content:space-between">
            <span class="featured-price">₹1,499 <span>/ session</span></span>
            <button class="btn btn-accent btn-sm">Book Now</button>
          </div>
        </div>
      </div>
      <div class="featured-stack">
        <div class="featured-mini reveal">
          <img src="https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=300&h=300&fit=crop" alt="Electrician">
          <div class="featured-mini-body">
            <h4>Electrical Safety Audit</h4>
            <p>Full home wiring inspection, MCB check & certified safety report.</p>
            <div class="featured-mini-footer"><span class="mini-price">₹799</span><span class="mini-rating">★ 4.9</span></div>
          </div>
        </div>
        <div class="featured-mini reveal">
          <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=300&fit=crop" alt="Chef">
          <div class="featured-mini-body">
            <h4>Private Chef — Party Package</h4>
            <p>Multi-cuisine menu crafted fresh for your event. Up to 20 guests.</p>
            <div class="featured-mini-footer"><span class="mini-price">₹3,999</span><span class="mini-rating">★ 4.9</span></div>
          </div>
        </div>
        <div class="featured-mini reveal">
          <img src="https://images.unsplash.com/photo-1585704032915-c3400ca199e7?w=300&h=300&fit=crop" alt="AC">
          <div class="featured-mini-body">
            <h4>AC Annual Maintenance Plan</h4>
            <p>4 services/year incl. gas refill, deep cleaning & priority support.</p>
            <div class="featured-mini-footer"><span class="mini-price">₹1,199</span><span class="mini-rating">★ 4.8</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════ POPULAR SERVICES ═══════════ -->
<section class="popular" id="popular">
  <div class="container">
    <div class="section-header-row">
      <div>
        <div class="section-eyebrow">Trending Now</div>
        <h2 class="section-title reveal">Most Booked Services</h2>
      </div>
      <a href="#" class="btn btn-outline">View All →</a>
    </div>
    <div class="services-grid">
      <div class="service-card reveal">
        <div class="service-img"><img src="https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=600&h=380&fit=crop" alt="Cleaning"><span class="service-badge">Most Popular</span><span class="service-rating">4.9</span></div>
        <div class="service-body"><h3>Full Home Deep Cleaning</h3><p class="desc">Complete home sanitization with eco-friendly products. Covers kitchen, bathroom, bedrooms & living areas.</p><div class="service-footer"><div class="service-price">₹1,499 <span>/ session</span></div><button class="btn btn-accent btn-sm">Book Now</button></div></div>
      </div>
      <div class="service-card reveal">
        <div class="service-img"><img src="https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=600&h=380&fit=crop" alt="Electrician"><span class="service-badge">Top Rated</span><span class="service-rating">4.8</span></div>
        <div class="service-body"><h3>Electrical Repair & Wiring</h3><p class="desc">Certified electricians for switches, wiring, MCB installation, fan fitting and rewiring jobs.</p><div class="service-footer"><div class="service-price">₹299 <span>/ visit</span></div><button class="btn btn-accent btn-sm">Book Now</button></div></div>
      </div>
      <div class="service-card reveal">
        <div class="service-img"><img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=380&fit=crop" alt="Chef"><span class="service-badge">New</span><span class="service-rating">4.9</span></div>
        <div class="service-body"><h3>Personal Chef for Events</h3><p class="desc">Professional chefs for house parties, dinners, and events. Multi-cuisine with fresh ingredients.</p><div class="service-footer"><div class="service-price">₹2,999 <span>/ event</span></div><button class="btn btn-accent btn-sm">Book Now</button></div></div>
      </div>
      <div class="service-card reveal">
        <div class="service-img"><img src="https://images.unsplash.com/photo-1585704032915-c3400ca199e7?w=600&h=380&fit=crop" alt="AC"><span class="service-badge">Bestseller</span><span class="service-rating">4.7</span></div>
        <div class="service-body"><h3>AC Service & Repair</h3><p class="desc">Complete AC servicing — gas refill, deep cleaning, compressor check and installation for all brands.</p><div class="service-footer"><div class="service-price">₹499 <span>/ unit</span></div><button class="btn btn-accent btn-sm">Book Now</button></div></div>
      </div>
      <div class="service-card reveal">
        <div class="service-img"><img src="https://images.unsplash.com/photo-1504148455328-c376907d081c?w=600&h=380&fit=crop" alt="Plumber"><span class="service-badge">Quick Fix</span><span class="service-rating">4.8</span></div>
        <div class="service-body"><h3>Plumbing Solutions</h3><p class="desc">Tap repairs, pipe fitting, drainage clearing, water tank cleaning, and full bathroom plumbing.</p><div class="service-footer"><div class="service-price">₹199 <span>/ visit</span></div><button class="btn btn-accent btn-sm">Book Now</button></div></div>
      </div>
      <div class="service-card reveal">
        <div class="service-img"><img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?w=600&h=380&fit=crop" alt="Carpenter"><span class="service-badge">Premium</span><span class="service-rating">4.9</span></div>
        <div class="service-body"><h3>Carpentry & Furniture</h3><p class="desc">Custom furniture, modular kitchen install, door/window repair and all woodwork by skilled carpenters.</p><div class="service-footer"><div class="service-price">₹399 <span>/ hour</span></div><button class="btn btn-accent btn-sm">Book Now</button></div></div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════ PARTNER APP ═══════════ -->
<section class="partner-app" id="partner">
  <div class="container">
    <div class="partner-grid">
      <div class="partner-info">
        <div class="section-eyebrow">For Service Partners</div>
        <h2 class="section-title reveal">Grow Your Business<br>With Our Partner App</h2>
        <p class="section-desc reveal">Join 50,000+ professionals earning more with ServeEase. Get steady leads, manage bookings, and build your reputation — all from one app.</p>
        <div class="partner-features">
          <div class="pf-item reveal">
            <div class="pf-icon">📱</div>
            <div><h4>Easy Job Management</h4><p>Accept, schedule, and manage jobs from your phone. Get notified instantly for new opportunities.</p></div>
          </div>
          <div class="pf-item reveal">
            <div class="pf-icon">💸</div>
            <div><h4>Weekly Payouts</h4><p>Transparent earnings dashboard with weekly direct bank transfers. No delays, no deductions surprises.</p></div>
          </div>
          <div class="pf-item reveal">
            <div class="pf-icon">📈</div>
            <div><h4>Build Your Reputation</h4><p>Collect ratings & reviews from customers. High-rated partners get priority leads and bonus incentives.</p></div>
          </div>
          <div class="pf-item reveal">
            <div class="pf-icon">🎓</div>
            <div><h4>Free Training & Certification</h4><p>Access free skill development programs and get certified to unlock premium service tiers.</p></div>
          </div>
        </div>
        <div class="app-badges reveal">
          <a href="#" class="app-badge">
            <span class="badge-icon">🍎</span>
            <div class="badge-text">Download on the<strong>App Store</strong></div>
          </a>
          <a href="#" class="app-badge">
            <span class="badge-icon">▶️</span>
            <div class="badge-text">Get it on<strong>Google Play</strong></div>
          </a>
        </div>
      </div>
      <div class="partner-visual reveal">
        <div class="phone-mockup">
          <div class="phone-screen">
            <div class="ps-logo">ServeEase</div>
            <div class="ps-sub">Partner App</div>
            <div class="ps-icon">🛠️</div>
            <div class="ps-sub" style="color:rgba(255,255,255,.6)">Manage jobs, track earnings,<br>grow your business</div>
            <div class="ps-btn">Start Earning →</div>
          </div>
        </div>
        <div class="phone-float phone-float-1">
          <span style="font-size:1.2rem">💰</span>
          <div><div class="pfl-text">₹45,000</div><div class="pfl-sub">This month's earnings</div></div>
        </div>
        <div class="phone-float phone-float-2">
          <span style="font-size:1.2rem">⭐</span>
          <div><div class="pfl-text">4.9 Rating</div><div class="pfl-sub">Top Partner badge</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════ VIDEO 2 ═══════════ -->
<section class="video-section-2">
  <div class="container">
    <div class="video-grid-2">
      <div class="video-info reveal">
        <div class="section-eyebrow">Customer Stories</div>
        <h2 class="section-title">Real People, Real Results</h2>
        <p class="section-desc">Hear from homeowners and businesses who've transformed their spaces with ServeEase professionals.</p>
        <div class="check-list">
          <div class="check-item"><span class="check-dot">✓</span> 98% customer satisfaction rate</div>
          <div class="check-item"><span class="check-dot">✓</span> Average 4.9-star service rating</div>
          <div class="check-item"><span class="check-dot">✓</span> 85% repeat booking rate</div>
        </div>
        <a href="#reviews" class="btn btn-primary btn-lg">Read Reviews →</a>
      </div>
      <div class="video-frame reveal">
        <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Customer Stories" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <div class="video-float-badge">🎬 Customer Stories</div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════ TESTIMONIALS ═══════════ -->
<section class="testimonials" id="reviews">
  <div class="container">
    <div class="section-eyebrow">Testimonials</div>
    <h2 class="section-title reveal">What Our Customers Say</h2>
    <p class="section-desc reveal" style="margin-left:auto;margin-right:auto">Thousands of happy customers trust ServeEase for their home service needs.</p>
    <div class="test-grid">
      <div class="test-card reveal">
        <div class="test-stars">★★★★★</div>
        <p class="quote">"Booked a deep cleaning service and the team arrived on time, were extremely thorough, and left my apartment spotless. Absolutely brilliant experience."</p>
        <div class="test-author">
          <img class="test-avatar" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop" alt="">
          <div><div class="test-name">Priya Sharma</div><div class="test-role">Homeowner, Delhi</div></div>
        </div>
      </div>
      <div class="test-card reveal">
        <div class="test-stars">★★★★★</div>
        <p class="quote">"The electrician was incredibly professional. Fixed a complex wiring issue in under an hour. The real-time tracking feature is a game-changer."</p>
        <div class="test-author">
          <img class="test-avatar" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="">
          <div><div class="test-name">Rahul Verma</div><div class="test-role">Business Owner, Mumbai</div></div>
        </div>
      </div>
      <div class="test-card reveal">
        <div class="test-stars">★★★★★</div>
        <p class="quote">"Hired a personal chef for my anniversary dinner. The food was restaurant-quality and the whole experience felt so premium. Will definitely use again!"</p>
        <div class="test-author">
          <img class="test-avatar" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop" alt="">
          <div><div class="test-name">Ananya Iyer</div><div class="test-role">Marketing Lead, Bangalore</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════ COVERAGE ═══════════ -->
<section class="coverage">
  <div class="container">
    <div class="section-eyebrow">Coverage</div>
    <h2 class="section-title reveal">Available in 50+ Cities</h2>
    <p class="section-desc reveal" style="margin-left:auto;margin-right:auto">And expanding every month. Check if ServeEase is available in your city.</p>
    <div class="city-pills reveal">
      <span class="city-pill active">Delhi NCR</span>
      <span class="city-pill">Mumbai</span>
      <span class="city-pill">Bangalore</span>
      <span class="city-pill">Hyderabad</span>
      <span class="city-pill">Chennai</span>
      <span class="city-pill">Kolkata</span>
      <span class="city-pill">Pune</span>
      <span class="city-pill">Ahmedabad</span>
      <span class="city-pill">Jaipur</span>
      <span class="city-pill">Lucknow</span>
      <span class="city-pill">Chandigarh</span>
      <span class="city-pill">Indore</span>
      <span class="city-pill">Bhopal</span>
      <span class="city-pill">Kochi</span>
      <span class="city-pill">Goa</span>
      <span class="city-pill">+ 35 more</span>
    </div>
  </div>
</section>

<!-- ═══════════ FAQ ═══════════ -->
<section class="faq" id="faq">
  <div class="container">
    <div style="text-align:center">
      <div class="section-eyebrow">Support</div>
      <h2 class="section-title reveal">Frequently Asked Questions</h2>
      <p class="section-desc reveal" style="margin-left:auto;margin-right:auto">Everything you need to know about ServeEase.</p>
    </div>
    <div class="faq-list">
      <div class="faq-item active">
        <div class="faq-q" onclick="toggleFaq(this)"><span>What is ServeEase?</span><span class="icon">+</span></div>
        <div class="faq-a" style="max-height:200px"><div class="faq-a-inner">ServeEase is a premium on-demand home services platform connecting customers with verified professionals — electricians, plumbers, cleaners, chefs, carpenters and more — through one seamless booking experience with transparent pricing.</div></div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)"><span>How do I book a service?</span><span class="icon">+</span></div>
        <div class="faq-a"><div class="faq-a-inner">Simply search for the service you need, select your preferred time slot, review the transparent pricing, and confirm your booking. You'll receive instant confirmation and real-time status updates.</div></div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)"><span>Are the service professionals verified?</span><span class="icon">+</span></div>
        <div class="faq-a"><div class="faq-a-inner">Yes. Every professional undergoes a rigorous background check, skill assessment, and identity verification before being onboarded. We maintain a rating threshold to ensure consistent quality.</div></div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)"><span>Can I track my booking in real time?</span><span class="icon">+</span></div>
        <div class="faq-a"><div class="faq-a-inner">Absolutely. Once confirmed, your personal dashboard shows live status — from professional assignment and en-route tracking to job completion and payment receipt.</div></div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)"><span>What if I'm not satisfied with the service?</span><span class="icon">+</span></div>
        <div class="faq-a"><div class="faq-a-inner">We offer a 100% satisfaction guarantee. If you're unhappy with the work, contact support within 24 hours and we'll arrange a free re-service or full refund.</div></div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)"><span>How can I become a service partner?</span><span class="icon">+</span></div>
        <div class="faq-a"><div class="faq-a-inner">Download our Partner App, complete your profile with skill details, and pass our verification process. Once approved, you can start accepting jobs and growing your client base immediately.</div></div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)"><span>What payment methods are accepted?</span><span class="icon">+</span></div>
        <div class="faq-a"><div class="faq-a-inner">We accept all major payment methods — UPI, credit/debit cards, net banking, and wallets. All transactions are encrypted end-to-end. You can also pay via cash after service completion.</div></div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════ CTA BANNER ═══════════ -->
<section class="cta">
  <div class="container">
    <div class="cta-box reveal">
      <div class="cta-text">
        <h2>Ready to Get Started?</h2>
        <p>Join over 2 million happy customers. Book your first service today and experience the ServeEase difference.</p>
      </div>
      <a href="#" class="btn btn-white btn-lg">Book a Service →</a>
    </div>
  </div>
</section>

<!-- ═══════════ FOOTER ═══════════ -->
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="logo"><span class="logo-mark">SE</span> ServeEase</div>
        <p>India's most trusted on-demand home services platform. Verified professionals, transparent pricing, satisfaction guaranteed.</p>
      </div>
      <div class="footer-col">
        <h4>Services</h4>
        <a href="#">Deep Cleaning</a><a href="#">Electrician</a><a href="#">Plumbing</a><a href="#">Carpenter</a><a href="#">Chef</a><a href="#">Pest Control</a><a href="#">AC Repair</a>
      </div>
      <div class="footer-col">
        <h4>Company</h4>
        <a href="#">About Us</a><a href="#">Careers</a><a href="#">Become a Partner</a><a href="#">Blog</a><a href="#">Press</a><a href="#">Contact</a>
      </div>
      <div class="footer-col">
        <h4>Support</h4>
        <a href="#">Help Center</a><a href="#">Contact Us</a><a href="#">Terms & Conditions</a><a href="#">Privacy Policy</a><a href="#">Refund Policy</a><a href="#">Cancellation Policy</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 ServeEase. All rights reserved.</span>
      <div class="footer-socials">
        <a href="#">𝕏</a><a href="#">in</a><a href="#">f</a><a href="#">▶</a>
      </div>
    </div>
  </div>
</footer>

<script>
  // Nav scroll
  window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('scrolled', window.scrollY > 20);
  });

  // FAQ
  function toggleFaq(el) {
    const item = el.parentElement;
    const wasActive = item.classList.contains('active');
    document.querySelectorAll('.faq-item').forEach(i => {
      i.classList.remove('active');
      i.querySelector('.faq-a').style.maxHeight = '0';
    });
    if (!wasActive) {
      item.classList.add('active');
      item.querySelector('.faq-a').style.maxHeight = item.querySelector('.faq-a-inner').scrollHeight + 20 + 'px';
    }
  }

  // Reveal on scroll
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
    });
  }, { threshold: .12 });
  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

  // City pill toggle
  document.querySelectorAll('.city-pill').forEach(pill => {
    pill.addEventListener('click', () => {
      document.querySelectorAll('.city-pill').forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
    });
  });
</script>
</body>
</html>