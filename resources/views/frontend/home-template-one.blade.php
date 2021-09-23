@extends('layouts.store', ['title' => __('Home')])

@section('css')

<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/custom-template-one.css')}}">
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
@endsection
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar-template-one')
</header>

<section class="home-slider-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="home-banner-slider">
                    @foreach($banners as $banner)
                        @php
                        $url = '';
                        if($banner->link == 'category'){
                        if($banner->category != null){
                        $url = route('categoryDetail', $banner->category->slug);
                        }
                        }
                        else if($banner->link == 'vendor'){
                        if($banner->vendor != null){
                        $url = route('vendorDetail', $banner->vendor->slug);
                        }
                        }
                        @endphp
                            @if($url)
                            <a href="{{$url}}">
                                @endif
                                <div>
                                    <img src="{{$banner->image['image_fit'] . '1500/600' . $banner->image['image_path']}}" alt="" >
                                </div>
                                @if($url)
                            </a>
                            @endif
                    @endforeach
                   
                   
                </div>
            </div>
        </div>
    </div>
</section>

<div class="home-content-area">
    @foreach($homePageLabels as $key => $homePageLabel)
  
        @if($homePageLabel->slug == 'pickup_delivery')
                @if(isset($homePageLabel->pickupCategories))
                 @include('frontend.booking.cabbooking-single-module')
                @endif 
        @elseif($homePageLabel->slug == 'dynamic_page')
                @include('frontend.included_files.dynamic_page')
        @elseif($homePageLabel->slug == 'new_products' || $homePageLabel->slug == 'on_sale' || $homePageLabel->slug == 'featured_products' || $homePageLabel->slug == 'best_sellers')
        <!-- Newly arrived Section Start From Here -->
        <section class="newly-arrived">
            <div class="container">
                <div class="row">
                    <div class="col-12 top-heading d-flex align-items-center justify-content-between mb-md-4 mb-3">
                        <h2 class="h2-heading">{{ $homePageLabel->slug == 'vendors' ? getNomenclatureName('vendors', true) :  __($homePageLabel->title) }}</h2>
                        <a class="see-all-btn" href="#">See all</a>
                    </div>
                    <div class="col-12 px-0">
                        <div class="newly-arrived-slider">
                            <div>
                                <div class="common-product-box">
                                    <div class="img-outer-box">
                                        <img src="{{asset('front-assets/images/product-img.jpg')}}" alt="">
                                        <div class="rating-box">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <span>4.5</span>
                                        </div>
                                    </div>
                                    <div class="product-description">
                                        <h3>Extra Virgin Olive Oil</h3>
                                        <p>Mega Mart</p>
                                        <b class="d-block">$ 90</b>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="common-product-box">
                                    <div class="img-outer-box">
                                        <img src="{{asset('front-assets/images/product-img.jpg')}}" alt="">
                                        <div class="rating-box">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <span>4.5</span>
                                        </div>
                                    </div>
                                    <div class="product-description">
                                        <h3>Extra Virgin Olive Oil</h3>
                                        <p>Mega Mart</p>
                                        <b class="d-block">$ 90</b>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="common-product-box">
                                    <div class="img-outer-box">
                                        <img src="{{asset('front-assets/images/product-img.jpg')}}" alt="">
                                        <div class="rating-box">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <span>4.5</span>
                                        </div>
                                    </div>
                                    <div class="product-description">
                                        <h3>Extra Virgin Olive Oil</h3>
                                        <p>Mega Mart</p>
                                        <b class="d-block">$ 90</b>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="common-product-box">
                                    <div class="img-outer-box">
                                        <img src="{{asset('front-assets/images/product-img.jpg')}}" alt="">
                                        <div class="rating-box">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <span>4.5</span>
                                        </div>
                                    </div>
                                    <div class="product-description">
                                        <h3>Extra Virgin Olive Oil</h3>
                                        <p>Mega Mart</p>
                                        <b class="d-block">$ 90</b>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="common-product-box">
                                    <div class="img-outer-box">
                                        <img src="{{asset('front-assets/images/product-img.jpg')}}" alt="">
                                        <div class="rating-box">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <span>4.5</span>
                                        </div>
                                    </div>
                                    <div class="product-description">
                                        <h3>Extra Virgin Olive Oil</h3>
                                        <p>Mega Mart</p>
                                        <b class="d-block">$ 90</b>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="common-product-box">
                                    <div class="img-outer-box">
                                        <img src="{{asset('front-assets/images/product-img.jpg')}}" alt="">
                                        <div class="rating-box">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <span>4.5</span>
                                        </div>
                                    </div>
                                    <div class="product-description">
                                        <h3>Extra Virgin Olive Oil</h3>
                                        <p>Mega Mart</p>
                                        <b class="d-block">$ 90</b>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="common-product-box">
                                    <div class="img-outer-box">
                                        <img src="{{asset('front-assets/images/product-img.jpg')}}" alt="">
                                        <div class="rating-box">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <span>4.5</span>
                                        </div>
                                    </div>
                                    <div class="product-description">
                                        <h3>Extra Virgin Olive Oil</h3>
                                        <p>Mega Mart</p>
                                        <b class="d-block">$ 90</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<<<<<<< HEAD
        </section>
        @elseif($homePageLabel->slug == 'brands')
        <!-- Popular Brands Section Start From Here -->
        <section class="popular-brands left-shape position-relative">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-2 cw top-heading pr-0 text-center text-lg-left mb-3 mb-lg-0">
                        <h2 class="h2-heading">Popular Brands</h2>
                        <p>Check out the favorites among people.</p>
                    </div>
                    <div class="col-lg-10 cw">
                        <div class="brand-slider">
                            <div>
                                <div class="brand-box d-flex align-items-center justify-content-center flex-column black-box">
                                    <div class="brand-ing">
                                        <img src="{{asset('front-assets/images/nike.png')}}" alt="">
                                    </div>
                                    <h6>Nike</h6>
=======
        </div>
    </section>

     <!-- Popular Brands Section Start From Here -->
     <section class="popular-brands left-shape position-relative">
        <div class="container">
            <div class="row align-items-center position-relative">
                <div class="col-lg-2 cw top-heading pr-0 text-center text-lg-left mb-3 mb-lg-0">
                    <h2 class="h2-heading">Popular Brands</h2>
                    <p>Check out the favorites among people.</p>
                </div>
                <div class="col-lg-10 cw">
                    <div class="brand-slider">
                        <div>
                            <div class="brand-box d-flex align-items-center justify-content-center flex-column black-box">
                                <div class="brand-ing">
                                    <img src="{{asset('front-assets/images/nike.png')}}" alt="">
>>>>>>> 22e95905632b55092d9b80bc82cf9da44e0eb2f1
                                </div>
                            </div>
                            <div>
                                <div class="brand-box d-flex align-items-center justify-content-center flex-column red-box">
                                    <div class="brand-ing">
                                        <img src="{{asset('front-assets/images/nike.png')}}" alt="">
                                    </div>
                                    <h6>Nike</h6>
                                </div>
                            </div>
                            <div>
                                <div class="brand-box d-flex align-items-center justify-content-center flex-column blue-box">
                                    <div class="brand-ing">
                                        <img src="{{asset('front-assets/images/dominos.png')}}" alt="">
                                    </div>
                                    <h6>Dominos</h6>
                                </div>
                            </div>
                            <div>
                                <div class="brand-box d-flex align-items-center justify-content-center flex-column red-box">
                                    <div class="brand-ing">
                                        <img src="{{asset('front-assets/images/nike.png')}}" alt="">
                                    </div>
                                    <h6>Nike</h6>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="brand-box d-flex align-items-center justify-content-center flex-column red-box">
                                <div class="brand-ing">
                                    <img src="{{asset('front-assets/images/nike.png')}}" alt="">
                                </div>
                                <h6>Nike</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @elseif($homePageLabel->slug == 'vendors')
        <!-- Vendors Section Start From Here -->
        <section class="suppliers-section pt-0 mb-5">
            <div class="container">
                <div class="row">
                    <div class="col-12 top-heading d-flex align-items-center justify-content-between mb-md-4 mb-3">
                        <h2 class="h2-heading">Suppliers</h2>
                        <a class="see-all-btn" href="#">See all</a>
                    </div>
                    <div class="col-12 px-0">
                        <div class="suppliers-slider">
                            <div>
                                <div class="suppliers-box px-2">
                                    <div class="suppliers-img-outer text-center">
                                        <img class="fluid-img mx-auto" src="{{asset('front-assets/images/appirio.png')}}" alt="">
                                    </div>
                                    <div class="supplier-rating d-flex align-items-center justify-content-between">
                                        <h6>Cloudtail</h6>
                                        <ul class="m-0 p-0">
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="suppliers-box px-2">
                                    <div class="suppliers-img-outer text-center">
                                        <img class="fluid-img mx-auto" src="{{asset('front-assets/images/cloud-tail.png')}}" alt="">
                                    </div>
                                    <div class="supplier-rating d-flex align-items-center justify-content-between">
                                        <h6>Cloudtail</h6>
                                        <ul class="m-0 p-0">
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="suppliers-box px-2">
                                    <div class="suppliers-img-outer text-center">
                                        <img class="fluid-img mx-auto" src="{{asset('front-assets/images/appirio.png')}}" alt="">
                                    </div>
                                    <div class="supplier-rating d-flex align-items-center justify-content-between">
                                        <h6>Cloudtail</h6>
                                        <ul class="m-0 p-0">
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="suppliers-box px-2">
                                    <div class="suppliers-img-outer text-center">
                                        <img class="fluid-img mx-auto" src="{{asset('front-assets/images/cloud-tail.png')}}" alt="">
                                    </div>
                                    <div class="supplier-rating d-flex align-items-center justify-content-between">
                                        <h6>Cloudtail</h6>
                                        <ul class="m-0 p-0">
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="suppliers-box px-2">
                                    <div class="suppliers-img-outer text-center">
                                        <img class="fluid-img mx-auto" src="{{asset('front-assets/images/cloud-tail.png')}}" alt="">
                                    </div>
                                    <div class="supplier-rating d-flex align-items-center justify-content-between">
                                        <h6>Cloudtail</h6>
                                        <ul class="m-0 p-0">
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                            <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @else

<<<<<<< HEAD
        @endif
    @endforeach
</div>

=======
    <!-- Popular Brands Section Start From Here -->
    <section class="royo-recommends right-shape position-relative">
        <div class="container">
            <div class="row align-items-center position-relative">
                <div class="col-lg-2 cw top-heading pl-0 order-lg-1 mb-3 mb-lg-0 text-center text-lg-left">
                    <h2 class="h2-heading">Royo Recommends</h2>
                    <p>Check out recommended items.</p>
                </div>
                <div class="col-lg-10 cw">
                    <div class="brand-slider">
                        <div>
                            <div class="brand-box d-flex align-items-center justify-content-center flex-column black-box">
                                <div class="brand-ing">
                                    <img src="{{asset('front-assets/images/nike.png')}}" alt="">
                                </div>
                                <h6>Nike</h6>
                            </div>
                        </div>
                        <div>
                            <div class="brand-box d-flex align-items-center justify-content-center flex-column black-box">
                                <div class="brand-ing">
                                    <img src="{{asset('front-assets/images/nike.png')}}" alt="">
                                </div>
                                <h6>Nike</h6>
                            </div>
                        </div>
                        <div>
                            <div class="brand-box d-flex align-items-center justify-content-center flex-column red-box">
                                <div class="brand-ing">
                                    <img src="{{asset('front-assets/images/nike.png')}}" alt="">
                                </div>
                                <h6>Nike</h6>
                            </div>
                        </div>
                        <div>
                            <div class="brand-box d-flex align-items-center justify-content-center flex-column blue-box">
                                <div class="brand-ing">
                                    <img src="{{asset('front-assets/images/dominos.png')}}" alt="">
                                </div>
                                <h6>Nike</h6>
                            </div>
                        </div>
                        <div>
                            <div class="brand-box d-flex align-items-center justify-content-center flex-column red-box">
                                <div class="brand-ing">
                                    <img src="{{asset('front-assets/images/nike.png')}}" alt="">
                                </div>
                                <h6>Nike</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newly arrived Section Start From Here -->
    <section class="suppliers-section pt-0 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 top-heading d-flex align-items-center justify-content-between mb-md-4 mb-3">
                    <h2 class="h2-heading">Suppliers</h2>
                    <a class="see-all-btn" href="#">See all</a>
                </div>
                <div class="col-12 px-0">
                    <div class="suppliers-slider">
                        <div>
                            <div class="suppliers-box px-2">
                                <div class="suppliers-img-outer text-center">
                                    <img class="fluid-img mx-auto" src="{{asset('front-assets/images/appirio.png')}}" alt="">
                                </div>
                                <div class="supplier-rating d-flex flex-column align-items-center justify-content-between">
                                    <h6>Cloudtail</h6>
                                    <ul class="m-0 p-0">
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="suppliers-box px-2">
                                <div class="suppliers-img-outer text-center">
                                    <img class="fluid-img mx-auto" src="{{asset('front-assets/images/cloud-tail.png')}}" alt="">
                                </div>
                                <div class="supplier-rating d-flex flex-column align-items-center justify-content-between">
                                    <h6>Cloudtail</h6>
                                    <ul class="m-0 p-0">
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="suppliers-box px-2">
                                <div class="suppliers-img-outer text-center">
                                    <img class="fluid-img mx-auto" src="{{asset('front-assets/images/fireart.png')}}" alt="">
                                </div>
                                <div class="supplier-rating d-flex flex-column align-items-center justify-content-between">
                                    <h6>Cloudtail</h6>
                                    <ul class="m-0 p-0">
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="suppliers-box px-2">
                                <div class="suppliers-img-outer text-center">
                                    <img class="fluid-img mx-auto" src="{{asset('front-assets/images/cloud-tail.png')}}" alt="">
                                </div>
                                <div class="supplier-rating d-flex flex-column align-items-center justify-content-between">
                                    <h6>Cloudtail</h6>
                                    <ul class="m-0 p-0">
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                        <li><i class="fa fa-star" aria-hidden="true"></i></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
>>>>>>> 22e95905632b55092d9b80bc82cf9da44e0eb2f1


<script type="text/template" id="vendors_template">
    <% _.each(vendors, function(vendor, k){%>
        <div class="product-box scale-effect">
            <div class="img-wrapper">
                <div class="front">
                    <a href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                        <img class="img-fluid blur-up lazyload m-auto bg-img" alt="" src="<%= vendor.logo.proxy_url %>200/200<%= vendor.logo['image_path'] %>">
                    </a>
                </div>
            </div>
            <div class="product-detail inner_spacing text-center m-0 w-100">
                <a href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                    <h3 class="d-flex justify-content-between p-0">
                        <span><%= vendor.name %></span>
                        @if($client_preference_detail)
                            @if($client_preference_detail->rating_check == 1)
                                <% if(vendor.vendorRating > 0){%>
                                    <span class="rating m-0"><%= vendor.vendorRating %> <i class="fa fa-star text-white p-0"></i></span>
                                <% } %>
                            @endif
                        @endif
                    </h3>
                </a>
                <% if(vendor.timeofLineOfSightDistance != undefined){ %>
                    <h6 class="d-flex justify-content-between">
                        <small><i class="fa fa-map-marker"></i> <%= vendor.lineOfSightDistance %>km</small>
                        <small><i class="fa fa-clock"></i> <%= vendor.timeofLineOfSightDistance %>min</small>
                    </h6>
                <% } %>
            </div>
        </div>
    <% }); %>
</script>

<script type="text/template" id="banner_template">
    <% _.each(brands, function(brand, k){%>
        <div>
            <div class="">
                <a href="<%= brand.redirect_url %>">
                    <img src="<%= brand.image.image_fit %>120/120<%= brand.image.image_path %>" alt="">
                </a>
            </div>
        </div>
    <% }); %>
</script>

<script type="text/template" id="products_template">
    <% _.each(products, function(product, k){ %>
        <div>
            <a class="card scale-effect text-center" href="{{route('productDetail')}}/<%= product.url_slug %>">
                <label class="product-tag"><%= type %></label>
                <div class="product-image">
                    <img src="<%= product.image_url %>" alt="">
                </div>    
                <div class="media-body align-self-center">
                    <div class="inner_spacing px-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="m-0"><%= product.title %></h3>
                            @if($client_preference_detail)
                                @if($client_preference_detail->rating_check == 1)
                                    <% if(product.averageRating > 0){%>
                                        <span class="rating"><%= product.averageRating %> <i class="fa fa-star text-white p-0"></i></span>
                                    <% } %>
                                @endif
                            @endif
                        </div>
                        <p><%= product.vendor_name %></p>
                        <h4>
                            <% if(product.inquiry_only == 0) { %>
                                <%= product.price %>
                            <% } %>
                        </h4>
                    </div>
                </div>
            </a>
        </div>
    <% }); %>
</script>

<section class="section-b-space p-t-0 pt-3 pt-md-5 ratio_asos d-none" id="our_vendor_main_div">
    <div class="vendors">
        @foreach($homePageLabels as $key => $homePageLabel)
        @if($homePageLabel->slug == 'pickup_delivery')
                @if(isset($homePageLabel->pickupCategories))
                 @include('frontend.booking.cabbooking-single-module')
                @endif 
        @elseif($homePageLabel->slug == 'dynamic_page')
                @include('frontend.included_files.dynamic_page')
         @else
        <div class="container render_full_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
            <div class="row">
                <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                    <div class="title1">
                        <!-- <h2 class="title-inner1 mb-0">{{ $homePageLabel->slug == 'vendors' ? getNomenclatureName('vendors', true) :  __($homePageLabel->title) }}</h2> -->
                    </div>
                    @if($homePageLabel->slug == 'vendors')
                    <a class="view_more_items" href="{{route('vendor.all')}}">{{__('View More')}}</a>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if($homePageLabel->slug == 'vendors')
                    <div class="product-5 product-m no-arrow render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}"></div>
                    @else
                    <div class="product-4 product-m no-arrow render_{{$homePageLabel->slug }}" id="{{$homePageLabel->slug.$key}}"></div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</section>

<div class="modal fade" id="age_restriction" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="{{asset('assets/images/18.png')}}" alt="">
                <p class="mb-0 mt-3">{{ $client_preference_detail ? $client_preference_detail->age_restriction_title : 'Are you 18 or older?' }}</p>
                <p class="mb-0">Are you sure you want to continue?</p>
            </div>
            <div class="modal-footer d-block">
                <div class="row no-gutters">
                    <div class="col-6 pr-1">
                        <button type="button" class="btn btn-solid w-100 age_restriction_yes" data-dismiss="modal">Yes</button>
                    </div>
                    <div class="col-6 pl-1">
                        <button type="button" class="btn btn-solid w-100 age_restriction_no" data-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{asset('front-assets/js/jquery.exitintent.js')}}"></script>
<script src="{{asset('front-assets/js/fly-cart.js')}}"></script>
<script src="{{asset('front-assets/js/custom-template-one.js')}}"></script>
<script>
    $(document).ready(function() {
        $("#doneeee").click(function(){
            console.log("nejhbfe");
            // $(".hide_div").hide();
        });
    });
    // $(".mobile-back").on("click", function() {
    //     $(".sm-horizontal").css("right", "-410px");
    // });
</script>
@endsection