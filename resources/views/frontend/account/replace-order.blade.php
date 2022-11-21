@extends('layouts.store', ['title' => 'Exchange Orders'])
@section('css')
@endsection
@section('content')

<section class="section-b-space order-page">
    <div class="container">
        <div class="row my-md-3">
            <div class="col-lg-3">
                <div class="account-sidebar"><a class="popup-btn">my account</a></div>
                <div class="dashboard-left mb-3">
                    <div class="collection-mobile-back"><span class="filter-back d-lg-none d-inline-block"><i class="fa fa-angle-left" aria-hidden="true"></i> back</span></div>
                    @include('layouts.store/profile-sidebar')
                </div>
            </div>
            <div class="col-lg-9">
            <form id="return-upload-form" class="theme-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                                    @csrf
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title">
                            <h2>{{__("Exchange Order")}}</h2>
                        </div>
                        <div class="welcome-msg">
                            <h5>{{__("Here are your for exchange product !")}}</h5>
                        </div>
                        <div class="row">
                            <div class="container">
                                @foreach($order->vendors as $key => $vendor)
                                @foreach($vendor->products as $key => $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">



                                            <div class="row">
                                                <div class="col-lg-4 p-0 @php if(count($product->media) == 0){  echo 'd-none'; } @endphp ">


                                                    <div class="exzoom hidden w-100">
                                                        <div class="exzoom_img_box mb-2">
                                                            <ul class='exzoom_img_ul'>
                                                                @if(!empty($product->media))

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
                                                                @endif
                                                            </ul>
                                                        </div>
                                                        @if(count($product->media) > 1)
                                                        <div class="exzoom_nav">
                                                            @if(!empty($product->media))
                                                            @foreach($product->media as $k => $image)
                                                            @php
                                                            if(isset($image->pimage)){
                                                            $img = $image->pimage->image;
                                                            }else{
                                                            $img = $image->image;
                                                            }
                                                            @endphp
                                                            @if(!is_null($img))
                                                            <span class="">
                                                                <img class="blur-up lazyloaded pro_imgs myimage1" data-src="{{@$img->path['image_fit'].'1000/1000'.@$img->path['image_path']}}" width="60" height="60" src="{{@$img->path['image_fit'].'1000/1000'.@$img->path['image_path']}}">
                                                            </span>
                                                            @endif
                                                            @endforeach
                                                            @endif
                                                        </div>
                                                        <p class="exzoom_btn">
                                                            <a href="javascript:void(0);" class="exzoom_prev_btn">
                                                                < </a> <a href="javascript:void(0);" class="exzoom_next_btn"> >
                                                                    </a>
                                                        </p>
                                                        @endif
                                                    </div>
                                                    <div id="myresult" class="img-zoom-result"></div>
                                                </div>

                                                <div class="@php if(!empty($product->media) && count($product->media) > 0){ echo 'col-lg-5'; } else { echo 'offset-lg-4 col-lg-4'; } @endphp rtl-text p-0">
                                                    <div class="product-right inner_spacing pl-sm-3 p-0">
                                                        <h2 class="mb-0">
                                                            {{ (!empty($product->translation) && isset($product->translation[0])) ? $product->translation[0]->title : ''}}
                                                        </h2>
                                                        <span class="rating main-rating">4.1<i class="fa fa-star" aria-hidden="true"></i></span>
                                                        <h6 class="sold-by">
                                                            <b> <img class="blur-up lazyload" data-src="{{$product->vendor->logo['image_fit']}}200/200{{$product->vendor->logo['image_path']}}" alt="{{$product->vendor->Name}}"></b> <a href="{{ route('vendorDetail', $product->vendor->slug) }}"><b> {{$product->vendor->name}} </b></a>
                                                        </h6>
                                                        @if($client_preference_detail)
                                                        @if($client_preference_detail->rating_check == 1)
                                                        @if($product->averageRating > 0)
                                                        <span class="rating">{{ decimal_format($product->averageRating) }} <i class="fa fa-star text-white p-0"></i></span>
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
                                                                            <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}" vid="{{$var_id}}" optid="{{$opt_id}}" value="{{$opt_id}}" type="radio" class="changeVariant dataVar{{$var_id}}" {{$checked}}>
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
                                                                            <input type="checkbox" id="inlineCheckbox_{{$row.'_'.$k}}" class="productDetailAddonOption" name="addonData[$row][]" addonId="{{$addon->addon_id}}" addonOptId="{{$option->id}}" data-price="{{$option->price * $option->multiplier}}" data-fixed_price="{{decimal_format($product->variant[0]->price * $product->variant[0]->multiplier)}}" data-original_price="{{decimal_format($product->variant[0]->compare_at_price * $product->variant[0]->multiplier)}}">
                                                                            <label class="pl-2 mb-0" for="inlineCheckbox_{{$row.'_'.$k}}" data-toggle="tooltip" data-placement="top" title="{{$option->title .' ('.Session::get('currencySymbol').decimal_format($option->price).')' }}">
                                                                                {{$option->title .' ('.Session::get('currencySymbol').decimal_format($option->price * $option->multiplier).')' }}</label>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>



                                                        </div>
                                                        @endif
                                                        @php
                                                        $checkSlot = 0;
                                                        @endphp
                                                        <div class="product-buttons">
                                                            @if(!$product->has_inventory || $product->variant[0]->quantity > 0 || $product->sell_when_out_of_stock == 1)

                                                            @if($product->inquiry_only == 0)
                                                            @php
                                                            if($product->sell_when_out_of_stock == 1 && $product->variant[0]->quantity == 0){
                                                            $product_quantity_in_cart = 1;
                                                            $product->variant[0]->quantity = 2;
                                                            }
                                                            else
                                                            $product_quantity_in_cart = $product_in_cart->quantity??0;


                                                            @endphp
                                                            @if($is_available == 1)
                                                            {{-- <a href="#" data-toggle="modal" data-target="#addtocart" class="btn btn-solid addToCart {{ (($checkSlot == 0  && $vendor_info->is_vendor_closed == 1) || ($product->variant[0]->quantity <= $product_quantity_in_cart && $product->has_inventory)) ? 'btn-disabled' : '' }}">{{__('Add To Cart')}}</a>--}}
                                                            @endif



                                                            @endif
                                                            @endif
                                                        </div>
                                                        <div class="border-product al_disc">
                                                            <h6 class="product-title">{{__('Product Details')}}</h6>
                                                            <p></p>
                                                            {!!(!empty($product->translation) && isset($product->translation[0])) ?
                                                            $product->translation[0]->body_html : ''!!}
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                            <!---------------------------------------------------END-->

                                            <input id="item_one{{$key}}" type="hidden" name="return_ids" value="{{ $product->id }}" required>
                                            <!-- <label class="order-items d-flex" for="item_one{{$key}}">
                                                <div class="item-img mx-1">
                                                    <img src="{{ $product->image_url }}" alt="">
                                                </div>
                                                <div class="items-name ml-2">
                                                    <h4 class="mt-0 mb-1"><b>{{ $product->product_name }}</b></h4>
                                                    <label><b>{{_("Quantity")}}</b>: {{ $product->quantity }}</label>
                                                </div>
                                            </label> -->
                                        </div>
                                    </td>


                                </tr>
                                @endforeach
                                @endforeach


                                <input type="hidden" name="order_vendor_product_id" value="{{ $product->id }}">
                                <!-- <input type="hidden" name="file_set" id="files_set" value="0">
                                    <div id="remove_files">
                                    </div> -->
                                <!-- <div class="row rating_files">
                                        <div class="col-12">
                                        <label>{{__('Upload Images')}}</label>
                                        </div>
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <div class="file file--upload">
                                                <label for="input-file">
                                                    <span class="plus_icon"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                                </label>
                                                <input id="input-file" type="file" name="images[]" accept="image/*"  multiple>

                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <span class="row show-multiple-image-preview" id="thumb-output">
                                            </span>
                                        </div>

                                    </div> -->


                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label>{{__('Reason for exchange product')}}</label>
                                        <select class="form-control" name="reason" id="reason">
                                            @foreach ($reasons as $reason)
                                            <option value="{{$reason->title}}">{{$reason->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{__('Comments (Optional)')}}:</label>
                                    <textarea class="form-control" name="coments" id="comments" cols="20" rows="4"></textarea>
                                </div>


                                <div class="row mb-sm-2 m-0 p-0" id="address_template_main_div">
                                    <div class="row w-100">

                                        @forelse($addresses as $k => $address)

                                        <div class="col-md-6 mb-2">
                                            <div class="delivery_box cart_delivery p-2 mb-sm-3 mb-1 position-relative">
                                                <!-- <a class="deleteAddress"><i class="fa fa-trash-o"></i></a> -->
                                                @if(!empty(Auth::user()) && $address->is_primary)
                                                <a class="alEditAddressIcons" href="{{route('user.addressBook')}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

                                                @endif
                                                <label class="radio m-0">{{ ($address->house_number ?? false) ? $address->house_number."," : '' }} {{$address->address}}, {{$address->state}} {{$address->pincode}}
                                                    @if($address->is_primary)
                                                    <input type="radio" name="address_id" value="{{$address->id}}" checked="checked">
                                                    @else
                                                    <input type="radio" name="address_id" value="{{$address->id}}" {{$k == 0? 'checked="checked"' : '' }}>
                                                    @endif
                                                    <span class="checkround"></span>
                                                </label>
                                            </div>
                                        </div>

                                        @if((($k+1)%2)==0)
                                    </div>
                                    @endif

                                    @if($k ==1)
                                </div>
                                <div class="view_all_address d-none" id="view_all_address_div">
                                    @endif


                                    @if((($k+1)%2)==0)
                                    <div class="row w-100">
                                        @endif








                                        {{-- @if($k ==2)


                                        <div class="view_all_address d-none" id="view_all_address_div" >
                                        @endif
                                            <div class="col-md-6 mb-2">
                                                <div class="delivery_box cart_delivery p-2 mb-sm-3 mb-1 position-relative">

                                                    @if(!empty(Auth::user()))
                                                        <a href="{{route('user.addressBook')}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        <!-- <span>{{ __('Edit') }} {{( __('Address') }}</span> -->
                                        @endif
                                        <label class="radio m-0">{{ ($address->house_number ?? false) ? $address->house_number."," : '' }} {{$address->address}}, {{$address->state}} {{$address->pincode}}
                                            @if($address->is_primary)
                                            <input type="radio" name="address_id" value="{{$address->id}}" checked="checked">
                                            @else
                                            <input type="radio" name="address_id" value="{{$address->id}}" {{$k == 0? 'checked="checked"' : '' }}>
                                            @endif
                                            <span class="checkround"></span>
                                        </label>
                                    </div>
                                </div>

                                @if(($k >6 ) && ($k ==count($addresses) -1 ))
                            </div>
                        </div> --}}

                        {{-- @endif --}}
                        @empty
                        <div class="col-12 address-no-found">
                            <p>{{ __('Address not available.') }}</p>
                        </div>
                        @endforelse
                        <!-- <div class="col-12 mt-4 text-center" id="add_new_address_btn">
                        <a class="btn btn-solid w-100 mx-auto mb-4">
                            <i class="fa fa-plus mr-1" aria-hidden="true"></i>{{__('Add New Address')}}
                        </a>
                    </div> -->
                    </div>
                </div>

                <div class="row w-100 mt-2">
                    <div class="cart_address w-100 text-center">
                        <a class="d-block w-100" id="view_all_address" href="javascript:void(0)">{{ __('View all address') }}</a>
                    </div>
                </div>

                <span class="text-danger" id="error-msg"></span>
                <span class="text-success" id="success-msg"></span>
                <button type="submit" class="btn btn-solid mt-3" id="return_form_button">{{__('Done')}}</button>
                <!-- </form> -->
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


<script type="text/javascript">
    $(document).ready(function(e) {


        $(document).delegate('#view_all_address', 'click', function() {

            $("#view_all_address").addClass("d-none");
            $("#view_all_address_div").removeClass("d-none");

        });

        $('body').delegate('.local-img-del', 'click', function() {
            var img_id = $(this).data('id');
            $(this).prev().remove();
            $(this).remove();
            $("#" + img_id).remove();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {


            $('#input-file').on('change', function() {
                $('#files_set').val(1);
                $(this).closest("form").submit();
            });

            $('.server-img-del').on('click', function(e) {
                var img_id = $(this).data('id');
                $(this).prev().remove();
                $(this).remove();
                $("#remove_files").append("<input type='hidden' name='remove_files[]' value='" + img_id + "'>");
            });





        });
        $('#return-upload-form').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            // let TotalImages = $('#input-file')[0].files.length; //Total Images
            let comments = $('#comments').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('update.order.replace')}}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    if (comments.length > 0)
                        $("#return_form_button").html('<i class="fa fa-spinner fa-spin fa-custom"></i> Loading').prop('disabled', true);
                },
                success: (data) => {
                    if (data.status == 'Success') {
                        if (comments.length == 0) {
                            $("#return_form_button").html('Request').prop('disabled', false);
                        } else {
                            $("#return_form_button").html('Request');
                            var url = "{{route('user.orders',['pageType' => 'returnOrders'])}}";
                            $(location).prop('href', url);
                        }
                    } else {
                        $('#error-msg').text(data.message);
                        $("#return_form_button").html('Request').prop('disabled', false);
                    }
                },
                error: function(data) {
                    $('#error-msg').text(data.message);
                    $("#review_form_button").html('Request').prop('disabled', false);
                }
            });

        });

    });
</script>





@endsection