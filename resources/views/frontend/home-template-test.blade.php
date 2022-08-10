@extends('layouts.store', ['title' => __('Home')]) @section('content')
@section('css-links')
<link rel="stylesheet/less" type="text/css" href="{{ asset('front-assets/css/shimmer-less.less') }}">
{{--
<link rel="stylesheet"  href="{{asset('css/aos.css')}}">
--}}
@endsection
@php
$preference = $client_preference_detail;
@endphp
@section('css')
<style>
   .cardbanner {height:300px;}
   .shimmer_effect .grid-row .cards {margin-bottom: 20px;}
   .shimmer_effect .grid-row .card_icon{display:none;}
   .shimmer_effect .grid-row .card_image{border-radius:12px;height:200px !important;}
   .alOneTemplate{position: fixed !important;width: 100%; background: #fff;z-index: 999 !important;top: 0;}
   @media(max-width:767px){.cardbanner {height:120px;}}
</style>
@endsection
<!-- <div class="offset-top @if((\Request::route()->getName() != 'userHome') || ($client_preference_detail->show_icons == 0)) inner-pages-offset @endif @if($client_preference_detail->hide_nav_bar == 1) set-hide-nav-bar @endif"></div> -->
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#login_modal"> Launch demo modal </button>

<!-- shimmer_effect start -->
<section class="section-b-space_  p-0 ratio_asos alOneTemplate">
    <div class="container-fulid shimmer_effect main_shimer topBar">
        <div class="row">
            <div class="col-12 cards">
                <div class="top_bar loading"></div>
            </div>
        </div>
    </div>
    <div class="container-fulid shimmer_effect main_shimer topBar">
        <div class="row">
            <div class="col-12 cards">
                <div class="logoArea_bar loading"></div>
            </div>
        </div>
    </div>
   <div class="container mb-3 mt-3 shimmer_effect main_shimer ">
      <div class="row">
         <div class="col-12 cards">
            <div class="cardbanner loading"></div>
         </div>
      </div>
   </div>
   @switch($preference->business_type)
   @case('taxi')
   <div class="container_al mb-3 shimmer_effect main_shimer">
      <div class="row">
         <div class="col-12 cards">
            <div class="cardbanner loading"></div>
         </div>
      </div>
   </div>
   @break
   @default
   <div class="container mb-5 shimmer_effect main_shimer">
      <div class="row">
         <div class="col-12 cards">
            <h2 class="h2-heading loading mb-3"></h2>
         </div>
      </div>
      <div class="row">
         <div class="col-1 grid-row">
            <div class="card_image loading"></div>
            <div class="d-flex align-items-center justify-content-between">
               <div class="card_title loading"></div>
            </div>
            <div class="card_content loading mt-0 w-75"></div>
            <div class="card_content loading mt-0 w-50"></div>
            <div class="card_line loading"></div>
            <div class="card_price loading"></div>
         </div>
         <div class="col-10">
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
         <div class="col-1 grid-row">
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
      <div class="row">
         <div class="col-1 grid-row">
            <div class="card_image loading"></div>
            <div class="d-flex align-items-center justify-content-between">
               <div class="card_title loading"></div>
            </div>
            <div class="card_content loading mt-0 w-75"></div>
            <div class="card_content loading mt-0 w-50"></div>
            <div class="card_line loading"></div>
            <div class="card_price loading"></div>
         </div>
         <div class="col-10">
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
         <div class="col-1 grid-row">
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
      <div class="row">
         <div class="col-1 grid-row">
            <div class="card_image loading"></div>
            <div class="d-flex align-items-center justify-content-between">
               <div class="card_title loading"></div>
            </div>
            <div class="card_content loading mt-0 w-75"></div>
            <div class="card_content loading mt-0 w-50"></div>
            <div class="card_line loading"></div>
            <div class="card_price loading"></div>
         </div>
         <div class="col-10">
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
         <div class="col-1 grid-row">
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
   @endswitch
</section>

@if(count($banners))

<section class="home-slider-wrapper py-sm-3 ">
   <div class="container">
      <div id="myCarousel" class="carousel slide al_desktop_banner" data-ride="carousel">
         <div class="carousel-inner">
            @foreach($banners as $key => $banner)
            @php $url=''; if($banner->link=='category'){if($banner->category !=null){$url=route('categoryDetail', $banner->category->slug);}}else if($banner->link=='vendor'){if($banner->vendor !=null){$url=route('vendorDetail', $banner->vendor->slug);}}@endphp
            <div class="carousel-item @if($key == 0) active @endif">
               <a class="banner-img-outer" href="{{$url??'#'}}">
                  <link rel="preload" as="image" href="{{$banner->image['proxy_url'] . '1370/300' . $banner->image['image_path']}}" />
                  <img alt="" title="" class="blur-up lazyload w-100" data-src="{{$banner->image['proxy_url'] . '1370/300' . $banner->image['image_path']}}">
               </a>
            </div>
            @endforeach
         </div>
         <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
         <span class="sr-only">Previous</span>
         </a>
         <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
         <span class="carousel-control-next-icon" aria-hidden="true"></span>
         <span class="sr-only">Next</span>
         </a>
      </div>
      <div id="myMobileCarousel" class="carousel slide al_mobile_banner mb-2" data-ride="carousel" style="display:none;">
         <div class="carousel-inner">
            @foreach($mobile_banners as $key => $banner)
            @php $url=''; if($banner->link=='category'){if($banner->category !=null){$url=route('categoryDetail', $banner->category->slug);}}else if($banner->link=='vendor'){if($banner->vendor !=null){$url=route('vendorDetail', $banner->vendor->slug);}}@endphp
            <div class="carousel-item @if($key == 0) active @endif">
               <a class="banner-img-outer" href="{{$url??'#'}}">
                  <link rel="preload" as="image" href="{{$banner->image['proxy_url'] . '1370/300' . $banner->image['image_path']}}" />
                  <img alt="" title="" class="blur-up lazyload w-100" data-src="{{$banner->image['proxy_url'] . '1370/300' . $banner->image['image_path']}}">
               </a>
            </div>
            @endforeach
         </div>
         <a class="carousel-control-prev" href="#myMobileCarousel" role="button" data-slide="prev">
         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
         <span class="sr-only">Previous</span>
         </a>
         <a class="carousel-control-next" href="#myMobileCarousel" role="button" data-slide="next">
         <span class="carousel-control-next-icon" aria-hidden="true"></span>
         <span class="sr-only">Next</span>
         </a>
      </div>
      <!-- <div class="shimmer_effect">
         <div class="loading"></div>
         </div>
         <div class="home-banner-slider">
         @foreach($banners as $banner) @php $url=''; if($banner->link=='category'){if($banner->category !=null){$url=route('categoryDetail', $banner->category->slug);}}else if($banner->link=='vendor'){if($banner->vendor !=null){$url=route('vendorDetail', $banner->vendor->slug);}}@endphp @if($url) <a class="banner-img-outer" href="{{$url}}"> @endif <img alt="" title="" class="blur-up lazyload" data-src="{{$banner->image['proxy_url'] . '1370/300' . $banner->image['image_path']}}"> @if($url) </a> @endif @endforeach
         </div> -->
   </div>
</section>
@endif


<section class="section-b-space ratio_asos  pt-0 mt-0 pb-0 mt-0" id="our_vendor_main_div">
   <div class="vendors">
      @foreach($homePageLabels as $key => $homePageLabel) 
         @if($homePageLabel->slug == 'pickup_delivery')
            @if(isset($homePageLabel->pickupCategories) && count($homePageLabel->pickupCategories))
               @include('frontend.booking.cabbooking-single-module')
            @endif
         @elseif($homePageLabel->slug == 'dynamic_page')
            @include('frontend.included_files.dynamic_page')
         @elseif($homePageLabel->slug == 'brands')
            <section class="popular-brands left-shape_ position-relative">
               <div class="container ">
                  <div class="row align-items-center">
                     <div class="col-lg-2 cw top-heading pr-0 text-center text-lg-left mb-3 mb-lg-0">
                        <h2 class="h2-heading">{{(!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : getNomenclatureName('brands', true)}}</h2> 
                     </div>
                     <div class="col-lg-10 al_custom_brand">
                        <div class="brand-slider render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}"> 
                           @foreach ($homePageData['brands'] as $brand )
                              @include('frontend.home_page_1.brands')
                           @endforeach
                        </div>
                     </div>
                  </div>
               </div> 
               <div class="container "  >
                  <div class="al_top_heading col-md-12">
                     <div class="row d-flex justify-content-between">
                        <h2 class="h2-heading text-capitalize">{{(!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : getNomenclatureName('brands', true)}}</h2>
                        {{-- <a class="" href="">See All</a> --}}
                     </div>
                  </div>
                  <div class="row ">
                     <div class=" col-md-12 al_custom_brand">
                        <div class=" brand-slider render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}"> </div>
                     </div>
                  </div>
               </div>
            </section>
         @elseif($homePageLabel->slug == 'vendors')
            <section class="suppliers-section">
               <div class="container"  >
                  <div class="row">
                     <div class="col-12 top-heading d-flex align-items-center justify-content-between">
                        <h2 class="h2-heading">{{(!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : getNomenclatureName('vendors', true)}}</h2>
                        <a class="" href="{{route('vendor.all')}}">{{__("See all")}}</a>
                     </div>
                     <div class="col-12">
                        <div class="suppliers-slider-{{$homePageLabel->slug}} product-m render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
                           @foreach ($homePageData['vendors'] as $vendor )
                              @include('frontend.home_page_1.vendor')
                           @endforeach
                        </div>
                     </div>
                  </div>
               </div>
            </section>
         @elseif($homePageLabel->slug == 'trending_vendors')
         <section class="suppliers-section">
            <div class="container"  >
               <div class="row">
                  <div class="col-12 top-heading d-flex align-items-center justify-content-between">
                     <h2 class="h2-heading">{{$homePageLabel->slug=='trending_vendors' ? __('Trending')." ".getNomenclatureName('vendors', true) : __($homePageLabel->title)}}</h2>
                  </div>
                  <div class="col-12">
                     <div class="suppliers-slider-{{$homePageLabel->slug}} product-m render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
                     </div>
                  </div>
               </div>
            </div>
         </section>
         @else
         <section class="container mb-0 render_full_{{$homePageLabel->slug}} " id="{{$homePageLabel->slug.$key}}"  >
            <div class="row" >
               <div class="col-12 top-heading d-flex align-items-center justify-content-between">
                  <h2 class="h2-heading"> @php if($homePageLabel->slug=='vendors'){echo getNomenclatureName('vendors', true);}elseif($homePageLabel->slug=='recent_orders'){echo (!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : __("Your Recent Orders");}else{echo (!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : __($homePageLabel->title);}@endphp </h2>
                  @if($homePageLabel->slug=='vendors') <a class="" href="{{route('vendor.all')}}">{{__('View More')}}</a> @endif
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  @if($homePageLabel->slug=='vendors' || $homePageLabel->slug=='trending_vendors')
                  <div class="product-5 product-m no-arrow render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}"></div>
                  @elseif($homePageLabel->slug=='recent_orders')
                  <div class="recent-orders product-m no-arrow render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}"></div>
                  @else
                  <div class="product-4-{{$homePageLabel->slug}} product-m no-arrow render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}"></div>
                  @endif
               </div>
            </div>
         </section>
         @endif 
      @endforeach
   </div>
</section>
<section class="no-store-wrapper mb-3" style="display: none;"  >
   <div class="container">
      @if(count($for_no_product_found_html)) @foreach($for_no_product_found_html as $key => $homePageLabel) @include('frontend.included_files.dynamic_page') @endforeach @else
      <div class="row">
         <div class="col-12 text-center"> <img class="no-store-image mt-2 mb-2 blur-up lazyload" data-src="{{getImageUrl(asset('images/no-stores.svg'),'250/250')}}" style="max-height: 250px;"> </div>
      </div>
      <div class="row">
         <div class="col-12 text-center mt-2">
            <h4>{{__('There are no stores available in your area currently.')}}</h4>
         </div>
      </div>
      @endif
   </div>
</section>
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
@endsection
@section('js-script')
<script type="text/javascript" src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/less@4"></script>
{{--<script type="text/javascript" src="{{asset('js/aos.js')}}"></script>--}}
@endsection
@section('script')
<script type="text/javascript">
function showKeycode(e) {
   if(e.code == 'KeyZ'){
      alert(e.keyCode);
   }
}

document.addEventListener('keydown',showKeycode);
   // AOS.init();
   // 	$('.center').slick({
   //   centerMode: true,
   //   centerPadding: '60px',
   //   slidesToShow: 2,
   //   responsive: [
   //     {breakpoint: 768, settings: { arrows: false, centerMode: true, centerPadding: '40px', slidesToShow: 2}},
   //     {breakpoint: 480, settings: { arrows: false, centerMode: true, centerPadding: '40px', slidesToShow: 1}}
   //   ]
   // });
   function changeImage(image, check) {
     var  icon = $(image).attr('data-icon');
     var  icon_two = $(image).attr('data-icon_two');
     if(check == 1)
     {
       setTimeout(function () {
           $(image).attr('data-src',icon_two);
           $(image).attr('src',icon_two);
       },200);
     }else if(check == 0){
          setTimeout(function () {
              $(image).attr('data-src',icon);
              $(image).attr('src',icon);
          },200);
     }
   }
</script>
@endsection