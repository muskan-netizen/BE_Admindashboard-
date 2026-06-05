<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ServeEase — Home Services at Your Doorstep</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    *,
    *::before,
    *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box
    }

    :root {
      /* --bg: #fafafa;
      --surface: #fff;
      --text: #1a1a1a;
      --text-secondary: #6b6b6b;
      --accent: #6c3ce0;
      --accent-light: #ede5ff;
      --accent-dark: #5228b5;
      --border: #e8e8e8;
      --border-light: #f2f2f2;
      --radius: 14px;
      --radius-sm: 10px;
      --radius-lg: 20px;
      --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06);
      --shadow-md: 0 4px 20px rgba(0, 0, 0, .08);
      --shadow-lg: 0 12px 40px rgba(0, 0, 0, .12);
      --font-body: 'DM Sans', sans-serif;
      --font-display: 'Playfair Display', serif;
      --container: 1200px; */

      --bg: #fafafa;
      --surface: #ffffff;

      --text: #1a1a1a;
      --text-secondary: #6b6b6b;

      /* Primary Accent (Yellow) */
      --accent: #FEDC5A;

      /* Soft backgrounds / hover states */
      --accent-light: #FFF6D6;

      /* Stronger interactive / pressed states */
      --accent-dark: #E6C94A;

      /* Optional complementary (for contrast elements like links/buttons) */
      --accent-contrast: #1F3A5F;

      /* Borders */
      --border: #e8e8e8;
      --border-light: #f2f2f2;

      /* Radius */
      --radius: 14px;
      --radius-sm: 10px;
      --radius-lg: 20px;

      /* Shadows (slightly warmer to match yellow tone) */
      --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06);
      --shadow-md: 0 4px 20px rgba(0, 0, 0, .08);
      --shadow-lg: 0 12px 40px rgba(0, 0, 0, .12);

      /* Typography */
      --font-body: 'DM Sans', sans-serif;
      --font-display: 'Playfair Display', serif;

      --container: 1200px;
    }

    html {
      scroll-behavior: smooth;
      font-size: 16px
    }

    body {
      font-family: var(--font-body);
      background: var(--bg);
      color: var(--text);
      line-height: 1.6;
      overflow-x: hidden
    }

    a {
      text-decoration: none;
      color: inherit
    }

    img {
      max-width: 100%;
      display: block
    }

    button {
      cursor: pointer;
      border: none;
      font-family: inherit
    }

    .container {
      max-width: var(--container);
      margin: 0 auto;
      padding: 0 24px
    }

    /* ── NAV ── */
    nav {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      background: rgba(255, 255, 255, .92);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid var(--border);
      transition: box-shadow .3s
    }

    nav.scrolled {
      box-shadow: var(--shadow-md)
    }

    .nav-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 68px;
      max-width: var(--container);
      margin: 0 auto;
      padding: 0 24px
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: var(--font-display);
      font-size: 1.45rem;
      font-weight: 700;
      color: var(--accent)
    }

    .logo-icon {
      width: 36px;
      height: 36px;
      background: var(--accent);
      border-radius: 10px;
      display: grid;
      place-items: center;
      color: #fff;
      font-size: .9rem;
      font-weight: 700;
      font-family: var(--font-body)
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 32px
    }

    .nav-links a {
      font-size: .88rem;
      font-weight: 500;
      color: var(--text-secondary);
      transition: color .2s
    }

    .nav-links a:hover {
      color: var(--accent)
    }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: 12px
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 10px 22px;
      border-radius: 50px;
      font-size: .88rem;
      font-weight: 600;
      transition: all .25s
    }

    .btn-ghost {
      background: transparent;
      color: var(--text)
    }

    .btn-ghost:hover {
      background: var(--accent-light);
      color: var(--accent)
    }

    .btn-primary {
      background: var(--accent);
      color: #fff
    }

    .btn-primary:hover {
      background: var(--accent-dark);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(108, 60, 224, .35)
    }

    .btn-outline {
      border: 1.5px solid var(--border);
      background: #fff;
      color: var(--text)
    }

    .btn-outline:hover {
      border-color: var(--accent);
      color: var(--accent)
    }

    .hamburger {
      display: none;
      flex-direction: column;
      gap: 5px;
      background: none;
      padding: 4px
    }

    .hamburger span {
      display: block;
      width: 22px;
      height: 2px;
      background: var(--text);
      border-radius: 2px;
      transition: all .3s
    }

    /* ── HERO ── */
    .hero {
      padding: 140px 0 80px;
      position: relative;
      overflow: hidden
    }

    .hero::before {
      content: '';
      position: absolute;
      top: -200px;
      right: -200px;
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(108, 60, 224, .08) 0%, transparent 70%);
      pointer-events: none
    }

    .hero::after {
      content: '';
      position: absolute;
      bottom: -100px;
      left: -100px;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(108, 60, 224, .05) 0%, transparent 70%);
      pointer-events: none
    }

    .hero .container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      align-items: center
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: var(--accent-light);
      color: var(--accent);
      padding: 6px 16px;
      border-radius: 50px;
      font-size: .78rem;
      font-weight: 600;
      margin-bottom: 20px;
      letter-spacing: .3px
    }

    .hero-badge::before {
      content: '';
      width: 6px;
      height: 6px;
      background: var(--accent);
      border-radius: 50%;
      animation: pulse 2s infinite
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1
      }

      50% {
        opacity: .4
      }
    }

    .hero h1 {
      font-family: var(--font-display);
      font-size: 3.6rem;
      line-height: 1.12;
      font-weight: 700;
      letter-spacing: -.02em;
      margin-bottom: 20px
    }

    .hero h1 span {
      color: var(--accent);
      position: relative
    }

    .hero p {
      font-size: 1.12rem;
      color: var(--text-secondary);
      max-width: 480px;
      margin-bottom: 32px;
      line-height: 1.7
    }

    .hero-search {
      display: flex;
      background: var(--surface);
      border: 1.5px solid var(--border);
      border-radius: 60px;
      padding: 6px;
      box-shadow: var(--shadow-md);
      max-width: 520px
    }

    .hero-search input {
      flex: 1;
      border: none;
      outline: none;
      padding: 14px 20px;
      font-size: .95rem;
      background: transparent;
      font-family: var(--font-body);
      color: var(--text)
    }

    .hero-search input::placeholder {
      color: #b0b0b0
    }

    .hero-search .btn-primary {
      padding: 14px 28px;
      border-radius: 50px;
      font-size: .9rem
    }

    .hero-stats {
      display: flex;
      gap: 40px;
      margin-top: 40px
    }

    .hero-stat strong {
      display: block;
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--text)
    }

    .hero-stat span {
      font-size: .82rem;
      color: var(--text-secondary)
    }

    .hero-visual {
      position: relative
    }

    .hero-img-main {
      border-radius: var(--radius-lg);
      overflow: hidden;
      box-shadow: var(--shadow-lg);
      aspect-ratio: 4/5;
      object-fit: cover;
      width: 100%
    }

    .hero-float {
      position: absolute;
      background: var(--surface);
      border-radius: var(--radius);
      padding: 14px 18px;
      box-shadow: var(--shadow-lg);
      display: flex;
      align-items: center;
      gap: 12px;
      animation: float 4s ease-in-out infinite
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0)
      }

      50% {
        transform: translateY(-10px)
      }
    }

    .hero-float-1 {
      top: 20%;
      left: -30px;
      animation-delay: 0s
    }

    .hero-float-2 {
      bottom: 15%;
      right: -20px;
      animation-delay: 1.5s
    }

    .hero-float .emoji {
      font-size: 1.6rem
    }

    .hero-float .fl-text strong {
      display: block;
      font-size: .82rem;
      font-weight: 600
    }

    .hero-float .fl-text span {
      font-size: .72rem;
      color: var(--text-secondary)
    }

    /* ── SERVICES CATEGORIES ── */
    .categories {
      padding: 80px 0
    }

    .section-label {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: .75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: var(--accent);
      margin-bottom: 12px
    }

    .section-title {
      font-family: var(--font-display);
      font-size: 2.4rem;
      font-weight: 600;
      margin-bottom: 12px
    }

    .section-sub {
      color: var(--text-secondary);
      font-size: 1rem;
      max-width: 500px;
      margin-bottom: 48px
    }

    .cat-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px
    }

    .cat-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 28px 24px;
      text-align: center;
      transition: all .3s;
      cursor: pointer;
      position: relative;
      overflow: hidden
    }

    .cat-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, var(--accent), #9b6dff);
      opacity: 0;
      transition: opacity .3s;
      border-radius: var(--radius)
    }

    .cat-card:hover {
      transform: translateY(-6px);
      box-shadow: var(--shadow-lg);
      border-color: transparent
    }

    .cat-card:hover::before {
      opacity: 1
    }

    .cat-card>* {
      position: relative;
      z-index: 1
    }

    .cat-card:hover .cat-icon,
    .cat-card:hover h3,
    .cat-card:hover p {
      color: #fff
    }

    .cat-icon {
      width: 60px;
      height: 60px;
      background: var(--accent-light);
      border-radius: 14px;
      display: grid;
      place-items: center;
      margin: 0 auto 16px;
      font-size: 1.6rem;
      transition: all .3s
    }

    .cat-card:hover .cat-icon {
      background: rgba(255, 255, 255, .2)
    }

    .cat-card h3 {
      font-size: .95rem;
      font-weight: 600;
      margin-bottom: 6px;
      transition: color .3s
    }

    .cat-card p {
      font-size: .78rem;
      color: var(--text-secondary);
      transition: color .3s
    }

    /* ── HOW IT WORKS ── */
    .how {
      padding: 80px 0;
      background: linear-gradient(180deg, var(--bg) 0%, #f3eeff 100%)
    }

    .how .container {
      text-align: center
    }

    .steps {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 32px;
      margin-top: 56px;
      position: relative
    }

    .steps::before {
      content: '';
      position: absolute;
      top: 56px;
      left: 20%;
      right: 20%;
      height: 2px;
      background: repeating-linear-gradient(90deg, var(--accent) 0, var(--accent) 8px, transparent 8px, transparent 16px);
      opacity: .3
    }

    .step {
      text-align: center;
      position: relative
    }

    .step-num {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: var(--accent);
      color: #fff;
      display: grid;
      place-items: center;
      font-size: 1.1rem;
      font-weight: 700;
      margin: 0 auto 24px;
      box-shadow: 0 4px 20px rgba(108, 60, 224, .3);
      position: relative;
      z-index: 2
    }

    .step h3 {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 10px
    }

    .step p {
      font-size: .88rem;
      color: var(--text-secondary);
      max-width: 280px;
      margin: 0 auto;
      line-height: 1.7
    }

    /* ── POPULAR SERVICES ── */
    .popular {
      padding: 80px 0
    }

    .popular-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 48px
    }

    .services-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px
    }

    .service-card {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
      transition: all .3s
    }

    .service-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
      border-color: transparent
    }

    .service-card-img {
      position: relative;
      overflow: hidden;
      aspect-ratio: 16/10
    }

    .service-card-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform .5s
    }

    .service-card:hover .service-card-img img {
      transform: scale(1.06)
    }

    .service-badge {
      position: absolute;
      top: 14px;
      left: 14px;
      background: rgba(255, 255, 255, .95);
      backdrop-filter: blur(8px);
      padding: 5px 12px;
      border-radius: 50px;
      font-size: .72rem;
      font-weight: 600;
      color: var(--accent)
    }

    .service-rating {
      position: absolute;
      top: 14px;
      right: 14px;
      background: rgba(0, 0, 0, .7);
      backdrop-filter: blur(8px);
      padding: 5px 10px;
      border-radius: 50px;
      font-size: .75rem;
      font-weight: 600;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 4px
    }

    .service-rating::before {
      content: '★';
      color: #fbbf24
    }

    .service-body {
      padding: 20px
    }

    .service-body h3 {
      font-size: 1.05rem;
      font-weight: 600;
      margin-bottom: 6px
    }

    .service-body .service-desc {
      font-size: .82rem;
      color: var(--text-secondary);
      margin-bottom: 14px;
      line-height: 1.6
    }

    .service-footer {
      display: flex;
      justify-content: space-between;
      align-items: center
    }

    .service-price {
      font-size: 1.15rem;
      font-weight: 700;
      color: var(--accent)
    }

    .service-price span {
      font-size: .78rem;
      font-weight: 400;
      color: var(--text-secondary)
    }

    .btn-book {
      padding: 8px 20px;
      font-size: .82rem
    }

    /* ── TESTIMONIALS ── */
    .testimonials {
      padding: 80px 0;
      background: var(--text);
      color: #fff;
      overflow: hidden
    }

    .testimonials .section-label {
      color: #a78bfa
    }

    .testimonials .section-title {
      color: #fff
    }

    .testimonials .section-sub {
      color: rgba(255, 255, 255, .5)
    }

    .test-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
      margin-top: 48px
    }

    .test-card {
      background: rgba(255, 255, 255, .06);
      border: 1px solid rgba(255, 255, 255, .08);
      border-radius: var(--radius);
      padding: 32px;
      transition: all .3s
    }

    .test-card:hover {
      background: rgba(255, 255, 255, .1);
      transform: translateY(-4px)
    }

    .test-stars {
      color: #fbbf24;
      font-size: .9rem;
      margin-bottom: 16px;
      letter-spacing: 2px
    }

    .test-card p {
      font-size: .92rem;
      color: rgba(255, 255, 255, .75);
      line-height: 1.8;
      margin-bottom: 24px;
      font-style: italic
    }

    .test-author {
      display: flex;
      align-items: center;
      gap: 14px
    }

    .test-avatar {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255, 255, 255, .15)
    }

    .test-name {
      font-weight: 600;
      font-size: .88rem
    }

    .test-role {
      font-size: .75rem;
      color: rgba(255, 255, 255, .4)
    }

    /* ── FAQ ── */
    .faq {
      padding: 80px 0
    }

    .faq .container {
      max-width: 760px
    }

    .faq-list {
      margin-top: 40px;
      display: flex;
      flex-direction: column;
      gap: 12px
    }

    .faq-item {
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      overflow: hidden;
      transition: border-color .3s
    }

    .faq-item.active {
      border-color: var(--accent)
    }

    .faq-q {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 24px;
      cursor: pointer;
      font-weight: 600;
      font-size: .95rem;
      background: var(--surface);
      transition: background .3s
    }

    .faq-q:hover {
      background: var(--accent-light)
    }

    .faq-q .icon {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: var(--accent-light);
      color: var(--accent);
      display: grid;
      place-items: center;
      font-size: 1.1rem;
      transition: all .3s;
      flex-shrink: 0
    }

    .faq-item.active .faq-q .icon {
      background: var(--accent);
      color: #fff;
      transform: rotate(45deg)
    }

    .faq-a {
      max-height: 0;
      overflow: hidden;
      transition: max-height .4s ease, padding .3s
    }

    .faq-a-inner {
      padding: 0 24px 20px;
      font-size: .88rem;
      color: var(--text-secondary);
      line-height: 1.8
    }

    /* ── CTA BANNER ── */
    .cta-banner {
      padding: 80px 0
    }

    .cta-box {
      background: linear-gradient(135deg, var(--accent) 0%, #9b6dff 50%, #c084fc 100%);
      border-radius: var(--radius-lg);
      padding: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 40px;
      position: relative;
      overflow: hidden
    }

    .cta-box::before {
      content: '';
      position: absolute;
      top: -60px;
      right: -60px;
      width: 300px;
      height: 300px;
      background: rgba(255, 255, 255, .08);
      border-radius: 50%
    }

    .cta-box::after {
      content: '';
      position: absolute;
      bottom: -80px;
      left: 30%;
      width: 200px;
      height: 200px;
      background: rgba(255, 255, 255, .06);
      border-radius: 50%
    }

    .cta-box>* {
      position: relative;
      z-index: 1
    }

    .cta-text h2 {
      font-family: var(--font-display);
      font-size: 2.2rem;
      color: #fff;
      margin-bottom: 12px
    }

    .cta-text p {
      color: rgba(255, 255, 255, .8);
      font-size: 1rem;
      max-width: 420px
    }

    .btn-white {
      background: #fff;
      color: var(--accent);
      font-weight: 700;
      padding: 16px 36px;
      font-size: .95rem
    }

    .btn-white:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 30px rgba(0, 0, 0, .2)
    }

    /* ── FOOTER ── */
    footer {
      background: #111;
      color: rgba(255, 255, 255, .65);
      padding: 64px 0 0
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 1.5fr 1fr 1fr 1fr;
      gap: 48px;
      padding-bottom: 48px;
      border-bottom: 1px solid rgba(255, 255, 255, .08)
    }

    .footer-brand .logo {
      color: #fff;
      margin-bottom: 16px
    }

    .footer-brand p {
      font-size: .85rem;
      line-height: 1.8;
      max-width: 300px
    }

    .footer-col h4 {
      color: #fff;
      font-size: .85rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 20px
    }

    .footer-col a {
      display: block;
      font-size: .85rem;
      padding: 5px 0;
      transition: color .2s
    }

    .footer-col a:hover {
      color: var(--accent)
    }

    .footer-bottom {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 24px 0;
      font-size: .8rem
    }

    .footer-socials {
      display: flex;
      gap: 12px
    }

    .footer-socials a {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 1px solid rgba(255, 255, 255, .12);
      display: grid;
      place-items: center;
      font-size: .85rem;
      transition: all .3s
    }

    .footer-socials a:hover {
      background: var(--accent);
      border-color: var(--accent);
      color: #fff
    }

    /* ── MOBILE NAV ── */
    .mobile-menu {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .5);
      z-index: 999
    }

    .mobile-menu-inner {
      position: absolute;
      right: 0;
      top: 0;
      bottom: 0;
      width: 280px;
      background: #fff;
      padding: 80px 24px 24px;
      display: flex;
      flex-direction: column;
      gap: 8px;
      transform: translateX(100%);
      transition: transform .3s
    }

    .mobile-menu.open .mobile-menu-inner {
      transform: translateX(0)
    }

    .mobile-menu.open {
      display: block
    }

    .mobile-menu a {
      padding: 14px 0;
      font-size: 1rem;
      font-weight: 500;
      border-bottom: 1px solid var(--border)
    }

    .mobile-close {
      position: absolute;
      top: 20px;
      right: 20px;
      background: none;
      font-size: 1.5rem;
      color: var(--text)
    }

    /* ── RESPONSIVE ── */
    @media(max-width:1024px) {
      .hero .container {
        grid-template-columns: 1fr
      }

      .hero-visual {
        display: none
      }

      .hero h1 {
        font-size: 2.8rem
      }

      .cat-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .services-grid {
        grid-template-columns: repeat(2, 1fr)
      }

      .test-grid {
        grid-template-columns: 1fr 1fr
      }

      .footer-grid {
        grid-template-columns: 1fr 1fr
      }

      .cta-box {
        flex-direction: column;
        text-align: center;
        padding: 48px 32px
      }

      .cta-text p {
        margin: 0 auto
      }
    }

    @media(max-width:768px) {
      .nav-links {
        display: none
      }

      .hamburger {
        display: flex
      }

      .hero {
        padding: 110px 0 60px
      }

      .hero h1 {
        font-size: 2.2rem
      }

      .hero-search {
        flex-direction: column;
        border-radius: var(--radius)
      }

      .hero-search .btn-primary {
        border-radius: 10px;
        margin-top: 4px
      }

      .hero-stats {
        gap: 24px;
        flex-wrap: wrap
      }

      .cat-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px
      }

      .cat-card {
        padding: 20px 16px
      }

      .services-grid {
        grid-template-columns: 1fr
      }

      .test-grid {
        grid-template-columns: 1fr
      }

      .steps {
        grid-template-columns: 1fr;
        gap: 40px
      }

      .steps::before {
        display: none
      }

      .section-title {
        font-size: 1.8rem
      }

      .footer-grid {
        grid-template-columns: 1fr;
        gap: 32px
      }

      .footer-bottom {
        flex-direction: column;
        gap: 16px;
        text-align: center
      }
    }

    @media(max-width:480px) {
      .cat-grid {
        grid-template-columns: 1fr 1fr;
        gap: 10px
      }

      .cat-icon {
        width: 48px;
        height: 48px;
        font-size: 1.3rem;
        border-radius: 12px
      }

      .cat-card {
        padding: 18px 12px
      }

      .popular-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px
      }
    }

    /* ── ANIMATIONS ── */
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: all .7s cubic-bezier(.16, 1, .3, 1)
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0)
    }












    /* ── WHY US ── */
    .why-us {
      padding: 90px 0;
      background: var(--accent-contrast);
      position: relative;
      overflow: hidden;
    }

    .why-us::before {
      content: '';
      position: absolute;
      top: -120px;
      right: -120px;
      width: 450px;
      height: 450px;
      background: radial-gradient(circle, rgba(254,220,90,.08) 0%, transparent 70%);
      pointer-events: none;
    }

    .why-us .section-label { color: var(--accent); }
    .why-us .section-title { color: #fff; }
    .why-us .section-sub { color: rgba(255,255,255,.55); }

    .why-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
      margin-top: 48px;
    }

    .why-card {
      background: rgba(255,255,255,.05);
      border: 1px solid rgba(255,255,255,.09);
      border-radius: var(--radius);
      padding: 32px 28px;
      transition: all .3s;
    }

    .why-card:hover {
      background: rgba(255,255,255,.1);
      transform: translateY(-5px);
      border-color: var(--accent);
    }

    .why-icon {
      width: 56px;
      height: 56px;
      background: rgba(254,220,90,.14);
      border-radius: 14px;
      display: grid;
      place-items: center;
      font-size: 1.5rem;
      margin-bottom: 20px;
    }

    .why-card h3 {
      font-size: 1rem;
      font-weight: 600;
      color: #fff;
      margin-bottom: 10px;
    }

    .why-card p {
      font-size: .84rem;
      color: rgba(255,255,255,.5);
      line-height: 1.75;
    }

    /* ── FEATURED SERVICES ── */
    .featured {
      padding: 80px 0;
      background: var(--bg);
    }

    .featured-grid {
      display: grid;
      grid-template-columns: 1.45fr 1fr;
      gap: 24px;
      margin-top: 48px;
    }

    .featured-main {
      position: relative;
      border-radius: var(--radius-lg);
      overflow: hidden;
      min-height: 440px;
      cursor: pointer;
    }

    .featured-main > img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform .55s;
    }

    .featured-main:hover > img { transform: scale(1.05); }

    .featured-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(0,0,0,.78) 0%, transparent 55%);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 32px;
    }

    .featured-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--accent);
      color: #1a1a1a;
      padding: 5px 14px;
      border-radius: 50px;
      font-size: .72rem;
      font-weight: 700;
      margin-bottom: 14px;
      width: fit-content;
      letter-spacing: .3px;
    }

    .featured-overlay h3 {
      font-family: var(--font-display);
      font-size: 1.65rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 8px;
      line-height: 1.2;
    }

    .featured-overlay p {
      font-size: .86rem;
      color: rgba(255,255,255,.72);
      margin-bottom: 20px;
      max-width: 340px;
      line-height: 1.6;
    }

    .featured-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .featured-footer strong {
      font-size: 1.35rem;
      color: var(--accent);
      font-weight: 700;
    }

    .featured-small-grid {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }

    .featured-small {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
      display: flex;
      transition: all .3s;
      cursor: pointer;
    }

    .featured-small:hover {
      transform: translateX(5px);
      box-shadow: var(--shadow-md);
      border-color: var(--accent);
    }

    .featured-small img {
      width: 115px;
      height: 115px;
      object-fit: cover;
      flex-shrink: 0;
    }

    .featured-small-body {
      padding: 14px 16px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .featured-small-body h4 {
      font-size: .95rem;
      font-weight: 600;
      margin-bottom: 4px;
    }

    .featured-small-body p {
      font-size: .78rem;
      color: var(--text-secondary);
      margin-bottom: 10px;
      line-height: 1.5;
    }

    .featured-small-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .featured-small-price {
      font-size: 1rem;
      font-weight: 700;
      color: var(--accent);
    }

    .rating-pill {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      background: #fff8e1;
      color: #b45309;
      padding: 3px 10px;
      border-radius: 50px;
      font-size: .72rem;
      font-weight: 600;
    }

    /* ── STATS TICKER ── */
    .stats-ticker {
      background: var(--accent);
      padding: 18px 0;
      overflow: hidden;
    }

    .ticker-track {
      display: flex;
      gap: 64px;
      animation: ticker 22s linear infinite;
      width: max-content;
    }

    @keyframes ticker {
      from { transform: translateX(0); }
      to   { transform: translateX(-50%); }
    }

    .ticker-item {
      display: flex;
      align-items: center;
      gap: 10px;
      white-space: nowrap;
      font-size: .82rem;
      font-weight: 600;
      color: #1a1a1a;
    }

    .ticker-item .dot {
      width: 6px;
      height: 6px;
      background: rgba(0,0,0,.3);
      border-radius: 50%;
    }

    @media(max-width:1024px) {
      .why-grid { grid-template-columns: repeat(2, 1fr); }
      .featured-grid { grid-template-columns: 1fr; }
      .featured-main { min-height: 320px; }
    }

    @media(max-width:768px) {
      .why-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media(max-width:480px) {
      .why-grid { grid-template-columns: 1fr; }
      .featured-small { flex-direction: column; }
      .featured-small img { width: 100%; height: 160px; }
    }

    /* ── VIDEO SECTION ── */
.video-section {
  padding: 90px 0;
  background: linear-gradient(180deg, #fff 0%, var(--accent-light) 100%);
}

.video-content {
  display: grid;
  grid-template-columns: 1fr 1.1fr;
  gap: 60px;
  align-items: center;
}

.video-text .section-title {
  margin-bottom: 14px;
}

.video-text .section-sub {
  margin-bottom: 24px;
}

.video-points {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 28px;
}

.vp-item {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: .9rem;
  color: var(--text-secondary);
}

.vp-item span {
  color: var(--accent);
  font-weight: 700;
}

/* Video Box */
.video-box {
  position: relative;
}

.video-wrapper {
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-lg);
  border: 1px solid var(--border);
}

.video-wrapper iframe {
  width: 100%;
  height: 360px;
  display: block;
}

/* Floating badge */
.video-badge {
  position: absolute;
  bottom: -16px;
  left: 20px;
  background: var(--accent);
  color: #fff;
  padding: 10px 18px;
  border-radius: 50px;
  font-size: .8rem;
  font-weight: 600;
  box-shadow: var(--shadow-md);
}

/* Responsive */
@media(max-width:1024px) {
  .video-content {
    grid-template-columns: 1fr;
  }

  .video-wrapper iframe {
    height: 300px;
  }

  .video-text {
    text-align: center;
  }

  .video-points {
    align-items: center;
  }
}
  </style>
</head>

<body>

  <!-- NAV -->
  <nav id="nav">
    <div class="nav-inner">
      <a href="#" class="logo"><span class="logo-icon">S</span> ServeEase</a>
      <div class="nav-links">
        <a href="#services">Services</a>
        <a href="#how">How It Works</a>
        <a href="#why">Why Us</a>
        <a href="#featured">Featured</a>
        <a href="#popular">Popular</a>
        <a href="#testimonials">Reviews</a>
        <a href="#faq">FAQ</a>
      </div>
      <div class="nav-actions">
        <a href="#" class="btn btn-ghost">Log In</a>
        <a href="#" class="btn btn-primary">Book Now</a>
      </div>
      <button class="hamburger" onclick="document.getElementById('mobileMenu').classList.add('open')">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>

  <!-- MOBILE MENU -->
  <div class="mobile-menu" id="mobileMenu" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="mobile-menu-inner">
      <button class="mobile-close" onclick="document.getElementById('mobileMenu').classList.remove('open')">&times;</button>
      <a href="#services" onclick="document.getElementById('mobileMenu').classList.remove('open')">Services</a>
      <a href="#how" onclick="document.getElementById('mobileMenu').classList.remove('open')">How It Works</a>
      <a href="#why" onclick="document.getElementById('mobileMenu').classList.remove('open')">Why Us</a>
      <a href="#featured" onclick="document.getElementById('mobileMenu').classList.remove('open')">Featured</a>
      <a href="#popular" onclick="document.getElementById('mobileMenu').classList.remove('open')">Popular</a>
      <a href="#testimonials" onclick="document.getElementById('mobileMenu').classList.remove('open')">Reviews</a>
      <a href="#faq" onclick="document.getElementById('mobileMenu').classList.remove('open')">FAQ</a>
      <a href="#" class="btn btn-primary" style="margin-top:16px;text-align:center">Book Now</a>
    </div>
  </div>

  <!-- HERO -->
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <div class="hero-badge">● Now serving 50+ cities</div>
        <h1>Expert Home<br>Services, <span>On Demand</span></h1>
        <p>From plumbing to deep cleaning, book verified professionals in 60 seconds. Transparent pricing, real-time tracking, and guaranteed satisfaction.</p>
        <div class="hero-search">
          <input type="text" placeholder="What service do you need?">
          <button class="btn btn-primary">🔍 Search</button>
        </div>
        <div class="hero-stats">
          <div class="hero-stat"><strong>2M+</strong><span>Bookings Done</span></div>
          <div class="hero-stat"><strong>50K+</strong><span>Verified Pros</span></div>
          <div class="hero-stat"><strong>4.9★</strong><span>Avg Rating</span></div>
        </div>
      </div>
      <div class="hero-visual">
        <img class="hero-img-main" src="https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=600&h=750&fit=crop" alt="Professional Service">
        <div class="hero-float hero-float-1">
          <span class="emoji">✅</span>
          <div class="fl-text"><strong>Booking Confirmed</strong><span>Electrician • 2:30 PM</span></div>
        </div>
        <div class="hero-float hero-float-2">
          <span class="emoji">⭐</span>
          <div class="fl-text"><strong>Rated 5.0</strong><span>Deep Cleaning Service</span></div>
        </div>
      </div>
    </div>
  </section>

  <!-- CATEGORIES -->
  <section class="categories" id="services">
    <div class="container">
      <div class="section-label">— Our Services</div>
      <h2 class="section-title reveal">What Do You Need?</h2>
      <p class="section-sub reveal">Browse verified professionals across all home service categories.</p>
      <div class="cat-grid">
        <div class="cat-card reveal">
          <div class="cat-icon">🔧</div>
          <h3>Plumbing</h3>
          <p>Leaks, fittings & pipes</p>
        </div>
        <div class="cat-card reveal">
          <div class="cat-icon">⚡</div>
          <h3>Electrician</h3>
          <p>Wiring, repairs & setup</p>
        </div>
        <div class="cat-card reveal">
          <div class="cat-icon">🧹</div>
          <h3>Deep Cleaning</h3>
          <p>Home & office cleaning</p>
        </div>
        <div class="cat-card reveal">
          <div class="cat-icon">🪵</div>
          <h3>Carpenter</h3>
          <p>Furniture & woodwork</p>
        </div>
        <div class="cat-card reveal">
          <div class="cat-icon">👨‍🍳</div>
          <h3>Chef</h3>
          <p>Personal & party chefs</p>
        </div>
        <div class="cat-card reveal">
          <div class="cat-icon">🐛</div>
          <h3>Pest Control</h3>
          <p>Safe & effective treatment</p>
        </div>
        <div class="cat-card reveal">
          <div class="cat-icon">❄️</div>
          <h3>AC & Appliance</h3>
          <p>Service & installation</p>
        </div>
        <div class="cat-card reveal">
          <div class="cat-icon">🤝</div>
          <h3>Helpers & Staff</h3>
          <p>Waiters, helpers & more</p>
        </div>
      </div>
    </div>
  </section>

  <section class="video-section">
    <div class="container">
      <div class="video-content">
        <div class="video-text reveal">
          <div class="section-label">— See It In Action</div>
          <h2 class="section-title">How ServeEase Works</h2>
          <p class="section-sub">
            Watch how easy it is to book trusted home services in just a few clicks.
            Real professionals, real results — all at your convenience.
          </p>

          <div class="video-points">
            <div class="vp-item">
              <span>✔</span> Book in under 60 seconds
            </div>
            <div class="vp-item">
              <span>✔</span> Verified professionals only
            </div>
            <div class="vp-item">
              <span>✔</span> Real-time service tracking
            </div>
          </div>

          <a href="#" class="btn btn-primary">Get Started →</a>
        </div>

        <div class="video-box reveal">
          <div class="video-wrapper">
            <iframe
              src="https://www.youtube.com/embed/dQw4w9WgXcQ"
              title="ServeEase Demo Video"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen>
            </iframe>
          </div>

          <div class="video-badge">
            ▶ Demo Video
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="how" id="how">
    <div class="container">
      <div class="section-label">— Simple Process</div>
      <h2 class="section-title reveal">How It Works</h2>
      <p class="section-sub reveal" style="margin-left:auto;margin-right:auto">Three easy steps from search to service. No hidden fees, no hassle.</p>
      <div class="steps">
        <div class="step reveal">
          <div class="step-num">1</div>
          <h3>Search & Discover</h3>
          <p>Browse our wide range of services and find exactly what you need using powerful search and filters.</p>
        </div>
        <div class="step reveal">
          <div class="step-num">2</div>
          <h3>Book Instantly</h3>
          <p>Choose your preferred time slot, view transparent pricing, and confirm your booking in seconds.</p>
        </div>
        <div class="step reveal">
          <div class="step-num">3</div>
          <h3>Enjoy the Service</h3>
          <p>Track your professional in real-time. Rate and review after completion with full satisfaction guarantee.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- STATS TICKER -->
  <div class="stats-ticker">
    <div class="ticker-track">
      <div class="ticker-item">✅ 2M+ Bookings Completed <span class="dot"></span></div>
      <div class="ticker-item">⭐ 4.9 Average Rating <span class="dot"></span></div>
      <div class="ticker-item">🔒 100% Verified Professionals <span class="dot"></span></div>
      <div class="ticker-item">🏙️ 50+ Cities Covered <span class="dot"></span></div>
      <div class="ticker-item">💰 Transparent Pricing <span class="dot"></span></div>
      <div class="ticker-item">🔄 Free Re-service Guarantee <span class="dot"></span></div>
      <div class="ticker-item">⚡ 60-Second Booking <span class="dot"></span></div>
      <div class="ticker-item">📍 Real-time Tracking <span class="dot"></span></div>
      <!-- duplicate for seamless loop -->
      <div class="ticker-item">✅ 2M+ Bookings Completed <span class="dot"></span></div>
      <div class="ticker-item">⭐ 4.9 Average Rating <span class="dot"></span></div>
      <div class="ticker-item">🔒 100% Verified Professionals <span class="dot"></span></div>
      <div class="ticker-item">🏙️ 50+ Cities Covered <span class="dot"></span></div>
      <div class="ticker-item">💰 Transparent Pricing <span class="dot"></span></div>
      <div class="ticker-item">🔄 Free Re-service Guarantee <span class="dot"></span></div>
      <div class="ticker-item">⚡ 60-Second Booking <span class="dot"></span></div>
      <div class="ticker-item">📍 Real-time Tracking <span class="dot"></span></div>
    </div>
  </div>

  <!-- WHY SERVEEASE -->
  <section class="why-us" id="why">
    <div class="container">
      <div style="text-align:center">
        <div class="section-label">— Why ServeEase</div>
        <h2 class="section-title reveal">Why Thousands Choose Us</h2>
        <p class="section-sub reveal" style="margin-left:auto;margin-right:auto">We don't just connect you to workers — we connect you to trust, quality, and peace of mind.</p>
      </div>
      <div class="why-grid">
        <div class="why-card reveal">
          <div class="why-icon">🛡️</div>
          <h3>Background-Verified Pros</h3>
          <p>Every professional on our platform undergoes police verification, skill testing, and identity checks before accepting a single job.</p>
        </div>
        <div class="why-card reveal">
          <div class="why-icon">💰</div>
          <h3>Transparent Pricing</h3>
          <p>No surprise charges. See the exact price before you book — including labour, materials, and any applicable taxes.</p>
        </div>
        <div class="why-card reveal">
          <div class="why-icon">📍</div>
          <h3>Real-time Tracking</h3>
          <p>Know exactly where your professional is. Live GPS tracking from the moment they leave until the job is done.</p>
        </div>
        <div class="why-card reveal">
          <div class="why-icon">🔄</div>
          <h3>Satisfaction Guarantee</h3>
          <p>Not happy? We'll re-do the service for free or issue a full refund — no questions asked, within 24 hours.</p>
        </div>
        <div class="why-card reveal">
          <div class="why-icon">🕐</div>
          <h3>On-time, Every Time</h3>
          <p>We hold our professionals to strict punctuality standards. Late arrival? You get a discount — automatically.</p>
        </div>
        <div class="why-card reveal">
          <div class="why-icon">🤝</div>
          <h3>Insured Services</h3>
          <p>All services come with damage coverage. If something goes wrong during the job, we've got you fully covered.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURED SERVICES -->
  <section class="featured" id="featured">
    <div class="container">
      <div class="popular-header">
        <div>
          <div class="section-label">— Editor's Pick</div>
          <h2 class="section-title reveal">Featured Services</h2>
        </div>
        <a href="#" class="btn btn-outline">Explore All →</a>
      </div>
      <div class="featured-grid">
        <!-- Big featured card -->
        <div class="featured-main reveal">
          <img src="https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=800&h=600&fit=crop" alt="Deep Cleaning">
          <div class="featured-overlay">
            <span class="featured-tag">⭐ Staff Pick</span>
            <h3>Premium Deep Cleaning Package</h3>
            <p>A full-home sanitization experience using hospital-grade eco-friendly products. Covers every corner — from ceiling fans to bathroom tiles.</p>
            <div class="featured-footer">
              <strong>₹1,499 <span style="font-size:.8rem;font-weight:400;color:rgba(255,255,255,.6)">/ session</span></strong>
              <button class="btn btn-primary btn-book">Book Now</button>
            </div>
          </div>
        </div>
        <!-- Small featured cards -->
        <div class="featured-small-grid">
          <div class="featured-small reveal">
            <img src="https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=300&h=300&fit=crop" alt="Electrician">
            <div class="featured-small-body">
              <h4>Electrical Safety Audit</h4>
              <p>Full home wiring inspection, MCB check & certified safety report.</p>
              <div class="featured-small-footer">
                <span class="featured-small-price">₹799</span>
                <span class="rating-pill">★ 4.9</span>
                <button class="btn btn-outline btn-book">Book</button>
              </div>
            </div>
          </div>
          <div class="featured-small reveal">
            <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=300&fit=crop" alt="Chef">
            <div class="featured-small-body">
              <h4>Private Chef — Party Package</h4>
              <p>Multi-cuisine menu crafted fresh for your event. Up to 20 guests.</p>
              <div class="featured-small-footer">
                <span class="featured-small-price">₹3,999</span>
                <span class="rating-pill">★ 4.9</span>
                <button class="btn btn-outline btn-book">Book</button>
              </div>
            </div>
          </div>
          <div class="featured-small reveal">
            <img src="https://images.unsplash.com/photo-1585704032915-c3400ca199e7?w=300&h=300&fit=crop" alt="AC">
            <div class="featured-small-body">
              <h4>AC Annual Maintenance Plan</h4>
              <p>4 services/year incl. gas refill, deep cleaning & priority support.</p>
              <div class="featured-small-footer">
                <span class="featured-small-price">₹1,199</span>
                <span class="rating-pill">★ 4.8</span>
                <button class="btn btn-outline btn-book">Book</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- POPULAR SERVICES -->
  <section class="popular" id="popular">
    <div class="container">
      <div class="popular-header">
        <div>
          <div class="section-label">— Trending Now</div>
          <h2 class="section-title reveal">Most Booked Services</h2>
        </div>
        <a href="#" class="btn btn-outline">View All →</a>
      </div>
      <div class="services-grid">
        <div class="service-card reveal">
          <div class="service-card-img">
            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=600&h=380&fit=crop" alt="Cleaning">
            <span class="service-badge">Most Popular</span>
            <span class="service-rating">4.9</span>
          </div>
          <div class="service-body">
            <h3>Full Home Deep Cleaning</h3>
            <p class="service-desc">Complete home sanitization with eco-friendly products. Covers kitchen, bathroom, bedrooms & living areas.</p>
            <div class="service-footer">
              <div class="service-price">₹1,499 <span>/ session</span></div>
              <button class="btn btn-primary btn-book">Book Now</button>
            </div>
          </div>
        </div>
        <div class="service-card reveal">
          <div class="service-card-img">
            <img src="https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=600&h=380&fit=crop" alt="Electrician">
            <span class="service-badge">Top Rated</span>
            <span class="service-rating">4.8</span>
          </div>
          <div class="service-body">
            <h3>Electrical Repair & Wiring</h3>
            <p class="service-desc">Certified electricians for switches, wiring, MCB installation, fan fitting and complete rewiring jobs.</p>
            <div class="service-footer">
              <div class="service-price">₹299 <span>/ visit</span></div>
              <button class="btn btn-primary btn-book">Book Now</button>
            </div>
          </div>
        </div>
        <div class="service-card reveal">
          <div class="service-card-img">
            <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=380&fit=crop" alt="Chef">
            <span class="service-badge">New</span>
            <span class="service-rating">4.9</span>
          </div>
          <div class="service-body">
            <h3>Personal Chef for Events</h3>
            <p class="service-desc">Professional chefs for house parties, dinners, and events. Multi-cuisine menu with fresh ingredients.</p>
            <div class="service-footer">
              <div class="service-price">₹2,999 <span>/ event</span></div>
              <button class="btn btn-primary btn-book">Book Now</button>
            </div>
          </div>
        </div>
        <div class="service-card reveal">
          <div class="service-card-img">
            <img src="https://images.unsplash.com/photo-1585704032915-c3400ca199e7?w=600&h=380&fit=crop" alt="AC">
            <span class="service-badge">Bestseller</span>
            <span class="service-rating">4.7</span>
          </div>
          <div class="service-body">
            <h3>AC Service & Repair</h3>
            <p class="service-desc">Complete AC servicing — gas refill, deep cleaning, compressor check and installation for all brands.</p>
            <div class="service-footer">
              <div class="service-price">₹499 <span>/ unit</span></div>
              <button class="btn btn-primary btn-book">Book Now</button>
            </div>
          </div>
        </div>
        <div class="service-card reveal">
          <div class="service-card-img">
            <img src="https://images.unsplash.com/photo-1504148455328-c376907d081c?w=600&h=380&fit=crop" alt="Plumber">
            <span class="service-badge">Quick Fix</span>
            <span class="service-rating">4.8</span>
          </div>
          <div class="service-body">
            <h3>Plumbing Solutions</h3>
            <p class="service-desc">Tap repairs, pipe fitting, drainage clearing, water tank cleaning, and full bathroom plumbing services.</p>
            <div class="service-footer">
              <div class="service-price">₹199 <span>/ visit</span></div>
              <button class="btn btn-primary btn-book">Book Now</button>
            </div>
          </div>
        </div>
        <div class="service-card reveal">
          <div class="service-card-img">
            <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?w=600&h=380&fit=crop" alt="Carpenter">
            <span class="service-badge">Premium</span>
            <span class="service-rating">4.9</span>
          </div>
          <div class="service-body">
            <h3>Carpentry & Furniture</h3>
            <p class="service-desc">Custom furniture, modular kitchen install, door/window repair and all woodwork by skilled carpenters.</p>
            <div class="service-footer">
              <div class="service-price">₹399 <span>/ hour</span></div>
              <button class="btn btn-primary btn-book">Book Now</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="video-section">
    <div class="container">
      <div class="video-content">
        <div class="video-box reveal">
          <div class="video-wrapper">
            <iframe
              src="https://www.youtube.com/embed/dQw4w9WgXcQ"
              title="ServeEase Demo Video"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen>
            </iframe>
          </div>

          <div class="video-badge">
            ▶ Demo Video
          </div>
        </div>

        
        <div class="video-text reveal">
          <div class="section-label">— See It In Action</div>
          <h2 class="section-title">How ServeEase Works</h2>
          <p class="section-sub">
            Watch how easy it is to book trusted home services in just a few clicks.
            Real professionals, real results — all at your convenience.
          </p>

          <div class="video-points">
            <div class="vp-item">
              <span>✔</span> Book in under 60 seconds
            </div>
            <div class="vp-item">
              <span>✔</span> Verified professionals only
            </div>
            <div class="vp-item">
              <span>✔</span> Real-time service tracking
            </div>
          </div>

          <a href="#" class="btn btn-primary">Get Started →</a>
        </div>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section class="testimonials" id="testimonials">
    <div class="container">
      <div style="text-align:center">
        <div class="section-label">— Testimonials</div>
        <h2 class="section-title reveal">What Our Customers Say</h2>
        <p class="section-sub reveal" style="margin-left:auto;margin-right:auto">Thousands of happy customers trust ServeEase for their home service needs.</p>
      </div>
      <div class="test-grid">
        <div class="test-card reveal">
          <div class="test-stars">★★★★★</div>
          <p>"Booked a deep cleaning service and the team arrived on time, were extremely thorough, and left my apartment spotless. Absolutely brilliant experience."</p>
          <div class="test-author">
            <img class="test-avatar" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop" alt="Customer">
            <div>
              <div class="test-name">Priya Sharma</div>
              <div class="test-role">Homeowner, Delhi</div>
            </div>
          </div>
        </div>
        <div class="test-card reveal">
          <div class="test-stars">★★★★★</div>
          <p>"The electrician was incredibly professional. Fixed a complex wiring issue in under an hour. The real-time tracking feature is a game-changer."</p>
          <div class="test-author">
            <img class="test-avatar" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop" alt="Customer">
            <div>
              <div class="test-name">Rahul Verma</div>
              <div class="test-role">Business Owner, Mumbai</div>
            </div>
          </div>
        </div>
        <div class="test-card reveal">
          <div class="test-stars">★★★★★</div>
          <p>"Hired a personal chef for my anniversary dinner. The food was restaurant-quality and the whole experience felt so premium. Will definitely use again!"</p>
          <div class="test-author">
            <img class="test-avatar" src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop" alt="Customer">
            <div>
              <div class="test-name">Ananya Iyer</div>
              <div class="test-role">Marketing Lead, Bangalore</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="faq" id="faq">
    <div class="container">
      <div style="text-align:center">
        <div class="section-label">— Support</div>
        <h2 class="section-title reveal">Frequently Asked Questions</h2>
        <p class="section-sub reveal" style="margin-left:auto;margin-right:auto">Everything you need to know about ServeEase.</p>
      </div>
      <div class="faq-list">
        <div class="faq-item active">
          <div class="faq-q" onclick="toggleFaq(this)"><span>What is ServeEase?</span><span class="icon">+</span></div>
          <div class="faq-a" style="max-height:200px">
            <div class="faq-a-inner">ServeEase is a premium on-demand home services platform connecting customers with verified professionals — electricians, plumbers, cleaners, chefs, carpenters and more — through one seamless booking experience with transparent pricing.</div>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-q" onclick="toggleFaq(this)"><span>How do I book a service?</span><span class="icon">+</span></div>
          <div class="faq-a">
            <div class="faq-a-inner">Simply search for the service you need, select your preferred time slot, review the transparent pricing, and confirm your booking. You'll receive instant confirmation and real-time status updates.</div>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-q" onclick="toggleFaq(this)"><span>Are the service professionals verified?</span><span class="icon">+</span></div>
          <div class="faq-a">
            <div class="faq-a-inner">Yes. Every professional undergoes a rigorous background check, skill assessment, and identity verification before being onboarded. We maintain a rating threshold to ensure consistent quality.</div>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-q" onclick="toggleFaq(this)"><span>Can I track my booking in real time?</span><span class="icon">+</span></div>
          <div class="faq-a">
            <div class="faq-a-inner">Absolutely. Once confirmed, your personal dashboard shows live status — from professional assignment and en-route tracking to job completion and payment receipt.</div>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-q" onclick="toggleFaq(this)"><span>What if I'm not satisfied with the service?</span><span class="icon">+</span></div>
          <div class="faq-a">
            <div class="faq-a-inner">We offer a 100% satisfaction guarantee. If you're unhappy with the work, contact our support team within 24 hours and we'll arrange a free re-service or full refund.</div>
          </div>
        </div>
        <div class="faq-item">
          <div class="faq-q" onclick="toggleFaq(this)"><span>How can I become a service partner?</span><span class="icon">+</span></div>
          <div class="faq-a">
            <div class="faq-a-inner">Click "Become a Partner" in the footer, complete your profile with skill details, and pass our verification process. Once approved, you can start accepting jobs and growing your client base immediately.</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA BANNER -->
  <section class="cta-banner">
    <div class="container">
      <div class="cta-box reveal">
        <div class="cta-text">
          <h2>Ready to Get Started?</h2>
          <p>Join over 2 million happy customers. Book your first service today and experience the ServeEase difference.</p>
        </div>
        <a href="#" class="btn btn-white">Book a Service →</a>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="logo"><span class="logo-icon">S</span> ServeEase</div>
          <p>India's most trusted on-demand home services platform. Verified professionals, transparent pricing, satisfaction guaranteed.</p>
        </div>
        <div class="footer-col">
          <h4>Services</h4>
          <a href="#">Deep Cleaning</a><a href="#">Electrician</a><a href="#">Plumbing</a><a href="#">Carpenter</a><a href="#">Chef</a><a href="#">Pest Control</a>
        </div>
        <div class="footer-col">
          <h4>Company</h4>
          <a href="#">About Us</a><a href="#">Careers</a><a href="#">Become a Partner</a><a href="#">Blog</a><a href="#">Press</a>
        </div>
        <div class="footer-col">
          <h4>Support</h4>
          <a href="#">Help Center</a><a href="#">Contact Us</a><a href="#">Terms & Conditions</a><a href="#">Privacy Policy</a><a href="#">Refund Policy</a>
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
    // Scroll nav shadow
    window.addEventListener('scroll', () => {
      document.getElementById('nav').classList.toggle('scrolled', window.scrollY > 20)
    });

    // FAQ toggle
    function toggleFaq(el) {
      const item = el.parentElement;
      const wasActive = item.classList.contains('active');
      document.querySelectorAll('.faq-item').forEach(i => {
        i.classList.remove('active');
        i.querySelector('.faq-a').style.maxHeight = '0'
      });
      if (!wasActive) {
        item.classList.add('active');
        item.querySelector('.faq-a').style.maxHeight = item.querySelector('.faq-a-inner').scrollHeight + 20 + 'px'
      }
    }

    // Reveal on scroll
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('visible');
          obs.unobserve(e.target)
        }
      })
    }, {
      threshold: .15
    });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
  </script>
</body>

</html>