<style>
    /* ── Wrapper ── */
    .slider-wrapper {
      position: relative;
      width: 100%;
      /* max-width: full; */
      overflow: hidden;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.45);
      background: #000;
    }

    /* ── Track ── */
    .slider-track {
      display: flex;
      transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      will-change: transform;
    }

    /* ── Slide ── */
    .slide {
      min-width: 100%;
      flex: 0 0 100%;
      line-height: 0;
    }

    .slide a {
      display: block;
    }

    .slide img {
      width: 100%;
      height: auto;
      aspect-ratio: 16 / 5;
      object-fit: cover;
      object-position: center;
      display: block;
    }

    /* ── Prev / Next buttons ── */
    .btn-prev,
    .btn-next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 10;
      width: 44px;
      height: 44px;
      border: none;
      border-radius: 50%;
      background: rgba(255,255,255,0.9);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.25);
      transition: background 0.2s, transform 0.2s;
    }

    .btn-prev:hover,
    .btn-next:hover {
      background: #fff;
      transform: translateY(-50%) scale(1.1);
    }

    .btn-prev { left: 14px; }
    .btn-next { right: 14px; }

    .btn-prev svg,
    .btn-next svg {
      width: 18px;
      height: 18px;
      stroke: #222;
      fill: none;
      stroke-width: 2.5;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    /* ── Dots ── */
    .slider-dots {
      position: absolute;
      bottom: 14px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 8px;
      z-index: 10;
    }

    .dot {
      width: 9px;
      height: 9px;
      border-radius: 50%;
      border: none;
      background: rgba(255,255,255,0.45);
      cursor: pointer;
      padding: 0;
      transition: background 0.25s, transform 0.25s;
    }

    .dot.active {
      background: #fff;
      transform: scale(1.4);
    }

    /* ── Responsive ── */
    @media (max-width: 600px) {
      .slide img {
        aspect-ratio: 4 / 3;
      }

      .btn-prev,
      .btn-next {
        width: 34px;
        height: 34px;
      }

      .btn-prev svg,
      .btn-next svg {
        width: 14px;
        height: 14px;
      }
    }

    /* ════════════════════════════════
       SHARED SLIDER BASE
    ════════════════════════════════ */
    .slider-wrapper {
      position: relative;
      width: 100%;
      overflow: hidden;
      background: #000;
    }

    .slider-track {
      display: flex;
      transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      will-change: transform;
    }

    .slide {
      min-width: 100%;
      flex: 0 0 100%;
      line-height: 0;
    }

    .slide a {
      display: block;
    }

    .slide img {
      width: 100%;
      display: block;
      object-fit: cover;
      object-position: center;
    }

    /* Prev / Next */
    .btn-prev,
    .btn-next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      z-index: 10;
      border: none;
      border-radius: 50%;
      background: rgba(255,255,255,0.88);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
      transition: background 0.2s, transform 0.2s;
    }

    .btn-prev:hover,
    .btn-next:hover {
      background: #fff;
    }

    .btn-prev svg,
    .btn-next svg {
      fill: none;
      stroke: #222;
      stroke-width: 2.5;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    /* Dots */
    .slider-dots {
      position: absolute;
      bottom: 12px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 7px;
      z-index: 10;
    }

    .dot {
      border-radius: 50%;
      border: none;
      background: rgba(255,255,255,0.45);
      cursor: pointer;
      padding: 0;
      transition: background 0.25s, transform 0.25s;
    }

    .dot.active {
      background: #fff;
      transform: scale(1.45);
    }


    /* ════════════════════════════════
       DESKTOP SLIDER  (≥ 768px)
       Banner: 1920 × 550
    ════════════════════════════════ */
    .slider-desktop {
      display: none;        /* hidden until ≥768px */
      max-width: 100%;
    }

    .slider-desktop .slide img {
      height: 550px;
      aspect-ratio: 1920 / 550;
    }

    .slider-desktop .btn-prev { left: 20px; width: 48px; height: 48px; }
    .slider-desktop .btn-next { right: 20px; width: 48px; height: 48px; }
    .slider-desktop .btn-prev svg,
    .slider-desktop .btn-next svg { width: 20px; height: 20px; }

    .slider-desktop .dot { width: 10px; height: 10px; }
    .slider-desktop .slider-dots { bottom: 16px; gap: 9px; }


    /* ════════════════════════════════
       MOBILE SLIDER  (< 768px)
       Banner: 768 × 300
    ════════════════════════════════ */
    .slider-mobile {
      display: block;       /* shown by default (mobile first) */
    }

    .slider-mobile .slide img {
      min-height: 220px;
      aspect-ratio: 768 / 300;
    }

    .slider-mobile .btn-prev { left: 10px; width: 34px; height: 34px; }
    .slider-mobile .btn-next { right: 10px; width: 34px; height: 34px; }
    .slider-mobile .btn-prev svg,
    .slider-mobile .btn-next svg { width: 14px; height: 14px; }

    .slider-mobile .dot { width: 7px; height: 7px; }
    .slider-mobile .slider-dots { bottom: 10px; gap: 6px; }


    /* ════════════════════════════════
       BREAKPOINT SWITCH
    ════════════════════════════════ */
    @media (min-width: 768px) {
      .slider-mobile  { display: none; }
      .slider-desktop { display: block; }
    }

    /* Large screens — cap height, keep ratio */
    @media (min-width: 1400px) {
      .slider-desktop .slide img {
        height: auto;
      }
    }

    /* Tiny phones */
    @media (max-width: 400px) {
      .slider-mobile .slide img {
        height: 140px;
        aspect-ratio: 5 / 2;
      }
    }
    

    .slide {
  width: 100%;
  overflow: hidden;
}
  </style>

  
<style>

  .slider-wrapper{
    margin-top: 50px;
  }
  .top-header{
    background: transparent;
  }
  .al_custom_head{
    background: transparent;
  }
  .navbar{
    border-bottom: 0px solid #eee;
  }
@media (min-width: 1025px) {
  .slider-wrapper {
    margin-top: 70px; /* change value as needed */
  }
}


/* Default — Show Desktop, Hide Mobile */
.slider-desktop {
  display: block;
}

.slider-mobile {
  display: none;
}

/* For screens less than 1024px */
@media (max-width: 1023px) {
  .slider-desktop {
    display: none;
  }

  .slider-mobile {
    display: block;
  }
}
#desktopTrack{
  border-radius: 0px;
  padding: 0;

}
#mobileTrack{
  border-radius: 0px;
  padding: 0;

}
</style>

 <!-- ══════════════════════════════
       DESKTOP SLIDER
  ══════════════════════════════ -->
  <div class="slider-wrapper slider-desktop" id="desktopSlider">
    <div class="slider-track" id="desktopTrack">

      <div class="slide">
        <a href="#">
          <img
            src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772282954/3_qiea7b.jpg"
            alt="Desktop Banner 1"
            width="1920" height="550"
            id="banner-img-border"
          >
        </a>
      </div>

      <div class="slide">
        <a href="#" class="category-container">
          <img
            src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772282954/1_af1sqy.jpg"
            alt="Desktop Banner 2"
            width="1920" height="550"
            loading="lazy"
            id="banner-img-border"
          >
        </a>
      </div>

      <div class="slide">
        <a href="#">
          <img
            src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772282954/2_ymzfhc.jpg"
            alt="Desktop Banner 3"
            width="1920" height="550"
            loading="lazy"
            id="banner-img-border"
          >
        </a>
      </div>

      <div class="slide">
        <a href="#">
          <img
            src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772282954/4_oebhwi.jpg"
            alt="Desktop Banner 4"
            width="1920" height="550"
            loading="lazy"
            id="banner-img-border"
          >
        </a>
      </div>

      <!-- <div class="slide">
        <a href="#">
          <img
            src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772282050/image_13.jpg_rjadaa.jpg"
            alt="Desktop Banner 4"
            width="1920" height="550"
            loading="lazy"
            id="banner-img-border"
          >
        </a>
      </div> -->
    </div>

    <button class="btn-prev" data-target="desktopTrack" aria-label="Previous">
      <svg viewBox="0 0 24 24" width="20" height="20"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="btn-next" data-target="desktopTrack" aria-label="Next">
      <svg viewBox="0 0 24 24" width="20" height="20"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="slider-dots" id="desktopDots"></div>
  </div>


  <!-- ══════════════════════════════
       MOBILE SLIDER
  ══════════════════════════════ -->
  <div class="slider-wrapper slider-mobile" id="mobileSlider">
    <div class="slider-track" id="mobileTrack">

      <!-- <div class="slide">
        <a href="#">
          <img
            src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772263364/048493c6-0f24-4ca3-89ba-65b7e71f6c52.png"
            alt="Mobile Banner 1"
            width="768" height="300"
            id="banner-img-border"
          >
        </a>
      </div> -->

      <div class="slide">
        <a href="#">
          <img
            src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772284505/image_17_fgsqrt.jpg"
            alt="Mobile Banner 2"
            width="768" height="300"
            loading="lazy"
            id="banner-img-border"
          >
        </a>
      </div>

      <div class="slide">
        <a href="#">
          <img
            src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772284505/image_16.jpg_y6tqfj.jpg"
            alt="Mobile Banner 3"
            width="768" height="300"
            loading="lazy"
            id="banner-img-border"
          >
        </a>
      </div>

      <div class="slide">
        <a href="#">
          <img
            src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772283156/image_14.jpg_qdbzlc.jpg"
            alt="Mobile Banner 3"
            width="768" height="300"
            loading="lazy"
            id="banner-img-border"
          >
        </a>
      </div>

      <div class="slide">
        <a href="#">
          <img
            src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772284635/image_19.jpg_ms4mes.jpg"
            alt="Mobile Banner 3"
            width="768" height="300"
            loading="lazy"
            id="banner-img-border"
          >
        </a>
      </div>

      <!-- <div class="slide">
        <a href="#">
          <img
            src="https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=768&h=300&fit=crop&q=80"
            alt="Mobile Banner 4"
            width="768" height="300"
            loading="lazy"
            id="banner-img-border"
          >
        </a>
      </div> -->

    </div>

    <!-- <button class="btn-prev" data-target="mobileTrack" aria-label="Previous">
      <svg viewBox="0 0 24 24" width="14" height="14"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="btn-next" data-target="mobileTrack" aria-label="Next">
      <svg viewBox="0 0 24 24" width="14" height="14"><polyline points="9 18 15 12 9 6"/></svg>
    </button> -->
    <div class="slider-dots" id="mobileDots"></div>
  </div>
