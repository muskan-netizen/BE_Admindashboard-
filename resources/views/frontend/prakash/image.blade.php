<style>
.process-section {
    width: 100%;
    padding: 40px 20px;
    background: #0f172a;
    position: relative;
    overflow: hidden;
    padding-bottom: 10px;
}

.process-wrapper {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    text-align: center;
}

/* CONTENT */
.process-content h1 {
    font-size: 35px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 15px;
}

.process-content p {
    font-size: 16px;
    line-height: 1.8;
    color: #cbd5e1;
    max-width: 750px;
    margin: 0 auto 10px auto;
}

/* IMAGE */
.process-image {
    width: 100%;
}

.process-image img {
    width: 100%;
    height: auto;              /* Important: No fixed height */
    display: block;
    border-radius: 20px;
    box-shadow: 0 30px 60px rgba(0,0,0,0.4);
}

/* Glow background */
.process-section::before {
    content: "";
    position: absolute;
    width: 600px;
    /* height: 600px; */
    background: radial-gradient(circle, rgba(59,130,246,0.15), transparent 70%);
    top: -200px;
    right: -200px;
}

/* Responsive */

@media (max-width: 992px) {
    .process-section {
        padding: 80px 40px;
    }

    .process-content h1 {
        font-size: 34px;
    }
}

@media (max-width: 576px) {
    .process-section {
        padding: 70px 20px;
        padding-bottom: 10px;
    }

    .process-content h1 {
        font-size: 28px;
    }

    .process-content p {
        font-size: 15px;
    }

    .process-image img {
        border-radius: 14px;
    }
}
</style>

<section class="process-section">
    <div class="process-wrapper" style="padding-bottom: 10px;">
        <div class="process-content">
            <h1>Our Process</h1>
            <p>
                We follow a structured roadmap designed for clarity, speed,
                and operational excellence.
            </p>
        </div>
        <div class="process-image" style="padding: 5px;">
            <img src="{{ asset('restocareimage/roadmap.jpeg') }}" alt="Our Process Roadmap">
        </div>
    </div>
</section>