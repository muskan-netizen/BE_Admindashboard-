<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Service Partner Program — Restocare</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<style>
  :root {
    --white: #ffffff;
    --bg: #f7f6f3;
    --surface: #ffffff;
    --border: #e8e4de;
    --border-dark: #d4cfc7;
    --orange: #e8621a;
    --orange-soft: #fdf0e8;
    --orange-mid: #fde0cc;
    --text: #1a1714;
    --text-2: #4a4540;
    --text-3: #8a8078;
    --accent-blue: #1a3a5c;
  }

  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html { scroll-behavior: smooth; }

  body {
    background: var(--bg);
    color: var(--text);
    font-family: 'Outfit', sans-serif;
    font-weight: 400;
    line-height: 1.65;
    -webkit-font-smoothing: antialiased;
  }

  /* ── TOP BAR ── */
  .topbar {
    background: var(--orange);
    color: #fff;
    text-align: center;
    padding: 0.5rem 1rem;
    font-size: 0.78rem;
    font-weight: 500;
    letter-spacing: 0.04em;
  }

  /* ── NAV ── */
  nav {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 1.1rem 5%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky; top: 0; z-index: 100;
  }
  .logo {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text);
    text-decoration: none;
    letter-spacing: -0.5px;
  }
  .logo span { color: var(--orange); }
  .nav-tag {
    font-size: 0.78rem;
    font-weight: 500;
    color: var(--text-3);
    border: 1px solid var(--border-dark);
    padding: 0.3rem 0.85rem;
    border-radius: 50px;
    letter-spacing: 0.04em;
  }

  /* ── HERO ── */
  .hero {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 5rem 5% 4.5rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
  }
  .hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--orange-soft);
    border: 1px solid var(--orange-mid);
    color: var(--orange);
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 0.35rem 0.9rem;
    border-radius: 50px;
    margin-bottom: 1.25rem;
  }
  .hero h1 {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(2.6rem, 4vw, 3.8rem);
    font-weight: 700;
    line-height: 1.08;
    color: var(--text);
    margin-bottom: 1.25rem;
  }
  .hero h1 span { color: var(--orange); }
  .hero p {
    color: var(--text-2);
    font-size: 1.05rem;
    max-width: 440px;
    margin-bottom: 2rem;
    line-height: 1.7;
  }
  .hero-meta {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
  }
  .hero-meta-item { display: flex; flex-direction: column; }
  .hero-meta-item strong {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--orange);
    line-height: 1;
  }
  .hero-meta-item span { font-size: 0.8rem; color: var(--text-3); margin-top: 0.2rem; }

  /* right side visual */
  .hero-visual {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  .info-card {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
    animation: fadeUp 0.5s ease both;
  }
  .info-card:hover { border-color: var(--orange-mid); box-shadow: 0 4px 20px rgba(232,98,26,0.08); }
  .info-card:nth-child(2) { animation-delay: 0.1s; }
  .info-card:nth-child(3) { animation-delay: 0.2s; }
  .info-card-icon {
    width: 40px; height: 40px;
    background: var(--orange-soft);
    border: 1px solid var(--orange-mid);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
  }
  .info-card h4 { font-size: 0.92rem; font-weight: 600; color: var(--text); margin-bottom: 0.2rem; }
  .info-card p { font-size: 0.82rem; color: var(--text-3); line-height: 1.5; }

  /* ── MAIN CONTENT ── */
  .page-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 5%;
  }

  /* ── SECTION STYLES ── */
  .section { padding: 5rem 0; border-bottom: 1px solid var(--border); }
  .section:last-child { border-bottom: none; }

  .section-header { margin-bottom: 3rem; }
  .section-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--orange);
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    margin-bottom: 0.6rem;
  }
  .section-eyebrow::before {
    content: '';
    width: 16px; height: 2px;
    background: var(--orange);
    border-radius: 2px;
  }
  .section-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(1.9rem, 3vw, 2.7rem);
    font-weight: 700;
    color: var(--text);
    line-height: 1.1;
    margin-bottom: 0.75rem;
  }
  .section-desc {
    color: var(--text-2);
    font-size: 1rem;
    max-width: 540px;
    line-height: 1.7;
  }

  /* ── HOW CONNECTION WORKS ── */
  .connection-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5px;
    background: var(--border);
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
  }
  .conn-cell {
    background: var(--white);
    padding: 2.25rem 1.75rem;
    transition: background 0.2s;
  }
  .conn-cell:hover { background: var(--orange-soft); }
  .conn-num {
    font-family: 'Cormorant Garamond', serif;
    font-size: 3rem;
    font-weight: 700;
    color: var(--orange-mid);
    line-height: 1;
    margin-bottom: 0.5rem;
  }
  .conn-cell h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 0.5rem;
  }
  .conn-cell p { font-size: 0.87rem; color: var(--text-2); line-height: 1.55; }

  /* ── ONBOARDING STEPS ── */
  .steps-list {
    display: flex;
    flex-direction: column;
    gap: 0;
    position: relative;
  }
  .steps-list::before {
    content: '';
    position: absolute;
    left: 27px; top: 54px; bottom: 54px;
    width: 1px;
    background: linear-gradient(to bottom, var(--orange), var(--border));
  }
  .step-row {
    display: grid;
    grid-template-columns: 56px 1fr;
    gap: 1.5rem;
    align-items: flex-start;
    padding: 1.75rem 0;
    border-bottom: 1px solid var(--border);
    position: relative;
  }
  .step-row:last-child { border-bottom: none; }
  .step-badge {
    width: 56px; height: 56px;
    background: var(--white);
    border: 2px solid var(--orange);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--orange);
    flex-shrink: 0;
    position: relative; z-index: 1;
  }
  .step-body { padding-top: 0.5rem; }
  .step-body h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 0.3rem;
  }
  .step-body p { color: var(--text-2); font-size: 0.9rem; line-height: 1.6; }
  .step-tag {
    display: inline-block;
    margin-top: 0.5rem;
    background: var(--orange-soft);
    border: 1px solid var(--orange-mid);
    color: var(--orange);
    font-size: 0.73rem;
    font-weight: 600;
    padding: 0.2rem 0.7rem;
    border-radius: 50px;
  }

  /* ── SERVICES TABLE ── */
  .services-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
  }
  .services-table thead {
    background: var(--text);
    color: #fff;
  }
  .services-table thead th {
    padding: 1rem 1.25rem;
    text-align: left;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
  }
  .services-table tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
  }
  .services-table tbody tr:last-child { border-bottom: none; }
  .services-table tbody tr:hover { background: var(--orange-soft); }
  .services-table td {
    padding: 1rem 1.25rem;
    font-size: 0.9rem;
    color: var(--text-2);
    vertical-align: top;
  }
  .services-table td:first-child { color: var(--text); font-weight: 500; }
  .svc-icon { margin-right: 0.5rem; }
  .badge {
    display: inline-block;
    background: #e8f5e9;
    color: #2e7d32;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 0.15rem 0.6rem;
    border-radius: 50px;
  }
  .badge.orange { background: var(--orange-soft); color: var(--orange); }

  /* ── KEY OBLIGATIONS ── */
  .obligations-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
  }
  .obl-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    transition: border-color 0.2s;
  }
  .obl-card:hover { border-color: var(--border-dark); }
  .obl-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
  }
  .obl-icon.green { background: #e8f5e9; }
  .obl-icon.red { background: #fdecea; }
  .obl-icon.blue { background: #e3f0fb; }
  .obl-icon.amber { background: #fff8e1; }
  .obl-card h4 { font-size: 0.92rem; font-weight: 600; color: var(--text); margin-bottom: 0.3rem; }
  .obl-card p { font-size: 0.83rem; color: var(--text-3); line-height: 1.55; }

  /* ── POLICY STRIP ── */
  .policy-strip {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 2.5rem;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 2rem;
  }
  .policy-item { }
  .policy-item h5 {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-3);
    margin-bottom: 0.5rem;
  }
  .policy-item p { font-size: 0.88rem; color: var(--text-2); line-height: 1.55; }
  .policy-item a {
    color: var(--orange);
    text-decoration: none;
    font-size: 0.82rem;
    font-weight: 600;
    display: inline-block;
    margin-top: 0.5rem;
  }
  .policy-item a:hover { text-decoration: underline; }

  /* ── FOOTER ── */
  footer {
    background: var(--text);
    color: rgba(255,255,255,0.5);
    padding: 2.5rem 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
  }
  footer .logo { color: #fff; }
  footer .logo span { color: var(--orange); }
  .footer-links { display: flex; gap: 2rem; flex-wrap: wrap; }
  .footer-links a {
    color: rgba(255,255,255,0.5);
    text-decoration: none;
    font-size: 0.82rem;
    transition: color 0.2s;
  }
  .footer-links a:hover { color: var(--orange); }
  footer p { font-size: 0.78rem; }

  /* ── ANIMATIONS ── */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 860px) {
    .hero { grid-template-columns: 1fr; gap: 2.5rem; }
    .hero-visual { display: none; }
    .connection-grid { grid-template-columns: 1fr; }
    .obligations-grid { grid-template-columns: 1fr; }
    .policy-strip { grid-template-columns: 1fr; }
    .section { padding: 3.5rem 0; }
  }
  @media (max-width: 600px) {
    .hero { padding: 3rem 5% 3rem; }
    footer { flex-direction: column; text-align: center; }
    .footer-links { justify-content: center; }
    .services-table { font-size: 0.82rem; }
    .services-table td, .services-table th { padding: 0.75rem 0.9rem; }
  }
</style>
</head>
<body>

<!-- TOP BAR -->
<div class="topbar">RestroEdge Pvt. Ltd. — Official Service Partner Information Page</div>

<!-- NAV -->
<nav>
  <a class="logo" href="/">Restocare</a>
  <span class="nav-tag">Service Partner Program</span>
</nav>

<!-- HERO -->
<div style="background: var(--white); border-bottom: 1px solid var(--border);">
  <div class="hero">
    <div class="hero-left">
      <div class="hero-eyebrow">✦ Platform Overview</div>
      <h1>How Service Partners Work with <span>Restocare</span></h1>
      <p>Restocare is a technology platform that connects verified, independent service professionals with customers seeking home services — including chefs, technicians, electricians, and more.</p>
      <div class="hero-meta">
        <div class="hero-meta-item">
          <strong>100+</strong>
          <span>Active Partners</span>
        </div>
        <div class="hero-meta-item">
          <strong>7+</strong>
          <span>Service Categories</span>
        </div>
        <div class="hero-meta-item">
          <strong>Delhi-NCR</strong>
          <span>Coverage</span>
        </div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="info-card">
        <div class="info-card-icon">🧑‍💼</div>
        <div>
          <h4>Independent Contractors</h4>
          <p>All service professionals operate as independent contractors — not employees of Restocare.</p>
        </div>
      </div>
      <div class="info-card">
        <div class="info-card-icon">📱</div>
        <div>
          <h4>Platform-Mediated Bookings</h4>
          <p>All service requests are assigned and managed exclusively through the Restocare app.</p>
        </div>
      </div>
      <div class="info-card">
        <div class="info-card-icon">🔒</div>
        <div>
          <h4>Verified & Trained</h4>
          <p>Every partner undergoes background verification, document checks, and mandatory training before going live.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<div class="page-content">

  <!-- SECTION 1: HOW THE CONNECTION WORKS -->
  <div class="section">
    <div class="section-header">
      <div class="section-eyebrow">Platform Model</div>
      <h2 class="section-title">How the Platform Connects<br/>Partners & Customers</h2>
      <p class="section-desc">Restocare acts solely as a technology intermediary — matching customer demand with skilled independent professionals in real time.</p>
    </div>
    <div class="connection-grid">
      <div class="conn-cell">
        <div class="conn-num">01</div>
        <h4>Customer Places a Request</h4>
        <p>A customer opens the Restocare app, selects a service category (e.g., Chef, AC Technician), picks a time slot, and confirms their booking.</p>
      </div>
      <div class="conn-cell">
        <div class="conn-num">02</div>
        <h4>Platform Matches a Partner</h4>
        <p>Restocare's system identifies the best available, verified service professional nearby based on skill, rating, and location — and assigns the job.</p>
      </div>
      <div class="conn-cell">
        <div class="conn-num">03</div>
        <h4>Partner Accepts & Arrives</h4>
        <p>The service professional receives the booking notification through the app, accepts it, and travels to the customer's location to complete the service.</p>
      </div>
      <div class="conn-cell">
        <div class="conn-num">04</div>
        <h4>Service is Delivered</h4>
        <p>The partner performs the service following Restocare's quality standards, safety protocols, and code of conduct established for their category.</p>
      </div>
      <div class="conn-cell">
        <div class="conn-num">05</div>
        <h4>Payment via Platform</h4>
        <p>All payments are processed securely through the Restocare platform. Partners are never permitted to accept cash or off-platform payments from customers.</p>
      </div>
      <div class="conn-cell">
        <div class="conn-num">06</div>
        <h4>Rating & Payout</h4>
        <p>The customer rates the service. The partner receives their earnings (after platform commission deduction) directly in their linked account.</p>
      </div>
    </div>
  </div>

  <!-- SECTION 2: SERVICES OFFERED -->
  <div class="section">
    <div class="section-header">
      <div class="section-eyebrow">Service Categories</div>
      <h2 class="section-title">Services Available on<br/>the Restocare Platform</h2>
      <p class="section-desc">Partners are onboarded under specific service categories. Each category has its own qualification and certification requirements.</p>
    </div>
    <table class="services-table">
      <thead>
        <tr>
          <th>Service Category</th>
          <th>Type of Work</th>
          <th>Required Credentials</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="svc-icon">👨‍🍳</span>Restaurant Chef</td>
          <td>Personal cooking, event catering, diet meal prep, special occasions</td>
          <td>Culinary certification or equivalent experience; Food hygiene compliance</td>
          <td><span class="badge">Active</span></td>
        </tr>
        <tr>
          <td><span class="svc-icon">🔧</span>Appliance Technician</td>
          <td>Repair of ACs, washing machines, refrigerators, geysers, microwaves</td>
          <td>Manufacturer or trade certification; ITI qualification preferred</td>
          <td><span class="badge">Active</span></td>
        </tr>
        <tr>
          <td><span class="svc-icon">🔌</span>Electrician</td>
          <td>Wiring, installations, circuit repairs, electrical inspections</td>
          <td>Licensed electrician certificate; Government permit where applicable</td>
          <td><span class="badge">Active</span></td>
        </tr>
        <tr>
          <td><span class="svc-icon">🪠</span>Plumber</td>
          <td>Pipe fitting, leak repairs, bathroom fixture installation, drainage</td>
          <td>Trade certification; Relevant work experience</td>
          <td><span class="badge">Active</span></td>
        </tr>
        <tr>
          <td><span class="svc-icon">🧹</span>Home Cleaning</td>
          <td>Deep cleaning, sofa cleaning, bathroom sanitization, full-home packages</td>
          <td>Restocare training completion; Background verification</td>
          <td><span class="badge">Active</span></td>
        </tr>
        <!-- <tr>
          <td><span class="svc-icon">💆</span>Wellness Professional</td>
          <td>Massage therapy, beauty services, wellness treatments at home</td>
          <td>Professional certification from recognized institute</td>
          <td><span class="badge orange">Coming Soon</span></td>
        </tr> -->
      </tbody>
    </table>
  </div>

  <!-- SECTION 3: PARTNER ONBOARDING PROCESS -->
  <div class="section">
    <div class="section-header">
      <div class="section-eyebrow">Onboarding Process</div>
      <h2 class="section-title">How a Service Professional<br/>Joins Restocare</h2>
      <p class="section-desc">Every partner goes through a structured onboarding process before they are permitted to accept any bookings on the platform.</p>
    </div>
    <div class="steps-list">
      <div class="step-row">
        <div class="step-badge">1</div>
        <div class="step-body">
          <h4>Online Registration</h4>
          <p>The applicant submits a registration form through the Restocare app or website, providing personal details, service category, years of experience, and location.</p>
          <span class="step-tag">📱 Via App / Website</span>
        </div>
      </div>
      <div class="step-row">
        <div class="step-badge">2</div>
        <div class="step-body">
          <h4>Document Submission & KYC</h4>
          <p>Applicants must submit valid government-issued identity proof, professional licenses or certifications relevant to their service category, and any trade permits required by law. The Restocare verification team reviews all documents.</p>
          <span class="step-tag">🪪 Aadhaar / PAN / Trade License</span>
        </div>
      </div>
      <div class="step-row">
        <div class="step-badge">3</div>
        <div class="step-body">
          <h4>Background Verification</h4>
          <p>A background verification is conducted to ensure the safety and trustworthiness of every professional before they are permitted to interact with customers.</p>
          <span class="step-tag">✅ Safety First</span>
        </div>
      </div>
      <div class="step-row">
        <div class="step-badge">4</div>
        <div class="step-body">
          <h4>Mandatory Platform Training</h4>
          <p>All partners must complete Restocare's onboarding training program. This covers service quality standards, safety protocols, customer interaction guidelines, platform usage, and the partner code of conduct. Failure to complete training may result in account suspension.</p>
          <span class="step-tag">🎓 Completion Required</span>
        </div>
      </div>
      <div class="step-row">
        <div class="step-badge">5</div>
        <div class="step-body">
          <h4>Security Deposit (If Applicable)</h4>
          <p>Depending on service category and risk profile, a refundable security deposit may be required. This deposit is non-interest bearing and is refunded upon termination of partnership after settling any outstanding dues.</p>
          <span class="step-tag">🔐 Fully Refundable</span>
        </div>
      </div>
      <div class="step-row">
        <div class="step-badge">6</div>
        <div class="step-body">
          <h4>Profile Activated — Partner Goes Live</h4>
          <p>Once all steps are complete, the partner's profile is activated on the platform. They can now receive booking requests, manage their schedule, and begin earning through Restocare.</p>
          <span class="step-tag">🚀 Start Accepting Jobs</span>
        </div>
      </div>
    </div>
  </div>

  <!-- SECTION 4: KEY OBLIGATIONS -->
  <div class="section">
    <div class="section-header">
      <div class="section-eyebrow">Partner Responsibilities</div>
      <h2 class="section-title">Rights & Obligations of<br/>Service Partners</h2>
      <p class="section-desc">Partners have defined rights and responsibilities to maintain the quality, trust, and integrity of the Restocare platform.</p>
    </div>
    <div class="obligations-grid">
      <div class="obl-card">
        <div class="obl-icon green">✅</div>
        <div>
          <h4>Independent Contractor Status</h4>
          <p>Partners operate as independent professionals — not employees of Restocare. They are responsible for their own taxes, statutory dues, and regulatory compliance.</p>
        </div>
      </div>
      <div class="obl-card">
        <div class="obl-icon red">🚫</div>
        <div>
          <h4>No Off-Platform Transactions</h4>
          <p>Partners must not solicit direct payments or arrange private services with any customer introduced through Restocare. Violations may result in permanent deactivation.</p>
        </div>
      </div>
      <div class="obl-card">
        <div class="obl-icon blue">⭐</div>
        <div>
          <h4>Minimum Rating Standard</h4>
          <p>Partners must maintain the minimum rating threshold set by Restocare. Consistently low ratings may lead to warnings, retraining, temporary suspension, or deactivation.</p>
        </div>
      </div>
      <div class="obl-card">
        <div class="obl-icon amber">🔒</div>
        <div>
          <h4>Customer Data Confidentiality</h4>
          <p>All customer information accessed through the platform must be kept strictly confidential and used solely for completing the assigned service — never for personal or marketing use.</p>
        </div>
      </div>
      <div class="obl-card">
        <div class="obl-icon green">🛡️</div>
        <div>
          <h4>Non-Exclusive Partnership</h4>
          <p>Restocare operates a non-exclusive partnership. Partners are free to work independently or with other platforms outside of Restocare-generated leads.</p>
        </div>
      </div>
      <div class="obl-card">
        <div class="obl-icon blue">📋</div>
        <div>
          <h4>Professional Conduct & Safety</h4>
          <p>Partners must maintain high standards of punctuality, hygiene, respectful conduct, and safe service delivery in accordance with Restocare's operational guidelines.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- SECTION 5: LEGAL & POLICIES -->
  <div class="section">
    <div class="section-header">
      <div class="section-eyebrow">Legal Framework</div>
      <h2 class="section-title">Governing Policies</h2>
      <p class="section-desc">All service partner relationships are governed by the following documents, in accordance with the laws of India.</p>
    </div>
    <div class="policy-strip">
      <div class="policy-item">
        <h5>Terms & Conditions</h5>
        <p>The complete legal agreement governing the service partner relationship — covering status, fees, security deposit, termination, and liability.</p>
        <a href="https://restocare.in/page/terms-and-conditions" target="_blank">Read Full Terms →</a>
      </div>
      <div class="policy-item">
        <h5>Privacy Policy</h5>
        <p>Details on how Restocare collects, processes, stores, and protects personal data of partners and customers on the platform.</p>
        <a href="https://restocare.in/page/privacy-policy" target="_blank">Read Privacy Policy →</a>
      </div>
      <div class="policy-item">
        <h5>Refund, Cancellation & Payment Policy</h5>
        <p>Details on how the refund, cancellation and payout works at <strong>RestoCare</strong>.</p>
        <a href="https://restocare.in/refund-policy" target="_blank">View Legal Terms →</a>
      </div>
    </div>
  </div>

</div><!-- end page-content -->

<!-- FOOTER -->
<footer>
  <a class="logo" href="#">Restocare</a>
  <p style="font-size:0.8rem;">© 2025 Restocare Pvt. Ltd. All rights reserved.</p>
  <div class="footer-links">
    <a href="terms.html">Terms & Conditions</a>
    <a href="privacy.html">Privacy Policy</a>
    <a href="service-partner-info.html">How It Works</a>
    <a href="#">Contact Us</a>
  </div>
</footer>

<script>
// Scroll reveal
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.style.opacity = '1';
      e.target.style.transform = 'translateY(0)';
    }
  });
}, { threshold: 0.08 });

document.querySelectorAll('.conn-cell, .step-row, .obl-card, .info-card').forEach((el, i) => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(16px)';
  el.style.transition = `opacity 0.45s ${i * 0.05}s ease, transform 0.45s ${i * 0.05}s ease`;
  observer.observe(el);
});
</script>
</body>
</html>
