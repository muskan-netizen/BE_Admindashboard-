@extends('layouts.store', ['title' => 'Product'])
@section('content')
<style type="text/css">

.cabbooking-loader {
  width: 30px;
  height: 30px;
  animation: loading 1s infinite ease-out;
  margin: auto;
  border-radius: 50%;
  background-color: red;
}
@keyframes loading {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(8);
    opacity: 0;
  }
}
.site-topbar,.main-menu.d-block{
    display: none !important;
}

.cab-booking-header img.img-fluid {
    height: 50px;
}
.cab-booking-header{
    display: block !important;
}
</style>

<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
if (strpos($url,'cabservice') !== false) {?>
<style>
    .container .main-menu .d-block{
         display: none;
     }
 </style>
<?php
} else { ?>
    <style>
        .cab-booking-header{
             display: none;
         }
     </style>
<?php }
?>
<header>
    <div class="mobile-fix-option"></div>
    @include('layouts.store/left-sidebar')
</header>
<section class="cab-booking pt-0">
    <div id="booking-map" style="width: 100%; height: 100%;"></div>
    <input id="booking-latitude" type="hidden" value="-34">
    <input id="booking-longitude"  type="hidden" value="151">

    <div class="booking-experience ds bc">
        <div class="address-form">

            <div class="loader-outer d-none">
                <div class="spinner-border avatar-lg text-primary m-2" role="status"></div>
            </div>

            <div class="location-box check-pick-first">
                <div class="where-to-go">
                    <div class="title title-36">{{__('Where can we pick you up?')}}</div>
                </div>
            </div>
            <div class="location-box check-dropoff-secpond" style="display:none">
                <ul class="location-inputs position-relative pl-2" id="location_input_main_div">
                    <li class="d-flex dots">
                        <div class="title title-24 position-relative edit-pickup">  {{__('From')}} - <span id="pickup-where-from"></span><i class="fa fa-angle-down" aria-hidden="true"></i></div>
                    </li>
                    <li class="d-flex dots where-to-first">
                        <div class="title title-36 pr-3 position-relative">{{__('Where To?')}}</div>
                    </li>
                    <li class="d-flex dots where-to-second" style="display:none !important;">
                        <div class="title title-24 position-relative edit-dropoff">  {{__('To')}} - <span id="dropoff-where-to"></span><i class="fa fa-angle-down" aria-hidden="true"></i></div>
                        <i class="fa fa-times ml-1 apremove" aria-hidden="true" data-rel=""></i>
                    </li>
                </ul>
                <a class="add-more-location position-relative pl-2" style="display:none" href="javascript:void(0)">{{__('Add Destination')}}</a>
            </div>
            <input type="hidden" name="pickup_location_latitude[]" value="" id="pickup_location_latitude">
            <input type="hidden" name="pickup_location_longitude[]" value="" id="pickup_location_longitude">
            <input type="hidden" name="destination_location_latitude[]" value="" id="destination_location_latitude"/>
            <input type="hidden" name="destination_location_longitude[]" value="" id="destination_location_longitude"/>
            <div class="location-container style-4">
                <div class="location-search d-flex align-items-center check-pickup">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <input class="form-control pickup-text pac-target-input" type="text" name="pickup_location_name[]" placeholder="Add A Pick-Up Location" id="pickup_location" autocomplete="off">
                </div>
                <div class="location-search d-flex align-items-center" style="display:none !important;" id="destination_location_add_more">
                </div>
                <div class="location-search d-flex align-items-center check-dropoff" style="display:none !important;">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <input class="form-control pickup-text" name="destination_location_name[]" type="text" placeholder="{{__('Add A Stop')}}" id="destination_location"/>
                </div>
                <div class="location-search d-flex align-items-center" style="display:none !important;" id="destination_location_add_temp">

                </div>
                <div class="scheduled-ride">
                    <button><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="mx-2 scheduleDateTimeApnd">Now</span> <i class="fa fa-angle-down" aria-hidden="true"></i></button>
                </div>
                <div class="loader cab-booking-main-loader"></div>
                <div class="location-list style-4"> 
                        <a class="select-location row align-items-center" id="get-current-location" href="javascript:void(0)">
                            <div class="col-2 text-center pl-4">
                                <div class="round-shape active-location">
                                    <i class="fa fa-crosshairs" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4><b>Allow location Access</b></h4>
                                <div class="current-location ellips text-color mb-2">Your current location</div>
                                <hr class="m-0">
                            </div>
                        </a>
                    @forelse($user_addresses as $user_address)
                        <!-- <a class="search-location-result position-relative d-block" href="javascript:void(0);" data-address="{{$user_address->address}}" data-latitude="{{$user_address->latitude}}" data-longitude="{{$user_address->longitude}}">
                            <h4 class="mt-0 mb-1"><b>{{$user_address->address}}</b></h4>
                            <p class="ellips mb-0">{{$user_address->city}}, {{$user_address->state}}, {{$user_address->country}}</p>
                        </a> -->
                        <a class="search-location-result position-relative row align-items-center mt-2" href="javascript:void(0);" data-address="{{$user_address->address}}" data-latitude="{{$user_address->latitude}}" data-longitude="{{$user_address->longitude}}">
                            <div class="col-2 text-center pl-3">
                                <div class="round-shape">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-10 pl-3">
                                <h4 class="mt-0 mb-1"><b>{{$user_address->address}}</b></h4>
                                <div class="current-location ellips mb-2">{{$user_address->city}}, {{$user_address->state}}, {{$user_address->country}}</div>
                                <hr class="m-0">
                            </div>
                        </a>
                    @empty
                    @endforelse
                </div>
                <div class="scheduled-ride-list"> 
                    <div class="scheduled-ride-list-heading d-flex align-items-center justify-content-between"> 
                        <h3>Choose Date And Time</h3>
                        <span class="skip-clear">
                            Skip
                        </span>
                    </div>

                    <div class="date-radio-list style-4">

                    </div>

                    <div class="scheduled-footer">

                    </div>
                </div>
                <div class="table-responsive style-4">
                    <div class="cab-button d-flex flex-nowrap align-items-center py-2" id="vendor_main_div"></div>
                </div>
                <div class="vehical-container style-4" id="search_product_main_div"></div>
            </div>
        </div>
        <script type="text/template" id="vendors_template">
            <% _.each(results, function(result, key){%>
                <a class="btn btn-solid ml-2 vendor-list" href="javascript:void(0);" data-vendor="<%= result.id %>"><%= result.name %></a>
            <% }); %>
        </script>
        <script type="text/template" id="products_template">
            <% _.each(results, function(result, key){%>
                <a class="vehical-view-box row align-items-center no-gutters px-2 my-2" href="javascript:void(0)" data-product_id="<%= result.id %>">
                    <div class="col-3 vehicle-icon">
                        <img class='img-fluid' src='<%= result.image_url %>'>
                    </div>
                    <div class="col-9">
                        <div class="row no-gutters">
                            <div class="col-8 vehicle-details">
                                <h4 class="m-0"><b><%= result.name %></b></h4>
                            </div>
                            <div class="col-4 ride-price pl-2 text-right">
                                <p class="mb-0"><b>{{Session::get('currencySymbol')}}<%= result.tags_price%></b></p>
                            </div>
                        </div> 
                    </div>
                </a>
                <hr class="m-0">
            <% }); %>
        </script>
        <script type="text/template" id="scheduleTime_template">
            <div class="scheduleTime">
                <select class="scheduleHour" onchange="checkScheduleDateTime(this)" ><option value="">HH</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select>
                <select class="scheduleMinute" onchange="checkScheduleDateTime(this)" ><option value="">MM</option><option value="0">00</option><option value="1">05</option><option value="2">10</option><option value="3">15</option><option value="4">20</option><option value="5">25</option><option value="6">30</option><option value="7">35</option><option value="8">40</option><option value="9">45</option><option value="10">50</option><option value="11">55</option></select>
                <select class="scheduleAmPm" onchange="checkScheduleDateTime(this)" ><option value="">AM/PM</option><option value="am">AM</option><option value="pm">PM</option></select>
            </div>
        </script>
        <script type="text/template" id="destination_location_template">
            <i class="fa fa-search destination-icon" aria-hidden="true"></i> 
            <input class="form-control pickup-text" type="text" name="destination_location_name[]" placeholder="{{__('Add A Stop')}}" id="destination_location_<%= random_id %>" data-rel="<%= random_id %>"/>
            <input type="hidden" name="destination_location_latitude[]" value="" id="destination_location_latitude_<%= random_id %>" data-rel="<%= random_id %>"/>
            <input type="hidden" name="destination_location_longitude[]" value="" id="destination_location_longitude_<%= random_id %>" data-rel="<%= random_id %>"/>
        </script>
        <script type="text/template" id="destination_location_template_li">
            <li class="d-flex dots" id="dots_<%= random_id %>">
                <div class="title title-24 position-relative edit-other-stop" id="<%= random_id %>">  {{__('To')}} - <span id="dropoff-where-to-<%= random_id %>"></span><i class="fa fa-angle-down" aria-hidden="true"></i></div>
                <i class="fa fa-times ml-1 apremove" aria-hidden="true" data-rel="<%= random_id %>"></i>
            </li>
        </script>
        <script type="text/template" id="cab_detail_box_template">
            <div class="cab-outer style-4">
                <div class="bg-white p-2">
                    <a class="close-cab-detail-box" href="javascript:void()">✕</a>
                    <div class="cab-image-box w-100 d-flex align-items-center justify-content-center">
                        <img src="<%= result.image_url %>">
                    </div>
                    <div class="cab-location-details">
                        <h4 class="d-flex align-items-center justify-content-between"><b><%= result.name %></b> <label><sub class="ling-throgh" id
                        ="discount_amount" style="display:none;"></sub> <b id="real_amount">{{Session::get('currencySymbol')}}<%= result.tags_price%></b></label></h4>
                        <p><%= result.description %></p>
                    </div>
                </div>
                <div class="cab-amount-details px-2">
                    <div class="row">
                        <div class="col-6 mb-2">{{__('Distance')}}</div>
                        <div class="col-6 mb-2 text-right" id="distance"></div>
                        <div class="col-6 mb-2">{{__('Duration')}}</div>
                        <div class="col-6 mb-2 text-right" id="duration"></div>
                        <% if(result.loyalty_amount_saved) { %>
                            <div class="col-6 mb-2">Loyalty</div>
                            <div class="col-6 mb-2 text-right">-{{Session::get('currencySymbol')}}<%= result.loyalty_amount_saved %></div>
                        <% } %>
                    </div>
                </div>
                <div class="coupon_box d-flex w-100 py-2 align-items-center justify-content-between">
                    <label class="mb-0 ml-1">   
                        <img src="{{asset('assets/images/discount_icon.svg')}}">
                        <span class="code-text">{{__('Select a promo code')}}</span>
                    </label>
                    <a href="javascript:void(0)" class="ml-1" data-product_id="<%= result.id %>"  data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.tags_price%>" id="promo_code_list_btn_cab_booking">Apply</a>
                    <a class="remove-coupon" href="javascript:void(0)" id="remove_promo_code_cab_booking_btn" data-product_id="<%= result.id %>" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.tags_price%>" style="display:none;">Remove</a>
                </div>
                <div class="form-group pmd-textfield pmd-textfield-floating-label" style="display:none;" id="schedule_datetime_main_div">
                    <label class="control-label" for="datetimepicker-default">Select Date and Time</label>
                    <input type="datetime-local" id="schedule_datetime" class="form-control" placeholder="Inline calendar" value="">
                </div>
            </div>
            <div class="payment-promo-container p-2">
                <h4 class="d-flex align-items-center justify-content-between mb-2"  data-toggle="modal" data-target="#payment_modal">
                    <span>
                        <i class="fa fa-money" aria-hidden="true"></i> Cash
                    </span>
                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                </h4>
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-solid w-100" id="pickup_now" data-product_id="<%= result.id %>" data-coupon_id ="" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.original_tags_price%>" data-image="<%= result.image_url %>" data-rel="pickup_now" data-task_type="now">Pickup Now</button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-solid w-100" id="pickup_later" data-product_id="<%= result.id %>" data-coupon_id ="" data-vendor_id="<%= result.vendor_id %>" data-amount="<%= result.original_tags_price%>" data-image="<%= result.image_url %>" data-rel="pickup_later">Pickup Later</button>
                    </div>
                </div>
            </div>
        </script>

        <script type="text/template" id="cab_booking_promo_code_template">
            <% _.each(promo_codes, function(promo_code, key){%>
                <div class="col-12 mt-2">
                    <div class="coupon-code mt-0">
                        <div class="p-2">
                            <img src="<%= promo_code.image.image_fit %>100/35<%= promo_code.image.image_path %>" alt="">
                            <h6 class="mt-0"><%= promo_code.title %></h6>
                        </div>
                        <hr class="m-0">
                        <div class="code-outer p-2 text-uppercase d-flex align-items-center justify-content-between">
                            <label class="m-0"><%= promo_code.name %></label>
                            <a class="btn btn-solid cab_booking_apply_promo_code_btn" data-vendor_id="<%= vendor_id %>" data-coupon_id="<%= promo_code.id %>" data-product_id="<%= product_id %>" data-amount="<%= amount %>" style="cursor: pointer;">Apply</a>
                        </div>
                        <hr class="m-0">
                        <div class="offer-text p-2">
                            <p class="m-0"><%= promo_code.short_desc %></p>
                        </div>
                    </div>
                </div>
            <% }); %>
        </script>

        <script type="text/template" id="order_success_template">
            <div class="bg-white p-2">
                <div class="w-100 h-100">
                    <img src="<%= product_image %>" alt="">
                </div>
                <div class="cab-location-details" id="searching_main_div">
                    <h4><b>Searching For Nearby Drivers</b></h4>
                    <div class="new-loader"></div>
                </div>
                <div class="cab-location-details" id="driver_details_main_div" style="display:none;">
                   <div class="row align-items-center">

                        <div class="col-4">
                           <div class="taxi-img">
                               <img src="" id="driver_image">
                           </div>
                        </div>

                        <div class="col-8" >
                            <h4 id="driver_name"><b><%= result.user_name %></b></h4>
                            <p class="mb-0" id="driver_phone_number"><%= result.phone_number %></p>
                        </div>

                   </div>
                </div>
            </div>
            <div class="cab-amount-details px-2">
                <div class="row">
                    <div class="col-6 mb-2">ETA</div>
                    <div class="col-6 mb-2 text-right" id="distance">--</div>
                    <div class="col-6 mb-2">Order ID</div>
                    <div class="col-6 mb-2 text-right" id=""><%= result.order_number %></div>
                    <div class="col-6 mb-2">Amount Paid</div>
                    <div class="col-6 mb-2 text-right">$<%= result.total_amount %></div>
                </div>
            </div>
        </script>

        <div class="cab-detail-box style-4 d-none" id="cab_detail_box"></div>
            <div class="promo-box style-4 d-none">
                <a class="d-block mt-2 close-promo-code-detail-box" href="javascript:void(0)">✕</a>
                <div class="row" id="cab_booking_promo_code_list_main_div">
                    
                </div>    
            </div>
        </div>


   
</section>

<!-- Payment Modal -->
<div class="modal fade payment-modal payment-modal-width" id="payment_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="payment_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header pb-0">
                <h5 class="modal-title" id="payment_modalLabel">Select Payment Method</h5>
                <button type="button" class="close right-top" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <h4 class="d-flex align-items-center justify-content-between mb-2 mt-3 px-3 select_cab_payment_method"><span><i class="fa fa-money mr-3" aria-hidden="true"></i> Cash</span></h4>
                {{-- <h4 class="payment-button"  data-toggle="modal" data-target="#select_payment_option" aria-label="Close">Select Payment Method</h4> --}}
            </div>        
        </div>
    </div>
</div>

<!-- Select Payment Option -->
<div class="modal fade select-payment-option payment-modal-width" id="select_payment_option" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="select_payment_optionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="select_payment_optionLabel">Choose payment method</h5>
                <button type="button" class="close right-top" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 class="d-flex  justify-content-between mb-2 mt-3 select_cab_payment_methodx"><span ><i class="fa fa-money mr-3" aria-hidden="true"></i> Cash</span></h4>
            </div>        
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('js/cab_booking.js')}}"></script>
<script>
var category_id = "{{ $category->id??'' }}";

var routeset = "{{route('pickup-delivery-route',':category_id')}}";

var autocomplete_urls = routeset.replace(":category_id", category_id);

var get_product_detail = "{{url('looking/product-detail')}}";
var promo_code_list_url = "{{route('verify.promocode.list')}}";
var get_vehicle_list = "{{url('looking/get-list-of-vehicles')}}";
var cab_booking_create_order = "{{url('looking/create-order')}}";
var live_location = "{{ URL::asset('/images/live_location.gif') }}";
var no_coupon_available_message = "{{__('No Other Coupons Available.')}}";
var order_tracking_details_url = "{{url('looking/order-tracking-details')}}";
var cab_booking_promo_code_remove_url = "{{url('looking/promo-code/remove')}}";
var apply_cab_booking_promocode_coupon_url = "{{ route('verify.cab.booking.promo-code') }}";

</script>
@endsection