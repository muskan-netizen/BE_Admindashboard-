@extends('layouts.store', ['title' => $vendor->name])
@section('css')
<style type="text/css">
.main-menu .brand-logo{display:inline-block;padding-top:20px;padding-bottom:20px}.slick-track{margin-left:0}
.social-icon-list {width: 100%;max-width: 90%;}.social-icon-list .modal-body {text-align: center;}
.social-icon-list .modal-body .text-center a img {width: 40px;}
.social-icon-list .modal-body .text-center {display: inline-block;margin: 0px 6px;}
</style>
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/price-range.css')}}">
@endsection
@section('content')

<section class="section-b-space ratio_asos main_venders">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="top-banner-wrapper">
                        @if(!empty($vendor->banner))
                            <div class="common-banner text-center"><img alt="" src="{{$vendor->banner['proxy_url'] . '1920/1080' . $vendor->banner['image_path']}}" class="img-fluid blur-up lazyload"></div>
                        @endif
                        <div class="row mt-n4">
                            <div class="col-12">
                                <form action="">
                                    <div class="row">
                                        <div class="col-sm-12 text-left w-10 pl-5">
                                            <div class="file file--upload">
                                                <label>
                                                    <span class="update_pic border-0">
                                                    <img src="{{$vendor->logo['proxy_url'] . '200/200' . $vendor->logo['image_path']}}" alt="">
                                                    </span>
                                                </label>
                                            </div>
                                            <div class="name_location d-block py-0">
                                                <h4 class="mt-0 mb-1"><b>{{$vendor->name}}</b></h4>
                                            </div>
                                            <div class="">
                                                @if($vendor->is_show_vendor_details == 1)
                                                @if($vendor->email)
                                                    <a href="{{$vendor->email}}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->email}}"><i class="fa fa-envelope"></i></a>
                                                @endif
                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->address}}"><i class="fa fa-address-card mx-1"></i></a>
                                                @if($vendor->website)
                                                    <a href="{{http_check($vendor->website) }}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->website}}"><i class="fa fa-home"></i></a>
                                                @endif
                                                @endif
                                                @if($vendor->instagram_url)
                                                <a class="open-social-medialinks" href="javascript:void(0)" data-toggle="tooltip" title="Social Media Links"><i class="fa fa-globe"></i></a>
                                                    <!-- <a href="{{$vendor->instagram_url}}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->instagram_url}}"><i class="fa fa-instagram"></i></a> -->
                                                @endif
                                            </div>
                                            @if ($vendor->is_show_vendor_details == 1 && $vendor->order_min_amount > 0)
                                                <span class="badge badge-danger">{{ __('Minimum order value') }}{{ Session::get('currencySymbol') . decimal_format($vendor->order_min_amount) }}</span>
                                            @endif
                                        </div>
                                        @if($vendor->desc)
                                            <div class="col-md-12 text-center vender-peragraph mt-3">
                                                <p>{{$vendor->desc}}</p>
                                                <p>{!! $vendor->short_desc !!}</p>
                                            </div>
                                        @endif

                                        @php
                                        $checkSlot = findSlot('',$vendor->id,'');
                                        @endphp

                                        <div class="col-md-12 text-center">
                                        @if($is_vendor_closed == 1 && $checkSlot == 0)
                                        <p class="text-danger">{{getNomenclatureName('Vendors', true) . __(' is not accepting orders right now.')}}</p>
                                        @elseif($is_vendor_closed == 1 && $vendor->closed_store_order_scheduled == 1)
                                        <p class="text-danger">{{__('We are not accepting orders right now. You can schedule this for '). $checkSlot}}.</p>
                                        @endif
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <div class="container homepageSix">
            <div class="row mb-sm-5 mb-2 mt-5">
                <div class="collection-filter col-lg-3">
                    <div class="theme-card">
                        <h5 class="title-border d-flex align-items-center justify-content-between">
                            <span>{{__('New Product')}}</span>
                            <span class="filter-back d-lg-none d-inline-block">
                                <i class="fa fa-angle-left" aria-hidden="true"></i> {{__('Back')}}
                            </span>
                        </h5>
                        <div class="offer-slider al">
                            @if(!empty($newProducts) && count($newProducts) > 0)
                                @foreach($newProducts as $newProds)
                                    @foreach($newProds as $new)
                                    <div class="col-md-12 p-0">
                                    <?php /*$imagePath = '';
                                    foreach ($new['media'] as $k => $v) {
                                        $imagePath = $v['image']['path']['image_fit'] . '300/300' . $v['image']['path']['image_path'];
                                    }*/ ?>

                                    <div class="common-product-box scale-effect mb-2">
                                        <a class="row  w-100" href="{{route('productDetail', [$new['vendor']['slug'],$new['url_slug']])}}">
                                            <div class="col-4">
                                                <div class="img-outer-box position-relative">
                                                        <img class="blur-up lazyload" data-src="{{$new['image_url']}}" alt="">
                                                        <div class="pref-timing">
                                                            <!--<span>5-10 min</span>-->
                                                        </div>
                                                        {{-- <i class="fa fa-heart-o fav-heart" aria-hidden="true"></i> --}}
                                                    </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="media-body align-self-center ">
                                                    <div class="inner_spacing px-0">
                                                        <div class="product-description">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h6 class="card_title ellips">{{ $new['translation_title'] }}</h6>
                                                                <!--<span class="rating-number">2.0</span>-->
                                                            </div>
                                                            <!-- <h3 class="m-0">{{ $new['translation_title'] }}</h3> -->
                                                            <p>{{$new['vendor']['name']}}</p>
                                                            <p class="pb-1">In {{$new['category_name']}}</p>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <b>
                                                                    @if($new['inquiry_only'] == 0)
                                                                        <?php $multiply = $new['variant_multiplier']; ?>
                                                                        {{ Session::get('currencySymbol').' '.(decimal_format($new['variant_price'] * $multiply))}}
                                                                    @endif
                                                                </b>

                                                                <!-- @if($client_preference_detail)
                                                                    @if($client_preference_detail->rating_check == 1)
                                                                        @if($new['averageRating'] > 0)
                                                                            <div class="rating-box">
                                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                                <span>{{ $new['averageRating'] }}</span>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endif   -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                    @endforeach
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="collection-content col-lg-9">
                    <div class="page-main-content">
                        <div class="col-12">
                            <div class="collection-product-wrapper">
                                <div class="product-top-filter">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="filter-main-btn">
                                                <span class="filter-btn btn btn-theme">
                                                    <i class="fa fa-filter" aria-hidden="true"></i>{{__('Filter')}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="product-filter-content">
                                                <!-- <div class="collection-view">
                                                    <ul>
                                                        <li><i class="fa fa-th grid-layout-view"></i></li>
                                                        <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                    </ul>
                                                </div> -->
                                                {{-- <div class="collection-grid-view">
                                                    <ul>
                                                        <li><img src="{{asset('front-assets/images/icon/2.png')}}" alt="" class="product-2-layout-view"></li>
                                                        <li><img src="{{asset('front-assets/images/icon/3.png')}}" alt="" class="product-3-layout-view"></li>
                                                        <li><img src="{{asset('front-assets/images/icon/4.png')}}" alt="" class="product-4-layout-view"></li>
                                                        <li><img src="{{asset('front-assets/images/icon/6.png')}}" alt="" class="product-6-layout-view"></li>
                                                    </ul>
                                                </div> --}}
                                                {{-- <div class="product-page-per-view">
                                                    <?php $pagiNate = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 8; ?>
                                                    <select class="customerPaginate">
                                                        <option value="8" @if($pagiNate == 8) selected @endif>Show 8
                                                        </option>
                                                        <option value="12" @if($pagiNate == 12) selected @endif>Show 12
                                                        </option>
                                                        <option value="24" @if($pagiNate == 24) selected @endif>Show 24
                                                        </option>
                                                        <option value="48" @if($pagiNate == 48) selected @endif>Show 48
                                                        </option>
                                                    </select>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="displayCategories">
                                    <div class="categories-wrapper-grid">
                                        <div class="row margin-res">
                                        @if($listData->isNotEmpty())
                                            @foreach($listData as $key => $cate)
                                            <div class="col-md-3 col-6 col-grid-box mt-3">
                                                <div class="product-box">
                                                    <div class="img-wrapper">
                                                        <a href="{{ route('vendorCategoryProducts', [$vendor->slug, $cate['slug']]) }}">
                                                            <div class="category-image "><img alt="" src="{{$cate['icon']['proxy_url'] . '300/300' . $cate['icon']['image_path']}}" ></div>
                                                        </a>
                                                    </div>
                                                    <div class="product-detail">
                                                        <a href="{{ route('vendorCategoryProducts', [$vendor->slug, $cate['slug']]) }}">
                                                            <h5>{{$cate['translation_name']}}</h5>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{__('Details Not Available')}}</h5></div>
                                        @endif
                                        </div>
                                    </div>
                                    @if(count($listData))
                                    <div class="pagination pagination-rounded justify-content-end mb-0">
                                        {{ $listData->links() }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="social-media-links-modal" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-labelledby="repeat_itemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content social-icon-list">
                <div class="modal-header pb-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   @if(!empty($socialMediaUrls))
                   @foreach($socialMediaUrls as $url)
                   <div class="text-center">
                        @php
                            if($url->icon == 'facebook'){
                                $iconUrl = asset('assets/images/social-media/facebook.png');
                            }else if($url->icon == 'github'){
                                $iconUrl = asset('assets/images/social-media/github.png');
                            }else if($url->icon == 'reddit'){
                                $iconUrl = asset('assets/images/social-media/reddit.png');
                            }else if($url->icon == 'whatsapp'){
                                $iconUrl = asset('assets/images/social-media/whatsapp-img.png');
                            }else if($url->icon == 'instagram'){
                                $iconUrl = asset('assets/images/social-media/instagram.png');
                            }else if($url->icon == 'tumblr'){
                                $iconUrl = asset('assets/images/social-media/tumblr.png');
                            }else if($url->icon == 'twitch'){
                                $iconUrl = asset('assets/images/social-media/twitch.png');
                            }else if($url->icon == 'twitter'){
                                $iconUrl = asset('assets/images/social-media/twitter.png');
                            }else if($url->icon == 'pinterest'){
                                $iconUrl = asset('assets/images/social-media/pinterest.png');
                            }else if($url->icon == 'youtube'){
                                $iconUrl = asset('assets/images/social-media/youtube.png');
                            }else if($url->icon == 'snapchat'){
                                $iconUrl = asset('assets/images/social-media/snapchat.png');
                            }else if($url->icon == 'linkedin'){
                                $iconUrl = asset('assets/images/social-media/linkedin.png');
                            }
                        @endphp
                        <a target="_blank" href="{{$url->url}}"><img src="{{$iconUrl}}" alt=""></a>
                    </div>
                   @endforeach
                   @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js-script')
@if(!empty($vendor->banner))
<script>
    $(document).ready(function() {
        $("body").addClass("homeHeader");
    });
    $(document).on('click', '.open-social-medialinks', function(e) {
        $('#social-media-links-modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    $(document).on('click', '.show_subet_addeon', function(e) {
        e.preventDefault();
        var show_class = $(this).data("div_id_show");
        $(this).addClass("d-none");
        $("#" + show_class).removeClass("d-none");
    });
</script>
@endif
@endsection
