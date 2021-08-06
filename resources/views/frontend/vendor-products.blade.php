@extends('layouts.store', ['title' => $vendor->name])
@section('css')
<style type="text/css">
    .main-menu .brand-logo {
        display: inline-block;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/price-range.css')}}">
@endsection
@section('content')
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<style type="text/css">
    .productVariants .firstChild {
        min-width: 150px;
        text-align: left !important;
        border-radius: 0% !important;
        margin-right: 10px;
        cursor: default;
        border: none !important;
    }

    .product-right .color-variant li,
    .productVariants .otherChild {
        height: 35px;
        width: 35px;
        border-radius: 50%;
        margin-right: 10px;
        cursor: pointer;
        border: 1px solid #f7f7f7;
        text-align: center;
    }

    .productVariants .otherSize {
        height: auto !important;
        width: auto !important;
        border: none !important;
        border-radius: 0%;
    }

    .product-right .size-box ul li.active {
        background-color: inherit;
    }
    .product-box .product-detail h4, .product-box .product-info h4{
        font-size: 16px;
    }
</style>
<!-- section start -->
<section class="section-b-space ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 collection-filter">
                    <div class="collection-filter-block">
                        <div class="collection-mobile-back"><span class="filter-back"><i class="fa fa-angle-left" aria-hidden="true"></i>{{__('Back')}}</span></div>
                        <div class="collection-collapse-block open">
                            @if(!empty($brands) && count($brands) > 0)
                            <h3 class="collapse-block-title">brand</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">
                                    @foreach($brands as $key => $val)
                                    <div class="custom-control custom-checkbox collection-filter-checkbox">
                                        <input type="checkbox" class="custom-control-input productFilter" fid="{{$val->brand_id}}" used="brands" id="brd{{$val->brand_id}}">
                                        @foreach($val->brand->translation as $k => $v)
                                        <label class="custom-control-label" for="brd{{$val->brand_id}}">{{$v->title}}</label>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @if(!empty($variantSets) && count($variantSets) > 0)
                        @foreach($variantSets as $key => $sets)
                        <div class="collection-collapse-block border-0 open">
                            <h3 class="collapse-block-title"> {{$sets->variantDetail->varcategory->cate->slug .' > '. $sets->title}}</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">
                                    @if($sets->type == 2)
                                        @foreach($sets->options as $ok => $opt)
                                        <div class="chiller_cb small_label d-inline-block color-selector">
                                            <?php $checkMark = ($key == 0) ? 'checked' : ''; ?>
                                            <input class="custom-control-input productFilter" type="checkbox" {{$checkMark}} id="Opt{{$key.'-'.$opt->id}}" fid="{{$sets->variant_type_id}}" used="variants" optid="{{$opt->id}}">
                                            <label for="Opt{{$key.'-'.$opt->id}}"></label>
                                            @if(strtoupper($opt->hexacode) == '#FFF' || strtoupper($opt->hexacode) == '#FFFFFF')
                                            <span style="background: #FFFFFF; border-color:#000;" class="check_icon white_check"></span>
                                            @else
                                            <span class="check_icon" style="background:{{$opt->hexacode}}; border-color: {{$opt->hexacode}};"></span>
                                            @endif
                                        </div>
                                        @endforeach
                                    @else
                                        @foreach($sets->options as $ok => $opt)
                                        <div class="custom-control custom-checkbox collection-filter-checkbox">
                                            <input type="checkbox" class="custom-control-input productFilter" id="Opt{{$key.'-'.$opt->id}}" fid="{{$sets->variant_type_id}}" type="variants" optid="{{$opt->id}}">
                                            <label class="custom-control-label" for="Opt{{$key.'-'.$opt->id}}">{{$opt->title}}</label>
                                        </div>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                        <div class="collection-collapse-block border-0 open">
                            <h3 class="collapse-block-title">{{__('Price')}}</h3>
                            <div class="collection-collapse-block-content">
                                <div class="wrapper mt-3">
                                    <div class="range-slider">
                                        <input type="text" class="js-range-slider rangeSliderPrice" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="theme-card">
                        <h5 class="title-border">{{__('New Product')}}</h5>
                        <div class="offer-slider slide-1">
                            @if(!empty($newProducts) && count($newProducts) > 0)
                            @foreach($newProducts as $newProds)
                            <div>
                                @foreach($newProds as $new)
                                <?php $imagePath = '';
                                foreach ($new['media'] as $k => $v) {
                                    $imagePath = $v['image']['path']['proxy_url'] . '300/300' . $v['image']['path']['image_path'];
                                } ?>
                                <div class="media">
                                    <a href="{{route('productDetail', $new['url_slug'])}} "><img class="img-fluid blur-up lazyload" style="max-width: 200px;" src="{{$imagePath}}" alt=""></a>
                                    <div class="media-body align-self-center">
                                        <div class="inner_spacing">
                                            <a href="{{route('productDetail', $new['url_slug'])}}">
                                                <h3>{{ $new['translation_title'] }}</h3>
                                                @if($new['inquiry_only'] == 0)
                                                    <h4 class="mt-1">
                                                        <?php $multiply = $new['variant_multiplier']; ?>
                                                        {{ Session::get('currencySymbol').' '.(number_format($new['variant_price'] * $multiply,2))}}
                                                    </h4>
                                                @endif
                                                @if($client_preference_detail)
                                                    @if($client_preference_detail->rating_check == 1)
                                                    <div class="custom_rating">
                                                        @if($new['averageRating'] > 0)
                                                            @for($i = 1; $i < 6; $i++)
                                                                <i class="fa fa-star{{ ($i <= $new['averageRating']) ? ' filled ' : '' }}"></i>
                                                            @endfor
                                                        @endif
                                                    </div>
                                                    @endif
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <!-- side-bar banner end here -->
                </div>
                <div class="collection-content col">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="top-banner-wrapper mb-4">
                                    @if(!empty($vendor->banner))
                                        <div class="common-banner text-center"><img alt="" src="{{$vendor->banner['proxy_url'] . '1000/200' . $vendor->banner['image_path']}}" class="img-fluid blur-up lazyload"></div>
                                    @endif
                                    <div class="row mt-n5">
                                        <div class="col-12">
                                            <form action="">
                                                <div class="row">
                                                    <div class="col-sm-12 text-center">
                                                        <div class="file file--upload">
                                                            <label>
                                                                <span class="update_pic border-0">
                                                                <img src="{{$vendor->logo['proxy_url'] . '1000/200' . $vendor->logo['image_path']}}" alt="">
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <div class="name_location d-block py-0">
                                                            <h4 class="mt-0 mb-1"><b>{{$vendor->name}}</b></h4>
                                                        </div>
                                                        @if($vendor->is_show_vendor_details == 1)
                                                            <div class="">
                                                                @if($vendor->email)
                                                                    <a href="{{$vendor->email}}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->email}}"><i class="fa fa-envelope"></i></a>
                                                                @endif
                                                                <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->address}}"><i class="fa fa-address-card mx-1"></i></a>
                                                                @if($vendor->website)
                                                                    <a href="{{http_check($vendor->website) }}" target="_blank" data-toggle="tooltip" data-placement="bottom" title="{{$vendor->website}}"><i class="fa fa-home"></i></a>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if($vendor->desc)
                                                        <div class="col-md-12 text-center">
                                                            <p>{{$vendor->desc}}</p>
                                                        </div>                                                  
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn"><span class="filter-btn btn btn-theme"><i class="fa fa-filter" aria-hidden="true"></i>{{__('Filter')}}</span></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="product-filter-content border-left">
                                                    <div class="collection-view">
                                                        <ul>
                                                            <li><i class="fa fa-th grid-layout-view"></i></li>
                                                            <li><i class="fa fa-list-ul list-layout-view"></i></li>
                                                        </ul>
                                                    </div>
                                                    <div class="collection-grid-view">
                                                        <ul>
                                                            <li><img src="{{asset('front-assets/images/icon/2.png')}}" alt="" class="product-2-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/3.png')}}" alt="" class="product-3-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/4.png')}}" alt="" class="product-4-layout-view"></li>
                                                            <li><img src="{{asset('front-assets/images/icon/6.png')}}" alt="" class="product-6-layout-view"></li>
                                                        </ul>
                                                    </div>
                                                    <div class="product-page-per-view">
                                                        <?php $pnum = (Session::has('cus_paginate')) ? Session::get('cus_paginate') : 8; ?>
                                                        <select class="customerPaginate">
                                                            <option value="8" @if($pnum==8) selected @endif>Show 8
                                                            </option>
                                                            <option value="12" @if($pnum==12) selected @endif>Show 12 </option>
                                                            <option value="24" @if($pnum==24) selected @endif>Show 24
                                                            </option>
                                                            <option value="48" @if($pnum==48) selected @endif>Show 48
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="displayProducts">
                                        <div class="product-wrapper-grid">
                                            <div class="row margin-res">
                                                @if($listData->isNotEmpty())
                                                    @foreach($listData as $key => $data)
                                                    <?php $imagePath = $imagePath2 = '';
                                                    $mediaCount = count($data->media);
                                                    for ($i = 0; $i < $mediaCount && $i < 2; $i++) {
                                                        if ($i == 0) {
                                                            $imagePath = $data->media[$i]->image->path['proxy_url'] . '300/300' . $data->media[$i]->image->path['image_path'];
                                                        }
                                                        $imagePath2 = $data->media[$i]->image->path['proxy_url'] . '300/300' . $data->media[$i]->image->path['image_path'];
                                                    } ?>
                                                    <div class="col-xl-4 col-6 col-grid-box mt-3">
                                                        <a href="{{route('productDetail', $data->url_slug)}}" class="product-box scale-effect d-block mt-0">
                                                            <div class="product-image p-0">
                                                                <img class="img-fluid blur-up lazyload" src="{{$imagePath}}" alt="">
                                                            </div>
                                                            <div class="media-body align-self-center">
                                                                <div class="inner_spacing w-100">
                                                                    <h3 class="d-flex align-items-center justify-content-between">
                                                                        <label class="mb-0">{{ $data->translation_title }}</label> <span class="rating">4.2</span>
                                                                    </h3>
                                                                    <p>Lorem ipsum dolor sit amet.</p>
                                                                    @if($data['inquiry_only'] == 0)
                                                                        <h4 class="mt-1">{{Session::get('currencySymbol').(number_format($data->variant_price * $data->variant_multiplier,2))}}</h4>
                                                                    @endif
                                                                    @if($client_preference_detail)
                                                                        @if($client_preference_detail->rating_check == 1)  
                                                                        <div class="custom_rating mt-0">
                                                                            @if($data->averageRating > 0)
                                                                                @for($i = 1; $i < 6; $i++)
                                                                                    <i class="fa fa-star{{ ($i <= $data->averageRating) ? ' filled ' : '' }}"></i>
                                                                                @endfor
                                                                            @endif
                                                                        </div>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{__('No Product Found')}}</h5></div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="pagination pagination-rounded justify-content-end mb-0">
                                            {{ $listData->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('front-assets/js/rangeSlider.min.js')}}"></script>
<script src="{{asset('front-assets/js/my-sliders.js')}}"></script>
<script>
    $('.js-range-slider').ionRangeSlider({
        type: 'double',
        grid: false,
        min: "{{$range_products->last() ? $range_products->last()->price : 0}}",
        max: "{{$range_products->first() ? $range_products->first()->price : 1000}}",
        from: "{{$range_products->last() ? $range_products->last()->price : 0}}",
        to: "{{$range_products->first() ? $range_products->first()->price : 1000}}",
        prefix: ""
    });

    var ajaxCall = 'ToCancelPrevReq';
    $('.js-range-slider').change(function() {
        filterProducts();
    });

    $('.productFilter').click(function() {
        filterProducts();
    });

    function filterProducts() {
        var brands = [];
        var variants = [];
        var options = [];
        $('.productFilter').each(function() {
            var that = this;
            if (this.checked == true) {
                var forCheck = $(that).attr('used');
                if (forCheck == 'brands') {
                    brands.push($(that).attr('fid'));
                } else {
                    variants.push($(that).attr('fid'));
                    options.push($(that).attr('optid'));
                }
            }
        });
        var range = $('.rangeSliderPrice').val();

        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('vendorProductFilters', $vendor->id) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "brands": brands,
                "variants": variants,
                "options": options,
                "range": range
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(response) {
                $('.displayProducts').html(response.html);
            },
            error: function(data) {
                //location.reload();
            },
        });
    }
</script>

@endsection