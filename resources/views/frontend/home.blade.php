@extends('layouts.store', ['title' => __('Home')])
@php
$actives=array();
@endphp
@foreach($homePageLabels as $homePageLabel)
@php
    if($homePageLabel->is_active == 1){
        array_push($actives,$homePageLabel->slug);
    }
@endphp
@endforeach
@section('css')
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
    @include('layouts.store/left-sidebar')
</header>
<section class="p-0 small-slider">
    <div class="slide-1 home-slider">
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
            <div>
                @if($url)
                <a href="{{$url}}">
                @endif
                    <div class="home text-center">
                        <img src="{{$banner->image['image_fit'] . '1500/600' . $banner->image['image_path']}}" class="bg-img blur-up lazyload">
                    </div>
                @if($url)
                </a>
                @endif
            </div>
        @endforeach
    </div>
</section>
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
            <div class="product-detail">
                <a href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                    <h6><%= vendor.name %></h6>
                </a>
                @if($client_preference_detail)
                    @if($client_preference_detail->rating_check == 1)
                        <div class="custom_rating">
                            <% if(vendor.vendorRating > 0) { %>
                                <% _.each([1,2,3,4,5], function(value, k){ %>
                                    <i class="fa fa-star<%= (k+1 <= vendor.vendorRating) ? ' filled' : '' %>"></i>
                                <% }); %>
                            <% } %>
                        </div>
                    @endif
                @endif
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
                <div class="product-image">
                    <img src="<%= product.image_url %>" alt="">
                </div>    
                <div class="media-body align-self-center px-3">
                    <div class="inner_spacing">
                        <h3><%= product.title %></h3>
                        <p><%= product.vendor_name %></p>
                        <h4>
                            <% if(product.inquiry_only == 0) { %>
                                <%= product.price %>
                            <% } %>
                        </h4>
                        @if($client_preference_detail)
                            @if($client_preference_detail->rating_check == 1)
                                <div class="custom_rating">
                                    <% if(product.averageRating > 0) { %>
                                        <% _.each([1,2,3,4,5], function(value, k){ %>
                                            <i class="fa fa-star<%= (k+1 <= product.averageRating) ? ' filled' : '' %>"></i>
                                        <% }); %>
                                    <% } %>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </a>
        </div>
    <% }); %>
</script>

@if(in_array("vendors", $actives))
<section class="section-b-space p-t-0 pt-5 ratio_asos pb-0 d-none" id="our_vendor_main_div">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{getNomenclatureName('vendors', true)}}</h2>
                </div>
                <a class="view_more_items" href="{{route('vendor.all')}}">{{__('View More')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="product-4 product-m no-arrow" id="vendor_main_div"></div>
            </div>
        </div>
    </div>
</section>
@endif
<section class="section-b-space">
    <div class="container">
    @if(in_array("new_products", $actives))
        <div class="row d-none" id="new_products_wrapper">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{ __('New Products') }}</h2>
                </div>
            </div>
            <div class="col-12 theme-card">
                <div class="vendor-product common_card" id="new_product_main_div"></div>
            </div>
        </div>
    @endif
    @if(in_array("featured_products", $actives))
        <div class="row d-none mt-4" id="featured_products_wrapper">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{ __('Feature Product') }}</h2>
                </div>
            </div>
            <div class="col-12 theme-card">
                <div class="vendor-product common_card" id="feature_product_main_div"></div>
            </div>
        </div>
    @endif
    @if(in_array("best_sellers", $actives))
        <div class="row d-none mt-md-5 mt-4" id="bestseller_products_wrapper">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{ __('Best Seller') }}</h2>
                </div>
            </div>
            <div class="col-12 theme-card">
                <div class="vendor-product common_card" id="best_seller_main_div">

                </div>
            </div>
        </div>
    @endif
    @if(in_array("on_sale", $actives))
        <div class="row d-none mt-4" id="onsale_products_wrapper">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{ __('On Sale') }}</h2>
                </div>
            </div>
            <div class="col-12 theme-card">
                <div class="vendor-product common_card" id="on_sale_product_main_div"></div>
            </div>
        </div>
    @endif
    </div>
</section>
@if(in_array("brands", $actives))
<section class="section-b-space pt-0">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center d-flex align-items-center justify-content-between mb-4">
                <div class="title1">
                    <h2 class="title-inner1 mb-0">{{ __('Brands') }}</h2>
                </div>
            </div>
            <div class="col-md-12">
                <div class="slide-6 no-arrow" id="brand_main_div"></div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Modal -->
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
@endsection