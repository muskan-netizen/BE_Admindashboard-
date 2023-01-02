@switch($client_preference_detail->business_type)
    @case('taxi')
        <?php $ordertitle = 'Rides'; ?>
        <?php $hidereturn = 1; ?>
    @break
    @default
        <?php $ordertitle = 'Orders'; ?>
         <?php $hidereturn = 0; ?>
@endswitch
@php
    $clientData = \App\Models\Client::select('socket_url')->first();
@endphp
@extends('layouts.store', ['title' => __('My '.getNomenclatureName($ordertitle, true))])
@section('css')

    <style type="text/css">
        .main-menu .brand-logo {
            display: inline-block;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        input:invalid,
        input:out-of-range {
            border-color: hsl(0, 50%, 50%);
            background: hsl(0, 50%, 90%);
        }
        .error{
            font-size:10px;
            color:red;
        }
    </style>
@endsection
@section('content')
    @php
    $timezone = Auth::user()->timezone;
    @endphp

    <style type="text/css">
        .productVariants .firstChild{min-width:150px;text-align:left!important;border-radius:0!important;margin-right:10px;cursor:default;border:none!important}.product-right .color-variant li,.productVariants .otherChild{height:35px;width:35px;border-radius:50%;margin-right:10px;cursor:pointer;border:1px solid #f7f7f7;text-align:center}.productVariants .otherSize{height:auto!important;width:auto!important;border:none!important;border-radius:0}.product-right .size-box ul li.active{background-color:inherit}.login-page .theme-card .theme-form input{margin-bottom:5px}.invalid-feedback{display:block}.al_body_template_one .order_popop .modal-body{padding:5px 15px 15px;background:#89898905;box-shadow:4px 10px 6px #838282}.al_body_template_one .order_popop p{font-size:13px;line-height:19px}.al_body_template_one .order_popop .modal-body textarea{border:1px solid#d9d3d3}.al_body_template_one .order_popop .modal-body textarea::placeholder{padding:5px 10px}.al_body_template_one .order_popop .modal-body button.close{position:absolute;right:5px;top:0;padding:0;margin:0}.al_body_template_one .order_popop .modal-body label{display:inline-block;font-size:18px!important;font-weight:400;}
        .productVariants .firstChild{min-width:150px;text-align:left!important;border-radius:0!important;margin-right:10px;cursor:default;border:none!important}.product-right .color-variant li,.productVariants .otherChild{height:35px;width:35px;border-radius:50%;margin-right:10px;cursor:pointer;border:1px solid #f7f7f7;text-align:center}.productVariants .otherSize{height:auto!important;width:auto!important;border:none!important;border-radius:0}.product-right .size-box ul li.active{background-color:inherit}.login-page .theme-card .theme-form input{margin-bottom:5px}.invalid-feedback{display:block}.al_body_template_one .order_popop .modal-body{padding:5px 15px 15px;background:#89898905;box-shadow:4px 10px 6px #838282}.al_body_template_one .order_popop p{font-size:13px;line-height:19px}.al_body_template_one .order_popop .modal-body textarea{border:1px solid#d9d3d3}.al_body_template_one .order_popop .modal-body textarea::placeholder{padding:5px 10px}.al_body_template_one .order_popop .modal-body button.close{position:absolute;right:5px;top:0;padding:0;margin:0}.al_body_template_one .order_popop .modal-body label{display:inline-block;font-size:18px!important;font-weight:400}
    </style>
    <section class="section-b-space order-page">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="text-sm-left" id="wallet_response">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <span>{!! \Session::get('success') !!}</span>
                            </div>
                            @php
                                \Session::forget('success');
                            @endphp
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <span>{!! \Session::get('error') !!}</span>
                            </div>
                            @php
                                \Session::forget('error');
                            @endphp
                        @endif
                        <div class="message d-none">
                            <div class="alert p-0"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-md-3">
                <div class="col-lg-3">
                    <div class="account-sidebar"><a class="popup-btn">{{ __('My Account') }}</a></div>
                    <div class="dashboard-left mb-3">
                        <div class="collection-mobile-back"><span class="filter-back d-lg-none d-inline-block"><i
                                    class="fa fa-angle-left" aria-hidden="true"></i>{{ __('Back') }}</span></div>
                        @include('layouts.store/profile-sidebar')
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="dashboard-right">
                        <div class="dashboard">
                            <div class="page-title">
                                <h2>{{ __(getNomenclatureName($ordertitle, true)) }}</h2>
                            </div>
                            <div class="welcome-msg">
                                <h5>{{ __('Here Are All Your Previous ' . getNomenclatureName($ordertitle, true)) }}</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="row" id="orders_wrapper">
                                    <div class="col-sm-12 col-lg-12 tab-product al_custom_ordertabs mt-md-3 p-0">
                                        <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link {{ Request::query('pageType') === null || Request::query('pageType') == 'activeOrders' ? 'active show' : '' }}"
                                                    id="active-orders-tab" data-toggle="tab" href="#active-orders" role="tab"
                                                    aria-selected="true"><i
                                                        class="icofont icofont-ui-home"></i>{{ __('Active ' . getNomenclatureName($ordertitle, true)) }}</a>
                                                <div class="material-border"></div>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ Request::query('pageType') == 'pastOrders' ? 'active show' : '' }}"
                                                    id="past_order-tab" data-toggle="tab" href="#past_order" role="tab"
                                                    aria-selected="false"><i
                                                        class="icofont icofont-man-in-glasses"></i>{{ __('Past ' . getNomenclatureName($ordertitle, true)) }}</a>
                                                <div class="material-border"></div>
                                            </li>
                                            @if (isset($hidereturn) && $hidereturn != 1)
                                                <li class="nav-item">
                                                    <a class="nav-link {{ Request::query('pageType') == 'returnOrders' ? 'active show' : '' }}"
                                                        id="return_order-tab" data-toggle="tab" href="#return_order" role="tab"
                                                        aria-selected="false"><i
                                                            class="icofont icofont-man-in-glasses"></i>{{ __('Return Requests') }}</a>
                                                    <div class="material-border"></div>
                                                </li>
                                            @endif
                                            <li class="nav-item">
                                                <a class="nav-link {{ Request::query('pageType') == 'rejectedOrders' ? 'active show' : '' }}"
                                                    id="return_order-tab" data-toggle="tab" href="#rejected_order" role="tab"
                                                    aria-selected="false"><i
                                                        class="icofont icofont-man-in-glasses"></i>{{ __('Rejected/Cancel ' . getNomenclatureName($ordertitle, true)) }}</a>
                                                <div class="material-border"></div>
                                            </li>
                                        </ul>
                                        <div class="tab-content nav-material al" id="top-tabContent">
                                            <div class="tab-pane fade {{ Request::query('pageType') === null || Request::query('pageType') == 'activeOrders' ? 'active show' : '' }}"
                                                id="active-orders" role="tabpanel" aria-labelledby="active-orders-tab">
                                                <div class="row">
                                                    @if ($activeOrders->isNotEmpty())
                                                        @foreach ($activeOrders as $key => $order)
                                                        @php

                                                            $total_other_taxes=0.00;
                                                            foreach(explode(":",$order->total_other_taxes) as $row){
                                                                $total_other_taxes+=(float)$row;
                                                            }

                                                        @endphp
                                                            <div class="col-12">
                                                                <div class="row no-gutters order_head">
                                                                    <div class="col-md-3 alOrderStatus">
                                                                        <h4>{{ __('Order Number') }}</h4>
                                                                        <span>#{{ $order->order_number }}</span>
                                                                    </div>
                                                                    <div class="col-md-3 alOrderStatus">
                                                                        <h4>{{ __('Date & Time') }}</h4>
                                                                        <span>{{ dateTimeInUserTimeZone($order->created_at, $timezone) }}</span>
                                                                    </div>
                                                                    <div class="col-md-3 alOrderStatus">
                                                                        <h4>{{ __(getNomenclatureName('Vendor Name',true)) }}</h4>
                                                                        <span><a class="text-capitalize">{{ $order->user->name }}</a></span>
                                                                    </div>
                                                                    @if ($client_preference_detail->business_type != 'taxi')
                                                                        <div class="col-md-3 ellipsis">
                                                                            <h4>{{ __('Address') }}</h4>
                                                                            @if($order->luxury_option_id == 3)

                                                                                <span class="ellipsis" data-toggle="tooltip"
                                                                                    data-placement="top" title="" >
                                                                                    @if ( count($order->vendors) > 0)
                                                                                        {{ $order->vendors->first() ? ($order->vendors->first()->vendor ? ($order->vendors->first()->vendor->address) : __('NA') ) : __('NA') }}
                                                                                    @else
                                                                                        NA
                                                                                    @endif
                                                                                </span>
                                                                            @else
                                                                                <span class="ellipsis" data-toggle="tooltip"
                                                                                    data-placement="top" title="">
                                                                                    @if ($order->address)
                                                                                        {{ $order->address->address }},
                                                                                        {{ $order->address->street }},
                                                                                        {{ $order->address->city }},
                                                                                        {{ $order->address->state }},
                                                                                        {{ $order->address->country }}
                                                                                        {{ $order->address->pincode }}
                                                                                    @else
                                                                                        NA
                                                                                    @endif
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="row no-gutters order_data d-none">
                                                                    <div class="col-md-3">#{{ $order->order_number }}
                                                                    </div>
                                                                    {{-- <div class="col-md-3">{{convertDateTimeInTimeZone($order->created_at, $timezone, 'l, F d, Y, h:i A')}}</div> --}}
                                                                    <div class="col-md-3">
                                                                        {{ dateTimeInUserTimeZone($order->created_at, $timezone) }}
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a class="text-capitalize">{{ $order->user->name }}</a>
                                                                    </div>
                                                                    @if ($client_preference_detail->business_type != 'taxi')
                                                                        <div class="col-md-3">
                                                                        @if($order->luxury_option_id == 3)

                                                                          <span class="ellipsis" data-toggle="tooltip"
                                                                              data-placement="top" title="">
                                                                              @if ( count($order->vendors) >0)
                                                                                  {{ $order->vendors->first() ? ($order->vendors->first()->vendor ? ($order->vendors->first()->vendor->address) : __('NA') ) : __('NA') }}
                                                                              @else
                                                                                  NA
                                                                              @endif
                                                                          </span>
                                                                          @else
                                                                          <span class="ellipsis" data-toggle="tooltip"
                                                                                data-placement="top" title="">
                                                                                @if ($order->address)
                                                                                    {{ $order->address->address }},
                                                                                    {{ $order->address->street }},
                                                                                    {{ $order->address->city }},
                                                                                    {{ $order->address->state }},
                                                                                    {{ $order->address->country }}
                                                                                    {{ $order->address->pincode }}
                                                                                @else
                                                                                    NA
                                                                                @endif
                                                                            </span>
                                                                          @endif

                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-md-9 mb-3">
                                                                        @php
                                                                            $subtotal_order_price = $total_order_price = $total_tax_order_price = 0;
                                                                        @endphp
                                                                        @foreach ($order->vendors as $key => $vendor)
                                                                            @php
                                                                                $product_total_count = $product_subtotal_amount = $product_taxable_amount = 0;
                                                                            @endphp
                                                                            <div class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0">
                                                                                @if ($vendor->delivery_fee > 0 || !empty($order->scheduled_date_time) || $order->luxury_option_id > 0)
                                                                                    <div class="progress-order font-12  d-flex align-items-center justify-content-between pr-2">
                                                                                        @if ($order->luxury_option_id > 0)
                                                                                            @php
                                                                                                $luxury_option = \App\Models\LuxuryOption::where('id', $order->luxury_option_id)->first();
                                                                                                if ($luxury_option->title == 'takeaway') {
                                                                                                    $luxury_option_name = getNomenclatureName('Takeaway', Session::get('customerLanguage'), false);
                                                                                                } elseif ($luxury_option->title == 'dine_in') {
                                                                                                    $luxury_option_name = 'Dine-In';
                                                                                                } else {
                                                                                                  //  $luxury_option_name = 'Delivery';
                                                                                                    $luxury_option_name = getNomenclatureName($luxury_option->title);

                                                                                                }
                                                                                            @endphp
                                                                                            <span
                                                                                                class="badge badge-info ml-2 my-1">{{ __($luxury_option_name) }}</span>
                                                                                        @endif
                                                                                        @if (!empty($order->scheduled_date_time))
                                                                                            <span
                                                                                                class="badge badge-success ml-2">{{__('Scheduled')}}</span>
                                                                                                <span class="ml-2 text-right">
                                                                                                    Slots:
                                                                                                    @if($clientPreference->scheduling_with_slots == 1 && $clientPreference->business_type == 'laundry')
                                                                                                        {{'Pickup: '. date('Y-m-d', strtotime(dateTimeInUserTimeZone($order->schedule_pickup, $timezone))).' '.$order->scheduled_slot.' | ' }}

                                                                                                        @if ($order->dropoff_scheduled_slot != "")
                                                                                                            {{'Dropoff: '.date('Y-m-d', strtotime(dateTimeInUserTimeZone($order->schedule_dropoff, $timezone))).' '.$order->dropoff_scheduled_slot }}
                                                                                                        @else
                                                                                                            Dropoff: N/A
                                                                                                        @endif
                                                                                                    @else
                                                                                                        {{ (($order->scheduled_slot)?dateTimeInUserTimeZone($order->scheduled_date_time, $timezone).'. Slot: '.$order->scheduled_slot:dateTimeInUserTimeZone($order->scheduled_date_time, $timezone) ) }}
                                                                                                    @endif
                                                                                                </span>
                                                                                        @elseif(!empty($vendor->ETA))
                                                                                            @if($clientPreference->hide_order_prepare_time!=1)
                                                                                                <span class="ml-2">{{__('Your order will arrive by')}} {{ $vendor->ETA }}</span>
                                                                                            @endif
                                                                                        @endif
                                                                                        @if ($order->is_gift == '1')
                                                                                            <div class="gifted-icon">
                                                                                                <img class="p-1 align-middle"
                                                                                                    src="{{ asset('assets/images/gifts_icon.png') }}"
                                                                                                    alt="">
                                                                                                <span
                                                                                                    class="align-middle">This
                                                                                                    is a gift.</span>
                                                                                            </div>
                                                                                        @endif
                                                                                        @if($clientData->socket_url !='' )
                                                                                            <a class="start_chat chat-icon btn btn-solid"  data-vendor_order_id="{{$vendor->id}}" data-vendor_id="{{$vendor->vendor_id}}" data-orderid="" data-order_id="{{$order->id}}">{{__('Chat')}}</a>
                                                                                            @if(isset($vendor->driver_chat) && ($vendor->driver_chat == 1) && ($vendor->dispatch_traking_url != ''))
                                                                                            <a class="start_chat_driver chat-icon btn btn-solid" data-driver_details_api="{{$vendor->dispatch_traking_url}}" data-vendor_order_id="{{$vendor->id}}" data-vendor_id="{{$vendor->vendor_id}}" data-orderid="" data-order_id="{{$order->id}}">{{__('Driver Chat')}}</a>
                                                                                            @endif
                                                                                        @endif
                                                                                    </div>
                                                                                @endif
                                                                                <span class="left_arrow pulse"></span>
                                                                                <div class="row">
                                                                                    <div class="col-5 col-sm-3">
                                                                                        <h5 class="m-0">
                                                                                            {{ __('Order Status') }}</h5>
                                                                                        <ul class="status_box mt-1 pl-0">
                                                                                            @if (!empty($vendor->order_status))
                                                                                                <li>
                                                                                                    @if ($vendor->order_status == 'placed')
                                                                                                        <img src="{{ asset('assets/images/order-icon.svg') }}"
                                                                                                            alt="">
                                                                                                    @elseif($vendor->order_status
                                                                                                        == 'accepted')
                                                                                                        <img src="{{ asset('assets/images/payment_icon.svg') }}"
                                                                                                            alt="">
                                                                                                    @elseif($vendor->order_status
                                                                                                        == 'processing')
                                                                                                        <img src="{{ asset('assets/images/customize_icon.svg') }}"
                                                                                                            alt="">
                                                                                                    @elseif($vendor->order_status
                                                                                                        == 'out for delivery')
                                                                                                        <img src="{{ asset('assets/images/driver_icon.svg') }}"
                                                                                                            alt="">
                                                                                                    @endif
                                                                                                    <label class="m-0 in-progress">{{__( ucfirst( $vendor->order_status)) }}</label>
                                                                                                </li>
                                                                                            @endif

                                                                                            @if (!empty($vendor->dispatch_traking_url))
                                                                                                <li>
                                                                                                    <img src="{{ asset('assets/images/order-icon.svg') }}"
                                                                                                        alt="">
                                                                                                    <a class="alOrderDetailsLink"  href="{{ route('front.booking.details', $order->order_number) }}"
                                                                                                    target="_blank">{{ __('Details') }}</a>
                                                                                                </li>
                                                                                            @endif
                                                                                            @if ($vendor->order_status_option_id==1 && ($client_preference_detail->is_cancel_order_user == 1))
                                                                                                <?php
                                                                                                    if($clientPreference->business_type == 'laundry'){
                                                                                                        $pickup_cancelling_charges = $clientCurrency->currency->symbol.$vendor->vendor->pickup_cancelling_charges;
                                                                                                    }
                                                                                                ?>

                                                                                                <h6 class="m-0">
                                                                                                @if ($clientPreference->business_type == 'laundry')
                                                                                                        <label class="rating-star cancel_order" id="cancel_order_{{$order->order_number}}" data-pickup_order="{{date('Y-m-d', strtotime(dateTimeInUserTimeZone($order->schedule_pickup, $timezone)))}}" data-order_id="{{$order->id}}" data-pickup_cancelling_charges="{{$pickup_cancelling_charges}}" data-order_number="{{$order->order_number}}" data-order_vendor_id="{{$vendor->vendor_id??0}}" data-id="{{$vendor->id??0}}">
                                                                                                            {{ __('Cancel Order') }}
                                                                                                        </label>
                                                                                                    @else
                                                                                                        <label class="rating-star cancel_order" data-order_vendor_id="{{$vendor->vendor_id??0}}" data-id="{{$vendor->id??0}}">
                                                                                                            {{ __('Cancel Orders') }}
                                                                                                        </label>
                                                                                                    @endif
                                                                                                </h6>
                                                                                            @endif
                                                                                            @if ($vendor->dineInTable)
                                                                                                <li>
                                                                                                    <h5 class="mb-1">
                                                                                                        {{ __('Dine-in') }}
                                                                                                    </h5>
                                                                                                    <h6 class="m-0">
                                                                                                        {{ $vendor->dineInTableName }}
                                                                                                    </h6>
                                                                                                    <h6 class="m-0">
                                                                                                        Category :
                                                                                                        {{ $vendor->dineInTableCategory }}
                                                                                                    </h6>
                                                                                                    <h6 class="m-0">
                                                                                                        Capacity :
                                                                                                        {{ $vendor->dineInTableCapacity }}
                                                                                                    </h6>
                                                                                                </li>
                                                                                            @endif

                                                                                        </ul>
                                                                                    </div>
                                                                                    <div class="col-7 col-sm-4">
                                                                                        <ul
                                                                                            class="product_list p-0 m-0 text-center">
                                                                                            @foreach ($vendor->products as $product)
                                                                                                @if ($vendor->vendor_id == $product->vendor_id)
                                                                                                    <li class="text-center mb-0 alOrderImg">
                                                                                                        <img src="{{ $product->image_url }}" alt="">
                                                                                                        <span class="item_no position-absolute">x{{ $product->quantity }}</span>
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <label class="items_price">{{ Session::get('currencySymbol') }}{{ decimal_format($product->price * $clientCurrency->doller_compare) }}</label>
                                                                                                    </li>
                                                                                                    @php
                                                                                                        $product_total_price = $product->price * $clientCurrency->doller_compare;
                                                                                                        $product_total_count += $product->quantity * $product_total_price;
                                                                                                        $product_taxable_amount += $product->taxable_amount;
                                                                                                        $total_tax_order_price += $product->taxable_amount;
                                                                                                    @endphp
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </ul>

                                                                                    </div>
                                                                                    <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                                        <ul class="price_box_bottom m-0 p-0">
                                                                                            <li
                                                                                                class="d-flex align-items-center justify-content-between">
                                                                                                <label
                                                                                                    class="m-0">{{ __('Product Total') }}</label>
                                                                                                <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->subtotal_amount
                                                                                                    *
                                                                                                    $clientCurrency->doller_compare)}}</span>
                                                                                            </li>
                                                                                            @if ($vendor->discount_amount > 0)
                                                                                                <li
                                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                                    <label
                                                                                                        class="m-0">{{ __('Coupon Discount') }}</label>
                                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->discount_amount
                                                                                                        *
                                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                                </li>
                                                                                            @endif
                                                                                            @if ($order->fixed_fee_amount > 0)
                                                                                                <li
                                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                                    <label
                                                                                                        class="m-0">{{ __($fixedFee) }}</label>
                                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->fixed_fee_amount
                                                                                                        *
                                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                                </li>
                                                                                            @endif
                                                                                            @if ($vendor->delivery_fee > 0)
                                                                                                <li
                                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                                    <label
                                                                                                        class="m-0">{{ __('Delivery Fee') }}</label>
                                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->delivery_fee
                                                                                                        *
                                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                                </li>
                                                                                            @endif

                                                                                            @if ($vendor->toll_amount > 0)
                                                                                                <li
                                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                                    <label
                                                                                                        class="m-0">{{ __('Toll Fee') }}</label>
                                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->toll_amount
                                                                                                        *
                                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                                </li>
                                                                                            @endif

                                                                                            @if ($vendor->service_fee_percentage_amount > 0)
                                                                                                <li
                                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                                    <label
                                                                                                        class="m-0">{{ __('Service Fee') }}</label>
                                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->service_fee_percentage_amount
                                                                                                        *
                                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                                </li>
                                                                                            @endif
                                                                                            <li
                                                                                                class="grand_total d-flex align-items-center justify-content-between">
                                                                                                <label
                                                                                                    class="m-0">{{ __('Amount') }}</label>
                                                                                                @php
                                                                                                    $product_subtotal_amount = $product_total_count - $vendor->discount_amount + $vendor->delivery_fee;
                                                                                                    $subtotal_order_price += $product_subtotal_amount;
                                                                                                @endphp
                                                                                                <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->payable_amount+$order->fixed_fee_amount
                                                                                                    *
                                                                                                    $clientCurrency->doller_compare)}}</span>
                                                                                            </li>
                                                                                            {{-- Check if order is created only --}}
                                                                                            @if ($vendor->status == 0)
                                                                                                @if ($vendor->order_status == 'placed')
                                                                                                    <button  style="font-size:10px; padding: 0 5px; float: right; margin-top: 5px;" data-toggle="modal" data-target="#orderModel{{$order->id}}" class="reschedule_order btn btn-solid" data-id="{{$order->id}}" data-order_vendor_id="{{ $vendor->id ?? 0 }}" data-vendor_id="{{$vendor->id}}">Reschedule</button>
                                                                                                @endif
                                                                                            @endif
                                                                                        </ul>
                                                                                    </div>
                                                                                    <?php
                                                                                        $pkup  = json_encode(date('Y-m-d', strtotime(dateTimeInUserTimeZone($order->schedule_pickup, $timezone))));
                                                                                        $dpoff = json_encode(date('Y-m-d', strtotime(dateTimeInUserTimeZone($order->schedule_dropoff, $timezone))));
                                                                                        $rescheduling_charges = json_encode($clientCurrency->currency->symbol.$vendor->vendor->rescheduling_charges);
                                                                                        $pickup_cancelling_charges = json_encode($clientCurrency->currency->symbol.$vendor->vendor->pickup_cancelling_charges);
                                                                                        $newPickupClass = json_encode('pickup_'.$order->order_number);
                                                                                        $newDropoffClass = json_encode('dropoff_'.$order->order_number);
                                                                                    ?>
                                                                                    @if ($clientPreference->business_type == 'laundry')
                                                                                    {{-- Model to get Slots --}}
                                                                                    <form onsubmit='checkDates({{$pkup}}, {{$dpoff}}, {{$rescheduling_charges}}, {{$pickup_cancelling_charges}}, {{$newPickupClass}}, {{$newDropoffClass}})' action="{{route('user.rescheduleOrder')}}" class="rescheduleOrder" id="rescheduleOrder" name="rescheduleOrder" method="post">
                                                                                        @csrf
                                                                                        <div class="modal fade" id="orderModel{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header" style="border-bottom: 1px solid #e5e8eb;">
                                                                                                    <h5 class="modal-title text-capitalize" id="exampleModalLongTitle">Order Number: #{{$order->order_number}}</h5>
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                    </div>
                                                                                                    <div class="modal-body">
                                                                                                        <input type="hidden" name="order_id" value="{{Crypt::encrypt($order->id)}}">
                                                                                                        @include('frontend.modals.rescheduling_modal', ['newPickupClass' => 'pickup_'.$order->order_number , 'newDropoffClass' => 'dropoff_'.$order->order_number ])
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                    <button type="submit" style="font-size:10px; padding: 0 5px; float: right; margin-top: 5px;" class="btn btn-solid reschedule_now_btn">Reschedule Now</button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>
                                                                                    {{-- Model to get Slots --}}
                                                                                @endif

                                                                                </div>
                                                                                @if(count($vendor['vendor_dispatcher_status']))
                                                                                            <div class="step-indicator step-indicator-order">

                                                                                                    @foreach ($vendor->dispatcher_status_icons as $key => $icons)
                                                                                                        @if(isset($vendor['vendor_dispatcher_status'][$key]) && !empty($vendor['vendor_dispatcher_status'][$key]))
                                                                                                        <div class="step step{{$key+1}} active">
                                                                                                            <div class="step-icon-order step-icon-order-fill"><img src="{{@$vendor->vendor_dispatcher_status[$key]->status_data['icon']}}"></div>

                                                                                                        </div>


                                                                                                        <div class="indicator-line active"></div>

                                                                                                        @if(count($vendor['vendor_dispatcher_status']) == $key+1)
                                                                                                            <p>{{@$vendor->vendor_dispatcher_status[$key]->status_data['driver_status']}}</p>
                                                                                                        @endif
                                                                                                        @if($key < count($vendor->dispatcher_status_icons)-1)
                                                                                                        <div class="indicator-line active"></div>
                                                                                                        @endif
                                                                                                        @else
                                                                                                        <div class="step step{{$key+1}} @if(app('request')->input('step') >= '1' || empty(app('request')->input('step'))) active @endif">
                                                                                                            <div class="step-icon-order"><img src="{{$icons}}"></div>
                                                                                                            <p></p>
                                                                                                        </div>
                                                                                                        @if($key < count($vendor->dispatcher_status_icons)-1)
                                                                                                        <div class="indicator-line"></div>
                                                                                                        @endif
                                                                                                        @endif
                                                                                                    @endforeach

                                                                                            </div>
                                                                                            @endif


                                                                            </div>

                                                                        @endforeach

                                                                    </div>
                                                                    <div class="col-md-3 mb-3 pl-lg-0">
                                                                        <div class="card-box p-2 mb-0 h-100">
                                                                            <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                                                <li
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <label
                                                                                        class="m-0">{{ __('Sub Total') }}</label>
                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_amount
                                                                                        *
                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                </li>
                                                                                @if ($order->wallet_amount_used > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Wallet') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->wallet_amount_used
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->loyalty_amount_saved > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Loyalty Used') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->loyalty_amount_saved
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->taxable_amount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Tax') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format(($order->taxable_amount)
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->taxable_amount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Container Charges') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format(($vendor->total_container_charges) * $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif

                                                                                @if ($order->total_toll_amount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Toll Amount') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_toll_amount
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->total_service_fee > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Service Fee') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_service_fee
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif

                                                                                @if ($order->fixed_fee_amount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __($fixedFee) }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->fixed_fee_amount
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->tip_amount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Tip Amount') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->tip_amount
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->subscription_discount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Subscription Discount') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->subscription_discount
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->total_discount_calculate > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Discount') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_discount_calculate
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->total_delivery_fee > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Delivery Fee') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_delivery_fee
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                <li
                                                                                    class="grand_total d-flex align-items-center justify-content-between">
                                                                                    <label
                                                                                        class="m-0">{{ __('Total Payable') }}</label>
                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->payable_amount+$order->fixed_fee_amount)}}</span>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="col-12">
                                                            <div class="no-gutters order_head">
                                                                <h4 class="text-center">{{ __('No Active Order Found') }}
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                {{ $activeOrders->appends(['pageType' => 'activeOrders'])->links() }}
                                            </div>
                                            <div class="tab-pane fade past-order {{ Request::query('pageType') == 'pastOrders' ? 'active show' : '' }}"
                                                id="past_order" role="tabpanel" aria-labelledby="past_order-tab">
                                                <div class="row">
                                                    @if ($pastOrders->isNotEmpty())
                                                        @foreach ($pastOrders as $key => $order)

                                                            <div class="col-12">
                                                                <div class="row no-gutters order_head">
                                                                    <div class="col-md-3 alOrderStatus">
                                                                        <h4>{{ __('Order Number') }}</h4>
                                                                        <span>#{{ $order->order_number }}</span>
                                                                    </div>
                                                                    <div class="col-md-3 alOrderStatus">
                                                                        <h4>{{ __('Date & Time') }}</h4>
                                                                        <span>{{ dateTimeInUserTimeZone($order->created_at, $timezone) }}</span>
                                                                    </div>
                                                                    <div class="col-md-3 alOrderStatus">
                                                                        <h4>{{ __('Customer Name') }}</h4>
                                                                        <span><a class="text-capitalize">{{ $order->user->name }}</a></span>
                                                                    </div>
                                                                    @if ($client_preference_detail->business_type != 'taxi')
                                                                        <div class="col-md-3">
                                                                            <h4>{{ __('Address') }}</h4>
                                                                            @if($order->luxury_option_id == 3)

                                                                                <span class="ellipsis" data-toggle="tooltip"
                                                                                    data-placement="top" title="" >
                                                                                    @if ( count($order->vendors) > 0)
                                                                                        {{ $order->vendors->first() ? ($order->vendors->first()->vendor ? ($order->vendors->first()->vendor->address) : __('NA') ) : __('NA') }}
                                                                                    @else
                                                                                        NA
                                                                                    @endif
                                                                                </span>
                                                                            @else
                                                                                <span class="ellipsis" data-toggle="tooltip"
                                                                                    data-placement="top" title="">
                                                                                    @if ($order->address)
                                                                                        {{ $order->address->house_number ?? false ? $order->address->house_number . ',' : '' }}
                                                                                        {{ $order->address->address }},
                                                                                        {{ $order->address->street }},
                                                                                        {{ $order->address->city }},
                                                                                        {{ $order->address->state }},
                                                                                        {{ $order->address->country }}
                                                                                        {{ $order->address->pincode }}
                                                                                    @else
                                                                                        NA
                                                                                    @endif
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="row no-gutters order_data d-none">
                                                                    <div class="col-md-3">#{{ $order->order_number }}
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        {{ dateTimeInUserTimeZone($order->created_at, $timezone) }}
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a class="text-capitalize">{{ $order->user->name }}</a>
                                                                    </div>
                                                                    @if ($client_preference_detail->business_type != 'taxi')
                                                                        <div class="col-md-3" {{$order->luxury_option_id }}>

                                                                            @if($order->luxury_option_id == 3)

                                                                            <span class="ellipsis" data-toggle="tooltip"
                                                                                data-placement="top" title="" >
                                                                                @if ( count($order->vendors) > 0)
                                                                                    {{ $order->vendors->first() ? ($order->vendors->first()->vendor ? ($order->vendors->first()->vendor->address) : __('NA') ) : __('NA') }}
                                                                                @else
                                                                                    NA
                                                                                @endif
                                                                            </span>
                                                                            @else
                                                                            <span class="ellipsis" data-toggle="tooltip"
                                                                                data-placement="top" title="">
                                                                                @if ($order->address)
                                                                                    {{ $order->address->house_number ?? false ? $order->address->house_number . ',' : '' }}
                                                                                    {{ $order->address->address }},
                                                                                    {{ $order->address->street }},
                                                                                    {{ $order->address->city }},
                                                                                    {{ $order->address->state }},
                                                                                    {{ $order->address->country }}
                                                                                    {{ $order->address->pincode }}
                                                                                @else
                                                                                    NA
                                                                                @endif
                                                                            </span>
                                                                            @endif

                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-md-9 mb-3">
                                                                        @php
                                                                            $subtotal_order_price = $total_order_price = $total_tax_order_price = 0;
                                                                        @endphp
                                                                        @foreach ($order->vendors as $key => $vendor)
                                                                            @php
                                                                                $product_total_count = $product_subtotal_amount = $product_taxable_amount = 0;
                                                                            @endphp
                                                                            <div
                                                                                class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0">
                                                                                <span class="left_arrow pulse"></span>
                                                                                <div class="row">
                                                                                    <div class="col-5 col-sm-3">
                                                                                        <h5 class="m-0">
                                                                                            {{ __('Order Status') }}</h5>
                                                                                        <ul class="status_box mt-1 pl-0">
                                                                                            @if (!empty($vendor->order_status))
                                                                                                <li>
                                                                                                    <img src="{{ asset('assets/images/driver_icon.svg') }}"
                                                                                                        alt="">
                                                                                                    <label
                                                                                                        class="m-0 in-progress">{{ __(ucfirst($vendor->order_status)) }}</label>
                                                                                                </li>
                                                                                            @endif




                                                                                            @if (!empty($vendor->dispatch_traking_url))
                                                                                                <li>
                                                                                                    <img src="{{ asset('assets/images/order-icon.svg') }}"
                                                                                                        alt="">
                                                                                                    <a class="alOrderDetailsLink" href="{{ route('front.booking.details', $order->order_number) }}"
                                                                                                    target="_blank">{{ __('Details') }}</a>
                                                                                                </li>
                                                                                            @endif
                                                                                            @if ($vendor->dineInTable)
                                                                                                <li>
                                                                                                    <h5 class="mb-1">
                                                                                                        {{ __('Dine-in') }}
                                                                                                    </h5>
                                                                                                    <h6 class="m-0">
                                                                                                        {{ $vendor->dineInTableName }}
                                                                                                    </h6>
                                                                                                    <h6 class="m-0">
                                                                                                        Category :
                                                                                                        {{ $vendor->dineInTableCategory }}
                                                                                                    </h6>
                                                                                                    <h6 class="m-0">
                                                                                                        Capacity :
                                                                                                        {{ $vendor->dineInTableCapacity }}
                                                                                                    </h6>
                                                                                                </li>
                                                                                            @endif
                                                                                        </ul>
                                                                                    </div>
                                                                                    <div class="col-7 col-sm-4 row">
                                                                                        <div class="col-6 col-sm-6">
                                                                                            <ul class="product_list p-0 m-0 text-center">
                                                                                                @foreach ($vendor->products as $product)
                                                                                                    @if ($vendor->vendor_id == $product->vendor_id)
                                                                                                        @php
                                                                                                            $pro_rating = $product->productRating->rating ?? 0;
                                                                                                        @endphp
                                                                                                        <li class="text-center mb-0 alOrderImg">
                                                                                                            <img src="{{ $product->image_url }}" alt="">
                                                                                                            <span class="item_no position-absolute">x{{ $product->quantity }}</span>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <label class="items_price">{{ Session::get('currencySymbol') }}{{ $product->price * $clientCurrency->doller_compare }}</label>
                                                                                                            <label class="rating-star add_edit_review"
                                                                                                                data-id="{{ $product->productRating->id ?? 0 }}"
                                                                                                                data-order_vendor_product_id="{{ $product->id ?? 0 }}">
                                                                                                                <i class="fa fa-star{{ $pro_rating >= 1 ? '' : '-o' }}"></i>
                                                                                                                <i class="fa fa-star{{ $pro_rating >= 2 ? '' : '-o' }}"></i>
                                                                                                                <i class="fa fa-star{{ $pro_rating >= 3 ? '' : '-o' }}"></i>
                                                                                                                <i class="fa fa-star{{ $pro_rating >= 4 ? '' : '-o' }}"></i>
                                                                                                                <i class="fa fa-star{{ $pro_rating >= 5 ? '' : '-o' }}"></i>
                                                                                                            </label>
                                                                                                            @php
                                                                                                                $product_total_price = $product->price * $clientCurrency->doller_compare;
                                                                                                                $product_total_count += $product->quantity * $product_total_price;
                                                                                                                $product_taxable_amount += $product->taxable_amount;
                                                                                                                $total_tax_order_price += $product->taxable_amount;
                                                                                                            @endphp
                                                                                                    @endif
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </div>

                                                                                        <div class="col-6 col-sm-6">
                                                                                        @if($order->vendors[0]->dispatch_traking_url!=null && $order->vendors[0]->dispatch_traking_url!="")

                                                                                            <ul class="product_list p-0 m-0 text-center">
                                                                                                @php
                                                                                                $driverrating = $order->driver_rating->rating ?? 0;
                                                                                                @endphp
                                                                                                <li class="text-center alOrderTaxi">
                                                                                                    {{-- <img src="#" alt=""> --}}
                                                                                                    <label class="items_price">{{__('Rate Your Driver')}}</label>
                                                                                                    <label class="rating-star add_edit_driver_review"
                                                                                                        data-id="{{ $order->driver_rating->id ?? 0 }}"
                                                                                                        data-order_vendor_product_id="{{ $product->id ?? 0 }}">
                                                                                                        <i
                                                                                                            class="fa fa-star{{ $driverrating >= 1 ? '' : '-o' }}"></i>
                                                                                                        <i
                                                                                                            class="fa fa-star{{ $driverrating >= 2 ? '' : '-o' }}"></i>
                                                                                                        <i
                                                                                                            class="fa fa-star{{ $driverrating >= 3 ? '' : '-o' }}"></i>
                                                                                                        <i
                                                                                                            class="fa fa-star{{ $driverrating >= 4 ? '' : '-o' }}"></i>
                                                                                                        <i
                                                                                                            class="fa fa-star{{ $driverrating >= 5 ? '' : '-o' }}"></i>
                                                                                                    </label>
                                                                                                </li>

                                                                                            </ul>

                                                                                        @endif

                                                                                        @if($order->reports!=null)
                                                                                        <div class="order-past-report text-center">
                                                                                            <a target="_blank" href="{{$order->reports->report['original']}}" download><i class="fa fa-download" aria-hidden="true"></i> Report</a>
                                                                                        </div>
                                                                                    @endif
                                                                                    </div>

                                                                                    </div>
                                                                                    <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                                        <ul class="price_box_bottom m-0 p-0">
                                                                                            <li
                                                                                                class="d-flex align-items-center justify-content-between">
                                                                                                <label
                                                                                                    class="m-0">{{ __('Product Total') }}</label>
                                                                                                <span>{{ Session::get('currencySymbol') }}{{decimal_format($product_total_count
                                                                                                    *
                                                                                                    $clientCurrency->doller_compare)}}</span>
                                                                                            </li>
                                                                                            @if ($vendor->discount_amount > 0)
                                                                                                <li
                                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                                    <label
                                                                                                        class="m-0">{{ __('Coupon Discount') }}</label>
                                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->discount_amount
                                                                                                        *
                                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                                </li>
                                                                                            @endif
                                                                                            @if ($vendor->delivery_fee > 0)
                                                                                                <li
                                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                                    <label
                                                                                                        class="m-0">{{ __('Delivery Fee') }}</label>
                                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->delivery_fee
                                                                                                        *
                                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                                </li>
                                                                                            @endif
                                                                                            <li
                                                                                                class="grand_total d-flex align-items-center justify-content-between">
                                                                                                <label
                                                                                                    class="m-0">{{ __('Amount') }}</label>
                                                                                                @php
                                                                                                    $product_subtotal_amount = $product_total_count - $vendor->discount_amount + $vendor->delivery_fee;
                                                                                                    $subtotal_order_price += $product_subtotal_amount;
                                                                                                @endphp
                                                                                                <span>{{ Session::get('currencySymbol') }}{{decimal_format($product_subtotal_amount
                                                                                                    *
                                                                                                    $clientCurrency->doller_compare)}}</span>
                                                                                            </li>

                                                                                            @if (isset($hidereturn) && $hidereturn != 1 && isset($vendor->vendor->return_request) && $vendor->vendor->return_request)
                                                                                                <button
                                                                                                    class="return-order-product btn btn-solid"
                                                                                                    data-id="{{ $order->id ?? 0 }}"
                                                                                                    data-vendor_id="{{ $vendor->vendor_id ?? 0 }}">
                                                                                                    <td class="text-center"
                                                                                                        colspan="3">
                                                                                                        {{ __('Return') }}
                                                                                                </button>
                                                                                            @endif

                                                                                            <button class="repeat-order-product btn btn-solid mr-2"
                                                                                                    data-id="{{ $order->id ?? 0 }}"
                                                                                                    data-order_vendor_id="{{ $vendor->id ?? 0 }}"
                                                                                                    data-vendor_id="{{ $vendor->vendor_id ?? 0 }}">
                                                                                                    <td class="text-center"
                                                                                                        colspan="3">{{ __('Repeat Order') }}</button>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="col-md-3 mb-3 pl-lg-0">
                                                                        <div class="card-box p-2 mb-0 h-100">
                                                                            <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                                                <li
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <label
                                                                                        class="m-0">{{ __('Sub Total') }}</label>
                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_amount
                                                                                        + $order->total_delivery_fee *
                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                </li>
                                                                                @if ($order->wallet_amount_used > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Wallet') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->wallet_amount_used
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->loyalty_amount_saved > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Loyalty Used') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->loyalty_amount_saved
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->taxable_amount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Tax') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->taxable_amount
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->total_service_fee > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Service Fee') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_service_fee
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->tip_amount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Tip Amount') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->tip_amount
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->subscription_discount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Subscription Discount') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->subscription_discount
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->total_discount_calculate > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Discount') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_discount_calculate
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->total_delivery_fee > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Delivery Fee') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_delivery_fee
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                <li
                                                                                    class="grand_total d-flex align-items-center justify-content-between">
                                                                                    <label
                                                                                        class="m-0">{{ __('Total Payable') }}</label>
                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->payable_amount-$order->total_discount_calculate
                                                                                        *
                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                </li>
                                                                            </ul>

                                                                            @if ($client_preference_detail->tip_after_order == 1 && $order->tip_amount <= 0 && $payments > 0)
                                                                                <hr>
                                                                                <div class="row">
                                                                                    <div class="col-12">
                                                                                        <div class="mb-2">
                                                                                        @if(getNomenclatureName('Want To Tip', true)!='Want To Tip') {{ getNomenclatureName('Want To Tip', true) }} @else {{__('Do you want to give a tip?')}} @endif
                                                                                        </div>
                                                                                        <div class="tip_radio_controls">
                                                                                            @if ($order->payable_amount > 0)
                                                                                                <input type="radio"
                                                                                                    class="tip_radio"
                                                                                                    id="control_01"
                                                                                                    name="select{{ $order->order_number }}"
                                                                                                    value="{{ round($order->payable_amount * 0.05, 2) }}">
                                                                                                <label class="tip_label"
                                                                                                    for="control_01">
                                                                                                    <h5 class="m-0"
                                                                                                        id="tip_5">
                                                                                                        {{ Session::get('currencySymbol') }}{{ decimal_format($order->payable_amount * 0.05) }}
                                                                                                    </h5>
                                                                                                    <p class="m-0">
                                                                                                        5%</p>
                                                                                                </label>

                                                                                                <input type="radio"
                                                                                                    class="tip_radio"
                                                                                                    id="control_02"
                                                                                                    name="select{{ $order->order_number }}"
                                                                                                    value="{{ round($order->payable_amount * 0.1, 2) }}">
                                                                                                <label class="tip_label"
                                                                                                    for="control_02">
                                                                                                    <h5 class="m-0"
                                                                                                        id="tip_10">
                                                                                                        {{ Session::get('currencySymbol') }}{{ decimal_format($order->payable_amount * 0.1) }}
                                                                                                    </h5>
                                                                                                    <p class="m-0">
                                                                                                        10%</p>
                                                                                                </label>

                                                                                                <input type="radio"
                                                                                                    class="tip_radio"
                                                                                                    id="control_03"
                                                                                                    name="select{{ $order->order_number }}"
                                                                                                    value="{{ round($order->payable_amount * 0.15, 2) }}">
                                                                                                <label class="tip_label"
                                                                                                    for="control_03">
                                                                                                    <h5 class="m-0"
                                                                                                        id="tip_15">
                                                                                                        {{ Session::get('currencySymbol') }}{{ decimal_format($order->payable_amount * 0.15) }}
                                                                                                    </h5>
                                                                                                    <p class="m-0">
                                                                                                        15%</p>
                                                                                                </label>

                                                                                                <input type="radio"
                                                                                                    class="tip_radio"
                                                                                                    id="custom_control{{ $order->order_number }}"
                                                                                                    name="select{{ $order->order_number }}"
                                                                                                    value="custom">
                                                                                                <label class="tip_label"
                                                                                                    for="custom_control{{ $order->order_number }}">
                                                                                                    <h5 class="m-0">
                                                                                                        {{ __('Custom') }}<br>{{ __('Amount') }}
                                                                                                    </h5>
                                                                                                </label>
                                                                                            @else
                                                                                                <input type="hidden"
                                                                                                    class="tip_radio"
                                                                                                    id="custom_control{{ $order->order_number }}"
                                                                                                    name="select{{ $order->order_number }}"
                                                                                                    value="custom" checked>

                                                                                            @endif
                                                                                        </div>
                                                                                        <div
                                                                                            class="custom_tip mb-1 @if ($order->payable_amount > 0)  d-none @endif">
                                                                                            <input
                                                                                                class="input-number form-control"
                                                                                                name="custom_tip_amount{{ $order->order_number }}"
                                                                                                id="custom_tip_amount{{ $order->order_number }}"
                                                                                                placeholder="{{ __('Enter Custom Amount') }}"
                                                                                                type="number" value=""
                                                                                                min="0.01" step="0.01">
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-md-6 text-md-right text-center">
                                                                                            <button type="button"
                                                                                                class="btn btn-solid topup_wallet_btn_tip topup_wallet_btn_for_tip"
                                                                                                data-order_number={{ $order->order_number }}
                                                                                                data-payableamount={{ $order->payable_amount }}>{{ __('Submit') }}</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <hr class="my-2">
                                                                            @endif

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="col-12">
                                                            <div class="no-gutters order_head">
                                                                <h4 class="text-center">{{ __('No Past Order Found') }}
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                {{ $pastOrders->appends(['pageType' => 'pastOrders'])->links() }}
                                            </div>
                                            <div class="tab-pane fade return-order {{ Request::query('pageType') == 'returnOrders' ? 'active show' : '' }}"
                                                id="return_order" role="tabpanel" aria-labelledby="return_order-tab">
                                                <div class="row">
                                                    @if ($returnOrders->isNotEmpty())
                                                        @foreach ($returnOrders as $key => $order)
                                                            @if ($order->orderStatusVendor->isNotEmpty())
                                                                <div class="col-12">
                                                                    <div class="row no-gutters order_head">
                                                                        <div class="col-md-3 alOrderStatus">
                                                                            <h4>{{ __('Order Number') }}</h4>
                                                                            <span>#{{ $order->order_number }}</span>
                                                                        </div>
                                                                        <div class="col-md-3 alOrderStatus">
                                                                            <h4>{{ __('Date & Time') }}</h4>
                                                                            <span>{{ dateTimeInUserTimeZone($order->created_at, $timezone) }}</span>
                                                                        </div>
                                                                        <div class="col-md-3 alOrderStatus">
                                                                            <h4>{{ __('Customer Name') }}</h4>
                                                                            <span><a class="text-capitalize">{{ $order->user->name }}</a></span>
                                                                        </div>
                                                                        @if ($client_preference_detail->business_type != 'taxi')
                                                                            <div class="col-md-3">
                                                                                <h4>{{ __('Address') }}</h4>
                                                                                @if($order->luxury_option_id == 3)

                                                                                    <span class="ellipsis" data-toggle="tooltip"
                                                                                        data-placement="top" title="" >
                                                                                        @if ( count($order->vendors) > 0)
                                                                                            {{ $order->vendors->first() ? ($order->vendors->first()->vendor ? ($order->vendors->first()->vendor->address) : __('NA') ) : __('NA') }}
                                                                                        @else
                                                                                            NA
                                                                                        @endif
                                                                                    </span>
                                                                                @else
                                                                                    <span class="ellipsis"
                                                                                        data-toggle="tooltip" data-placement="top"
                                                                                        title="">
                                                                                        @if ($order->address)
                                                                                            {{ $order->address->address }},
                                                                                            {{ $order->address->street }},
                                                                                            {{ $order->address->city }},
                                                                                            {{ $order->address->state }},
                                                                                            {{ $order->address->country }}
                                                                                            {{ $order->address->pincode }}
                                                                                        @else
                                                                                            NA
                                                                                        @endif
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-md-9 mb-3">
                                                                            @php
                                                                                $subtotal_order_price = $total_order_price = $total_tax_order_price = 0;
                                                                            @endphp
                                                                            @foreach ($order->vendors as $key => $vendor)
                                                                                @php
                                                                                    $product_total_count = $product_subtotal_amount = $product_taxable_amount = 0;
                                                                                @endphp
                                                                                <div
                                                                                    class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0">
                                                                                    <span class="left_arrow pulse"></span>
                                                                                    <div class="row">
                                                                                        <div class="col-5 col-sm-3">
                                                                                            <h5 class="m-0"></h5>
                                                                                            <ul class="status_box mt-1 pl-0">
                                                                                                @if ($vendor->dineInTable)
                                                                                                    <li>
                                                                                                        <h5
                                                                                                            class="mb-1">
                                                                                                            {{ __('Dine-in') }}
                                                                                                        </h5>
                                                                                                        <h6
                                                                                                            class="m-0">
                                                                                                            {{ $vendor->dineInTableName }}
                                                                                                        </h6>
                                                                                                        <h6
                                                                                                            class="m-0">
                                                                                                            Category :
                                                                                                            {{ $vendor->dineInTableCategory }}
                                                                                                        </h6>
                                                                                                        <h6
                                                                                                            class="m-0">
                                                                                                            Capacity :
                                                                                                            {{ $vendor->dineInTableCapacity }}
                                                                                                        </h6>
                                                                                                    </li>
                                                                                                @endif
                                                                                            </ul>
                                                                                        </div>
                                                                                        <div class="col-7 col-sm-4">
                                                                                            <ul class="product_list p-0 m-0 text-center">
                                                                                                @foreach ($vendor->products as $product)
                                                                                                    @if ($vendor->vendor_id == $product->vendor_id)
                                                                                                        @php
                                                                                                            $pro_rating = $product->productRating->rating ?? 0;
                                                                                                        @endphp
                                                                                                        <li class="text-center mb-0 alOrderImg">
                                                                                                            <img src="{{ $product->image_url }}" alt="">
                                                                                                            <span class="item_no position-absolute">x{{ $product->quantity }}</span>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <label
                                                                                                                class="items_price">{{ Session::get('currencySymbol') }}{{ $product->price * $clientCurrency->doller_compare }}</label>
                                                                                                            <label
                                                                                                                class="rating-star add_edit_review"
                                                                                                                data-id="{{ $product->productRating->id ?? 0 }}"
                                                                                                                data-order_vendor_product_id="{{ $product->id ?? 0 }}">
                                                                                                                <i
                                                                                                                    class="fa fa-star{{ $pro_rating >= 1 ? '' : '-o' }}"></i>
                                                                                                                <i
                                                                                                                    class="fa fa-star{{ $pro_rating >= 2 ? '' : '-o' }}"></i>
                                                                                                                <i
                                                                                                                    class="fa fa-star{{ $pro_rating >= 3 ? '' : '-o' }}"></i>
                                                                                                                <i
                                                                                                                    class="fa fa-star{{ $pro_rating >= 4 ? '' : '-o' }}"></i>
                                                                                                                <i
                                                                                                                    class="fa fa-star{{ $pro_rating >= 5 ? '' : '-o' }}"></i>
                                                                                                            </label>
                                                                                                            {{ __($product->productReturn->status ?? '') }}
                                                                                                        </li>
                                                                                                        @php
                                                                                                            $product_total_price = $product->price * $clientCurrency->doller_compare;
                                                                                                            $product_total_count += $product->quantity * $product_total_price;
                                                                                                            $product_taxable_amount += $product->taxable_amount;
                                                                                                            $total_tax_order_price += $product->taxable_amount;
                                                                                                        @endphp
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                        <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                                            <ul
                                                                                                class="price_box_bottom m-0 p-0">
                                                                                                <li
                                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                                    <label
                                                                                                        class="m-0">{{ __('Product Total') }}</label>
                                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->subtotal_amount
                                                                                                        *
                                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                                </li>
                                                                                                @if ($vendor->discount_amount > 0)
                                                                                                    <li
                                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                                        <label
                                                                                                            class="m-0">{{ __('Coupon Discount') }}</label>
                                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->discount_amount
                                                                                                            *
                                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                                    </li>
                                                                                                @endif
                                                                                                @if ($vendor->delivery_fee > 0)
                                                                                                    <li
                                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                                        <label
                                                                                                            class="m-0">{{ __('Delivery Fee') }}</label>
                                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->delivery_fee
                                                                                                            *
                                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                                    </li>
                                                                                                @endif
                                                                                                <li
                                                                                                    class="grand_total d-flex align-items-center justify-content-between">
                                                                                                    <label
                                                                                                        class="m-0">{{ __('Amount') }}</label>
                                                                                                    @php
                                                                                                        $product_subtotal_amount = $product_total_count - $vendor->discount_amount + $vendor->delivery_fee;
                                                                                                        $subtotal_order_price += $product_subtotal_amount;
                                                                                                        $total_order_price += $product_subtotal_amount + $total_tax_order_price;
                                                                                                    @endphp
                                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->payable_amount
                                                                                                        *
                                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                                </li>


                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                        <div class="col-md-3 mb-3 pl-lg-0">
                                                                            <div class="card-box p-2 mb-0 h-100">
                                                                                <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ 'Sub Total' }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_amount
                                                                                            + $order->total_delivery_fee *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                    @if ($order->wallet_amount_used > 0)
                                                                                        <li
                                                                                            class="d-flex align-items-center justify-content-between">
                                                                                            <label
                                                                                                class="m-0">{{ __('Wallet') }}</label>
                                                                                            <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->wallet_amount_used
                                                                                                *
                                                                                                $clientCurrency->doller_compare)}}</span>
                                                                                        </li>
                                                                                    @endif
                                                                                    @if ($order->loyalty_amount_saved > 0)
                                                                                        <li
                                                                                            class="d-flex align-items-center justify-content-between">
                                                                                            <label
                                                                                                class="m-0">{{ __('Loyalty Used') }}</label>
                                                                                            <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->loyalty_amount_saved
                                                                                                *
                                                                                                $clientCurrency->doller_compare)}}</span>
                                                                                        </li>
                                                                                    @endif
                                                                                    @if ($order->taxable_amount > 0)
                                                                                        <li
                                                                                            class="d-flex align-items-center justify-content-between">
                                                                                            <label
                                                                                                class="m-0">{{ __('Tax') }}</label>
                                                                                            <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->taxable_amount
                                                                                                *
                                                                                                $clientCurrency->doller_compare)}}</span>
                                                                                        </li>
                                                                                    @endif
                                                                                    @if ($order->total_service_fee > 0)
                                                                                        <li
                                                                                            class="d-flex align-items-center justify-content-between">
                                                                                            <label
                                                                                                class="m-0">{{ __('Service Fee') }}</label>
                                                                                            <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_service_fee
                                                                                                *
                                                                                                $clientCurrency->doller_compare)}}</span>
                                                                                        </li>
                                                                                    @endif
                                                                                    @if ($order->tip_amount > 0)
                                                                                        <li
                                                                                            class="d-flex align-items-center justify-content-between">
                                                                                            <label
                                                                                                class="m-0">{{ __('Tip Amount') }}</label>
                                                                                            <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->tip_amount
                                                                                                *
                                                                                                $clientCurrency->doller_compare)}}</span>
                                                                                        </li>
                                                                                    @endif
                                                                                    @if ($order->subscription_discount > 0)
                                                                                        <li
                                                                                            class="d-flex align-items-center justify-content-between">
                                                                                            <label
                                                                                                class="m-0">{{ __('Subscription Discount') }}</label>
                                                                                            <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->subscription_discount
                                                                                                *
                                                                                                $clientCurrency->doller_compare)}}</span>
                                                                                        </li>
                                                                                    @endif
                                                                                    @if ($order->total_delivery_fee > 0)
                                                                                        <li
                                                                                            class="d-flex align-items-center justify-content-between">
                                                                                            <label
                                                                                                class="m-0">{{ __('Delivery Fee') }}</label>
                                                                                            <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_delivery_fee
                                                                                                *
                                                                                                $clientCurrency->doller_compare)}}</span>
                                                                                        </li>
                                                                                    @endif
                                                                                    <li
                                                                                        class="grand_total d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Total Payable') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->payable_amount
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <div class="col-12">
                                                            <div class="no-gutters order_head">
                                                                <h4 class="text-center">{{ __('No Return Requests') }}
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                {{ $returnOrders->appends(['pageType' => 'returnOrders'])->links() }}
                                            </div>
                                            <div class="tab-pane fade rejected-order {{ Request::query('pageType') == 'rejectedOrders' ? 'active show' : '' }}"
                                                id="rejected_order" role="tabpanel" aria-labelledby="rejected_order-tab">
                                                <div class="row">
                                                    @if ($rejectedOrders->isNotEmpty())
                                                        @foreach ($rejectedOrders as $key => $order)
                                                            <div class="col-12">
                                                                <div class="row no-gutters order_head">
                                                                    <div class="col-md-3 alOrderStatus">
                                                                        <h4>{{ __('Order Number') }}</h4>
                                                                        <span>#{{ $order->order_number }}</span>
                                                                    </div>
                                                                    <div class="col-md-3 alOrderStatus">
                                                                        <h4>{{ __('Date & Time') }}</h4>
                                                                        <span>{{ dateTimeInUserTimeZone($order->created_at, $timezone) }}</span>
                                                                    </div>
                                                                    <div class="col-md-3 alOrderStatus">
                                                                        <h4>{{ __('Customer Name') }}</h4>
                                                                        <span><a class="text-capitalize">{{ $order->user->name }}</a></span>
                                                                    </div>
                                                                    @if ($client_preference_detail->business_type != 'taxi')
                                                                        <div class="col-md-3">
                                                                            <h4>{{ __('Address') }}</h4>
                                                                            @if($order->luxury_option_id == 3)

                                                                                <span class="ellipsis" data-toggle="tooltip"
                                                                                    data-placement="top" title="" >
                                                                                    @if ( count($order->vendors) > 0)
                                                                                        {{ $order->vendors->first() ? ($order->vendors->first()->vendor ? ($order->vendors->first()->vendor->address) : __('NA') ) : __('NA') }}
                                                                                    @else
                                                                                        NA
                                                                                    @endif
                                                                                </span>
                                                                            @else
                                                                                <span class="ellipsis" data-toggle="tooltip"
                                                                                    data-placement="top" title="">
                                                                                    @if ($order->address)
                                                                                        {{ $order->address->address }},
                                                                                        {{ $order->address->street }},
                                                                                        {{ $order->address->city }},
                                                                                        {{ $order->address->state }},
                                                                                        {{ $order->address->country }}
                                                                                        {{ $order->address->pincode }}
                                                                                    @else
                                                                                        NA
                                                                                    @endif
                                                                                </span>
                                                                                @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="row no-gutters order_data d-none">
                                                                    <div class="col-md-3">#{{ $order->order_number }}
                                                                    </div>
                                                                    {{-- <div class="col-md-3">{{convertDateTimeInTimeZone($order->created_at, $timezone, 'l, F d, Y, h:i A')}}</div> --}}
                                                                    <div class="col-md-3">
                                                                        {{ dateTimeInUserTimeZone($order->created_at, $timezone) }}
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <a class="text-capitalize">{{ $order->user->name }}</a>
                                                                    </div>
                                                                    @if ($client_preference_detail->business_type != 'taxi')
                                                                        <div class="col-md-3">
                                                                            <span class="ellipsis" data-toggle="tooltip"
                                                                                data-placement="top" title="">
                                                                                @if ($order->address)
                                                                                    {{ $order->address->address }},
                                                                                    {{ $order->address->street }},
                                                                                    {{ $order->address->city }},
                                                                                    {{ $order->address->state }},
                                                                                    {{ $order->address->country }}
                                                                                    {{ $order->address->pincode }}
                                                                                @else
                                                                                    NA
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-md-9 mb-3">
                                                                        @php
                                                                            $subtotal_order_price = $total_order_price = $total_tax_order_price = 0;
                                                                        @endphp
                                                                        @foreach ($order->vendors as $key => $vendor)
                                                                            @php
                                                                                $product_total_count = $product_subtotal_amount = $product_taxable_amount = 0;
                                                                            @endphp
                                                                            <div
                                                                                class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0">
                                                                                @if ($vendor->delivery_fee > 0 || !empty($order->scheduled_date_time) || $order->luxury_option_id > 0)
                                                                                    <div
                                                                                        class="progress-order font-12  d-flex align-items-center justify-content-between pr-2">
                                                                                        @if ($order->luxury_option_id > 0)
                                                                                            @php
                                                                                                $luxury_option = \App\Models\LuxuryOption::where('id', $order->luxury_option_id)->first();
                                                                                                if ($luxury_option->title == 'takeaway') {
                                                                                                    $luxury_option_name = getNomenclatureName('Takeaway', Session::get('customerLanguage'), false);
                                                                                                } elseif ($luxury_option->title == 'dine_in') {
                                                                                                    $luxury_option_name = 'Dine-In';
                                                                                                } else {

                                                                                                    //$luxury_option_name = 'Delivery';
                                                                                                    $luxury_option_name = getNomenclatureName($luxury_option->title);
                                                                                                }
                                                                                            @endphp
                                                                                            <span
                                                                                                class="badge badge-info ml-2 my-1">{{ __($luxury_option_name) }}</span>
                                                                                        @endif

                                                                                        @if ($order->is_gift == '1')
                                                                                            <div class="gifted-icon">
                                                                                                <img class="p-1 align-middle"
                                                                                                    src="{{ asset('assets/images/gifts_icon.png') }}"
                                                                                                    alt="">
                                                                                                <span
                                                                                                    class="align-middle">This
                                                                                                    is a gift.</span>
                                                                                            </div>
                                                                                        @endif
                                                                                        <button class="chat-icon btn btn-solid" style="font-size:10px; padding: 0 5px; float: right; margin-top: 5px;" >{{__('Chat')}}</button>
                                                                                    </div>
                                                                                @endif
                                                                                <span class="left_arrow pulse"></span>
                                                                                <div class="row">
                                                                                    <div class="col-5 col-sm-3">
                                                                                        <h5 class="m-0">
                                                                                            {{ __('Order Status') }} </h5>
                                                                                        <ul class="status_box mt-1 pl-0">
                                                                                            @if (!empty($vendor->order_status))
                                                                                                <li>
                                                                                                    <label class="m-0 in-progress">{{ __(ucfirst($vendor->order_status)) }} </label>
                                                                                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="{{$vendor->reject_reason}}" aria-hidden="true"></i>
                                                                                                </li>
                                                                                            @endif
                                                                                        </ul>

                                                                                    </div>
                                                                                    <div class="col-7 col-sm-4">
                                                                                        <ul
                                                                                            class="product_list p-0 m-0 text-center">
                                                                                            @foreach ($vendor->products as $product)
                                                                                                @if ($vendor->vendor_id == $product->vendor_id)
                                                                                                    <li class="text-center mb-0 alOrderImg">
                                                                                                        <img src="{{ $product->image_url }}" alt="">
                                                                                                        <span class="item_no position-absolute">x{{ $product->quantity }}</span>
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <label class="items_price">{{ Session::get('currencySymbol') }}{{ decimal_format($product->price * $clientCurrency->doller_compare) }}</label>
                                                                                                    </li>
                                                                                                    @php
                                                                                                        $product_total_price = $product->price * $clientCurrency->doller_compare;
                                                                                                        $product_total_count += $product->quantity * $product_total_price;
                                                                                                        $product_taxable_amount += $product->taxable_amount;
                                                                                                        $total_tax_order_price += $product->taxable_amount;
                                                                                                    @endphp
                                                                                                @endif
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </div>
                                                                                    <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                                        <ul class="price_box_bottom m-0 p-0">
                                                                                            <li
                                                                                                class="d-flex align-items-center justify-content-between">
                                                                                                <label
                                                                                                    class="m-0">{{ __('Product Total') }}</label>
                                                                                                <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->subtotal_amount
                                                                                                    *
                                                                                                    $clientCurrency->doller_compare)}}</span>
                                                                                            </li>
                                                                                            @if ($vendor->discount_amount > 0)
                                                                                                <li
                                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                                    <label
                                                                                                        class="m-0">{{ __('Coupon Discount') }}</label>
                                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->discount_amount
                                                                                                        *
                                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                                </li>
                                                                                            @endif
                                                                                            @if ($vendor->delivery_fee > 0)
                                                                                                <li
                                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                                    <label
                                                                                                        class="m-0">{{ __('Delivery Fee') }}</label>
                                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->delivery_fee
                                                                                                        *
                                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                                </li>
                                                                                            @endif


                                                                                            <li
                                                                                                class="grand_total d-flex align-items-center justify-content-between">
                                                                                                <label
                                                                                                    class="m-0">{{ __('Amount') }}</label>
                                                                                                @php
                                                                                                    $product_subtotal_amount = $product_total_count - $vendor->discount_amount + $vendor->delivery_fee;
                                                                                                    $subtotal_order_price += $product_subtotal_amount;
                                                                                                @endphp
                                                                                                <span>{{ Session::get('currencySymbol') }}{{decimal_format($vendor->payable_amount
                                                                                                    *
                                                                                                    $clientCurrency->doller_compare)}}</span>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                                sdf
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="col-md-3 mb-3 pl-lg-0">
                                                                        <div class="card-box p-2 mb-0 h-100">
                                                                            <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                                                <li
                                                                                    class="d-flex align-items-center justify-content-between">
                                                                                    <label
                                                                                        class="m-0">{{ __('Sub Total') }}</label>
                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_amount
                                                                                        *
                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                </li>
                                                                                @if ($order->wallet_amount_used > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Wallet') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->wallet_amount_used
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->loyalty_amount_saved > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Loyalty Used') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->loyalty_amount_saved
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->taxable_amount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Tax') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->taxable_amount
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->total_service_fee > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Service Fee') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_service_fee
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->tip_amount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Tip Amount') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->tip_amount
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->subscription_discount > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Subscription Discount') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->subscription_discount
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->total_discount_calculate > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Discount') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_discount_calculate
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                @if ($order->total_delivery_fee > 0)
                                                                                    <li
                                                                                        class="d-flex align-items-center justify-content-between">
                                                                                        <label
                                                                                            class="m-0">{{ __('Delivery Fee') }}</label>
                                                                                        <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->total_delivery_fee
                                                                                            *
                                                                                            $clientCurrency->doller_compare)}}</span>
                                                                                    </li>
                                                                                @endif
                                                                                <li
                                                                                    class="grand_total d-flex align-items-center justify-content-between">
                                                                                    <label
                                                                                        class="m-0">{{ __('Total Payable') }}</label>
                                                                                    <span>{{ Session::get('currencySymbol') }}{{decimal_format($order->payable_amount
                                                                                        - $order->total_discount_calculate *
                                                                                        $clientCurrency->doller_compare)}}</span>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="col-12">
                                                            <div class="no-gutters order_head">
                                                                <h4 class="text-center">{{ __('No Rejected/Cancel Order Found') }}
                                                                </h4>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                {{ $pastOrders->appends(['pageType' => 'rejectedOrders'])->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-account box-info">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade product-rating" id="product_rating" tabindex="-1" aria-labelledby="product_ratingLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div id="review-rating-form-modal">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade driver-rating" id="driver_rating" tabindex="-1" aria-labelledby="driver_ratingLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div id="driver-review-rating-form-modal">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade return-order" id="return_order_model" tabindex="-1" aria-labelledby="return_orderLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <div id="return-order-form-modal"></div>
                </div>
            </div>
        </div>
    </div>

<!-- start cancel order -->
<div class="modal fade vendor-order-cancel order_popop" id="cancel_order" tabindex="-1" aria-labelledby="cancel_orderLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <div id="cancel-order-form-modal">
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- end cancel order -->

    <!-- tip after order complete -->
    @include('frontend.modals.tip_after_order')

    <!-- end tip order after complete -->
    <!-- repeat order modal -->
    <div class="modal fade remove-cart-modal" id="repeat_cart_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_cartLabel" style="background-color: rgba(0,0,0,0.8);">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header pb-0">
              <h5 class="modal-title" id="remove_cartLabel">{{__('Repeat Order')}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body text-center">
              <h6 class="m-0 px-3">{{__('This change will remove all your cart products. Do you really want to continue ?')}}</h6>
            </div>
            <div class="modal-footer flex-nowrap justify-content-center align-items-center">
              <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button>
              <button type="button" class="btn btn-solid" id="repeat_cart_button" data-cart_id="">{{__('Remove')}}</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade remove-cart-modal" id="repeat_cart_modal1" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="remove_cartLabel" style="background-color: rgba(0,0,0,0.8);">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header pb-0">
              <h5 class="modal-title" id="remove_cartLabel">{{__('Repeat Order')}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body text-center">
              <h6 class="m-0 px-3">{{__('Are u sure u want to repeat same order')}}</h6>
            </div>
            <div class="modal-footer flex-nowrap justify-content-center align-items-center">
              <button type="button" class="btn btn-solid black-btn" data-dismiss="modal">{{__('Cancel')}}</button>
              <button type="button" class="btn btn-solid" id="repeat_cart_button" data-cart_id="">{{__('Yes')}}</button>
            </div>
          </div>
        </div>
      </div>
    <!-- end repat order modal -->


@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    @if(in_array('razorpay',$client_payment_options))
    <script type="text/javascript" src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @endif
    @if(in_array('stripe',$client_payment_options) || in_array('stripe_fpx',$client_payment_options) || in_array('stripe_oxxo',$client_payment_options) || in_array('stripe_ideal',$client_payment_options))
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
    @endif
    @if(in_array('stripe_oxxo',$client_payment_options))
    <script>
    var stripe_oxxo_publishable_key = '{{ $stripe_oxxo_publishable_key }}';
    </script>
    @endif

    @if(in_array('stripe_ideal',$client_payment_options))
    <script>
    var stripe_ideal_publishable_key = '{{ $stripe_ideal_publishable_key }}';
    </script>
    @endif

    @if(in_array('yoco',$client_payment_options))
    <script src="https://js.yoco.com/sdk/v1/yoco-sdk-web.js"></script>
    <script>
        // Replace the supplied `publicKey` with your own.
        // Ensure that in production you use a production public_key.
        var sdk = new window.YocoSDK({
            publicKey: yoco_public_key
        });
    </script>
    @endif
    @if(in_array('checkout',$client_payment_options))
    <script src="https://cdn.checkout.com/js/framesv2.min.js"></script>
    @endif
    <script src="{{ asset('js/tip_after_order.js') }}"></script>
    @if(in_array('kongapay',$client_payment_options))
    <script src="https://kongapay-pg.kongapay.com/js/v1/production/pg.js"></script>
    @endif
    @if(in_array('flutterwave',$client_payment_options))
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    @endif
    @if(in_array('payphone',$client_payment_options))
    <script src="https://pay.payphonetodoesposible.com/api/button/js?appId={{$payphone_id}}"></script>
    @endif
    @if(in_array('khalti',$client_payment_options))
        <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    @endif
    <script src="{{ asset('js/payment.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/developer.js')}}"></script>
    <script type="text/javascript">
        $(document).delegate(".topup_wallet_btn_tip", "click", function() {
            $('#topup_wallet').modal('show');
            var payable_amount = $(this).attr('data-payableamount');
            //  if(payable_amount > 0)
            //  {
            //     $('#topup_wallet').modal('show');
            //  }
            var order_number = $(this).attr('data-order_number');
            var input_name = "select" + order_number;
            var custom_tip_amount = "custom_tip_amount" + order_number;

            var select_tip = $('input[name="' + input_name + '"]:checked').val();

            if (select_tip != 'custom' && select_tip != undefined) {
                $('.wallet_balance').html(select_tip);
                var tip_amount = select_tip;
            } else {
                $('.wallet_balance').html($('input[name="' + custom_tip_amount + '"]').val());
                var tip_amount = $('input[name="' + custom_tip_amount + '"]').val();
            }

            $("#wallet_amount").val(tip_amount);
            $("#cart_tip_amount").val(tip_amount);
            $("#order_number").val(order_number);

        });
        var ajaxCall = 'ToCancelPrevReq';
        var credit_tip_url = "{{ route('user.tip_after_order') }}";
        var payment_stripe_url = "{{ route('payment.stripe') }}";
        var create_konga_hash_url = "{{route('kongapay.createHash')}}";
        var create_payphone_url = "{{route('payphone.createHash')}}";
        var create_easypaisa_hash_url = "{{route('easypaisa.createHash')}}";
        var create_dpo_tocken = "{{route('dpo.createTocken')}}";
        var create_windcave_hash_url = "{{route('windcave.createHash')}}";
        var create_paytech_hash_url = "{{route('paytech.createHash')}}";
        var create_flutterwave_url = "{{route('flutterwave.createHash')}}";
        var create_viva_wallet_pay_url = "{{route('vivawallet.pay')}}";
        var create_mvodafone_pay_url = "{{route('mvodafone.pay')}}";
        var create_ccavenue_url = "{{route('ccavenue.pay')}}";
        var post_payment_via_gateway_url = "{{route('payment.gateway.postPayment', ':gateway')}}";
        var payment_retrive_stripe_fpx_url = "{{url('payment/retrieve/stripe_fpx')}}";
        var payment_create_stripe_fpx_url = "{{url('payment/create/stripe_fpx')}}";
        var payment_create_stripe_oxxo_url = "{{url('payment/create/stripe_oxxo')}}";
        var payment_create_stripe_ideal_url = "{{url('payment/create/stripe_ideal')}}";
        var payment_retrive_stripe_ideal_url = "{{url('payment/retrieve/stripe_ideal')}}";
        var payment_paypal_url = "{{ route('payment.paypalPurchase') }}";
        var payment_yoco_url = "{{ route('payment.yocoPurchase') }}";
        var payment_checkout_url = "{{route('payment.checkoutPurchase')}}";
        var payment_paylink_url = "{{ route('payment.paylinkPurchase') }}";
        var wallet_payment_options_url = "{{ route('wallet.payment.option.list') }}";
        var payment_success_paypal_url = "{{ route('payment.paypalCompletePurchase') }}";
        var payment_paystack_url = "{{ route('payment.paystackPurchase') }}";
        var payment_success_paystack_url = "{{ route('payment.paystackCompletePurchase') }}";
        var payment_payfast_url = "{{ route('payment.payfastPurchase') }}";
        var payment_khalti_url = "{{route('payment.khaltiVerification')}}";
        var payment_khalti_complete_purchase = "{{route('payment.khaltiCompletePurchase')}}";
        var amount_required_error_msg = "{{ __('Please enter amount.') }}";
        var payment_method_required_error_msg = "{{ __('Please select payment method.') }}";
        var check_pickup_schedule_slots = "{{route('cart.check_pickup_schedule_slots')}}";
        var check_dropoff_schedule_slots = "{{route('cart.check_dropoff_schedule_slots')}}";
        var create_mtn_momo_token = "{{route('mtn.momo.createTocken')}}";
        var payment_plugnpay_url = "{{route('payment.plugnpay.beforePayment')}}";
    </script>

    <script type="text/javascript">
        localStorage.removeItem('check_pk_date_check');
        localStorage.removeItem('check_date_check');
        localStorage.removeItem('check_pickup_order_date');
        var ajaxCall = 'ToCancelPrevReq';
        $('.verifyEmail').click(function() {
            verifyUser('email');
        });
        $('.verifyPhone').click(function() {
            verifyUser('phone');
        });

          // Added by Ovi
          function checkDates(prevPickup, prevDropoff, reschedulingCharges, pickupCancellingCharges, newPickupClass, newDropoffClass){
            // var pickup_schedule_datetime = $('.'+newPickupClass).val();
            // var dropoff_schedule_datetime = $('.'+newDropoffClass).val();
            var pickup_schedule_datetime  = "{{date('Y-m-d')}}";
            var dropoff_schedule_datetime = "{{date('Y-m-d')}}";

            if(Date.parse(pickup_schedule_datetime) == Date.parse(prevPickup)){
                if(localStorage.getItem('check_pk_date_check') == null){
                    localStorage.setItem("check_pk_date_check", true);
                    Swal.fire({
                        icon: 'info',
                        text: 'You are trying to reschedule the order on the day of pickup, additional '+pickupCancellingCharges+' will be debited from your wallet.',
                        confirmButtonText: 'Ok',
                    });
                    return false;
                }
            }

            if(Date.parse(dropoff_schedule_datetime) == Date.parse(prevDropoff)){
                if(localStorage.getItem('check_date_check') == null){
                    localStorage.setItem("check_date_check", true);
                    Swal.fire({
                        icon: 'info',
                        text: 'You are trying to reschedule the order on the day of delivery, additional '+reschedulingCharges+' will be debited from your wallet.',
                        confirmButtonText: 'Ok',
                    });
                    return false;
                }
                return true;
            }
        }

        function verifyUser($type = 'email') {
            ajaxCall = $.ajax({
                type: "post",
                dataType: "json",
                url: "{{ route('verifyInformation', Auth::user()->id) }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "type": $type,
                },
                beforeSend: function() {
                    if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                        ajaxCall.abort();
                    }
                },
                success: function(response) {
                    var res = response.result;

                },
                error: function(data) {

                },
            });
        }
        $('body').on('click', '.add_edit_driver_review', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            var order_vendor_product_id = $(this).data('order_vendor_product_id');
            $.get('/rating/get-driver-rating?id=' + id + '&order_vendor_product_id=' + order_vendor_product_id,
                function(markup) {
                    $('#driver_rating').modal('show');
                    $('#driver-review-rating-form-modal').html(markup);
                });
        });
        $('body').on('click', '.add_edit_review', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            var order_vendor_product_id = $(this).data('order_vendor_product_id');
            $.get('/rating/get-product-rating?id=' + id + '&order_vendor_product_id=' + order_vendor_product_id,
                function(markup) {
                    $('#product_rating').modal('show');
                    $('#review-rating-form-modal').html(markup);
                });
        });
        $('body').on('click', '.return-order-product', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            var vendor_id = $(this).data('vendor_id');
            $.get('/return-order/get-order-data-in-model?id=' + id + '&vendor_id=' + vendor_id, function(markup) {
                $('#return_order_model').modal('show');
                $('#return-order-form-modal').html(markup);
            });
        });

        $(document).delegate(".repeat-order-product", "click", function () {
            var order_vendor_id = $(this).data('order_vendor_id');
            $.ajax({
                type: "get",
                dataType: 'json',
                url: cart_details_url,
                success: function (response) {

                    if (response.data != "") {
                        let cartProducts = response.data.products;


                        if (cartProducts != "") {
                            $("#repeat_cart_modal").modal('show');
                            $("#repeat_cart_modal #repeat_cart_button").attr("data-cart_id", response.data.id);
                            $("#repeat_cart_modal #repeat_cart_button").attr("data-order_vendor_id", order_vendor_id);

                        }else{
                            $("#repeat_cart_modal1").modal('show');
                            $("#repeat_cart_modal1 #repeat_cart_button").attr("data-cart_id", response.data.id);
                            $("#repeat_cart_modal1 #repeat_cart_button").attr("data-order_vendor_id", order_vendor_id);
                        }


                    }
                }
            });
        });

        $(document).delegate("#repeat_cart_button", "click", function () {

            let cart_id = $(this).attr("data-cart_id");
            let order_vendor_id = $(this).attr("data-order_vendor_id");

            $.ajax({
                type: "post",
                dataType: 'json',
                url: "{{route('web.repeatOrder')}}",
                data: { 'cart_id': cart_id,'order_vendor_id': order_vendor_id },
                success: function (response) {
                    if (response.status == 'success') {
                        window.location.href = response.cart_url;
                    }
                }
            });

        });

        $(document).delegate("#orders_wrapper .nav-tabs .nav-link", "click", function() {
            let id = $(this).attr('id');
            const params = window.location.search;
            if (params != '') {
                if (id == 'active-orders-tab') {
                    window.location.href = window.location.pathname + '?pageType=activeOrders';
                } else if (id == 'past_order-tab') {
                    window.location.href = window.location.pathname + '?pageType=pastOrders';
                }
            }
        });

    ///// cancel order start
    $('body').on('click', '.cancel_order', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        var order_vendor_id = $(this).data('order_vendor_id');
        var pickup_cancelling_charges = $(this).data('pickup_cancelling_charges');
        if (typeof pickup_cancelling_charges !== 'undefined' && pickup_cancelling_charges !== false) {
            var order_id = $(this).data('order_id');
            var pickup_order_date = $(this).data('pickup_order');
            var order_number = $(this).data('order_number');
            var today = "{{date('Y-m-d')}}";
            if(Date.parse(pickup_order_date) == Date.parse(today)){
                if(localStorage.getItem('check_pickup_order_date') == null){
                    localStorage.setItem("check_pickup_order_date", true);
                    Swal.fire({
                        icon: 'info',
                        text: 'You are trying to cancel the order on the day of pickup, additional '+pickup_cancelling_charges+' will be debited from your wallet.',
                        confirmButtonText: 'Ok',
                    });

                    $.get('/return-order/get-vendor-order-for-cancel?id=' + id +'&order_vendor_id=' + order_vendor_id+'&pickup_cancelling_charges='+pickup_cancelling_charges+'&order_id='+order_id+'&pickup_order_date='+pickup_order_date+'&order_number='+order_number, function(markup)
                    {
                        $('#cancel_order').modal('show');
                        $('#cancel-order-form-modal').html(markup);
                    });
                }
            }else{
                $.get('/return-order/get-vendor-order-for-cancel?id=' + id +'&order_vendor_id=' + order_vendor_id, function(markup)
                {
                    $('#cancel_order').modal('show');
                    $('#cancel-order-form-modal').html(markup);
                });
            }
        }
        else{
            $.get('/return-order/get-vendor-order-for-cancel?id=' + id +'&order_vendor_id=' + order_vendor_id, function(markup)
            {
                $('#cancel_order').modal('show');
                $('#cancel-order-form-modal').html(markup);
            });
        }
    });
    ////////// cancel order end

    // Added by Ovi
    // Check Slot Availability
    $(document).on("change", ".schedule_pickup_slot_select", function()
    {
        var url = "{{route('checkSlotOrders')}}"
        var schedule_pickup_datetime = $('#pickup_schedule_datetime_re').val();
        var vendor_id = $('#vendor_id').val();
        // get schedule_pickup_slot_select class parent id
        var parentRowID = this.parentNode.id;
        // get parentRowID child first's value
        var schedule_pickup_slot = document.getElementById(parentRowID).childNodes[1].value;
        $.ajax({
            type: "GET",
            data: {
                "schedule_pickup_datetime": schedule_pickup_datetime,
                "schedule_pickup_slot":     schedule_pickup_slot,
                "vendor_id":                vendor_id,
            },
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(output) {
                // Check if orderCount is greaten equal to orders_per_slot
                if(output.orderCount >= output.orders_per_slot){
                    success_error_alert('error', 'All slots are full for the selected date & slot please choose another date or slot.', ".cart_response");
                    // Disable the place order button
                    $('.reschedule_now_btn').attr("disabled", true);
                }else{
                    // Enable the place order button
                    $('.reschedule_now_btn').attr("disabled", false);
                }
            },
            error: function(output) {
                // console.log(output);
            },
        });
    });

</script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
<script src="{{asset('front-assets/js/reschedule_order.js')}}"></script>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{asset('assets/js/chat/user_vendor_chat.js')}}"></script>
@endsection
