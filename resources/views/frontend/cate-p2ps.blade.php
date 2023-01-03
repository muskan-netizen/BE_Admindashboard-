@extends('layouts.store', [
'title' => (!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->name : $category->slug,
'meta_title'=>(!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->meta_title:'',
'meta_keyword'=>(!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->meta_keyword:'',
'meta_description'=>(!empty($category->translation) && isset($category->translation[0])) ? $category->translation[0]->meta_description:'',
])

@section('css')
<style type="text/css">
.main-menu .brand-logo {display: inline-block;padding-top: 20px;padding-bottom: 20px;}.slick-track{margin-left: 0px;}.product-box .product-detail h4, .product-box .product-info h4{font-size: 16px;}
.main-fillter .side_fillter {
    background: transparent !important;
    border-top: 1px solid #D9D9D9;
    /* margin-top: 18px !important; */
    overflow-y: auto !important;
    border-right: 1px solid #D9D9D9;
    height: 600px;
    overflow-x: hidden !important;
}
.p2p-sidebar {
    padding: 20px;
}

.p2p-sidebar label.control-label {
    margin: 10px 0 10px;
    color: #000;
    font-size: 18px;
    font-weight: 500;
}
.p2p-sidebar .checkbox.checkbox-success {
    align-items: center;
    justify-content: flex-start;
    display: inline-flex;
    margin-bottom: 10px;
    width: 100%;
}
.p2p-sidebar .checkbox.checkbox-success label {
    margin-bottom: 0;
    padding-left: 10px;
}
.p2p-sidebar .form-check-inline label {
    margin: 0;
    padding-left: 10px;
}
.p2p-sidebar .form-check-inline {
    display: inline-flex;
    align-items: center;
    padding-left: 0;
    margin-right: 0;
    width: 49%;
}
.p2p-sidebar .form-check-inline.d-block {
    width: 100%;
}
.p2p-sidebar .custom-search {
    width: 100%;
    margin-bottom: 14px;
    background-color: white;
    border: 1px solid #aaa;
    border-radius: 4px;
    cursor: text;
    height: 40px;
}
.p2p-sidebar .select2-container{width:100% !important;}
.select2-container--default .select2-results>.select2-results__options li {
    display: block !important;
    color: #222;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered li {
    list-style: none;
    color: #000;
}
.custom_filtter {
    border-bottom: 1px solid #D9D9D9;
}
.collection-product-wrapper .product-top-filter{border:none !important;}
.custom_filtter ul {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
}
.custom_filtter ul li span {
    font-weight: 500;
    font-size: 20px;
    line-height: 24px;
    color: #0A0A0A;
    position: relative;
}
/* .custom_filtter ul li span:after {
    content: '';
    background: #D9D9D9;
    height: 1px;
    position: absolute;
    left: -49px;
    width: 37px;
    bottom: -13px;
} */
.custom_filtter ul li {
    flex-grow: 1;
    min-width: 0;
    max-width: 100%;
    line-height: 50px;
}
.custom_filtter ul li a.active {
    color: #E9248D;
}
 .custom_filtter ul li a.active:after {
    content: '';
    background: #E9248D;
    width: 50px;
    height: 2px;
    position: absolute;
    left: 0;
    bottom: -13px;
}
.custom_filtter ul li a {
    font-style: normal;
    font-weight: 400;
    font-size: 18px;
    line-height: 22px;
    color: #6F6F6F;
    position: relative;
}
.main-fillter {
    margin-top: 8px;
}
.product-image img{
    height: 200px !important;
    object-fit: cover;
}
.irs-to {
    left: 85% !important;
}
</style>
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/price-range.css')}}">
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@if(!empty($category))
@include('frontend.included_files.categories_breadcrumb')
@endif
<section class="section-b-space ratio_asos">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="top-banner-wrapper text-center">
                        @if(!empty($category->image) && $category->image['is_original'])
                            <div class="common-banner"><img alt="" class="blur-up lazyload" data-src="{{$category->image['image_fit'] . '1920/1080' . $category->image['image_path']}}" class="img-fluid blur-up lazyload"></div>
                        @endif
                        <div class="top-banner-content small-section">
                            <h4>{{ $category->translation_name }}</h4>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5 homepageSix">
                <div class="collection-filter col-lg-3 main-fillter">
                        <!-- <ul class="breadcrumb p-0 mb-2">
                            <li class="breadcrumb-item align-items-center"><a href="javascript:void(0)">Home <i class="fa fa-angle-right" aria-hidden="true"></i> <span>Pharmacy <i class="fa fa-angle-right" aria-hidden="true"></i>
                                </span><span class="active">Healthcare Device</span></a>
                            </li>
                        </ul> -->
                    <aside class="side_fillter">
                       
                    <!-- side-bar colleps block stat -->
                    @if( (count($category->brands) > 0) || (count($variantSets) > 0) )
                    <div class="collection-filter-block bg-transparent p-0 m-0">
                        <!-- <div class="collection-mobile-back">
                            <span class="filter-back d-lg-none d-inline-block">
                                <i class="fa fa-angle-left" aria-hidden="true"></i> {{__('Back')}}
                            </span>
                        </div> -->
                        @if(!empty($category->brands) && count($category->brands) > 0)
                        <div class="collection-collapse-block open mb-2">
                            <h3 class="collapse-block-title">{{__('Brand')}}</h3>
                            <div class="collection-collapse-block-content">
                                <div class="collection-brand-filter">
                                    @foreach($category->brands as $key => $val)
                                        <div class="custom-control custom-checkbox collection-filter-checkbox">
                                            <input type="checkbox" class="custom-control-input productFilter" fid="{{$val->id}}" used="brands" id="brd{{$val->id}}">
                                            @foreach($val->translation as $k => $v)
                                                <label class="custom-control-label" for="brd{{$val->id}}">{{$v->title}}</label>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(!empty($variantSets) && count($variantSets) > 0)
                          @foreach($variantSets as $key => $sets)
                            <div class="collection-collapse-block border-0 mb-2 open p-2">
                                <h3 class="collapse-block-title">{{$sets->title}}</h3>
                                <div class="collection-collapse-block-content">
                                    <div class="collection-brand-filter">
                                    <?php /*
                                    @if($sets->type == 2)
                                        @foreach($sets->options as $ok => $opt)
                                            <div class="chiller_cb small_label d-inline-block color-selector mt-2">
                                                <?php $checkMark = ($key == 0) ? 'checked' : ''; ?>
                                                <input class=" productFilter" type="checkbox" {{$checkMark}} id="Opt{{$key.'-'.$opt->id}}" fid="{{$sets->variant_type_id}}" used="variants" optid="{{$opt->id}}">
                                                {{-- custom-control-input --}}
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
                                    */ ?>
                                    </div>
                                </div>
                            </div>
                          @endforeach
                        @endif
                        <div class="collection-collapse-block border-0 mb-2 open p-2">
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
                    @endif
                    @php $show_new_Products = 0; @endphp
                    @if($show_new_Products && !empty($newProducts) && count($newProducts) > 0)
                    <div class="theme-card custom-inner-card">
                        <h5 class="title-border d-flex align-items-center justify-content-between">
                            <span>{{__('New Product')}}</span>
                            <span class="filter-back d-lg-none d-inline-block">
                                <i class="fa fa-angle-left" aria-hidden="true"></i> {{__('Back')}}
                            </span>
                        </h5>

                        <div class="offer-slider al">
                           
                                @foreach($newProducts as $newProds)
                                    <div class="col-12 p-0">
                                    @foreach($newProds as $new)
                                        <?php /*$imagePath = '';
                                        foreach ($new['media'] as $k => $v) {
                                            $imagePath = $v['image']['path']['image_fit'].'300/300'.$v['image']['path']['image_path'];
                                        }*/ ?>
                                        <div class=" common-product-box scale-effect mb-2">
                                            <a class="row  w-100" href="{{route('productDetail', [$new['vendor']['slug'],$new['url_slug']])}}">
                                                <div class="col-4">
                                                    <div class="img-outer-box position-relative  pr-0">
                                                        <img class="blur-up lazyload p-0" data-src="{{$new['image_url']}}" alt="">
                                                        <div class="pref-timing"></div>
                                                        {{--<i class="fa fa-heart-o fav-heart" aria-hidden="true"></i>--}}
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
                                                                <!-- <h3 class="mb-0 mt-2">{{ $new['translation_title'] }}</h3> -->
                                                                <p>{{$new['vendor']['name']}}</p>
                                                                <p class="pb-1">{{__('In')}} {{$new['category_name']}}</p>
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
                                    @endforeach
                                    </div>
                                @endforeach
                            
                        </div>
                    </div>
                    @endif

                    @php $getAdditionalPreference = getAdditionalPreference(['is_attribute']); @endphp
                    @if( isset($getAdditionalPreference['is_attribute']) && !empty($productAttributes))
                        <div class="p2p-sidebar" >
                            
                            <div class="row">
                                <div id="variantAjaxDiv" class="col-12 mb-2">
                                    <div class="row mb-2">
                                        
                                        @foreach($productAttributes as $vk => $var)
                                        @php $counter = 0; @endphp
                                        <div class="col-sm-12">
                                            <label class="control-label">{{$var->title??null}}</label>
                                        </div>
                                        <div class="col-sm-12">
                                            @if( !empty($var->type) && $var->type == 1 )
                                            {{-- <select class="form-control " name="free_delivery_roles[]" data-toggle="select2" multiple="multiple" placeholder="Select role..."> --}}
                                            <select name="" class="dropdown_select select2-multiple" data-key="{{$var->title}}" multiple>
                                                @foreach($var->option as $key => $opt)
                                                <option value="{{$opt->id}}">{{$opt->title}}</option>
                                                @endforeach
                                            </select>

                                            @else
                                            
                                            @foreach($var->option as $key => $opt)
                                            
                                            @if(isset($opt) && isset($var) && !empty($var->title) )

                                                @if( !empty($var->type) && $var->type == 3 )
                                                    <div class="form-check-inline">
                                                        <div class="attr_radio_{{$var->id}}">
                                                        <input type="radio" name="attribute[{{$var->id}}][option][{{$counter}}][value]" class="attr_radio"  
                                                        value="{{$opt->id}}" data-key="{{$var->title}}">
                                                        </div>
                                                        <label for="opt_vid_{{$opt->id}}">{{$opt->title}}</label>
                                                    </div>


                                                @elseif( !empty($var->type) && $var->type == 4 )
                                                    <div class="form-check-inline d-block">
                                                        <input type="textbox" class="text_field custom-search" name="attribute[{{$var->id}}][option][{{$counter}}][value]" value="" data-key="{{$var->title}}">
                                                    </div>
                                                @else
                                                    <div class="checkbox checkbox-success form-check-inline">
                                                        <input type="checkbox" name="" value="{{$opt->id}}" class="dynamic_checkbox" data-key="{{$var->title}}">
                                                        <option value=""></option>
                                                        <label for="opt_vid_{{$opt->id}}">{{$opt->title}}</label>
                                                    </div>
                                                @endif
                                                @php $counter++; @endphp
                                            @endif
                                            @endforeach
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    @endif
                    </aside>
                </div>
                
                <div class="collection-content col-lg-9 outter-fillter-data">
                    <div class="page-main-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="collection-product-wrapper">
                                    <div class="product-top-filter">
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="filter-main-btn">
                                                    <span class="filter-btn btn btn-theme">
                                                       {{__('New Product')}} >
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="product-filter-content">
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="displayProducts" id="category_products_filter">
                                        <div class="col-12 custom_filtter">
                                        <!-- <select name="order_type" id='order_type' class="sortingFilter p-1">
                                                <option value="">{{__('Sort By')}}</option>
                                                <option value="featured">{{_('Featured')}}</option>
                                                <option value="a_to_z">{{_('A to Z')}}</option>
                                                <option value="z_to_a">{{_('Z to A')}}</option>
                                                <option value="low_to_high">{{_('Cost : Low to High')}}</option>
                                                <option value="high_to_low">{{_('Cost : High to Low')}}</option>
                                                <option value="rating">{{_('Avg. Customer Review')}}</option>
                                                <option value="newly_added">{{_('Newest Arrivals')}}</option>
                                            </select> -->
                                        
                                            <ul>
                                                <input type="hidden" name="order_type" id='order_type' class="sortingFilter" />
                                                <li><span>{{__('Sort By:')}}</span></li>
                                                <li><a href="javascript:void(0)" class="sortingFilterOther" data-value="newly_added">{{__('Newest Arrivals')}}</a></li>
                                                <li><a href="javascript:void(0)" class="sortingFilterOther" data-value="featured">{{__('Featured')}}</a></li>
                                                <li><a href="javascript:void(0)" class="sortingFilterOther" data-value="a_to_z">{{__('A to Z')}}</a></li>
                                                <li><a href="javascript:void(0)" class="sortingFilterOther" data-value="z_to_a">{{__('Z to A')}}</a></li>
                                                <li><a href="javascript:void(0)" class="sortingFilterOther" data-value="low_to_high">{{__('Cost : Low to High')}}</a></li>
                                                <li><a href="javascript:void(0)" class="sortingFilterOther" data-value="high_to_low">{{__('Cost : High to Low')}}</a></li>
                                                <li><a href="javascript:void(0)" class="sortingFilterOther" data-value="rating">{{__('Avg. Customer Review')}}</a></li>
                                                
                                            </ul>
                                        </div>
                                        <div class="product-wrapper-grid">
                                            <div class="row margin-res">
                                              @if($listData->isNotEmpty())
                                                @foreach($listData as $key => $data)
                                                <?php /*$imagePath = $imagePath2 = '';
                                                $mediaCount = count($data->media);
                                                for ($i = 0; $i < $mediaCount && $i < 2; $i++) {
                                                    if($i == 0){
                                                        $imagePath = $data->media[$i]->image->path['image_fit'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                    }
                                                    $imagePath2 = $data->media[$i]->image->path['image_fit'].'300/300'.$data->media[$i]->image->path['image_path'];
                                                }*/ ?>
                                                <div class="col-xl-3 col-md-3 col-6 mt-3">
                                                    <a href="{{route('productDetail', [$data->vendor->slug,$data->url_slug])}}" target="_blank" class="product-box scale-effect mt-0 product-card-box position-relative al_box_third_template al">
                                                        <div class="product-image">
                                                            <img class="img-fluid blur-up lazyload" data-src="{{$data->image_url}}" alt="">
                                                        </div>
                                                        <div class="media-body align-self-center">
                                                            <div class="inner_spacing w-100">
                                                                <h3 class="d-flex align-items-center justify-content-between">
                                                                    <label class="mb-0"><b>{{ $data->translation_title }}</b></label>
                                                                    @if($client_preference_detail)
                                                                        @if($client_preference_detail->rating_check == 1)
                                                                            @if($data->averageRating > 0)
                                                                                <span class="rating">{{ number_format($data->averageRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                </h3>
                                                                <div class="product-description_list border-bottom">
                                                                    <span class="flag-discount">30% Off</span>
                                                                    <h6 class="mt-0 mb-1"><b>{{$data->vendor->name}}</b></h6>
                                                                    @if (strlen($data->translation_description) >= 65)
                                                                        <p title="{{$data->translation_description}}">{{ substr($data->translation_description, 0, 64)." ..." }}</p>
                                                                    @else
                                                                        <p>{{ $data->translation_description }}</p>
                                                                    @endif
                                                                    </div>
                                                                    @if($data->inquiry_only == 0)
                                                                        <h4 class="mt-1">{{Session::get('currencySymbol').' '.(decimal_format($data->variant_price * $data->variant_multiplier))}}</h4>
                                                                    @endif
                                                                
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                @endforeach
                                              @else
                                                <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{ __('No Product Found') }}</h5></div>
                                              @endif
                                            </div>
                                        </div>
                                        <div class="pagination pagination-rounded justify-content-end mb-0">
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
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script>
    @if(!empty($category->image) && $category->image['is_original'])
    $(document).ready(function() {
        $("body").addClass("homeHeader");
    });
    @endif
</script>
<script>
    $(document).ready(function() {
        $('.sortingFilter').val('newly_added');
        filterProducts();
    });
    $(document).on("change",".attr_radio", function() {
        
        var parentClass = $(this).parent().prop('className');
        
        var attr_radio_class = $(this).data('class');
        $("."+parentClass+" .attr_radio").prop('checked', false);
        $(this).prop('checked', true);
        
    });

    $('.js-range-slider').ionRangeSlider({
        type: 'double',
        grid: false,
        min: 0,
        max: 50000,
        from: 0,
        to: 50000,
        prefix: " "
    });
    var ajaxCall = 'ToCancelPrevReq';
    $('.js-range-slider').change(function(){
        filterProducts();
    });
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
    

    $(document).on('click','.sortingFilterOther',function(){
        var filterValue = $(this).data('value');
        $('#order_type').val(filterValue);
        filterProducts();
    });

    $(document).on('change','.sortingFilter',function(){
        filterProducts();
    });
    $('.js-range-slider').change(function(){
        filterProducts();
    });

    $('.attr_radio, .dynamic_checkbox, .dropdown_select, .text_field').change(function() {
        filterProducts();
    });

    function filterProducts(page='', limit=''){
        var brands = [];
        var variants = [];
        var options = [];
        var vendor_id =$("#vendor_id").val();
        var dropdown_options = {};
        var dynamic_options = {};
        var radio_option = {};
        var checkbox_option_arr = {};
        var text_field_search = {};
        $('.dropdown_select').each(function(i, obj) {
            dropdown_options[$(this).data('key')] = $(this).val();
        });
        $('.text_field').each(function(i, obj) {
            text_field_search[$(this).data('key')] = $(this).val();
        });

        $('.attr_radio').each(function(i, obj){
            if(this.checked) {
                radio_option[$(this).data('key')] = $(this).val();
            }
        });
        
        $('.dynamic_checkbox').each(function(i, obj){
            
            var dataType = typeof checkbox_option_arr[$(this).data('key')];
            if(dataType == 'undefined') {
                checkbox_option_arr[$(this).data('key')] = [];
            }
            if(this.checked) {
                checkbox_option_arr[$(this).data('key')].push($(this).val());
            }
        });
        
        dynamic_options['dropdown_options'] = dropdown_options;
        dynamic_options['radio_option'] = radio_option;
        dynamic_options['checkbox_option_arr'] = checkbox_option_arr;
        dynamic_options['text_field_search'] = text_field_search;
        
        // return false;
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
            "dynamic_options" : dynamic_options,
            "filter_type" : 1
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

    $('.select2-multiple').select2();
</script>
@endsection
