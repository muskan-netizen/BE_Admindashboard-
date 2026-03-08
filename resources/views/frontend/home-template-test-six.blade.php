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
.menu-slider .slick-slide{margin:0 10px;}
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





<section class="home-slider-wrapper">

	<div class="container-fulid">
		<div id="myCarousel" class="carousel slide al_desktop_banner" data-ride="carousel">
			<div class="carousel-inner">
				@foreach($banners as $key => $banner)
					@php $url=''; if($banner->link=='category'){if(!empty($banner->category_slug)){$url=route('categoryDetail', $banner->category_slug);}}else if($banner->link=='vendor'){if(!empty($banner->vendor_slug)){$url=route('vendorDetail', $banner->vendor_slug);}}else if($banner->link=='url'){if($banner->link_url !=null){$url=$banner->link_url;}}@endphp
					<div class="carousel-item @if($key == 0) active @endif">
					 <a class="banner-img-outer" href="{{$url??'#'}}" target="_blank">
                        <link rel="preload" as="image" href="{{ get_file_path($banner->image,'IMG_URL1','1920','500') }}" />
						<img alt="" title="" class="lazyload w-100" data-src="{{ get_file_path($banner->image,'IMG_URL1','1920','500') }}">
					</a>
					</div>
				@endforeach

			</div>
			<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">{{__('Previous')}}</span>
			</a>
			<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">{{__('Next')}}</span>
			</a>
		</div>

		<div id="myMobileCarousel" class="carousel slide al_mobile_banner mb-2" data-ride="carousel" style="display:none;">
			<div class="carousel-inner">
				@foreach($mobile_banners as $key => $banner)
					@php $url=''; if($banner->link=='category'){if(!empty($banner->category_slug)){$url=route('categoryDetail', $banner->category_slug);}}else if($banner->link=='vendor'){if(!empty($banner->vendor_slug)){$url=route('vendorDetail', $banner->vendor_slug);}}@endphp
					<div class="carousel-item @if($key == 0) active @endif">
					 <a class="banner-img-outer" style = "" href="{{$url??'#'}}">
                        <link rel="preload" as="image" href="{{ get_file_path($banner->image,'IMG_URL1','400','150') }}" />
						<img alt="" title="" class=" lazyload w-100" data-src="{{ get_file_path($banner->image,'IMG_URL1','400','150') }}">
					</a>
          <a class="banner-img-outer" href="{{$url??'#'}}">
    <link rel="preload" as="image" href="{{ get_file_path($banner->image,'IMG_URL1','400','150') }}" />
    <img 
        alt="" 
        title="" 
        class="lazyload w-100 banner-img"
        data-src="{{ get_file_path($banner->image,'IMG_URL1','400','150') }}"
    >
</a>
					</div>
				@endforeach

			</div>
			<a class="carousel-control-prev" href="#myMobileCarousel" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">{{__('Previous')}}</span>
			</a>
			<a class="carousel-control-next" href="#myMobileCarousel" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">{{__('Next')}}</span>
			</a>
   </div>

	</div>
</section>


@else
<section class="home-slider-wrapper bg-primary" style="min-height: 150px">
   <div class="container-fulid">
		<div id="myCarousel" class="carousel slide al_desktop_banner" data-ride="carousel"></div>
      <div id="myMobileCarousel" class="carousel slide al_mobile_banner mb-2" data-ride="carousel" style="display:none;"></div>
   </div>
</section>
@endif
@if(count($navCategories))

<!-- Category section start -->
 <section class="alSixMainMenu my-menu" style="width: 100%; background: white; margin: 0; padding: 0;">
      <div class="menu-navigation_al" style="width: 100%; background: white; margin: 0px; padding: 0;">
         <!-- testing -->

<style>
  .banner-img-outer {
    display: block;
    width: 100%;
    height: 150px; /* Set your desired height */
    overflow: hidden;
}

.banner-img {
    width: 100%;
    height: 100%;
    object-fit: contain; /* Makes image fully fill container */
}

</style>



<style>
  #banner-img-border{
    border-radius: 0px;
  }
  /* ── Outer section ── */
  .space-slider-homeric {
    width: 100%;
    background: #fff;
    margin: 0;
    border-radius: 0;
    /* padding: 12px 8px; */
  }

  /* ── Inner row ── */
  .space-slider-homeric .cat-row {
    width: 100%;
    margin: 0;
    padding: 0;
    border-radius: 0;
  }

  /* ── Grid — 8 columns desktop ── */
  .cat-grid {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 8px 4px;
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
  }

  /* ── Each item ── */
  .cat-grid .al_main_category {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    padding: 6px 2px;
    margin: 0;
  }

  /* ── Anchor ── */
  .cat-grid .al_main_category > a {
    display: flex;
    flex-direction: column;
    align-items: center;
    /* gap: 6px; */
    text-decoration: none;
    color: #222;
    width: 100%;
  }

  /* ── White circle ── */
  .cat-grid .al_main_category .nav-cate-img {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin: 0 auto;
    transition: transform 0.2s ease;
    flex-shrink: 0;
  }

  .cat-grid .al_main_category > a:hover .nav-cate-img {
    transform: translateY(-2px);
  }

  /* ── Icon image ── */
  .cat-grid .al_main_category .nav-cate-img img,
  .cat-grid .al_main_category .nav-cate-img img.blur-up,
  .cat-grid .al_main_category .nav-cate-img img.lazyload {
    width: 100% !important;
    height: 100% !important;
    max-width: 100% !important;
    max-height: 100% !important;
    object-fit: contain;
    display: block;
    border-radius: 0;
    padding: 10px;
    margin: 0;
  }

  /* ── Category label ── */
  .cat-grid .al_main_category .alCategoryName {
    font-size: 11px !important;
    font-weight: 600;
    color: #333;
    line-height: 1.3;
    width: 100%;
    max-width: 100%;
    display: block;
    text-align: center;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    padding: 0 2px;
  }

  /* ── Active category ── */
  .cat-grid .al_main_category > a.current_category .nav-cate-img {
    outline: 2px solid #4a90d9;
    outline-offset: 2px;
  }

  .cat-grid .al_main_category > a.current_category .alCategoryName {
    color: #4a90d9;
  }

  /* ── Hide dropdowns ── */
  .cat-grid .al_main_category_list,
  .cat-grid .al_main_category_sub_list {
    display: none !important;
    visibility: hidden;
    pointer-events: none;
  }

  /* ══════════════════════════════════════
     RESPONSIVE BREAKPOINTS
  ══════════════════════════════════════ */

  /* Large desktop — 8 columns */
  @media (min-width: 1200px) {
    .cat-grid {
      grid-template-columns: repeat(8, 1fr);
      gap: 10px 6px;
    }

    .cat-grid .al_main_category .nav-cate-img {
      width: 80px;
      height: 80px;
    }

    .cat-grid .al_main_category .alCategoryName {
      font-size: 12px !important;
    }
  }

  /* Standard desktop — 8 columns */
  @media (min-width: 992px) and (max-width: 1199px) {
    .cat-grid {
      grid-template-columns: repeat(8, 1fr);
      gap: 8px 4px;
    }

    .cat-grid .al_main_category .nav-cate-img {
      width: 68px;
      height: 68px;
    }

    .cat-grid .al_main_category .alCategoryName {
      font-size: 11px !important;
    }
  }

  /* Tablet landscape — 4 columns */
  @media (min-width: 768px) and (max-width: 991px) {
    .cat-grid {
      grid-template-columns: repeat(4, 1fr);
      gap: 16px 10px;
    }

    .cat-grid .al_main_category .nav-cate-img {
      width: 64px;
      height: 64px;
    }

    .cat-grid .al_main_category .alCategoryName {
      font-size: 11px !important;
    }
  }

  /* Tablet portrait — 4 columns */
  @media (min-width: 600px) and (max-width: 767px) {
    .cat-grid {
      grid-template-columns: repeat(4, 1fr);
      gap: 14px 8px;
    }

    .cat-grid .al_main_category .nav-cate-img {
      width: 58px;
      height: 58px;
    }

    .cat-grid .al_main_category .alCategoryName {
      font-size: 11px !important;
    }
  }

  /* Mobile large — 4 columns */
  @media (min-width: 420px) and (max-width: 599px) {
    .space-slider-homeric {
      padding: 10px 8px;
    }

    .cat-grid {
      grid-template-columns: repeat(4, 1fr);
      gap: 12px 6px;
    }

    .cat-grid .al_main_category .nav-cate-img {
      width: 52px;
      height: 52px;
    }

    .cat-grid .al_main_category .alCategoryName {
      font-size: 10px !important;
      max-width: 72px;
    }
  }

  /* Mobile standard — 4 columns */
  @media (min-width: 340px) and (max-width: 419px) {
    .space-slider-homeric {
      /* padding: 8px 6px; */
    }

    .cat-grid {
      grid-template-columns: repeat(4, 1fr);
      /* gap: 10px 4px; */
    }

    .cat-grid .al_main_category .nav-cate-img {
      width: 46px;
      height: 46px;
    }

    .cat-grid .al_main_category .alCategoryName {
      font-size: 9px !important;
      max-width: 62px;
    }
  }

  /* Very small phones — 4 columns */
  @media (max-width: 339px) {
    .space-slider-homeric {
      /* padding: 6px 4px; */
    }

    .cat-grid {
      grid-template-columns: repeat(4, 1fr);
      /* gap: 8px 2px; */
    }

    .cat-grid .al_main_category .nav-cate-img {
      width: 40px;
      height: 40px;
    }

    .cat-grid .al_main_category .alCategoryName {
      font-size: 8px !important;
      max-width: 52px;
    }
  }

  /* prakash */
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
.space-slider-homeric{
  margin-bottom: 10px;
}
.al_main_category {
  max-width: 100%;
  box-sizing: border-box;
}
.space-slider-homeric,
.cat-row,
.cat-grid {
  width: 100%;
  max-width: 100%;
  overflow-x: hidden;
  box-sizing: border-box;
}

</style>

{{-- ── BLADE TEMPLATE — no Bootstrap classes ── --}}
<div class="space-slider-homeric" style = "padding: 10px 10px;" >
  <div class="cat-row" >
    <ul class="cat-grid" >

      @foreach($navCategories as $cate)
        @if($cate['name'])
          <li class="al_main_category" style = "padding: 0px; margin: 0px; overflow: hidden;">

            <a href="{{ route('categoryDetail', $cate['slug']) }}"
               class="{{ isset($category) && $category->slug == $cate['slug'] ? 'current_category' : '' }}">

              @if($client_preference_detail->show_icons == 1 && (\Request::route()->getName() == 'userHome' || \Request::route()->getName() == 'categoryDetail') || \Request::route()->getName() == 'homeTest')
                <div class="nav-cate-img {{ \Request::route()->getName() == 'userHome' ? '' : 'activ_nav' }}">
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
                    style="padding: 0px; margin: 0px; background-color: white"
                  >
                  <!-- <img
                    class="blur-up lazyload"
                    src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772695084/png_222_to76ze.png"
                    data-src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772695084/png_222_to76ze.png"
                    data-icon="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772695084/png_222_to76ze.png"
                    data-icon_two="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772695084/png_222_to76ze.png"
                    alt="{{ $cate['name'] }}" 
                    width="78"
                    height="78"
                    onmouseover="changeImage(this,1)"
                    onmouseout="changeImage(this,0)"
                    style="padding: 0px; margin: 0px;"
                  > -->
                </div>
              @endif

              <span class="alCategoryName" style= "width: 100%; padding: 0px; margin: 0px;">
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

<div id="videoContainerX9a72" class="pb-2">
  <section id="videoSectionX9a72">
    <video
      id="videoPlayerX9a72"
      autoplay
      muted
      loop
      playsinline>
      <source src="https://res.cloudinary.com/dpqnudpkj/video/upload/v1772285315/IMG_5947_1_s0qnml.mp4" type="video/mp4">
    </video>
  </section>
</div>


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


<div id="videoContainerX9a72" class="pb-2">
  <section id="videoSectionX9a72">
    <video
      id="videoPlayerX9a72"
      autoplay
      muted
      loop
      playsinline>
      <source src="https://res.cloudinary.com/dpqnudpkj/video/upload/v1772284042/chef_2_2_zlak1y.mp4" type="video/mp4">
    </video>
  </section>
</div>

<section class="how-it-works how-it-works-padding-class" id= "how-it-works-section-id">

    <!-- Header -->
    <div class="how-it-works__header">
      <h2 class="how-it-works__heading">How It Works</h2>
      <h3 class="how-it-works__subheading">
        A simple three-step process to get you started quickly and easily
      </h3>
    </div>

    <!-- Cards -->
    <article class="how-it-works__cards">

      <div class="how-it-works__card">
        <div class="how-it-works__icon">
          <img src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772534693/lens-svgrepo-com_1_bq1odp.png" alt="Search icon">
        </div>
        <div class="how-it-works__text">
          <h3 class="how-it-works__card-title">Search &amp; Discover</h3>
          <p class="how-it-works__card-info">
            Browse through our wide range of services and find exactly what you need with our powerful search tools.
          </p>
        </div>
      </div>

      <div class="how-it-works__card">
        <div class="how-it-works__icon">
          <img src="https://res.cloudinary.com/ddqdhpdq0/image/upload/v1772534488/lightning-bolt-black-shape-svgrepo-com_zhc4bb.png" alt="Book icon" style = "z-index: 10;">
        </div>
        <div class="how-it-works__text">
          <h3 class="how-it-works__card-title">Book Instantly</h3>
          <p class="how-it-works__card-info">
            Choose your preferred time slot and confirm your booking in just a few clicks with zero hassle.
          </p>
        </div>
      </div>

      <div class="how-it-works__card">
        <div class="how-it-works__icon">
          <img src="https://img.icons8.com/ios-filled/50/ffffff/star.png" alt="Enjoy icon">
        </div>
        <div class="how-it-works__text">
          <h3 class="how-it-works__card-title">Enjoy the Service</h3>
          <p class="how-it-works__card-info">
            Sit back and relax while our verified professionals take care of everything for you seamlessly.
          </p>
        </div>
      </div>

    </article>

  </section>



<style>
 
  #how-it-works-section-id{
    padding-bottom: 10px;
  }

    /* ── Section ── */
    .how-it-works {
      background-color: #F5F6F6;
      color: #000000;
      padding: 12px 16px;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      gap: 20px;
    }
    

    /* ── Header block ── */
    .how-it-works__header {
      /* padding-bottom: 20px; */
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .how-it-works__heading {
      text-align: center;
      font-weight: 600;
      font-size: 18px;
      line-height: 1.3;
    }

    .how-it-works__subheading {
      color: #616060;
      text-align: center;
      max-width: 80%;
      font-size: 15px;
      font-weight: 400;
      margin-top: 6px;
      line-height: 1.5;
    }

    /* ── Cards wrapper ── */
    .how-it-works__cards {
      width: 100%;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 40px;
      padding-top: 20px;
    }

    /* ── Single card ── */
    .how-it-works__card {
      position: relative;
      width: 87%;
      background: #ffffff;
      border-radius: 6px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.08);
      padding: 24px 24px 0px 24px;
      transition: box-shadow 0.2s ease;
    }

    .how-it-works__card:hover {
      box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    /* ── Icon block ── */
    .how-it-works__icon {
      position: absolute;
      top: -24px;
      left: 24px;
      width: 56px;
      height: 56px;
      border-radius: 4px;
      background-color: #000000;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.25);
    }

    .how-it-works__icon img {
      width: 28px;
      height: 28px;
      object-fit: contain;
      display: block;
      /* invert so dark icons show on black bg */
      /* filter: invert(1) brightness(2); */
    }

    /* ── Text block ── */
    .how-it-works__text {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
      margin-top: 16px;
    }

    .how-it-works__card-title {
      font-size: 18px;
      font-weight: 600;
      color: #000;
    }

    .how-it-works__card-info {
      font-size: 15px;
      color: #4b5563;
      line-height: 1.65;
    }
    .how-it-works{
      padding-bottom: 50px;
    }

    /* ══════════════════════
       RESPONSIVE
    ══════════════════════ */

    /* Small tablets and up — 2 columns */
    @media (min-width: 540px) {
      .how-it-works__heading {
        font-size: 20px;
      }

      .how-it-works__card {
        width: 45%;
      }
       .how-it-works{
      padding-bottom: 50px;
    }
    }

    /* Desktop — 3 columns */
    @media (min-width: 1024px) {
      .how-it-works__heading {
        font-size: 24px;
        
      }
       .how-it-works{
      padding-bottom: 50px;
    }

      .how-it-works__card {
        width: 30%;
      }
    }
    .how-it-works{
      /* padding-bottom: 200px; */
    }
    .how-it-works-padding-class{
      /* padding-bottom: 200px; */
    }
      @media (max-width: 768px) {
  .how-it-works-padding-class {
    padding: 16px; /* Adjust value as needed */
    /* margin-bottom: 30px;  */
  }
} 
 .how-it-works__icon {
  /* background: transparent; */
}
  </style>



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
	<div class="carousel-inner">
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
		  %>
		  <div class="carousel-item <% if(k == 0) { %> active <% } %>">
			 <a class="banner-img-outer" href="<%= url %>">
				<link rel="preload" as="image" href="<%= banner.image.proxy_url %>sw/300<%= banner.image.image_path %>" />
				<img alt="" title="" class="blur-up lazyload w-100" data-src="<%= banner.image.proxy_url %>1370/300<%= banner.image.image_path %>">
			 </a>
		  </div>
	   <% }); %>
	</div>
	<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">{{__('Previous')}}</span>
	</a>
	<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">{{__('Next')}}</span>
	</a>
</script>

<script type="text/template" id="mobile_banners_template">
	<div class="carousel-inner">
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
		  %>
		  <div class="carousel-item <% if(k == 0) { %> active <% } %>">
			 <a class="banner-img-outer" href="<%= url %>">
				<link rel="preload" as="image" href="<%= banner.image.proxy_url %>400/150<%= banner.image.image_path %>" />
				<img alt="" title="" class="blur-up lazyload w-100" data-src="<%= banner.image.proxy_url %>400/150<%= banner.image.image_path %>">
			 </a>
		  </div>
	   <% }); %>
	</div>
	<a class="carousel-control-prev" href="#myMobileCarousel" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">{{__('Previous')}}</span>
	</a>
	<a class="carousel-control-next" href="#myMobileCarousel" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">{{__('Next')}}</span>
	</a>
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
function initSlider({ trackId, dotsId, wrapper }) {

  if (!wrapper) return;

  const track  = document.getElementById(trackId);
  const dotsEl = document.getElementById(dotsId);

  if (!track || !dotsEl) return;

  const slides = track.querySelectorAll('.slide');
  const total  = slides.length;

  if (!total) return;

  let current = 0;
  let timer   = null;

  // ---------- Build dots ----------
  slides.forEach((_, i) => {
    const d = document.createElement('button');
    d.className = 'dot' + (i === 0 ? ' active' : '');
    d.setAttribute('aria-label', `Slide ${i + 1}`);
    d.addEventListener('click', () => {
      reset();
      goTo(i);
    });
    dotsEl.appendChild(d);
  });

  const dots = dotsEl.querySelectorAll('.dot');

  // ---------- Slide logic ----------
  function goTo(index) {
    dots[current].classList.remove('active');
    current = (index + total) % total;
    track.style.transform = `translateX(-${current * 100}%)`;
    dots[current].classList.add('active');
  }

  function next() {
    goTo(current + 1);
  }

  function prev() {
    goTo(current - 1);
  }

  function start() {
    timer = setInterval(next, 3800);
  }

  function reset() {
    clearInterval(timer);
    start();
  }

  // ---------- Optional Buttons (safe) ----------
  const nextBtn = wrapper.querySelector('.btn-next');
  const prevBtn = wrapper.querySelector('.btn-prev');

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      reset();
      next();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      reset();
      prev();
    });
  }

  // ---------- Touch swipe ----------
  let tx = 0;

  track.addEventListener('touchstart', e => {
    tx = e.changedTouches[0].screenX;
  }, { passive: true });

  track.addEventListener('touchend', e => {
    const diff = tx - e.changedTouches[0].screenX;

    if (Math.abs(diff) > 40) {
      reset();
      diff > 0 ? next() : prev();
    }
  }, { passive: true });

  // ---------- Pause on hover ----------
  wrapper.addEventListener('mouseenter', () => {
    clearInterval(timer);
  });

  wrapper.addEventListener('mouseleave', start);

  // ---------- Start autoplay ----------
  start();
}


// Desktop
initSlider({
  trackId: 'desktopTrack',
  dotsId:  'desktopDots',
  wrapper: document.getElementById('desktopSlider'),
});

// Mobile
initSlider({
  trackId: 'mobileTrack',
  dotsId:  'mobileDots',
  wrapper: document.getElementById('mobileSlider'),
});

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
