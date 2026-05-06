@extends('layouts.store', ['title' => __('Home')])

@section('cssnew')
<style>
  .ts-home {
    --ts-primary: #ff4d57;
    --ts-dark: #111827;
    --ts-muted: #6b7280;
    --ts-bg: #f3f5f8;
    --ts-card-border: #e8edf3;
    --ts-page-gutter: clamp(16px, 4.5vw, 48px);
    /* Page gutters: keep content off the viewport edges (Bootstrap-like container feel) */
    box-sizing: border-box;
    width: 100%;
    max-width: 100%;
    padding-left: max(var(--ts-page-gutter), env(safe-area-inset-left));
    padding-right: max(var(--ts-page-gutter), env(safe-area-inset-right));
    overflow-x: hidden;
  }

  .ts-home .ts-container {
    width: 100%;
    max-width: 1240px;
    margin-left: auto;
    margin-right: auto;
  }

  .ts-home .ts-full-width {
    width: 100%;
    margin: 0;
    padding: 0;
  }

  /* Hero: full viewport width; text/search stay aligned with page gutters via .ts-hero-overlay .ts-container */
  .ts-home .ts-hero {
    position: relative;
    width: 100vw;
    max-width: 100vw;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    overflow: hidden;
    background: #0b1220;
  }

  .ts-home .ts-hero-overlay .ts-container {
    padding-left: max(var(--ts-page-gutter), env(safe-area-inset-left));
    padding-right: max(var(--ts-page-gutter), env(safe-area-inset-right));
    box-sizing: border-box;
  }

  .ts-home .ts-hero::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(95deg, rgba(15, 23, 42, 0.82) 0%, rgba(15, 23, 42, 0.55) 45%, rgba(15, 23, 42, 0.2) 100%);
    z-index: 1;
    pointer-events: none;
  }

  .ts-home .ts-hero-track {
    display: flex;
    transition: transform 0.65s ease;
  }

  .ts-home .ts-hero-slide {
    flex: 0 0 100%;
    min-width: 100%;
    position: relative;
    display: block;
    aspect-ratio: 1920 / 680;
    min-height: clamp(220px, 28vw, 420px);
    overflow: hidden;
  }

  .ts-home .ts-hero-slide img {
    position: absolute;
    left: 50%;
    top: 50%;
    width: 103%;
    height: 103%;
    max-width: none;
    max-height: none;
    transform: translate(-50%, -50%) scale(1.01);
    object-fit: cover;
    /* Upper bias keeps heads / upper body in frame across mixed hero photos */
    object-position: center 24%;
    animation: tsHeroZoom 14s ease-in-out infinite alternate;
  }

  @keyframes tsHeroZoom {
    from { transform: translate(-50%, -50%) scale(1.01); }
    to { transform: translate(-50%, -50%) scale(1.05); }
  }

  .ts-home .ts-hero-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    z-index: 2;
  }

  .ts-home .ts-hero-content {
    max-width: min(760px, 100%);
    width: 100%;
    color: #fff;
    padding: 88px 0 56px;
    box-sizing: border-box;
  }

  .ts-home .ts-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, 0.35);
    background: rgba(255, 255, 255, 0.14);
    padding: 8px 16px;
    font-size: 13px;
    margin-bottom: 16px;
    font-weight: 600;
    letter-spacing: .2px;
  }

  .ts-home .ts-title {
    font-size: clamp(34px, 5.4vw, 43px);
    line-height: 1.08;
    margin: 0 0 16px;
    font-weight: 800;
    max-width: 720px;
    color: white;
    letter-spacing: -0.02em;
  }

  .ts-home .ts-subtitle {
    font-size: clamp(15px, 1.5vw, 19px);
    line-height: 1.65;
    color: rgba(255, 255, 255, 0.95);
    margin: 0 0 28px;
    max-width: 640px;
    letter-spacing: 0.01em;
  }

  .ts-home .ts-search {
    display: grid;
    grid-template-columns: 1.4fr 1fr auto;
    gap: 10px;
    background: rgba(255, 255, 255, 0.98);
    border: 1px solid #edf2f7;
    border-radius: 16px;
    padding: 12px;
    margin-top: 20px;
    max-width: 860px;
    box-shadow: 0 16px 34px rgba(15, 23, 42, 0.25);
  }

  .ts-home .ts-search input {
    height: 58px;
    border: 1px solid #e5e7eb;
    border-radius: 11px;
    padding: 0 17px;
    font-size: 15px;
    color: #111827;
    outline: none;
  }

  .ts-home .ts-search button {
    border: 0;
    border-radius: 11px;
    background: linear-gradient(130deg, #ff5a5f, #ff3f66);
    color: #fff;
    height: 58px;
    padding: 0 28px;
    font-weight: 700;
    font-size: 19px;
    letter-spacing: .2px;
    transition: transform .2s ease, box-shadow .2s ease;
  }

  .ts-home .ts-search button:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(255, 77, 87, .35);
  }

  .ts-home .ts-dots {
    position: absolute;
    left: 50%;
    bottom: 18px;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 3;
  }

  .ts-home .ts-dot {
    width: 9px;
    height: 9px;
    border-radius: 99px;
    border: 0;
    background: rgba(255, 255, 255, 0.5);
    transition: all .25s ease;
  }

  .ts-home .ts-dot.is-active {
    width: 24px;
    background: #fff;
  }

  .ts-home .ts-section {
    padding: 72px 0 12px;
    position: relative;
  }

  .ts-home .ts-bg-light {
    background: var(--ts-bg);
  }

  .ts-home .ts-head {
    display: flex;
    align-items: end;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
  }

  .ts-home .ts-h2 {
    font-size: clamp(28px, 3.2vw, 38px);
    line-height: 1.12;
    margin: 0;
    font-weight: 800;
    color: var(--ts-dark);
    letter-spacing: -.3px;
  }

  .ts-home .ts-h3 {
    color: var(--ts-muted);
    margin: 8px 0 0;
    font-size: 15px;
    line-height: 1.7;
    max-width: 620px;
  }

  .ts-home .ts-grid-cats {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 14px;
  }

  /* Popular categories: icon in white tile; label sits below tile (matches mobile reference) */
  .ts-home .ts-cat-cell {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    text-decoration: none;
    color: inherit;
    min-width: 0;
  }

  .ts-home .ts-cat-cell .ts-cat-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid var(--ts-card-border);
    width: 100%;
    max-width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px 8px;
    min-height: 0;
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
  }

  .ts-home .ts-cat-cell:hover .ts-cat-card {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(17, 24, 39, .1);
    border-color: #dbe7ff;
  }

  .ts-home .ts-cat-cell .ts-cat-icon {
    width: 64px;
    height: 64px;
    margin: 0;
    border-radius: 50%;
    background: #f5f8fd;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
  }

  .ts-home .ts-cat-cell .ts-cat-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 6px;
  }

  .ts-home .ts-cat-cell .ts-cat-name {
    margin-top: 8px;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.25;
    color: #111827;
    width: 100%;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .ts-home .ts-steps {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
  }

  .ts-home .ts-step {
    background: #fff;
    border-radius: 16px;
    border: 1px solid var(--ts-card-border);
    padding: 20px;
    transition: transform .25s ease, box-shadow .25s ease;
  }

  .ts-home .ts-step:hover {
    transform: translateY(-5px);
    box-shadow: 0 14px 30px rgba(17, 24, 39, .08);
  }

  .ts-home .ts-step h4 {
    margin: 0 0 8px;
    font-size: 18px;
    font-weight: 700;
    color: #101827;
  }

  .ts-home .ts-step p {
    margin: 0;
    color: var(--ts-muted);
    font-size: 14px;
    line-height: 1.7;
  }

  .ts-home .ts-how-icon {
    width: 58px;
    height: 58px;
    border-radius: 8px;
    background: #111;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    box-shadow: 0 6px 14px rgba(0, 0, 0, .22);
  }

  .ts-home .ts-how-icon img {
    width: 30px;
    height: 30px;
    object-fit: contain;
  }

  .ts-home .ts-two-col {
    display: grid;
    grid-template-columns: 1.05fr 0.95fr;
    gap: 26px;
    align-items: center;
  }

  .ts-home .ts-media-box {
    border-radius: 18px;
    overflow: hidden;
    min-height: 340px;
    background: #e5e7eb;
    border: 1px solid var(--ts-card-border);
    box-shadow: 0 20px 34px rgba(15, 23, 42, .12);
  }

  .ts-home .ts-media-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .ts-home .ts-bullet-list {
    margin: 18px 0 0;
    padding: 0;
    list-style: none;
    display: grid;
    gap: 10px;
  }

  .ts-home .ts-bullet-list li::before {
    content: "✓";
    color: #16a34a;
    margin-right: 8px;
    font-weight: 700;
  }

  .ts-home .ts-cards-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }

  .ts-home .ts-image-card {
    background: #fff;
    border-radius: 15px;
    border: 1px solid var(--ts-card-border);
    overflow: hidden;
    transition: transform .25s ease, box-shadow .25s ease;
  }

  .ts-home .ts-image-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 30px rgba(15, 23, 42, .12);
  }

  .ts-home .ts-image-card .img {
    aspect-ratio: 16 / 10;
    overflow: hidden;
  }

  .ts-home .ts-image-card .img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .45s ease;
  }

  .ts-home .ts-image-card:hover .img img {
    transform: scale(1.08);
  }

  .ts-home .ts-image-card .body {
    padding: 14px;
  }

  .ts-home .ts-image-card .meta {
    color: var(--ts-muted);
    font-size: 12px;
    margin-bottom: 6px;
  }

  .ts-home .ts-image-card h4 {
    margin: 0 0 6px;
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
  }

  .ts-home .ts-image-card p {
    margin: 0;
    font-size: 13px;
    line-height: 1.6;
    color: var(--ts-muted);
  }

  .ts-home .ts-rating {
    margin-top: 8px;
    color: #f59e0b;
    font-size: 12px;
  }

  .ts-home .ts-provider-hidden {
    display: none !important;
  }

  /* Full-bleed horizontal row (mobile); desktop unchanged */
  .ts-home .ts-x-scroll-wrap {
    width: 100%;
  }

  @media (max-width: 767px) {
    .ts-home .ts-x-scroll-wrap {
      overflow-x: auto;
      overflow-y: hidden;
      -webkit-overflow-scrolling: touch;
      scroll-snap-type: x mandatory;
      scroll-padding-left: 16px;
      scroll-padding-right: 16px;
      margin-left: calc(50% - 50vw);
      margin-right: calc(50% - 50vw);
      width: 100vw;
      padding: 0 0 10px;
      padding-left: max(14px, env(safe-area-inset-left));
      padding-right: max(14px, env(safe-area-inset-right));
      box-sizing: border-box;
      touch-action: pan-x;
      overscroll-behavior-x: contain;
    }

    .ts-home .ts-x-scroll-wrap .ts-cards-4.ts-x-scroll,
    .ts-home .ts-x-scroll-wrap .ts-steps.ts-x-scroll {
      grid-template-columns: unset;
    }

    .ts-home .ts-x-scroll-wrap::-webkit-scrollbar {
      height: 4px;
    }

    .ts-home .ts-x-scroll-wrap::-webkit-scrollbar-thumb {
      background: rgba(17, 24, 39, 0.22);
      border-radius: 99px;
    }

    /* Popular categories: 4 per row (reference layout), not horizontal scroll */
    .ts-home .ts-cat-grid-popular {
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 10px 8px;
      width: 100%;
      max-width: 100%;
    }

    .ts-home .ts-cat-grid-popular .ts-cat-cell .ts-cat-card {
      border-radius: 18px;
      padding: 5px;
      aspect-ratio: 1;
      box-sizing: border-box;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Large icon circle inside tile (reduces empty white like native app) */
    .ts-home .ts-cat-grid-popular .ts-cat-cell .ts-cat-icon {
      width: 92%;
      height: auto;
      aspect-ratio: 1;
      max-width: 100%;
      max-height: 100%;
    }

    .ts-home .ts-cat-grid-popular .ts-cat-cell .ts-cat-icon img {
      padding: clamp(1px, 0.9vw, 4px);
    }

    .ts-home .ts-cat-grid-popular .ts-cat-cell .ts-cat-name {
      margin-top: 6px;
      font-size: clamp(9px, 2.8vw, 11px);
      font-weight: 700;
    }

    /* Card grids → horizontal strips */
    .ts-home .ts-x-scroll-wrap .ts-cards-4.ts-x-scroll,
    .ts-home .ts-x-scroll-wrap .ts-steps.ts-x-scroll {
      display: flex;
      flex-wrap: nowrap;
      gap: 14px;
      width: max-content;
      min-width: 100%;
    }

    .ts-home .ts-x-scroll-wrap .ts-cards-4.ts-x-scroll > .ts-image-card,
    .ts-home .ts-x-scroll-wrap .ts-cards-4.ts-x-scroll > a.ts-image-card {
      flex: 0 0 clamp(240px, 78vw, 300px);
      scroll-snap-align: start;
      scroll-snap-stop: always;
      max-width: none;
    }

    .ts-home .ts-x-scroll-wrap .ts-cards-4.ts-x-scroll > .ts-step {
      flex: 0 0 clamp(240px, 82vw, 300px);
      scroll-snap-align: start;
      scroll-snap-stop: always;
    }

    .ts-home .ts-x-scroll-wrap .ts-steps.ts-x-scroll > .ts-step {
      flex: 0 0 clamp(240px, 82vw, 300px);
      scroll-snap-align: start;
      scroll-snap-stop: always;
    }

    /* Show all provider cards in the strip; hide expand/collapse on small screens */
    .ts-home [data-ts-providers-grid] .ts-image-card.ts-provider-hidden {
      display: block !important;
    }

    .ts-home [data-ts-providers-toggle-wrap] {
      display: none !important;
    }

    /* Hero (mobile): taller area, no in-banner search, compact title, undistorted image */
    .ts-home .ts-hero {
      padding-top: max(6px, env(safe-area-inset-top));
    }

    .ts-home .ts-hero .ts-search {
      display: none !important;
    }

    .ts-home .ts-hero-overlay {
      align-items: flex-start;
    }

    .ts-home .ts-hero .ts-hero-content {
      padding: 145px 0 56px;
      max-width: 100%;
    }

    .ts-home .ts-hero .ts-eyebrow {
      font-size: 10px;
      padding: 5px 10px;
      margin-bottom: 6px;
    }

    .ts-home .ts-hero .ts-title {
      font-size: clamp(17px, 5.2vw, 22px);
      line-height: 1.22;
      margin: 0 0 8px;
      padding-right: 8px;
      font-weight: 700;
      letter-spacing: 0.01em;
      text-transform: none;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .ts-home .ts-hero .ts-subtitle {
      display: none !important;
    }

    .ts-home .ts-hero-slide {
      aspect-ratio: 375 / 270;
      min-height: clamp(248px, 72vw, 340px);
    }

    .ts-home .ts-hero-slide img {
      position: absolute;
      inset: 0;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      max-width: none;
      max-height: none;
      transform: none;
      animation: none;
      object-fit: cover;
      object-position: center 26%;
    }

    .ts-home .ts-dots {
      bottom: 10px;
    }

    .ts-home .ts-h2 {
      font-size: 20px;
    }

    .ts-home .ts-h3 {
      font-size: 13px;
      margin-top: 4px;
    }
  }

  .ts-home .ts-providers-toggle-wrap {
    text-align: center;
    margin-top: 22px;
  }

  .ts-home .ts-providers-toggle-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #e8eaed;
    border: 0;
    border-radius: 999px;
    color: #111827;
    font-weight: 700;
    cursor: pointer;
    font-size: 15px;
    padding: 12px 24px;
    letter-spacing: 0.02em;
    transition: background 0.2s ease, transform 0.15s ease;
  }

  .ts-home .ts-providers-toggle-btn:hover {
    background: #dde1e6;
  }

  .ts-home .ts-providers-toggle-btn:focus-visible {
    outline: 2px solid #111827;
    outline-offset: 2px;
  }

  .ts-home .ts-providers-toggle-chevron {
    display: inline-flex;
    flex-shrink: 0;
    line-height: 0;
    color: #111827;
  }

  .ts-home .ts-providers-toggle-chevron svg {
    display: block;
  }

  .ts-home .ts-cta {
    border-radius: 18px;
    padding: 36px;
    background: linear-gradient(110deg, #111827 0%, #1f2937 100%);
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-bottom: 8px;
    border: 1px solid #2d3748;
  }

  .ts-home .ts-cta a {
    background: linear-gradient(130deg, #ff5a5f, #ff3f66);
    color: #fff;
    border-radius: 10px;
    padding: 12px 18px;
    font-weight: 700;
  }

  .ts-home .ts-cta--bg {
    position: relative;
    overflow: hidden;
    background: transparent;
    isolation: isolate;
  }

  .ts-home .ts-cta--bg::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: url("{{ asset('service-partner.jpg') }}");
    background-size: cover;
    background-position: center;
    z-index: -2;
    transform: scale(1.02);
  }

  .ts-home .ts-cta--bg::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, rgba(12, 28, 60, 0.88) 0%, rgba(20, 34, 67, 0.84) 42%, rgba(20, 34, 67, 0.72) 100%);
    z-index: -1;
  }

  .ts-home .ts-cta--bg .ts-h2,
  .ts-home .ts-cta--bg .ts-h3 {
    color: #fff !important;
  }

  .ts-home .ts-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
    border: 2px solid #fff;
    box-shadow: 0 6px 16px rgba(15, 23, 42, .16);
  }

  .ts-home .ts-faq-wrap {
    max-width: 1180px;
    margin: 0 auto;
  }

  .ts-home .ts-faq-split {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 28px;
    align-items: start;
  }

  .ts-home .ts-faq-media {
    background: #fff;
    border: 1px solid var(--ts-card-border);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 16px 32px rgba(15, 23, 42, .08);
    position: sticky;
    top: 96px;
  }

  .ts-home .ts-faq-media-main {
    position: relative;
    aspect-ratio: 4 / 3;
    overflow: hidden;
  }

  .ts-home .ts-faq-media-main img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .55s ease;
  }

  .ts-home .ts-faq-media:hover .ts-faq-media-main img {
    transform: scale(1.06);
  }

  .ts-home .ts-faq-media-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(17,24,39,.05) 15%, rgba(17,24,39,.75) 100%);
    color: #fff;
    padding: 96px;
    display: flex;
    flex-direction: column;
    justify-content: end;
  }

  .ts-home .ts-faq-media-overlay h4 {
    margin: 0 0 4px;
    font-size: 21px;
    font-weight: 800;
    line-height: 1.2;
    color: #fff;
  }

  .ts-home .ts-faq-media-overlay p {
    margin: 0;
    font-size: 13px;
    opacity: .94;
    color: #fff;
  }

  .ts-home .ts-faq-video-box {
    padding: 12px;
    border-top: 1px solid var(--ts-card-border);
    background: #f8fbff;
  }

  .ts-home .ts-faq-video-box video {
    width: 100%;
    border-radius: 12px;
    display: block;
    object-fit: cover;
    max-height: 220px;
    background: #e5e7eb;
  }

  .ts-home .ts-video-highlight {
    display: grid;
    grid-template-columns: 1.05fr 1fr;
    gap: 20px;
    background: #fff;
    border: 1px solid var(--ts-card-border);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 14px 28px rgba(15, 23, 42, .08);
  }

  .ts-home .ts-video-preview {
    min-height: 320px;
    background: #0f172a;
  }

  .ts-home .ts-video-preview video {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
  }

  .ts-home .ts-video-copy {
    padding: 30px 26px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .ts-home .ts-video-copy h3 {
    margin: 0 0 10px;
    font-size: 31px;
    line-height: 1.15;
    color: #111827;
    font-weight: 800;
  }

  .ts-home .ts-video-copy p {
    margin: 0;
    color: var(--ts-muted);
    font-size: 15px;
    line-height: 1.75;
  }

  .ts-home .ts-mid-banner {
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid var(--ts-card-border);
    box-shadow: 0 14px 28px rgba(15, 23, 42, .08);
    position: relative;
  }

  .ts-home .ts-mid-banner img {
    width: 100%;
    display: block;
    max-height: 420px;
    object-fit: cover;
    transition: transform .45s ease;
  }

  .ts-home .ts-mid-banner:hover img {
    transform: scale(1.02);
  }

  .ts-home .ts-faq-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 7px 16px;
    border-radius: 999px;
    background: #fdeef1;
    color: #d94b5f;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .9px;
    text-transform: uppercase;
    margin-bottom: 14px;
  }

  .ts-home .ts-faq-list {
    display: grid;
    gap: 12px;
  }

  .ts-home .ts-faq-item {
    background: #fff;
    border: 1px solid #eceff4;
    border-radius: 14px;
    overflow: hidden;
    transition: border-color .22s ease, box-shadow .22s ease;
  }

  .ts-home .ts-faq-item:hover {
    border-color: #e4e9f2;
    box-shadow: 0 8px 20px rgba(15, 23, 42, .06);
  }

  .ts-home .ts-faq-question {
    width: 100%;
    border: 0;
    background: transparent;
    padding: 18px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    text-align: left;
    cursor: pointer;
  }

  .ts-home .ts-faq-no {
    width: 28px;
    min-width: 28px;
    height: 28px;
    border-radius: 999px;
    background: #fff1f3;
    color: #d94b5f;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12px;
  }

  .ts-home .ts-faq-title {
    flex: 1;
    margin: 0;
    color: #111827;
    font-size: 22px;
    line-height: 1.25;
    letter-spacing: .2px;
    font-weight: 700;
  }

  .ts-home .ts-faq-chevron {
    width: 26px;
    min-width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #f7f9fc;
    color: #6b7280;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: transform .25s ease, background .25s ease, color .25s ease;
  }

  .ts-home .ts-faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height .35s ease;
  }

  .ts-home .ts-faq-answer p {
    margin: 0;
    color: var(--ts-muted);
    font-size: 14px;
    line-height: 1.75;
    padding: 0 20px 20px 60px;
  }

  .ts-home .ts-faq-item.is-open {
    border-color: #dfe7f5;
    box-shadow: 0 12px 26px rgba(15, 23, 42, .08);
  }

  .ts-home .ts-faq-item.is-open .ts-faq-chevron {
    transform: rotate(180deg);
    background: #ffecee;
    color: #d94b5f;
  }

  .ts-home .reveal-up {
    opacity: 0;
    transform: translate3d(0, 58px, 0);
    transition: opacity .8s ease, transform .8s ease;
    will-change: opacity, transform;
  }

  .ts-home .reveal-up.is-visible {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }

  .ts-home [data-stagger].is-visible > * {
    animation: tsStagger .7s ease both;
  }

  .ts-home [data-stagger].is-visible > *:nth-child(1) { animation-delay: .05s; }
  .ts-home [data-stagger].is-visible > *:nth-child(2) { animation-delay: .1s; }
  .ts-home [data-stagger].is-visible > *:nth-child(3) { animation-delay: .15s; }
  .ts-home [data-stagger].is-visible > *:nth-child(4) { animation-delay: .2s; }
  .ts-home [data-stagger].is-visible > *:nth-child(5) { animation-delay: .25s; }
  .ts-home [data-stagger].is-visible > *:nth-child(6) { animation-delay: .3s; }

  @keyframes tsStagger {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 1199px) {
    .ts-home .ts-hero-slide {
      aspect-ratio: 1280 / 600;
    }
    .ts-home .ts-grid-cats { grid-template-columns: repeat(4, 1fr); }
    .ts-home .ts-cards-4 { grid-template-columns: repeat(3, 1fr); }
  }

  @media (max-width: 991px) {
    .ts-home .ts-hero-slide {
      aspect-ratio: 1024 / 540;
    }

    .ts-home .ts-hero-content {
      padding: 76px 0 46px;
    }

    .ts-home .ts-search { grid-template-columns: 1fr; max-width: 620px; margin-top: 22px; }
    .ts-home .ts-steps { grid-template-columns: 1fr 1fr; }
    .ts-home .ts-two-col { grid-template-columns: 1fr; }
    .ts-home .ts-cards-4 { grid-template-columns: repeat(2, 1fr); }
    .ts-home .ts-cta { flex-direction: column; align-items: flex-start; }
    .ts-home .ts-faq-split { grid-template-columns: 1fr; }
    .ts-home .ts-faq-media { position: relative; top: 0; }
    .ts-home .ts-video-highlight { grid-template-columns: 1fr; }
    .ts-home .ts-video-preview { min-height: 280px; }
  }

  @media (max-width: 639px) {
    .ts-home .ts-container { width: 100%; }
    .ts-home .ts-section { padding: 40px 0 8px; }
    .ts-home .ts-head {
      align-items: flex-start;
      margin-bottom: 16px;
      gap: 10px;
      flex-direction: column;
    }
    .ts-home .ts-h2 { font-size: 24px; line-height: 1.2; }
    .ts-home .ts-h3 { font-size: 14px; line-height: 1.55; }
    .ts-home .ts-hero-overlay { align-items: flex-start; }
    .ts-home .ts-hero-content { padding: 44px 0 16px; }
    .ts-home .ts-eyebrow { font-size: 11px; padding: 6px 12px; margin-bottom: 10px; }
    .ts-home .ts-title { font-size: 27px; margin-bottom: 8px; }
    .ts-home .ts-subtitle {
      font-size: 13px;
      line-height: 1.45;
      margin-bottom: 12px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .ts-home .ts-search {
      grid-template-columns: 1fr;
      gap: 8px;
      padding: 8px;
      margin-top: 10px;
      border-radius: 12px;
    }
    .ts-home .ts-search input,
    .ts-home .ts-search button {
      height: 44px;
      border-radius: 8px;
      font-size: 14px;
    }
    .ts-home .ts-search button { width: 100%; padding: 0 14px; }
    .ts-home .ts-dots { bottom: 8px; }
    .ts-home .ts-grid-cats:not(.ts-x-scroll):not(.ts-cat-grid-popular) { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .ts-home .ts-cat-grid-popular {
      grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
      gap: 8px 6px !important;
    }
    .ts-home .ts-cat-grid-popular .ts-cat-cell .ts-cat-card {
      padding: 4px;
      border-radius: 14px;
    }

    .ts-home .ts-cat-grid-popular .ts-cat-cell .ts-cat-icon {
      width: 94%;
    }

    .ts-home .ts-cat-grid-popular .ts-cat-cell .ts-cat-icon img {
      padding: 1px 2px;
    }

    .ts-home .ts-cat-grid-popular .ts-cat-cell .ts-cat-name {
      font-size: 9px;
      margin-top: 5px;
    }
    .ts-home .ts-steps:not(.ts-x-scroll) { grid-template-columns: 1fr; }
    .ts-home .ts-step { padding: 16px; }
    .ts-home .ts-step h4 { font-size: 16px; }
    .ts-home .ts-two-col { gap: 14px; }
    .ts-home .ts-media-box { min-height: 220px; }
    .ts-home .ts-cards-4:not(.ts-x-scroll) { grid-template-columns: 1fr; }
    .ts-home .ts-image-card .body { padding: 12px; }
    .ts-home .ts-image-card h4 { font-size: 16px; }
    .ts-home .ts-video-preview { min-height: 200px; }
    .ts-home .ts-faq-title { font-size: 18px; }
    .ts-home .ts-faq-no { width: 24px; min-width: 24px; height: 24px; font-size: 11px; }
    .ts-home .ts-faq-media-overlay { padding: 14px; }
    .ts-home .ts-faq-answer p { padding: 0 16px 16px 16px; }
    .ts-home .ts-faq-question { padding: 12px; gap: 10px; }
    .ts-home .ts-mid-banner img { max-height: 180px; }
    .ts-home .ts-cta { padding: 18px; border-radius: 14px; }
    .ts-home .ts-cta a { width: 100%; text-align: center; }
    .ts-home .ts-video-copy h3 { font-size: 24px; }
    .ts-home .ts-video-copy { padding: 20px 16px; }
  }

  @media (max-width: 420px) {
    .ts-home .ts-container { width: 100%; }
    .ts-home .ts-hero-content { padding: 36px 0 12px; }
    .ts-home .ts-title { font-size: 24px; }
    .ts-home .ts-subtitle { display: none; }
    .ts-home .ts-search input::placeholder { font-size: 13px; }
    .ts-home .ts-search button { font-size: 13px; letter-spacing: 0; }
    .ts-home .ts-h2 { font-size: 21px; }
    .ts-home .ts-video-copy h3 { font-size: 20px; }
    .ts-home .ts-faq-title { font-size: 16px; }
  }
</style>
@endsection

@section('content')
<div class="ts-home">
  <section class="ts-full-width ts-hero reveal-up" id="ts-hero">
    <div class="ts-hero-track" data-ts-hero-track>
      <a class="ts-hero-slide" href="#">
        <img src="{{ asset('new.jpeg') }}" alt="Beauty Service">
      </a>
      <a class="ts-hero-slide" href="#">
        <img src="{{ asset('website.jpeg') }}" alt="Carpenter Service">
      </a>
      <!-- <a class="ts-hero-slide" href="#">
        <img src="{{ asset('chef.jpg') }}" alt="Chef Service">
      </a>
      <a class="ts-hero-slide" href="#">
        <img src="{{ asset('plumber.jpg') }}" alt="Plumber Service">
      </a> -->
    </div>
    <div class="ts-hero-overlay">
      <div class="ts-container">
        <div class="ts-hero-content">
          <span class="ts-eyebrow">Trusted service marketplace</span>
          <h1 class="ts-title">Find Top Professionals For Any Home Service In Minutes</h1>
          <p class="ts-subtitle">Book verified experts for cleaning, plumbing, beauty, repairs and more. Compare profiles, read reviews, and schedule the right service with confidence.</p>
          <!-- <div class="ts-search">
            <input type="text" placeholder="What service are you looking for?" readonly>
            <input type="text" placeholder="Your city or location" readonly>
            <button type="button">Search</button>
          </div> -->
        </div>
      </div>
    </div>
    <div class="ts-dots" data-ts-hero-dots></div>
  </section>

  <section class="ts-section reveal-up">
    <div class="ts-container">
      <div class="ts-head">
        <div>
          <h2 class="ts-h2">Popular Categories</h2>
          <p class="ts-h3">Choose your service category and connect with top-rated professionals near you.</p>
        </div>
      </div>
      @php
        $popularCategories = collect($navCategories ?? [])
          ->filter(function ($cate) {
            return !empty($cate['name']) && !empty($cate['slug']);
          })
          ->take(12);
      @endphp
      <div class="ts-grid-cats ts-cat-grid-popular" data-stagger>
        @forelse($popularCategories as $cate)
          @php
            $categoryIcon = '';
            if (!empty($cate['icon']['image_fit']) && !empty($cate['icon']['image_path'])) {
              $categoryIcon = $cate['icon']['image_fit'].'100/100'.$cate['icon']['image_path'];
            }
          @endphp
          <a class="ts-cat-cell" href="{{ route('categoryDetail', $cate['slug']) }}">
            <div class="ts-cat-card">
              <div class="ts-cat-icon">
                <img src="{{ $categoryIcon ?: 'https://img.icons8.com/fluency/96/services.png' }}" alt="{{ $cate['name'] }}">
              </div>
            </div>
            <div class="ts-cat-name">{{ $cate['name'] }}</div>
          </a>
        @empty
          <a class="ts-cat-cell" href="#">
            <div class="ts-cat-card">
              <div class="ts-cat-icon"><img src="https://img.icons8.com/fluency/96/services.png" alt="Service"></div>
            </div>
            <div class="ts-cat-name">Service</div>
          </a>
        @endforelse
      </div>
    </div>
  </section>

  <section class="ts-section ts-bg-light reveal-up">
    <div class="ts-container">
      <div class="ts-head">
        <div>
          <h2 class="ts-h2">How It Works</h2>
          <p class="ts-h3">A simple and reliable process from discovery to service completion.</p>
        </div>
      </div>
    </div>
    <div class="ts-x-scroll-wrap">
      <div class="ts-steps ts-x-scroll" data-stagger>
        <article class="ts-step">
          <div class="ts-how-icon"><img src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772534693/lens-svgrepo-com_1_bq1odp.png" alt=""></div>
          <h4>Search & Discover</h4>
          <p>Browse through a wide range of professional services and filter by category, rating, and location.</p>
        </article>
        <article class="ts-step">
          <div class="ts-how-icon"><img src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772534488/lightning-bolt-black-shape-svgrepo-com_zhc4bb.png" alt=""></div>
          <h4>Book Instantly</h4>
          <p>Select your preferred date and time, then confirm your booking with transparent pricing.</p>
        </article>
        <article class="ts-step">
          <div class="ts-how-icon"><img src="https://img.icons8.com/ios-filled/50/ffffff/star.png" alt=""></div>
          <h4>Enjoy & Review</h4>
          <p>Relax while verified professionals handle the work and share your rating after completion.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="ts-section reveal-up">
    <div class="ts-container">
      <div class="ts-video-highlight" data-stagger>
        <div class="ts-video-preview">
          <video autoplay muted loop playsinline>
            <source src="https://res.cloudinary.com/dpqnudpkj/video/upload/v1772284042/chef_2_2_zlak1y.mp4" type="video/mp4">
          </video>
        </div>
        <div class="ts-video-copy">
          <h3>See how on-demand service works in real life</h3>
          <p>From instant booking to doorstep delivery, our on-demand workflow keeps everything simple, transparent, and fast. Watch how professionals are assigned, tracked, and completed with quality checks at every step.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="ts-section reveal-up">
    <div class="ts-container ts-two-col">
      <div>
        <h2 class="ts-h2">Why customers choose us</h2>
        <p class="ts-h3">We combine trusted professionals, verified reviews, transparent pricing, and premium customer support.</p>
        <ul class="ts-bullet-list">
          <li>Verified providers with quality checks</li>
          <li>Real-time order and booking updates</li>
          <li>Secure checkout and easy support</li>
          <li>Fast reschedule and cancellation options</li>
        </ul>
      </div>
      <div class="ts-media-box">
        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?q=80&w=1400&auto=format&fit=crop" alt="">
      </div>
    </div>
  </section>

  <section class="ts-section ts-bg-light reveal-up" data-ts-providers-section>
    <div class="ts-container">
      <div class="ts-head">
        <div>
          <h2 class="ts-h2">Top Providers</h2>
          <p class="ts-h3">Highly rated professionals delivering quality service at your doorstep.</p>
        </div>
      </div>
      @php
        $providers = $topProvidersVendors ?? collect();
        $reviewMap = $topProvidersReviewCounts ?? [];
        $dummyReviews = [220, 184, 161, 195, 150, 178, 142, 203];
      @endphp
    </div>
    <div class="ts-x-scroll-wrap">
      <div class="ts-cards-4 ts-x-scroll" data-stagger data-ts-providers-grid>
        @forelse($providers as $idx => $vendor)
          @php
            $bannerUrl = '';
            if (!empty($vendor->banner['image_fit']) && !empty($vendor->banner['image_path'])) {
              $bannerUrl = $vendor->banner['image_fit'].'800/500'.$vendor->banner['image_path'];
            }
            $hasRating = $vendor->rating !== null && (float) $vendor->rating > 0;
            $displayRating = $hasRating ? number_format((float) $vendor->rating, 1) : '4.8';
            $rc = (int) ($reviewMap[$vendor->id] ?? 0);
            $displayReviews = $rc > 0 ? $rc : ($dummyReviews[$idx % count($dummyReviews)] ?? 184);
            $titleLine = trim((string) ($vendor->short_desc ?? ''));
            if ($titleLine === '') {
              $titleLine = $vendor->name;
            }
            $descLine = trim((string) ($vendor->desc ?? ''));
            $extraClass = $idx >= 4 ? ' ts-provider-hidden' : '';
          @endphp
          <a href="{{ route('vendorDetail', $vendor->slug) }}" class="ts-image-card{{ $extraClass }}" data-ts-provider-card="1" style="text-decoration:none;color:inherit;display:block;">
            <div class="img">
              <img src="{{ $bannerUrl ?: 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?q=80&w=1200&auto=format&fit=crop' }}" alt="{{ $vendor->name }}">
            </div>
            <div class="body">
              <div class="meta">{{ $vendor->name }}</div>
              <h4>{{ $titleLine }}</h4>
              @if($descLine !== '')
                <p>{{ $descLine }}</p>
              @endif
              <div class="ts-rating">★★★★★ {{ $displayRating }} ({{ $displayReviews }} reviews)</div>
            </div>
          </a>
        @empty
        @endforelse
      </div>
    </div>
    <div class="ts-container">
      @if($providers->count() > 4)
        <div class="ts-providers-toggle-wrap" data-ts-providers-toggle-wrap>
          <button type="button" class="ts-providers-toggle-btn" data-ts-providers-more>
            <span>Show More</span>
            <span class="ts-providers-toggle-chevron" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </button>
          <button type="button" class="ts-providers-toggle-btn ts-provider-hidden" data-ts-providers-less>
            <span>Show Less</span>
            <span class="ts-providers-toggle-chevron" aria-hidden="true">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 15l-6-6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </button>
        </div>
      @endif
    </div>
  </section>

  @if(!empty($homePageData['most_popular_products'] ?? []) && count($homePageData['most_popular_products'] ?? []) > 0)
  <section class="ts-section reveal-up">
    <div class="ts-container">
      <div class="ts-head">
        <div>
          <h2 class="ts-h2">{{ $popularSectionTitle ?? __('Popular Services') }}</h2>
          <p class="ts-h3">Most in-demand services across your city, curated for speed and quality.</p>
        </div>
      </div>
    </div>
    <div class="ts-x-scroll-wrap">
      <div class="ts-cards-4 ts-x-scroll" data-stagger>
        @foreach(($homePageData['most_popular_products'] ?? []) as $product)
          @php
            $pPath = isset($product->path) ? trim($product->path) : '';
            if ($pPath !== '' && preg_match('#^https?://#i', $pPath)) {
              $imgUrl = $pPath;
            } elseif ($pPath !== '') {
              $imgUrl = get_file_path($pPath, 'FILL_URL', '600', '360');
            } else {
              $imgUrl = 'https://images.unsplash.com/photo-1603712725038-e9334ae8f39f?q=80&w=1200&auto=format&fit=crop';
            }
            $productHref = url($product->vendor_slug . '/product/' . $product->url_slug);
          @endphp
          <a href="{{ $productHref }}" class="ts-image-card" style="text-decoration:none;color:inherit;display:block;">
            <div class="img"><img src="{{ $imgUrl }}" alt="{{ $product->title ?? '' }}"></div>
            <div class="body">
              <h4>{{ $product->title ?? '' }}</h4>
              <p>
                @if(!empty($product->price_numeric))
                  {{ __('From') }} {!! showPriceWithCurrency($product->price_numeric) !!}
                @endif
                @if(!empty($product->vendor_name))
                  @if(!empty($product->price_numeric)) • @endif{{ $product->vendor_name }}
                @endif
              </p>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>
  @endif
  <section class="ts-section reveal-up" style="padding-top: 28px;">
    <div class="ts-container">
      <div class="ts-mid-banner" data-stagger>
        <img src="{{ asset('chef-banner.jpg') }}" alt="Chef special service banner">
      </div>
    </div>
  </section>

  @if(!empty($homePageData['featured_products'] ?? []) && count($homePageData['featured_products'] ?? []) > 0)
  <section class="ts-section ts-bg-light reveal-up">
    <div class="ts-container">
      <div class="ts-head">
        <div>
          <h2 class="ts-h2">{{ $featuredSectionTitle ?? __('Featured Services') }}</h2>
          <p class="ts-h3">Premium hand-picked services for the best user experience.</p>
        </div>
      </div>
    </div>
    <div class="ts-x-scroll-wrap">
      <div class="ts-cards-4 ts-x-scroll" data-stagger>
        @foreach(($homePageData['featured_products'] ?? []) as $product)
          @php
            $pPath = isset($product->path) ? trim($product->path) : '';
            if ($pPath !== '' && preg_match('#^https?://#i', $pPath)) {
              $imgUrl = $pPath;
            } elseif ($pPath !== '') {
              $imgUrl = get_file_path($pPath, 'FILL_URL', '600', '360');
            } else {
              $imgUrl = 'https://images.unsplash.com/photo-1556909212-d5b604d0c90d?q=80&w=1200&auto=format&fit=crop';
            }
            $productHref = url($product->vendor_slug . '/product/' . $product->url_slug);
            $metaLabel = $product->category_name ?? __('Premium');
            $desc = $product->meta_description ?? $product->vendor_name ?? '';
          @endphp
          <a href="{{ $productHref }}" class="ts-image-card" style="text-decoration:none;color:inherit;display:block;">
            <div class="img"><img src="{{ $imgUrl }}" alt="{{ $product->title ?? '' }}"></div>
            <div class="body">
              <div class="meta">{{ $metaLabel }}</div>
              <h4>{{ $product->title ?? '' }}</h4>
              @if($desc !== '')
                <p>{{ $desc }}</p>
              @endif
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <section class="ts-section reveal-up">
    <div class="ts-container">
      <div class="ts-cta ts-cta--bg">
        <div>
          <h2 class="ts-h2" style="margin-bottom: 6px;">Become a service partner</h2>
          <p class="ts-h3" style="color: rgba(255,255,255,0.92); margin: 0;">Grow your business by listing your services and receiving quality bookings daily.</p>
        </div>
        <a href="#">Get Started</a>
      </div>
    </div>
  </section>

  <section class="ts-section reveal-up">
    <div class="ts-container">
      <div class="ts-head">
        <div>
          <h2 class="ts-h2">What our customers say</h2>
          <p class="ts-h3">Real feedback from users who book services daily on our platform.</p>
        </div>
      </div>
    </div>
    <div class="ts-x-scroll-wrap">
      <div class="ts-cards-4 ts-x-scroll" data-stagger>
        <article class="ts-step">
          <img class="ts-avatar" src="https://randomuser.me/api/portraits/women/65.jpg" alt="">
          <h4>“Excellent experience”</h4>
          <p>Booking was smooth and the professional arrived on time. Highly recommended for busy families.</p>
        </article>
        <article class="ts-step">
          <img class="ts-avatar" src="https://randomuser.me/api/portraits/men/33.jpg" alt="">
          <h4>“Very convenient”</h4>
          <p>I found and booked an electrician in under five minutes. The app flow is fast and very clear.</p>
        </article>
        <article class="ts-step">
          <img class="ts-avatar" src="https://randomuser.me/api/portraits/women/12.jpg" alt="">
          <h4>“Best service quality”</h4>
          <p>The quality of work was top-notch and support team was quick to respond throughout the process.</p>
        </article>
        <article class="ts-step">
          <img class="ts-avatar" src="https://randomuser.me/api/portraits/men/57.jpg" alt="">
          <h4>“Will book again”</h4>
          <p>Transparent pricing, clean UI, and reliable providers. I already booked my second service.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="ts-section reveal-up" style="padding-top: 24px;">
    <div class="ts-container">
      <div class="ts-faq-wrap">
        <div class="ts-head" style="justify-content:center; text-align:center; margin-bottom: 28px;">
          <div>
            <!-- <span class="ts-faq-badge">Support</span> -->
            <h2 class="ts-h2">Frequently Asked <span style="color:#e3495b;">Questions</span></h2>
            <p class="ts-h3" style="margin-left:auto; margin-right:auto;">Everything you need to know about RestoCare - bookings, providers, and payments.</p>
          </div>
        </div>

        <div class="ts-faq-split">
          <aside class="ts-faq-media reveal-up">
            <div class="ts-faq-media-main">
              <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1400&auto=format&fit=crop" alt="">
              <div class="ts-faq-media-overlay">
                <h4>Need Help Fast?</h4>
                <p>Watch on-demand booking flow and quick support guide.</p>
              </div>
            </div>
          </aside>

          <div class="ts-faq-list" data-stagger>
            <article class="ts-faq-item is-open">
              <button type="button" class="ts-faq-question" aria-expanded="true">
                <span class="ts-faq-no">01</span>
                <h4 class="ts-faq-title">What Is RestoCare?</h4>
                <span class="ts-faq-chevron">⌄</span>
              </button>
              <div class="ts-faq-answer">
                <p>RestoCare is an on-demand service marketplace where you can discover verified professionals, compare options, and book services in just a few clicks.</p>
              </div>
            </article>

            <article class="ts-faq-item">
              <button type="button" class="ts-faq-question" aria-expanded="false">
                <span class="ts-faq-no">02</span>
                <h4 class="ts-faq-title">How Do I Book A Service?</h4>
                <span class="ts-faq-chevron">⌄</span>
              </button>
              <div class="ts-faq-answer">
                <p>Search your required service, choose a provider based on ratings and pricing, select your preferred date and time, then confirm your booking securely.</p>
              </div>
            </article>

            <article class="ts-faq-item">
              <button type="button" class="ts-faq-question" aria-expanded="false">
                <span class="ts-faq-no">03</span>
                <h4 class="ts-faq-title">Can I Track My Booking In Real Time?</h4>
                <span class="ts-faq-chevron">⌄</span>
              </button>
              <div class="ts-faq-answer">
                <p>Yes, you can monitor booking status, provider assignment, and service progress from your account dashboard in real time.</p>
              </div>
            </article>

            <article class="ts-faq-item">
              <button type="button" class="ts-faq-question" aria-expanded="false">
                <span class="ts-faq-no">04</span>
                <h4 class="ts-faq-title">How Can I Register As A Service Provider?</h4>
                <span class="ts-faq-chevron">⌄</span>
              </button>
              <div class="ts-faq-answer">
                <p>Click on "Become a Service Partner", complete the onboarding form, submit required documents, and our team will review and activate your profile.</p>
              </div>
            </article>

            <article class="ts-faq-item">
              <button type="button" class="ts-faq-question" aria-expanded="false">
                <span class="ts-faq-no">05</span>
                <h4 class="ts-faq-title">Is My Payment Information Secure?</h4>
                <span class="ts-faq-chevron">⌄</span>
              </button>
              <div class="ts-faq-answer">
                <p>Absolutely. We use secure encrypted payment gateways and never store sensitive card details on our application servers.</p>
              </div>
            </article>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@section('home-page')
<script type="text/javascript" src="{{ asset('assets/js/template/commonFunction.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/template/template-six/templateFunction.js') }}"></script>
<script>
  (function () {
    const homeRoot = document.querySelector('.ts-home');
    if (!homeRoot) {
      return;
    }

    const reveals = homeRoot.querySelectorAll('.reveal-up');
    const revealObserver = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.14, rootMargin: '0px 0px -20px 0px' });
    reveals.forEach(el => revealObserver.observe(el));

    const hero = document.getElementById('ts-hero');
    if (hero) {
    const track = hero.querySelector('[data-ts-hero-track]');
    const slides = track ? Array.from(track.children) : [];
    const dotsWrap = hero.querySelector('[data-ts-hero-dots]');
    let index = 0;
    let timer = null;

    function render() {
      if (!track || !slides.length) {
        return;
      }
      track.style.transform = 'translateX(-' + (index * 100) + '%)';
      if (dotsWrap) {
        Array.from(dotsWrap.children).forEach((dot, i) => {
          dot.classList.toggle('is-active', i === index);
        });
      }
    }

    function go(nextIndex) {
      index = (nextIndex + slides.length) % slides.length;
      render();
    }

    function start() {
      if (slides.length <= 1) {
        return;
      }
      stop();
      timer = window.setInterval(function () {
        go(index + 1);
      }, 4200);
    }

    function stop() {
      if (timer) {
        window.clearInterval(timer);
        timer = null;
      }
    }

    if (dotsWrap) {
      dotsWrap.innerHTML = '';
      slides.forEach(function (_, i) {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'ts-dot' + (i === 0 ? ' is-active' : '');
        dot.addEventListener('click', function () {
          go(i);
          start();
        });
        dotsWrap.appendChild(dot);
      });
    }

    hero.addEventListener('mouseenter', stop);
    hero.addEventListener('mouseleave', start);
    render();
    start();
    }

    const faqItems = homeRoot.querySelectorAll('.ts-faq-item');
    faqItems.forEach(item => {
      const button = item.querySelector('.ts-faq-question');
      const answer = item.querySelector('.ts-faq-answer');
      if (!button || !answer) {
        return;
      }

      if (item.classList.contains('is-open')) {
        answer.style.maxHeight = answer.scrollHeight + 'px';
      }

      button.addEventListener('click', function () {
        const currentlyOpen = item.classList.contains('is-open');

        faqItems.forEach(other => {
          other.classList.remove('is-open');
          const otherBtn = other.querySelector('.ts-faq-question');
          const otherAnswer = other.querySelector('.ts-faq-answer');
          if (otherBtn) {
            otherBtn.setAttribute('aria-expanded', 'false');
          }
          if (otherAnswer) {
            otherAnswer.style.maxHeight = '0px';
          }
        });

        if (!currentlyOpen) {
          item.classList.add('is-open');
          button.setAttribute('aria-expanded', 'true');
          answer.style.maxHeight = answer.scrollHeight + 'px';
        }
      });
    });

    const providerGrid = homeRoot.querySelector('[data-ts-providers-grid]');
    const providerToggleWrap = homeRoot.querySelector('[data-ts-providers-toggle-wrap]');
    if (providerGrid && providerToggleWrap) {
      const moreBtn = providerToggleWrap.querySelector('[data-ts-providers-more]');
      const lessBtn = providerToggleWrap.querySelector('[data-ts-providers-less]');
      let scrollYBeforeExpand = null;
      function getProviderCards() {
        return Array.from(providerGrid.querySelectorAll('[data-ts-provider-card]'));
      }
      function setExtraVisible(visible) {
        getProviderCards().forEach(function (el, i) {
          if (i >= 4) {
            el.classList.toggle('ts-provider-hidden', !visible);
          }
        });
        if (moreBtn && lessBtn) {
          moreBtn.classList.toggle('ts-provider-hidden', visible);
          lessBtn.classList.toggle('ts-provider-hidden', !visible);
        }
      }
      if (moreBtn) {
        moreBtn.addEventListener('click', function () {
          scrollYBeforeExpand = window.scrollY || window.pageYOffset || 0;
          setExtraVisible(true);
        });
      }
      if (lessBtn) {
        lessBtn.addEventListener('click', function () {
          setExtraVisible(false);
          var targetY = scrollYBeforeExpand;
          scrollYBeforeExpand = null;
          requestAnimationFrame(function () {
            requestAnimationFrame(function () {
              if (targetY !== null && targetY !== undefined) {
                window.scrollTo({ top: targetY, behavior: 'smooth' });
              }
            });
          });
        });
      }
    }
  })();
</script>
@endsection
