<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Toptter | Landing Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --toptter-green: #134a45;
      --toptter-accent: #be5249;
      --toptter-gold: #b4ae64;
      --toptter-bg: #fbfbfb;
      --toptter-darktext: #222;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: var(--toptter-bg);
      color: var(--toptter-darktext);
    }
    .navbar {
      box-shadow: 0 2px 12px 0 rgba(0,0,0,0.03);
    }
    .main-hero {
      background: var(--toptter-green);
      color: #fff;
      padding: 60px 0 40px 0;
      text-align: center;
    }
    .main-hero .btn {
      margin: 10px 8px 0 0;
      font-weight: 600;
    }
    .brand-logo {
      height: 46px;
      margin-right: 8px;
    }
    .stats-section {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 2px 12px 0 rgba(0,0,0,0.07);
      margin: -40px auto 30px auto;
      padding: 24px 12px;
      max-width: 900px;
      display: flex;
      flex-direction: row;
      justify-content: center;
      gap: 48px;
    }
    .stat-card {
      text-align: center;
    }
    .feature-section {
      background: #fff0ee;
      padding: 60px 0 40px 0;
    }
    .feature-icon {
      width: 48px;
      height: 48px;
      margin-bottom: 12px;
      object-fit: contain;
      filter: grayscale(30%);
    }
    .service-card {
      border: none;
      border-radius: 14px;
      box-shadow: 0 2px 8px 0 rgba(0,0,0,0.05);
      padding: 30px 18px;
      margin-bottom: 22px;
      text-align: center;
    }
    .download-section {
      padding: 40px 0 24px 0;
      text-align: center;
    }
    .footer {
      background: #f7f7f7;
      color: #777;
      text-align: center;
      padding: 20px 0;
      font-size: 15px;
    }
    @media (max-width: 767px) {
      .stats-section {
        flex-direction: column;
        gap: 20px;
        max-width: 96%;
      }
      .feature-section .row {
        --bs-gutter-x: 0;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg bg-white">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="https://ext.same-assets.com/2968786203/1191543175.webp" class="brand-logo" alt="Toptter logo">
        <strong style="color: var(--toptter-green); font-size: 1.55rem;">Toptter</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="#download">Download</a></li>
          <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <section class="main-hero">
    <div class="container">
      <h1 style="font-size: 2.3rem; font-weight: 700; line-height:1.2;">India’s #1 Delivery & Service Super App</h1>
      <p style="max-width:480px;margin:16px auto;">Experience fast & easy delivery for food, groceries, essentials, and more with Toptter.</p>
      <a href="#download" class="btn btn-light" style="color: var(--toptter-green);border-radius:7px;">Download on App Store</a>
      <a href="#download" class="btn btn-outline-light" style="border-radius:7px;border: 2px solid #fff;">Get it on Google Play</a>
    </div>
  </section>
  <div class="stats-section">
    <div class="stat-card">
      <div style="font-size:1.6rem;font-weight:700;color: var(--toptter-accent);">10,000+</div>
      <div>Vendors & Restaurants</div>
    </div>
    <div class="stat-card">
      <div style="font-size:1.6rem;font-weight:700;color: var(--toptter-green);">50+</div>
      <div>Cities Served</div>
    </div>
    <div class="stat-card">
      <div style="font-size:1.6rem;font-weight:700;color: var(--toptter-gold);">2M+</div>
      <div>Deliveries</div>
    </div>
  </div>
  <section id="features" class="feature-section">
    <div class="container">
      <h2 class="text-center mb-4" style="font-weight:600;color:var(--toptter-green)">Services on Toptter</h2>
      <div class="row justify-content-center">
        <div class="col-md-4 col-12">
          <div class="service-card">
            <img src="https://ext.same-assets.com/2968786203/219239471.png" alt="delivery" class="feature-icon">
            <h5 style="color:var(--toptter-accent)">Delivery</h5>
            <p>Food, groceries, and more delivered to you in minutes!</p>
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div class="service-card">
            <img src="https://ext.same-assets.com/2968786203/219239471.png" alt="dine in" class="feature-icon">
            <h5 style="color:var(--toptter-green)">Dine-In</h5>
            <p>Reserve a table at your favorite local spot with ease.</p>
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div class="service-card">
            <img src="https://ext.same-assets.com/2968786203/219239471.png" alt="Takeaway" class="feature-icon">
            <h5 style="color:var(--toptter-gold)">Takeaway</h5>
            <p>Order ahead and pick up fresh food anytime.</p>
          </div>
        </div>
      </div>
      <div class="row justify-content-center mt-4">
        <div class="col-md-4 col-12">
          <div class="service-card">
            <img src="https://ext.same-assets.com/2968786203/219239471.png" alt="Pick & Drop" class="feature-icon">
            <h5 style="color:var(--toptter-accent)">Pick & Drop</h5>
            <p>Send parcels and essentials across city, fast and safe.</p>
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div class="service-card">
            <img src="https://ext.same-assets.com/2968786203/219239471.png" alt="On Demand" class="feature-icon">
            <h5 style="color:var(--toptter-green)">On-Demand Services</h5>
            <p>Home, repair, beauty and utility services at your door.</p>
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div class="service-card">
            <img src="https://ext.same-assets.com/2968786203/219239471.png" alt="Appointment" class="feature-icon">
            <h5 style="color:var(--toptter-gold)">Appointment</h5>
            <p>Book appointments for clinics, wellness, and more.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="download-section" id="download">
    <div class="container">
      <h4 style="font-weight:600;color:var(--toptter-green)">Download the Toptter App</h4>
      <p>Get exclusive offers, track your order, and enjoy seamless service everywhere.</p>
      <img src="https://ext.same-assets.com/2968786203/350015891.svg" height="48" alt="App Store" style="margin:6px 10px 6px 0;">
      <img src="https://ext.same-assets.com/2968786203/3005067254.svg" height="48" alt="Google Play">
    </div>
  </section>
  <footer class="footer" id="contact">
    <div>Contact: CDCL Building, 28B, Sector 28, Chandigarh, India | 📞 3434534543543 | ✉️ <a href="mailto:topteer@gmail.com">topteer@gmail.com</a></div>
    <div style="color:#aaa; margin-top:4px;">&copy; 2024-25 | All rights reserved</div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
