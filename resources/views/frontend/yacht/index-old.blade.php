@extends('layouts.store', ['title' => __('Home')])
@section('css-links')
{{--<link href="{{asset('css/aos.css')}}" rel="stylesheet">--}}
<script>
	var featured_products_length = {{ isset($homePageData['featured_products']) ? count($homePageData['featured_products']) : ''}};
</script>
@endsection
@section('css')
<style>
	.logoArea_bar {
		height: 300px;
		margin: 0 0 30px;
	}

	.alMainMenuView {
		height: 100px;
		width: 100px;
		border-radius: 50%;
	}

	@media(max-width: 767px) {
		.logoArea_bar {
			height: 150px;
			margin: 0 0 20px;
		}
	}
</style>
@endsection
@section('content')
<!-- Shimmer Efferct Start -->
<section class="section-b-space_  p-0 ratio_asos banner_shimmer">
	<div class="container-fulid shimmer_effect  main_shimer topBar">
		<div class="row">
			<div class="col-12 cards">
				<div class="logoArea_bar loading"></div>
			</div>
		</div>
	</div>
	<div class="container shimmer_effect main_shimer">
		<div class="row">
			<div class="col-12 cards">
				<div class="cardbanner loading"></div>
			</div>
		</div>
	</div>
	<div class="container mb-md-5 shimmer_effect main_shimer">
		<div class="row">
			<div class="col-12 cards">
				<h2 class="h2-heading loading mb-3"></h2>
			</div>
		</div>
		<div class="row">

			<div class="col-sm-12">
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
	<div class="container mb-md-5 shimmer_effect main_shimer">
		<div class="row">
			<div class="col-12 cards">
				<h2 class="h2-heading loading mb-3"></h2>
			</div>
		</div>
		<div class="row">

			<div class="col-sm-12">
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
			<!-- <div class="col-sm-1 grid-row px-sm-3 p-0 d-sm-block d-none">
                <div class="card_image loading"></div>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="card_title loading"></div>
                    <div class="card_icon loading"></div>
                </div>
                <div class="card_content loading mt-0 w-75"></div>
                <div class="card_content loading mt-0 w-50"></div>
                <div class="card_line loading"></div>
                <div class="card_price loading"></div>
            </div> -->
		</div>

	</div>
	<div class="container mb-md-5 shimmer_effect main_shimer">
		<div class="row">
			<div class="col-12 cards">
				<h2 class="h2-heading loading mb-3"></h2>
			</div>
		</div>
		<div class="row">

			<div class="col-sm-12">
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
			<!-- <div class="col-sm-1 grid-row px-sm-3 p-0 d-sm-block d-none">
                <div class="card_image loading"></div>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="card_title loading"></div>
                    <div class="card_icon loading"></div>
                </div>
                <div class="card_content loading mt-0 w-75"></div>
                <div class="card_content loading mt-0 w-50"></div>
                <div class="card_line loading"></div>
                <div class="card_price loading"></div>
            </div> -->
		</div>

	</div>
</section>

<!-- Shimmer Efferct End -->

<!-- html code here -->
<button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#login_modal"> Launch demo modal </button>
@if(count($banners))
<section class="home-slider-wrapper pt-md-0 pb-0">

	<div class="container-fluid p-0">
		<div id="myCarousel" class="carousel slide al_desktop_banner" data-ride="carousel">
			<div class="carousel-inner">
				@foreach($banners as $key => $banner)
				@php $url=''; if($banner->link=='category'){if(!empty($banner->category_slug)){$url=route('categoryDetail', $banner->category_slug);}}else if($banner->link=='vendor'){if(!empty($banner->vendor_slug)){$url=route('vendorDetail', $banner->vendor_slug);}}else if($banner->link=='url'){if($banner->link_url !=null){$url=$banner->link_url;}}@endphp
				<div class="carousel-item @if($key == 0) active @endif">
					<a class="banner-img-outer" href="{{$url??'#'}}"  target="_blank">
						<link rel="preload" as="image" href="{{ get_file_path($banner->image,'IMG_URL1','1170','500') }}" />
						<img alt="" title="" class="blur-up lazyload w-100" data-src="{{ get_file_path($banner->image,'IMG_URL1','1170','500') }}">
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
					<a class="banner-img-outer" href="{{$url??'#'}}">
						<link rel="preload" as="image" href="{{ get_file_path($banner->image,'IMG_URL1','400','150') }}" />
						<img alt="" title="" class="blur-up lazyload w-100" data-src="{{ get_file_path($banner->image,'IMG_URL1','400','150') }}">
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
<section class="home-slider-wrapper">
	<div class="container-fulid">
		<div id="myCarousel" class="carousel slide al_desktop_banner" data-ride="carousel"></div>
		<div id="myMobileCarousel" class="carousel slide al_mobile_banner mb-2" data-ride="carousel" style="display:none;"></div>
	</div>
</section>
@endif



<!-- no-store-wrapper start -->
<section class="no-store-wrapper mb-3 mt-3" style="display: none;">
	<div class="container">
		@if(count($for_no_product_found_html))
		@foreach($for_no_product_found_html as $key => $homePageLabel)
		@include('frontend.included_files.dynamic_page')
		@endforeach
		@else
		<div class="row">
			<div class="col-12 text-center"> <img class="no-store-image mt-2 mb-2 blur-up lazyload" data-src="{{getImageUrl(asset('images/no-stores.svg'),'250/250')}}" style="max-height: 250px;"> </div>
		</div>
		<div class="row">
			<div class="col-12 text-center mt-2">
				<h4>{{__('We are currently not operating in your location.')}}</h4>
			</div>
		</div>
		@endif
	</div>
</section><!-- no-store-wrapper end -->

<section>
    <form action="{{ route('productSearch') }}" method="post">
		@csrf
        <input type="radio" name="service" required value="rental">
		<label for="">Car Rental</label>

        <input type="radio" name="service" value="airport">
		<label for="">Air Port</label>

        <input type="radio" name="service" value="yacht">
		<label for="">Yacht Book</label>

        <input type="text" name="location" placeholder="Location">
        <input type="number" name="seats" placeholder="Booking Number of seats">
        <input type="datetime-local" name="pickup_date">
        <input type="datetime-local" name="drop_date">
        <button type="submit">search</button>
    </form>
</section>
<!-- our_vendor_main_div start -->
<section class="section-b-space ratio_asos pt-0 mt-0 pb-0 {{isset($client_preference_detail) && $client_preference_detail->business_type == 'taxi' ? 'taxi' : ''}}" id="our_vendor_main_div">

	<div class="vendors">
		@foreach($homePageLabels as $key => $homePageLabel)
		@if($homePageLabel->slug == 'pickup_delivery')

		@else
		@if(!empty($homePageData[$homePageLabel->slug]) && count($homePageData[$homePageLabel->slug]) != 0)

		<section class="main-product mb-0 render_full_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
			<div class="container p2p-full-width">
				<div class="row ">
					<div class="col-md-12 pl-0 text-center">
						<div class="top-heading d-flex justify-content-between">
							<h2 class="h2-heading"> @php
								echo (!empty($homePageLabel->translations->first()->title)) ? $homePageLabel->translations->first()->title : __($homePageLabel->title);
								@endphp </h2>
								<!-- <a class="" href="">See All  <i class="fa fa-angle-right" aria-hidden="true"></i> </a>  -->
						</div>
					</div>
				</div>
			<div class="product-m  render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
				<div class="row  {{(($homePageLabel->slug =='featured_products')?'p2p_eccomerce_slider10':'p2p_eccomerce_slider')}}">
							@foreach ($homePageData[$homePageLabel->slug] as $product )
							@include('frontend.home_page_8.product')
							@endforeach
					</div>
				</div>
			</div>
		</section>
		@endif
		@endif @endforeach


	</div>
</section><!-- our_vendor_main_div end -->



<!-- age-restriction star -->
<div class="modal age-restriction fade" id="age_restriction" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body text-center"> <img style="height: 150px;" class="blur-up lazyload" data-src="{{getImageUrl(asset('assets/images/age-img.svg'),'150/150')}}" alt="" title="">
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
</div><!-- age-restriction end -->

<!-- footer code in layouts.store/footercontent-template-two -->
@section('home-page')
{{-- <script type="text/javascript" src="{{asset('front-assets/js/homepage-three.js')}}"></script> --}}
<script type="text/javascript" src="{{asset('assets/js/template/commonFunction.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/template/template-eight/templateFunction.js')}}"></script>
@endsection
@endsection
@section('js-script')
{{--<script type="text/javascript" src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script type="text/javascript" src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script type="text/javascript" src="{{asset('js/aos.js')}}"></script>--}}
@endsection
@section('script')
@endsection
