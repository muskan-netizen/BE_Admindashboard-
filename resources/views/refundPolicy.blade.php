<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Refund, Cancellation & Payment Policy – RestoCare</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Source+Sans+3:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --bg: #f7f5f2;
      --surface: #ffffff;
      --border: #e4ddd4;
      --accent: #b5651d;
      --accent-light: #f0e6d8;
      --text-primary: #1c1a18;
      --text-secondary: #5c5550;
      --text-muted: #9a918a;
      --heading-font: 'Playfair Display', Georgia, serif;
      --body-font: 'Source Sans 3', 'Segoe UI', sans-serif;
      --radius: 6px;
      --shadow: 0 2px 16px rgba(0,0,0,0.06);
    }
    a{
      text-decoration: none;
      color: var(--accent);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html { scroll-behavior: smooth; }

    body {
      font-family: var(--body-font);
      background: var(--bg);
      color: var(--text-primary);
      font-size: 15.5px;
      line-height: 1.75;
      font-weight: 400;
    }

    /* ── Header ── */
    header {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 0 2rem;
      position: sticky;
      top: 0;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 62px;
      box-shadow: 0 1px 8px rgba(0,0,0,0.05);
    }
    .logo {
      font-family: var(--heading-font);
      font-size: 1.35rem;
      font-weight: 700;
      color: var(--accent);
      letter-spacing: 0.02em;
    }
    .logo span { color: var(--text-primary); }
    .badge {
      font-size: 0.72rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--text-muted);
      border: 1px solid var(--border);
      padding: 4px 10px;
      border-radius: 20px;
    }

    /* ── Hero ── */
    .hero {
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 56px 2rem 48px;
      text-align: center;
    }
    .hero-tag {
      display: inline-block;
      font-size: 0.72rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.12em;
      color: var(--accent);
      background: var(--accent-light);
      padding: 5px 14px;
      border-radius: 20px;
      margin-bottom: 20px;
    }
    .hero h1 {
      font-family: var(--heading-font);
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      font-weight: 700;
      color: var(--text-primary);
      line-height: 1.2;
      max-width: 680px;
      margin: 0 auto 16px;
    }
    .hero p {
      color: var(--text-secondary);
      font-size: 0.95rem;
      max-width: 540px;
      margin: 0 auto;
    }
    .hero-divider {
      width: 48px;
      height: 2px;
      background: var(--accent);
      margin: 28px auto 0;
      border-radius: 2px;
    }

    /* ── Layout ── */
    .page-wrap {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 1.5rem;
    }
    .content-wrap {
      display: grid;
      grid-template-columns: 220px 1fr;
      gap: 2.5rem;
      padding: 3rem 0 5rem;
      align-items: start;
    }

    /* ── Sidebar TOC ── */
    .toc {
      position: sticky;
      top: 78px;
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 20px 0;
      box-shadow: var(--shadow);
    }
    .toc-title {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.12em;
      color: var(--text-muted);
      padding: 0 18px 10px;
      border-bottom: 1px solid var(--border);
      margin-bottom: 8px;
    }
    .toc a {
      display: block;
      padding: 6px 18px;
      font-size: 0.8rem;
      color: var(--text-secondary);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.15s, background 0.15s;
      line-height: 1.4;
    }
    .toc a:hover {
      color: var(--accent);
      background: var(--accent-light);
    }

    /* ── Sections ── */
    .section {
      background: var(--surface);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      margin-bottom: 1.25rem;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: box-shadow 0.2s;
    }
    .section:hover { box-shadow: 0 4px 24px rgba(0,0,0,0.09); }

    .section-header {
      display: flex;
      align-items: flex-start;
      gap: 14px;
      padding: 22px 26px 18px;
      border-bottom: 1px solid var(--border);
      background: #fdfcfb;
    }
    .section-num {
      flex-shrink: 0;
      width: 30px;
      height: 30px;
      background: var(--accent);
      color: #fff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.72rem;
      font-weight: 700;
      margin-top: 2px;
    }
    .section-title {
      font-family: var(--heading-font);
      font-size: 1.05rem;
      font-weight: 600;
      color: var(--text-primary);
      line-height: 1.3;
    }

    .section-body {
      padding: 22px 26px 24px;
    }

    /* sub-headings inside sections */
    .sub-heading {
      font-family: var(--body-font);
      font-size: 0.85rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: var(--accent);
      margin: 18px 0 6px;
    }
    .sub-heading:first-child { margin-top: 0; }

    p { margin-bottom: 10px; color: var(--text-secondary); }
    p:last-child { margin-bottom: 0; }

    ul {
      list-style: none;
      margin: 8px 0 10px;
      padding: 0;
    }
    ul li {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      color: var(--text-secondary);
      padding: 3px 0;
      font-size: 0.94rem;
    }
    ul li::before {
      content: '';
      width: 6px;
      height: 6px;
      background: var(--accent);
      border-radius: 50%;
      flex-shrink: 0;
      margin-top: 8px;
    }

    /* Info rows / data tables */
    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 14px;
      border-radius: 4px;
      border: 1px solid var(--border);
      margin-bottom: 8px;
      background: var(--bg);
      font-size: 0.93rem;
      gap: 16px;
    }
    .info-row:last-child { margin-bottom: 0; }
    .info-row .label { color: var(--text-secondary); font-weight: 400; }
    .info-row .value { font-weight: 600; color: var(--text-primary); white-space: nowrap; }
    .value.green { color: #2e7d4f; }
    .value.amber { color: #b07d1a; }
    .value.red { color: #b5341d; }

    /* callout box */
    .callout {
      background: var(--accent-light);
      border-left: 3px solid var(--accent);
      border-radius: 0 4px 4px 0;
      padding: 12px 16px;
      margin: 14px 0;
      font-size: 0.93rem;
      color: var(--text-secondary);
    }
    .callout strong { color: var(--text-primary); }

    /* ── Additional section (no number) ── */
    .section-header.alt { background: var(--accent-light); }
    .section-num.alt { background: var(--text-primary); }

    /* ── Footer ── */
    footer {
      background: var(--text-primary);
      color: var(--text-muted);
      text-align: center;
      padding: 28px 1.5rem;
      font-size: 0.82rem;
    }
    footer strong { color: #fff; }

    /* ── Responsive ── */
    @media (max-width: 760px) {
      .content-wrap { grid-template-columns: 1fr; }
      .toc { position: static; }
      .section-body { padding: 18px; }
      .info-row { flex-direction: column; align-items: flex-start; gap: 4px; }
    }
  </style>
</head>
<body>

<!-- Header -->
<header>
  <div class="logo"><a href="https://restocare.in">Resto<span>Care</span></a></div>
  <span class="badge">Legal Document</span>
</header>

<!-- Hero -->
<div class="hero">
  <div class="hero-tag">Policy Document</div>
  <h1>Refund, Cancellation &amp; Payment Policy</h1>
  <p>RestoCare — By booking or using services on the platform, customers and service partners agree to the following terms.</p>
  <div class="hero-divider"></div>
</div>

<!-- Body -->
<div class="page-wrap">
  <div class="content-wrap">

    <!-- TOC -->
    <nav class="toc" aria-label="Table of Contents">
      <div class="toc-title">Contents</div>
      <a href="#s1">1. Customer Cancellation</a>
      <a href="#s2">2. After Partner Assignment</a>
      <a href="#s3">3. Customer Default / No-Show</a>
      <a href="#s4">4. Provider Cancellation</a>
      <a href="#s5">5. Platform Cancellation</a>
      <a href="#s6">6. Service Quality Issues</a>
      <a href="#s7">7. Service Warranty</a>
      <a href="#s8">8. Payment Refunds</a>
      <a href="#s9">9. Partial Refunds</a>
      <a href="#s10">10. Non-Refundable Conditions</a>
      <a href="#s11">11. Rescheduling Policy</a>
      <a href="#s12">12. Waiting Charges</a>
      <a href="#s13">13. Subscriptions / Packages</a>
      <a href="#s14">14. Force Majeure</a>
      <a href="#s15">15. Refund Processing</a>
      <a href="#s16">16. Taxation</a>
      <a href="#s17">17. Partner Payout Terms</a>
      <a href="#s18">18. Dispute Resolution</a>
      <a href="#s19">Additional Terms</a>
    </nav>

    <!-- Sections -->
    <main>

      <!-- 1 -->
      <div class="section" id="s1">
        <div class="section-header">
          <div class="section-num">1</div>
          <div class="section-title">Customer-Initiated Cancellation (Time-Based)</div>
        </div>
        <div class="section-body">
          <div class="sub-heading">1.1 Immediate Cancellation</div>
          <p>If the customer cancels the booking immediately after placing the order (e.g., within a few minutes) and no service partner has been assigned, the customer will be eligible for a 100% refund.</p>

          <div class="sub-heading">1.2 Cancellation Within Limited Time Window</div>
          <p>If cancellation occurs within the permitted window (for example 10 mins after the booking is confirmed), the platform may deduct applicable cancellation charges.</p>

          <div class="sub-heading">1.3 Last-Minute Cancellation</div>
          <p>If cancellation occurs less than 30 minutes before the scheduled service partner is about to reach the service location, it will be treated as last-minute cancellation and may be non-refundable or subject to applicable charges.</p>

          <div class="sub-heading">1.4 Cancellation After Service Start</div>
          <p>Once the service has started or partially started, the booking cannot be cancelled and no refund will be provided (0%).</p>
        </div>
      </div>

      <!-- 2 -->
      <div class="section" id="s2">
        <div class="section-header">
          <div class="section-num">2</div>
          <div class="section-title">Cancellation After Service Professional Assignment</div>
        </div>
        <div class="section-body">
          <div class="info-row"><span class="label">Service partner assigned but not yet dispatched</span><span class="value amber">10% cancellation charge</span></div>
          <div class="info-row"><span class="label">Service partner already en route to the location</span><span class="value amber">20% cancellation charge</span></div>
          <div class="info-row"><span class="label">Service partner has arrived at the location</span><span class="value red">30% cancellation charge</span></div>
        </div>
      </div>

      <!-- 3 -->
      <div class="section" id="s3">
        <div class="section-header">
          <div class="section-num">3</div>
          <div class="section-title">Customer Default / No-Show</div>
        </div>
        <div class="section-body">
          <div class="callout"><strong>A 50% service charge may apply if:</strong></div>
          <ul>
            <li>Customer is not available at the service location</li>
            <li>Customer does not respond to calls or messages</li>
            <li>Customer provides an incorrect or incomplete address</li>
            <li>Customer denies entry or access to the service partner</li>
          </ul>
        </div>
      </div>

      <!-- 4 -->
      <div class="section" id="s4">
        <div class="section-header">
          <div class="section-num">4</div>
          <div class="section-title">Service Provider-Initiated Cancellation</div>
        </div>
        <div class="section-body">
          <p>If the service partner cancels, the platform will attempt to assign another partner. If no replacement partner is available, the customer will receive a 100% refund.</p>
        </div>
      </div>

      <!-- 5 -->
      <div class="section" id="s5">
        <div class="section-header">
          <div class="section-num">5</div>
          <div class="section-title">Platform-Initiated Cancellation</div>
        </div>
        <div class="section-body">
          <p>Bookings may be cancelled due to:</p>
          <ul>
            <li>No service partner available</li>
            <li>Technical/system issues</li>
            <li>Safety concerns</li>
            <li>Service not feasible at location</li>
          </ul>
          <div class="callout">If service cannot be arranged, a <strong>100% refund</strong> will be issued.</div>
        </div>
      </div>

      <!-- 6 -->
      <div class="section" id="s6">
        <div class="section-header">
          <div class="section-num">6</div>
          <div class="section-title">Service Quality Issues (Post-Service)</div>
        </div>
        <div class="section-body">
          <p>Customers may receive re-service or corrective service if:</p>
          <ul>
            <li>Service quality is poor</li>
            <li>Service is incomplete</li>
            <li>Wrong service is delivered</li>
            <li>Damage occurs during service (compensation up to ₹10,000)</li>
            <li>Service does not match description</li>
          </ul>
        </div>
      </div>

      <!-- 7 -->
      <div class="section" id="s7">
        <div class="section-header">
          <div class="section-num">7</div>
          <div class="section-title">Service Warranty</div>
        </div>
        <div class="section-body">
          <p>Customers may receive up to 3 months warranty depending on service type. Warranty issues will be resolved by assigning another service professional.</p>
        </div>
      </div>

      <!-- 8 -->
      <div class="section" id="s8">
        <div class="section-header">
          <div class="section-num">8</div>
          <div class="section-title">Payment-Related Refunds</div>
        </div>
        <div class="section-body">
          <p>100% refunds apply for:</p>
          <ul>
            <li>Double payment</li>
            <li>Failed transaction where amount was deducted</li>
            <li>Incorrect billing amount</li>
          </ul>
          <p style="margin-top:12px;">Coupons not applied correctly will be refunded only up to the coupon value.</p>
        </div>
      </div>

      <!-- 9 -->
      <div class="section" id="s9">
        <div class="section-header">
          <div class="section-num">9</div>
          <div class="section-title">Partial Refund Conditions</div>
        </div>
        <div class="section-body">
          <p>Partial refunds may apply if:</p>
          <ul>
            <li>Service partially completed</li>
            <li>Materials used but work incomplete</li>
            <li>Mid-service cancellation</li>
          </ul>
        </div>
      </div>

      <!-- 10 -->
      <div class="section" id="s10">
        <div class="section-header">
          <div class="section-num">10</div>
          <div class="section-title">Non-Refundable Conditions</div>
        </div>
        <div class="section-body">
          <p>Refunds are not applicable for:</p>
          <ul>
            <li>Last-minute cancellations</li>
            <li>Customer no-show</li>
            <li>Completed services</li>
            <li>Change of mind after service</li>
            <li>Third-party material costs</li>
          </ul>
        </div>
      </div>

      <!-- 11 -->
      <div class="section" id="s11">
        <div class="section-header">
          <div class="section-num">11</div>
          <div class="section-title">Rescheduling Policy</div>
        </div>
        <div class="section-body">
          <p>Customers may request to reschedule a booked service instead of cancelling, subject to the following conditions:</p>
          <ul>
            <li>Rescheduling requests must be made within the permitted time window before the scheduled service time.</li>
            <li>One reschedule may be allowed without additional charges, depending on availability and timing.</li>
            <li>Additional or last-minute rescheduling requests may attract rescheduling fees as determined by the platform.</li>
            <li>The platform reserves the right to limit the number of rescheduling requests for a single booking.</li>
            <li>Rescheduling is subject to service partner availability and operational feasibility.</li>
            <li>Failure to comply with the above conditions may result in the booking being treated as a cancellation under the applicable cancellation policy.</li>
          </ul>
        </div>
      </div>

      <!-- 12 -->
      <div class="section" id="s12">
        <div class="section-header">
          <div class="section-num">12</div>
          <div class="section-title">Waiting Charges</div>
        </div>
        <div class="section-body">
          <p>If a service partner is required to wait due to delays caused by the customer, the following conditions will apply:</p>
          <ul>
            <li>A grace waiting period may be provided after the scheduled service time.</li>
            <li>If the delay exceeds the grace period, waiting charges may be applied at the discretion of the platform.</li>
            <li>Delays caused by the customer that result in late service start or extended waiting time may also attract additional convenience or operational charges.</li>
            <li>If the delay becomes excessive and the service cannot be completed, the booking may be treated as a customer cancellation and applicable charges may apply.</li>
          </ul>
        </div>
      </div>

      <!-- 13 -->
      <div class="section" id="s13">
        <div class="section-header">
          <div class="section-num">13</div>
          <div class="section-title">Subscription / Package Services</div>
        </div>
        <div class="section-body">
          <p>Subscription or package-based services offered on the platform may be subject to the following conditions:</p>
          <ul>
            <li>All packages may have a defined validity or expiry period, after which unused sessions may expire.</li>
            <li>Refunds, if applicable, may be issued only for unused sessions and may be subject to review and approval by the platform.</li>
            <li>Used or partially consumed sessions are non-refundable.</li>
            <li>The platform reserves the right to modify or discontinue subscription packages, subject to applicable terms and conditions.</li>
            <li>Package services are non-transferable and may only be used by the registered customer account unless otherwise permitted by the platform.</li>
          </ul>
        </div>
      </div>

      <!-- 14 -->
      <div class="section" id="s14">
        <div class="section-header">
          <div class="section-num">14</div>
          <div class="section-title">Force Majeure</div>
        </div>
        <div class="section-body">
          <p>The platform shall not be held responsible for any delay, interruption, or cancellation of services caused by events beyond its reasonable control. Such events may include, but are not limited to:</p>
          <ul>
            <li>Severe weather conditions, natural disasters, or environmental disruptions.</li>
            <li>Strikes, labor disputes, or transportation disruptions affecting service operations.</li>
            <li>Government restrictions, lockdowns, or regulatory actions that prevent service delivery.</li>
            <li>Emergency situations or unforeseen circumstances that make the service unsafe or impossible to perform.</li>
          </ul>
          <p>In such cases, the platform may reschedule the service or cancel the booking without liability, and refunds, if applicable, will be processed according to the platform's refund policy.</p>
        </div>
      </div>

      <!-- 15 -->
      <div class="section" id="s15">
        <div class="section-header">
          <div class="section-num">15</div>
          <div class="section-title">Refund Processing</div>
        </div>
        <div class="section-body">
          <p>Approved refunds will be processed in accordance with the following conditions:</p>
          <ul>
            <li>Once a refund request is reviewed and approved, the refund will be processed within 7–10 business days.</li>
            <li>Refunds may be credited through the original payment method used for the transaction or to the platform wallet, as determined by the platform.</li>
            <li>Any refund amount credited to the platform wallet cannot be withdrawn or transferred to a bank account.</li>
            <li>Wallet balances may only be used for future service bookings on the platform.</li>
            <li>The platform reserves the right to deduct applicable payment gateway charges, taxes, or processing fees, where applicable.</li>
          </ul>
        </div>
      </div>

      <!-- 16 -->
      <div class="section" id="s16">
        <div class="section-header">
          <div class="section-num">16</div>
          <div class="section-title">Taxation</div>
        </div>
        <div class="section-body">
          <p>All services are subject to 18% GST as per Indian tax laws. Prices shown on the platform are exclusive of GST unless stated otherwise.</p>
        </div>
      </div>

      <!-- 17 -->
      <div class="section" id="s17">
        <div class="section-header">
          <div class="section-num">17</div>
          <div class="section-title">Service Partner / Driver Payout Terms</div>
        </div>
        <div class="section-body">
          <p>Service partners and drivers associated with RestoCare shall be subject to the following payout conditions:</p>
          <div class="info-row"><span class="label">Payment processing timeline</span><span class="value">7–10 business days after successful completion</span></div>
          <div class="info-row"><span class="label">Lead generation fee</span><span class="value">₹30 per lead</span></div>
          <div class="info-row"><span class="label">Platform service fee (commission)</span><span class="value amber">20% of total service amount</span></div>
          <div class="info-row"><span class="label">Service partner earnings (approx.)</span><span class="value green">~80% of total service earnings</span></div>
          <p style="margin-top:14px;">All payouts will be processed according to the platform's internal verification, dispute resolution, and payment cycle policies.</p>
        </div>
      </div>

      <!-- 18 -->
      <div class="section" id="s18">
        <div class="section-header">
          <div class="section-num">18</div>
          <div class="section-title">Dispute Resolution</div>
        </div>
        <div class="section-body">
          <p>Any complaints, disputes, or claims related to services must be raised by the customer within a reasonable time period after completion of the service through the platform's official support channels.</p>
          <p>Upon receiving a complaint, RestroEdge Pvt. Ltd. will review the matter, conduct necessary investigation, and evaluate the circumstances involved.</p>
          <p>Based on the findings, the company may provide an appropriate resolution, which may include re-service, partial refund, full refund, or other corrective action as deemed appropriate.</p>
          <div class="callout">The <strong>final decision made by RestroEdge Pvt. Ltd. shall be final and binding</strong> on all parties involved.</div>
        </div>
      </div>

      <!-- Additional Terms -->
      <div class="section" id="s19">
        <div class="section-header alt">
          <div class="section-num alt">+</div>
          <div class="section-title">Additional Service, Refund &amp; Payment Terms</div>
        </div>
        <div class="section-body">
          <p>The following additional terms shall apply to services booked through RestoCare:</p>

          <div class="sub-heading">Service Warranty</div>
          <p>In case of poor or defective service, customers may receive a service warranty of up to three (3) months, during which the platform may assign another qualified professional to resolve the issue.</p>

          <div class="sub-heading">Non-Delivery of Service</div>
          <p>If a booked service cannot be delivered or completed due to operational reasons, the customer may be eligible for a refund within 7–10 business days, subject to verification and approval.</p>

          <div class="sub-heading">Unsatisfactory Service</div>
          <p>If the customer is not satisfied with the service provided, the platform may review the complaint and arrange corrective service or process a refund within 7–10 business days, depending on the case.</p>

          <div class="sub-heading">Immediate Cancellation (Mistaken Booking)</div>
          <p>If a booking is cancelled immediately due to a mistaken order, an approved refund will be processed within 7–10 business days.</p>

          <div class="sub-heading">Refund Method</div>
          <p>Refunds may be credited either to the original payment method or to the platform wallet, as determined by the platform.</p>

          <div class="sub-heading">Wallet Usage Policy</div>
          <p>Any amount credited to the platform wallet may only be used for future service bookings and cannot be withdrawn or transferred to a bank account.</p>

          <div class="sub-heading">Taxation (GST)</div>
          <p>All services listed on the platform are subject to 18% Goods and Services Tax (GST). Prices displayed on the platform are exclusive of GST unless otherwise stated.</p>

          <div class="sub-heading">Service Partner / Driver Payout Terms</div>
          <p>Service partners and drivers will receive their payouts within 7–10 days after successful completion of the service.</p>

          <div class="sub-heading">Platform Fees &amp; Lead Charges</div>
          <p>A lead generation fee of ₹30 per lead and an additional 20% platform service fee will be deducted from the total service amount.</p>

          <div class="sub-heading">Service Partner Earnings</div>
          <p>After applicable deductions, service partners will receive approximately 80% of the total service earnings.</p>

          <div class="callout">These terms shall form an integral part of the service agreement and platform policies.</div>
        </div>
      </div>

    </main>
  </div>
</div>

<!-- Footer -->
<footer>
  <p><strong>RestroEdge Pvt. Ltd.</strong> &nbsp;·&nbsp; Refund, Cancellation &amp; Payment Policy &nbsp;·&nbsp; All rights reserved.</p>
  <p style="margin-top:6px;">For queries, please reach out through the platform's official support channels.</p>
</footer>

</body>