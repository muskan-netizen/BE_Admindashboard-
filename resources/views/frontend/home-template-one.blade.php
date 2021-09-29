@extends('layouts.store', ['title' => __('Home')])

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
    @include('layouts.store/left-sidebar-template-one')
</header>
<div class="offset-top"></div>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#login_modal">
  Launch demo modal
</button>
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
                    <a class="banner-img-outer" href="{{$url}}">
                        @endif
                            <img src="{{$banner->image['image_fit'] . '1500/600' . $banner->image['image_path']}}">
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
   
    <!-- Popular Brands Section Start From Here -->
    {{-- <section class="royo-recommends right-shape position-relative">
        <div class="container">
            <div class="row align-items-center">
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
    </section> --}}


</div>


<script type="text/template" id="vendors_template">
    <% _.each(vendors, function(vendor, k){%>

        <div>
            <a class="suppliers-box d-block px-2" href="{{route('vendorDetail')}}/<%= vendor.slug %>">
                <div class="suppliers-img-outer">
                    <img class="fluid-img mx-auto" src="<%= vendor.logo.image_fit %>200/200<%= vendor.logo['image_path'] %>" alt="">
                </div>
                <div class="supplier-rating">
                    <h6 class="mb-1"><%= vendor.name %></h6>
                    <p title="<%= vendor.categoriesList %>" class="vendor-cate border-bottom pb-1 mb-1 ellips"><%= vendor.categoriesList %></p>
                    <!-- <% if(vendor.timeofLineOfSightDistance != undefined){ %>
                    <div class="product-timing d-flex justify-content-between">
                        <small><i class="fa fa-map-marker"></i> <%= vendor.lineOfSightDistance %>km</small>
                        <small><i class="fa fa-clock-o"></i> <%= vendor.timeofLineOfSightDistance %>min</small>
                    </div>
                    <% } %> -->
                    <div class="product-timing">
                        <small title="<%= vendor.address %>" class="ellips d-block"><i class="fa fa-map-marker"></i> <%= vendor.address %></small>
                        <% if(vendor.timeofLineOfSightDistance != undefined){ %>
                            <ul class="timing-box mb-1">
                                <li>
                                    <small class="d-block"><img class="d-inline-block mr-1" src="{{ asset('front-assets/images/distance.png') }}" alt=""> <%= vendor.lineOfSightDistance %> km</small>
                                </li>
                                <li>
                                    <small class="d-block mx-1"><i class="fa fa-clock-o"></i> <%= vendor.timeofLineOfSightDistance %> min</small>
                                </li>
                            </ul>
                        <% } %>
                        <!-- <small class="ellips d-block"><i class="fa fa-map-marker"></i> <%= vendor.address %></small>
                        <small class="d-block">
                            <i class="fa fa-clock-o"></i> <%= vendor.timeofLineOfSightDistance %> min
                            <i class="fa fa-map-marker"></i> <%= vendor.lineOfSightDistance %> km
                        </small> -->
                    </div>
                    @if($client_preference_detail)
                        @if($client_preference_detail->rating_check == 1)
                            <% if(vendor.vendorRating > 0){%>
                                <ul class="custom-rating m-0 p-0">
                                    <% for(var i=0; i < 5; i++){ %>
                                        <% if(i <= vendor.vendorRating){
                                            var starFillClass = 'fa-star';
                                        }else{
                                            var starFillClass = 'fa-star-o';
                                        } %>
                                        <li><i class="fa <%= starFillClass %>" aria-hidden="true"></i></li>
                                    <% } %>
                                </ul>
                            <% } %>
                        @endif
                    @endif
                </div>
            </a>
        </div> 

    <% }); %>
</script>   

<script type="text/template" id="banner_template">
    <% _.each(brands, function(brand, k){%>
        <div>
            <a class="brand-box d-block black-box" href="<%= brand.redirect_url %>">
                <div class="brand-ing">
                    <img src="<%= brand.image.image_fit %>120/120<%= brand.image.image_path %>" alt="">
                </div>
                <h6><%= brand.translation_title %></h6>
            </a>
        </div>
    <% }); %>
</script>

<script type="text/template" id="products_template">
    <% _.each(products, function(product, k){ %>
        <a class="common-product-box scale-effect text-center" href="{{route('productDetail')}}/<%= product.url_slug %>">
            <div class="img-outer-box position-relative">
                <img src="<%= product.image_url %>" alt="">
                <!-- @if($client_preference_detail)
                    @if($client_preference_detail->rating_check == 1)
                        <% if(product.averageRating > 0){%>
                            <div class="rating-box">
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <span><%= product.averageRating %></span>
                            </div>
                        <% } %>
                    @endif
                @endif -->
                <!-- <div class="off-price">
                    20<sup>%</sup>    
                    <span>off</span>
                </div> -->
            </div>    
            <div class="media-body align-self-center">
                <div class="inner_spacing px-0">
                    <div class="product-description">
                        <h3 class="m-0"><%= product.title %></h3>
                        <p><%= product.vendor_name %></p>
                        <p class="border-bottom pb-1">In <%= product.category %></p>
                        <div class="d-flex align-items-center justify-content-between">
                            <b><% if(product.inquiry_only == 0) { %>
                                <%= product.price %>
                            <% } %></b>

                            @if($client_preference_detail)
                                @if($client_preference_detail->rating_check == 1)
                                    <% if(product.averageRating > 0){%>
                                        <div class="rating-box">
                                            <i class="fa fa-star" aria-hidden="true"></i>
                                            <span><%= product.averageRating %></span>
                                        </div>
                                    <% } %>
                                @endif
                            @endif  
                        </div>                       
                    </div>
                </div>
            </div>
        </a>
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
        @elseif($homePageLabel->slug == 'brands')  
                                
        <section class="popular-brands left-shape position-relative">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2 cw top-heading pr-0 text-center text-lg-left mb-3 mb-lg-0">
                    <h2 class="h2-heading">{{ $homePageLabel->slug == 'brands' ? getNomenclatureName('brands', true) :  __($homePageLabel->title) }}</h2>
                    <!-- <p>Check out the favorites among people.</p> -->
                </div>
                <div class="col-lg-10 cw">
                    <div class="brand-slider render_{{$homePageLabel->slug }}"  id="{{$homePageLabel->slug.$key}}">

                    </div>

                </div>
                </div>
            </div>
        </div>
        </section> 
        @elseif($homePageLabel->slug == 'vendors')
        <section class="suppliers-section pt-0 mb-3">
        <div class="container">
            <div class="row">
                <div class="col-12 top-heading d-flex align-items-center justify-content-between  mb-3">
                    <h2 class="h2-heading">{{ $homePageLabel->slug == 'vendors' ? getNomenclatureName('vendors', true) :  __($homePageLabel->title) }}</h2>
                    <a class="btn btn-solid" href="{{route('vendor.all')}}">See all</a>
                </div>
                <div class="col-12 px-0">
                    <div class="suppliers-slider render_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">

                       
                    </div>
                </div>
            </div>
        </div>
       </section> 
        @else
        <div class="container render_full_{{$homePageLabel->slug}}" id="{{$homePageLabel->slug.$key}}">
            <div class="row">
                <div class="col-12 top-heading d-flex align-items-center justify-content-between  mb-0">
                    <h2 class="h2-heading">{{ $homePageLabel->slug == 'vendors' ? getNomenclatureName('vendors', true) :  __($homePageLabel->title) }}</h2>
                    @if($homePageLabel->slug == 'vendors')
                    <a class="btn btn-solid" href="{{route('vendor.all')}}">{{__('View More')}}</a>
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