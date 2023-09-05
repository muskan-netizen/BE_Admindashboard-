@extends('layouts.store', [
'title' => (!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->name : $category->slug,
'meta_title'=>(!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->meta_title:'',
'meta_keyword'=>(!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->meta_keyword:'',
'meta_description'=>(!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->meta_description:'',
])

@section('css')
<style type="text/css">
.main-menu .brand-logo {display: inline-block;padding-top: 20px;padding-bottom: 20px;}.slick-track{margin-left: 0px;}.product-box .product-detail h4, .product-box .product-info h4{font-size: 16px;}
</style>
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/price-range.css')}}">
@endsection
@section('content')
@if(!empty($category))
@include('frontend.included_files.categories_breadcrumb')
@endif
@php
$additionalPreference = getAdditionalPreference(['is_token_currency_enable']);
@endphp
<section class="section-b-space ratio_asos">
    <div class="collection-wrapper">
        <div class="container" id="divFirst">
            <div class="row">
                <div class="col-12">
                    <div class="top-banner-wrapper text-center">
                       
                       @include('frontend.vendor-category-topbar-banner')
                       
                        <div class="top-banner-content small-section">
                            <h4>{{ $category->translation_name }}</h4>
                            {{-- @if(!empty($category->childs) && count($category->childs) > 0)
                                <div class="row">
                                    <div class="col-12">

                                        <div class="slide-6 no-arrow">
                                            @foreach($category->childs->toArray() as $cate)
                                            <div class="category-block">
                                                <a href="{{route('categoryDetail', $cate['slug'])}}">
                                                    <div class="category-image"><img alt="" class="blur-up lazyload" data-src="{{$cate['icon']['image_fit'] . '300/300' . $cate['icon']['image_path']}}" ></div>
                                                </a>
                                                <div class="category-details">
                                                    <a href="{{route('categoryDetail', $cate['slug'])}}">
                                                        <h5>{{$cate['translation_name']}}</h5>
                                                    </a>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4 homepageSix">
                <div class="collection-filter col-lg-3 main-fillter">
                        <!-- <ul class="breadcrumb p-0 mb-2 mt-3">
                            <li class="breadcrumb-item align-items-center"><a href="javascript:void(0)">Home <i class="fa fa-angle-right" aria-hidden="true"></i> <span>Pharmacy <i class="fa fa-angle-right" aria-hidden="true"></i>
                                </span><span class="active">Healthcare Device</span></a>
                            </li>
                        </ul> -->


                    <!--- Left Sidebar filters -->
                        @include('frontend.category-left-sidebar')
                    <!---End Left Sidebar filters -->

                </div>
                
                <div class="collection-content col-lg-9 outter-fillter-data fillter_product">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn">
                                                    <span class="filter-btn btn btn-theme">
                                                       {{__('Filters')}} >
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
                                                            <li><img class="blur-up lazyload" data-src="{{asset('front-assets/images/icon/2.png')}}" alt="" class="product-2-layout-view"></li>
                                                            <li><img class="blur-up lazyload" data-src="{{asset('front-assets/images/icon/3.png')}}" alt="" class="product-3-layout-view"></li>
                                                            <li><img class="blur-up lazyload" data-src="{{asset('front-assets/images/icon/4.png')}}" alt="" class="product-4-layout-view"></li>
                                                            <li><img class="blur-up lazyload" data-src="{{asset('front-assets/images/icon/6.png')}}" alt="" class="product-6-layout-view"></li>
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
                                    <div class="displayProducts main_category" id="category_products_filter">
                                 
                                    @include('frontend.ajax.product-card')
                                        <div class="pagination pagination-rounded justify-content-end mb-0 page-m-20">
                                            @if(!empty($listData))
                                                {{ $listData->links() }}
                                            @endif
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
    <input type="hidden" id="vendor_id" value="{{ isset($vendor_id) ? $vendor_id : ''}}">
</section>
@endsection
@section('script')
<script src="{{asset('front-assets/js/rangeSlider.min.js')}}"></script>
<script src="{{asset('front-assets/js/my-sliders.js')}}"></script>
<script>
    @if(!empty($category->image) && $category->image['is_original'])
    $(document).ready(function() {
        $("body").addClass("homeHeader");
    });
    @endif
</script>
<script>
    $('.js-range-slider').ionRangeSlider({
        type: 'double',
        grid: false,
        min: 0,
        max: {{ $maxPrice??50000 }},
        from: 0,
        to: {{ $maxPrice??50000 }},
        prefix: " "
    });
    var ajaxCall = 'ToCancelPrevReq';
    
    $('.productFilter').click(function(){
        filterProducts();
    });

    $(document).on('click', '#category_products_filter .pagination a.page-link', function(e){
        e.preventDefault();
        var link = $(this).attr('href');
        var urlParams = new URL(link).searchParams;
        var page = urlParams.get('page');
        filterProducts(page);
    });

    $(document).on('change','.sortingFilter',function(){
        filterProducts();
    });
   
    var debounceTimeout;

    $('.js-range-slider ').on('input', function() {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(function() {
        filterProducts();
    }, 300);
    });

    function filterProducts(page='', limit=''){
        var brands = [];
        var variants = [];
        var options = [];
        var vendor_id =$("#vendor_id").val();
        $('.productFilter').each(function () {
            var that = this;
            if(this.checked == true){
                var forCheck = $(that).attr('used');
                if(forCheck == 'brands'){
                    brands.push($(that).attr('fid'));
                }else{
                    variants.push($(that).attr('fid'));
                    options.push($(that).attr('optid'));
                }
            }
        });
        var range = $('.rangeSliderPrice').val();
        var order_type = $('.sortingFilter').val();
        var ajaxData = {
            "_token": "{{ csrf_token() }}",
            "brands": brands,
            "vendor_id": vendor_id,
            "variants": variants,
            "options": options,
            "range": range,
            "order_type" : order_type,
        };

        if(limit != ''){
            ajaxData.limit = limit;
        }
        if(page != ''){
            ajaxData.page = page;
        }

        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('productFilters', $category->id) }}",
            data: ajaxData,
            beforeSend : function() {
                if(ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
                $('.spinner-overlay').show();
            },
            success: function(response) {
                $('.displayProducts').html(response.html);
            },
            complete: function() {
                $('.spinner-overlay').hide();
            },
            error: function (data) {
                //location.reload();
            },
        });
    }
    $(".page-link").on("click" ,function(){
        document.getElementById("divFirst").scrollIntoView();

});
</script>
@endsection
