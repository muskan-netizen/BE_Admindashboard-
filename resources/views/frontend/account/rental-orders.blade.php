@php
    $ordertitle = 'Rental Orders';
    $orderTitles = [
        'all' => 'All',
        'upcoming' => 'Upcoming',
        'ongoing' => 'Ongoing',
    ];
    $clientData = \App\Models\Client::select('socket_url')->first();
@endphp
@extends('layouts.store', ['title' => __('My Rental ' . getNomenclatureName($ordertitle, true))])
@section('css')
    <link href="{{ asset('assets/css/azul.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @php
        $timezone = Auth::user()->timezone;
    @endphp
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
                        <div class="collection-mobile-back">
                            <span class="filter-back d-lg-none d-inline-block">
                                <i class="fa fa-angle-left" aria-hidden="true"></i>{{ __('Back') }}
                            </span>
                        </div>
                        @include('layouts.store/profile-sidebar')
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="dashboard-right">
                        <div class="dashboard">
                            <div class="page-title">
                                <h2>{{ __(getNomenclatureName($ordertitle, true)) }}</h2>
                            </div>
                            <div class="order_response mt-3 mb-3 d-none">
                                <div class="alert p-0" role="alert"></div>
                            </div>
                            <div class="welcome-msg">
                                <h5>{{ __('Here Are All Your Previous ' . getNomenclatureName($ordertitle, true)) }}</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="row" id="orders_wrapper">
                                    <div class="col-sm-12 col-lg-12 tab-product al_custom_ordertabs mt-md-3 p-0">
                                        <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link {{ Request::query('pageType') === null || Request::query('pageType') == 'all' ? 'active show' : '' }}" id="all-orders-tab" data-toggle="tab" href="#all-orders" role="tab" aria-selected="true"><i class="icofont icofont-ui-home"></i>{{ __($orderTitles['all']) }}</a>
                                                <div class="material-border"></div>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ Request::query('pageType') == 'upcoming' ? 'active show' : '' }}" id="upcoming_order-tab" data-toggle="tab" href="#upcoming_order" role="tab" aria-selected="false"><i class="icofont icofont-man-in-glasses"></i>{{ __($orderTitles['upcoming']) }}</a>
                                                <div class="material-border"></div>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ Request::query('pageType') == 'ongoing' ? 'active show' : '' }}" id="ongoing_order-tab" data-toggle="tab" href="#ongoing_order" role="tab" aria-selected="false"><i class="icofont icofont-man-in-glasses"></i>{{ __($orderTitles['ongoing']) }}</a>
                                                <div class="material-border"></div>
                                            </li>
                                        </ul>
                                        <div class="tab-content nav-material al" id="top-tabContent">
                                            <div class="tab-pane fade {{ Request::query('pageType') === null || Request::query('pageType') == 'all' ? 'active show' : '' }}"
                                                id="all-orders" role="tabpanel" aria-labelledby="all-orders-tab">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row no-gutters order_head">
                                                            <div class="col-md-3 alOrderStatus">
                                                                <h4>Order Number</h4>
                                                                <span>#63726760</span>
                                                            </div>
                                                            <div class="col-md-3 alOrderStatus">
                                                                <h4>Date &amp; Time</h4>
                                                                <span>2023-06-13 13:50</span>
                                                            </div>
                                                            <div class="col-md-3 alOrderStatus">
                                                                <h4>Customer Name</h4>
                                                                <span><a class="text-capitalize">Emart</a></span>
                                                            </div>
                                                            <div class="col-md-3 ellipsis">
                                                                <h4>Address</h4>
                                                                <div class="alOrderAddressBox">
                                                                    <span class="ellipsis alTTitle" data-toggle="tooltip" data-placement="top" title="" data-original-title="NA">NA<span>    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row no-gutters order_data d-none">
                                                            <div class="col-md-3">#63726760</div>
                                                            <div class="col-md-3">2023-06-13 13:50</div>
                                                            <div class="col-md-3">
                                                                <a class="text-capitalize">Emart</a>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <h4>Address</h4>
                                                                <div class="alOrderAddressBox">
                                                                    <span class="ellipsis alTTitle" data-toggle="tooltip" data-placement="top" title="" data-original-title="NA">NA<span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-9 mb-3">
                                                                <div class="order_detail order_detail_data align-items-top pb-3 card-box no-gutters mb-0">
                                                                    <div class="progress-order font-12  d-flex align-items-center justify-content-between pr-2">
                                                                        <span class="badge badge-success ml-2">Scheduled</span>
                                                                        <span class="ml-2 text-right">Slots:2023-06-13 23:24</span>
                                                                        <a class="start_chat chat-icon btn btn-solid" data-vendor_order_id="51" data-vendor_id="3" data-orderid="" data-order_id="48">Chat</a>
                                                                    </div>
                                                                    <span class="left_arrow pulse"></span>
                                                                    <div class="row">
                                                                        <div class="col-6 col-sm-4">
                                                                            <h5 class="m-0">Order Status</h5>
                                                                            <ul class="status_box mt-1 pl-0">
                                                                                <li>
                                                                                    <img src="http://192.168.100.156:9001/assets/images/order-icon.svg" alt="">
                                                                                    <label class="m-0 in-progress">Placed</label>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-6 col-sm-3">
                                                                            <ul class="product_list p-0 m-0 text-center">
                                                                                <li class="text-center mb-0 alOrderImg">
                                                                                    <img src="https://images.royoorders.com/insecure/fit/74/100/sm/0/plain/https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/1W2cqXPIfMVnibn2Eu2i4br4RSDwexkSLMu3nux8.jpg@webp" alt="">
                                                                                    <span class="item_no position-absolute">x1</span>
                                                                                </li>
                                                                                <li>
                                                                                    <label class="items_price">₹1710.00</label>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-md-5 mt-md-0 mt-sm-2">
                                                                            <ul class="price_box_bottom m-0 p-0">
                                                                                <li class="d-flex align-items-center justify-content-between">
                                                                                    <label class="m-0">Product Total</label>
                                                                                    <span>₹1710.00</span>
                                                                                </li>
                                                                                <li class="grand_total d-flex align-items-center justify-content-between">
                                                                                    <label class="m-0">Amount</label>
                                                                                    <span>₹1710.00</span>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3 mb-3 pl-lg-0">
                                                                <div class="card-box p-2 mb-0 h-100">
                                                                    <ul class="price_box_bottom m-0 pl-0 pt-1">
                                                                        <li class="d-flex align-items-center justify-content-between">
                                                                            <label class="m-0">Sub Total</label>
                                                                            <span>₹1710.00</span>
                                                                        </li>
                                                                        <li class="grand_total d-flex align-items-center justify-content-between">
                                                                            <label class="m-0">Total Payable</label>
                                                                            <span>₹1710.00</span>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade upcoming-order {{ Request::query('pageType') == 'upcoming' ? 'active show' : '' }} "id="upcoming_order" role="tabpanel" aria-labelledby="upcoming_order-tab">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        upcoming
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade ongoing-order {{ Request::query('pageType') == 'ongoing' ? 'active show' : '' }} "id="ongoing_order" role="tabpanel" aria-labelledby="ongoing_order-tab">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        ongoing
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
                <div class="box-account box-info"></div>
            </div>
        </div>
    </div>
</div>
</div>
</section>
@endsection
@section('script')
    <script src="{{ asset('assets/js/chat/user_vendor_chat.js') }}"></script>
@endsection
