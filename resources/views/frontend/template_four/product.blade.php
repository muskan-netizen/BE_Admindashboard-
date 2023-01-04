@extends('layouts.store', [
'title' => (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->title : '',
'meta_title'=>(!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->meta_title:'',
'meta_keyword'=>(!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->meta_keyword:'',
'meta_description'=>(!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->meta_description:'',
])
@php
$clientData = \App\Models\Client::select('socket_url')->first();
@endphp

@section('css')
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"/>
    <link rel="stylesheet" href="{{ asset('front-assets/css/swiper.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('front-assets/css/easyzoom.css') }}" />
    <link rel="stylesheet" href="{{ asset('front-assets/css/main.css') }}" /> -->

    <link rel="stylesheet" href="{{asset('css/jquery.exzoom.css')}}">
<style type="text/css">
    .main-menu .brand-logo{display:inline-block;padding-top:20px;padding-bottom:20px}.btn-disabled{opacity:.5;pointer-events:none}.fab{font:normal normal normal 14px/1 FontAwesome;font-size:inherit}
    #number{display:block}#exzoom{display:none}.exzoom .exzoom_btn a.exzoom_next_btn{right:-12px} .exzoom .exzoom_nav .exzoom_nav_inner{-webkit-transition:all .5s;-moz-transition:all .5s;transition:all .5s}

    @media screen and (max-width:768px){
        .exzoom .exzoom_zoom_outer{display:none}
        }
    .border-product.al_disc ol,.border-product.al_disc ul{padding-left:30px}.border-product.al_disc ol li,.border-product.al_disc ul li{display:list-item;padding-left:0;padding-top:8px;list-style-type:disc;font-size:14px}.border-product.al_disc ol li{list-style-type:decimal}.productVariants .firstChild{min-width:150px;text-align:left!important;border-radius:0!important;margin-right:10px;cursor:default;border:none!important}.product-right .color-variant li,.productVariants .otherChild{height:35px;width:35px;border-radius:50%;margin-right:10px;cursor:pointer;border:1px solid #f7f7f7;text-align:center}.productVariants .otherSize{height:auto!important;width:auto!important;border:none!important;border-radius:0}.product-right .size-box ul li.active{background-color:inherit}
.container-badge-value span{
    padding: 0px;
    margin-right: 10px;
    color: #6c757d;
    font-size: 15px;}

.container-badge{display: flex;margin-bottom: 10px;}
.value-badge{width:100px;font-weight: bold;}
.container-badge-value{width:calc( 100% - 100px);}


</style>

@endsection

@section('content')
@if(!empty($category))
@include('frontend.included_files.products_breadcrumb')
@endif
<!-- <div class="toast">
    <div class="toast-header">
      Toast Header
    </div>
    <div class="toast-body">
      Some text inside the toast body
    </div>
  </div> -->
@php
$checkSlot = findSlot('',$product->vendor->id,'');
@endphp
<section class="section-b-space FiveTemplate alSingleProducts">
    <div class="collection-wrapper al">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-sm-left">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <span>{!! \Session::get('success') !!}</span>
                            </div>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <span>{!! \Session::get('error') !!}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="container-fluid">
                        {{--<div class="row">
                            <div class="col-xl-12">
                                <div class="filter-main-btn mb-2">
                                    <span class="filter-btn">
                                        <i class="fa fa-filter" aria-hidden="true"></i> filter
                                    </span>
                                </div>
                            </div>
                        </div>--}}
                        <div class="row no-gutters">
                            <div class="col-lg-5 @php if(count($product->media) == 0){  echo 'd-block'; } @endphp ">
                                {{-- <div class="product__carousel">
                                    <div class="gallery-parent">
                                        @php
                                            if($product->variant->first()->media->isNotEmpty()){
                                                $product->media = $product->variant->first()->media;
                                            }

                                            if($product->media->isEmpty()){
                                                $arr = [
                                                    'image' => (object)[
                                                        'path' => [
                                                            'image_fit' => \Config::get('app.FIT_URl'),
                                                            'image_path' => \Config::get('app.IMG_URL2').'/'.\Storage::disk('s3')->url('default/default_image.png').'@webp'
                                                        ]
                                                    ]
                                                ];
                                                $coll = (object)collect($arr)->all();
                                                $product->media[] = $coll;
                                            }
                                        @endphp

                                        <div class="swiper-container gallery-top">
                                            <div class="swiper-wrapper">
                                            @if(!empty($product->media))
                                                @foreach($product->media as $k => $image)
                                                    @php
                                                        if(isset($image->pimage)){
                                                            $img = $image->pimage->image;
                                                        }else{
                                                            $img = $image->image;
                                                        }
                                                    @endphp
                                                    <div class="swiper-slide easyzoom easyzoom--overlay">
                                                        <a href="{{$img->path['image_fit'].'600/600'.$img->path['image_path']}}">
                                                        <img class="blur-up lazyload" data-src="{{$img->path['image_fit'].'600/600'.$img->path['image_path']}}" alt="">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            @endif
                                            </div>

                                            <div class="swiper-button-next swiper-button-white"></div>
                                            <div class="swiper-button-prev swiper-button-white"></div>
                                        </div>
                                        <div class="swiper-container gallery-thumbs">
                                            <div class="swiper-wrapper">
                                                @if(!empty($product->media))
                                                    @foreach($product->media as $k => $image)
                                                    @php
                                                        if(isset($image->pimage)){
                                                            $img = $image->pimage->image;
                                                        }else{
                                                            $img = $image->image;
                                                        }
                                                    @endphp
                                                    <div class="swiper-slide">
                                                        <img class="blur-up lazyload" data-src="{{$img->path['image_fit'].'300/300'.$img->path['image_path']}}" alt="">
                                                    </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="exzoom hidden w-100" id="exzoom">
                                    <div class="exzoom_img_box mb-2">
                                        <ul class='exzoom_img_ul'>
                                        @if(!empty($product->media) && count($product->media) > 0)
                                                @foreach($product->media as $k => $image)
                                                        @php
                                                            if(isset($image->pimage)){
                                                                $img = $image->pimage->image;
                                                            }else{
                                                                $img = $image->image;
                                                            }
                                                        @endphp
                                                @endforeach
                                                @if(!is_null($img))
                                                <img id="main_image" src="{{@$img->path['image_fit'].'1000/1000'.@$img->path['image_path']}}" />
                                                @endif
                                                @else
                                                        
                                                    <img id="main_image" class="blur-up lazyload" data-src="{{loadDefaultImage()}}" alt="">
                                                        
                                            @endif
                                        </ul>
                                    </div>
                                    @if(count($product->media) > 1)
                                    <div class="exzoom_nav"></div>
                                    <p class="exzoom_btn">
                                        <a href="javascript:void(0);" class="exzoom_prev_btn">
                                            < </a> <a href="javascript:void(0);" class="exzoom_next_btn"> >
                                        </a>
                                    </p>
                                    @endif
                                </div>
                                <div id="product_variant_quantity_wrapper" class="mt-3">
                                        @if($product->inquiry_only == 0)
                                        <div class="product-description border-product pb-0">
                                            <h6 class="product-title mt-0">{{__('Quantity')}}:
                                                @if($product->has_inventory && !$product->variant[0]->quantity > 0 && $product->sell_when_out_of_stock != 1)
                                                    <span id="outofstock" style="color: red;">{{ __('Out of Stock')}}</span>
                                                @else
                                                @php
                                                $product_quantity_in_cart = $product_in_cart->quantity??0;
                                                @endphp
                                                <input type="hidden" value="{{$product->has_inventory}}" id="hasInventory">
                                                <input type="hidden" id="instock" value="{{ ($product->variant[0]->quantity - $product_quantity_in_cart)}}">
                                                @endif
                                            </h6>
                                            @if(!$product->has_inventory || $product->variant[0]->quantity > 0 || $product->sell_when_out_of_stock == 1)
                                            @if($product->minimum_order_count > 1)
                                            {{-- <p class="mb-1 product_price">   {{__('Minimum Quantity') }} : {{ $product->minimum_order_count }} </p>
                                            <p class="mb-1 product_price">   {{__('Batch') }} : {{ $product->batch_count }} </p> --}}
                                            @endif
                                            <div class="qty-box mb-3">
                                                <div class="input-group">
                                                    <span class="input-group-prepend">
                                                        <button type="button" class="btn quantity-left-minus" data-type="minus" data-field="" data-batch_count={{$product->batch_count}} data-minimum_order_count={{$product->minimum_order_count}}><i class="ti-minus"></i>
                                                        </button>
                                                    </span>
                                                    <input type="text" name="quantity"  onkeypress="return event.charCode > 47 && event.charCode < 58;" pattern="[0-9]{5}" id="quantity" class="form-control input-qty-number quantity_count"  value="{{$product->minimum_order_count??1}}" data-minimum_order_count={{$product->minimum_order_count}}>
                                                    <span class="input-group-prepend quant-plus">
                                                        <button type="button" class="btn quantity-right-plus " data-type="plus" data-field="" data-batch_count={{$product->batch_count}} data-minimum_order_count={{$product->minimum_order_count}}>
                                                            <i class="ti-plus"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endif

                                    </div>
                            </div>

                            <div class="@php if(!empty($product->media) && count($product->media) > 0){ echo 'col-lg-7'; } else { echo 'col-lg-7'; } @endphp rtl-text">
                                <div class="product-right inner_spacing pl-sm-3 p-0">
                                    <h2 class="mb-0">
                                        {{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->title : ''}}
                                    </h2>
                                    <h6 class="sold-by">
                                        <b> <img class="blur-up lazyload" data-src="{{$product->vendor->logo['image_fit']}}200/200{{$product->vendor->logo['image_path']}}" alt="{{$product->vendor->Name}}"></b> <a href="{{ route('vendorDetail', $product->vendor->slug) }}"><b> {{$product->vendor->name}} </b></a>
                                    </h6>
                                    @if($client_preference_detail)
                                        @if($client_preference_detail->rating_check == 1)
                                            @if($product->averageRating > 0)
                                                <span class="rating">{{ number_format($product->averageRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                            @endif
                                        @endif
                                    @endif
                                    <div class="description_txt mt-3">
                                        <p>{{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->meta_description : ''}}</p>
                                    </div>
                                    <input type="hidden" name="available_product_variant" id="available_product_variant" value="{{$product->variant[0]->id}}">
                                    <input type="hidden" name="start_time" id="start_time" value="">
                                    <input type="hidden" name="end_time" id="end_time" value="">
                                    <div id="product_variant_wrapper">
                                        <input type="hidden" name="variant_id" id="prod_variant_id" value="{{$product->variant[0]->id}}">
                                        @if($product->inquiry_only == 0)
                                            <h3 id="productPriceValue" class="mb-md-3">
                                                <b class="mr-1">{{Session::get('currencySymbol')}}<span class="product_fixed_price">{{number_format($product->variant[0]->price * $product->variant[0]->multiplier,2,".",",")}}</span></b>
                                                @if($product->variant[0]->compare_at_price > 0 )
                                                    <span class="org_price">{{Session::get('currencySymbol')}}<span class="product_original_price">{{number_format($product->variant[0]->compare_at_price * $product->variant[0]->multiplier,2,".",",")}}</span></span>
                                                @endif
                                            </h3>
                                        @endif
                                    </div>
                                    <div id="product_variant_options_wrapper">
                                        @if(!empty($product->variantSet))
                                            @php
                                                $selectedVariant = isset($product->variant[0]) ? $product->variant[0]->id : 0;
                                                if($product->minimum_order_count > 0)
                                                $product->minimum_order_count = $product->minimum_order_count;
                                                else
                                                $product->minimum_order_count = 1;
                                            @endphp
                                            @foreach($product->variantSet as $key => $variant)
                                                @if($variant->type == 1 || $variant->type == 2)
                                                <div class="size-box">
                                                    <ul class="productVariants">
                                                        <li class="firstChild">{{$variant->title}}</li>
                                                        <li class="otherSize">
                                                            @foreach($variant->option2 as $k => $optn)
                                                            <?php $var_id = $variant->variant_type_id;
                                                            $opt_id = $optn->variant_option_id;
                                                            $checked = ($selectedVariant == $optn->product_variant_id) ? 'checked' : '';
                                                            ?>
                                                            <label class="radio d-inline-block txt-14 mr-2">{{$optn->title}}
                                                                <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}" vid="{{$var_id}}" optid="{{$opt_id}}" value="{{$opt_id}}" type="radio" class="changeVariant dataVar{{$var_id}}" {{$checked}} data-cartCheck="{{(($checkSlot == 0  && $vendor_info->is_vendor_closed == 1) || ($optn->quantity <= $product_quantity_in_cart && $product->has_inventory) || ($optn->quantity < $product->minimum_order_count)) ? 1 : 0}}">
                                                                <span class="checkround"></span>
                                                            </label>
                                                            @endforeach
                                                        </li>
                                                    </ul>
                                                </div>
                                                @else
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <div id="variant_response">
                                        <span class="text-danger mb-2 mt-2"></span>
                                    </div>
                                    <div class="border-product al_disc">
                                        <h6 class="product-title mb-2">{{__('Product Details')}}</h6>
                                        
                                        {!!(!empty($product->translation) && isset($product->translation[0])) ?
                                            $product->translation[0]->body_html : ''!!}

                                        @if( p2p_module_status() )
                                            @if( !empty($attr_array) )
                                                @foreach($attr_array as $attr_key => $attr_val)
                                                    <div class="container-badge">
                                                        <div class="value-badge">{{ $attr_key }} : </div>
                                                        @if( !empty($attr_val) )
                                                            <div class="container-badge-value">
                                                                @foreach($attr_val as $inn_key => $inn_val)
                                                                    <span>{{$inn_val['value']}}</span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        
                                                    </div>
                                                @endforeach
                                            @endif

                                            {{-- Chat Button --}}
                                            @if($clientData->socket_url !='' )
                                                <hr>
                                                <h6 class="sold-by">
                                                    <span>Sold by : </span>
                                                    <b> <img class="blur-up lazyload" data-src="{{$product->vendor->logo['image_fit']}}200/200{{$product->vendor->logo['image_path']}}" alt="{{$product->vendor->Name}}"></b> <a href="{{ route('vendorDetail', $product->vendor->slug) }}"><b> {{$product->vendor->name}} </b></a>
                                                    <a class="start_p2p_chat chat-icon btn btn-solid"  data-vendor_order_id="" data-vendor_id="{{ $product->vendor->id }}" data-orderid="" data-order_id="" data-product_id="{{ $product->id }}">{{__('Chat')}}</a>
                                                </h6>
                                            @endif
                                    @endif
                                    </div>
                                    <div class="border-product">
                                        <h6 class="product-title">{{__('Share It')}}</h6>
                                        <div class="product-icon w-100">
                                            <!-- <ul class="product-social"> -->
                                                {!! $shareComponent !!}
                                                <!-- <li><a href="#"><i class="fa fa-twitter"></i></a></li> -->
                                                <!-- <li><a href="#"><i class="fa fa-facebook"></i></a></li> -->
                                                <!-- <li><a href="#"><i class="fa fa-google-plus"></i></a></li> -->
                                                <!-- <li><a href="#"><i class="fa fa-instagram"></i></a></li> -->
                                            <!-- </ul>   -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 pl-0">
                                @if(!empty($product->addOn) && $product->addOn->count() > 0)
                                    <div class="border-product">
                                        <h6 class="product-title">{{ __('Addon List')}}</h6>

                                        <div id="addon-table">
                                            @foreach($product->addOn as $row => $addon)
                                                <div class="addon-product">
                                                    <h4 addon_id="{{$addon->addon_id}}" class="header-title productAddonSet mb-2">{{$addon->title}}
                                                        @php
                                                            $min_select = '';
                                                            $minText = __('Minimum');
                                                            $maxText = __('Maximum');
                                                            $andText = __('and');
                                                            if($addon->min_select > 0){
                                                                $min_select = $minText.' '.$addon->min_select;
                                                            }
                                                            $max_select = '';
                                                            if($addon->max_select > 0){
                                                                $max_select = $maxText.' '.$addon->max_select;
                                                            }
                                                            if( ($min_select != '') && ($max_select != '') ){
                                                                $min_select = $min_select.' '.$andText.' ';
                                                            }
                                                        @endphp
                                                        @if( ($min_select != '') || ($max_select != '') )
                                                            <small>({{__($min_select).__($max_select)}} {{ __('Selections Allowed')}})</small>
                                                        @endif
                                                    </h4>

                                                    <div class="productAddonSetOptions" data-min="{{$addon->min_select}}" data-max="{{$addon->max_select}}" data-addonset-title="{{$addon->title}}">
                                                        @foreach($addon->setoptions as $k => $option)
                                                        <div class="checkbox checkbox-success form-check-inline mb-1">
                                                            <label class="checkboxAl" for="inlineCheckbox_{{$row.'_'.$k}}" data-toggle="tooltip" data-placement="top" title="{{$option->title .' ('.Session::get('currencySymbol').decimal_format($option->price).')' }}">
                                                                <input type="checkbox" id="inlineCheckbox_{{$row.'_'.$k}}" class="productDetailAddonOption" name="addonData[$row][]" addonId="{{$addon->addon_id}}" addonOptId="{{$option->id}}" data-price="{{$option->price * $option->multiplier}}" data-fixed_price="{{decimal_format($product->variant[0]->price * $product->variant[0]->multiplier)}}" data-original_price="{{decimal_format($product->variant[0]->compare_at_price * $product->variant[0]->multiplier)}}">
                                                                {{$option->title .' ('.Session::get('currencySymbol').decimal_format($option->price * $option->multiplier).')' }}
                                                                <span class="checkmark"></span>
                                                            </label>

                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>


                                        {{--<table class="table table-centered table-nowrap table-striped d-none" id="addon-table">
                                            <tbody>
                                                @foreach($product->addOn as $row => $addon)
                                                <tr>
                                                    <td>
                                                        <h4 addon_id="{{$addon->addon_id}}" class="header-title productAddonSet">{{$addon->title}}
                                                            @php
                                                                $min_select = '';
                                                                if($addon->min_select > 0){
                                                                    $min_select = 'Minimum '.$addon->min_select;
                                                                }
                                                                $max_select = '';
                                                                if($addon->max_select > 0){
                                                                    $max_select = 'Maximum '.$addon->max_select;
                                                                }
                                                                if( ($min_select != '') && ($max_select != '') ){
                                                                    $min_select = $min_select.' and ';
                                                                }
                                                            @endphp
                                                            @if( ($min_select != '') || ($max_select != '') )
                                                                <small>({{__($min_select).__($$max_select)}} {{ __('Selections Allowed')}})</small>
                                                            @endif
                                                        </h4>
                                                    </td>
                                                </tr>
                                                <tr class="productAddonSetOptions" data-min="{{$addon->min_select}}" data-max="{{$addon->max_select}}" data-addonset-title="{{$addon->title}}">
                                                    <td>
                                                        @foreach($addon->setoptions as $k => $option)
                                                        <div class="checkbox checkbox-success form-check-inline">
                                                            <input type="checkbox" id="inlineCheckbox_{{$row.'_'.$k}}" class="productDetailAddonOption" name="addonData[$row][]" addonId="{{$addon->addon_id}}" addonOptId="{{$option->id}}">
                                                            <label class="pl-2" for="inlineCheckbox_{{$row.'_'.$k}}">
                                                                {{$option->title .' ($'.decimal_format($option->price).')' }}</label>
                                                        </div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>--}}
                                    </div>
                                    @endif
                                    <div class="product-buttons">
                                        @if(!$product->has_inventory || $product->variant[0]->quantity > 0  || $product->sell_when_out_of_stock == 1)
                                        @if($is_inwishlist_btn && $is_available)
                                        <button type="button" class="btn btn-solid addWishList mr-2" proSku="{{$product->sku}}" remWishlist="{{ __('Remove From Wishlist') }}" addWishlist="{{ __('Add To Wishlist') }}">
                                            <i class="ti-heart"></i>
                                            {{ (isset($product->inwishlist) && (!empty($product->inwishlist))) ? __('Remove From Wishlist') : __('Add To Wishlist') }}
                                        </button>
                                        @endif
                                        @if($product->inquiry_only == 0)
                                        @php
                                        if($product->sell_when_out_of_stock == 1 && $product->variant[0]->quantity == 0){
                                            $product_quantity_in_cart = 1;
                                            $product->variant[0]->quantity = 2;
                                        }
                                        else
                                        $product_quantity_in_cart = $product_in_cart->quantity??0;


                                        @endphp
                                        @if($is_available == 1 )
                                            <a href="#" data-toggle="modal" data-target="#addtocart" class="btn btn-solid addToCart  {{ (($checkSlot == 0  && $vendor_info->is_vendor_closed == 1) || ($product->has_inventory && $product->variant[0]->quantity <= $product_quantity_in_cart) || ($product->has_inventory && $product->variant[0]->quantity < $product->minimum_order_count)) ? 'btn-disabled' : '' }}" ><i class="ti-shopping-cart"></i> {{__('Add To Cart')}}</a>
                                        @endif

                                            @if($vendor_info->is_vendor_closed == 1 && $checkSlot == 0)
                                            <p class="text-danger">{{getNomenclatureName('Vendors', true) . __(' is not accepting orders right now.')}}</p>
                                            @elseif($vendor_info->is_vendor_closed == 1 && $vendor_info->closed_store_order_scheduled == 1)
                                            <p class="text-danger">{{ __('We are not accepting orders right now. You can schedule this for '). $checkSlot}}.</p>
                                            @endif
                                        @else
                                            <a href="#" data-toggle="modal" data-target="#inquiry_form" class="btn btn-solid inquiry_mode">{{ __('Inquire Now')}}</a>
                                        @endif
                                        @endif
                                    </div>
                            </div>

                            <div class="col-12 pl-0">
                                @if($client_preference_detail && $client_preference_detail->rating_check == 1)
                                <section class="tab-product m-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-lg-12">
                                            <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                                <!-- <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-toggle="tab" href="#top-home" role="tab" aria-selected="true"><i class="icofont icofont-ui-home"></i>{{__('Description')}}</a>
                                                    <div class="material-border"></div>
                                                </li> -->
                                                <!-- <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-toggle="tab"
                                                        href="#top-profile" role="tab" aria-selected="false"><i
                                                            class="icofont icofont-man-in-glasses"></i>Details</a>
                                                    <div class="material-border"></div>
                                                </li> -->
                                                @if($client_preference_detail && $client_preference_detail->rating_check == 1)
                                                <li class="nav-item"><a class="nav-link active" id="review-top-tab" data-toggle="tab" href="#top-review" role="tab" aria-selected="false"><i class="icofont icofont-contacts"></i>{{__('Ratings & Reviews')}}</a>
                                                    <div class="material-border"></div>
                                                </li>
                                                @endif
                                            </ul>
                                            <div class="tab-content nav-material" id="top-tabContent">
                                                <div class="tab-pane fade" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                                                    <p>{!! (!empty($product->translation) && isset($product->translation[0])) ?
                                                        $product->translation[0]->body_html : ''!!}</p>
                                                </div>
                                                <div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="profile-top-tab">
                                                    <p>{!! (!empty($product->translation) && isset($product->translation[0])) ?
                                                        $product->translation[0]->body_html : ''!!}</p>
                                                </div>
                                                <div class="tab-pane show active" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                                                    @forelse ($rating_details as $rating)
                                                    <div v-for="item in list" class="w-100 d-flex justify-content-between mb-3">
                                                        <div class="review-box">

                                                            <div class="review-author mb-1">
                                                                <p><strong>{{$rating->user->name??'NA'}}</strong> - <i class="fa fa-star{{ $rating->rating >= 1 ? '' : '-o' }}" aria-hidden="true"></i>
                                                                    <i class="fa fa-star{{ $rating->rating >= 2 ? '' : '-o' }}" aria-hidden="true"></i>
                                                                    <i class="fa fa-star{{ $rating->rating >= 3 ? '' : '-o' }}" aria-hidden="true"></i>
                                                                    <i class="fa fa-star{{ $rating->rating >= 4 ? '' : '-o' }}" aria-hidden="true"></i>
                                                                    <i class="fa fa-star{{ $rating->rating >= 5 ? '' : '-o' }}" aria-hidden="true"></i>
                                                                </p>
                                                            </div>
                                                            <div class="review-comment">
                                                                <p>{{$rating->review??''}}</p>
                                                            </div>
                                                            <div class="row review-wrapper">
                                                                @if(isset($rating->reviewFiles))
                                                                @foreach ($rating->reviewFiles as $files)
                                                                <a target="_blank" href="{{$files->file['image_fit'].'900/900'.$files->file['image_path']}}" class="col review-photo mt-2 lightBoxGallery" data-gallery="">
                                                                    <img class="blur-up lazyload" data-src="{{$files->file['image_fit'].'300/300'.$files->file['image_path']}}">
                                                                </a>
                                                                @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="review-date mt-2">
                                                                <time> {{ $rating->time_zone_created_at->diffForHumans();}} </time>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @empty
                                                    <p>{{__('No Result Found')}}</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/template" id="variant_image_template">
    <% if(variant.media != '') { %>
        <div class="swiper-container gallery-top">
            <div class="swiper-wrapper">
                <% _.each(variant.media, function(img, key){ %>
                    <div class="swiper-slide easyzoom easyzoom--overlay">
                        <a href="<%= img.pimage.image.path['image_fit'] %>600/600<%= img.pimage.image.path['image_path'] %>">
                        <img class="blur-up lazyload" data-src="<%= img.pimage.image.path['image_fit'] %>600/600<%= img.pimage.image.path['image_path'] %>" alt="">
                        </a>
                    </div>
                <% }); %>
            </div>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-white"></div>
            <div class="swiper-button-prev swiper-button-white"></div>
        </div>
        <div class="swiper-container gallery-thumbs">
            <div class="swiper-wrapper">
                <% _.each(variant.media, function(img, key){ %>
                    <div class="swiper-slide">
                        <img class="blur-up lazyload" data-src="<%= img.pimage.image.path['image_fit'] %>300/300<%= img.pimage.image.path['image_path'] %>" alt="">
                    </div>
                <% }); %>
            </div>
        </div>
    <% }else{ %>
        <div class="swiper-container gallery-top">
            <div class="swiper-wrapper">
                <% _.each(variant.product.media, function(img, key){ %>
                    <% if(img.image != null) {%>
                        <div class="swiper-slide easyzoom easyzoom--overlay">
                            <a href="<%= img.image.path['image_fit'] %>600/600<%= img.image.path['image_path'] %>">
                            <img class="blur-up lazyload" data-src="<%= img.image.path['image_fit'] %>600/600<%= img.image.path['image_path'] %>" alt="">
                            </a>
                        </div>
                    <% }; %>
                <% }); %>
            </div>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-white"></div>
            <div class="swiper-button-prev swiper-button-white"></div>
        </div>
        <div class="swiper-container gallery-thumbs">
            <div class="swiper-wrapper">
                <% _.each(variant.product.media, function(img, key){ %>
                    <% if(img.image != null) {%>
                        <div class="swiper-slide">
                            <img class="blur-up lazyload" data-src="<%= img.image.path['image_fit'] %>300/300<%= img.image.path['image_path'] %>" alt="">
                        </div>
                    <% }; %>
                <% }); %>
            </div>
        </div>
    <% } %>
</script>
<script type="text/template" id="variant_template">
    <input type="hidden" name="variant_id" id="prod_variant_id" value="<%= variant.id %>">
    <% if(variant.product.inquiry_only == 0) { %>
        <h3 id="productPriceValue" class="mb-md-3">
            <b class="mr-1"><span class="product_fixed_price">{{Session::get('currencySymbol')}}<%= Helper.formatPrice(variant.productPrice) %></span></b>
            <% if(variant.compare_at_price > 0 ) { %>
                <span class="org_price">{{Session::get('currencySymbol')}}<span class="product_original_price"><%= Helper.formatPrice(variant.compare_at_price) %></span></span>
            <% } %>
        </h3>
    <% } %>
</script>
<script type="text/template" id="variant_options_template">
    <% _.each(availableSets, function(type, key){ %>
        <% if(type.variant_detail.type == 1 || type.variant_detail.type == 2) { %>
            <div class="size-box">
                <ul class="productVariants">
                    <li class="firstChild"><%= type.variant_detail.title %></li>
                    <li class="otherSize">
                        <% _.each(type.option_data, function(opt, key){ %>
                        <label class="radio d-inline-block txt-14 mr-2"><%= opt.title %>
                            <input id="lineRadio-<%= opt.id %>" name="var_<%= opt.variant_id %>" vid="<%= opt.variant_id %>" optid="<%= opt.id %>" value="<%= opt.id %>" type="radio" class="changeVariant dataVar<%= opt.variant_id %>">
                            <span class="checkround"></span>
                        </label>
                        <% }); %>
                    </li>
                </ul>
            </div>
        <% } %>
    <% }); %>
</script>
<script type="text/template" id="variant_quantity_template">
    <% if(variant.product.inquiry_only == 0) { %>
    <div class="product-description border-product pb-0">
        <h6 class="product-title mt-0">{{__('Quantity')}}:
            <% if(variant.product.has_inventory && !(variant.quantity > 0) && (variant.product.sell_when_out_of_stock != 1)){ %>
                <span id="outofstock" style="color: red;">{{__('Out of Stock')}}</span>
            <% }else{ %>
                <input type="hidden" id="instock" value="<%= variant.quantity %>">
            <% } %>
        </h6>
        <% if(!variant.product.has_inventory || (variant.quantity > 0) || (variant.product.sell_when_out_of_stock == 1)){ %>
        <div class="qty-box mb-3">
            <div class="input-group">
                <span class="input-group-prepend">
                    <button type="button" class="btn quantity-left-minus" data-type="minus" data-field="" data-batch_count="<%= variant.product.batch_count %>" data-minimum_order_count="<%= variant.product.minimum_order_count %>"><i class="ti-angle-left"></i>
                    </button>
                </span>
                <input type="text" onkeypress="return event.charCode > 47 && event.charCode < 58;" pattern="[0-9]{5}" name="quantity" id="quantity" class="form-control input-qty-number quantity_count" value="<%= variant.product.minimum_order_count %>" data-minimum_order_count="<%= variant.product.minimum_order_count %>">
                <span class="input-group-prepend quant-plus">
                    <button type="button" class="btn quantity-right-plus " data-type="plus" data-field="" data-batch_count="<%= variant.product.batch_count %>" data-minimum_order_count="<%= variant.product.minimum_order_count %>">
                        <i class="ti-angle-right"></i>
                    </button>
                </span>
            </div>
        </div>
        <% } %>
    </div>
    <% } %>
</script>
@if($product->related_products->count() > 0)


<section class="section-b-space ratio_asos alProductsPage">
    <div class="container">
        <div class="row m-0">
            <div class="col-12 p-0">
                <h3>{{__('Related products')}}</h3>
            </div>
        </div>
    </div>
    <div class="container pb-md-4">
        <div class="product-4 product-m  related-products pb-2 d-flex">
            @forelse($product->related_products as $related_product)
            <div>
				<a class="common-product-box scale-effect text-center"
						href="{{route('productDetail',[$related_product->vendor->slug,$related_product->url_slug])}}">
					<div class="img-outer-box position-relative">
						<img class="img-fluid blur-up lazyload" data-src="{{ $related_product->image_url }}" alt="">
						<!-- <div class="pref-timing">
							<span>5-10 min</span>
						</div> -->
						<!-- <i class="fa fa-heart-o fav-heart" aria-hidden="true"></i> -->
					</div>
					<div class="media-body align-self-center">
						<div class="inner_spacing px-0">
							<div class="product-description">
								<div class="d-flex align-items-center justify-content-between">
									<h6 class="card_title ellips">{{ $related_product->translation_title }}</h6>
								</div>
								<p>{{ $related_product->vendor_name }}</p>
								<p class="border-bottom pb-1">In {{$related_product->category_name}}</p>
								<div class="d-flex align-items-center justify-content-between">
									<b>
										@if($related_product->inquiry_only == 0)
										{{ Session::get('currencySymbol') . $related_product->variant_price }}
										@endif
									</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
            @empty
            @endforelse
        </div>
    </div>
</section>
@endif
<div class="modal fade product-rating" id="product_rating" tabindex="-1" aria-labelledby="product_ratingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="review-rating-form-modal"></div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="inquiry_form" tabindex="-1" aria-labelledby="inquiry_formLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="inquiry_formLabel">{{__('Inquiry')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php
                $user = Auth::user();
                @endphp
                <form id="inquiry-form">
                    <div class="row">
                        <input type="hidden" name="vendor_id" value="{{$product->vendor_id}}" />
                        <input type="hidden" name="product_id" value="{{$product->id}}" />
                        <div class="col-md-6 form-group">
                            <label>{{__('Name')}}</label>
                            <input class="form-control" name="name" id="name" value="{{$user ? $user->name : '' }}" type="text" placeholder="{{__('Name')}}">
                            <span class="text-danger error-text nameError"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{__('Email')}}</label>
                            <input class="form-control" name="email" id="email" value="{{$user ? $user->email : '' }}" type="text" placeholder="{{__('Email')}}">
                            <span class="text-danger error-text emailError"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{__('Phone Number')}}</label>
                            <input class="form-control" name="number1" id="number1" value="{{$user ? $user->phone_number : '' }}" type="text" placeholder="{{__('Phone Number')}}" style="display:inline-block;">
                            <span class="text-danger error-text numberError"></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>{{__('Company Name')}}</label>
                            <input class="form-control" name="company_name" id="company_name" type="text" placeholder="{{__('Company Name')}}">
                        </div>
                        <div class="col-12 form-group">
                            <label>{{__('Message')}}</label>
                            <textarea class="form-control" name="message" id="message" cols="30" rows="8" placeholder="{{__('Message')}}"></textarea>
                            <span class="text-danger error-texprapt messageError"></span>
                        </div>
                        <div class="col-12 form-group checkbox-input">
                            <input type="checkbox" id="agree" name="agree" required>
                            <label for="agree">{{__('I accept the')}} <a href="{{url('page/terms-conditions')}}" target="_blank">{{__('Terms And Conditions')}}</a> {{__('and have read the')}} <a href="{{url('page/privacy-policy')}}" target="_blank"> {{__('Privacy Policy')}}</a></label>
                            <span class="d-block text-danger error-text agreeError"></span>
                        </div>
                        <div class="col-12 mt-2">
                            <button type="button" class="btn btn-solid w-100 submitInquiryForm">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js-script')
<script type="text/javascript"src="{{asset('front-assets/js/slick.js')}}"></script>
<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
<script type="text/javascript" src="{{asset('front-assets/js/jquery.elevatezoom.js')}}"></script>
@endsection
@section('script')
<script>
    var maximumquantitylert = "{{__('Quantity is not available in stock')}}";
    var minimumquantitylert = "{{__('Minimum Quantity count is')}}";
    $(document).on('click', '.submitInquiryForm', function(e) {
        e.preventDefault();
        var formData = new FormData(document.getElementById("inquiry-form"));
        formData.append("variant_id", $('#prod_variant_id').val());
        var submit_url = "{{ route('inquiryMode.store') }}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: submit_url,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#inquiry_form').modal('hide');
            },
            error: function(response) {
                // console.log(response);
                $('.messageError').html(response.responseJSON.errors.message[0]);
                $('.agreeError').html(response.responseJSON.errors.agree[0]);
                $('.numberError').html(response.responseJSON.errors.number[0]);
                $('.emailError').html(response.responseJSON.errors.email[0]);
                $('.nameError').html(response.responseJSON.errors.name[0]);
            },
            complete: function() {}
        });
    });


    var valueHover = 0;

    function calcSliderPos(e, maxV) {
        return (e.offsetX / e.target.clientWidth) * parseInt(maxV, 10);
    }

    $(".starrate").on("click", function() {
        $(this).data('val', valueHover);
        $(this).addClass('saved')
    });

    $(".starrate").on("mouseout", function() {
        upStars($(this).data('val'));
    });


    $(".starrate span.ctrl").on("mousemove", function(e) {
        var maxV = parseInt($(this).parent("div").data('max'))
        valueHover = Math.ceil(calcSliderPos(e, maxV) * 2) / 2;
        upStars(valueHover);
    });


    function upStars(val) {
        var val = parseFloat(val);
        $("#test").html(val.toFixed(1));

        var full = Number.isInteger(val);
        val = parseInt(val);
        var stars = $("#starrate i");

        stars.slice(0, val).attr("class", "fa fa-star");
        if (!full) {
            stars.slice(val, val + 1).attr("class", "fa fa-star-half-o");
            val++
        }
        stars.slice(val, 5).attr("class", "fa fa-star-o");
    }


    $(document).ready(function() {
        $(".starrate span.ctrl").width($(".starrate span.cont").width());
        $(".starrate span.ctrl").height($(".starrate span.cont").height());
    });
</script>

<script type="text/javascript">
    var ajaxCall = 'ToCancelPrevReq';
    var vendor_id = "{{ $product->vendor_id }}";
    var product_id = "{{ $product->id }}";
    var add_to_cart_url = "{{ route('addToCart') }}";
    $('.changeVariant').click(function() {
        updatePrice();
        $('.addToCart').removeClass('btn-disabled');
        var check = $(this).attr('data-cartCheck');
        if(check == 1 ||check == '1')
        {
            $('.addToCart').addClass('btn-disabled');
        }
    });
    function updatePrice()
    {
        var variants = [];
        var options = [];
        $('.changeVariant').each(function() {
            var that = this;
            if (this.checked == true) {
                variants.push($(that).attr('vid'));
                options.push($(that).attr('optid'));
            }
        });
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: "{{ route('productVariant', $product->sku) }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "variants": variants,
                "options": options,
            },
            beforeSend: function() {
                if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                    ajaxCall.abort();
                }
            },
            success: function(resp) {
                // console.log(resp);
                if(resp.status == 'Success'){
                    $("#variant_response span").html('');
                    var response = resp.data;
                    if(response.variant != ''){
                        $('#product_variant_wrapper').html('');
                        let variant_template = _.template($('#variant_template').html());
                        response.variant.productPrice = (parseFloat(checkAddOnPrice()) + parseFloat(response.variant.productPrice)).toFixed(digit_count);
                        response.variant.compare_at_price = (parseFloat(checkAddOnPrice()) + parseFloat(response.variant.compare_at_price)).toFixed(digit_count);
                        $("#product_variant_wrapper").append(variant_template({ Helper: NumberFormatHelper, variant:response.variant}));
                        $('#product_variant_quantity_wrapper').html('');
                        let variant_quantity_template = _.template($('#variant_quantity_template').html());
                        $("#product_variant_quantity_wrapper").append(variant_quantity_template({variant:response.variant}));
                        // console.log(response.variant.quantity);
                        if(!response.is_available){
                            $(".addToCart, #addon-table").hide();
                        }else{
                            $(".addToCart, #addon-table").show();
                        }
                        let variant_image_template = _.template($('#variant_image_template').html());
                        $(".product__carousel .gallery-parent").html('');
                        $(".product__carousel .gallery-parent").append(variant_image_template({variant:response.variant}));
                        // easyZoomInitialize();
                        // $('.easyzoom').easyZoom();

                        if(response.variant.media != ''){
                            $(".product-slick").slick({ slidesToShow: 1, slidesToScroll: 1, arrows: !0, fade: !0, asNavFor: ".slider-nav" });
                            $(".slider-nav").slick({ vertical: !1, slidesToShow: 3, slidesToScroll: 1, asNavFor: ".product-slick", arrows: !1, dots: !1, focusOnSelect: !0 });
                        }
                    }
                }else{
                    $("#variant_response span").html(resp.message);
                    $(".addToCart, #addon-table").hide();
                }
            },
            error: function(data) {

            },
        });
    }
    function checkAddOnPrice()
    {
        price  = 0;
        $('.productDetailAddonOption').each(function(){
            if($(this).prop('checked') == true){
                var cp = $(this).data('price');
                price = price + parseFloat(cp);
            }
        });
        return price;
    }
</script>
<script>
    var addonids = [];
    var addonoptids = [];
    $(function() {
        $(".productDetailAddonOption").click(function(e) {
            var addon_elem = $(this).closest('tr');
            var addon_minlimit = addon_elem.data('min');
            var addon_maxlimit = addon_elem.data('max');
            if(addon_elem.find(".productDetailAddonOption:checked").length > addon_maxlimit) {
                this.checked = false;
            }else{
                var addonId = $(this).attr("addonId");
                var addonOptId = $(this).attr("addonOptId");
                if ($(this).is(":checked")) {
                    addonids.push(addonId);
                    addonoptids.push(addonOptId);
                } else {
                    addonids.splice(addonids.indexOf(addonId), 1);
                    addonoptids.splice(addonoptids.indexOf(addonOptId), 1);
                }
                if($('.changeVariant').length > 0)
                {
                    updatePrice();
                }else{
                    addOnPrice = parseFloat(checkAddOnPrice());
                    org_price = parseFloat($(this).data('original_price')) + addOnPrice;
                    fixed_price = parseFloat($(this).data('fixed_price')) + addOnPrice;
                    $('.product_fixed_price').html(fixed_price.toFixed(digit_count));
                    $('.product_original_price').html(org_price.toFixed(digit_count));
                }
            }
        });
    });
</script>

<!-----  rating product if delivered -->

<script type="text/javascript">
    $(document).ready(function(e) {
        $('.rating-star-click').click(function() {
            $('.rating_files').show();
            $('.form-row').show();
            $('#product_rating').modal('show');
        });
        $('body').on('click', '.add_edit_review', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            $.get('/rating/get-product-rating?id=' + id, function(markup) {
                $('#product_rating').modal('show');
                $('#review-rating-form-modal').html(markup);
            });
        });
    });
</script>


<script>

        (function ($, window) {
            let ele = null,
                exzoom_img_box = null,
                boxWidth = null,
                boxHeight = null,
                exzoom_img_ul_outer = null, //用于限制 ul 宽度,又不影响放大镜区域
                exzoom_img_ul = null,
                exzoom_img_ul_position = 0, //循环图片区域的边距,用于移动时跟随光标
                exzoom_img_ul_width = 0, //循环图片区域的最大宽度
                exzoom_img_ul_max_margin = 0, //循环图片区域的最大外边距,应该是图片数量减一乘以boxWidth
                exzoom_nav = null,
                exzoom_nav_inner = null,
                navHightClass = "current", //当前图片的类,
                exzoom_navSpan = null,
                navHeightWithBorder = null,
                images = null,
                exzoom_prev_btn = null, //导航上一张图片
                exzoom_next_btn = null, //导航下一张图片
                imgNum = 0, //图片的数量
                imgIndex = 0, //当前图片的索引
                imgArr = [], //图片属性的数字
                exzoom_zoom = null,
                exzoom_main_img = null,
                exzoom_zoom_outer = null,
                exzoom_preview = null, //预览区域
                exzoom_preview_img = null, //预览区域的图片
                autoPlayInterval = null, //用于控制自动播放的间隔时间
                startX = 0, //移动光标的起始坐标
                startY = 0, //移动光标的起始坐标
                endX = 0, //移动光标的终止坐标
                endY = 0, //移动光标的终止坐标
                g = {}, //全局变量
                defaults = {
                    "navWidth": 60, //列表每个宽度,该版本中请把宽高填写成一样
                    "navHeight": 60, //列表每个高度,该版本中请把宽高填写成一样
                    "navItemNum": 5, //列表显示个数
                    "navItemMargin": 7, //列表间隔
                    "navBorder": 1, //列表边框，没有边框填写0，边框在css中修改
                    "autoPlay": true, //是否自动播放
                    "autoPlayTimeout": 2000, //播放间隔时间
                };


            let methods = {
                init: function (options) {
                    let opts = $.extend({}, defaults, options);

                    ele = this;
                    exzoom_img_box = ele.find(".exzoom_img_box");
                    exzoom_img_ul = ele.find(".exzoom_img_ul");
                    exzoom_nav = ele.find(".exzoom_nav");
                    exzoom_prev_btn = ele.find(".exzoom_prev_btn"); //缩略图导航上一张按钮
                    exzoom_next_btn = ele.find(".exzoom_next_btn"); //缩略图导航下一张按钮

                    //todo 以后可以分开宽度和高度的限制
                    boxHeight = boxWidth = ele.outerWidth(); //在小屏幕中,有 padding 的情况下,计算不准,需要手动指定 ele 的宽度

                    // console.log("boxWidth::" + boxWidth);
                    // console.log("ele.parent().width()::" + ele.parent().width());
                    // console.log("ele.parent().outerWidth()::" + ele.parent().outerWidth());
                    // console.log("ele.parent().innerWidth()::" + ele.parent().innerWidth());

                    //todo 缩略图导航的高度和宽度可以改为根据 导航栏宽度 和 navItemNum 计算出来,但是对于不同尺寸的不好处理
                    g.navWidth = opts.navWidth;
                    g.navHeight = opts.navHeight;
                    g.navBorder = opts.navBorder;
                    g.navItemMargin = opts.navItemMargin;
                    g.navItemNum = opts.navItemNum;
                    g.autoPlay = opts.autoPlay;
                    g.autoPlayTimeout = opts.autoPlayTimeout;

                    images = exzoom_img_box.find("img");
                    imgNum = images.length; //图片的数量
                    checkLoadedAllImages(images) //检查图片是否健在完成,全部加载完成的会执行初始化
                },
                prev: function () { //上一张图片
                    moveLeft()
                },
                next: function () { //下一张图片
                    moveRight();
                },
                setImg: function () { //设置大图
                    let url = arguments[0];

                    getImageSize(url, function (width, height) {
                        exzoom_preview_img.attr("src", url);
                        exzoom_main_img.attr("src", url);

                        //todo 未测试
                        //判断已有的图片数量是否合最初的一致,不是的话就先删除最后一个
                        if (exzoom_img_ul.find("li").length === imgNum + 1) {
                            exzoom_img_ul.find("li:last").remove();
                        }
                        exzoom_img_ul.append('<li style="width: ' + boxWidth + 'px;">' +
                            '<img  class="img-fluid blur-up lazyload" data-src="' + url + '"></li>');

                        let image_prop = copute_image_prop(url, width, height);
                        previewImg(image_prop);
                    });
                },
            };

            $.fn.extend({
                "exzoom": function (method, options) {
                    if (arguments.length === 0 || (typeof method === 'object' && !options)) {
                        if (this.length === 0) {
                            // alert("调用 jQuery.exzomm 时的选择器为空");
                            $.error('Selector is empty when call jQuery.exzomm');
                        } else {
                            return methods.init.apply(this, arguments);
                        }
                    } else if (methods[method]) {
                        return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
                    } else {
                        // alert("调用了 jQuery.exzomm 中不存在的方法");
                        $.error('Method ' + method + 'does not exist on jQuery.exzomm');
                    }
                }
            });

            /**
             * 初始化
             */
            function init() {
                exzoom_img_box.append("<div class='exzoom_img_ul_outer'></div>");
                exzoom_nav.append("<p class='exzoom_nav_inner'></p>");
                exzoom_img_ul_outer = exzoom_img_box.find(".exzoom_img_ul_outer");
                exzoom_nav_inner = exzoom_nav.find(".exzoom_nav_inner");

                //把 exzoom_img_ul 移动到 exzoom_img_ul_outer 里
                exzoom_img_ul_outer.append(exzoom_img_ul);

                //循环所有图片,计算尺寸,添加缩略图导航
                for (let i = 0; i < imgNum; i++) {
                    imgArr[i] = copute_image_prop(images.eq(i)); //记录图片的尺寸属性等
                    // console.log(imgArr[i]);
                    let li = exzoom_img_ul.find("li").eq(i);
                    li.css("width", boxWidth); //设置图片上级的 li 元素的宽度
                    li.find("img").css({
                        "margin-top": imgArr[i][5],
                        "width": imgArr[i][3]
                    });
                }

                //缩略图导航
                exzoom_navSpan = exzoom_nav.find("span");
                navHeightWithBorder = g.navBorder * 2 + g.navHeight;
                g.exzoom_navWidth = (navHeightWithBorder + g.navItemMargin) * g.navItemNum;
                g.exzoom_nav_innerWidth = (navHeightWithBorder + g.navItemMargin) * imgNum;

                exzoom_navSpan.eq(imgIndex).addClass(navHightClass);
                exzoom_nav.css({
                    "height": navHeightWithBorder + "px",
                    "width": boxWidth - exzoom_prev_btn.width() - exzoom_next_btn.width(),
                });
                exzoom_nav_inner.css({
                    "width": g.exzoom_nav_innerWidth + "px"
                });
                exzoom_navSpan.css({
                    "margin-left": g.navItemMargin + "px",
                    "width": g.navWidth + "px",
                    "height": g.navHeight + "px",
                });

                //设置滚动区域的宽度
                exzoom_img_ul_width = boxWidth * imgNum;
                exzoom_img_ul_max_margin = boxWidth * (imgNum - 1);
                exzoom_img_ul.css("width", exzoom_img_ul_width);
                //添加放大镜
                exzoom_img_box.append(`
                    <div class='exzoom_zoom_outer'>
                        <span class='exzoom_zoom'></span>
                    </div>
                    <p class='exzoom_preview'>
                        <img class='exzoom_preview_img blur-up lazyload' data-src='' />
                    </p>
                `);
                exzoom_zoom = exzoom_img_box.find(".exzoom_zoom");
                exzoom_main_img = exzoom_img_box.find(".exzoom_main_img");
                exzoom_zoom_outer = exzoom_img_box.find(".exzoom_zoom_outer");
                exzoom_preview = exzoom_img_box.find(".exzoom_preview");
                exzoom_preview_img = exzoom_img_box.find(".exzoom_preview_img");

                //设置大图和预览图区域
                exzoom_img_box.css({
                    "width": boxHeight + "px",
                    "height": boxHeight + "px",
                });

                exzoom_img_ul_outer.css({
                    "width": boxHeight + "px",
                    "height": boxHeight + "px",
                });

                exzoom_preview.css({
                    "width": boxHeight + "px",
                    "height": boxHeight + "px",
                    "left": boxHeight + 5 + "px", //添加个边距
                });

                previewImg(imgArr[imgIndex]);
                autoPlay(); //自动播放
                bindingEvent(); //绑定事件
            }

            /**
             * 检测图片是否加载完成
             * @param images
             */
            function checkLoadedAllImages(images) {
                let timer = setInterval(function () {
                    let loaded_images_counter = 0;
                    let all_images_num = images.length;
                    images.each(function () {
                        if (this.complete) {
                            loaded_images_counter++;
                        }
                    });
                    if (loaded_images_counter === all_images_num) {
                        clearInterval(timer);
                        jQuery('#exzoom').show();
                        init();
                    }
                }, 100)
            }

            /**
             * 获取光标坐标,如果是 touch 事件,只处理第一个
             */
            function getCursorCoords(event) {
                let e = event || window.event;
                let coords_data = e, //记录坐标的数据,默认为 event 本身,移动端的 touch 会修改
                    x, //x 轴
                    y; //y 轴

                if (e["touches"] !== undefined) {
                    if (e["touches"].length > 0) {
                        coords_data = e["touches"][0];
                    }
                }

                x = coords_data.clientX || coords_data.pageX;
                y = coords_data.clientY || coords_data.pageY;

                return {
                    'x': x,
                    'y': y
                }
            }

            /**
             * 检查移动端触摸滑动的位置
             */
            function checkNewPositionLimit(new_position) {
                if (-new_position > exzoom_img_ul_max_margin) {
                    //限制向右的范围
                    new_position = -exzoom_img_ul_max_margin;
                    imgIndex = 0; //向右超出范围的回到第一个
                } else if (new_position > 0) {
                    //限制向左的范围
                    new_position = 0;
                }
                return new_position
            }

            /**
             * 绑定各种事件
             */
            function bindingEvent() {
                //移动端大图区域的 touchend 事件
                exzoom_img_ul.on("touchstart", function (event) {
                    let coords = getCursorCoords(event);
                    startX = coords.x;
                    startY = coords.y;

                    let left = exzoom_img_ul.css("left");
                    exzoom_img_ul_position = parseInt(left);

                    window.clearInterval(autoPlayInterval); //停止自动播放
                });

                //移动端大图区域的 touchmove 事件
                exzoom_img_ul.on("touchmove", function (event) {
                    let coords = getCursorCoords(event);
                    let new_position;
                    endX = coords.x;
                    endY = coords.y;

                    //只跟随光标移动
                    new_position = exzoom_img_ul_position + endX - startX;
                    new_position = checkNewPositionLimit(new_position);
                    exzoom_img_ul.css("left", new_position);

                });

                //移动端大图区域的 touchend 事件
                exzoom_img_ul.on("touchend", function (event) {
                    //触屏滑动,根据移动方向按倍数对齐元素
                    // console.log(endX < startX);
                    if (endX < startX) {
                        //向左滑动
                        moveRight();
                    } else if (endX > startX) {
                        //向右滑动
                        moveLeft();
                    }

                    autoPlay(); //恢复自动播放
                });

                //大屏幕在放大区域点击,判断向左还是向右移动
                exzoom_zoom_outer.on("mousedown", function (event) {
                    let coords = getCursorCoords(event);
                    startX = coords.x;
                    startY = coords.y;

                    let left = exzoom_img_ul.css("left");
                    exzoom_img_ul_position = parseInt(left);
                });

                exzoom_zoom_outer.on("mouseup", function (event) {
                    let offset = ele.offset();

                    if (startX - offset.left < boxWidth / 2) {
                        //在放大镜的左半部分点击
                        moveLeft();
                    } else if (startX - offset.left > boxWidth / 2) {
                        //在放大镜的右半部分点击
                        moveRight();
                    }
                });

                //进入 exzoom 停止自动播放
                ele.on("mouseenter", function () {
                    window.clearInterval(autoPlayInterval); //停止自动播放
                });
                //离开 exzoom 开始自动播放
                ele.on("mouseleave", function () {
                    autoPlay(); //恢复自动播放
                });

                //大屏幕进入大图区域
                exzoom_zoom_outer.on("mouseenter", function () {
                    exzoom_zoom.css("display", "block");
                    exzoom_preview.css("display", "block");
                });

                //大屏幕在大图区域移动
                exzoom_zoom_outer.on("mousemove", function (e) {
                    let width_limit = exzoom_zoom.width() / 2,
                        max_X = exzoom_zoom_outer.width() - width_limit,
                        max_Y = exzoom_zoom_outer.height() - width_limit,
                        current_X = e.pageX - exzoom_zoom_outer.offset().left,
                        current_Y = e.pageY - exzoom_zoom_outer.offset().top,
                        move_X = current_X - width_limit,
                        move_Y = current_Y - width_limit;

                    if (current_X <= width_limit) {
                        move_X = 0;
                    }
                    if (current_X >= max_X) {
                        move_X = max_X - width_limit;
                    }
                    if (current_Y <= width_limit) {
                        move_Y = 0;
                    }
                    if (current_Y >= max_Y) {
                        move_Y = max_Y - width_limit;
                    }
                    exzoom_zoom.css({
                        "left": move_X + "px",
                        "top": move_Y + "px"
                    });

                    exzoom_preview_img.css({
                        "left": -move_X * exzoom_preview.width() / exzoom_zoom.width() + "px",
                        "top": -move_Y * exzoom_preview.width() / exzoom_zoom.width() + "px"
                    });
                });

                //大屏幕离开大图区域
                exzoom_zoom_outer.on("mouseleave", function () {
                    exzoom_zoom.css("display", "none");
                    exzoom_preview.css("display", "none");
                });

                //大屏幕光宝进入放大预览区域
                exzoom_preview.on("mouseenter", function () {
                    exzoom_zoom.css("display", "none");
                    exzoom_preview.css("display", "none");
                });

                //缩略图导航
                exzoom_next_btn.on("click", function () {
                    moveRight();
                });
                exzoom_prev_btn.on("click", function () {
                    moveLeft();
                });

                exzoom_navSpan.hover(function () {
                    imgIndex = $(this).index();
                    move(imgIndex);
                });
            }

            /**
             * 聚焦在导航图片上,左右移动都会调用
             * @param direction: 方向,right | left,必填
             */
            function move(direction) {
                if (typeof direction === "undefined") {
                    alert("exzoom 中的 move 函数的 direction 参数必填");
                }
                //如果超出图片数量了,返回第一张
                if (imgIndex > imgArr.length - 1) {
                    imgIndex = 0;
                }

                //设置高亮
                exzoom_navSpan.eq(imgIndex).addClass(navHightClass).siblings().removeClass(navHightClass);

                //判断缩略图导航是否需要重新设置偏移量
                let exzoom_nav_width = exzoom_nav.width();
                let nav_item_width = g.navItemMargin + g.navWidth + g.navBorder * 2; // 单个导航元素的宽度
                let new_nav_offset = 0;

                //直接对比当前索引的图片占据的宽度和exzoom的宽度的差作为偏移量即可
                let temp = nav_item_width * (imgIndex + 1);
                if (temp > exzoom_nav_width) {
                    new_nav_offset = boxWidth - temp;
                }

                exzoom_nav_inner.css({
                    "left": new_nav_offset
                });

                //切换大图
                let new_position = -boxWidth * imgIndex;
                //在 animate 方法前先调用 stop() ,避免反应迟钝
                new_position = checkNewPositionLimit(new_position);
                exzoom_img_ul.stop().animate({
                    "left": new_position
                }, 500);
                //处理放大区域
                previewImg(imgArr[imgIndex]);
            }

            /**
             * 导航向右
             */
            function moveRight() {
                imgIndex++; //先增加 index,后面判断范围
                if (imgIndex > imgNum) {
                    imgIndex = imgNum;
                }
                move("right");
            }

            /**
             * 导航向左
             */
            function moveLeft() {
                imgIndex--; //先减少 index,后面判断范围
                if (imgIndex < 0) {
                    imgIndex = 0;
                }
                move("left");
            }

            /**
             * 自动播放
             */
            function autoPlay() {
                if (g.autoPlay) {
                    autoPlayInterval = window.setInterval(function () {
                        if (imgIndex >= imgNum) {
                            imgIndex = 0;
                        }
                        imgIndex++;
                        move("right");
                    }, g.autoPlayTimeout);
                }
            }

            /**
             * 预览图片
             */
            function previewImg(image_prop) {
                if (image_prop === undefined) {
                    return
                }
                exzoom_preview_img.attr("src", image_prop[0]);

                exzoom_main_img.attr("src", image_prop[0])
                    .css({
                        "width": image_prop[3] + "px",
                        "height": image_prop[4] + "px"
                    });
                exzoom_zoom_outer.css({
                    "width": image_prop[3] + "px",
                    "height": image_prop[4] + "px",
                    "top": image_prop[5] + "px",
                    "left": image_prop[6] + "px",
                    "position": "relative"
                });
                exzoom_zoom.css({
                    "width": image_prop[7] + "px",
                    "height": image_prop[7] + "px"
                });
                exzoom_preview_img.css({
                    "width": image_prop[8] + "px",
                    "height": image_prop[9] + "px"
                });
            }

            /**
             * 获得图片的真实尺寸
             * @param url
             * @param callback
             */
            function getImageSize(url, callback) {
                let img = new Image();
                img.src = url;

                // 如果图片被缓存，则直接返回缓存数据
                if (typeof callback !== "undefined") {
                    if (img.complete) {
                        callback(img.width, img.height);
                    } else {
                        // 完全加载完毕的事件
                        img.onload = function () {
                            callback(img.width, img.height);
                        }
                    }
                } else {
                    return {
                        width: img.width,
                        height: img.height
                    }
                }
            }

            /**
             * 计算图片属性
             * @param image : jquery 对象或 图片url地址
             * @param width : image 为图片url地址时指定宽度
             * @param height : image 为图片url地址时指定高度
             * @returns {Array}
             */
            function copute_image_prop(image, width, height) {
                let src;
                let res = [];

                if (typeof image === "string") {
                    src = image;
                } else {
                    src = image.attr("src");
                    let size = getImageSize(src);
                    width = size.width;
                    height = size.height;
                }

                res[0] = src;
                res[1] = width;
                res[2] = height;
                let img_scale = res[1] / res[2];

                if (img_scale === 1) {
                    res[3] = boxHeight; //width
                    res[4] = boxHeight; //height
                    res[5] = 0; //top
                    res[6] = 0; //left
                    res[7] = boxHeight / 2;
                    res[8] = boxHeight * 2; //width
                    res[9] = boxHeight * 2; //height
                    exzoom_nav_inner.append(
                        `<span><img class="blur-up lazyload" data-src="${src}" width="${g.navWidth }" height="${g.navHeight }"/></span>`);
                } else if (img_scale > 1) {
                    res[3] = boxHeight; //width
                    res[4] = boxHeight / img_scale;
                    res[5] = (boxHeight - res[4]) / 2;
                    res[6] = 0; //left
                    res[7] = res[4] / 2;
                    res[8] = boxHeight * 2 * img_scale; //width
                    res[9] = boxHeight * 2; //height
                    let top = (g.navHeight - (g.navWidth / img_scale)) / 2;
                    exzoom_nav_inner.append(
                        `<span><img class="blur-up lazyload" data-src="${src}" width="${g.navWidth }" style='top:${top}px;' /></span>`);
                } else if (img_scale < 1) {
                    res[3] = boxHeight * img_scale; //width
                    res[4] = boxHeight; //height
                    res[5] = 0; //top
                    res[6] = (boxHeight - res[3]) / 2;
                    res[7] = res[3] / 2;
                    res[8] = boxHeight * 2; //width
                    res[9] = boxHeight * 2 / img_scale;
                    let top = (g.navWidth - (g.navHeight * img_scale)) / 2;
                    exzoom_nav_inner.append(
                        `<span><img class="blur-up lazyload" data-src="${src}" height="${g.navHeight}" style="left:${top}px;"/></span>`);
                }

                return res;
            }

            // 闭包结束
        })(jQuery, window);


        $(document).ready(function () {
            $('.container').imagesLoaded(function () {
                $("#exzoom").exzoom({
                    autoPlay: false,
                });
                $("#exzoom").removeClass('hidden')
            });

        });
    </script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{asset('assets/js/chat/user_vendor_chat.js')}}"></script>
@endsection
