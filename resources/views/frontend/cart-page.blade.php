@if($cart_details->totalQuantity<=0)
<div class="col-md-12"  id="address_template">
    <div class="delivery_box p-0 mb-3">
        <label class="radio m-0">{{$data->address->address.' '.$address->city.' '.$address->state.' '.$address->pincode}}
            <input type="radio" checked="checked" name="address_id" value="{{$address->id}}">
            <span class="checkround"></span>
        </label>
    </div>
</div>

<div class="container"  id="empty_cart_template">
        <div class="row mt-2 mb-4 mb-lg-5">
            <div class="col-12 text-center">
                <div class="cart_img_outer" style="height:200px;">
                    <img class="blur-up lazyload" data-src="{{asset('front-assets/images/empty_cart.png')}}">
                </div>
                <h3>{{__('Your Cart Is Empty!')}}</h3>
                <p>{{__('Add items to it now.')}}</p>
                <a class="btn btn-solid" href="{{url('/')}}">{{__('Continue Shopping')}}</a>
            </div>
        </div>
    </div>

    @else

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h3 class="page-title text-uppercase mt-lg-4">{{__('Cart')}}</h3>
            </div>
            <div class="cart_response mt-3 mb-3 d-none">
                <div class="alert p-0" role="alert"></div>
            </div>
            @if (\Session::has('error'))
                <div class="alert alert-danger">
                    <span>{!! \Session::get('error') !!}</span>
                </div>
            @endif
        </div>
    </div>
</div>

    <div class="col-lg-8" id="cart_template">
        <div class="shoping_cart p-3">
            <div class="row mb-2 border-bottom">
                        <div class="col-6">
                            <div class="single_cart_heading">
                                    <h3>Shopping Cart</h3>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="item-show-cart text-right">
                                <h4>{{$cart_details->totalQuantity}}  Items</h4>
                            </div>
                        </div>
            </div>
            <div class="row border-bottom product_title_add py-1">
                    <div class="col-md-4">
                        <span>Product Details</span>
                    </div>

                    <div class="col-md-2 text-center">
                        <span>Price</span>
                    </div>

                    <div class="col-md-2 text-center">
                        <span>Quantity</span>
                    </div>

                    <div class="col-md-4 text-center">
                        <span>Total</span>
                    </div>

            </div>
   @php
    $fixed_fee=0;
    $fixed_fee_amount=0;
    $total_fixed_fee_amount=0;
    $price_bifurcation=0;
    $total_wallet_amount_used=0;
    $closed_store= 0;

    /* Getting other taxes */
    $tax_fixed_fee_percentage=0;
    $tax_container_charges_percentage=0;
    $tax_markup_charges_percentage=0;
    $tax_service_charges_percentage=0;
    $tax_delivery_charges_percentage=0;
    
    $product_container_charges_tax_amount=0;
    
    $other_taxes=0;
    $other_taxes_string="";
    @endphp
    @foreach($cart_details->products as $product)


        <div id="thead_{{$product->vendor->id}}">
            <div class="row">
                
                <div class="col-12">
                    <div class="countdownholder alert-danger" id="min_order_validation_error_{{$product->vendor->id}}" style="display:none;">Your cart will be expired in </div>
                </div>
                @if($product->is_vendor_closed == 1 && $product->closed_store_order_scheduled == 0)
                    {{-- $closed_store = 1 --}}
                    <div class="col-12">
                        <div class="text-danger">{{$cart_details->totalQuantity}}
                            <i class="fa fa-exclamation-circle"></i>{{getNomenclatureName('Vendors', true) . __(' is not accepting orders right now.')}}
                        </div>
                    </div>
                @elseif( $product->is_vendor_closed == 1 && $product->closed_store_order_scheduled == 1 )
                    <div class="col-12">
                        <div class="text-danger">
                            <i class="fa fa-exclamation-circle"></i> {{__('We are not accepting orders right now. You can schedule this for ')}}{{$product->delaySlot}}
                        </div>
                    </div>
               @endif

                @if( (($product->vendor->order_min_amount) > 0) &&  (($cart_details->total_payable_amount)+($total_wallet_amount_used) < ($product->vendor->order_min_amount)) )
                    <div class="col-12" id="MOV_Notification">
                        <div class="text-danger">
                            <i class="fa fa-exclamation-circle"></i> {{__('We are not accepting orders less then')}} {{Session::get('currencySymbol')}}{{decimal_format($product->vendor->order_min_amount)}}
                        </div>
                    </div>
               @endif
                <div id="mov" style="display:none;">{{$product->vendor->order_min_amount}}</div>
                @if( ($product->isDeliverable != '') && ($product->isDeliverable == 0) )
                    <div class="col-12">
                        <div class="text-danger">
                            <i class="fa fa-exclamation-circle"></i> {{ __('Products for this vendor are not deliverable at your area. Please change address or remove product.')}}
                        </div>
                    </div>
                @endif

            </div>
        </div>
        
        <div class="col-12 cart-heading mt-2 px-0">
            <h5 class="my-1"><b>{{$product->vendor->name}}</b></h5>
            <input type="hidden" name="category_name" id="category_name" value= "{{$product->vendor->name}}"/>
        </div>


        {{-- Product Detail Loop --}}

        <div id="tbody_{{$product->vendor->id}}">

            @foreach($product->vendor_products as $vendor_product)
                <div class="row align-items-md-center vendor_products_tr alFourTemplateCartPage" id="tr_vendor_products_{{$vendor_product->id}}">
                    <div class="product-img col-3 col-md-2">
                        @if($vendor_product->pvariant->media_one)
                            <img class='blur-up lazyload w-100' data-src="{{$vendor_product->pvariant->media_one->pimage->image->path->proxy_url.'200/200'.$vendor_product->pvariant->media_one->pimage->image->path->image_path}}">
                        @elseif($vendor_product->pvariant->media_second && $vendor_product->pvariant->media_second->image != null)
                            <img class='blur-up lazyload w-100' data-src="{{ $vendor_product->pvariant->media_second->image->path->proxy_url.'200/200'. $vendor_product->pvariant->media_second->image->path->image_path}}">
                        @else
                            <img class='blur-up lazyload w-100' data-src="{{$vendor_product->image_url}}">
                        @endif
                    </div>
                    <div class="col-9 col-md-10">
                        <div class="row align-items-md-center">
                            <div class="col-md-3 order-md-1">
                                <h4 class="cart_product_name">{{$vendor_product->product->category_name->name }}</h4>
                                <h4 class="mt-0 mb-1" style="word-wrap: break-word; line-height:20px"><strong>{{$vendor_product->product->translation_one ? $vendor_product->product->translation_one->title :  $vendor_product->product->sku }}</strong></h4>
                                <input type="hidden" name="hidden_product_name" id="hidden_product_name" value= "{{$vendor_product->product->translation_one ? $vendor_product->product->translation_one->title :  $vendor_product->product->sku }}" />
                                @foreach($vendor_product->pvariant->vset as $vset)
                                    @if($vset->variant_detail->trans)
                                        <label><span><b>{{$vset->variant_detail->trans->title }}:</b></span> {{$vset->option_data->trans->title }}</label>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-6 col-md-2 mb-1 mb-md-0 order-md-2">
                                <span class="alFourTempTitle">{{ __('Price')}}</span>
                                <div class="items-price">{{Session::get('currencySymbol')}}{{ decimal_format($vendor_product->pvariant->price * $vendor_product->pvariant->multiplier) }}</div>
                            </div>
                            <div class="col-6 col-md-2 text-left order-md-4">
                                <span class="alFourTempTitle">{{ __('Total')}}</span>
                                <div class="items-price">{{Session::get('currencySymbol')}}{{decimal_format($vendor_product->quantity_price) }}</div>
                            </div>
                            <div class="col-10 col-md-4 text-md-center order-md-3">
                                <div class="number d-flex justify-content-md-center">
                                    <div class="counter-container d-flex align-items-center">
                                        <span class="minus qty-minus" data-minimum_order_count="{{$vendor_product->product->minimum_order_count }}"
                                        data-batch_count="{{$vendor_product->product->batch_count }}" data-id="{{$vendor_product->id }}" data-base_price=" {{$vendor_product->pvariant->price }}" data-vendor_id="{{$vendor_product->vendor_id }}">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </span>
                                        <input placeholder="1" type="text" data-minimum_order_count="{{$vendor_product->product->minimum_order_count }}"
                                        data-batch_count="{{$vendor_product->product->batch_count }}" value="{{$vendor_product->quantity }}" class="input-number" step="0.01" id="quantity_{{$vendor_product->id }}" readonly>
                                        <span class="plus qty-plus" data-minimum_order_count="{{$vendor_product->product->minimum_order_count }}"
                                            data-batch_count="{{$vendor_product->product->batch_count }}" data-id="{{$vendor_product->id }}" data-base_price=" {{$vendor_product->pvariant->price }}">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                @if($cart_details->pharmacy_check == 1)
                                    @if($vendor_product->product->pharmacy_check == 1)
                                        <button type="button" class="btn btn-solid prescription_btn mt-2" data-cart="{{$vendor_product->cart_id }}" data-product="{{$vendor_product->product->id }}" data-vendor_id="{{$vendor_product->vendor_id }}">{{ __('Add Prescription')}}</button>
                                        @if($vendor_product->cart_product_prescription > 0)
                                            <h4 class="mt-0 mb-1" style="word-wrap: break-word; line-height:20px"><strong>{{$vendor_product->cart_product_prescription }} {{ __('Prescription Added')}}</strong></h4>
                                        @endif
                                    @endif
                                @endif
                            </div>
                            <div class="col-2 col-md-1 text-right text-md-center p-in order-md-5">
                                <a class="action-icon d-block remove_product_via_cart" data-product="{{$vendor_product->id }}" data-vendor_id="{{$vendor_product->vendor_id }}">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                       @if(count($vendor_product->addon) != 0)
                            <hr class="my-2">
                            <div class="row align-items-md-center add_head">
                                <div class="col-12">
                                    <h6 class="m-0 pl-0"><b>{{__('Add Ons')}}</b></h6>
                                </div>
                            </div>
                            @foreach($vendor_product->addon as $ad=>$addon)
                            @if($addon->option)
                                <div class="row">
                                    <div class="col-md-3 col col-sm-4 items-details">
                                        <p class="p-0 m-0">{{$addon->option->title }}</p>
                                    </div>
                                    <div class="col-md-6 col col-sm-4">
                                        <div class="extra-items-price">{{Session::get('currencySymbol')}}{{decimal_format($addon->option->price_in_cart * $addon->option->multiplier) }}</div>
                                    </div>
                                    <div class="col-md-3 col col-sm-4">
                                        <div class="extra-items-price">{{Session::get('currencySymbol')}}{{decimal_format($addon->option->quantity_price) }}</div>
                                    </div>
                                </div>
                           @endif
                            @endforeach
                       @endif

                        @if($vendor_product->pvariant->container_charges > 0)
                            <div class="row">
                                <div class="col-md-3 col-sm-4 items-details text-left">
                                    <p class="p-0 m-0 alert-danger">{{ __('Container Charges') }} *</p>
                                </div>
                                <div class="col-md-2 col-sm-4 text-center">
                                    <div class="extra-items-price">{{Session::get('currencySymbol')}}{{decimal_format($vendor_product->pvariant->container_charges) }} 
                                   
                                    
                                    {{-- /* --- Vendor Tax Get Percentage ---- */ --}}
                                    @foreach($cart_details->taxRates as $index=> $tax)
                                        @if($vendor_product->product->container_charges_tax_id!=null)
                                            @if($vendor_product->product->container_charges_tax_id==$index)
                                               {{ $product_container_charges_tax_amount+=$vendor_product->pvariant->container_charges*$tax->tax_rate/100;}}
                                            @endif
                                        @endif                        
                                    @endforeach
                                   
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-4 text-right">
                                    <div class="extra-items-price">{{Session::get('currencySymbol')}}{{decimal_format($vendor_product->quantity_container_charges) }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if( ($vendor_product->product->delay_order_time->delay_order_hrs != '' && $vendor_product->product->delay_order_time->delay_order_min != '' ) &&  (($vendor_product->product->delay_order_time->delay_order_hrs != 0) || ($vendor_product->product->delay_order_time->delay_order_hrs != 0)))
                        <div class="col-12">
                            <div class="text-danger" style="font-size:12px;">
                                <i class="fa fa-exclamation-circle"></i>Preparation Time is
                                @if($vendor_product->product->delay_order_time->delay_order_hrs > 0)
                                    {{$vendor_product->product->delay_order_time->delay_order_hrs }} Hrs
                                @endif
                                @if($vendor_product->product->delay_order_time->delay_order_min > 0)
                                    {{$vendor_product->product->delay_order_time->delay_order_min }} Minutes
                                @endif
                            </div>
                        </div>
                    @endif
                    @if($vendor_product->product_out_of_stock == 1)
                        <div class="col-12">
                            <div class="text-danger" style="font-size:12px;">
                                <i class="fa fa-exclamation-circle"></i>{{__("This Product is out of stock")}}
                            </div>
                        </div>
                    @endif
                    @if($client_preference_detail->product_order_form ==1)
                        @if( ($vendor_product->faq_count > 0 ) && ($vendor_product->user_product_order_form == '' || $vendor_product->user_product_order_form == null ) )
                        <div class=" col-3 {{$vendor_product->faq_count }}  " id="product_faq_dev_{{$vendor_product->product_id }}">
                            <input type="hidden" name="product_faq_ids" value="{{$vendor_product->product_id }}">
                            <div class="text-center my-3 btn-product-order-form-div">
                                <button class="clproduct_cart_order_form btn btn-solid w-100" id="add__cart_product_form" data-dev_remove_id="product_faq_dev_{{$vendor_product->product_id }}" data-product_id="{{$vendor_product->product_id }}"  data-vendor_id="{{$vendor_product->vendor_id }}">{{__('Product Order Form')}}</button>
                            </div>
                        </div>
                       @endif
                    @endif

                </div>
                <input type="hidden" name="cart_product_ids[]" value="{{$vendor_product->id }}">

                <hr class="my-1">
            @endforeach

        {{-- End Product Detail Loop --}}

               
        <div class="row my-2">
            @if(@$guest_user)
                <div class="col-lg-6 ">
                @if($product->is_promo_code_available > 0)
                        <div class="coupon_box w-100 d-flex align-content-center">
                            <img class="blur-up lazyload" data-src="{{ asset('assets/images/discount_icon.svg') }}">
                            <label class="mb-0 ml-2">
                                @if($product->coupon)
                                    {{$product->coupon->promo->name}}
                                @else
                                    <a href="javascript:void(0)" class="promo_code_list_btn ml-1" data-vendor_id="{{$product->vendor->id}}" data-cart_id="{{$cart_details->id }}" data-amount="{{$product->product_sub_total_amount  }}">{{__('Select a promo code')}}</a>
                                @endif
                            </label>
                        </div>
                        @if($product->coupon)
                            <label class="p-1 m-0"><a href="javascript:void(0)" class="remove_promo_code_btn ml-1" data-coupon_id="{{$product->coupon ? $product->coupon->promo->id : '' }}" data-cart_id="{{$cart_details->id}}">Remove</a></label>
                        @endif
                        @endif
                    </div>
                    @endif
                    <div class="col-lg-6">
                        @if($product->delOptions)
                            <div class="row mb-1 d-flex align-items-center   @if($product->promo_free_deliver == 1  ) {{$product->promo_free_deliver }} org_price @endif ">
                                <div class="col-5 text-lg-right">
                                    <label class="m-0 radio">
                                        {{__('Delivery Fee')}} :</label>
                                    </div>
                                <div class="col-7">
                                    {!!$product->delOptions!!}
                                </div>
                            </div>
                       @endif
                       @if($product->vendor->fixed_fee_amount>0)
                            <div class="row mb-1 d-flex align-items-center">
                                <div class="col-5 text-lg-right">
                                    <label class="m-0 radio">
                                        {{__('fixedFee')}} :</label>
                                    </div>
                                <div class="col-7">
                                {{$product->vendor->fixed_fee_amount}} 
                                </div>
    
                            </div>
                       @endif
    
                        {{-- Home Service Schedual code Start at down --}}
                        @if(($cart_details->closed_store_order_scheduled == 1 || $client_preference_detail->off_scheduling_at_cart != 1) && $cart_details->vendorCnt > 1)
                            @if($client_preference_detail->business_type != 'laundry')
                            <div class="row mb-1 d-flex align-items-center" style="{{(($product->schedule_type == 'schedule') ? '' : 'display:none!important')}}">
                                <div class="col-5 text-lg-right">
                                    <label class="m-0 radio">
                                        {{__('Scheduled Slot')}} :</label>
                                    </div>
                                <div class="col-7 vendor_slot_cart">
                                    @if($product->slotsCnt != 0)
                                        <input type="date" class="form-control vendor_schedule_datetime" placeholder="Inline calendar" data-schedule_type="date" data-vendor_id="{{$product->vendor_id}}" data-cart_product_id="{{$product->cart_product_id}}" value="{{(($product->scheduled_date_time != '')?$product->scheduled_date_time : $product->delay_date ) }}"  min="{{(($product->delay_date != '0') ? $product->delay_date : '') }}" >
                                        <select onchange="checkSlotAvailability(this);" class="form-control vendor_schedule_slot" id="vendor_schedule_slot_{{$product->vendor_id }}" data-schedule_type="time" data-vendor_id="{{$product->vendor_id}}" data-cart_product_id="{{$product->cart_product_id}}" >
                                            <option value="">{{__("Select Slot")}} </option>
                                            @foreach($product->slots as $slot)
                                               <option value="{{$slot->value}}" {{$slot->value == $product->selected_slot ? "selected" : ""}} >{{$slot->name }}</option>
                                           @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                            @endif
                        @endif
                       
                        
                        {{-- Home Service Schedual code end at down --}}
    
                        <div class="row mb-1">
                            <div class="col-5 text-lg-right">
                                @if($product->coupon_amount_used > 0)
                                    <label class="m-0 radio">{{__('Coupon Discount')}} :</label>
                                @endif
                            </div>
                            <div class="col-7 text-right">
                                @if($product->coupon_amount_used > 0)
                                    <p class="total_amt m-0">{{Session::get('currencySymbol')}} {{decimal_format($product->coupon_amount_used)}}</p>
                                @endif
                            </div>
                        </div>
    
                        <div class="row">
                            @if($cart_details->vendorCnt>1)
                                <div class="col-5 text-lg-right">
                                    <label class="m-0 radio">{{__('Sub Total')}} :</label>
                                </div>
                                <div class="col-7 text-right">
                                    <p class="total_amt m-0">{{Session::get('currencySymbol')}} {{decimal_format($product->product_total_amount+$product->vendor->fixed_fee_amount)}}</p>
                                </div>
                            @endif
                        </div>
                </div>
            </div>
        </div>
            <hr class="my-1">
        {{-- @endif --}}
            <div class="row mb-md-1 alFourTemplateCartButtons mt-3">
                <div class="col-sm-6 col-lg-4 mb-2 mb-sm-0 d-lg-flex align-items-lg-center justify-content-lg-between">
                    <a class="btn shoping" href="{{ url('/') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i>
     {{__('Continue Shopping')}}</a>
                </div>
            </div>
        </div>

@endforeach
@endif