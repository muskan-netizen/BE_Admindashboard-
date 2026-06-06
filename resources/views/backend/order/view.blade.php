@extends('layouts.vertical', ['title' => 'Order Detail'])
@section('css')
<link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css">
<style>
    /* td { white-space:pre-line; word-break:break-all} */
    /* table css add here */
    .product_tab_inner tr,
    td {
        border: 1px solid#eee;
        padding: 10px 10px;
    }

    .product_tab_inner tr th {
        padding: 10px 10px;
        border: 1px solid#eee;
        font-weight: 600;
    }

    .outer_div {
        border-radius: 10px;
        border: 1px solid#bab8b8;
        background: #f4efefc2;
    }

    .outer_div h6 {
        font-size: 14px;
        font-weight: 600 !important;
    }

    .product_appointment_spa h4.header-title {
        display: inline-block;
    }

    .product_appointment_spa p {
        display: inline-block;
        float: right;
    }

    #cancel-request-card {
        background: #ddd;
    }

    .royo-thumnail_img {
        width: 100px;
        height: auto;
    }

    .royo-ques h3 {
        font-size: 15px;
        font-weight: 600 !important;
    }

    .royo-ques h6 {
        font-size: 14px;
        padding: 5px 0px;
    }

    .custom-accordin1 .card-header {
        padding: 0px 0px !important;
        background-color: rgba(0, 0, 0, .03);
        border: 1px solid#d5cece;
        border-radius: 10px;
    }

    .custom-accordin1 .card-body {
        padding: 10px 10px;
        border-bottom: 1px solid#eee;
        border-radius: 10px;
    }

    .custom-accordin1 .card:nth-child(1) {
        margin: 29px 0px;
    }

    .custom-accordin1 .card {
        padding-bottom: 0px !important;
        border-radius: 0px !important;
        box-shadow: none !important;
        border: 1px solid#eee;
        border-radius: 10px !important;
    }



</style>
@endsection
@section('content')
@php
$timezone = Auth::user()->timezone;
@endphp
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between ">
                    <h4 class="page-title">{{ __('Order Detail') }}</h4>
                    <div class="al_back_btn">
                        <a class="al_print_btn_back mr-2" href="{{ url()->previous() }}">{{ __('Back') }}</a>
                        <button class="al_print_btn badge badge-info" onclick='printDiv();'>{{ __('Print') }} <img src=""> </button>
                    </div>
                </div>

            </div>
        </div>

        <div class="text-sm-left">
            @if (\Session::has('success'))
            <div class="alert alert-success">
                <span>{!! \Session::get('success') !!}</span>
            </div>
            @endif
        </div>
        <div class="text-sm-left">
            @if (\Session::has('error'))
            <div class="alert alert-danger">
                <span>{!! \Session::get('error') !!}</span>
            </div>
            @endif
        </div>
        @if(getAdditionalPreference(['document_report'])['document_report'] == 1)
            <div class="uploadDocument">
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModal">
                    Upload Report
                </button>
            </div>
        @endif
        @if ($order->vendors->first())
        @if ($order->vendors->first()->cancel_request && $order->vendors->first()->cancel_request->status == 'Pending')
        <div class="row">

            <div class="col-lg-12 mb-3">
                <div class="card mb-0 h-100" id="cancel-request-card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ __('Cancel Order Request') }}</h4>
                        <button type="button" class="complete_request_btn btn btn-sm btn-info" title='Approve' data-status="1" data-id="{{ $order->vendors->first()->cancel_request->id }}">
                            <i class='fa fa-check mr-1'></i> {{ __('Approve') }}
                        </button>
                        <button type="button" class="complete_request_btn btn btn-sm btn-danger" title='Reject' data-status="2" data-id="{{ $order->vendors->first()->cancel_request->id }}">
                            <i class='fa fa-times mr-1'></i> {{ __('Reject') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
        <div class="row">
            @if($order->luxury_option_id != 4)
                <div class="col-lg-4 mb-3">
                    <div class="card mb-0 h-100">
                        <div class="card-body">
                            <h4 class="header-title mb-3">{{__('Track Order')}}</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <h5 class="mt-0">{{__('Order ID')}}:</h5>
                                        <p>#{{$order->order_number}}</p>
                                        @if(@$order->vendors[0]->exchanged_to_order)
                                        <h4>{{ __('Exchanged To') }}</h4>
                                        <a href="{{$order->vendors[0]->exchanged_to_order->vendor_detail_url }}"><span>#{{ $order->vendors[0]->exchanged_to_order->orderDetail->order_number }}</span></a>
                                        @endIf
                                        @if(@$order->vendors[0]->exchanged_of_order)
                                        <h4>{{ __('Exchange Of') }}</h4>
                                        <a href="{{$order->vendors[0]->exchanged_of_order->vendor_detail_url }}"><span># {{$order->vendors[0]->exchanged_of_order->orderDetail->order_number}}</span></a>

                                        @endIf
                                    </div>
                                </div>

                                @if (!in_array($order->luxury_option_id, [6, 8]) && $order->is_long_term !=1 )
                                @if (isset($order->vendors) &&
                                empty($order->vendors->first()->dispatch_traking_url) &&
                                $order->vendors->first()->delivery_fee > 0 &&
                                $order->vendors->first()->order_status_option_id >= 2 &&
                                $order->vendors->first()->shipping_delivery_type == 'D')
                                <div class='inner-div d-inline-block' style="float: right;">
                                    <form method='POST' action='"+full.destroy_url+"'>

                                        <button type='button' class='btn btn-danger' id="create_dispatch_request" data-order_vendor_id="{{ $order->vendors->first()->id }}">{{ __('Create Dispatch Request') }}</i>
                                        </button>

                                    </form>
                                </div>
                                @endif
                                @endif
                              @foreach ($order->vendors as $vendor)

                                @if($vendor->vendor_id == $vendor_id)
                                @if(isset($order->vendors) && isset($vendor->dispatch_traking_url) && $vendor->dispatch_traking_url!=null)
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <h5 class="mt-0">{{ __('Tracking ID') }}: </h5>
                                        <p>
                                            @php
                                            $track = explode('/', $vendor->dispatch_traking_url);
                                            $track_code = end($track);
                                            @endphp
                                            <a href="{{ $vendor->dispatch_traking_url }}" target="_blank">#{{ $track_code }}</a>
                                        </p>
                                        @if (isset($vendor->label_pdf))
                                            <a href="{{ $vendor->label_pdf }}" target="_blank">{{ __("Label PDF")}}</a>
                                        @endif
                                    </div>
                                </div>
                                @elseif(isset($order->vendors) &&
                                isset($vendor->lalamove_tracking_url) &&
                                $vendor->lalamove_tracking_url != null)
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <h5 class="mt-0">{{ __('Tracking ID') }}:</h5>
                                        <p>
                                            <a href="{{ $vendor->lalamove_tracking_url }}" target="_blank">#{{ $vendor->web_hook_code }}</a>
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                             @endif
                            @endforeach
                            {{-- @endif
                            @endif --}}
                            <div class="row track-order-list">
                                <div class="col-lg-6">
                                    <!-- <button type="button" class="btn btn-danger waves-effect waves-light">
                                            <i class="mdi mdi-close"></i>
                                        </button> -->
                                    <ul class="list-unstyled" id="order_statuses">
                                        @php
                                        if ($order->vendors->first()->order_status_option_id == 2) {
                                        $open_option = ['4'];
                                        } elseif ($order->vendors->first()->order_status_option_id == 3) {
                                        $open_option = ['0'];
                                        } elseif ($order->vendors->first()->order_status_option_id == 1) {
                                        $open_option = ['2', '3'];
                                        } else {
                                        $open_option = [$order->vendors->first()->order_status_option_id + 1];
                                        }
                                        @endphp

                                        <!-- List of completed order status -->
                                        @if (count($vendor_order_statuses))
                                        @foreach ($vendor_order_statuses as $key => $vendor_order_status)
                                        @php
                                        $order_status = $order_status_options
                                        ->where('id', $vendor_order_status->order_status_option_id)
                                        ->pluck('title')
                                        ->first();
                                        $glow = '';
                                        if ($key < count($vendor_order_statuses) - 1) { $glow='completed' ; } $date=isset($vendor_order_status_created_dates[$vendor_order_status->order_status_option_id]) ? $vendor_order_status_created_dates[$vendor_order_status->order_status_option_id] : '';
                                            @endphp

                                            <li class="{{ $glow }} disabled" data-status_option_id="{{ $vendor_order_status->order_status_option_id }}" data-order_vendor_id="{{ $vendor_order_status->vendor_id }}">
                                                @if ($vendor_order_status->order_status_option_id == 5 &&
                                                ($order->luxury_option_id == 2 || $order->luxury_option_id == 3))
                                                <h5 class="mt-0 mb-1">{{ __('Order Prepared') }}</h5>
                                                @else
                                                    <h5 class="mt-0 mb-1">{{__($order_status)}}</h5>
                                                @endif
                                                <p class="text-muted" id="text_muted_{{ $vendor_order_status->order_status_option_id }}">
                                                    @if ($date)
                                                    <small class="text-muted">{{ dateTimeInUserTimeZone($date, $timezone) }}</small>
                                                    @endif
                                                </p>
                                            </li>
                                            @endforeach
                                            @endif

                                            <!-- List of incomplete order status if order is not rejected -->

                                            @if (!in_array(3, $vendor_order_status_option_ids))
                                            @foreach ($order_status_options as $order_status_option)
                                            @if (!in_array($order_status_option->id, $vendor_order_status_option_ids))
                                            @php
                                            $class = in_array($order_status_option->id, $vendor_order_status_option_ids) ? 'disabled' : '';
                                            if ($order_status_option->id == $order->vendors->first()->order_status_option_id) {
                                            $glow = '';
                                            } else {
                                            $glow = 'completed';
                                            }
                                            $date = isset($vendor_order_status_created_dates[$order_status_option->id]) ? $vendor_order_status_created_dates[$order_status_option->id] : '';
                                            @endphp
                                            @if (in_array(3, $vendor_order_status_option_ids) && $order_status_option->id == 2)
                                            @continue
                                            @endif
                                            @if (in_array(2, $vendor_order_status_option_ids) && $order_status_option->id == 3)
                                            @continue
                                            @endif

                                            <li class="{{ $class }} {{ $glow }}  @if (in_array($order_status_option->id, $open_option)) open-for-update-status @else disabled @endif" data-status_option_id="{{ $order_status_option->id }}" data-order_vendor_id="{{ $order_status_option->order_vendor_id }}">
                                                @if ($order_status_option->id == 5 && ($order->luxury_option_id == 2 || $order->luxury_option_id == 3))
                                                <h5 class="mt-0 mb-1">{{ __('Order Prepared') }}</h5>
                                                @elseif($order_status_option->id == 2)
                                                <h5 style="padding: 2px 10px;" class="mt-0 mb-1 text-info">
                                                    {{ $order_status_option->title }}
                                                </h5>
                                                @elseif($order_status_option->id == 3)
                                                <h5 style="padding: 2px 10px;" class="mt-0 mb-1 text-danger">
                                                    {{ $order_status_option->title }}
                                                </h5>
                                                @else
                                                <h5 class="mt-0 mb-1">{{ $order_status_option->title }}</h5>
                                                @endif
                                                <p class="text-muted" id="text_muted_{{ $order_status_option->id }}">
                                                    @if ($date)
                                                    <small class="text-muted">{{ dateTimeInUserTimeZone($date, $timezone) }}</small>
                                                    @endif
                                                </p>
                                            </li>
                                            @if (in_array(3, $vendor_order_status_option_ids) && $order_status_option->id == 3)
                                            @break
                                            @endif
                                            @endif
                                            @endforeach
                                            @endif
                                    </ul>
                                </div>

                                @if (isset($order->vendors) &&
                                ($order->vendors->first()->dispatch_traking_url != null ||
                                $order->vendors->first()->lalamove_tracking_url != null ||
                                $order->vendors->first()->web_hook_code != null))
                                <div class="col-lg-6">
                                    <ul class="list-unstyled remove-curser">
                                        @foreach ($dispatcher_status_options as $dispatcher_status_option)
                                        @php
                                        if ($dispatcher_status_option->vendorOrderDispatcherStatus && $dispatcher_status_option->id == $dispatcher_status_option->vendorOrderDispatcherStatus->dispatcher_status_option_id ?? '') {
                                        $class = 'disabled';
                                        }

                                        if ($dispatcher_status_option->id == $order->vendors->first()->dispatcher_status_option_id) {
                                        $glow = '';
                                        } else {
                                        $glow = 'completed';
                                        }

                                        $date = isset($dispatcher_status_option->vendorOrderDispatcherStatus) ? $dispatcher_status_option->vendorOrderDispatcherStatus->created_at : '';
                                        @endphp
                                        <li class="{{ @$class }} {{ @$glow }}" data-status_option_id="{{ $dispatcher_status_option->id }}">
                                            <h5 class="mt-0 mb-1">{{ $dispatcher_status_option->title }}</h5>
                                            <p class="text-muted" id="dispatch_text_muted_{{ $dispatcher_status_option->id }}">
                                                @if ($date)
                                                <small class="text-muted">{{ dateTimeInUserTimeZone($date, $timezone) }}</small>
                                                @endif
                                            </p>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @php
                $col = "col-lg-8";
            @endphp
            @if($order->luxury_option_id == 4)
                @php
                    $col = "col-lg-12";
                @endphp
            @endif
            <div class="{{$col}} mb-3">
                <div class="card mb-0 h-100">
                    <div class="card-body product_appointment_spa">
                        <h4 class="header-title mb-3">
                            <div class="form-ul mb-1">

                                <span><img src="{{ @$vendor_data->logo['image_fit'] . '32/32' . @$vendor_data->logo['image_path'] }}" alt="product-img" height="20"></span>
                                {{ $vendor_data->name }}
                            </div>

                            @if ($order->luxury_option_name != '')
                            <span class="badge badge-info mr-2">{{ $order->luxury_option_name }}</span>
                            @endif
                            {{ __("Items from Order") }} #{{$order->order_number}}
                            @if(@$order->vendors[0]->exchanged_to_order)

                            {{ __("Exchange To") }}<a href="{{$order->vendors[0]->exchanged_to_order->vendor_detail_url }}"><span>#{{ $order->vendors[0]->exchanged_to_order->orderDetail->order_number }}</span></a>
                            @endIf


                            <button class=" badge badge-info border-0"  data-toggle="modal" data-target="#showDelayTimeModal">{{ __('Add Delay Time') }} <img src=""> </button>


                            @if(@$order->vendors[0]->exchanged_of_order)
                            {{ __("Exchange Of") }}
                            <a href="{{$order->vendors[0]->exchanged_of_order->vendor_detail_url }}"><span># {{$order->vendors[0]->exchanged_of_order->orderDetail->order_number}}</span></a>

                            @endIf
                            {{-- <a href="{{ route('order.edit.detail',[$order->id,$order->vendors->first()->vendor_id])}}">{{__('Edit Order')}}</a> --}}
                            @if(@$order->order_exchange_request && $order->order_exchange_request->type == 2)
                            <a href="javascript:;" data-id="{{ $order->order_exchange_request->id }}" class="show-return-product-modal" data-status="Pending">Reason to Exchange </a>
                            @endif
                        </h4>
                        @if ($order->luxury_option_id == 2)
                        @foreach ($order->vendors as $vendor)
                        <p>{{ $vendor->dineInTableName }} | {{ __('Category') }} :
                            {{ $vendor->dineInTableCategory }} | {{ __('Capacity') }} :
                            {{ $vendor->dineInTableCapacity }}
                        </p>
                        @endforeach
                        @endif
                        @if ($order->product_schedule_type == 'schedule')
                        <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('*Instant/Scheduled Product Wise') }}</span> </p>
                        @endif


                        <div class="table-responsive">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __(getNomenclatureName("Product Name",true)) }}</th>
                                        <th>{{ __("Product") }}</th>
                                        <th>{{ __("Quantity") }}</th>
                                        <th>{{ __("Price") }}</th>
                                        <th>{{ __("Total") }}</th>
                                    </tr>
                                </thead>

                                @foreach ($order->vendors as $vendor)

                                @if($vendor->vendor_id == $vendor_id)

                                <tbody>
                                    @php
                                    $sub_total = 0;
                                    $taxable_amount = 0;
                                    $adminRevenue = 0;
                                    $storeRevenue = 0;
                                    $revenue = $vendor->admin_commission_percentage_amount + $vendor->admin_commission_fixed_amount + $vendor->total_markup_price;
                                    @endphp

                                    @foreach ($vendor->products as $product)
                                    @if ($product->order_id == $order->id)
                                    @php
                                    $taxable_amount = $vendor->taxable_amount;
                                    $vendor_service_fee = $vendor->service_fee_percentage_amount;
                                    $container_charges = $vendor->total_container_charges;
                                    $sub_total += $product->total_amount;
                                    // dd($order->luxury_option_id);
                                    //pr($product->toArray());
                                    $product_url = isset($product->product) ? ($product->product->is_long_term_service != 1 ? route('product.edit', @$product->product->id) : 'javascript:void(0)') : 'javascript:void(0)';
                                    @endphp

                                    <tr>
                                        <th scope="row" class="product-modal2">


                                            <a href="{{ $product_url }}" @if ($product_url !='javascript:void(0)' ) target="_blank" @endif>
                                                {{ $product->product_title }} @if(@$product->product->is_long_term_service && $product->product->is_long_term_service ==1) <span class="badge badge-info"> {{ __('Long Term Service') }}</span> @endif
                                            </a>

                                            @if (isset($product->product) &&
                                            isset($product->product->category) &&
                                            isset($product->product->category->categoryDetail) &&
                                            $product->product->category->categoryDetail->translation_one)
                                            (in
                                            {{ $product->product->category->categoryDetail->translation_one->name }})
                                            @endif
                                            @if(!empty($order->luxury_option_id) && $order->luxury_option_id == 4)
                                                @if(empty($product->order_product_status))
                                                    <a href="javascript:void(0)" class="float-right">
                                                        <span class="badge badge-info mr-2">
                                                            {{ __('Pending') }}
                                                        </span>
                                                    </a>
                                                @elseif($product->order_product_status->order_status_option_id == 2)
                                                    <a href="javascript:void(0)" class="float-right">
                                                        <span class="badge badge-success mr-2">
                                                            {{ __('Accepted') }}
                                                        </span>
                                                    </a>
                                                @elseif($product->order_product_status->order_status_option_id == 3)
                                                    <a href="javascript:void(0)" class="float-right">
                                                        <span class="badge badge-danger mr-2">
                                                            {{ __('Rejected') }}
                                                        </span>
                                                    </a>
                                                @endif
                                            @endif

                                            @if (isset($product->user_product_order_form))
                                            <a href="javascript:void(0)" class="Order_product_form float-right " data-product_form_id="{{ $product->id }}">
                                                <span class="badge badge-info mr-2">
                                                    {{ $nomenclatureProductOrderForm }}
                                                </span>
                                            </a>
                                            @endif

                                            <p class="p-0 m-0">
                                                @if (isset($product->scheduled_date_time))
                                                {{ date('Y-m-d', strtotime(dateTimeInUserTimeZone($product->scheduled_date_time, $timezone))) }}
                                                @endif
                                                @if ($product->schedule_slot != '')
                                                {{ __('slot') }} {{ $product->schedule_slot }}
                                                @endif
                                            </p>

                                            @foreach ($product->prescription as $pres)
                                            <br><a target="_blank" href="{{ $pres ? @$pres->prescription['proxy_url'] . '500/500' . @$pres->prescription['image_path'] : '' }}">{{ $product->prescription ? 'Prescription' : '' }}</a>
                                            @endforeach

                                            <p class="p-0 m-0">
                                                {{ substr($product->product_variant_sets, 0, -2) }}
                                            </p>
                                            @if ($product->addon && count($product->addon))
                                            <hr class="my-2">
                                            <h6 class="m-0 pl-0"><b>{{ __('Add Ons') }}</b></h6>
                                            @foreach ($product->addon as $addon)
                                            <p class="p-0 m-0">
                                                {{ $addon->option->translation_title }}
                                            </p>
                                            @endforeach
                                            @endif
                                        </th>
                                        <td>
                                            @if ($product->image_path)
                                            <img src="{{ @$product->image_path['proxy_url'] . '32/32' . @$product->image_path['image_path'] }}" alt="product-img" height="32">
                                            @else
                                            @php $image_path = getDefaultImagePath(); @endphp
                                            <img src="{{ $image_path['proxy_url'] . '32/32' . $image_path['image_path'] }}" alt="product-img" height="32">
                                            @endif
                                        </td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>
                                            {{ $clientCurrency->currency->symbol }}{{ decimal_format($product->actual_price) }}
                                            @if ($product->addon->isNotEmpty())
                                            <hr class="my-2">
                                            @foreach ($product->addon as $addon)
                                            <p class="p-0 m-0">
                                                {{ $clientCurrency->currency->symbol }}{{ decimal_format($addon->option->price_in_cart) }}
                                            </p>
                                            @endforeach
                                            @endif
                                        </td>

                                        <td>
                                            {{ $clientCurrency->currency->symbol }}{{ decimal_format($product->total_amount) }}

                                            {{-- mohit sir branch code added by sohail --}}
                                            @php
                                                $getAdditionalPreference = getAdditionalPreference(['update_order_product_price','is_enable_allergic_items','blockchain_route_formation']);
                                            @endphp
                                            @if( @getAdditionalPreference(['update_order_product_price'])['update_order_product_price'] == '1')
                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#addModal" class="badge badge-info ml-3 update_product_price" data-or_prod_old_price="{{decimal_format($product->total_amount)}}" data-or_vend_prod_id="{{$product->id}}">Update Price <img src=""> </a>
                                            @endif
                                            {{-- till here --}}
                                        </td>
                                    </tr>

                                    @if (count($product->routes) > 0)
                                    <tr class="route">
                                        <th scope="row" colspan="4" class="text-end">
                                            <div class="outer_div p-2">
                                                <h6>{{ __('Dispatcher Routes') }}</h6>
                                                <table class="wp-table w-100">
                                                    <tr>
                                                        <th width="20%">#</th>
                                                        <th width="40%">{{ __('Tracking URL') }}
                                                        </th>
                                                        <th width="40%">{{ __('Status') }}</th>
                                                    </tr>
                                                    @foreach ($product->routes as $key => $route)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td><a href="{{ $route->dispatch_traking_url }}" target="_blank">{{ __('Track') }}</a>
                                                        </td>
                                                        <td>{{ $route->DispatchStatus->first() ? $route->DispatchStatus[0]->status_data['driver_status'] ?? '' : 'na' }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </th>
                                        <td></td>
                                    </tr>
                                    @endif

                                    @if(isset($recurring_booking) && !empty($recurring_booking))
                                        <tr class="route">
                                            <th scope="row" colspan="4" class="text-end">
                                                <div class="outer_div p-2 mb-2">
                                                    <h6>{{ __('Recurring Booking') }}</h6>
                                                    <hr class="my-2">
                                                    <div class="service_product">
                                                        <table class="wp-table w-100">
                                                            <th width="20%">#</th>
                                                            <th width="40%">{{ __('Scheduled date time') }}</th>
                                                            <th width="20%">{{ __('Dispatch Traking Url') }}</th>

                                                            @foreach ($recurring_booking as $key=>$booking)
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td>{{ $booking->schedule_date }} </td>
                                                                    @if(!empty($booking->dispatch_traking_url))
                                                                        <td><a href="{{ $booking->dispatch_traking_url }}" target="_blank">View</a></td>
                                                                    @else
                                                                        <td>Pending</td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    @endif

                                    @if ( isset($product->longTermSchedule) && isset($product->longTermSchedule->schedule) && count($product->longTermSchedule->schedule) > 0)

                                    <tr class="route">
                                        <th scope="row" colspan="4" class="text-end">
                                            <div class="outer_div p-2 mb-2">
                                                <h6>{{ __('Product Detail') }}</h6>
                                                <hr class="my-2">
                                                <div class="service_product">
                                                    @php
                                                    $Service_product_url = isset($product->longTermSchedule->product) ? route('product.edit', @$product->longTermSchedule->product->id) : '#';
                                                    @endphp
                                                    <h6>{{ __('Product Name') }}: <a href="{{ $Service_product_url }}" target="_blank"> {{ $product->longTermSchedule->product->primary->title }} </a></h6>

                                                    <h6>{{ __('No. of Bookings') }}: {{ $product->longTermSchedule->service_quentity }} </h6>

                                                    <h6>{{ __('Service Time:') }}: {{ __(config('constants.Period.'.$product->longTermSchedule->service_period))}} </h6>

                                                    @if ($product->longTermSchedule->addon && count($product->longTermSchedule->addon))
                                                    <hr class="my-2">
                                                    <h6 class="m-0 pl-0"><b>{{ __('Add Ons') }}</b></h6>
                                                    @foreach ($product->longTermSchedule->addon as $addon)
                                                    <div class="longTermAddon d-flex">
                                                        <p class="p-0 mr-2 mb-0">{{ $addon->set->title }} :</p>
                                                        <b class="p-0 m-0">{{ $addon->option->translation_title }}</b>
                                                    </div>

                                                    @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="outer_div p-2">
                                                <h6>{{ __('Long Term Service Schedule') }}</h6>
                                                <table class="wp-table w-100">
                                                    @php
                                                    $showRoute = !empty($product->longTermSchedule->product) ??($product->longTermSchedule->product->Requires_last_mile ==1 ? 1 : 0);
                                                    if($vendor->delivery_fee <=0){ $showRoute=0; } @endphp <tr>
                                                        <th width="20%">#</th>
                                                        <th width="40%">{{ __('Scheduled date time') }}</th>
                                                        <th width="20%">{{ __('Service Status') }}</th>
                                                        @if($showRoute==1 )
                                                        <th width="20%">{{ __('Service Route') }}</th>
                                                        @endif
                                    </tr>
                                    @foreach ($product->longTermSchedule->schedule as $key => $schedule)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><a href="javascript:void(0)">{{ date('d M Y h:i A', strtotime(dateTimeInUserTimeZone($schedule->schedule_date, $timezone)))   }}</a>
                                        </td>
                                        <td> <span class="badge {{  $schedule->status ==0 ? 'badge-info' : 'badge-success'}}  mr-2">{{ $schedule->status ==0 ? __('Pending') : __('Completed')}}</span>
                                            @if($schedule->status ==0)
                                            <a class="Complete_longTermBoking badge badge-success" href="JavaScript:void(0)" data-service_id="{{ $schedule->id }}">{{ __('Complete Booking')}}</a>
                                            @endif
                                        </td>
                                        @if($showRoute ==1 )
                                        <td>
                                            @if( $schedule->dispatch_traking_url !='' && $schedule->status ==0 )
                                            <a href="{{ $schedule->dispatch_traking_url }}" target="_blank">{{ __('Track') }}</a>
                                            @endif

                                            <span class="badge badge-info mr-2"> {{ $schedule->status ==0 ? ( $schedule->DispatchStatus->first() ? $schedule->DispatchStatus[0]->status_data['driver_status'] ?? '' : 'na') : __('Completed') }} </span>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                            </table>
                        </div>
                        </th>
                        <td></td>
                        </tr>
                        @endif
                        @endif
                        @endforeach

                        @php
                                    $sub_total = $sub_total - $vendor->orderDetail->bid_discount;
                        @endphp

                        @if($container_charges > 0)
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Container Charges") }} :</th>
                            <td>{{$clientCurrency->currency->symbol}}@money($container_charges)</td>
                        </tr>
                        @endif
                        @if($vendor->orderDetail->bid_discount > 0)
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Bid Discount") }} :</th>
                            <td>{{$clientCurrency->currency->symbol}}@money($vendor->orderDetail->bid_discount)</td>
                        </tr>
                        @endif
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Sub Total") }} :</th>
                            <td>
                                <div class="fw-bold">{{$clientCurrency->currency->symbol}}{{decimal_format($sub_total)+decimal_format($container_charges)}}</div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{__('Delivery Fee')}} :</th>
                            <td>{{$clientCurrency->currency->symbol}}{{decimal_format($vendor->delivery_fee)}}</td>
                        </tr>
                        @if($vendor_service_fee > 0)
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Service Fee") }} :</th>
                            <td>{{$clientCurrency->currency->symbol}}{{decimal_format($vendor_service_fee)}}</td>
                        </tr>
                        @endif

                        @if($order->fixed_fee_amount > 0)
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __($fixedFee) }} :</th>
                            <td>{{$clientCurrency->currency->symbol}}{{decimal_format($order->fixed_fee_amount)}}</td>
                        </tr>
                        @endif
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Estimated Tax") }} :</th>
                            <td>{{$clientCurrency->currency->symbol}}{{decimal_format($order->total_other_taxes_amount)}}</td>
                        </tr>
                        @if($vendor->additional_price > 0)
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Additional Price") }} :</th>
                            <td style="width:200px;">{{$clientCurrency->currency->symbol}}{{decimal_format($vendor->additional_price)}}</td>
                        </tr>
                        @endif

                        @if(number_format($vendor->orderDetail->loyalty_points_used) > 0)
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Redeemed Loyality Points") }} :</th>
                            <td style="width:200px;">{{$vendor->orderDetail->loyalty_points_used??0.00}} ({{$clientCurrency->currency->symbol}}{{decimal_format($vendor->orderDetail->loyalty_amount_saved??0.00)}})</td>
                        </tr>
                        @endif

                        <tr>
                            <?php
                            $vendorDiscount = 0;
                            $adminDiscount = 0;
                            // dd($vendor);
                            if ($vendor->coupon_code) {
                                if ($vendor->coupon_paid_by == 1) {
                                    $couponFrom = 'From Admin';
                                    $adminDiscount = $vendor->discount_amount;
                                } else {
                                    $couponFrom = 'From Vendor';
                                    $vendorDiscount = $vendor->discount_amount ?? 0;
                                }
                            } else {
                                $couponFrom = '';
                                $adminDiscount = 0;
                                $vendorDiscount = 0;
                            }
                            ?>
                            <th scope="row" colspan="4" class="text-end">{{__('Total Discount')}} {{$couponFrom}}:</th>
                            <td>-{{$clientCurrency->currency->symbol}}{{decimal_format($vendor->discount_amount)}}</td>
                        </tr>


                        @if($client_preference_detail->is_tax_price_inclusive)

                        @php //taxable_amount
                        $adminRevenue = ($revenue + $container_charges + $vendor_service_fee + $vendor->delivery_fee) - $adminDiscount - number_format($vendor->orderDetail->loyalty_amount_saved);

                        //taxable_amount
                        $storeRevenue = ($sub_total + $order->fixed_fee_amount + $container_charges + $vendor_service_fee + $vendor->delivery_fee) - $adminRevenue - $vendorDiscount;

                        @endphp

                        @else

                        @php

                        $adminRevenue = ($revenue + $order->total_other_taxes_amount + $container_charges + $vendor_service_fee + $vendor->delivery_fee) - $adminDiscount - number_format($vendor->orderDetail->loyalty_amount_saved);

                        $storeRevenue = ($sub_total + $order->fixed_fee_amount +  $order->total_other_taxes_amount + $container_charges + $vendor_service_fee + $vendor->delivery_fee) - $adminRevenue - $vendorDiscount;

                        @endphp
                        @endif


                        {{-- @if (Auth::user()->is_superadmin) --}}
                        <tr>
                            <th scope="row" colspan="4" class="text-end">
                                {{ $client_head->name }} {{ __('Revenue') }} :
                            </th>

                            <td>{{ $clientCurrency->currency->symbol }}{{ decimal_format($adminRevenue) }}
                            </td>
                        </tr>
<tr>
                            <th scope="row" colspan="4" class="text-end">
                                {{ __('Admin Commission') }} :
                            </th>

                            <td>{{ $clientCurrency->currency->symbol }}{{ decimal_format($vendor->admin_commission_percentage_amount) }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Store Earning") }} :</th>
                            {{-- <td>{{$clientCurrency->currency->symbol}}{{decimal_format($vendor->sub_total * $clientCurrency->doller_compare - $revenue - $vendorDiscount)}}</td> --}}
                            <td>{{$clientCurrency->currency->symbol}}{{decimal_format($vendor->vendor_amount)}}</td>
                        </tr>
                        {{-- @endif --}}
                        @if($order->tip_amount > 0)
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Tip Amount") }} :</th>
                            <td style="width:200px;"> {{$clientCurrency->currency->symbol}}{{decimal_format($order->tip_amount??0.00)}}</td>
                        </tr>
                        @endif
                        @if($vendor->reject_reason)
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Reject Reason") }} :</th>
                            <td style="width:200px;">{{$vendor->reject_reason}}</td>
                        </tr>
                        @endif
                        @if($vendor->additional_price>0)
                        @endif

                    @if($order->wallet_amount_used>0)
                    <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Wallet Amount Used") }} :</th>
                            <td>
                                <div class="fw-bold">{{$clientCurrency->currency->symbol}}{{decimal_format($order->wallet_amount_used * $clientCurrency->doller_compare)}}</div>
                            </td>
                    </tr>
                    @endif
                    @if($order->subscription_discount>0)
                    <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Subscription Discount") }} :</th>
                            <td>
                                <div class="fw-bold">-{{$clientCurrency->currency->symbol}}{{decimal_format($order->subscription_discount)}}</div>
                            </td>
                    </tr>
                    @endif
                        <tr>
                            <th scope="row" colspan="4" class="text-end">{{ __("Total") }} :</th>
                            <td>
                                @php
                            if($order->luxury_option_id == 4) // 'rental'
                                {

                                    $total=$order->total_amount+$order->fixed_fee_amount+$order->total_delivery_fee+$order->total_service_fee+$order->total_container_charges+$order->rental_protection_amount+$order->booking_option_price - $order->wallet_amount_used +$order->total_other_taxes_amount;
                                }else{

                                    $total = $order->total_service_fee+$order->fixed_fee_amount+$order->total_delivery_fee+$order->tip_amount-$order->subscription_discount+$order->total_amount - $order->wallet_amount_used + $order->total_other_taxes_amount;
                                }
                                @endphp

                              <div class="fw-bold">{{$clientCurrency->currency->symbol}}{{decimal_format($total)}}</div>
                            </td>
                    </tr>

                    <tr>
                        <th scope="row" colspan="4" class="text-end">{{ __("Payable Amount") }} :</th>
                        <td>
                            <div class="fw-bold">{{$clientCurrency->currency->symbol}}{{decimal_format($order->payable_amount)}}</div>
                        </td>
                    </tr>
                    @if(@$order->advance_amount > 0)
                    <tr>
                        <th scope="row" colspan="4" class="text-end">{{ __("Advance Paid") }} :</th>
                        <td>
                            <div class="fw-bold">{{$clientCurrency->currency->symbol}}{{decimal_format(@$order->advance_amount)}}</div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="4" class="text-end">{{ __("Pending Amount") }} :</th>
                        <td>
                            <div class="fw-bold">{{$clientCurrency->currency->symbol}}{{decimal_format($order->payable_amount) - decimal_format(@$order->advance_amount)}}</div>
                        </td>
                    </tr>
                    @endif
                    </tbody>
                    @endif

                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @if (Auth::user()->is_superadmin ||
    ($order->address && $order->luxury_option_id == 1 && $client_preference_detail->hide_order_address == 0))

    <div class="col-lg-6 mb-3">
        <div class="card mb-0 h-100">
            <div class="col-lg-6 card-body">
                <h4 class="header-title mb-3">{{ __('Delivery Information') }}</h4>
                <h5 class="font-family-primary fw-semibold">{{ $order->user->name }}</h5>
                @if ($order->type == 1 && isset($order->friend_name))
                <p class="mb-2"><span class="fw-semibold me-2"><b>{{ __('Friend Name') }}:</b></span>
                    {{ $order->friend_name }}
                </p>
                @endif
                @if ($order->type == 1 && isset($order->friend_phone_number))
                <p class="mb-2"><span class="fw-semibold me-2"><b>{{ __('Friend Phone Number') }}:</b></span>
                    {{ $order->friend_phone_number }}
                </p>
                @endif
                <p class="mb-2"><span class="fw-semibold me-2">{{ __('Email') }}:</span>
                    {{ $order->user->email ? $order->user->email : '' }}
                </p>
                @if (!is_null($order->user) && isset($order->user->phone_number))
                <p class="mb-2"><span class="fw-semibold me-2">{{ __('Phone') }}:</span>
                    {{ '+' . $order->user->dial_code . $order->user->phone_number }}
                </p>
                @endif

                <p class="mb-2"><span class="fw-semibold me-2">{{ __('Address') }}:</span>
                    @if (!is_null($order->address) && !empty($order->address->address))
                    {{ $order->address ? $order->address->house_number."," : ''}} {{ $order->address ? $order->address->address : ''}}
                    @elseif ($order->vendors->first()->vendor->laundry && (!$order->vendors->first()->vendor->pick_drop))
                   {{ $order->vendors->first()->vendor->address ?? ''}}
                    @endif
                </p>
                @if (isset($order->address) && !empty($order->address->street))
                    <p class="mb-2"><span class="fw-semibold me-2">{{ __('Street') }}:</span>
                        {{ $order->address ? $order->address->street : '' }}
                    </p>
                @endif
                @if(!is_null($order->address) && !empty($order->address->city))
                <p class="mb-2"><span class="fw-semibold me-2">{{__('City')}}:</span> {{ $order->address ? $order->address->city : ''}}</p>
                @endif
                @if (isset($order->address) && !empty($order->address->state))
                    <p class="mb-2"><span class="fw-semibold me-2">{{ __('State') }}:</span>
                        {{ $order->address ? $order->address->state : '' }}
                    </p>
                @endif
                @if(!is_null($order->address) && !empty($order->address->pincode))
                <p class="mb-0"><span class="fw-semibold me-2">{{ getNomenclatureName('Zip Code', true) }}:</span>  {{ $order->address ? $order->address->pincode : ''}}</p>
                @endif
            </div>
            @if (isset($driver_data->name))
                <div class="col-lg-6 card-body">
                    <h4 class="header-title mb-3">{{ __('Driver Information') }}</h4>
                    <p class="mb-2"><span class="fw-semibold me-2">{{ __('Name') }}:</span>
                        {{ $driver_data->name ? $driver_data->name : '' }}
                    </p>
                    <p class="mb-2"><span class="fw-semibold me-2">{{ __('Contact Number') }}:</span>
                        {{ $driver_data->phone ? $driver_data->phone : '' }}
                    </p>
                </div>
            @endif

            @if (in_array(6, $vendor_order_status_option_ids))
            <div class="col-lg-6 card-body">
                <h4 class="header-title mb-3">{{ __('Upload Report') }}</h4>

                @if ($order->reports != null)
                <div class="upload-report py-1">
                    <a target="_blank" class="d-inline-block" href="{{ $order->reports->report['original'] }}" download><i class="fa fa-download" aria-hidden="true"></i> &nbsp;Download
                        Report</a>
                    <a href="{{ route('order.report.delete', $order->reports->id) }}"> <span><i class="fa fa-times floar-right" aria-hidden="true"></i></span></a>
                </div>
                {{-- <form class="" action="{{route('order.upload.report')}}" method="post">
                <input type="text">
                </form> --}}
                @endif
                <form class="" action="{{ route('order.upload.report') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ $order->id }}" name="order_id">
                    <input type="hidden" value="{{ $vendor_data->id }}" name="vendor_id">
                    <div class="card px-2">
                        <div class="dropify-wrapper report-upload-subt w-50">
                            {{-- <div class="dropify-message">
                                        <span class="file-icon"></span>
                                        <p>Drag and drop a file here or click</p>
                                        <p class="dropify-error">Ooops, something wrong appended.</p>
                                    </div> --}}
                            <div class="dropify-loader"></div>
                            <div class="dropify-errors-container">
                                <ul></ul>
                            </div>
                            <input required type="file" accept="image/*,.pdf,.doc" data-plugins="dropify" name="file_name" class="dropify" data-default-file="">
                            <button type="button" class="dropify-clear">Remove</button>
                            <div class="dropify-preview">
                                <span class="dropify-render"></span>
                                <div class="dropify-infos">
                                    <div class="dropify-infos-inner">
                                        <p class="dropify-filename">
                                            <span class="file-icon"></span>
                                            <span class="dropify-filename-inner"></span>
                                        </p>
                                        <p class="dropify-infos-message">Drag and drop or click to
                                            replace</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-50 mt-3 btn btn-info waves-effect waves-light mt-2">Submit</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
    @elseif($order->luxury_option_id == 2 || $order->luxury_option_id == 3)
    <div class="col-lg-6 mb-3">
        <div class="card mb-0 h-100">
            <div class="card-body">
                <h4 class="header-title mb-3">{{ __('User Information') }}</h4>
                <h5 class="font-family-primary fw-semibold">{{ $order->user->name }}</h5>
                <p class="mb-2"><span class="fw-semibold me-2">{{ __('Address') }}:</span>
                    {{ $order->user->address->first() ? $order->user->address->first()->address : __('Not Available') }}
                </p>
                <p class="mb-0"><span class="fw-semibold me-2">{{ __('Mobile') }}:</span>
                    {{ $order->user->phone_number ? $order->user->phone_number : __('Not Available') }}
                </p>
                @if (isset($order->address) && !empty($order->address->street))
                <p class="mb-2"><span class="fw-semibold me-2">{{ __('Street') }}:</span>
                    {{ $order->address ? $order->address->street : '' }}
                </p>
                @endif
                <p class="mb-2"><span class="fw-semibold me-2">{{ __('City') }}:</span>
                    {{ $order->address ? $order->address->city : '' }}
                </p>
                @if (isset($order->address) && !empty($order->address->state))
                <p class="mb-2"><span class="fw-semibold me-2">{{ __('State') }}:</span>
                    {{ $order->address ? $order->address->state : '' }}
                </p>
                @endif
                <p class="mb-0"><span class="fw-semibold me-2">{{ getNomenclatureName('Zip Code', true) }}:</span>
                    {{ $order->address ? $order->address->pincode : '' }}
                </p>

            </div>
        </div>
    </div>
    @endif

    <div class="col-lg-6 mb-3">
        <div class="card mb-0 h-100">
            <div class="card-body">
                <h4 class="header-title mb-3">{{ __('Payment Information') }}</h4>
                <p class="mb-2"><span class="fw-semibold me-2">{{ __('Payment By') }} :</span>
                    {{ $order->paymentOption ? $order->paymentOption->title : '' }}
                </p>
                @if ($order->payment)
                <p class="mb-2"><span class="fw-semibold me-2">{{ __('Transaction Id') }} :</span>
                    {{ $order->payment ? $order->payment->transaction_id : '' }}
                </p>
                @endif
            </div>
            @if ($getAdditionalPreference['blockchain_route_formation'] == 1)

            <div class="card-body">
                <h4 class="header-title mb-3">{{ __('Blockchain Order Information') }}</h4>
                <a href="{{ route('orders.getBlockchainOrderDetail', ['order_id' => $order->id]) }}" target="_blank">
                    <button type="button" id="blockchain_order_data" data-id="{{ $order->id }}" class="btn btn-primary">Get Blockchain Order Data</button>
                </a>
            </div>

            @endif
            <div class="card-body">
                <h4 class="header-title mb-3 ">{{ __('Comment/Schedule Information') }}</h4>

                @if ($order->comment_for_pickup_driver)
                <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Comment for Pickup Driver') }} :</span>
                    {{ $order->comment_for_pickup_driver ?? '' }}
                </p>
                @endif

                @if ($order->comment_for_dropoff_driver)
                <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Comment for Dropoff Driver') }} :</span>
                    {{ $order->comment_for_dropoff_driver ?? '' }}
                </p>
                @endif

                @if ($order->comment_for_vendor)
                <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Comment for Vendor') }} :</span>
                    {{ $order->comment_for_vendor ?? '' }}
                </p>
                @endif

                @if ($order->schedule_pickup)
                <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Schedule Pickup') }}
                        :</span>
                    {{ dateTimeInUserTimeZone($order->schedule_pickup, $timezone) . ' ' . ($order->scheduled_slot ? ', Slot : ' . $order->scheduled_slot : '') }}
                </p>
                @endif

                @if ($order->schedule_dropoff)
                <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Schedule Dropoff') }}
                        :</span>
                    {{ dateTimeInUserTimeZone($order->schedule_dropoff, $timezone) . ' ' . ($order->dropoff_scheduled_slot ? ', Slot : ' . $order->dropoff_scheduled_slot : '') }}
                </p>
                @endif

                @if ($order->specific_instructions)
                <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Specific instructions') }} :</span>
                    {{ $order->specific_instructions ?? '' }}
                </p>
                @endif

            </div>

            @if ($getAdditionalPreference['is_enable_allergic_items'] == 1)
            <div class="card-body">
                @if (count($order->user->allergicItems))
                    <h4 class="header-title mb-3 "> {{ __('Customer Allergic Items')}} </h4>
                @endif
                @forelse ($order->user->allergicItems as $item)
                    {{ $item->title }}@if(!$loop->last),@endif
                @empty
                    <b>{{ __('No Allergic Item Found')}}</b><br>
                @endforelse

                @if ($order->user->custom_allergic_items)
                    <h4 class="header-title mb-3 "> {{ __('Custom Allergic Items')}} </h4>
                    {{ $order->user->custom_allergic_items }}
                @endif
            </div>
            @endif

        </div>
    </div>
    @if (!empty($processorProduct) && $processorProduct->is_processor_enable == 1)
        <div class="col-lg-6 mb-3">
            <div class="card mb-0 h-100">
                <div class="card-body">
                    <h4 class="header-title mb-3">{{ __('Processor Information') }}</h4>
                    <p class="mb-2"><span class="fw-semibold me-2">{{ __('Processor Name') }}:</span>
                        {{ $processorProduct->name }}
                    </p>
                    <p class="mb-2"><span class="fw-semibold me-2">{{ __('Processor Address') }}:</span>
                        {{ $processorProduct->address }}
                    </p>
                    <p class="mb-2"><span class="fw-semibold me-2">{{ __('Processor Date') }}:</span>
                        {{ $processorProduct->date }}
                    </p>
                </div>

            </div>
        </div>
    @endif
    @if (count($user_registration_documents) > 0)
    <div class="col-lg-6 mb-3">
        <div class="card mb-0">
            <div class="card-body">
                <h4 class="header-title mb-3">{{ __('User Proof') }}</h4>
                @foreach ($user_registration_documents as $user_registration_document)
                @php
                $field_value = '';
                if (!empty($user_docs) && count($user_docs) > 0) {
                foreach ($user_docs as $key => $user_doc) {
                if ($user_registration_document->id == $user_doc->user_registration_document_id) {
                if ($user_registration_document->file_type == 'Text' || $user_registration_document->file_type == 'selector') {
                $field_value = $user_doc->file_name;
                } else {
                $field_value = $user_doc->image_file['storage_url'];
                }
                }
                }
                }
                @endphp
                <div class="mb-2">
                    @if ($field_value)
                    <label class="mb-2"><b>{{ $user_registration_document->primary ? $user_registration_document->primary->name : '' }}
                            : </b></label>
                    @if (strtolower($user_registration_document->file_type) == 'image')
                    <a href="{{ $field_value }}" target="_blank">
                        <div class="border rounded-lg royo-thumnail_img text-center ">
                            <img src="{{ $field_value }}" class="img-thumbnail fi">
                        </div>
                    </a>
                    @elseif(strtolower($user_registration_document->file_type) == 'pdf')
                    <div>
                        <a href="{{ $field_value }}" target="_blank"><i class="fa fa-file-pdf fa-6x text-danger"></i></a>
                    </div>
                    @else
                    {{ $field_value }}
                    @endif
                    @endif
                </div>
                @endforeach
            </div>

        </div>
    </div>
    @endif
    <!-- Category kyc document -->
    @if (count($category_KYC_document) > 0)
    <div class="col-lg-6 mb-3">
        <div class="card mb-0">
            <div class="card-body">
                <h4 class="header-title mb-3">{{ __('User Place Order Documents') }}</h4>
                @foreach ($category_KYC_document as $document)
                @php

                $field_value = $document->image_file['storage_url'];
                @endphp
                <div class="mb-2">
                    @if ($field_value)
                    <label class="mb-2"><b>{{ $document->category_document->primary ? $document->category_document->primary->name : '' }}
                            : </b></label>
                    @if (strtolower($document->category_document->file_type) == 'image')
                    <a href="{{ $field_value }}" target="_blank">
                        <div class="border rounded-lg royo-thumnail_img text-center ">
                            <img src="{{ $field_value }}" class="img-thumbnail fi">
                        </div>
                    </a>
                    @elseif(strtolower($document->category_document->file_type) == 'pdf')
                    <div>
                        <a href="{{ $field_value }}" target="_blank"><i class="fa fa-file-pdf fa-6x text-danger"></i></a>
                    </div>
                    @else
                    {{ $field_value }}
                    @endif
                    @endif
                </div>
                @endforeach
            </div>

        </div>
    </div>
    @endif

</div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Upload Report</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="post" enctype="multipart/form-data" action="{{route('orderDocument',['order_id' => $order->id, 'vendor_id' => $order->vendors[0]->vendor_id]) }}" >
                @csrf
                <div class="form-group">
                    <input type="file" name="document[]" multiple class="form-control mb-2" >
                    <input type="submit" class="btn btn-success text-center" value="Upload" />
                </div>
            </form>
        </div>
        <div class="card-body">
            @if(count($order->vendors) > 0)
                <h4 class="header-title mb-3">{{__('Reports') }}</h4>
                @foreach($order->vendors as $doc)
                    @foreach($doc->orderDocument as $file)
                        @php
                            $files = Storage::disk('s3')->url($file['document']);
                        @endphp
                        <div class="mb-2 d-flex ">
                            <div class="col-9">
                                <img  src="{{url('file-download' . '/pdf.png')}}"    ><a href="{{$files}}"> {{$file['file_name']}}   </a>
                            </div>
                            <div class="col-3">
                                <a  href="{{route('deleteDocument',$file->id)}}" > <i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            @endif
        </div>

        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
      </div>
    </div>
  </div>

      <!-- modal for Delay Time -->
<div class="modal fade delay_time" id="showDelayTimeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Delay Time</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         @foreach ($order->vendors as $vendor)
         @if($vendor->vendor_id == $vendor_id)
         <div class="modal-body mt-0 pt-0">
            <div class="form-group">
               <label for="message-text" class="col-form-label">Enter Time(in minutes):</label>
               <input type="number" class="form-control" value="{{$vendor->extra_time}}" id="buffer_time">
            </div>
         </div>
         <div class="modal-footer pt-0">
            @php
            $track = explode('/', $vendor->dispatch_traking_url);
            $track_code = end($track);
            @endphp
            <button class="buffer_time_btn  badge badge-info border-0"   data-tracking_id={{$track_code}} data-tracking_url={{$vendor->dispatch_traking_url}}   data-order_id={{$order->id}} data-vendor_id={{$vendor_id}}>{{ __('Save') }} <img src=""> </button>
         </div>
         @endif
         @endforeach
      </div>
   </div>
</div>

<!-- product return modal -->
<div class="modal fade return-order" id="return_order" tabindex="-1" aria-labelledby="return_orderLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <div id="return-order-form-modal">

                </div>


            </div>
        </div>
    </div>
</div>
<!-- end product return modal -->





<div id="delivery_info_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __('Delivery Info') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="AddCardBox">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect waves-light submitAddForm">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- modal for product order form -->
<div class="modal fade product-order-form" id="order_product_order_form" tabindex="-1" aria-labelledby="order_product_order_form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="order_product-order-form-modal">

                </div>
            </div>
        </div>
    </div>

    <!-- modal for Category KYC form -->
    <div class="modal fade caregory_kyc_form-form" id="caregory_kyc_form" tabindex="-1" aria-labelledby="caregory_kyc_form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div id="caregory_kyc_form-modal">

                    </div>
                </div>
            </div>
        </div>
    </div>






    <!-- Order Invoice Code -->
    <div style="display: none;">
        @include('backend.order.print')
    </div>
    <!--End Order Invoice Code -->
    @endsection
    @section('script')




    <div id="blockchain_order_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="blockchain_order_modal_label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="blockchain_order_modal_label">Order Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <table class="table">
                <thead>
                  <tr>
                    <th>Field</th>
                    <th>Value</th>
                  </tr>
                </thead>
                <tbody id="order_data_table">
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>


    <script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
    <script>
        $('body').on('click', '.show-return-product-modal', function(event) {
            $(".vendor-name").click(function(e) {
                e.stopPropagation();
            });
            event.preventDefault();
            var id = $(this).data('id');
            var status = $(this).data('status');
            var returnurl = "{{route('get-return-product-modal')}}";
            $.get(returnurl + '?id=' + id + '&status=' + status, function(markup) {
                $('#return_order').modal('show');
                $('#return-order-form-modal').html(markup);
            });
        });

        //mohit sir branch code added by sohail
        $(".update_product_price").click(function() {
            $('#or-vend-prod-id').val($(this).data('or_vend_prod_id'));
            $('#or-prod-old-price').val($(this).data('or_prod_old_price'));
            $('#vendor_order_product_price_modal').modal({
                keyboard: false
            });
        });
        if ($("#update-order-product-price-form").length > 0) {
            $(document).on('click', '.submitOrderUpdatedPriceByVendor', function(e) {
                e.preventDefault();
                var form =  document.getElementById('update-order-product-price-form');
                var formData = new FormData(form);

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
                    url: "{{route('update.product.price')}}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        $(".loader_box").show();
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {

                    },
                    complete: function(){
                        $('.loader_box').hide();
                    }
                });
            });
        }
        //till here


        $("#order_statuses li").click(function() {
            var reload_page = `{{in_array($order->luxury_option_id,[6,8]) ? 1 : 0}}`;

            Swal.fire({
                title: "{{__('Are you sure?')}}",
                // text:"{{__('You want to delete the banner.')}}",
                // icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ok',
            }).then((result) => {
                if (result.value) {
                    let that = $(this);
                    var status_option_id = that.data("status_option_id");
                    var order_vendor_id = that.data("order_vendor_id");
                    $.ajax({
                        url: "{{ route('order.changeStatus') }}",
                        type: "POST",
                        data: {
                            order_id: "{{ $order->id }}",
                            vendor_id: "{{ $vendor_id }}",
                            "_token": "{{ csrf_token() }}",
                            status_option_id: status_option_id,
                            order_vendor_id: order_vendor_id,
                        },
                        success: function(response) {
                            console.log(response);
                            that.addClass("completed");
                            if (status_option_id == 2) {
                                // if (reload_page == 1 || reload_page == '1') {
                                //     // setTimeout(function(){location.reload();}, 2500);
                                // }
                                that.next('li').remove();
                            }
                            if (status_option_id == 3) {
                                that.prev('li').remove();
                                that.nextAll('li').remove();
                            }
                            $('#text_muted_' + status_option_id).html(
                                '<small class="text-muted">' + response.created_date +
                                '</small>');
                            if (status_option_id == 2 || status_option_id == 4)
                                $.NotificationApp.send("Success", response.message, "top-right",
                                    "#5ba035", "success");
                            //location.reload();
                            setTimeout(function() {
                               // location.reload();
                            }, 3000);
                        },
                        beforeSend: function() {
                            spinnerJS.showSpinner();
                        },
                        complete: function() {
                            spinnerJS.hideSpinner();
                        },
                    });
                } else {
                    return false;
                }
            });

        });

        $('#Order_category_kyc_document').click(function() {
            $('#caregory_kyc_form').modal('show');
        });

        // if($('.Complete_longTermBoking').lenght > 0){
        $(document).on('click', '.Complete_longTermBoking', function(e) {

            let service_id = $(this).attr('data-service_id');
            console.log(service_id);
            var formData = {
                service_id: service_id
            }
            updateLongTermBooking(formData);
        });
        //}
        async function updateLongTermBooking(formData) {

            axios.post(`/client/long_term_service/updateBooking`, formData)
                .then(async response => {
                    console.log(response);
                    //var data = response.data.variant_data;

                    if (response.data.success) {
                        $.NotificationApp.send("Success", response.data.message, "top-right",
                            "#5ba035", "success");
                    } else {
                        $.NotificationApp.send("Error", _language.getLanString('Something went wrong, try again later!'), "top-right",
                            "#ab0535", "error");

                    }
                })
                .catch(e => {
                    console.log(e);
                    $.NotificationApp.send("Error", _language.getLanString('Something went wrong, try again later!'), "top-right",
                        "#ab0535", "error");
                })
            setTimeout(function() {
                location.reload();
            }, 3000);
        }
        $("#create_dispatch_request").click(function() {
            Swal.fire({
                title: "{{ __('Are you sure?') }}",
                // text:"{{ __('You want to delete the banner.') }}",
                // icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ok',
            }).then((result) => {
                if (result.value) {

                    let that = $(this);
                    var order_vendor_id = that.data("order_vendor_id");
                    $.ajax({
                        url: "{{ route('create.dispatch.request') }}",
                        type: "POST",
                        data: {
                            order_id: "{{ $order->id }}",
                            vendor_id: "{{ $vendor_id }}",
                            "_token": "{{ csrf_token() }}",
                            order_vendor_id: order_vendor_id,
                        },
                        success: function(response) {
                            $.NotificationApp.send("Success", response.message, "top-right",
                                "#5ba035", response.status);
                            // location.reload();
                        },
                        error: function(error) {
                            var response = $.parseJSON(error.responseText);
                            let error_messages = response.message;
                            Swal.fire({
                                // title: "Warning!",
                                text: error_messages,
                                icon: "error",
                                button: "{{ __('ok') }}",
                            });
                            //  alert(error_messages);
                            location.reload();
                        }
                    });
                } else {
                    return false;
                }
            });
        });

        $(document).on('click', '.complete_request_btn', function(e) {
            let id = $(this).attr('data-id');
            let status = $(this).attr('data-status');
            let title = $(this).attr('title');
            Swal.fire({
                title: "Are you sure?",
                text: "You really want to " + title + " this request?",
                icon: 'warning',
                iconColor: '{{ getClientPreferenceDetail()->web_color }}',
                showCancelButton: true,
                confirmButtonText: 'Yes, ' + title + ' it!',
                confirmButtonColor: '{{ getClientPreferenceDetail()->web_color }}'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        data: {
                            id: id,
                            status: status
                        },
                        url: "{{ route('cancel-order.request.status.update') }}",
                        headers: {
                            Accept: "application/json"
                        },
                        success: function(response) {
                            if (response.status == 'Success') {
                                $.NotificationApp.send("Success", response.message, "top-right",
                                    "#5ba035", "success");
                                setTimeout(function() {
                                    location.reload();
                                }, 2500);
                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon: "error",
                                    button: "OK",
                                });
                                return false;
                            }
                        },
                        beforeSend: function() {
                            $(".loader_box").show();
                        },
                        complete: function() {
                            $(".loader_box").hide();
                        },
                        error: function(response) {
                            let error = response.responseJSON;
                            Swal.fire({
                                text: error.message,
                                icon: "error",
                                button: "OK",
                            });
                            return false;
                        }
                    });
                }
            });
        });
        $(document).on('click', '.Order_product_form', function(e) {
            var product_form_id = $(this).attr('data-product_form_id');

            var href = "{{ url('client/orders/product_faq') }}" + "/" + product_form_id;
            $.ajax({
                type: "GET",
                url: href,
                success: function(response) {
                    $('#order_product_order_form').modal('show');
                    $('#order_product-order-form-modal').html(response);
                    $('#order_product_order_form').modal('show');
                },
                error: function(error) {
                    Swal.fire({
                        text: "{{ __('Something went wrong!') }}",
                        icon: "error",
                        button: "OK",
                    });
                }
            });
            // $.get(href, function(response) {
            //     console.log(response);
            //     $('#order_product-order-form-modal').html(response);
            //     $('#order_product_order_form').modal('show');
            //  });
            // $('#order_product-order-form-modal').html(product_form_data);
        });

        function printDiv() {
            var divToPrint = document.getElementById('al_print_area');
            var windowUrl = 'about:blank';
            var windowName = 'Print Order Detail';
            var newWin = window.open(windowUrl, windowName);
            newWin.document.write(divToPrint.innerHTML);
            newWin.document.close();
            newWin.focus();
            newWin.print();
            setTimeout(function() {
                newWin.close();
            }, 10);
        }




        $(document).on('click', '.buffer_time_btn', function(e) {
            var time = $("#buffer_time").val();
            var order_id = $(this).data('order_id');
            var vendor_id = $(this).data('vendor_id');
            var tracking_id = $(this).data('tracking_id');
            if(time <=0 ){
                alert("Please enter a valid minutes");
                return false;
            }
            $.ajax({
                        type: "POST",
                        data: {
                            order_id: order_id,
                            vendor_id: vendor_id,
                            time:time,
                            tracking_id:tracking_id
                        },
                        url: "{{ route('order.delay_time') }}",
                        headers: {
                            Accept: "application/json"
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.status == 'success') {
                                $('#showDelayTimeModal').modal('hide');
                                $.NotificationApp.send("Success", response.message, "top-right",
                                    "#5ba035", "success");

                            }else{
                                  $.NotificationApp.send("Error", response.message, "top-right",
                                    "#5ba035", "error");
                            }
                        },
                        error: function(response) {
                            let error = response.responseJSON;
                            Swal.fire({
                                text: error.message,
                                icon: "error",
                                button: "OK",
                            });
                            return false;
                        }
                    });
        });


    </script>
    @endsection
