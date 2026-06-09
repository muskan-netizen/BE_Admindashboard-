@extends('layouts.store', ['title' => __('Home')])
@section('css-links')
<script>
	var featured_products_length = {{ isset($homePageData['featured_products']) ? count($homePageData['featured_products']) : ''}};
</script>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1150346783738431');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1150346783738431&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
@endsection
@section('cssnew')
<style>
/* ====================================================
   URBAN COMPANY STYLE — GLOBAL DESIGN SYSTEM
   ==================================================== */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

:root {
  --uc-purple:       #663399;
  --uc-purple-hover: #4e2577;
  --uc-purple-light: #f5eeff;
  --uc-dark:         #1a1a1a;
  --uc-gray:         #717171;
  --uc-gray-light:   #f7f7f7;
  --uc-border:       #e8e8e8;
  --uc-white:        #ffffff;
  --uc-star:         #f5a623;
  --uc-green:        #02bd7a;
  --uc-shadow-sm:    0 2px 8px rgba(0,0,0,.07);
  --uc-shadow-md:    0 4px 18px rgba(0,0,0,.10);
  --uc-shadow-hover: 0 8px 30px rgba(0,0,0,.14);
  --uc-radius:       14px;
  --uc-radius-sm:    8px;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
  background: #fff;
  color: var(--uc-dark);
  -webkit-font-smoothing: antialiased;
}

/* ── Section wrappers ── */
.suppliers-section,
section.container-fluid.mb-0,
section.container.mb-0 {
  padding: 28px 20px !important;
}

/* ── Section headings UC-style ── */
.h2-heading {
  font-size: 20px !important;
  font-weight: 700 !important;
  color: #1a1a1a !important;
  letter-spacing: -.3px !important;
  margin: 0 !important;
  line-height: 1.3 !important;
}

.top-heading,
.al_top_heading {
  display: flex !important;
  align-items: center !important;
  justify-content: space-between !important;
  margin-bottom: 18px !important;
  padding: 0 !important;
}

.top-heading a,
.al_top_heading a {
  font-size: 13px !important;
  font-weight: 600 !important;
  color: var(--uc-purple) !important;
  text-decoration: none !important;
  letter-spacing: .1px !important;
}

.top-heading a:hover,
.al_top_heading a:hover {
  color: var(--uc-purple-hover) !important;
  text-decoration: underline !important;
}

/* ── UC Vendor card ── */
.al_custom_vendors_sec {
  padding: 6px !important;
}

.al_custom_vendors_sec .suppliers-box {
  border-radius: var(--uc-radius) !important;
  overflow: hidden !important;
  background: var(--uc-white) !important;
  box-shadow: var(--uc-shadow-sm) !important;
  border: 1px solid var(--uc-border) !important;
  transition: box-shadow .22s ease, transform .22s ease !important;
  display: block !important;
}

.al_custom_vendors_sec .suppliers-box:hover {
  box-shadow: var(--uc-shadow-hover) !important;
  transform: translateY(-3px) !important;
  border-color: #d4b3f0 !important;
}

.al_custom_vendors_sec .suppliers-img-outer {
  background: var(--uc-gray-light) !important;
  overflow: hidden !important;
  border-bottom: 1px solid var(--uc-border) !important;
}

.al_custom_vendors_sec .supplier-rating h6 {
  font-weight: 600 !important;
  font-size: 13px !important;
  color: #1a1a1a !important;
  margin: 0 0 2px !important;
}

/* ── UC Product card ── */
.product-card-box {
  border-radius: var(--uc-radius) !important;
  overflow: hidden !important;
  background: var(--uc-white) !important;
  box-shadow: var(--uc-shadow-sm) !important;
  border: 1px solid var(--uc-border) !important;
  transition: box-shadow .22s ease, transform .22s ease !important;
  margin: 6px !important;
}

.product-card-box:hover {
  box-shadow: var(--uc-shadow-hover) !important;
  transform: translateY(-3px) !important;
  border-color: #d4b3f0 !important;
}

.card_title,
.product-card-box h6 {
  font-weight: 600 !important;
  color: #1a1a1a !important;
  font-size: 13.5px !important;
}

/* ── Rating badge UC-style ── */
.rating-number {
  background: #fff8e7 !important;
  color: #b7791f !important;
  font-size: 11.5px !important;
  font-weight: 700 !important;
  padding: 2px 7px !important;
  border-radius: 20px !important;
  border: 1px solid #f5e4b0 !important;
  display: inline-flex !important;
  align-items: center !important;
  gap: 3px !important;
}

/* ── UC Button style ── */
.btn-solid {
  background: var(--uc-purple) !important;
  border-color: var(--uc-purple) !important;
  border-radius: 8px !important;
  font-weight: 600 !important;
  font-size: 13px !important;
  letter-spacing: .2px !important;
  transition: background .18s, transform .18s !important;
}

.btn-solid:hover {
  background: var(--uc-purple-hover) !important;
  transform: translateY(-1px) !important;
}

/* ── Banner wrapper ── */
.home-slider-wrapper {
  position: relative;
  z-index: 1;
  width: 100%;
}

#home-slider-id {
  padding-top: 50px;
}
 
.home-slider-wrapper .home-slider-banner-shell {
    width: 100%;
}
 
/* ── Each carousel (desktop / mobile) ── */
.home-slider-wrapper .home-banner-carousel {
    position: relative;
    display: block;
    width: 100%;
    overflow: hidden;
    background: #fff;
}
 
/* Mobile carousel hidden on desktop by default */
.home-slider-wrapper .home-banner-carousel.is-mobile {
    display: none !important;
}
 
/* Force-hide utility used by JS */
.home-slider-wrapper .home-banner-carousel.is-hidden-force {
    display: none !important;
}
 
/* ── Slide track ── */
.home-slider-wrapper .home-banner-track {
    display: flex;
    width: 100%;
    transition: transform 0.45s ease;
    will-change: transform;
}
 
.home-slider-wrapper .home-banner-slide {
    flex: 0 0 100%;
    min-width: 100%;
}
 
/* ── Image container — aspect-ratio drives the height ── */
.home-slider-wrapper .banner-img-outer {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    overflow: hidden;
    background: #f5f5f5;
    text-decoration: none;
}
 
/* Desktop banner: 1920 × 700 */
.home-slider-wrapper .home-banner-carousel.is-desktop .banner-img-outer {
    aspect-ratio: 1920 / 700;
}
 
/* Mobile banner: 400 × 250 */
.home-slider-wrapper .home-banner-carousel.is-mobile .banner-img-outer {
    aspect-ratio: 400 / 250;
}
 
/* ── Banner image ── */
.home-slider-wrapper .banner-img-outer img,
.home-slider-wrapper .banner-img {
    display: block;
    width: 100%;
    height: 100%;
    max-width: 100% !important;
    max-height: none !important;
    object-fit: cover;           /* fills edge-to-edge, no white gaps */
    object-position: center;
}
 
/* ── Prev / Next buttons ── */
.home-slider-wrapper .home-banner-nav {
    position: absolute;
    top: 50%;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    padding: 0;
    border: 0;
    border-radius: 50%;
    background: rgba(17, 24, 39, 0.52);
    color: #fff;
    cursor: pointer;
    transform: translateY(-50%);
    transition: background 0.2s ease;
    line-height: 1;
}
 
.home-slider-wrapper .home-banner-nav:hover {
    background: rgba(17, 24, 39, 0.80);
}
 
.home-slider-wrapper .home-banner-nav.prev { left: 14px; }
.home-slider-wrapper .home-banner-nav.next { right: 14px; }
 
.home-slider-wrapper .home-banner-nav span {
    font-size: 30px;
    line-height: 1;
    display: block;
    margin-top: -2px; /* optical centering of ‹ › glyphs */
}
 
/* ── Dot indicators ── */
.home-slider-wrapper .home-banner-dots {
    position: absolute;
    left: 50%;
    bottom: 12px;
    z-index: 2;
    display: flex;
    gap: 7px;
    transform: translateX(-50%);
}
 
.home-slider-wrapper .home-banner-dot {
    width: 8px;
    height: 8px;
    padding: 0;
    border: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.50);
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
}
 
.home-slider-wrapper .home-banner-dot.is-active {
    background: #fff;
    transform: scale(1.35);
}
 
/* hidden attribute support */
.home-slider-wrapper .home-banner-nav[hidden],
.home-slider-wrapper .home-banner-dots[hidden] {
    display: none !important;
}
 
/* ══════════════════════════════════════
   RESPONSIVE BREAKPOINTS
══════════════════════════════════════ */
 
/* Large tablet */
@media (max-width: 1199px) {
    .home-slider-wrapper .home-banner-carousel.is-desktop .banner-img-outer {
        aspect-ratio: 1280 / 420;
    }
}
 
/* Tablet */
@media (max-width: 991px) {
    .home-slider-wrapper .home-banner-carousel.is-desktop .banner-img-outer {
        aspect-ratio: 1024 / 380;
    }
    .home-slider-wrapper .home-banner-nav {
        width: 36px;
        height: 36px;
    }
    .home-slider-wrapper .home-banner-nav span {
        font-size: 24px;
    }
}
 
/* Mobile — swap to mobile carousel */
@media (max-width: 767px) {
    .home-slider-wrapper .home-banner-carousel.is-desktop {
        display: none !important;
    }
    .home-slider-wrapper .home-banner-carousel.is-mobile {
        display: block !important;
    }
    .home-slider-wrapper .home-banner-carousel.is-hidden-force {
        display: none !important;
    }
    .home-slider-wrapper .home-banner-carousel.is-mobile .banner-img-outer {
        aspect-ratio: 400 / 150;
    }
    .home-slider-wrapper .home-banner-nav {
        width: 30px;
        height: 30px;
    }
    .home-slider-wrapper .home-banner-nav span {
        font-size: 20px;
    }
    .home-slider-wrapper .home-banner-nav.prev { left: 8px; }
    .home-slider-wrapper .home-banner-nav.next { right: 8px; }
    .home-slider-wrapper .home-banner-dots    { bottom: 7px; }
    .home-slider-wrapper .home-banner-dot     { width: 6px; height: 6px; }
}
 
/* Small mobile */
@media (max-width: 479px) {
    .home-slider-wrapper .home-banner-nav {
        width: 26px;
        height: 26px;
    }
    .home-slider-wrapper .home-banner-nav span {
        font-size: 17px;
    }
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css">
@endsection
@section('content')
<!-- shimmer_effect start -->
<section class="section-b-space_  p-0 ratio_asos alHomeServiceShimmer ">
	<div class="container_al mb-3 shimmer_effect main_shimer">
		<div class="row">
			<div class="col-12 cards">
				<div class="headerLoding loading"></div>
            <div class="cardbanner loading"></div>
			</div>
		</div>
	</div>
   <div class="container mb-3 shimmer_effect alcardCatagory main_shimer">
		<div class="row">
			<div class="col-2 cards">
				<div class="cardCatagory loading"></div>
			</div>
         <div class="col-2 cards">
				<div class="cardCatagory loading"></div>
			</div>
         <div class="col-2 cards">
				<div class="cardCatagory loading"></div>
			</div>
         <div class="col-2 cards">
				<div class="cardCatagory loading"></div>
			</div>
         <div class="col-2 cards">
				<div class="cardCatagory loading"></div>
			</div>
         <div class="col-2 cards">
				<div class="cardCatagory loading"></div>
			</div>

		</div>
	</div>

   <div class="container mb-3 shimmer_effect  main_shimer">
         <div class="row">
            <div class="col-12">
               <div class="card_title ml-4 mb-4 maintitle loading"></div>
            </div>
         </div>
         <div class="row">
            <div class="col-12 px-md-5 px-2">
               <div class="grid-row grid-4-4">
                  <div class="cards">
                     <div class="card_image loading"></div>
                     <div class="d-flex align-items-center justify-content-between">
                           <div class="card_title loading"></div>
                           <div class="card_icon loading"></div>
                     </div>
                     <div class="card_content loading mt-0 w-75"></div>
                     <div class="card_content loading mt-0 w-50"></div>
                     <div class="card_line loading"></div>
                     <div class="card_price loading"></div>
                  </div>
                  <div class="cards">
                     <div class="card_image loading"></div>
                     <div class="d-flex align-items-center justify-content-between">
                           <div class="card_title loading"></div>
                           <div class="card_icon loading"></div>
                     </div>
                     <div class="card_content loading mt-0 w-75"></div>
                     <div class="card_content loading mt-0 w-50"></div>
                     <div class="card_line loading"></div>
                     <div class="card_price loading"></div>
                  </div>
                  <div class="cards">
                     <div class="card_image loading"></div>
                     <div class="d-flex align-items-center justify-content-between">
                           <div class="card_title loading"></div>
                           <div class="card_icon loading"></div>
                     </div>
                     <div class="card_content loading mt-0 w-75"></div>
                     <div class="card_content loading mt-0 w-50"></div>
                     <div class="card_line loading"></div>
                     <div class="card_price loading"></div>
                  </div>
                  <div class="cards">
                     <div class="card_image loading"></div>
                     <div class="d-flex align-items-center justify-content-between">
                           <div class="card_title loading"></div>
                           <div class="card_icon loading"></div>
                     </div>
                     <div class="card_content loading mt-0 w-75"></div>
                     <div class="card_content loading mt-0 w-50"></div>
                     <div class="card_line loading"></div>
                     <div class="card_price loading"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>


	</div>
</section>
<!-- shimmer_effect end -->
<button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#login_modal"> Launch demo modal </button>
@if(count($banners))



<!-- /////////////// hero banner -->

<section class="home-slider-wrapper" id="home-slider-id">

	<div class="home-slider-banner-shell">
		<div id="myCarousel" class="home-banner-carousel is-desktop al_desktop_banner" data-banner-carousel>
			<div class="home-banner-track" data-banner-track>
				<div class="home-banner-slide">
					<a class="banner-img-outer" href="#">
						<img alt="Chef cooking" class="lazyload banner-img" src="https://images.pexels.com/photos/2290753/pexels-photo-2290753.jpeg?auto=compress&cs=tinysrgb&w=1920" data-src="https://images.pexels.com/photos/2290753/pexels-photo-2290753.jpeg?auto=compress&cs=tinysrgb&w=1920">
					</a>
				</div>
				<div class="home-banner-slide">
					<a class="banner-img-outer" href="#">
						<img alt="Restaurant Interior" class="lazyload banner-img" src="https://images.pexels.com/photos/262978/pexels-photo-262978.jpeg?auto=compress&cs=tinysrgb&w=1920" data-src="https://images.pexels.com/photos/262978/pexels-photo-262978.jpeg?auto=compress&cs=tinysrgb&w=1920">
					</a>
				</div>
				<div class="home-banner-slide">
					<a class="banner-img-outer" href="#">
						<img alt="Commercial Kitchen" class="lazyload banner-img" src="https://images.pexels.com/photos/333850/pexels-photo-333850.jpeg?auto=compress&cs=tinysrgb&w=1920" data-src="https://images.pexels.com/photos/333850/pexels-photo-333850.jpeg?auto=compress&cs=tinysrgb&w=1920">
					</a>
				</div>
				<div class="home-banner-slide">
					<a class="banner-img-outer" href="#">
						<img alt="Chef Plating" class="lazyload banner-img" src="https://images.pexels.com/photos/887827/pexels-photo-887827.jpeg?auto=compress&cs=tinysrgb&w=1920" data-src="https://images.pexels.com/photos/887827/pexels-photo-887827.jpeg?auto=compress&cs=tinysrgb&w=1920">
					</a>
				</div>
			</div>
			<button type="button" class="home-banner-nav prev" data-banner-prev aria-label="{{__('Previous')}}">
				<span aria-hidden="true">&#8249;</span>
			</button>
			<button type="button" class="home-banner-nav next" data-banner-next aria-label="{{__('Next')}}">
				<span aria-hidden="true">&#8250;</span>
			</button>
			<div class="home-banner-dots" data-banner-dots></div>
		</div>

		<div id="myMobileCarousel" class="home-banner-carousel is-mobile al_mobile_banner" data-banner-carousel data-no-slick="true">
			<div class="home-banner-track" data-banner-track>
				<div class="home-banner-slide">
					<a class="banner-img-outer" href="#">
						<img alt="Chef cooking" class="lazyload banner-img" src="https://images.pexels.com/photos/2290753/pexels-photo-2290753.jpeg?auto=compress&cs=tinysrgb&w=800" data-src="https://images.pexels.com/photos/2290753/pexels-photo-2290753.jpeg?auto=compress&cs=tinysrgb&w=800">
					</a>
				</div>
				<div class="home-banner-slide">
					<a class="banner-img-outer" href="#">
						<img alt="Restaurant Interior" class="lazyload banner-img" src="https://images.pexels.com/photos/262978/pexels-photo-262978.jpeg?auto=compress&cs=tinysrgb&w=800" data-src="https://images.pexels.com/photos/262978/pexels-photo-262978.jpeg?auto=compress&cs=tinysrgb&w=800">
					</a>
				</div>
				<div class="home-banner-slide">
					<a class="banner-img-outer" href="#">
						<img alt="Commercial Kitchen" class="lazyload banner-img" src="https://images.pexels.com/photos/333850/pexels-photo-333850.jpeg?auto=compress&cs=tinysrgb&w=800" data-src="https://images.pexels.com/photos/333850/pexels-photo-333850.jpeg?auto=compress&cs=tinysrgb&w=800">
					</a>
				</div>
				<div class="home-banner-slide">
					<a class="banner-img-outer" href="#">
						<img alt="Chef Plating" class="lazyload banner-img" src="https://images.pexels.com/photos/887827/pexels-photo-887827.jpeg?auto=compress&cs=tinysrgb&w=800" data-src="https://images.pexels.com/photos/887827/pexels-photo-887827.jpeg?auto=compress&cs=tinysrgb&w=800">
					</a>
				</div>
			</div>
			<button type="button" class="home-banner-nav prev" data-banner-prev aria-label="{{__('Previous')}}">
				<span aria-hidden="true">&#8249;</span>
			</button>
			<button type="button" class="home-banner-nav next" data-banner-next aria-label="{{__('Next')}}">
				<span aria-hidden="true">&#8250;</span>
			</button>
			<div class="home-banner-dots" data-banner-dots></div>
   </div>

	</div>
</section>


@else
<section class="home-slider-wrapper" style="min-height: 150px" id="home-slider-id">

   <div class="home-slider-banner-shell">
		<div id="myCarousel" class="home-banner-carousel is-desktop al_desktop_banner" data-banner-carousel></div>
      <div id="myMobileCarousel" class="home-banner-carousel is-mobile al_mobile_banner" data-banner-carousel data-no-slick="true"></div>
   </div>
</section>
@endif
@if(count($navCategories))

<!-- Category section start -->
 <section id="premiumCategoryUI" class="alSixMainMenu my-menu" style="width: 100%; background: white; margin: 0; padding: 0;">
      <div class="menu-navigation_al" style="width: 100%; background: white; margin: 0px; padding: 0;">
         <!-- testing -->

<style>
/* ===============================
   URBAN COMPANY — CATEGORY UI
=============================== */
#premiumCategoryUI {
  width: 100%;
  background: #ffffff;
  padding: 40px 20px 20px;
  border-bottom: 1px solid #f0f0f0;
}

#premiumCategoryUI .popular-categories-header {
  margin-bottom: 30px;
  padding-left: 10px;
}

#premiumCategoryUI .popular-categories-header h2 {
  font-weight: 800;
  font-size: 32px;
  color: #111b2b;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: -0.5px;
}

#premiumCategoryUI .popular-categories-header p {
  color: #6c757d;
  font-size: 16px;
  margin: 0;
}

/* Grid Layout */
#premiumCategoryUI .cat-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 24px 20px;
  list-style: none;
  padding: 0;
  margin: 0;
}

/* Category Card */
#premiumCategoryUI .al_main_category {
  display: flex;
  flex-direction: column;
  width: 180px;
}

/* Clickable Area */
#premiumCategoryUI .al_main_category > a {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
  width: 100%;
  color: #1a1a1a;
}

/* White card with border */
#premiumCategoryUI .nav-cate-img {
  width: 160px;
  height: 100px;
  border-radius: 14px;
  background: #ffffff;
  border: 1px solid #eaebf0;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 12px;
  transition: all .2s ease;
  box-shadow: 0 2px 8px rgba(0,0,0,0.02);
}

/* Hover Effect */
#premiumCategoryUI .al_main_category > a:hover .nav-cate-img {
  border-color: #d1d5db;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  transform: translateY(-2px);
}

/* Circle inside white card */
#premiumCategoryUI .nav-cate-img-inner {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: #f4f7fb;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

/* Image */
#premiumCategoryUI .nav-cate-img-inner img {
  width: 34px !important;
  height: 34px !important;
  object-fit: contain;
  padding: 0 !important;
}

/* Category Name */
#premiumCategoryUI .alCategoryName {
  font-size: 15px !important;
  font-weight: 700;
  color: #111b2b;
  line-height: 1.4;
  text-align: center;
  word-break: break-word;
  margin-top: 6px;
}

/* Active Category */
#premiumCategoryUI a.current_category .nav-cate-img {
  border-color: var(--uc-purple);
  box-shadow: 0 4px 12px rgba(102, 51, 153, 0.1);
}

#premiumCategoryUI a.current_category .alCategoryName {
  color: var(--uc-purple);
}

/* ===============================
   RESPONSIVE
=============================== */
@media (max-width: 991px) {
  #premiumCategoryUI .cat-grid { gap: 15px 10px; justify-content: center; }
  #premiumCategoryUI .al_main_category { width: 130px; }
  #premiumCategoryUI .nav-cate-img { width: 120px; height: 70px; }
  #premiumCategoryUI .nav-cate-img-inner { width: 44px; height: 44px; }
  #premiumCategoryUI .nav-cate-img-inner img { width: 26px !important; height: 26px !important; }
}

@media (max-width: 600px) {
  #premiumCategoryUI { padding: 30px 10px 15px; }
  #premiumCategoryUI .popular-categories-header h2 { font-size: 22px; }
  #premiumCategoryUI .popular-categories-header p { font-size: 13px; }
  #premiumCategoryUI .al_main_category { width: 100px; }
  #premiumCategoryUI .nav-cate-img { width: 95px; height: 60px; border-radius: 10px; margin-bottom: 8px; }
  #premiumCategoryUI .nav-cate-img-inner { width: 38px; height: 38px; }
  #premiumCategoryUI .nav-cate-img-inner img { width: 22px !important; height: 22px !important; }
  #premiumCategoryUI .alCategoryName { font-size: 11px !important; }
}

@media (max-width: 380px) {
  #premiumCategoryUI .al_main_category { width: 85px; }
  #premiumCategoryUI .nav-cate-img { width: 80px; height: 56px; }
  #premiumCategoryUI .nav-cate-img-inner { width: 34px; height: 34px; }
  #premiumCategoryUI .nav-cate-img-inner img { width: 20px !important; height: 20px !important; }
  #premiumCategoryUI .alCategoryName { font-size: 10px !important; }
}

/* ── Outer section ── */
.space-slider-homeric {
  width: 100%;
  background: #fff;
  margin: 0;
  margin-bottom: 10px;
  overflow-x: hidden;
  box-sizing: border-box;
}

.space-slider-homeric .cat-row { 
  width: 100%; 
  margin: 0 auto; 
  padding: 0; 
  max-width: 1200px;
  overflow-x: hidden;
  box-sizing: border-box;
}

.cat-grid {
  width: 100%;
  max-width: 100%;
  overflow-x: hidden;
  box-sizing: border-box;
}

.al_main_category {
  max-width: 100%;
  box-sizing: border-box;
}

.al_main_category_list,
.al_main_category_sub_list { display: none !important; }

/* Main Container */




 #videoContainerX9a72 {
  width: 100%;
  overflow: hidden;
}





/* Desktop + Laptop */
#videoSectionX9a72 {
  position: relative;
  width: 100%;
  height: 50vh;   /* Desktop + Laptop */
  min-height: 400px;
  background: #000;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

/* Video */
#videoPlayerX9a72 {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* ================= Responsive ================= */

/* Tablet */
@media (max-width: 992px) {
  #videoSectionX9a72 {
    height: 40vh;
  }
}

/* Mobile */
@media (max-width: 576px) {
  #videoSectionX9a72 {
    height: 30vh;
    min-height: 200px;
  }
}
</style>

{{-- ── BLADE TEMPLATE — no Bootstrap classes ── --}}
<div class="space-slider-homeric" style="padding: 10px 10px;">
  <div class="cat-row">
    <div class="popular-categories-header">
      <h2>POPULAR CATEGORIES</h2>
      <p>Choose your service category and connect with top-rated professionals near you.</p>
    </div>
    <ul class="cat-grid">

      @foreach($navCategories as $cate)
        @if($cate['name'])
          <li class="al_main_category" style="padding: 0px; margin: 0px; overflow: hidden;">

            <a href="{{ route('categoryDetail', $cate['slug']) }}"
               class="{{ isset($category) && $category->slug == $cate['slug'] ? 'current_category' : '' }}">

              @if($client_preference_detail->show_icons == 1 && (\Request::route()->getName() == 'userHome' || \Request::route()->getName() == 'categoryDetail') || \Request::route()->getName() == 'homeTest')
                <div class="nav-cate-img {{ \Request::route()->getName() == 'userHome' ? '' : 'activ_nav' }}">
                  <div class="nav-cate-img-inner">
                    <img
                      class="lazyload"
                      src="{{ $cate['icon']['image_fit'] }}78/78{{ $cate['icon']['image_path'] }}"
                      data-src="{{ $cate['icon']['image_fit'] }}78/78{{ $cate['icon']['image_path'] }}"
                      data-icon="{{ $cate['icon']['image_fit'] }}200/200{{ $cate['icon']['image_path'] }}"
                      data-icon_two="{{ !is_null($cate['icon_two']) ? $cate['icon_two']['image_fit'].'200/200'.$cate['icon_two']['image_path'] : $cate['icon']['image_fit'].'200/200'.$cate['icon']['image_path'] }}"
                      alt="{{ $cate['name'] }}"
                      width="78"
                      height="78"
                      onmouseover="changeImage(this,1)"
                      onmouseout="changeImage(this,0)"
                      style="padding: 0px; margin: 0px; background-color: transparent;"
                    >
                  </div>
                </div>
              @endif

              <span class="alCategoryName" style="width: 100%; padding: 0px; margin: 0px;">
                {{ $cate['name'] }}
              </span>

            </a>

            @if(!empty($cate['children']))
              <ul class="al_main_category_list">
                @foreach($cate['children'] as $childs)
                  <li>
                    <a href="{{ route('categoryDetail', $childs['slug']) }}">
                      <span class="new-tag">{{ $childs['name'] }}</span>
                    </a>
                    @if(!empty($childs['children']))
                      <ul class="al_main_category_sub_list">
                        @foreach($childs['children'] as $chld)
                          <li>
                            <a href="{{ route('categoryDetail', $chld['slug']) }}">{{ $chld['name'] }}</a>
                          </li>
                        @endforeach
                      </ul>
                    @endif
                  </li>
                @endforeach
              </ul>
            @endif

          </li>
        @endif
      @endforeach

    </ul>
  </div>
</div>

{{-- ── Force lazyload to fire on these icons ── --}}
<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof lazySizes !== 'undefined') {
      lazySizes.autoSizer.checkElems();
    }
    // Fallback: copy data-src → src if lazysizes hasn't set it yet
    document.querySelectorAll('.cat-grid img.lazyload[data-src]').forEach(function (img) {
      if (!img.getAttribute('src') || img.getAttribute('src') === '') {
        img.setAttribute('src', img.getAttribute('data-src'));
      }
    });
  });
</script>
      <!-- <div class="container-fluid space-slider-homeric" style="width: 100vw; background: white; margin: 0px; padding: 0;">
         <div class="row" style="width: 100vw; background: white; margin: 0px; padding: 0;">
               <ul id="main-menu" class="col sm pixelstrap sm-horizontal menu-slider2" style="width: 100vw; display: flex; justify-content: space-between;">
                  @foreach($navCategories as $cate)
                  @if($cate['name'])
                  <li class="al_main_category">
                     <a href="{{route('categoryDetail', $cate['slug'])}}" class="{{isset($category) && $category->slug == $cate['slug'] ? 'current_category' : ''}}">
                        @if($client_preference_detail->show_icons==1 && (\Request::route()->getName()=='userHome' || \Request::route()->getName()=='categoryDetail') || \Request::route()->getName()=='homeTest')
                        <div class="nav-cate-img {{ \Request::route()->getName()=='userHome' ? '' : 'activ_nav'}} " >
                           <img class="blur-up lazyload" data-icon_two="{{!is_null($cate['icon_two']) ? $cate['icon_two']['image_fit'].'200/200'.$cate['icon_two']['image_path'] : $cate['icon']['image_fit'].'200/200'.$cate['icon']['image_path']}}" data-icon="{{$cate['icon']['image_fit']}}200/200{{$cate['icon']['image_path']}}" data-src="{{$cate['icon']['image_fit']}}150/150{{$cate['icon']['image_path']}}" alt="" onmouseover='changeImage(this,1)' onmouseout='changeImage(this,0)'>
                        </div>
                        @endif
                        <span class="alCategoryName" style="font-size: 18px; font-weight: bold;">
                           {{$cate['name']}}
                        </span>
                     </a>
                     @if(!empty($cate['children']))
                     <ul class="al_main_category_list">
                        @foreach($cate['children'] as $childs)
                        <li>
                           <a href="{{route('categoryDetail', $childs['slug'])}}"><span class="new-tag">{{$childs['name']}}</span></a>
                           @if(!empty($childs['children']))
                           <ul class="al_main_category_sub_list">
                              @foreach($childs['children'] as $chld)
                              <li><a href="{{route('categoryDetail', $chld['slug'])}}">{{$chld['name']}}</a></li>
                              @endforeach
                           </ul>
                           @endif
                        </li>
                        @endforeach
                     </ul>
                     @endif
                  </li>
                  @endif
                  @endforeach
               </ul>
            </div>
         </div>
      </div> -->
   </section>
   <!-- <section class="alSixMainMenu p-0">
      <div class="menu-navigation_al">
      <div class="container space-slider-homeric">
         <div class="row">
            <div class="col-12">
               <ul id="main-menu" class="sm pixelstrap sm-horizontal menu-slider" >
                  @foreach($navCategories as $cate)
                  @if($cate['name'])
                  <li class="al_main_category">
                     <a href="{{route('categoryDetail', $cate['slug'])}}" class="{{isset($category) && $category->slug == $cate['slug'] ? 'current_category' : ''}}">
                        @if($client_preference_detail->show_icons==1 && (\Request::route()->getName()=='userHome' || \Request::route()->getName()=='categoryDetail') || \Request::route()->getName()=='homeTest')
                        <div class="nav-cate-img {{ \Request::route()->getName()=='userHome' ? '' : 'activ_nav'}} " >
                           <img class="blur-up lazyload" data-icon_two="{{!is_null($cate['icon_two']) ? $cate['icon_two']['image_fit'].'200/200'.$cate['icon_two']['image_path'] : $cate['icon']['image_fit'].'200/200'.$cate['icon']['image_path']}}" data-icon="{{$cate['icon']['image_fit']}}200/200{{$cate['icon']['image_path']}}" data-src="{{$cate['icon']['image_fit']}}150/150{{$cate['icon']['image_path']}}" alt="" onmouseover='changeImage(this,1)' onmouseout='changeImage(this,0)'>
                        </div>
                        @endif
                        <span class="alCategoryName">{{$cate['name']}}</span>
                     </a>
                     @if(!empty($cate['children']))
                     <ul class="al_main_category_list">
                        @foreach($cate['children'] as $childs)
                        <li>
                           <a href="{{route('categoryDetail', $childs['slug'])}}"><span class="new-tag">{{$childs['name']}}</span></a>
                           @if(!empty($childs['children']))
                           <ul class="al_main_category_sub_list">
                              @foreach($childs['children'] as $chld)
                              <li><a href="{{route('categoryDetail', $chld['slug'])}}">{{$chld['name']}}</a></li>
                              @endforeach
                           </ul>
                           @endif
                        </li>
                        @endforeach
                     </ul>
                     @endif
                  </li>
                  @endif
                  @endforeach
               </ul>
            </div>
         </div>
      </div>
   </section>  -->
@endif

<!-- ===============================
     NEW HOW IT WORKS SECTION
     =============================== -->
<section class="how-it-works-new-section">
  <div class="how-it-works-container">
    <div class="how-it-works-header">
      <h2>HOW IT WORKS</h2>
      <p>A simple and reliable process from discovery to service completion.</p>
    </div>
    
    <div class="how-it-works-grid">
      <!-- Card 1 -->
      <div class="hiw-card">
        <div class="hiw-icon">
          <img src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772534693/lens-svgrepo-com_1_bq1odp.png" alt="Search icon">
        </div>
        <h3>Search & Discover</h3>
        <p>Browse through a wide range of professional services and filter by category, rating, and location.</p>
      </div>

      <!-- Card 2 -->
      <div class="hiw-card">
        <div class="hiw-icon">
          <img src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772534488/lightning-bolt-black-shape-svgrepo-com_zhc4bb.png" alt="Book icon">
        </div>
        <h3>Book Instantly</h3>
        <p>Select your preferred date and time, then confirm your booking with transparent pricing.</p>
      </div>

      <!-- Card 3 -->
      <div class="hiw-card">
        <div class="hiw-icon">
          <img src="https://photos.app.goo.gl/3y7L1TdzkQ8jHPgL9" alt="Enjoy icon">
        </div>
        <h3>Enjoy & Review</h3>
        <p>Relax while verified professionals handle the work and share your rating after completion.</p>
      </div>
    </div>
    
    <!-- Big Card -->
    <div class="hiw-big-card">
      <div class="hiw-bc-left"></div>
      <div class="hiw-bc-right">
        <h3>See how on-demand service works in real life</h3>
        <p>From instant booking to doorstep delivery, our on-demand workflow keeps everything simple, transparent, and fast. Watch how professionals are assigned, tracked, and completed with quality checks at every step.</p>
      </div>
    </div>
  </div>
</section>

<style>
.how-it-works-new-section {
  width: 100%;
  background-color: #f8f9fa;
  padding: 60px 20px;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
}

.how-it-works-container {
  max-width: 1200px;
  margin: 0 auto;
}

.how-it-works-header {
  margin-bottom: 40px;
}

.how-it-works-header h2 {
  font-weight: 800;
  font-size: 32px;
  color: #111b2b;
  margin-bottom: 12px;
  text-transform: uppercase;
  letter-spacing: -0.5px;
}

.how-it-works-header p {
  color: #6c757d;
  font-size: 16px;
  margin: 0;
}

.how-it-works-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-bottom: 40px;
}

.hiw-card {
  background: #ffffff;
  border-radius: 12px;
  padding: 32px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.03);
  border: 1px solid #eaebf0;
}

.hiw-icon {
  width: 48px;
  height: 48px;
  background-color: #111b2b;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 24px;
}

.hiw-icon img {
  width: 24px;
  height: 24px;
  filter: brightness(0) invert(1);
}

.hiw-card h3 {
  font-size: 20px;
  font-weight: 700;
  color: #111b2b;
  margin-bottom: 12px;
}

.hiw-card p {
  font-size: 15px;
  color: #6c757d;
  line-height: 1.6;
  margin: 0;
}

/* Big Card */
.hiw-big-card {
  display: flex;
  background: #ffffff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 16px rgba(0,0,0,0.03);
  border: 1px solid #eaebf0;
  min-height: 300px;
}

.hiw-bc-left {
  flex: 0 0 50%;
  background-color: #111b2b;
}

.hiw-bc-right {
  flex: 0 0 50%;
  padding: 48px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.hiw-bc-right h3 {
  font-size: 28px;
  font-weight: 800;
  color: #111b2b;
  margin-bottom: 16px;
  line-height: 1.3;
}

.hiw-bc-right p {
  font-size: 16px;
  color: #6c757d;
  line-height: 1.6;
  margin: 0;
}

@media (max-width: 991px) {
  .how-it-works-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .hiw-big-card {
    flex-direction: column;
  }
  .hiw-bc-left {
    height: 250px;
  }
  .hiw-bc-right {
    padding: 32px;
  }
}

@media (max-width: 767px) {
  .how-it-works-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<!-- ===============================
     WHY CUSTOMERS CHOOSE US
     =============================== -->
<section class="why-choose-us-section">
  <div class="why-choose-us-container">
    <div class="wcu-left">
      <h2>WHY CUSTOMERS CHOOSE US</h2>
      <p class="wcu-subtitle">We combine trusted professionals, verified reviews, transparent pricing, and premium customer support.</p>
      
      <ul class="wcu-list">
        <li>
          <span class="wcu-check">✓</span>
          <span>Verified providers with quality checks</span>
        </li>
        <li>
          <span class="wcu-check">✓</span>
          <span>Real-time order and booking updates</span>
        </li>
        <li>
          <span class="wcu-check">✓</span>
          <span>Secure checkout and easy support</span>
        </li>
        <li>
          <span class="wcu-check">✓</span>
          <span>Fast reschedule and cancellation options</span>
        </li>
      </ul>
    </div>
    <div class="wcu-right">
      <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Customer Service">
    </div>
  </div>
</section>

<style>
.why-choose-us-section {
  width: 100%;
  background-color: #ffffff;
  padding: 80px 20px;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
}

.why-choose-us-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  gap: 60px;
}

.wcu-left {
  flex: 1;
}

.wcu-left h2 {
  font-weight: 800;
  font-size: 32px;
  color: #111b2b;
  margin-bottom: 16px;
  text-transform: uppercase;
  letter-spacing: -0.5px;
}

.wcu-subtitle {
  color: #6c757d;
  font-size: 16px;
  line-height: 1.6;
  margin-bottom: 32px;
}

.wcu-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.wcu-list li {
  display: flex;
  align-items: center;
  font-size: 15px;
  color: #6c757d;
  font-weight: 500;
}

.wcu-check {
  color: #10b981;
  font-weight: 800;
  margin-right: 12px;
  font-size: 16px;
}

.wcu-right {
  flex: 1;
}

.wcu-right img {
  width: 100%;
  height: auto;
  border-radius: 16px;
  box-shadow: 0 12px 32px rgba(0,0,0,0.08);
  object-fit: cover;
  display: block;
}

@media (max-width: 991px) {
  .why-choose-us-container {
    flex-direction: column;
    gap: 40px;
  }
  .wcu-left, .wcu-right {
    width: 100%;
  }
}
</style>

<!-- ===============================
     TOP PROVIDERS SECTION
     =============================== -->
<section class="top-providers-section">
  <div class="top-providers-container">
    <div class="tp-header">
      <h2>TOP PROVIDERS</h2>
      <p>Highly rated professionals delivering quality service at your doorstep.</p>
    </div>

    <div class="tp-grid" id="providersGrid">
      @php
        $dummyProviders = [
          [
            'image' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=600&q=80',
            'category' => 'Electrician',
            'title' => 'PowerPro Electric',
            'description' => 'Certified electricians handling wiring, repairs, and installations with safety and efficiency guaranteed.',
            'rating' => '4.5',
            'reviews' => '220'
          ],
          [
            'image' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=600&q=80',
            'category' => 'Deep Cleaning',
            'title' => 'CleanHub Pro',
            'description' => 'Deep home and apartment cleaning with trained professionals and eco-safe products for hygiene.',
            'rating' => '4.0',
            'reviews' => '184'
          ],
          [
            'image' => 'https://images.unsplash.com/photo-1605810230434-7631ac76ec81?auto=format&fit=crop&w=600&q=80',
            'category' => 'Technician',
            'title' => 'FixPro Technicians',
            'description' => 'Expert repair and maintenance services delivered by skilled professionals with precision and care.',
            'rating' => '4.8',
            'reviews' => '161'
          ],
          [
            'image' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=600&q=80',
            'category' => 'Pest Control',
            'title' => 'SafeGuard Pest Control',
            'description' => 'Effective pest removal solutions using safe methods to protect your home and ensure hygiene.',
            'rating' => '4.8',
            'reviews' => '195'
          ],
          [
            'image' => 'https://images.unsplash.com/photo-1585435421671-0c16764628ce?auto=format&fit=crop&w=600&q=80',
            'category' => 'Plumber',
            'title' => 'Prime Plumbers',
            'description' => 'Reliable plumbing services for leak repairs, pipe installations, and general maintenance.',
            'rating' => '4.6',
            'reviews' => '142'
          ],
          [
            'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=600&q=80',
            'category' => 'Painter',
            'title' => 'Pro Paint Services',
            'description' => 'Transform your space with high-quality interior and exterior painting services by experts.',
            'rating' => '4.9',
            'reviews' => '88'
          ]
        ];
        
        // If a real variable from the backend is provided, use it instead.
        $topProviders = isset($top_providers_data) && count($top_providers_data) > 0 ? $top_providers_data : $dummyProviders;
      @endphp

      @foreach($topProviders as $index => $provider)
      <div class="tp-card provider-item" {!! $index >= 3 ? 'style="display: none;"' : '' !!}>
        <div class="tp-img-wrapper">
          <img src="{{ $provider['image'] }}" alt="{{ $provider['title'] }}">
        </div>
        <div class="tp-content">
          <span class="tp-category">{{ $provider['category'] }}</span>
          <h3>{{ $provider['title'] }}</h3>
          <p>{{ $provider['description'] }}</p>
          <div class="tp-rating">
            <span class="stars">
              @for($i = 1; $i <= 5; $i++)
                @if($i <= (int)$provider['rating'])
                  ★
                @else
                  ☆
                @endif
              @endfor
            </span> 
            <span class="rating-text">{{ $provider['rating'] }} ({{ $provider['reviews'] }} reviews)</span>
          </div>
        </div>
      </div>
      @endforeach

    </div>

    <div class="tp-action">
      <button id="showMoreProvidersBtn" class="tp-btn">Show More <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg></button>
    </div>
  </div>
</section>

<style>
.top-providers-section {
  width: 100%;
  background-color: #f8f9fa;
  padding: 80px 20px;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
}

.top-providers-container {
  max-width: 1200px;
  margin: 0 auto;
}

.tp-header {
  margin-bottom: 40px;
}

.tp-header h2 {
  font-weight: 800;
  font-size: 32px;
  color: #111b2b;
  margin-bottom: 12px;
  text-transform: uppercase;
  letter-spacing: -0.5px;
}

.tp-header p {
  color: #6c757d;
  font-size: 16px;
  margin: 0;
}

.tp-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-bottom: 40px;
}

.tp-card {
  background: #ffffff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 16px rgba(0,0,0,0.04);
  border: 1px solid #eaebf0;
  display: flex;
  flex-direction: column;
}

.tp-img-wrapper {
  width: 100%;
  height: 220px;
  overflow: hidden;
}

.tp-img-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.tp-card:hover .tp-img-wrapper img {
  transform: scale(1.05);
}

.tp-content {
  padding: 24px;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.tp-category {
  font-size: 12px;
  color: #6c757d;
  text-transform: uppercase;
  font-weight: 700;
  margin-bottom: 8px;
  letter-spacing: 0.5px;
}

.tp-content h3 {
  font-size: 20px;
  font-weight: 800;
  color: #111b2b;
  margin-bottom: 12px;
}

.tp-content p {
  font-size: 14px;
  color: #6c757d;
  line-height: 1.6;
  margin: 0 0 20px 0;
  flex: 1;
}

.tp-rating {
  display: flex;
  align-items: center;
  font-size: 13px;
}

.tp-rating .stars {
  color: #f59e0b;
  margin-right: 8px;
  letter-spacing: 2px;
}

.tp-rating .rating-text {
  color: #6c757d;
  font-weight: 600;
}

.tp-action {
  text-align: center;
}

.tp-btn {
  background-color: #e5e7eb;
  color: #4b5563;
  font-size: 15px;
  font-weight: 600;
  padding: 12px 24px;
  border-radius: 24px;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: background-color 0.2s ease;
}

.tp-btn:hover {
  background-color: #d1d5db;
}

@media (max-width: 991px) {
  .tp-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 767px) {
  .tp-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const showMoreBtn = document.getElementById('showMoreProvidersBtn');
  const providerItems = document.querySelectorAll('.provider-item');
  
  if(showMoreBtn) {
    showMoreBtn.addEventListener('click', function() {
      providerItems.forEach(function(item) {
        item.style.display = 'flex';
      });
      showMoreBtn.style.display = 'none';
    });
  }
});
</script>

<!-- ===============================
     RESTAURANT REPAIR & INSTALLATION
     =============================== -->
<section class="rest-repair-section">
  <div class="rest-repair-container">
    <div class="rr-header">
      <h2>Restaurant repair & installation</h2>
      <!-- <a href="#" class="rr-see-all">See all</a> -->
    </div>

    <div class="rr-carousel-wrapper">
      <button class="rr-nav rr-prev" aria-label="Previous">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6" fill="none" stroke="currentColor" stroke-width="2"/></svg>
      </button>
      
      <div class="rr-track" id="rrTrack">
        <!-- Card 1 -->
        <div class="rr-card">
          <div class="rr-img">
            <img src="https://images.pexels.com/photos/2290753/pexels-photo-2290753.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Kitchen equipment repair">
          </div>
          <div class="rr-info">
            <h4>Kitchen equipment repair</h4>
            <p class="rr-meta"><span class="rr-star">★</span> 4.73 <span class="rr-dot">•</span> <span class="rr-instant">⚡ Instant</span></p>
            <p class="rr-price">₹149</p>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="rr-card">
          <div class="rr-img">
            <img src="https://images.pexels.com/photos/333850/pexels-photo-333850.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Exhaust fan repair">
          </div>
          <div class="rr-info">
            <h4>Exhaust fan repair</h4>
            <p class="rr-meta"><span class="rr-star">★</span> 4.74</p>
            <p class="rr-price">₹99</p>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="rr-card">
          <div class="rr-img">
            <img src="https://images.pexels.com/photos/262978/pexels-photo-262978.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Commercial plumbing">
          </div>
          <div class="rr-info">
            <h4>Commercial plumbing</h4>
            <p class="rr-meta"><span class="rr-star">★</span> 4.79</p>
            <p class="rr-price">₹199</p>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="rr-card">
          <div class="rr-img">
            <img src="https://images.pexels.com/photos/1036857/pexels-photo-1036857.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Switchboard repair & replacement">
          </div>
          <div class="rr-info">
            <h4>Switchboard repair & replacement</h4>
            <p class="rr-meta"><span class="rr-star">★</span> 4.83 <span class="rr-dot">•</span> <span class="rr-instant">⚡ Instant</span></p>
            <p class="rr-price">₹99</p>
          </div>
        </div>

        <!-- Card 5 -->
        <div class="rr-card">
          <div class="rr-img">
            <img src="https://images.pexels.com/photos/1402407/pexels-photo-1402407.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Cold storage repair">
          </div>
          <div class="rr-info">
            <h4>Cold storage repair</h4>
            <p class="rr-meta"><span class="rr-star">★</span> 4.76</p>
            <p class="rr-price">₹249</p>
          </div>
        </div>

        <!-- Card 6 -->
        <div class="rr-card">
          <div class="rr-img">
            <img src="https://images.pexels.com/photos/3773194/pexels-photo-3773194.jpeg?auto=compress&cs=tinysrgb&w=400" alt="Gas pipeline service">
          </div>
          <div class="rr-info">
            <h4>Gas pipeline service</h4>
            <p class="rr-meta"><span class="rr-star">★</span> 4.88 <span class="rr-dot">•</span> <span class="rr-instant">⚡ Instant</span></p>
            <p class="rr-price">₹199</p>
          </div>
        </div>

      </div>

      <button class="rr-nav rr-next" aria-label="Next">
        <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6" fill="none" stroke="currentColor" stroke-width="2"/></svg>
      </button>
    </div>
  </div>
</section>

<style>
.rest-repair-section {
  width: 100%;
  background-color: #ffffff;
  padding: 30px 20px;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
}

.rest-repair-container {
  max-width: 1200px;
  margin: 0 auto;
}

.rr-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.rr-header h2 {
  font-weight: 800;
  font-size: 24px;
  color: #111b2b;
  margin: 0;
  letter-spacing: -0.5px;
}

.rr-see-all {
  padding: 6px 14px;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  color: #663399;
  font-weight: 600;
  font-size: 13px;
  text-decoration: none;
  transition: all 0.2s ease;
}

.rr-see-all:hover {
  background-color: #f8f9fa;
  border-color: #cbd5e1;
  color: #4e2577;
}

.rr-carousel-wrapper {
  position: relative;
}

.rr-track {
  display: flex;
  gap: 16px;
  overflow-x: auto;
  scroll-behavior: smooth;
  padding: 4px 4px 12px 4px;
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.rr-track::-webkit-scrollbar {
  display: none;
}

.rr-card {
  flex: 0 0 calc(25% - 12px);
  min-width: 220px;
  display: flex;
  flex-direction: column;
  cursor: pointer;
  background: #ffffff;
  border: 1px solid #eaebf0;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0,0,0,0.03);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.rr-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0,0,0,0.08);
  border-color: #d4b3f0;
}

.rr-img {
  width: 100%;
  aspect-ratio: 4 / 3;
  overflow: hidden;
  background-color: #f4f5f7;
}

.rr-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.rr-card:hover .rr-img img {
  transform: scale(1.05);
}

.rr-info {
  padding: 16px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.rr-info h4 {
  font-size: 15px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 6px 0;
  line-height: 1.3;
}

.rr-meta {
  font-size: 13px;
  color: #6c757d;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 6px;
}

.rr-star {
  color: #f5a623;
}

.rr-instant {
  color: #02bd7a;
  font-weight: 600;
  background: #e6f9f2;
  padding: 2px 6px;
  border-radius: 4px;
  font-size: 11px;
}

.rr-price {
  font-size: 15px;
  font-weight: 700;
  color: #111b2b;
  margin: auto 0 0 0;
}

.rr-nav {
  position: absolute;
  top: 35%;
  transform: translateY(-50%);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 10;
  color: #111b2b;
  transition: all 0.2s ease;
}

.rr-nav:hover {
  background: #f8f9fa;
  box-shadow: 0 6px 16px rgba(0,0,0,0.12);
}

.rr-nav svg {
  width: 18px;
  height: 18px;
}

.rr-prev {
  left: -18px;
}

.rr-next {
  right: -18px;
}

@media (max-width: 991px) {
  .rr-card {
    flex: 0 0 calc(33.333% - 11px);
  }
}

@media (max-width: 767px) {
  .rr-card {
    flex: 0 0 calc(50% - 8px);
  }
  .rr-prev, .rr-next {
    display: none;
  }
  .rr-header h2 {
    font-size: 20px;
  }
}

@media (max-width: 480px) {
  .rr-card {
    flex: 0 0 calc(85% - 10px);
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const track = document.getElementById('rrTrack');
  const prevBtn = document.querySelector('.rr-prev');
  const nextBtn = document.querySelector('.rr-next');

  if(track && prevBtn && nextBtn) {
    const scrollAmount = 300; // Adjust based on card width + gap

    prevBtn.addEventListener('click', () => {
      track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    });

    nextBtn.addEventListener('click', () => {
      track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    });
  }
});
</script>

<!-- ===============================
     FULL WIDTH VIDEO SECTION
     =============================== -->
<div class="full-width-video-container">
  <video autoplay muted loop playsinline class="restocare-chef-video">
    <source src="https://videos.pexels.com/video-files/3205712/3205712-hd_1920_1080_25fps.mp4" type="video/mp4">
  </video>
  <div class="video-overlay">
    <h2>RestoCare</h2>
    <p>Premium Restaurant Services & Maintenance</p>
  </div>
</div>

<style>
.full-width-video-container {
  position: relative;
  width: 100%;
  height: 60vh;
  min-height: 400px;
  overflow: hidden;
  background-color: #000;
  margin-bottom: 0;
}

.restocare-chef-video {
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.6;
}

.video-overlay {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  color: #fff;
  z-index: 2;
  width: 100%;
  padding: 0 20px;
}

.video-overlay h2 {
  font-size: 48px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 16px;
  text-shadow: 0 4px 12px rgba(0,0,0,0.5);
  color: #ffffff;
}

.video-overlay p {
  font-size: 20px;
  font-weight: 500;
  text-shadow: 0 2px 8px rgba(0,0,0,0.5);
  color: #f8f9fa;
}

@media (max-width: 767px) {
  .full-width-video-container {
    height: 40vh;
  }
  .video-overlay h2 {
    font-size: 32px;
  }
  .video-overlay p {
    font-size: 16px;
  }
}
</style>

<!-- ===============================
     BECOME A SERVICE PARTNER BANNER
     =============================== -->
<section class="partner-banner-section">
  <div class="partner-banner-container">
    <div class="partner-content">
      <h2>BECOME A SERVICE PARTNER</h2>
      <p>Grow your business by listing your services and receiving quality bookings daily.</p>
    </div>
    <div class="partner-action">
      <a href="#" class="partner-btn">Get Started</a>
    </div>
  </div>
</section>

<!-- ===============================
     TESTIMONIALS SECTION
     =============================== -->
<section class="testimonials-section">
  <div class="testimonials-container">
    <div class="testimonials-header">
      <h2>WHAT OUR CUSTOMERS SAY</h2>
      <p>Real feedback from users who book services daily on our platform.</p>
    </div>
    
    <div class="testimonials-grid">
      <!-- Card 1 -->
      <div class="testimonial-card">
        <div class="testimonial-author">
          <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Author">
        </div>
        <h4>"Excellent Experience"</h4>
        <p>Booking was smooth and the professional arrived on time. Highly recommended for busy families.</p>
      </div>
      <!-- Card 2 -->
      <div class="testimonial-card">
        <div class="testimonial-author">
          <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Author">
        </div>
        <h4>"Very Convenient"</h4>
        <p>I found and booked an electrician in under five minutes. The app flow is fast and very clear.</p>
      </div>
      <!-- Card 3 -->
      <div class="testimonial-card">
        <div class="testimonial-author">
          <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Author">
        </div>
        <h4>"Best Service Quality"</h4>
        <p>The quality of work was top-notch and support team was quick to respond throughout the process.</p>
      </div>
      <!-- Card 4 -->
      <div class="testimonial-card">
        <div class="testimonial-author">
          <img src="https://randomuser.me/api/portraits/men/46.jpg" alt="Author">
        </div>
        <h4>"Will Book Again"</h4>
        <p>Transparent pricing, clean UI, and reliable providers. I already booked my second service.</p>
      </div>
    </div>
  </div>
</section>

<style>
/* Partner Banner */
.partner-banner-section {
  padding: 40px 20px;
  background-color: #ffffff;
  display: flex;
  justify-content: center;
}

.partner-banner-container {
  max-width: 1200px;
  width: 100%;
  background: linear-gradient(rgba(17, 27, 43, 0.85), rgba(17, 27, 43, 0.95)), url('https://images.unsplash.com/photo-1556761175-5973dc0f32d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
  border-radius: 12px;
  padding: 40px 50px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.partner-content h2 {
  color: #ffffff;
  font-size: 32px;
  font-weight: 800;
  margin-bottom: 8px;
  letter-spacing: -0.5px;
}

.partner-content p {
  color: #e2e8f0;
  font-size: 16px;
  margin-bottom: 0;
}

.partner-action .partner-btn {
  background-color: #ff4b68;
  color: #ffffff;
  font-weight: 700;
  font-size: 16px;
  padding: 14px 32px;
  border-radius: 8px;
  text-decoration: none;
  transition: all 0.3s ease;
  display: inline-block;
  white-space: nowrap;
}

.partner-action .partner-btn:hover {
  background-color: #e63953;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(255, 75, 104, 0.3);
  color: #ffffff;
}

/* Testimonials */
.testimonials-section {
  padding: 60px 20px 20px;
  background-color: #ffffff;
}

.testimonials-container {
  max-width: 1200px;
  margin: 0 auto;
}

.testimonials-header {
  margin-bottom: 40px;
}

.testimonials-header h2 {
  font-weight: 800;
  font-size: 32px;
  color: #111b2b;
  margin-bottom: 12px;
  text-transform: uppercase;
  letter-spacing: -0.5px;
}

.testimonials-header p {
  color: #64748b;
  font-size: 16px;
}

.testimonials-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}

.testimonial-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.testimonial-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06);
}

.testimonial-author img {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 16px;
  border: 2px solid #f8f9fa;
}

.testimonial-card h4 {
  font-size: 18px;
  font-weight: 700;
  color: #111b2b;
  margin-bottom: 12px;
}

.testimonial-card p {
  font-size: 14px;
  color: #64748b;
  line-height: 1.6;
  margin-bottom: 0;
}

@media (max-width: 991px) {
  .partner-banner-container {
    flex-direction: column;
    text-align: center;
    padding: 30px;
  }
  
  .partner-content {
    margin-bottom: 24px;
  }
  
  .testimonials-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 575px) {
  .testimonials-grid {
    grid-template-columns: 1fr;
  }
  
  .partner-content h2 {
    font-size: 24px;
  }
}
</style>










<!-- no-store-wrapper start -->
<section class="no-store-wrapper mb-3 mt-5 pt-5" style="display: none;">
   <div class="container">
      @if(count($for_no_product_found_html))
      @foreach($for_no_product_found_html as $key => $homePageLabel)
      @include('frontend.included_files.dynamic_page')
      @endforeach
      @else
      <div class="row">
         <div class="col-12 text-center">
            <img class="no-store-image mt-2 mb-2 blur-up lazyload" data-src="{{getImageUrl(asset('images/no-stores.svg'),'250/250')}}" style="max-height: 250px;">
         </div>
      </div>
      <div class="row">
         <div class="col-12 text-center mt-2">
            <h4>{{__('We are currently not operating in your location.')}}</h4>
         </div>
      </div>
      @endif
   </div>
</section>

<!-- our_vendor_main_div start -->
<section class="section-b-space ratio_asos pt-0 mt-0 pb-0 {{isset($client_preference_detail) && $client_preference_detail->business_type == 'taxi' ? 'taxi' : ''}}" id="our_vendor_main_div" >
   <div class="vendors">
   @foreach ($enable_layout as $enabled)
      <div class="container mb-3 shimmer_effect  shimmer_effect_{{ $enabled  }}">
         <div class="row">
            <div class="col-12">
               <div class="card_title ml-4 mb-4 maintitle loading"></div>
            </div>
         </div>
         <div class="row">
            <div class="col-12 px-md-5 px-2">
               <div class="grid-row grid-4-4">
                  <div class="cards">
                     <div class="card_image loading"></div>
                     <div class="d-flex align-items-center justify-content-between">
                           <div class="card_title loading"></div>
                           <div class="card_icon loading"></div>
                     </div>
                     <div class="card_content loading mt-0 w-75"></div>
                     <div class="card_content loading mt-0 w-50"></div>
                     <div class="card_line loading"></div>
                     <div class="card_price loading"></div>
                  </div>
                  <div class="cards">
                     <div class="card_image loading"></div>
                     <div class="d-flex align-items-center justify-content-between">
                           <div class="card_title loading"></div>
                           <div class="card_icon loading"></div>
                     </div>
                     <div class="card_content loading mt-0 w-75"></div>
                     <div class="card_content loading mt-0 w-50"></div>
                     <div class="card_line loading"></div>
                     <div class="card_price loading"></div>
                  </div>
                  <div class="cards">
                     <div class="card_image loading"></div>
                     <div class="d-flex align-items-center justify-content-between">
                           <div class="card_title loading"></div>
                           <div class="card_icon loading"></div>
                     </div>
                     <div class="card_content loading mt-0 w-75"></div>
                     <div class="card_content loading mt-0 w-50"></div>
                     <div class="card_line loading"></div>
                     <div class="card_price loading"></div>
                  </div>
                  <div class="cards">
                     <div class="card_image loading"></div>
                     <div class="d-flex align-items-center justify-content-between">
                           <div class="card_title loading"></div>
                           <div class="card_icon loading"></div>
                     </div>
                     <div class="card_content loading mt-0 w-75"></div>
                     <div class="card_content loading mt-0 w-50"></div>
                     <div class="card_line loading"></div>
                     <div class="card_price loading"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   @endforeach
   @foreach($homePageLabels as $key => $homePageLabel)
      @if($homePageLabel->slug == 'featured_products')
         @continue
      @endif
      @if($homePageLabel->slug == 'pickup_delivery') @if(isset($homePageLabel->pickupCategories) && count($homePageLabel->pickupCategories)) @include('frontend.booking.cabbooking-single-module') @endif
      @elseif($homePageLabel->slug == 'dynamic_page') @include('frontend.included_files.dynamic_page')
      @elseif($homePageLabel->slug == 'brands')
         <section class="container-fluid popular-brands  left-shape_ position-relative "  >
            <div class="al_top_heading d-flex justify-content-between">
               <h2 class="h2-heading text-capitalize">{{(!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : getNomenclatureName('brands', true)}}</h2>
               {{-- <a class="" href="">See All</a> --}}
            </div>
            <div class="row">
               <div class=" col-12 al_custom_brand p-0">
                  <div class=" brand-slider render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
                     @foreach ($homePageData[$homePageLabel->slug] as $key => $brand )
                        <div>
                           <a class="brand-box d-block black-box" href="{{ $brand->redirect_url }}">
                              <div class="brand-ing">
                                 <img class="blur-up lazyload" data-src="{{ get_file_path($brand->image,'FILL_URL','260','260') }}" alt="" title="">
                              </div>
                              <h6>{{ $brand->translation_title }}</h6>
                           </a>
                        </div>
                     @endforeach
                  </div>
               </div>
            </div>
         </section>
      @elseif($homePageLabel->slug == 'vendors' && (count($homePageData['vendors']) != 0))
         <section class="suppliers-section container-fluid">
            <div class=" top-heading  d-flex justify-content-between align-self-center">
               <h2 class="h2-heading mt-4">{{(!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : getNomenclatureName('vendors', true)}}</h2>
               <a  href="{{route('vendor.all')}}">{{__("See all")}}</a>
            </div>
            <div class="col-12 p-0">
               <div class="suppliers-slider-{{$homePageLabel->slug}} product-m render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
                  @foreach ($homePageData[$homePageLabel->slug] as $key => $vendor )
                  @include('frontend.home_page_6.vendor')
                  @endforeach
               </div>
            </div>
         </section>
      @elseif($homePageLabel->slug == 'trending_vendors' && (count($homePageData['trending_vendors']) != 0))
         <section class="suppliers-section container" id="homepage_trending_vendors_div">
            <div class=" top-heading ">
               <h2 class="h2-heading">{{$homePageLabel->slug=='trending_vendors' ? __('Trending')." ".getNomenclatureName('vendors', true) : __($homePageLabel->title)}}</h2>
            </div>
            <div class="row">
               <div class="col-12 p-0">
                  <div class="suppliers-slider-{{$homePageLabel->slug}} product-m render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
                     @foreach ($homePageData[$homePageLabel->slug] as $key => $vendor )
                     @include('frontend.home_page_6.vendor')
                     @endforeach
                  </div>
               </div>
            </div>
         </section>
      @elseif($homePageLabel->slug == 'best_sellers' && (count($homePageData['best_sellers']) != 0))
         <section class="suppliers-section container-fluid">
            <div class=" top-heading d-flex justify-content-between align-self-center">
               <h2 class="h2-heading mt-4">{{(!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : getNomenclatureName('Best sellers', true)}}</h2>
            </div>
            <div class="col-12 p-0">
               <div class="suppliers-slider-{{$homePageLabel->slug}} product-m render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
                  @foreach ($homePageData[$homePageLabel->slug] as $key => $vendor )
                  @include('frontend.home_page_6.vendor')
                  @endforeach
               </div>
            </div>
         </section>
      @elseif($homePageLabel->slug == 'recent_orders' && count($homePageData['recent_orders']) != 0)
         <section class="container-fluid mb-0 render_full_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}"  >
            <div class="top-heading test d-flex justify-content-between">
               <h2 class="h2-heading"> @php echo (!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : __("Your Recent Orders"); @endphp </h2>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="recent-orders product-m  render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
							@foreach ($homePageData[$homePageLabel->slug] as $order )
							@include('frontend.common_section.recent_order')
							@endforeach
                  </div>
               </div>
            </div>
         </section>
      @elseif($homePageLabel->slug == 'cities' && count($homePageData[$homePageLabel->slug]) != 0  )
         <section class="container render_full_{{$homePageLabel->slug}}">
            <div class=" top-heading d-flex justify-content-between align-self-center">
               <h2 class="h2-heading">{{(!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : 'Cities'}}</h2>
            </div>
            <div class="col-12 p-0">
               <div class="suppliers-slider-{{$homePageLabel->slug}} product-m render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
                  <div class="alSpaListSlider">
                  @foreach ($homePageData[$homePageLabel->slug] as $cities )
                        <div>
                           <div class="alSpaListBox">
                              <div class="alSpaCityBox">
                                 <a href="javascript:void(0);" class="cities updateLocationByCity" data-lat="{{$cities['latitude']}}" data-long="{{$cities['longitude']}}" data-place_id="{{$cities['place_id']}}" data-address="{{$cities['address']}}"><img class="w-100" src="{{$cities['image']['image_fit']}}260/260{{$cities['image']['image_path']}}"></a>
                              </div>
                              <p>{{$cities["title"]}} </p>
                           </div>
                        </div>
                        @endforeach
                     </div>
               </div>
            </div>
         </section>
      @elseif($homePageLabel->slug == 'banner' && (count($homePageData['banners']) != 0))
			@if(!empty(@$homePageData['banners'][$homePageLabel->translations->first()->cab_booking_layout_id]))
				<section class="container mb-0 render_full_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}"  >
					<div class="top-heading d-flex justify-content-between">
						<h2 class="h2-heading"> @php
							echo (!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : __($homePageLabel->title);
						@endphp </h2>
					</div>
					<div class="custom_banner">
						<div class="container">
							<div class="text-center">
								@php
								    $url = $homePageData['banners'][$homePageLabel->translations->first()->cab_booking_layout_id]; // replace with your URL
									$extension = pathinfo($url, PATHINFO_EXTENSION);
									$image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']; // list of image extensions
									$video_extensions = ['mp4', 'avi', 'mov', 'wmv']; // list of video extensions
								@endphp
								@if(in_array($extension, $image_extensions))
									<img alt="" title="" class="blur-up lazyload w-100" src="{{$homePageData['banners'][$homePageLabel->translations->first()->cab_booking_layout_id]}}" >
								@elseif (in_array($extension, $video_extensions))
									<video id="video1" width="100%" controls autoplay muted>
										<source src="{{$homePageData['banners'][$homePageLabel->translations->first()->cab_booking_layout_id]}}" type="video/mp4">
									</video>
								@else
								@endif
							</div>
						</div>
					</div>
				</section>
			@endif
      @else
         @if(@$homePageData[$homePageLabel->slug] && count($homePageData[$homePageLabel->slug]) != 0)
         <section class="container-fluid mb-0 render_full_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}"  >
            <div class="top-heading  d-flex justify-content-between">
               <h2 class="h2-heading mt-4"> @php echo (!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : __($homePageLabel->title);@endphp </h2>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="product-4-{{$homePageLabel->slug}} product-m  render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
                     @foreach ($homePageData[$homePageLabel->slug] as $product )
                     @include('frontend.home_page_6.product')
                     @endforeach
                  </div>
               </div>
            </div>
         </section>
         @endif
      @endif
   @endforeach
   </div>
</section>
<!-- our_vendor_main_div end -->


 <!-- Our Process -->
@include('frontend.prakash.image')







<!-- Broken video container removed to avoid huge black gap -->




















<!-- <style>


  .testimonial-section {
    width: 100%;
    background: #eae9e6;
    padding: 20px 0 80px;
    position: relative;
    /* margin-bottom: 30px; */
    /* overflow: hidden; */
  }
  .testimonial-section::before {
    content: '';
    position: absolute;
    width: 420px; height: 420px; border-radius: 50%;
    background: rgba(127,29,29,0.05);
    top: -160px; right: -100px; pointer-events: none;
  }
  .testimonial-section::after {
    content: '';
    position: absolute;
    width: 280px; height: 280px; border-radius: 50%;
    background: rgba(127,29,29,0.05);
    bottom: -90px; left: -70px; pointer-events: none;
  }

  /* Header */
  .section-header { text-align: center; margin-bottom: 10px; padding: 0 20px; }

  .t-subtitle {
    display: inline-flex; align-items: center; gap: 12px;
     font-weight: 700;
    text-transform: uppercase; letter-spacing: 4px; margin-bottom: 12px;
    text-align: center;
      font-weight: 600;
      font-size: 24px;
      line-height: 1.3;
      color: black;
  }
  .t-subtitle::before, .t-subtitle::after {
    content: ''; display: block; width: 36px; height: 1.5px; opacity: 0.55;
  }
  .t-title { color: #616060;
      text-align: center;
      max-width: 80%;
      font-size: 15px;
      font-weight: 400;
      margin-top: 6px;
      line-height: 1.5; }

  /* Viewport */
  .slider-viewport {
    /* position: relative; overflow: hidden; */
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 7%, black 93%, transparent 100%);
    mask-image: linear-gradient(to right, transparent 0%, black 7%, black 93%, transparent 100%);
  }

  /* Track — CSS transition handles the smooth slide */
  .slider-track {
    display: flex;
    gap: 24px;
    will-change: transform;
    align-items: stretch;
    /* padding: 44px 12px 12px; */
    /* transition set by JS so we can toggle instant vs animated */
  }

  /* Card */
  .t-card {
    background: #fff;
    border-radius: 5px;
    padding: 40px 26px 28px;
    position: relative;
    box-shadow: 0 2px 14px rgba(0,0,0,0.06), 0 8px 32px rgba(0,0,0,0.05);
    transition: transform 0.32s ease, box-shadow 0.32s ease;
    display: flex; flex-direction: column; text-align: left;
    flex-shrink: 0; cursor: default; user-select: none;
  }
  .t-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 6px 28px rgba(127,29,29,0.12), 0 18px 50px rgba(0,0,0,0.09);
  }

  .quote-badge {
    width: 50px; height: 50px;
    background: linear-gradient(145deg, #9b1c1c, #7f1d1d);
    border-radius: 12px; position: absolute; top: -25px; left: 22px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 28px; font-family: Georgia, serif; line-height: 1;
    box-shadow: 0 6px 20px rgba(127,29,29,0.42); user-select: none;
  }

  .stars { display: flex; gap: 3px; margin-bottom: 14px; }
  .stars svg { width: 15px; height: 15px; fill: #f59e0b; }

  .t-text { color: #555; line-height: 1.8; font-size: 14px; font-style: italic; flex-grow: 1; }

  .t-divider {
    width: 34px; height: 2px; background: #7f1d1d;
    opacity: 0.45; margin: 22px 0; border-radius: 2px;
  }

  .t-user { display: flex; align-items: center; gap: 13px; }

  .avatar {
    width: 46px; height: 46px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 15px; color: #fff;
    border: 2.5px solid rgba(255,255,255,0.6); transition: box-shadow 0.3s;
  }
  .t-card:hover .avatar { box-shadow: 0 0 0 3px rgba(127,29,29,0.25); }

  .av-red    { background: linear-gradient(135deg,#991b1b,#dc2626); }
  .av-blue   { background: linear-gradient(135deg,#1d4ed8,#60a5fa); }
  .av-green  { background: linear-gradient(135deg,#065f46,#10b981); }
  .av-purple { background: linear-gradient(135deg,#5b21b6,#a78bfa); }
  .av-orange { background: linear-gradient(135deg,#92400e,#f97316); }

  .u-name { font-size: 15px; font-weight: 700; color: #1a1a1a; }
  .u-role { font-size: 12.5px; color: #9ca3af; margin-top: 2px; }

  /* Progress bar under each dot */
  .slider-controls {
    display: flex; align-items: center; justify-content: center;
    gap: 14px; margin-top: 30px;
  }

  .nav-btn {
    width: 42px; height: 42px; background: #fff; border: none;
    border-radius: 50%; cursor: pointer; display: flex;
    align-items: center; justify-content: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: background 0.25s, transform 0.2s, color 0.25s;
    color: #7f1d1d; flex-shrink: 0;
  }
  .nav-btn:hover { background: #7f1d1d; color: #fff; transform: scale(1.08); }
  .nav-btn svg {
    width: 16px; height: 16px; stroke: currentColor; fill: none;
    stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
  }

  /* Dots with progress ring */
  .dots { display: flex; align-items: center; gap: 8px; }

  .dot-wrap { position: relative; width: 28px; height: 28px; cursor: pointer; }

  .dot-wrap svg {
    width: 28px; height: 28px;
    transform: rotate(-90deg);
    position: absolute; top: 0; left: 0;
  }

  .dot-wrap svg .ring-bg {
    fill: none; stroke: #ddd; stroke-width: 2.5;
  }
  .dot-wrap svg .ring-fill {
    fill: none; stroke: #7f1d1d; stroke-width: 2.5;
    stroke-linecap: round;
    stroke-dasharray: 69.1; /* circumference of r=11 circle: 2π×11 */
    stroke-dashoffset: 69.1;
    transition: stroke-dashoffset linear; /* duration set by JS */
  }

  .dot-inner {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 8px; height: 8px; border-radius: 50%;
    background: #c2bfb8;
    transition: background 0.3s, transform 0.3s;
  }
  .dot-wrap.active .dot-inner {
    background: #7f1d1d;
    transform: translate(-50%, -50%) scale(1.2);
  }

  @media (max-width: 640px) { .t-title { font-size: 24px; } }


  .testimonial-container{
    /* prakash-mani */
  }

</style> -->
<!-- <section class="testimonial-section">
  <div style="max-width:1200px; margin:0 auto;" class="testimonial-container">
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

  </div> -->
<!-- </section>

<script>
(function () {

  /* ── Data ── */
  const CARDS = [
    { initials:'PM', av:'av-red',    name:'Prakash Mani',  role:'Web Developer',     text:'"RestoCare transformed our restaurant\'s online presence. Their expertise in digital marketing helped us reach a wider audience and boost our reservations significantly."' },
    { initials:'RS', av:'av-blue',   name:'Rohit Sharma',  role:'Restaurant Owner',  text:'"Amazing service and great support team. Highly recommended for businesses looking to grow digitally. The results exceeded all our expectations from day one."' },
    { initials:'AV', av:'av-green',  name:'Anjali Verma',  role:'Business Manager',  text:'"Professional team with outstanding marketing strategies. Our sales increased within weeks of signing up. Their dedication and creativity are truly unmatched."' },
    { initials:'NG', av:'av-purple', name:'Neha Gupta',    role:'Cafe Owner',        text:'"Working with this team has been a game changer for our cafe. The social media campaigns they crafted brought us consistent new customers every single week."' },
    { initials:'SK', av:'av-orange', name:'Suresh Kumar',  role:'Hotel Manager',     text:'"Exceptional results every time. The team understood our brand vision perfectly and delivered a campaign that tripled our online bookings in just one month."' },
  ];

  const STAR = `<svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`;
  const CIRC = 2 * Math.PI * 11;   // ring circumference

  const WAIT_MS  = 2000;   // pause at each card
  const SLIDE_MS = 600;    // slide animation duration

  /* ── DOM refs ── */
  const viewport   = document.getElementById('sliderViewport');
  const track      = document.getElementById('sliderTrack');
  const btnPrev    = document.getElementById('btnPrev');
  const btnNext    = document.getElementById('btnNext');
  const dotsWrap   = document.getElementById('dotsContainer');

  const GAP        = 24;
  let perView      = 3;
  let cardWidth    = 0;
  let N            = CARDS.length;
  let currentIdx   = 0;   // which original card is first-visible
  let isAnimating  = false;
  let autoTimer    = null;
  let ringAnimRAF  = null;

  /* ── Build DOM ──
     Track layout: [N clones at end] + [N originals] + [N clones at start]
     We always show originals; clones let us teleport silently.
  */
  function cardHTML(d) {
    return `<div class="t-card">
      <div class="quote-badge">&#8220;</div>
      <div class="stars">${STAR.repeat(5)}</div>
      <p class="t-text">${d.text}</p>
      
      <div class="t-user">
        <div class="avatar ${d.av}">${d.initials}</div>
        <div><div class="u-name">${d.name}</div><div class="u-role">${d.role}</div></div>
      </div>
    </div>`;
  }

  function buildTrack() {
    track.innerHTML = '';
    // back clones
    CARDS.forEach(d => track.insertAdjacentHTML('beforeend', cardHTML(d)));
    // originals
    CARDS.forEach(d => track.insertAdjacentHTML('beforeend', cardHTML(d)));
    // front clones
    CARDS.forEach(d => track.insertAdjacentHTML('beforeend', cardHTML(d)));
  }

  /* ── Layout ── */
  function getPerView() {
    return window.innerWidth < 640 ? 1 : window.innerWidth < 1024 ? 2 : 3;
  }

  function calcCardWidth() {
    return (viewport.clientWidth - GAP * (perView - 1) - 24) / perView;
  }

  function posFor(globalIdx) {
    return globalIdx * (cardWidth + GAP);
  }

  /* global index for original[i] */
  function origPos(i) { return posFor(N + i); }

  function applyPos(x, instant) {
    track.style.transition = instant ? 'none' : `transform ${SLIDE_MS}ms cubic-bezier(0.4,0,0.2,1)`;
    track.style.transform  = `translateX(-${x}px)`;
  }

  /* ── Dots ── */
  function buildDots() {
    dotsWrap.innerHTML = '';
    for (let i = 0; i < N; i++) {
      const wrap = document.createElement('div');
      wrap.className = 'dot-wrap' + (i === 0 ? ' active' : '');
      wrap.innerHTML = `
        <svg viewBox="0 0 28 28">
          <circle class="ring-bg"   cx="14" cy="14" r="11"/>
          <circle class="ring-fill" cx="14" cy="14" r="11"/>
        </svg>
        <div class="dot-inner"></div>`;
      wrap.addEventListener('click', () => { if (!isAnimating) jumpTo(i); });
      dotsWrap.appendChild(wrap);
    }
  }

  function setActiveDot(i) {
    Array.from(dotsWrap.children).forEach((w, j) => {
      w.classList.toggle('active', j === i);
      const ring = w.querySelector('.ring-fill');
      ring.style.transition = 'none';
      ring.style.strokeDashoffset = CIRC; // reset
    });
    // start progress ring on active dot
    const activeRing = dotsWrap.children[i].querySelector('.ring-fill');
    // force reflow so transition fires
    void activeRing.getBoundingClientRect();
    activeRing.style.transition = `stroke-dashoffset ${WAIT_MS}ms linear`;
    activeRing.style.strokeDashoffset = '0';
  }

  /* ── Slide to a target original index with infinite wrap ── */
  function slideTo(newIdx, skipAnim) {
    if (isAnimating && !skipAnim) return;
    isAnimating = true;
    clearTimeout(autoTimer);
    cancelAnimationFrame(ringAnimRAF);

    // wrap newIdx into 0…N-1
    newIdx = ((newIdx % N) + N) % N;

    const target = origPos(newIdx);
    applyPos(target, !!skipAnim);

    const done = () => {
      currentIdx  = newIdx;
      isAnimating = false;
      setActiveDot(currentIdx);
      scheduleNext();
    };

    if (skipAnim) {
      setTimeout(done, 20);
    } else {
      setTimeout(done, SLIDE_MS);
    }
  }

  /* Called by user — figure out shortest path accounting for clones */
  function jumpTo(newIdx) {
    clearTimeout(autoTimer);
    isAnimating = false;

    // If direction matters, just go forward or backward by diff
    const diff = newIdx - currentIdx;

    if (diff === 0) return;

    // Slide directly
    isAnimating = true;
    const target = origPos(newIdx);
    applyPos(target, false);

    setTimeout(() => {
      currentIdx  = newIdx;
      isAnimating = false;
      setActiveDot(currentIdx);
      scheduleNext();
    }, SLIDE_MS);
  }

  function scheduleNext() {
    clearTimeout(autoTimer);
    autoTimer = setTimeout(() => {
      goNext();
    }, WAIT_MS);
  }

  function goNext() {
    if (isAnimating) return;
    const next = (currentIdx + 1) % N;

    // If wrapping around (N-1 → 0), we use the front-clone trick:
    // currently showing original[N-1], slide to clone[0] (which is at global index N+N = 2N)
    // then silently jump to original[0]
    if (next === 0) {
      isAnimating = true;
      clearTimeout(autoTimer);

      // slide to the "front clone" of card 0 (global index = N + N = 2N)
      const clonePos = posFor(2 * N);
      applyPos(clonePos, false);

      setTimeout(() => {
        // silently teleport to original[0]
        applyPos(origPos(0), true);
        void track.getBoundingClientRect(); // force reflow
        currentIdx  = 0;
        isAnimating = false;
        setActiveDot(0);
        scheduleNext();
      }, SLIDE_MS + 20);
    } else {
      slideTo(next, false);
    }
  }

  function goPrev() {
    if (isAnimating) return;
    const prev = (currentIdx - 1 + N) % N;

    if (prev === N - 1 && currentIdx === 0) {
      // wrap backward: slide to back clone of last card (global index = N-1)
      isAnimating = true;
      clearTimeout(autoTimer);

      const clonePos = posFor(N - 1);
      applyPos(clonePos, false);

      setTimeout(() => {
        applyPos(origPos(N - 1), true);
        void track.getBoundingClientRect();
        currentIdx  = N - 1;
        isAnimating = false;
        setActiveDot(N - 1);
        scheduleNext();
      }, SLIDE_MS + 20);
    } else {
      clearTimeout(autoTimer);
      isAnimating = false;
      slideTo(prev, false);
    }
  }

  /* ── Buttons ── */
  btnNext.addEventListener('click', () => { goNext(); });
  btnPrev.addEventListener('click', () => { goPrev(); });

  /* ── Hover pause ── */
  viewport.addEventListener('mouseenter', () => {
    clearTimeout(autoTimer);
    // freeze the ring at current position
    const activeRing = dotsWrap.children[currentIdx]?.querySelector('.ring-fill');
    if (activeRing) {
      const computed = getComputedStyle(activeRing).strokeDashoffset;
      activeRing.style.transition = 'none';
      activeRing.style.strokeDashoffset = computed;
    }
  });
  viewport.addEventListener('mouseleave', () => {
    if (!isAnimating) scheduleNext();
  });

  /* ── Drag / swipe ── */
  let dragStartX = 0, isDragging = false;

  viewport.addEventListener('pointerdown', e => {
    if (isAnimating) return;
    isDragging  = true;
    dragStartX  = e.clientX;
    clearTimeout(autoTimer);
    viewport.setPointerCapture(e.pointerId);
  });

  viewport.addEventListener('pointerup', e => {
    if (!isDragging) return;
    isDragging = false;
    const dx = e.clientX - dragStartX;
    if (Math.abs(dx) > 50) {
      dx < 0 ? goNext() : goPrev();
    } else {
      scheduleNext();
    }
  });

  /* ── Init ── */
  function init() {
    clearTimeout(autoTimer);
    isAnimating = false;
    perView     = getPerView();
    buildTrack();
    cardWidth   = calcCardWidth();

    Array.from(track.children).forEach(c => {
      c.style.width    = cardWidth + 'px';
      c.style.minWidth = cardWidth + 'px';
    });

    buildDots();
    currentIdx = 0;
    applyPos(origPos(0), true);
    setTimeout(() => {
      setActiveDot(0);
      scheduleNext();
    }, 50);
  }

  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(init, 150);
  });

  init();
})();
</script> -->

@include('frontend.aman-testimonial')
{{-- @include('frontend.aman-testimonial') --}}



<!-- age-restriction star -->
<div class="modal age-restriction fade" id="age_restriction" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body text-center">
            <img style="height: 150px;" class="blur-up lazyload" data-src="{{getImageUrl(asset('assets/images/age-img.svg'),'150/150')}}" alt="" title="">
            <p class="mb-0 mt-3">{{$client_preference_detail ? $client_preference_detail->age_restriction_title : __('Are you 18 or older?')}}</p>
            <p class="mb-0">{{__('Are you sure you want to continue?')}}</p>
         </div>
         <div class="modal-footer d-block">
            <div class="row no-gutters">
               <div class="col-6 pr-1">
                  <button type="button" class="btn btn-solid w-100 age_restriction_yes" data-dismiss="modal">{{__('Yes')}}</button>
               </div>
               <div class="col-6 pl-1">
                  <button type="button" class="btn btn-solid w-100 age_restriction_no" data-dismiss="modal">{{__('No')}}</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- age-restriction end -->

<!-- no-store-wrapper end -->























































<script type="text/template" id="desktop_banners_template">
	<div class="home-banner-track" data-banner-track>
	   <% _.each(banners, function(banner, k){%>
		  <%
		  var url='#';
		  if(banner.link == 'category'){
			 if(banner.category != null){
				url = "{{route('categoryDetail')}}" + "/" + banner.category.slug;
			 }
		  }
          else if(banner.link == 'vendor'){
			 if(banner.vendor != null){
				url = "{{route('vendorDetail')}}" + "/" + banner.vendor.slug;
			 }
		  }
		  else if(banner.link == 'url'){
			 if(banner.link_url != null){
				url = banner.link_url;
			 }
		  }
		  %>
		  <div class="home-banner-slide">
			 <a class="banner-img-outer" href="<%= url %>">
				<link rel="preload" as="image" href="<%= banner.image.proxy_url %>1920/500<%= banner.image.image_path %>" />
				<img alt="" title="" class="blur-up lazyload banner-img" src="<%= banner.image.proxy_url %>1920/500<%= banner.image.image_path %>" data-src="<%= banner.image.proxy_url %>1920/500<%= banner.image.image_path %>">
			 </a>
		  </div>
	   <% }); %>
	</div>
	<% if (banners.length > 1) { %>
	<button type="button" class="home-banner-nav prev" data-banner-prev aria-label="{{__('Previous')}}">
		<span aria-hidden="true">&#8249;</span>
	</button>
	<button type="button" class="home-banner-nav next" data-banner-next aria-label="{{__('Next')}}">
		<span aria-hidden="true">&#8250;</span>
	</button>
	<div class="home-banner-dots" data-banner-dots></div>
	<% } %>
</script>

<script type="text/template" id="mobile_banners_template">
	<div class="home-banner-track" data-banner-track>
	   <% _.each(banners, function(banner, k){%>
		  <%
		  var url='#';
		  if(banner.link == 'category'){
			 if(banner.category != null){
				url = "{{route('categoryDetail')}}" + "/" + banner.category.slug;
			 }
		  }
          else if(banner.link == 'vendor'){
			 if(banner.vendor != null){
				url = "{{route('vendorDetail')}}" + "/" + banner.vendor.slug;
			 }
		  }
		  else if(banner.link == 'url'){
			 if(banner.link_url != null){
				url = banner.link_url;
			 }
		  }
		  %>
		  <div class="home-banner-slide">
			 <a class="banner-img-outer" href="<%= url %>">
				<link rel="preload" as="image" href="<%= banner.image.proxy_url %>400/150<%= banner.image.image_path %>" />
				<img alt="" title="" class="blur-up lazyload banner-img" src="<%= banner.image.proxy_url %>400/150<%= banner.image.image_path %>" data-src="<%= banner.image.proxy_url %>400/150<%= banner.image.image_path %>">
			 </a>
		  </div>
	   <% }); %>
	</div>
	<% if (banners.length > 1) { %>
	<button type="button" class="home-banner-nav prev" data-banner-prev aria-label="{{__('Previous')}}">
		<span aria-hidden="true">&#8249;</span>
	</button>
	<button type="button" class="home-banner-nav next" data-banner-next aria-label="{{__('Next')}}">
		<span aria-hidden="true">&#8250;</span>
	</button>
	<div class="home-banner-dots" data-banner-dots></div>
	<% } %>
</script>






















































<!-- vendors_template start -->
<script type="text/template" id="vendors_template" >
   <% _.each(vendors, function(vendor, k){%>
   	<div class="product-card-box position-relative text-center al_custom_vendors_sec"  >
   		<a class="suppliers-box d-block" href="{{route('vendorDetail')}}/<%=vendor.slug %>">
   			<div class="suppliers-img-outer position-relative ">
   				<% if(vendor.is_vendor_closed==1){%>
   					<img class="fluid-img mx-auto blur-up lazyload grayscale-image" data-src="<%=vendor.logo.image_fit %>200/200<%=vendor.logo['image_path'] %>" alt="" title="">
   				<%}else{%>
   					<img  class="fluid-img mx-auto blur-up lazyload" data-src="<%=vendor.logo.image_fit %>200/200<%=vendor.logo['image_path'] %>" alt="" title="">
   				<%}%>
   			</div>
   			<div class="supplier-rating">
   				<h6 class="mb-1 ellips"><%=vendor.name %></h6>
   				{{--<p title="<%=vendor.categoriesList %>" class="vendor-cate mb-1 ellips d-none">
   					<%=vendor.categoriesList %>
   				</p>--}}
   					<% if(vendor.timeofLineOfSightDistance !=undefined){%>
   						<div class="pref-timing"> <span><%=vendor.timeofLineOfSightDistance %></span> </div>
   					<%}%>
   			</div>
   			@if($client_preference_detail) @if($client_preference_detail->rating_check==1)
   			<% if(vendor.vendorRating > 0){%> <span class="rating-number"><i class="fa fa-star"></i> <%=vendor.vendorRating %> </span>
   			<%}%> @endif @endif
   		</a>
   	</div>
   	<% }); %>
</script><!-- vendors_template end -->

<!-- banner_template start -->
<script type="text/template" id="banner_template" >
   <% _.each(brands, function(brand, k){%>
   	<div>
   		<a class="brand-box d-block black-box" href="<%=brand.redirect_url %>">
   			<div class="brand-ing">
                      <img class="blur-up lazyload" data-src="<%=brand.image.image_fit %>260/260<%=brand.image.image_path %>" alt="" title="">
                  </div>
   			<h6><%=brand.translation_title %></h6>
              </a>
   	</div>
   	<% }); %>
</script><!-- banner_template end -->
<!-- products_template start -->
<script type="text/template" id="products_template" >
   <% _.each(products, function(product, k){ %>
   	<div class="product-card-box position-relative al_box_third_template al"  >
   		{{--<div class="add-to-fav 12">
   			<input id="fav_pro_one" type="checkbox">
   			<label for="fav_pro_one"><i class="fa fa-heart-o fav-heart" aria-hidden="true"></i></label>
   		</div>--}}
   		<a class="common-product-box text-center" href="<%=product.vendor.slug %>/product/<%=product.url_slug %>">
   			<div class="img-outer-box position-relative">
                      <img class="blur-up lazyload" data-src="<%=product.image_url %>" alt="" title="">
   				<div class="pref-timing"> </div>
   			</div>
   			<div class="media-body align-self-start">
   				<div class="inner_spacing px-0">
   					<div class="product-description">
   						<div class="d-flex align-items-center justify-content-between">
   							<h6 class="card_title ellips"><%=product.title %></h6> @if($client_preference_detail) @if($client_preference_detail->rating_check==1)
   							<% if(product.averageRating > 0){%> <span class="rating-number"><i class="fa fa-star"></i> <%=product.averageRating %></span>
   								<%}%> @endif @endif
                              </div>
   						<div class="product-description_list border-bottom">
   							<p>
   								<%=product.vendor_name %>
   							</p>
   							<p class="al_product_category">
   								<span>
   							    {{__('In')}}
   								<%=product.category %></span>
   							</p>
   						</div>
   						<div class="d-flex align-items-center justify-content-between al_clock pt-2">
   							<b><% if(product.inquiry_only==0){%> <%=product.price %> <%}%></b>
   						</div>
   					</div>
   				</div>
   			</div>
   		</a>
   	</div>
   	<% }); %>
</script><!-- products_template end -->
<!-- trending_vendors_template start -->
<script type="text/template" id="trending_vendors_template" >
   <% _.each(trending_vendors, function(vendor, k){%>
   	<div class="product-card-box position-relative text-center al_custom_vendors_sec"  >
   		<a class="suppliers-box al_vendors_template2 d-block" href="{{route('vendorDetail')}}/<%=vendor.slug %>">
   			<div class="suppliers-img-outer position-relative ">
   				<% if(vendor.is_vendor_closed==1){%> <img class="fluid-img mx-auto blur-up lazyload grayscale-image" data-src="<%=vendor.logo.image_fit %>200/200<%=vendor.logo['image_path'] %>" alt="" title="">
   					<%}else{%> <img class="fluid-img mx-auto blur-up lazyload w-100" data-src="<%=vendor.logo.image_fit %>200/200<%=vendor.logo['image_path'] %>" alt="" title="">
   						<%}%>
   			</div>
   			<div class="supplier-rating">
   				<h6 class="mb-1 ellips"><%=vendor.name %></h6>
   				<p title="<%=vendor.categoriesList %>" class="vendor-cate mb-1 ellips d-none">
   					<%=vendor.categoriesList %>
   				</p>

   					<% if(vendor.timeofLineOfSightDistance !=undefined){%>
   								<div class="pref-timing"> <span><%=vendor.timeofLineOfSightDistance %></span> </div>
   								<%}%>
   			</div>
   			@if($client_preference_detail) @if($client_preference_detail->rating_check==1)
   					<% if(vendor.vendorRating > 0){%>
   					<span class="rating-number"><i class="fa fa-star"></i> <%=vendor.vendorRating %> </span>
   					<%}%> @endif @endif
   		</a>
   	</div>
   	<% }); %>
</script><!-- trending_vendors_template end -->
<!-- recent_orders_template start -->
<script type="text/template" id="recent_orders_template"  >
   <% _.each(recent_orders, function(order, k){ %>
   	<% subtotal_order_price = total_order_price = total_tax_order_price = 0; %>
   		<% _.each(order.vendors, function(vendor, k){ %>
   			<%   product_total_count = product_subtotal_amount = product_taxable_amount = 0; %>
            @include('frontend.common_section.recent_order_j')
   				<% }); %>
   					<% }); %>
</script><!-- recent_orders_template end -->
<!-- cities start -->
<script type="text/template" id="cities_template" >
   <% _.each(cities, function(city, k){%>
      <div class="alSpaListSlider">
         <div>
            <div class="alSpaListBox">
               <div class="alSpaCityBox">
                  <a href="/cities/<%=city.slug %>"><img class="w-100" src="<%=city.image.image_fit %>260/260<%=city.image.image_path %>"></a>
               </div>
               <p><%=city.title %></p>
            </div>
         </div>
      </div>
   	<% });
   %>
</script><!-- cities cities end -->
<!-- footer code in layouts.store/footercontent-template-two -->
 <script>
  const iframe = document.getElementById("videoFrame");
  const container = document.getElementById("videoContainer");

  const originalSrc = iframe.getAttribute("src");

  if (!originalSrc || originalSrc.trim() === "") {
    container.style.display = "none";
  }
</script>

@include('frontend.prakash.faq-section')

@endsection
@section('home-page')
 {{-- <script type="text/javascript" src="{{asset('front-assets/js/homepage-six.js')}}"></script> --}}
 <script type="text/javascript" src="{{asset('assets/js/template/commonFunction.js')}}"></script>
 <script type="text/javascript" src="{{asset('assets/js/template/template-six/templateFunction.js')}}"></script>
<script>
(() => {
  const mobileBreakpoint = window.matchMedia('(max-width: 767px)');
  let refreshQueued = false;

  function ensureBannerImageSources(carousel) {
    carousel.querySelectorAll('img[data-src]').forEach(image => {
      if (!image.getAttribute('src')) {
        image.setAttribute('src', image.getAttribute('data-src'));
      }
    });

    if (window.lazySizes && window.lazySizes.autoSizer) {
      window.lazySizes.autoSizer.checkElems();
    }
  }

  function syncBannerVisibility() {
    document.querySelectorAll('.home-slider-wrapper .home-slider-banner-shell').forEach(shell => {
      const desktopCarousel = shell.querySelector('.home-banner-carousel.is-desktop[data-banner-carousel]');
      const mobileCarousel = shell.querySelector('.home-banner-carousel.is-mobile[data-banner-carousel]');

      if (!desktopCarousel || !mobileCarousel) {
        return;
      }

      const mobileHasSlides = mobileCarousel.querySelectorAll('.home-banner-slide').length > 0;
      const shouldShowMobile = mobileBreakpoint.matches && mobileHasSlides;

      desktopCarousel.classList.toggle('is-hidden-force', shouldShowMobile);
      mobileCarousel.classList.toggle('is-hidden-force', !shouldShowMobile);
    });
  }

  function queueBannerRefresh() {
    if (refreshQueued) {
      return;
    }

    refreshQueued = true;
    window.requestAnimationFrame(() => {
      refreshQueued = false;
      window.initHomeTemplateSixBanners();
    });
  }

  function attachBannerObservers() {
    document.querySelectorAll('.home-slider-wrapper [data-banner-carousel]').forEach(carousel => {
      if (carousel._bannerObserver) {
        return;
      }

      const observer = new MutationObserver(queueBannerRefresh);
      observer.observe(carousel, { childList: true });
      carousel._bannerObserver = observer;
    });
  }

  function initBannerCarousel(carousel) {
    if (!carousel) {
      return;
    }

    if (typeof carousel._bannerCleanup === 'function') {
      carousel._bannerCleanup();
    }

    const track = carousel.querySelector('[data-banner-track]');
    const slides = track ? Array.from(track.children) : [];
    const prevBtn = carousel.querySelector('[data-banner-prev]');
    const nextBtn = carousel.querySelector('[data-banner-next]');
    const dotsWrap = carousel.querySelector('[data-banner-dots]');
    const listeners = [];
    let current = 0;
    let autoplayTimer = null;
    let touchStartX = 0;

    if (!track || !slides.length) {
      syncBannerVisibility();
      return;
    }

    ensureBannerImageSources(carousel);

    function listen(target, eventName, handler, options) {
      target.addEventListener(eventName, handler, options);
      listeners.push(() => target.removeEventListener(eventName, handler, options));
    }

    function stopAutoplay() {
      if (autoplayTimer) {
        window.clearInterval(autoplayTimer);
        autoplayTimer = null;
      }
    }

    function update() {
      track.style.transform = `translateX(-${current * 100}%)`;
      slides.forEach((slide, index) => {
        slide.setAttribute('aria-hidden', index === current ? 'false' : 'true');
      });

      if (dotsWrap) {
        Array.from(dotsWrap.children).forEach((dot, index) => {
          dot.classList.toggle('is-active', index === current);
          dot.setAttribute('aria-current', index === current ? 'true' : 'false');
        });
      }
    }

    function goTo(index) {
      current = (index + slides.length) % slides.length;
      update();
    }

    function startAutoplay() {
      if (slides.length <= 1) {
        return;
      }

      stopAutoplay();
      autoplayTimer = window.setInterval(() => {
        goTo(current + 1);
      }, 4500);
    }

    function restartAutoplay() {
      stopAutoplay();
      startAutoplay();
    }

    if (dotsWrap) {
      dotsWrap.innerHTML = '';
      slides.forEach((_, index) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = `home-banner-dot${index === 0 ? ' is-active' : ''}`;
        dot.setAttribute('aria-label', `Slide ${index + 1}`);
        listen(dot, 'click', () => {
          goTo(index);
          restartAutoplay();
        });
        dotsWrap.appendChild(dot);
      });
    }

    if (slides.length <= 1) {
      if (prevBtn) {
        prevBtn.hidden = true;
      }
      if (nextBtn) {
        nextBtn.hidden = true;
      }
      if (dotsWrap) {
        dotsWrap.hidden = true;
      }
    } else {
      if (prevBtn) {
        prevBtn.hidden = false;
        listen(prevBtn, 'click', () => {
          goTo(current - 1);
          restartAutoplay();
        });
      }

      if (nextBtn) {
        nextBtn.hidden = false;
        listen(nextBtn, 'click', () => {
          goTo(current + 1);
          restartAutoplay();
        });
      }

      if (dotsWrap) {
        dotsWrap.hidden = false;
      }

      listen(track, 'touchstart', event => {
        touchStartX = event.changedTouches[0].screenX;
      }, { passive: true });

      listen(track, 'touchend', event => {
        const diff = touchStartX - event.changedTouches[0].screenX;

        if (Math.abs(diff) > 40) {
          goTo(diff > 0 ? current + 1 : current - 1);
          restartAutoplay();
        }
      }, { passive: true });

      listen(carousel, 'mouseenter', stopAutoplay);
      listen(carousel, 'mouseleave', startAutoplay);
      startAutoplay();
    }

    update();
    syncBannerVisibility();

    carousel._bannerCleanup = () => {
      stopAutoplay();
      listeners.forEach(removeListener => removeListener());
      delete carousel._bannerCleanup;
    };
  }

  window.initHomeTemplateSixBanners = function initHomeTemplateSixBanners() {
    document.querySelectorAll('.home-slider-wrapper [data-banner-carousel]').forEach(initBannerCarousel);
    syncBannerVisibility();
    attachBannerObservers();
  };

  window.addEventListener('resize', queueBannerRefresh);
  if (typeof mobileBreakpoint.addEventListener === 'function') {
    mobileBreakpoint.addEventListener('change', queueBannerRefresh);
  } else if (typeof mobileBreakpoint.addListener === 'function') {
    mobileBreakpoint.addListener(queueBannerRefresh);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', window.initHomeTemplateSixBanners, { once: true });
  } else {
    window.initHomeTemplateSixBanners();
  }
})();
</script>

<script>

!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1150346783738431');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1150346783738431&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
@endsection

@section('script')
<script type="text/javascript">
    @if(count($banners))
    $(document).ready(function() {
        $("body").addClass("homeHeader");
    });
    @endif

</script>
@endsection
