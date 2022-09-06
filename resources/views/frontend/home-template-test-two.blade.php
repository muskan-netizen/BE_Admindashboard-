@extends('layouts.store', ['title' => __('Home')])
@section('css-links')
{{--<link href="{{asset('css/aos.css')}}" rel="stylesheet">--}}
@endsection
@section('css')
<style type="text/css">
.main-menu .brand-logo{display:inline-block;padding-top:20px;padding-bottom:20px}
.shimmer_effect{overflow:hidden}
.grid-row.grid-4-4{display:grid;grid-template-columns:repeat(4,1fr);grid-gap:20px}
.shimmer_effect .card_image{width:100%;height:100%}
.shimmer_effect .card_image.loading{width:100%;height:180px}
.shimmer_effect .card_title.loading{width:50%;height:1rem;margin:1rem 0;border-radius:3px;position:relative}
.shimmer_effect .card_description{padding:8px;font-size:16px}
.shimmer_effect .card_description.loading{height:1rem;margin:1rem 0;border-radius:3px}
.shimmer_effect .loading{position:relative;background:#cccccc86}.shimmer_effect .loading:after{content:"";display:block;position:absolute;top:0;width:100%;height:100%;transform:translateX(-100px);background:linear-gradient(90deg,transparent,rgba(255,255,255,.2),transparent);animation:loading .8s infinite}.no-store-wrapper{display:none}@keyframes loading{100%{transform:translateX(100%)}}
.cardbanner {border-radius:0;height:550px;}
.shimmer_effect .grid-row .cards {margin-bottom: 40px;}
.shimmer_effect .grid-row .card_icon{display:none;}
.alTemplateTwoShimnerEffect .alTemplateTwoShimnerEffectBanner{width: 100%;max-width: 100%;}
.shimmer_effect .grid-row .card_image{border-radius:0;height:200px !important;}
.container_al{width: 100%;}
.alTwoHomeShimmer{position: fixed !important; background-color:#fff;width: 100%;top:0;z-index: 999 !important;}
.top_bar{height:50px;}
.logoArea_bar{height:54px;margin:20px 0 10px;}
.al_body_template_two section.section-b-space_.p-0.ratio_asos .container_al.shimmer_effect{width:100%; max-width: 100%;}
.alTabsView{border-radius:50px;}
@media(max-width:767px){.cardbanner {border-radius:0;height:250px;}}
@media (max-width: 991px){
.al_body_template_two #alTaxiBookingWrapper .cab-booking {
height: auto;
}}
</style>

@endsection
@section('content')

{{-- <div class="offset-top @if((\Request::route()->getName() != 'userHome') || ($client_preference_detail->show_icons == 0)) inner-pages-offset @endif @if($client_preference_detail->hide_nav_bar == 1) set-hide-nav-bar @endif"></div> --}}
<!-- shimmer_effect start -->
<section class="section-b-space_  p-0 ratio_asos alTwoHomeShimmer">
    <div class="container-fulid shimmer_effect  main_shimer topBar">
        <div class="row">
            <div class="col-12 cards">
                <div class="top_bar loading"></div>
            </div>
        </div>
    </div>
    <div class="container shimmer_effect main_shimer topBar">
        <div class="row">
            <div class="col-2 cards">
                <div class="logoArea_bar loading"></div>
            </div>
            <div class="col-1 cards">
                <div class="logoArea_bar"></div>
            </div>
            <div class="col-7 cards">
                <div class="logoArea_bar alTabsView loading"></div>
            </div>
            <div class="col-2 cards">
                <div class="logoArea_bar loading"></div>
            </div>
        </div>
    </div>
    <div class="container-fulid mt-1 mb-1 shimmer_effect main_shimer topBar">
        <div class="row">
            <div class="col-12 cards">
                <div class="top_bar loading"></div>
            </div>
        </div>
    </div>
	<div class="container_al mb-3 shimmer_effect main_shimer">
		<div class="row">
			<div class="col-12 cards">
				<div class="cardbanner loading"></div>
			</div>
		</div>
	</div>
	<div class="container mb-5 shimmer_effect main_shimer">
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
		<div class="row mt-3">
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


	</div>
</section>
<!-- shimmer_effect end -->
@if(count($banners))
<section class="home-slider-wrapper pt-md-3 pb-0">
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
                        <link rel="preload" as="image" href="{{$banner->image['proxy_url'] . '400/150' . $banner->image['image_path']}}" />
						<img alt="" title="" class="blur-up lazyload w-100" data-src="{{$banner->image['proxy_url'] . '400/150' . $banner->image['image_path']}}">
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

	</div>
</section>
@else
<section class="home-slider-wrapper">
    <div class="container-fulid">
        <div id="myCarousel" class="carousel slide al_desktop_banner" data-ride="carousel"></div>
        <div id="myMobileCarousel" class="carousel slide al_mobile_banner mb-2" data-ride="carousel" style="display:none;"></div>
    </div>
</section>
@endif


{{-- <script type="text/template" id="desktop_banners_template">
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
				<link rel="preload" as="image" href="<%= banner.image.proxy_url %>1370/300<%= banner.image.image_path %>" />
				<img alt="" title="" class="blur-up lazyload w-100" data-src="<%= banner.image.proxy_url %>1370/300<%= banner.image.image_path %>">
			 </a>
		  </div>
	   <% }); %>
	</div>
	<a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
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
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#myMobileCarousel" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</script> --}}

<section class="section-b-space ratio_asos pb-0 pt-0 mt-0 al_template_two_content" id="our_vendor_main_div">
    <div class="vendors">
        @foreach($homePageLabels as $key => $homePageLabel)
        @if($homePageLabel->slug == 'pickup_delivery')
            @if(isset($homePageLabel->pickupCategories) && count($homePageLabel->pickupCategories))
                @include('frontend.booking.cabbooking-single-module')
            @endif
        @elseif($homePageLabel->slug == 'dynamic_page')
            @include('frontend.included_files.dynamic_page')
        @else
        <div class="container render_full_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
            <div class="row">
                <div class="col-12"   >
                    @if($homePageLabel->slug == 'vendors' || $homePageLabel->slug == 'trending_vendors' || $homePageLabel->slug == 'best_sellers' && count($homePageData[$homePageLabel->slug]) != 0)
                    <div class="product-5 product-m no-arrow render_{{$homePageLabel->slug}} suppliers-slider-{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}" >
                        @foreach ($homePageData[$homePageLabel->slug] as $vendor)
                        @include('frontend.home_page_2.vendor')
                        @endforeach
                    </div>
                    @elseif($homePageLabel->slug == 'recent_orders' && count($homePageData[$homePageLabel->slug]) != 0)
                        <div class="recent-orders product-m no-arrow render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
                        @foreach ($homePageData[$homePageLabel->slug] as $order )
                        @include('frontend.home_page_2.recent_order')
                        @endforeach</div>
                    @elseif($homePageLabel->slug == 'brands' && count($homePageData[$homePageLabel->slug]) != 0)
                    <div class="brand-slider product-m no-arrow render_{{$homePageLabel->slug }}" id="{{$homePageLabel->slug.$key}}" >
                        @foreach ($homePageData[$homePageLabel->slug] as $brand )
                        @include('frontend.home_page_2.brands')
                        @endforeach
                    </div>
                    @else
                    @if(count($homePageData[$homePageLabel->slug]) != 0)
                    <div class="product-4-{{$homePageLabel->slug}} product-m no-arrow render_{{$homePageLabel->slug }}" id="{{$homePageLabel->slug.$key}}">
                        @foreach ($homePageData[$homePageLabel->slug] as $product )
                        @include('frontend.home_page_2.product')
                        @endforeach
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</section>
<section class="no-store-wrapper mb-3">
    <div class="container">
        @if(count($for_no_product_found_html))
        @foreach($for_no_product_found_html as $key => $homePageLabel)
            @include('frontend.included_files.dynamic_page')
        @endforeach
       @else
        <div class="row">
            <div class="col-12 text-center">
                <img class="no-store-image mt-2 mb-2" src="{{ getImageUrl(asset('images/no-stores.svg'),'250/250') }}" style="max-height: 250px;">
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center mt-2">
                <h4>{{__('There are no stores available in your area currently.')}}</h4>
            </div>
        </div>
        @endif
    </div>
</section>
<div class="modal fade" id="age_restriction" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img style="height:150px" class="img-fluid blur-up lazyload" data-src="{{asset('assets/images/18.png')}}" alt="">
                <p class="mb-0 mt-3">{{ $client_preference_detail ? $client_preference_detail->age_restriction_title : __('Are you 18 or older?') }}</p>
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
{{--<script type="text/javascript" src="{{asset('js/aos.js')}}"></script>--}}
@endsection
@section('script')
<script type="text/javascript">
    // AOS.init();
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
