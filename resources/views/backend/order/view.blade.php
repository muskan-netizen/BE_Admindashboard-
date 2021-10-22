@extends('layouts.vertical', ['title' => 'Order Detail'])
@section('css')
<!-- <style>
td { white-space:pre-line; word-break:break-all}
</style> -->
@endsection
@section('content')
@php
$timezone = Auth::user()->timezone;
@endphp
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ __("Order Detail") }}</h4>
                </div>
            </div>
        </div>
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
                            @if(isset($order->vendors) && isset($order->vendors->first()->dispatch_traking_url) && $order->vendors->first()->dispatch_traking_url !=null)
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

                                    @foreach($order_status_options as $order_status_option)
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
                                        @else
                                            <h5 class="mt-0 mb-1">{{$order_status_option->title}}</h5>
                                        @endif
                                        <p class="text-muted" id="text_muted_{{$order_status_option->id}}">
                                            @if($date)
                                                <small class="text-muted">{{convertDateTimeInTimeZone($date, $timezone, 'l, F d, Y, H:i A')}}</small>
                                            @endif
                                        </p>
                                    </li>
                                    @if (in_array(3, $vendor_order_status_option_ids) && $order_status_option->id == 3)
                                    @break
                                    @endif
                                    @endforeach
                                </ul>
                            </div>

                            @if(isset($order->vendors) && isset($order->vendors->first()->dispatch_traking_url) && $order->vendors->first()->dispatch_traking_url !=null)
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
                                            <small class="text-muted">{{convertDateTimeInTimeZone($date, $timezone, 'l, F d, Y, H:i A')}}</small>
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
                        <h4 class="header-title mb-3">{{ __("Items from Order") }} #{{$order->order_number}}</h4>
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
                                    @endphp
                                    @foreach($vendor->products as $product)
                                    @if($product->order_id == $order->id)
                                    @php
                                    $taxable_amount += $product->taxable_amount;
                                    $sub_total += $product->quantity * $product->price;
                                    @endphp
                                    <tr>
                                        <th scope="row">{{$product->product_name}}
                                            <p>
                                                @if(isset($product->scheduled_date_time)) {{convertDateTimeInTimeZone($product->scheduled_date_time, $timezone, 'l, F d, Y, H:i A')}} @endif
                                            <p>
                                                @foreach($product->prescription as $pres)
                                                <br><a target="_blank" href="{{ ($pres) ? $pres->prescription['proxy_url'].'74/100'.$pres->prescription['image_path'] : ''}}">{{($product->prescription) ? 'Prescription' : ''}}</a>
                                                @endforeach
                                        </th>
                                        <td>
                                            <img src="{{$product->image_path['proxy_url'].'32/32'.$product->image_path['image_path']}}" alt="product-img" height="32">
                                        </td>
                                        <td>{{ $product->quantity }}</td>
                                        <td>$@money($product->price)</td>

                                        <td>$@money($product->quantity * $product->price)</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{__('Delivery Fee')}} :</th>
                                        <td>$@money($vendor->delivery_fee)</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Sub Total") }} :</th>
                                        <td>
                                            <div class="fw-bold">$@money($sub_total)</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{__('Total Discount')}} :</th>
                                        <td>$@money($vendor->discount_amount)</td>
                                    </tr>

                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Estimated Tax") }} :</th>
                                        <td>$@money($taxable_amount)</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Reject Reason") }} :</th>
                                        <td style="width:200px;">{{$vendor->reject_reason}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" colspan="4" class="text-end">{{ __("Total") }} :</th>
                                        <td>
                                            <div class="fw-bold">$@money($vendor->payable_amount+$taxable_amount)</div>
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
            @if($order->address)
            <div class="col-lg-6 mb-3">
                <div class="card mb-0 h-100">
                    <div class="card-body">
                        <h4 class="header-title mb-3">{{ __("Shipping Information") }}</h4>
                        <h5 class="font-family-primary fw-semibold">{{$order->user->name}}</h5>
                        <p class="mb-2"><span class="fw-semibold me-2">{{ __("Address") }}:</span> {{ $order->address ? $order->address->address : ''}}</p>
                        <p class="mb-0"><span class="fw-semibold me-2">{{ __("Mobile") }}:</span> {{$order->user->phone_number}}</p>
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
                </div>
            </div>
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
@endsection
@section('script')
<script>
    $("#order_statuses li").click(function() {
        if (confirm("Are you Sure?")) {
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
        }
    });
</script>
@endsection