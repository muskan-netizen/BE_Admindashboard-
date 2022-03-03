@extends('layouts.vertical', ['title' => 'Order Detail'])
@section('css')
<link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<style>
/* td { white-space:pre-line; word-break:break-all} */
#cancel-request-card{
    background: #ddd;
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
                    <h4 class="page-title">{{ __("Order Detail") }}</h4>
                    <div class="al_back_btn">
                        <a class="al_print_btn_back mr-2" href="{{ url()->previous() }}">Back</a>
                        <button class="al_print_btn badge badge-info" onclick='printDiv();'>Print <img src=""> </button>
                    </div>
                </div>

            </div>
        </div>
        
        @if($order->vendors->first())
            @if( ($order->vendors->first()->cancel_request) && ($order->vendors->first()->cancel_request->status == 'Pending') )
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <div class="card mb-0 h-100" id="cancel-request-card">
                        <div class="card-body">
                            <h4 class="header-title mb-3">{{__('Cancel Order Request')}}</h4>
                            <button type="button" class="complete_request_btn btn btn-sm btn-info" title='Approve' data-status="1" data-id="{{$order->vendors->first()->cancel_request->id}}">
                                <i class='fa fa-check mr-1'></i> Approve
                            </button>
                            <button type="button" class="complete_request_btn btn btn-sm btn-danger" title='Reject' data-status="2" data-id="{{$order->vendors->first()->cancel_request->id}}">
                                <i class='fa fa-times mr-1'></i> Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif
        <div class="row">
            <div class="col-lg-4 mb-3">
                <div class="card mb-0 h-100">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{__('Track Order')}}</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h5 class="mt-0">{{__('Order ID')}}:</h5>
                                    <p>#{{$order->order_number}}</p>
                                </div>
                            </div>
                             @if(isset($order->vendors) && empty($order->vendors->first()->dispatch_traking_url) && ($order->vendors->first()->delivery_fee > 0) && ($order->vendors->first()->order_status_option_id >= 2) && $order->vendors->first()->shipping_delivery_type=='D')
                             <div class='inner-div d-inline-block' style="float: right;">
                                <form method='POST' action='"+full.destroy_url+"'>

                                        <button type='button' class='btn btn-danger' id="create_dispatch_request"  data-order_vendor_id="{{$order->vendors->first()->id}}">{{__('Create Dispatch Request')}}</i>
                                        </button>

                                </form>
                             </div>
                            @endif

                            @if(isset($order->vendors) && isset($order->vendors->first()->dispatch_traking_url) && $order->vendors->first()->dispatch_traking_url !=null && $order->vendors->first()->dispatch_traking_url !=0 )
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h5 class="mt-0">{{ __("Tracking ID") }}:</h5>
                                    <p>
                                        @php
                                        $track = explode('/',$order->vendors->first()->dispatch_traking_url);
                                        $track_code = end($track);
                                        @endphp
                                        <a href="{{$order->vendors->first()->dispatch_traking_url}}" target="_blank">#{{ $track_code }}</a>
                                    </p>
                                </div>
                            </div>
                            @elseif(isset($order->vendors) && isset($order->vendors->first()->lalamove_tracking_url) && $order->vendors->first()->lalamove_tracking_url !=null )

                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <h5 class="mt-0">{{ __("Tracking ID") }}:</h5>
                                    <p>
                                        <a href="{{$order->vendors->first()->lalamove_tracking_url}}" target="_blank">#{{ $order->vendors->first()->web_hook_code }}</a>
                                    </p>
                                </div>
                            </div>

                            @endif
                        </div>
                        <div class="row track-order-list">
                            <div class="col-lg-6">
                                <!-- <button type="button" class="btn btn-danger waves-effect waves-light">
                                    <i class="mdi mdi-close"></i>
                                 </button> -->
                                <ul class="list-unstyled" id="order_statuses">
                                    @php
                                    if($order->vendors->first()->order_status_option_id == 2)
                                    $open_option = ['4'];
                                    elseif ($order->vendors->first()->order_status_option_id == 3)
                                    $open_option = ['0'];
                                    elseif ($order->vendors->first()->order_status_option_id == 1)
                                    $open_option = ['2','3'];
                                    else
                                    $open_option = [$order->vendors->first()->order_status_option_id + 1];
                                    @endphp

                                    <!-- List of completed order status -->
                                @if(count($vendor_order_statuses))
                                    @foreach ($vendor_order_statuses as $key => $vendor_order_status)
                                        @php
                                            $order_status = $order_status_options->where('id', $vendor_order_status->order_status_option_id)->pluck('title')->first();
                                            $glow = '';
                                            if( $key < count($vendor_order_statuses)-1 ){
                                                $glow = 'completed';
                                            }
                                            $date = isset($vendor_order_status_created_dates[$vendor_order_status->order_status_option_id]) ? $vendor_order_status_created_dates[$vendor_order_status->order_status_option_id] : '';
                                        @endphp

                                        <li class="{{$glow}} disabled" data-status_option_id="{{$vendor_order_status->order_status_option_id}}" data-order_vendor_id="{{$vendor_order_status->vendor_id}}">
                                            @if( ($vendor_order_status->order_status_option_id == 5) && (($order->luxury_option_id == 2) || ($order->luxury_option_id == 3)) )
                                                <h5 class="mt-0 mb-1">{{__('Order Prepared')}}</h5>
                                            @else
                                                <h5 class="mt-0 mb-1">{{$order_status}}</h5>
                                            @endif
                                            <p class="text-muted" id="text_muted_{{$vendor_order_status->order_status_option_id}}">
                                                @if($date)
                                                    <small class="text-muted">{{dateTimeInUserTimeZone($date, $timezone)}}</small>
                                                @endif
                                            </p>
                                        </li>
                                    @endforeach
                                @endif

                                    <!-- List of incomplete order status if order is not rejected -->

                                    @if(!in_array(3, $vendor_order_status_option_ids))
                                        @foreach($order_status_options as $order_status_option)
                                            @if(!in_array($order_status_option->id, $vendor_order_status_option_ids))
                                                @php
                                                    $class = in_array($order_status_option->id, $vendor_order_status_option_ids) ? 'disabled': '';
                                                    if($order_status_option->id == $order->vendors->first()->order_status_option_id)
                                                        $glow = '';
                                                    else
                                                        $glow = 'completed';
                                                        $date = isset($vendor_order_status_created_dates[$order_status_option->id]) ? $vendor_order_status_created_dates[$order_status_option->id] : '';
                                                @endphp
                                                @if (in_array(3, $vendor_order_status_option_ids) && $order_status_option->id == 2)
                                                    @continue
                                                @endif
                                                @if (in_array(2, $vendor_order_status_option_ids) && $order_status_option->id == 3)
                                                    @continue
                                                @endif

                                                <li class="{{$class}} {{$glow}}  @if(in_array($order_status_option->id, $open_option))open-for-update-status @else disabled @endif" data-status_option_id="{{$order_status_option->id}}" data-order_vendor_id="{{$order_status_option->order_vendor_id}}">
                                                    @if( ($order_status_option->id == 5) && (($order->luxury_option_id == 2) || ($order->luxury_option_id == 3)) )
                                                        <h5 class="mt-0 mb-1">{{__('Order Prepared')}}</h5>
                                                    @elseif($order_status_option->id == 2)
                                                        <h5 style="padding: 2px 10px;" class="mt-0 mb-1 text-info">
                                                           {{$order_status_option->title}}</h5>
                                                    @elseif($order_status_option->id == 3)
                                                    <h5 style="padding: 2px 10px;" class="mt-0 mb-1 text-danger">
                                                       {{$order_status_option->title}} </h5>
                                                    @else
                                                    <h5 class="mt-0 mb-1">{{$order_status_option->title}}</h5>
                                                    @endif
                                                    <p class="text-muted" id="text_muted_{{$order_status_option->id}}">
                                                        @if($date)
                                                            <small class="text-muted">{{dateTimeInUserTimeZone($date, $timezone)}}</small>
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



                            @if(isset($order->vendors) && ($order->vendors->first()->dispatch_traking_url !=null || $order->vendors->first()->lalamove_tracking_url !=null))
                            <div class="col-lg-6">
                                <ul class="list-unstyled remove-curser">
                                    @foreach($dispatcher_status_options as $dispatcher_status_option)
                                    @php
                                    if($dispatcher_status_option->vendorOrderDispatcherStatus && $dispatcher_status_option->id == $dispatcher_status_option->vendorOrderDispatcherStatus->dispatcher_status_option_id??'')
                                    $class = 'disabled';

                                    if($dispatcher_status_option->id == $order->vendors->first()->dispatcher_status_option_id)
                                    $glow = '';
                                    else
                                    $glow = 'completed';

                                    $date = isset($dispatcher_status_option->vendorOrderDispatcherStatus) ? $dispatcher_status_option->vendorOrderDispatcherStatus->created_at : '';
                                    @endphp
                                    <li class="{{$class}} {{$glow}}" data-status_option_id="{{$dispatcher_status_option->id}}">
                                        <h5 class="mt-0 mb-1">{{$dispatcher_status_option->title}}</h5>
                                        <p class="text-muted" id="dispatch_text_muted_{{$dispatcher_status_option->id}}">
                                            @if($date)
                                            <small class="text-muted">{{dateTimeInUserTimeZone($date, $timezone)}}</small>
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
            <div class="col-lg-8 mb-3">
                <div class="card mb-0 h-100">
                    <div class="card-body">
                        <h4 class="header-title mb-3">
                            <div class="form-ul mb-1">

                                <span><img src="{{@$vendor_data->logo['image_fit'].'32/32'.@$vendor_data->logo['image_path']}}" alt="product-img" height="20"></span>
                                 {{ $vendor_data->name }}</div>

                            @if($order->luxury_option_name != '')
                                <span class="badge badge-info mr-2">{{$order->luxury_option_name}}</span>
                            @endif
                            {{ __("Items from Order") }} #{{$order->order_number}}
                            {{-- <a href="{{ route('order.edit.detail',[$order->id,$order->vendors->first()->vendor_id])}}">{{__('Edit Order')}}</a> --}}
                        </h4>
                        @if($order->luxury_option_id == 2)
                            @foreach($order->vendors as $vendor)
                                <p>{{ $vendor->dineInTableName }} | Category : {{ $vendor->dineInTableCategory }} | Capacity : {{ $vendor->dineInTableCapacity }}</p>
                            @endforeach
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-centered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __("Product Name") }}</th>
                                        <th>{{ __("Product") }}</th>
                                        <th>{{ __("Quantity") }}</th>
                                        <th>{{ __("Price") }}</th>
                                        <th>{{ __("Total") }}</th>
                                    </tr>
                                </thead>
                                @foreach($order->vendors as $vendor)
                                <tbody>
                                    @php
                                    $sub_total = 0;
                                    $taxable_amount = 0;
                                    $revenue = 0;
                                    @endphp
                                    @foreach($vendor->products as $product)
                                    @if($product->order_id == $order->id)
                                    @php
                                    $taxable_amount = $vendor->taxable_amount;
                                    $vendor_service_fee = $vendor->service_fee_percentage_amount;
                                    $container_charges = $vendor->total_container_charges;
                                    $sub_total += $product->total_amount;
                                    $revenue += ($vendor->service_fee_percentage_amount + $vendor->admin_commission_percentage_amount + $vendor->admin_commission_fixed_amount);
                                    @endphp
                                    <tr>
                                        <th scope="row">

                                            <a href="{{ isset($product->product) ? route('product.edit', @$product->product->id) : '#'}}" target="_blank">
                                                {{$product->product_name}}
                                            </a>
                                            @if(isset($product->product) && isset($product->product->category) && isset($product->product->category->categoryDetail) && $product->product->category->categoryDetail->translation_one) ( in {{$product->product->category->categoryDetail->translation_one->name}} ) @endif
                                            <p class="p-0 m-0">
                                                @if(isset($product->scheduled_date_time)) {{dateTimeInUserTimeZone($product->scheduled_date_time, $timezone)}} @endif
                                            </p>
                                                @foreach($product->prescription as $pres)
                                                <br><a target="_blank" href="{{ ($pres) ? @$pres->prescription['proxy_url'].'74/100'.@$pres->prescription['image_path'] : ''}}">{{($product->prescription) ? 'Prescription' : ''}}</a>
                                                @endforeach

                                                <p class="p-0 m-0">{{ substr($product->product_variant_sets, 0, -2) }}</p>
                                            @if($product->addon && count($product->addon))
                                                <hr class="my-2">
                                                <h6 class="m-0 pl-0"><b>{{__('Add Ons')}}</b></h6>
                                                @foreach($product->addon as $addon)
                                                    <p class="p-0 m-0">{{ $addon->option->translation_title }}</p>
                                                @endforeach
                                            @endif
                                        </th>
                                        <td>
                                            @if($product->image_path)
                                            <img src="{{@$product->image_path['proxy_url'].'32/32'.@$product->image_path['image_path']}}" alt="product-img" height="32">
                                            @else
                                            @php $image_path = getDefaultImagePath(); @endphp
                                            <img src="{{$image_path['proxy_url'].'32/32'.$image_path['image_path']}}" alt="product-img" height="32">
                                            @endif
                                        </td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>
                                            {{$clientCurrency->currency->symbol}}{{decimal_format($product->price)}}
                                            @if($product->addon->isNotEmpty())
                                                <hr class="my-2">
                                                @foreach($product->addon as $addon)
                                                    <p class="p-0 m-0">{{$clientCurrency->currency->symbol}}{{ decimal_format($addon->option->price_in_cart) }}</p>
                                                @endforeach
                                            @endif
                                        </td>

                                        <td>{{$clientCurrency->currency->symbol}}{{decimal_format($product->total_amount)}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{__('Delivery Fee')}} :</th>
                                        <td>{{$clientCurrency->currency->symbol}}{{decimal_format($vendor->delivery_fee)}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Sub Total") }} :</th>
                                        <td>
                                            <div class="fw-bold">{{$clientCurrency->currency->symbol}}{{decimal_format($sub_total)}}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{__('Total Discount')}} :</th>
                                        <td>{{$clientCurrency->currency->symbol}}{{decimal_format($vendor->discount_amount)}}</td>
                                    </tr>

                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Estimated Tax") }} :</th>
                                        <td>{{$clientCurrency->currency->symbol}}{{decimal_format($taxable_amount)}}</td>
                                    </tr>
                                    @if($vendor_service_fee > 0)
                                        <tr>
                                            <th scope="row" colspan="4" class="text-end">{{ __("Service Fee") }} :</th>
                                            <td>{{$clientCurrency->currency->symbol}}{{decimal_format($vendor_service_fee)}}</td>
                                        </tr>
                                    @endif

                                    @if($container_charges > 0)
                                        <tr>
                                            <th scope="row" colspan="4" class="text-end">{{ __("Container Charges") }} :</th>
                                            <td>{{$clientCurrency->currency->symbol}}@money($container_charges)</td>
                                        </tr>
                                    @endif

                                    @if(Auth::user()->is_superadmin)
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{$client_head->name}} {{ __("Revenue") }} :</th>
                                        <td>{{$clientCurrency->currency->symbol}}{{decimal_format($revenue)}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Store Earning") }} :</th>
                                        <td>{{$clientCurrency->currency->symbol}}{{decimal_format($vendor->payable_amount * $clientCurrency->doller_compare - $revenue - $vendor->delivery_fee)}}</td>
                                    </tr>
                                    @endif
                                    @if(number_format($vendor->orderDetail->loyalty_points_used) > 0)
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Redemmed Loyality Points") }} :</th>
                                        <td style="width:200px;">{{$vendor->orderDetail->loyalty_points_used??0.00}} ({{$clientCurrency->currency->symbol}}{{decimal_format($vendor->orderDetail->loyalty_amount_saved??0.00)}})</td>
                                    </tr>
                                    @endif
                                    @if($vendor->reject_reason)
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Reject Reason") }} :</th>
                                        <td style="width:200px;">{{$vendor->reject_reason}}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Total") }} :</th>
                                        <td>
                                            <div class="fw-bold">{{$clientCurrency->currency->symbol}}{{decimal_format($vendor->payable_amount * $clientCurrency->doller_compare)}}</div>
                                        </td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            @if($order->address && ($order->luxury_option_id == 1) && ($client_preference_detail->hide_order_address ==0 ) )

            <div class="col-lg-6 mb-3">
                <div class="card mb-0 h-100">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ __("Delivery Information") }}</h4>
                        <h5 class="font-family-primary fw-semibold">{{$order->user->name}}</h5>
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __("Email") }}:</span> {{ $order->user->email ? $order->user->email : ''}}</p>
                        @if(!is_null($order->user) && isset($order->user->phone_number))
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __('Phone')}}:</span> {{'+'.$order->user->dial_code.$order->user->phone_number}}</p>
                        @endif
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __("Address") }}:</span> {{ $order->address->house_number ? $order->address->house_number."," : ''}} {{ $order->address ? $order->address->address : ''}}</p>
                        @if(isset($order->address) && !empty($order->address->street))
                        <p class="mb-2"><span class="fw-semibold me-2">{{__('Street')}}:</span> {{ $order->address ? $order->address->street : ''}}</p>
                        @endif
                        <p class="mb-2"><span class="fw-semibold me-2">{{__('City')}}:</span> {{ $order->address ? $order->address->city : ''}}</p>
                        @if(isset($order->address) && !empty($order->address->state))
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __("State") }}:</span> {{ $order->address ? $order->address->state : ''}}</p>
                        @endif
                        <p class="mb-0"><span class="fw-semibold me-2">{{ getNomenclatureName('Zip Code', true) }}:</span>  {{ $order->address ? $order->address->pincode : ''}}</p>
                    </div>
                </div>
            </div>

            @elseif( ($order->luxury_option_id == 2) || ($order->luxury_option_id == 3) )
            <div class="col-lg-6 mb-3">
                <div class="card mb-0 h-100">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ __("User Information") }}</h4>
                        <h5 class="font-family-primary fw-semibold">{{$order->user->name}}</h5>
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __("Address") }}:</span> {{ $order->user->address->first() ? $order->user->address->first()->address : __('Not Available')}}</p>
                        <p class="mb-0"><span class="fw-semibold me-2">{{ __("Mobile") }}:</span> {{$order->user->phone_number ? $order->user->phone_number : __('Not Available')}}</p>
                        @if(isset($order->address) && !empty($order->address->street))
                        <p class="mb-2"><span class="fw-semibold me-2">{{__('Street')}}:</span> {{ $order->address ? $order->address->street : ''}}</p>
                        @endif
                        <p class="mb-2"><span class="fw-semibold me-2">{{__('City')}}:</span> {{ $order->address ? $order->address->city : ''}}</p>
                        @if(isset($order->address) && !empty($order->address->state))
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __("State") }}:</span> {{ $order->address ? $order->address->state : ''}}</p>
                        @endif
                        <p class="mb-0"><span class="fw-semibold me-2">{{ getNomenclatureName('Zip Code', true) }}:</span>  {{ $order->address ? $order->address->pincode : ''}}</p>

                    </div>
                </div>
            </div>
            @endif

            <div class="col-lg-6 mb-3">
                <div class="card mb-0 h-100">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ __('Payment Information') }}</h4>
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __('Payment By') }} :</span> {{ $order->paymentOption  ? $order->paymentOption->title : ''}}</p>
                        @if($order->payment)
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __('Transaction Id') }} :</span> {{ $order->payment  ? $order->payment->transaction_id : ''}}</p>
                        @endif
                    </div>


                    <div class="card-body">
                        <h4 class="header-title mb-3 ">{{ __('Comment/Schedule Information') }}</h4>
                        @if($order->comment_for_pickup_driver)
                          <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Comment for Pickup Driver') }} :</span> {{ $order->comment_for_pickup_driver ?? ''}}</p>
                        @endif

                        @if($order->comment_for_dropoff_driver)
                          <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Comment for Dropoff Driver') }} :</span> {{ $order->comment_for_dropoff_driver ?? ''}}</p>
                        @endif

                        @if($order->comment_for_vendor)
                          <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Comment for Vendor') }} :</span> {{ $order->comment_for_vendor ?? ''}}</p>
                        @endif

                        @if($order->schedule_pickup)
                          <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Schedule Pickup') }} :</span> {{dateTimeInUserTimeZone($order->schedule_pickup, $timezone)}} </p>
                        @endif

                        @if($order->schedule_dropoff)
                          <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Schedule Dropoff') }} :</span> {{dateTimeInUserTimeZone($order->schedule_dropoff, $timezone)}} </p>
                        @endif

                        @if($order->specific_instructions)
                          <p class="mb-2 text-danger"><span class="fw-semibold me-2">{{ __('Specific instructions') }} :</span> {{ $order->specific_instructions ?? ''}}</p>
                        @endif

                    </div>


                </div>
            </div>

            @if(count($user_registration_documents) > 0)
            <div class="col-lg-6 mb-3">
                <div class="card mb-0">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ __('User Proof') }}</h4>
                        @foreach($user_registration_documents as $user_registration_document)
                            @php
                            $field_value = "";
                            if(!empty($user_docs) && count($user_docs) > 0){
                                foreach($user_docs as $key => $user_doc){
                                    if($user_registration_document->id == $user_doc->user_registration_document_id){
                                        if($user_registration_document->file_type == 'Text' || $user_registration_document->file_type == 'selector' ){
                                            $field_value = $user_doc->file_name;
                                        } else {
                                            $field_value = $user_doc->image_file['storage_url'];
                                        }
                                    }
                                }
                            }
                            @endphp
                            <div class="mb-2">                                
                                @if($field_value)
                                    <label class="mb-2"><b>{{$user_registration_document->primary ? $user_registration_document->primary->name : ''}} : </b></label>
                                    @if(strtolower($user_registration_document->file_type) == 'image')
                                        <div class="border rounded-lg text-center">
                                            <img src="{{$field_value}}" class="fi" style="height: 200px !important; width: auto;">
                                        </div>
                                    @elseif(strtolower($user_registration_document->file_type) == 'pdf')
                                        <div>
                                            <a href="{{$field_value}}" target="_blank"><i class="fa fa-file-pdf fa-6x text-danger"></i></a>
                                        </div>
                                    @else
                                        {{$field_value}}
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
<div id="delivery_info_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Delivery Info") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="AddCardBox">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect waves-light submitAddForm">{{ __("Submit") }}</button>
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
<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
<script>
    $("#order_statuses li").click(function() {
        Swal.fire({
            title: "{{__('Are you sure?')}}",
           // text:"{{__('You want to delete the banner.')}}",
                // icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ok',
        }).then((result) => {
            if(result.value)
            {
                let that = $(this);
                var status_option_id = that.data("status_option_id");
                var order_vendor_id = that.data("order_vendor_id");
                $.ajax({
                    url: "{{ route('order.changeStatus') }}",
                    type: "POST",
                    data: {
                        order_id: "{{$order->id}}",
                        vendor_id: "{{$vendor_id}}",
                        "_token": "{{ csrf_token() }}",
                        status_option_id: status_option_id,
                        order_vendor_id: order_vendor_id,
                    },
                    success: function(response) {
                        console.log(response);
                        that.addClass("completed");
                        if (status_option_id == 2) {
                            that.next('li').remove();
                        }
                        if (status_option_id == 3) {
                            that.prev('li').remove();
                            that.nextAll('li').remove();
                        }
                        $('#text_muted_' + status_option_id).html('<small class="text-muted">' + response.created_date + '</small>');
                        if (status_option_id == 2)
                            $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        location.reload();
                    },
                });
            }else{
                return false;
            }
        });

    });


    $("#create_dispatch_request").click(function() {
        Swal.fire({
            title: "{{__('Are you sure?')}}",
           // text:"{{__('You want to delete the banner.')}}",
                // icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ok',
        }).then((result) => {
            if(result.value)
            {

                let that = $(this);
                var order_vendor_id = that.data("order_vendor_id");
                $.ajax({
                    url: "{{ route('create.dispatch.request') }}",
                    type: "POST",
                    data: {
                        order_id: "{{$order->id}}",
                        vendor_id: "{{$vendor_id}}",
                        "_token": "{{ csrf_token() }}",
                        order_vendor_id: order_vendor_id,
                    },
                    success: function(response) {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", response.status);
                    // location.reload();
                    },
                    error: function(error) {
                    var response = $.parseJSON(error.responseText);
                    let error_messages = response.message;
                    Swal.fire({
                        // title: "Warning!",
                        text: error_messages,
                        icon : "error",
                        button: "{{__('ok')}}",
                    });
                    //  alert(error_messages);
                    location.reload();
                    }
                });
            }else{
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
            text: "You really want to "+ title +" this request?",
            icon: 'warning',
            iconColor: '{{getClientPreferenceDetail()->web_color}}',
            showCancelButton: true,
            confirmButtonText: 'Yes, '+ title + ' it!',
            confirmButtonColor: '{{getClientPreferenceDetail()->web_color}}'
        }).then((result) => {
            if(result.value)
            {
                $.ajax({
                    type: "POST",
                    data: {id: id, status: status},
                    url: "{{ route('cancel-order.request.status.update') }}",
                    headers: {Accept: "application/json"},
                    success: function(response) {
                        if (response.status == 'Success') {
                            $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                            setTimeout(function(){location.reload();}, 2500);
                        } else {
                            Swal.fire({
                                text: response.message,
                                icon : "error",
                                button: "OK",
                            });
                            return false;
                        }
                    },
                    beforeSend: function(){
                        $(".loader_box").show();
                    },
                    complete: function(){
                        $(".loader_box").hide();
                    },
                    error: function(response) {
                        let error = response.responseJSON;
                        Swal.fire({
                            text: error.message,
                            icon : "error",
                            button: "OK",
                        });
                        return false;
                    }
                });
            }
        });
    });

    function printDiv()
    {
        var divToPrint=document.getElementById('al_print_area');
        var newWin=window.open('','Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
        newWin.document.close();
        setTimeout(function(){newWin.close();},10);
    }

</script>
@endsection
