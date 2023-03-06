@extends('layouts.store', ['title' => $vendor->name])
@section('css')
<style type="text/css">
    .main-menu .brand-logo{display:inline-block;padding-top:20px;padding-bottom:20px}.productVariants .firstChild{min-width:150px;text-align:left!important;border-radius:0!important;margin-right:10px;cursor:default;border:none!important}.product-right .color-variant li,.productVariants .otherChild{height:35px;width:35px;border-radius:50%;margin-right:10px;cursor:pointer;border:1px solid #f7f7f7;text-align:center}.productVariants .otherSize{height:auto!important;width:auto!important;border:none!important;border-radius:0}.product-right .size-box ul li.active{background-color:inherit}.product-box .product-detail h4,.product-box .product-info h4{font-size:16px}select.changeVariant{color:#343a40;border:1px solid #bbb;border-radius:5px;font-size:14px}.counter-container{border:1px solid var(--theme-deafult);border-radius:5px;padding:2px}.switch{opacity:0;position:absolute;z-index:1;width:18px;height:18px;cursor:pointer}.switch+.lable{position:relative;display:inline-block;margin:0;line-height:20px;min-height:18px;min-width:18px;font-weight:400;cursor:pointer}.switch+.lable::before{cursor:pointer;font-family:fontAwesome;font-weight:400;font-size:12px;color:#32a3ce;content:"\a0";background-color:#fafafa;border:1px solid #c8c8c8;box-shadow:0 1px 2px rgba(0,0,0,.05);border-radius:0;display:inline-block;text-align:center;height:16px;line-height:14px;min-width:16px;margin-right:1px;position:relative;top:-1px}.switch:checked+.lable::before{display:inline-block;content:'\f00c';background-color:#f5f8fc;border-color:#adb8c0;box-shadow:0 1px 2px rgba(0,0,0,.05),inset 0 -15px 10px -12px rgba(0,0,0,.05),inset 15px 10px -12px rgba(255,255,255,.1)}.switch+.lable{margin:0 4px;min-height:24px}.switch+.lable::before{font-weight:400;font-size:11px;line-height:17px;height:20px;overflow:hidden;border-radius:12px;background-color:#f5f5f5;-webkit-box-shadow:inset 0 1px 1px 0 rgba(0,0,0,.15);box-shadow:inset 0 1px 1px 0 rgba(0,0,0,.15);border:1px solid #ccc;text-align:left;float:left;padding:0;width:52px;text-indent:-21px;margin-right:0;-webkit-transition:text-indent .3s ease;-o-transition:text-indent .3s ease;transition:text-indent .3s ease;top:auto}.switch.switch-bootstrap+.lable::before{font-family:FontAwesome;content:"\f00d";box-shadow:none;border-width:0;font-size:16px;background-color:#a9a9a9;color:#f2f2f2;width:52px;height:22px;line-height:21px;text-indent:32px;-webkit-transition:background .1s ease;-o-transition:background .1s ease;transition:background .1s ease}.switch.switch-bootstrap+.lable::after{content:'';position:absolute;top:2px;left:3px;border-radius:12px;box-shadow:0 -1px 0 rgba(0,0,0,.25);width:18px;height:18px;text-align:center;background-color:#f2f2f2;border:4px solid #f2f2f2;-webkit-transition:left .2s ease;-o-transition:left .2s ease;transition:left .2s ease}.switch.switch-bootstrap:checked+.lable::before{content:"\f00c";text-indent:6px;color:#fff;border-color:#b7d3e5}.switch-primary>.switch.switch-bootstrap:checked+.lable::before{background-color:#337ab7}.switch-success>.switch.switch-bootstrap:checked+.lable::before{background-color:#5cb85c}.switch-danger>.switch.switch-bootstrap:checked+.lable::before{background-color:#d9534f}.switch-info>.switch.switch-bootstrap:checked+.lable::before{background-color:#5bc0de}.switch-warning>.switch.switch-bootstrap:checked+.lable::before{background-color:#f0ad4e}.switch.switch-bootstrap:checked+.lable::after{left:32px;background-color:#fff;border:4px solid #fff;text-shadow:0 -1px 0 rgba(0,200,0,.25)}.switch-square{opacity:0;position:absolute;z-index:1;width:18px;height:18px;cursor:pointer}.switch-square+.lable{position:relative;display:inline-block;margin:0;line-height:20px;min-height:18px;min-width:18px;font-weight:400;cursor:pointer}.switch-square+.lable::before{cursor:pointer;font-family:fontAwesome;font-weight:400;font-size:12px;color:#32a3ce;content:"\a0";background-color:#fafafa;border:1px solid #c8c8c8;box-shadow:0 1px 2px rgba(0,0,0,.05);border-radius:0;display:inline-block;text-align:center;height:16px;line-height:14px;min-width:16px;margin-right:1px;position:relative;top:-1px}.switch-square:checked+.lable::before{display:inline-block;background-color:#f5f8fc;border-color:#adb8c0;box-shadow:0 1px 2px rgba(0,0,0,.05),inset 0 -15px 10px -12px rgba(0,0,0,.05),inset 15px 10px -12px rgba(255,255,255,.1)}.switch-square+.lable{margin:0 4px;min-height:24px}.switch.switch-bootstrap+.lable::before,.switch.switch-bootstrap:checked+.lable::before{content:"";width:40px;height:18px;line-height:21px}.switch.switch-bootstrap+.lable::after{width:14px;height:14px}.switch+.lable{line-height:14px}.switch.switch-bootstrap:checked+.lable::after{left:23px}.switch-square+.lable::before{font-weight:400;font-size:11px;line-height:17px;height:20px;overflow:hidden;border-radius:2px;background-color:#f5f5f5;-webkit-box-shadow:inset 0 1px 1px 0 rgba(0,0,0,.15);box-shadow:inset 0 1px 1px 0 rgba(0,0,0,.15);border:1px solid #ccc;text-align:left;float:left;padding:0;width:52px;text-indent:-21px;margin-right:0;-webkit-transition:text-indent .3s ease;-o-transition:text-indent .3s ease;transition:text-indent .3s ease;top:auto}.switch-square.switch-bootstrap+.lable::before{font-family:FontAwesome;box-shadow:none;border-width:0;font-size:16px;background-color:#a9a9a9;color:#f2f2f2;width:52px;height:22px;line-height:21px;text-indent:32px;-webkit-transition:background .1s ease;-o-transition:background .1s ease;transition:background .1s ease}.switch-square.switch-bootstrap+.lable::after{content:'';position:absolute;top:2px;left:3px;border-radius:12px;box-shadow:0 -1px 0 rgba(0,0,0,.25);width:18px;height:18px;text-align:center;background-color:#f2f2f2;border:4px solid #f2f2f2;-webkit-transition:left .2s ease;-o-transition:left .2s ease;transition:left .2s ease}.switch-square.switch-bootstrap:checked+.lable::before{text-indent:6px;color:#fff;border-color:#b7d3e5}.switch-primary>.switch-square.switch-bootstrap:checked+.lable::before{background-color:#337ab7}.switch-success>.switch-square.switch-bootstrap:checked+.lable::before{background-color:#5cb85c}.switch-danger>.switch-square.switch-bootstrap:checked+.lable::before{background-color:#d9534f}.switch-info>.switch-square.switch-bootstrap:checked+.lable::before{background-color:#5bc0de}.switch-warning>.switch-square.switch-bootstrap:checked+.lable::before{background-color:#f0ad4e}.switch-square.switch-bootstrap:checked+.lable::after{left:32px;background-color:#fff;border:4px solid #fff;text-shadow:0 -1px 0 rgba(0,200,0,.25)}.switch-square.switch-bootstrap+.lable::after{border-radius:2px}
    .profile_address ul.vendor-info li.d-block.vendor-location a{position: absolute;right: 0px;top:0px;padding:0px 6px;
    border-radius: 4px;border: 1px dotted#938a8a;background-color: #f8f1f8;}.social-icon-list {
    width: 100%;max-width: 90%;}.social-icon-list .modal-body {text-align: center;}.social-icon-list .modal-body .text-center a img {width: 40px;}
.social-icon-list .modal-body .text-center {display: inline-block;margin: 0px 6px;}.profile_address ul.vendor-info li.d-block.vendor-location a span {font-size: 13px;}.profile_address ul.vendor-info li.d-block.vendor-location a img {width: 12px;}
/* .vendor-description .vendor-details-left .vender-icon .vendor-stories a img {width: 110px;height: 110px;} */
.vendor-description .profile_address h3 {font-size: 24px;text-transform: capitalize;margin: 10px 0px 6px 0px;}
.vendor-description .profile_address h4 {font-size: 16px; margin: 0px;color: #6c757d;}
/* .vendor-description .profile_address ul.vendor-info li {padding: 2px 8px 3px 0px;} */
.al_body_template_two .vendor-description .vendor-reviwes{padding: 0;position: absolute;right: auto;left:0px;top:5px;}
.al_body_template_six.homeHeader .product-bottom-bar{padding: 20px 10px;}
.vendor-description .vendor-info .d-block.vendor-location{padding-left:0px;}
.al_body_template_six.homeHeader .vendor-stories{background: transparent;}
.vendor-description .vendor-details-left .vendor-location a {position: inherit !important;}
.al_body_template_six .vendor-description .vendor-info .vendor-location{margin-bottom:10px;}
.line_diff_between_products{border-top: 1px dotted rgb(61, 60, 60)}
span.alPriceValue, span.alPriceValue i {
    display: inline-flex;
    align-items: baseline;
}
</style>
@endsection
@section('css-links')
<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/price-range.css') }}">
@endsection
@php
$add_to_cart =  route('addToCart') ;
$is_service_product_price_from_dispatch_forOnDemand = 0;
$additionalPreference = getAdditionalPreference(['is_service_product_price_from_dispatch']);
$category_type_idForNotShowshPlusMinus = ['12'];
if(($additionalPreference['is_service_product_price_from_dispatch'] == 1) && ( Session::get('vendorType') == 'on_demand')){
    $is_service_product_price_from_dispatch_forOnDemand =1;
    array_push($category_type_idForNotShowshPlusMinus,8);
}

@endphp
@section('content')
    <!-- section start -->
    <section class="section-b-space ratio_asos alProductCategories">
        <div class="collection-wrapper">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-12">
                        <div class="product-banner-img">
                            @if (!empty($vendor->banner))
                            <link rel="preload" as="image" href="{{ $vendor->banner['image_fit'] . '1920/1080' . $vendor->banner['image_path'] }}" />
                            <img alt="" src="{{ $vendor->banner['image_fit'] . '1920/1080' . $vendor->banner['image_path'] }}">
                            @endif
                        </div>
                        </div>
                </div>
                    {{-- <div class="row">
                        <div class="col-12"> --}}
                        <div class="vendor-design_new">
                            <div class="container">
                            <div class="row align-items-center">
                        <div class="col-12 product-bottom-bar vendor-description pb-2">
                            <div class="row vendor-details-left align-items-center">
                                <div class="vender-icon mr-sm-3">
                                    <div class="vendor-stories">
                                        <div class="circle-wrapper"></div>
                                        <a href="" data-toggle="modal" data-target="#vendorStories_">
                                            <img id="vendorStoriesImg" src="{{ $vendor->logo['image_fit'] . '120/120' . $vendor->logo['image_path'] }}" class="rounded-circle avatar-sm avatar-lg" alt="profile-image">
                                        </a>
                                    </div>
                                   
                                    <!-- <img src="{{ $vendor->logo['image_fit'] . '120/120' . $vendor->logo['image_path'] }}" class="rounded-circle avatar-lg" alt="profile-image"> -->
                                </div>
                                <div class="ml-sm-1 position-relative profile_address vendor_icon-design">
                                            <h3>{{ $vendor->name }}</h3>
                                            <div class="vendor-reviwes">
                                        @if ($vendor->vendorRating > 0)
                                            <div class="rating-text-box ml-sm-auto p-1">
                                                <span>{{ $vendor->vendorRating }}</span>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                             
                                            </div>
                                            
                                        @endif
                                        
                                    
                                        {{-- <div class="review-text">
                                                <div class="reviw-number">409</div>
                                                <div class="reviews-text">Delivery Reviews</div>
                                            </div> --}}
                                    </div>
                                    <ul class="vendor-info customize_vendor"> <li class="d-block vendor-location">
                                                        <a href="javascript:void(0)" onclick="copyToClipboard('#p1')" >
                                                            <img src="{{ asset('assets/icons/domain_copy_icon.svg')}}" alt="">
                                                            <span class="copied_txt" id="show_copy_msg_on_click_copy">{{ __('Copy') }}</span>
                                                            <span class="copied_txt" id="show_copy_msg_on_click_copied" style="display:none;">{{ __('Copied') }}</span>
                                                        </a>
                                                        <span id="p1" style="display:none;">{{url()->current()}}</span>
                                                    </li>
                                              </ul>
                                            @if (!empty($vendor->desc))
                                                <h4 title="{{ $vendor->desc }}" style="line-height: 24px">
                                                <?xml version="1.0" encoding="UTF-8"?>
<svg width="29px" height="40px" viewBox="0 0 29 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <title>location</title>
    <g id="design-update" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="location" fill="#FFFFFF" fill-rule="nonzero">
            <g id="Group" transform="translate(14.444444, 20.000000) scale(-1, 1) rotate(-180.000000) translate(-14.444444, -20.000000) ">
                <path d="M12.1881488,39.8490462 C8.99166591,39.3022058 6.44847876,37.9897886 4.21638484,35.7477427 C1.61875637,33.1463445 0.218836839,29.9199857 0.0166262395,26.0999144 C-0.138920375,23.217284 0.794359313,19.8424971 2.92534794,15.6005774 C4.75302066,11.9679943 7.08621988,8.3744712 10.2515935,4.3044157 C11.9159423,2.16392586 13.3936351,0.414036356 13.7358377,0.187488161 C14.1013722,-0.0624960537 14.7857773,-0.0624960537 15.1513119,0.187488161 C15.6801703,0.539028463 18.9388719,4.58564794 20.7820993,7.15579815 C24.9429713,12.9835552 27.735033,18.4988319 28.5516527,22.5298274 C28.839414,23.9438006 28.9327419,24.9827975 28.8705233,26.0999144 C28.6683127,29.9199857 27.2683931,33.1463445 24.6707647,35.7477427 C22.3997841,38.0366607 19.7477143,39.3725138 16.4967901,39.8802943 C15.4079638,40.0521584 13.2691978,40.0365344 12.1881488,39.8490462 Z M16.7378873,37.1148439 C20.1132489,36.4039513 23.0064159,34.2947095 24.701874,31.2948989 C25.6818177,29.5606334 26.1951215,27.5998197 26.1951215,25.5296379 C26.1951215,21.6314466 23.807481,16.2255379 19.1644145,9.57752023 C17.4922884,7.19485818 14.6224534,3.48415499 14.4435748,3.48415499 C14.2646961,3.48415499 11.3948611,7.19485818 9.722735,9.57752023 C7.8250663,12.2960986 6.55736139,14.3740924 5.34409779,16.7645664 C2.8397973,21.7017547 2.17094685,25.1156016 3.05756256,28.4825765 C3.61753037,30.6152543 4.62858337,32.3573318 6.19960418,33.9119211 C8.07394088,35.7633667 10.3915854,36.9039197 13.0436552,37.271084 C13.8680523,37.3882641 15.8512716,37.3023321 16.7378873,37.1148439 Z" id="Shape"></path>
                <path d="M13.1214285,32.7323081 C11.5115211,32.4432639 9.8238403,31.435515 8.78167798,30.1309099 C8.17504618,29.3731452 7.57619172,28.1622842 7.35842646,27.2326554 C7.14066119,26.3342746 7.12510653,24.7796853 7.3195398,23.9516126 C7.98839024,21.1471022 9.98716424,19.1003564 12.732562,18.3972758 C13.5491817,18.1863516 15.3379678,18.1863516 16.1545875,18.3972758 C18.8999853,19.1003564 20.8987593,21.1471022 21.5676097,23.9516126 C21.762043,24.7796853 21.7464883,26.3342746 21.5287231,27.2326554 C21.3031805,28.1622842 20.7121033,29.3731452 20.1054715,30.1309099 C19.2188558,31.2402148 17.7800496,32.1932797 16.3879074,32.58388 C15.719057,32.7791802 13.8447203,32.8651122 13.1214285,32.7323081 Z M15.2679718,30.0762258 C16.6990007,29.8106176 17.9589282,28.8341168 18.5966694,27.4748276 C18.9466493,26.740499 19.0633092,26.1155384 19.0088679,25.2171577 C18.9388719,23.9984846 18.4800094,23.0141718 17.5700617,22.139227 C16.8156606,21.4205224 15.4701824,20.8658699 14.4435748,20.8658699 C13.4169671,20.8658699 12.0714889,21.4205224 11.3170878,22.139227 C10.4071401,23.0141718 9.94827759,23.9984846 9.87828161,25.2171577 C9.8238403,26.1155384 9.94050026,26.740499 10.2904801,27.4748276 C11.2004278,29.3965813 13.2303112,30.4512022 15.2679718,30.0762258 Z" id="Shape"></path>
            </g>
        </g>
    </g>
</svg> {{ substr($vendor->desc, 0, 80) . '...' }}</h4>
                                            @endif
                                            <p>{!! $vendor->short_desc !!}</p>
                                            <ul class="vendor-info">

                                                    <!-- <li class="d-block vendor-location">
                                                        <a href="javascript:void(0)" onclick="copyToClipboard('#p1')" >
                                                            <img src="{{ asset('assets/icons/domain_copy_icon.svg')}}" alt="">
                                                            <span class="copied_txt" id="show_copy_msg_on_click_copy">{{ __('Copy') }}</span>
                                                            <span class="copied_txt" id="show_copy_msg_on_click_copied" style="display:none;">{{ __('Copied') }}</span>
                                                        </a>
                                                        <span id="p1" style="display:none;">{{url()->current()}}</span>
                                                    </li> -->

                                                {{-- <li class="d-block food-items">
                                                        <i class="icon-ic_eat"></i>
                                                        @forelse($listData as $key => $data)
                                                            {{ $data->category->translation_one->name . (( $key !=  count($listData)-1 ) ? ',' : '') }}
                                                        @empty
                                                        @endforelse
                                                </li> --}}

                                                @if ($vendor->is_show_vendor_details == 1)
                                                    <li class="d-block vendor-location">
                                                        <i class="icon-location"></i> {{ $vendor->address }}
                                                    </li>
                                                    @if ($vendor->email)
                                                        <li class="d-block vendor-email">
                                                            <i class="fa fa-envelope"></i> {{ $vendor->email }}
                                                        </li>
                                                    @endif
                                                    @if ($vendor->website)
                                                        <li class="d-block vendor-website">
                                                            <i class="fa fa-home"></i> {{ $vendor->website }}
                                                        </li>
                                                    @endif
                                                @endif
                                                
                                                @if(isset($socialMediaUrls) && count($socialMediaUrls)>0)
                                                    <li class="d-block vendor-instagram">
                                                    <?xml version="1.0" encoding="UTF-8"?>
<svg width="40px" height="40px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <title>globe</title>
    <g id="design-update" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="globe" fill="#FFFFFF" fill-rule="nonzero">
            <g id="Group" transform="translate(20.000000, 20.000000) scale(-1, 1) rotate(-180.000000) translate(-20.000000, -20.000000) ">
                <path d="M17.4989013,39.8909971 C10.2329215,38.9923312 4.05293227,34.1786078 1.32623663,27.3018602 C0.755896284,25.8796238 0.248058987,23.7853416 0.0761755945,22.1755575 C-0.0253918648,21.1987467 -0.0253918648,18.807514 0.0761755945,17.8307033 C0.857463743,10.4538286 5.71707603,4.10065149 12.7017921,1.32650899 C14.1237365,0.756051514 16.2175887,0.248109928 17.8270423,0.0761912378 C18.8036525,-0.0253970793 21.1943943,-0.0253970793 22.1710044,0.0761912378 C30.108892,0.920155718 36.8514088,6.50751316 39.1718346,14.1735239 C39.7499878,16.0724439 40,17.8307033 40,20.0031304 C40,21.6363579 39.9218712,22.5350238 39.6406075,23.9494458 C38.3280434,30.6073878 33.6950046,36.1400438 27.2962547,38.6797517 C25.8743103,39.2502092 23.780458,39.7581508 22.1710044,39.9300695 C21.147517,40.0394723 18.506763,40.0160288 17.4989013,39.8909971 Z M18.8270912,33.5690779 L18.8270912,29.5524322 L18.2254993,29.5836901 C16.8035549,29.6774639 14.4753162,29.9822289 13.3580741,30.2322924 C13.0299331,30.3026228 13.0143073,30.1854055 13.5221446,31.9905518 C13.9206016,33.4206027 14.553445,35.2726359 15.014405,36.3822929 L15.280043,36.9996373 L16.0535182,37.171556 C16.7644905,37.3278457 18.2176864,37.5544658 18.6552078,37.5779092 L18.8270912,37.5857237 L18.8270912,33.5690779 Z M23.0069828,37.3434746 C23.5226329,37.2575153 24.1164119,37.1481125 24.3351726,37.0934111 L24.7258167,36.9918228 L24.9836418,36.3744784 C25.4446018,35.2804504 26.0774452,33.4206027 26.4759021,31.9905518 C26.9837394,30.1854055 26.9681137,30.3026228 26.6399727,30.2322924 C25.5227306,29.9822289 23.1944919,29.6774639 21.7803604,29.5836901 L21.1709556,29.5524322 L21.1709556,33.5690779 L21.1709556,37.5935382 L21.6241027,37.5466513 C21.8663021,37.5232078 22.4913326,37.429434 23.0069828,37.3434746 Z M11.7876849,34.6396625 C11.5767371,33.9988747 11.256409,32.9361046 11.0767127,32.2718733 C10.8970165,31.607642 10.7329459,31.0371845 10.7016944,31.0059266 C10.6391914,30.9199672 7.56872894,31.9905518 7.34996826,32.170285 C7.22496216,32.2640588 9.13130524,33.9051008 10.1938571,34.6162191 C10.6704429,34.936613 12.0611358,35.757134 12.1627033,35.7883919 C12.1705161,35.7883919 11.9986327,35.2726359 11.7876849,34.6396625 Z M29.7260608,34.663106 C30.8823673,33.8972864 32.7808975,32.2796878 32.6480785,32.170285 C32.4293178,31.9905518 29.3588554,30.9199672 29.2963524,31.0059266 C29.2651008,31.0371845 29.1010303,31.607642 28.921334,32.2718733 C28.7416378,32.9361046 28.4213096,33.9988747 28.2103618,34.631848 L27.8275306,35.7883919 L28.4213096,35.4601835 C28.7494507,35.2804504 29.3432296,34.920984 29.7260608,34.663106 Z M6.72493774,29.7790523 C7.22496216,29.5680611 8.1781337,29.2085948 8.83441574,28.9819747 C9.49851067,28.7553546 10.068851,28.5443635 10.1079154,28.5131055 C10.1469798,28.4818476 10.1079154,27.989535 10.0141608,27.3721906 C9.70164559,25.3404243 9.45163338,22.8632322 9.45163338,21.7066883 L9.45163338,21.1753033 L5.93583671,21.1753033 L2.41222716,21.1753033 L2.45910445,21.6676159 C2.67786513,23.7618981 3.26383124,25.8405514 4.17793838,27.700399 C4.72484008,28.825685 5.62332145,30.2557359 5.74051467,30.208849 C5.78739196,30.1854055 6.22491333,29.9978579 6.72493774,29.7790523 Z M34.8278725,29.4742873 C36.2732555,27.2549733 37.2576786,24.4339439 37.5389423,21.6676159 L37.5858196,21.1753033 L34.0622101,21.1753033 L30.5464134,21.1753033 L30.5464134,21.7066883 C30.5464134,22.8632322 30.2964012,25.3404243 29.9838859,27.3721906 C29.8901314,27.989535 29.8510669,28.4818476 29.8901314,28.5131055 C29.9291958,28.5443635 30.507349,28.7631691 31.1714439,28.9897892 C31.8355388,29.2085948 32.8043361,29.5836901 33.3199863,29.8103102 C33.8356365,30.0369303 34.2731579,30.224478 34.2965965,30.2322924 C34.3200352,30.2322924 34.5622345,29.8962696 34.8278725,29.4742873 Z M13.2565067,27.7863584 C14.5612579,27.5050369 17.06138,27.1924574 17.9286098,27.1924574 C18.1551834,27.1924574 18.4520729,27.169014 18.5848918,27.1455705 L18.8270912,27.0986836 L18.8270912,24.1369934 L18.8270912,21.1753033 L15.3503589,21.1753033 L11.8736266,21.1753033 L11.8736266,21.6676159 C11.8736266,22.2458878 12.0689487,24.629306 12.1861419,25.5123429 C12.2955222,26.3719364 12.4986572,27.6769555 12.5377216,27.8019873 C12.576786,27.9192046 12.6080375,27.9192046 13.2565067,27.7863584 Z M27.5540798,27.2706023 C27.8275306,25.6686327 28.1244201,22.7460149 28.1244201,21.6676159 L28.1244201,21.1753033 L24.6476879,21.1753033 L21.1709556,21.1753033 L21.1709556,24.1369934 L21.1709556,27.0986836 L21.4131549,27.1455705 C21.5459739,27.169014 21.8428634,27.1924574 22.069437,27.1924574 C22.9522926,27.1924574 25.9133747,27.5753672 26.8743591,27.8176163 C27.405635,27.9504626 27.4446994,27.9192046 27.5540798,27.2706023 Z M9.45163338,18.3073869 C9.45163338,17.1430285 9.70164559,14.6658365 10.0141608,12.6340701 C10.1079154,12.0167257 10.1469798,11.5244131 10.1079154,11.4931552 C10.068851,11.4618972 9.49069779,11.2430916 8.82660286,11.0164715 C8.16250793,10.7898515 7.17808487,10.4225706 6.63899604,10.188136 C6.1077201,9.96151594 5.66238586,9.78178277 5.65457298,9.78959725 C5.38893501,10.1178057 4.54514381,11.5478566 4.17012549,12.3058617 C3.26383124,14.1735239 2.67786513,16.2443626 2.45910445,18.3464593 L2.41222716,18.8309575 L5.93583671,18.8309575 L9.45163338,18.8309575 L9.45163338,18.3073869 Z M18.8270912,15.8692673 L18.8270912,12.9153916 L18.5848918,12.8606902 C18.4520729,12.8372468 18.1551834,12.8138033 17.9286098,12.8138033 C17.0535671,12.8138033 15.0065921,12.5559253 13.4440158,12.2511603 C12.9986816,12.165201 12.6080375,12.1261285 12.576786,12.165201 C12.3970897,12.3683776 11.8814395,16.9320374 11.8736266,18.3464593 L11.8736266,18.8309575 L15.3503589,18.8309575 L18.8270912,18.8309575 L18.8270912,15.8692673 Z M28.1244201,18.3464593 C28.1166073,16.9320374 27.6009571,12.3683776 27.4212608,12.165201 C27.3900093,12.1261285 26.9993652,12.165201 26.554031,12.2511603 C24.9914547,12.5559253 22.9444797,12.8138033 22.069437,12.8138033 C21.8428634,12.8138033 21.5459739,12.8372468 21.4131549,12.8606902 L21.1709556,12.9153916 L21.1709556,15.8692673 L21.1709556,18.8309575 L24.6476879,18.8309575 L28.1244201,18.8309575 L28.1244201,18.3464593 Z M37.5389423,18.3464593 C37.3201816,16.2443626 36.7342155,14.1735239 35.8279213,12.3058617 C35.452903,11.5478566 34.6091118,10.1178057 34.3434738,9.78959725 C34.3356609,9.78178277 33.8903267,9.96151594 33.3590507,10.188136 C32.8199619,10.4225706 31.8355388,10.7898515 31.1714439,11.0164715 C30.507349,11.2430916 29.9291958,11.4618972 29.8901314,11.4931552 C29.8510669,11.5244131 29.8901314,12.0167257 29.9838859,12.6340701 C30.2964012,14.6658365 30.5464134,17.1430285 30.5464134,18.3073869 L30.5464134,18.8309575 L34.0622101,18.8309575 L37.5858196,18.8309575 L37.5389423,18.3464593 Z M18.8270912,6.43718279 L18.8270912,2.41272253 L18.3817569,2.45960945 C17.6942234,2.53775431 16.1003955,2.80344683 15.6628742,2.91284963 L15.2722301,3.01443795 L15.014405,3.63178234 C14.420626,5.06183326 13.233068,8.75027062 13.1080619,9.57079165 C13.0846233,9.70363791 13.1783779,9.74271034 13.9674789,9.89118557 C15.0065921,10.0943622 16.6707359,10.3131678 17.7723522,10.3913127 C18.2020606,10.4225706 18.6161434,10.4538286 18.6942722,10.461643 C18.8192783,10.4694575 18.8270912,10.2272085 18.8270912,6.43718279 Z M23.4366912,10.2662809 C24.4054885,10.1646926 25.9680648,9.92244351 26.6399727,9.77396828 C26.9681137,9.70363791 26.9837394,9.8208552 26.4759021,8.01570894 C26.0774452,6.58565802 25.4446018,4.72581037 24.9836418,3.63178234 L24.7258167,3.01443795 L24.3351726,2.91284963 C23.8976513,2.80344683 22.3038234,2.53775431 21.6241027,2.45960945 L21.1709556,2.41272253 L21.1709556,6.43718279 L21.1709556,10.4538286 L21.7803604,10.4225706 C22.1085014,10.3991272 22.858538,10.3287968 23.4366912,10.2662809 Z M11.1001514,7.64842811 C11.3032863,6.90605194 11.6314273,5.8198384 11.8267494,5.25719541 L12.1705161,4.21786878 L11.5767371,4.54607719 C10.7173202,5.01494635 9.68601983,5.71043559 8.82660286,6.41373933 C8.07656624,7.02326923 7.28746521,7.78908885 7.34996826,7.83597577 C7.51403877,7.97663652 10.529811,9.06285006 10.6548171,9.03159212 C10.6938815,9.02377763 10.8970165,8.39861876 11.1001514,7.64842811 Z M30.2338981,8.75808511 C31.0542507,8.4845781 32.5699497,7.90630614 32.6480785,7.83597577 C32.7105816,7.78908885 32.0074222,7.10141409 31.2105083,6.45281176 C30.3276527,5.72606457 29.3119781,5.03057532 28.4213096,4.54607719 L27.8275306,4.21786878 L28.1712974,5.25719541 C28.3666195,5.8198384 28.6791347,6.86697951 28.8744568,7.57809773 C29.2651008,9.01596314 29.2807266,9.06285006 29.319791,9.06285006 C29.3276039,9.06285006 29.7416866,8.9300038 30.2338981,8.75808511 Z" id="Shape"></path>
            </g>
        </g>
    </g>
</svg><a class="open-social-medialinks" href="javascript:void(0)">Social Media Links</a>
                                                    </li>
                                                @endif


                                                @php
                                                    $checkSlot = findSlot('', $vendor->id, '');
                                                @endphp

                                                <li class="vendor-timing">
                                                <?xml version="1.0" encoding="UTF-8"?>
<svg width="40px" height="40px" viewBox="0 0 40 40" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <title>clock</title>
    <g id="design-update" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="clock" fill="#FFFFFF" fill-rule="nonzero">
            <g id="Group" transform="translate(20.000000, 20.000000) scale(-1, 1) rotate(-180.000000) translate(-20.000000, -20.000000) translate(0.000000, 0.000000)">
                <path d="M18.0039139,39.9761222 C17.1898239,39.8900174 15.7181996,39.6238752 14.888454,39.412527 C7.99217221,37.6278088 2.38747554,32.0309951 0.587084149,25.1191259 C0.0939334638,23.2013366 0.00782778865,22.4498763 5.15640758e-16,20.0311135 C5.15640758e-16,17.4010025 0.11741683,16.4773325 0.75146771,14.3012288 C2.66927593,7.65550176 8.18786693,2.30134716 14.888454,0.571422933 C16.81409,0.0782771141 17.5499022,0 20,0 C22.4500978,0 23.18591,0.0782771141 25.111546,0.571422933 C32.1722114,2.39527969 37.7847358,8.12516445 39.5146771,15.2953481 C39.9373777,17.0174446 40,17.6749724 40,20.0311135 C39.9921722,22.4498763 39.9060665,23.2013366 39.4129159,25.1191259 C37.6125245,32.0309951 32.0078278,37.6278088 25.111546,39.412527 C23.334638,39.8665342 22.4970646,39.9682945 20.3131115,39.9917776 C19.2172211,40.007433 18.1761252,39.9999225 18.0039139,39.9761222 Z M22.4266145,36.9389702 C25.4168297,36.4927906 28.3913894,35.2012182 30.6927593,33.3460506 C32.555773,31.8509577 34.3091977,29.6591985 35.334638,27.5613719 C36.5636008,25.0486765 37.0958904,22.7473294 37.0958904,19.991975 C37.0958904,18.3794664 36.9706458,17.3305531 36.5949119,15.8354602 C35.0919765,9.77681156 30.2152642,4.90014735 24.1565558,3.39722675 C22.6614481,3.02149661 21.6125245,2.89625322 20,2.89625322 C18.3874755,2.89625322 17.3385519,3.02149661 15.8434442,3.39722675 C9.78473581,4.90014735 4.90802348,9.77681156 3.40508806,15.8354602 C3.02935421,17.3305531 2.90410959,18.3794664 2.90410959,19.991975 C2.90410959,24.6886018 4.71232877,28.9468768 8.10958904,32.2579987 C9.02544031,33.1503578 9.53424658,33.5652265 10.6223092,34.2932037 C12.962818,35.8509183 15.7573386,36.8058991 18.8258317,37.0876967 C19.4833659,37.1503184 21.6046967,37.0563858 22.4266145,36.9389702 Z" id="Shape"></path>
                <path d="M19.2563601,32.0857891 C19.2172211,32.0701337 19.0841487,32.0388228 18.9667319,32.0153397 C18.6614481,31.9448903 18.2778865,31.5926433 18.1135029,31.2247408 C17.9726027,30.9272878 17.964775,30.5593854 17.964775,24.7668789 C17.964775,18.958717 17.9726027,18.60647 18.1135029,18.309017 C18.2857143,17.9332868 18.5362035,17.6906278 18.9197652,17.5262459 C19.1780822,17.4244856 19.9452055,17.4088302 24.9080235,17.4088302 C30.3013699,17.4088302 30.6223092,17.4166579 30.927593,17.5575567 C31.4285714,17.7845603 31.6868885,18.1837736 31.7260274,18.7786797 C31.7651663,19.3892412 31.5694716,19.8197653 31.0998043,20.125046 L30.7710372,20.344222 L25.8551859,20.3677051 L20.9393346,20.3911882 L20.9393346,25.5731332 C20.9393346,28.9468768 20.9080235,30.8568384 20.853229,31.0525312 C20.7279843,31.4987107 20.3600783,31.8666132 19.8982387,32.007512 C19.5068493,32.1249276 19.4050881,32.1327554 19.2563601,32.0857891 Z" id="Path"></path>
            </g>
        </g>
    </g>
</svg>
                                                    @if ($vendor->is_vendor_closed == 0 && $vendor->show_slot == 0)
                                                        {{ $vendor->opening_time }} – {{ $vendor->closing_time }}
                                                        <span class="badge badge-success">{{ __('Open') }}</span>
                                                    @elseif($vendor->is_vendor_closed == 0 && $vendor->show_slot == 1)
                                                        24 x 7 <span class="badge badge-success">{{ __('Open') }}</span>
                                                    @elseif($vendor->closed_store_order_scheduled == 1 && $checkSlot != 0)
                                                        <span class="badge badge-danger">{{ __('Closed') }}</span>
                                                        <p class="p-0 m-0">{{ __('We are not accepting orders right now. You can schedule this for ') . $checkSlot }}.</p>
                                                    @else
                                                        <span class="badge badge-danger">{{ __('Closed') }}</span>
                                                    @endif
                                                    </span>
                                                    {{-- <span data-toggle="tooltip" data-placement="right" title="Tooltip on right"><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>
                                                            <span class="tooltip-text d-none">Mon-Sun : 11am - 11pm</span> --}}
                                                    </span>

                                                </li>
                                                @if ($vendor->order_min_amount > 0)
                                                    <span class="badge badge-danger">{{ __('Minimum order value') }}
                                                        {{ Session::get('currencySymbol') . decimal_format($vendor->order_min_amount) }}</span>
                                                @endif

                                            </ul>
                                        </div>
                            </div>

                        </div>
                        </div>
                        </div>
                        </div>

                        {{-- </div>
                    </div> --}}
            </div>
                <div class="position-relative container">
                    <div class="categories-product-list mt-sm-4">

                        <a id="side_menu_toggle" class="d-md-none d-flex" href="javascript:void(0)">
                            <div class="manu-bars">
                                <span class="bar-line"></span>
                                <span class="bar-line"></span>
                                <span class="bar-line"></span>
                            </div>
                            <span>{{ __('Menu') }}</span>
                        </a>

                        <div class="row">
                            <div class="col-12">
                                <div class="col-sm-6 offset-sm-3">
                                    <div class="row  d-flex align-items-start justify-content-center m-0">
                                        <div class="col-7 vendor-search-bar mb-sm-0 mb-2 p-0">
                                            <div class="radius-bar w-100">
                                                <div class="search_form d-flex align-items-center">
                                                    <button class="btn"><i class="fa fa-search" aria-hidden="true"></i></button>
                                                    <input class="form-control border-0 typeahead" type="search"
                                                        placeholder="{{ __('Search') }}" id="vendor_search_box">
                                                </div>
                                                <div class="list-box style-4" style="display:none;" id="search_box_main_div">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-5 text-right pl-0 pr-0">
                                            <!-- <span class="d-lg-inline-block d-none"> {{ __('Sort By') }} :</span> -->
                                            <select name="order_type" id='order_type' class="product_tag_filter p-1">
                                                <option value="featured">{{ __('Featured') }}</option>
                                                <option value="a_to_z">{{ __('A to Z') }}</option>
                                                <option value="z_to_a">{{ __('Z to A') }}</option>
                                                <option value="low_to_high">{{ __('Cost : Low to High') }}</option>
                                                <option value="high_to_low">{{ __('Cost : High to Low') }}</option>
                                                <option value="rating">{{ __('Avg. Customer Review') }}</option>
                                                <option value="newly_added">{{ __('Newest Arrivals') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row vendor-products-wrapper">
                                    <div class="col-sm-4 col-lg-3 border-right al_white_bg_round">
                                        <nav class="scrollspy-menu ">
                                            <ul>
                                                @forelse($listData as $key => $data)
                                                    <li><a data-slug="{{ $data->category->slug??'#' }}" style="cursor: pointer;">{{ $data->category->translation[0]->name??'' }}({{ $data->products_count }})</a>
                                                    </li>
                                                @empty
                                                @endforelse
                                            </ul>
                                        </nav>
                                    </div>
                                    <div class="col-md-8 col-lg-6 alScrollspyProduct">

                                        <div class="col-12 d-sm-flex justify-content-start mb-2 p-0">
                                            @if (isset($tags) && !empty($tags))
                                                @foreach ($tags as $key => $tag)
                                                    <label class="label-switch switch-primary product_tag_filter mr-2 mb-0">
                                                        <input type="checkbox"
                                                            class="switch switch-bootstrap product_tag_filter status"
                                                            name="tag_id" id="product_tag_filter_{{ $key }}"
                                                            data-tag_id="{{ $tag->id }}" value="
                                                            {{ $tag->id }}">
                                                        <span class="lable">
                                                            @if (isset($tag->icon) && !empty($tag->icon))
                                                                <img class="ml-1"
                                                                    src="{{ $tag->icon['proxy_url'] . '100/100' . $tag->icon['image_path'] }}"
                                                                    alt="">
                                                            @endif <span
                                                                class="ml-1">{{ $tag->primary ? $tag->primary->name : '' }}</span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>
                                        @forelse($listData as $key => $data)
                                            <section class="scrolling_section " id="{{ $data->category->slug }}">
                                                @if (!empty($data->products))
                                                    <h2 class="category-head mt-0 mb-3">
                                                        {{ @$data->category->translation[0]->name??'' }}
                                                        ({{ $data->products_count }})
                                                    </h2>
                                                    @forelse($data->products as $prod)
                                                    @php
                                                        $product_url =  route('productDetail', [$prod->vendor->slug, $prod->url_slug]);
                                                    @endphp
                                                        <div class="row cart-box-outer al_white_bg_round product_row classes_wrapper no-gutters mb-2 pb-2 border-bottom"
                                                            data-p_sku="{{ $prod->sku }}"
                                                            data-slug="{{ $prod->url_slug }}">
                                                            <div class=" col-sm-2 col-4 mb-2">
                                                                <a target="_blank"
                                                                    href="{{ $product_url }}">
                                                                    <div class="class_img product_image">
                                                                        <img src="{{ $prod->product_image }}"
                                                                            alt="{{ $prod->translation_title }}">
                                                                    </div>
                                                                </a>

                                                            </div>
                                                            <div class="col-sm-10 col-8  pl-md-3 pl-2">
                                                                <div class="row price_head">
                                                                    <div class="col-sm-12">
                                                                        <div
                                                                            class="d-flex align-items-start justify-content-between">
                                                                            <h5 class="mt-0">
                                                                                {{ $prod->translation_title }}

                                                                            </h5>
                                                                            <div class="product_variant_quantity_wrapper">
                                                                               
                                                                                    @php
                                                                                        $data = $prod;
                                                                                        $productVariantInCart = 0;
                                                                                        $productVariantIdInCart = 0;
                                                                                        $productVariantInCartWithDifferentAddons = 0;
                                                                                        $cartProductId = 0;
                                                                                        $cart_id = 0;
                                                                                        $vendor_id = 0;
                                                                                        $product_id = $data->id;
                                                                                        $variant_id = $data->variant[0] ? $data->variant[0]->id : 0;
                                                                                        $variant_price = 0;
                                                                                        $variant_quantity = $prod->variant_quantity;
                                                                                        $isAddonExist = 0;
                                                                                        $minimum_order_count = $data->minimum_order_count == 0 ? 1 : $data->minimum_order_count;
                                                                                        $batch_count = $data->batch_count;
                                                                                        if (count($data->addOn) > 0) {
                                                                                            $isAddonExist = 1;
                                                                                        }
                                                                                    //pr($data->toArray());
                                                                                    @endphp
                                                                                @if($prod->category_type_id !=10)
                                                                                    @foreach ($data->variant as $var)
                                                                                        @if (isset($var->checkIfInCart) && count($var->checkIfInCart) > 0)
                                                                                            @php
                                                                                                //dd($var->_markup_price);
                                                                                                $productVariantInCart = 1;
                                                                                                $productVariantIdInCart = $var->checkIfInCart['0']['variant_id'];
                                                                                                $cartProductId = $var->checkIfInCart['0']['id'];
                                                                                                $cart_id = $var->checkIfInCart['0']['cart_id'];
                                                                                                // $variant_quantity = $var->checkIfInCart['0']['quantity'];
                                                                                                $variant_quantity = 0;
                                                                                                $vendor_id = $data->vendor_id;
                                                                                                $product_id = $data->id;
                                                                                                $batch_count = $data->batch_count;
                                                                                                $variant_price = decimal_format($var->price * $data->variant_multiplier);
                                                                                                if (count($var->checkIfInCart) > 1) {
                                                                                                    $productVariantInCartWithDifferentAddons = 1;
                                                                                                }
                                                                                                foreach ($var->checkIfInCart as $cartVar) {
                                                                                                    $variant_quantity = $variant_quantity + $cartVar['quantity'];
                                                                                                }
                                                                                            @endphp
                                                                                            @break;
                                                                                        @endif
                                                                                    @endforeach

                                                                                    @if ( ($is_service_product_price_from_dispatch_forOnDemand ==1) || ($vendor->is_vendor_closed == 0 || ($vendor->closed_store_order_scheduled != 0 && $checkSlot != 0) )  )
                                                                                        @php
                                                                                            $is_customizable = false;
                                                                                            if ($isAddonExist > 0 && ($variant_quantity > 0 || $prod->sell_when_out_of_stock == 1)) {
                                                                                                $is_customizable = true;
                                                                                            }
                                                                                        @endphp

                                                                                        @if ($productVariantInCart > 0)
                                                                                            @if( $is_service_product_price_from_dispatch_forOnDemand ==1)
                                                                                                <a class="btn btn-solid btn btn-solid view_on_demand_price"  style="display:none;" id="add_button_href{{$cartProductId}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('view Price') }}</a>
                                                                                            @else
                                                                                            
                                                                                                {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a> --}}
                                                                                                <a class="add-cart-btn add_vendor_product as"
                                                                                                    style="display:none;"
                                                                                                    id="add_button_href{{ $cartProductId }}"
                                                                                                    data-variant_id="{{ $productVariantIdInCart }}"
                                                                                                    data-add_to_cart_url="{{ route('addToCart') }}"
                                                                                                    data-vendor_id="{{ $vendor_id }}"
                                                                                                    data-product_id="{{ $product_id }}"
                                                                                                    data-addon="{{ $isAddonExist }}"
                                                                                                    data-minimum_order_count="{{ $minimum_order_count }}"
                                                                                                    data-batch_count="{{ $batch_count }}"
                                                                                                    href="javascript:void(0)">{{ __('Add') }}
                                                                                                    @if ($minimum_order_count > 0)
                                                                                                        ({{ $minimum_order_count }})
                                                                                                    @endif
                                                                                                </a>
                                                                                            @endif
                                                                                                
                                                                                            @if(isset($data->category_type_id) && (!in_array($data->category_type_id,$category_type_idForNotShowshPlusMinus))) )
                                                                                                <div class="number"
                                                                                                    id="show_plus_minus{{ $cartProductId }}">
                                                                                                    <span
                                                                                                        class="minus qty-minus-product {{ $productVariantInCartWithDifferentAddons ? 'remove-customize' : '' }}"
                                                                                                        data-variant_id="{{ $productVariantIdInCart }}"
                                                                                                        data-parent_div_id="show_plus_minus{{ $cartProductId }}"
                                                                                                        data-id="{{ $cartProductId }}"
                                                                                                        data-base_price="{{ $variant_price }}"
                                                                                                        data-vendor_id="{{ $vendor_id }}"
                                                                                                        data-product_id="{{ $product_id }}"
                                                                                                        data-cart="{{ $cart_id }}"
                                                                                                        data-addon="{{ $isAddonExist }}"
                                                                                                        data-minimum_order_count="{{ $minimum_order_count }}"
                                                                                                        data-batch_count="{{ $batch_count }}">
                                                                                                        <i class="fa fa-minus"
                                                                                                            aria-hidden="true"></i>
                                                                                                    </span>
                                                                                                    <input
                                                                                                        style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;"
                                                                                                        placeholder="1" type="text"
                                                                                                        value="{{ $variant_quantity }}"
                                                                                                        class="input-number"
                                                                                                        id="quantity_ondemand_{{ $cartProductId }}"
                                                                                                        readonly>
                                                                                                    <span
                                                                                                        class="plus qty-plus-product {{ $is_customizable ? 'repeat-customize' : '' }}"
                                                                                                        data-variant_id="{{ $productVariantIdInCart }}"
                                                                                                        data-id="{{ $cartProductId }}"
                                                                                                        data-base_price="{{ $variant_price }}"
                                                                                                        data-vendor_id="{{ $vendor_id }}"
                                                                                                        data-product_id="{{ $product_id }}"
                                                                                                        data-cart="{{ $cart_id }}"
                                                                                                        data-addon="{{ $isAddonExist }}"
                                                                                                        data-batch_count="{{ $batch_count }}">
                                                                                                        <i class="fa fa-plus"
                                                                                                            aria-hidden="true"></i>
                                                                                                    </span>
                                                                                                </div>
                                                                                            @else
                                                                                                    <a class="btn btn-solid " id="added_button_href{{$cartProductId}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('Added') }}</a>
                                                                                            @endif

                                                                                        @else
                                                                                            @if ( (in_array($data->category_type_id,[12,8]))  || ($prod->has_inventory == 0 || ($variant_quantity > 0 || $prod->sell_when_out_of_stock == 1)))
                                                                                                @if(   $is_service_product_price_from_dispatch_forOnDemand ==1)
                                                                                                    <a class="btn btn-solid btn btn-solid view_on_demand_price"  id="add_button_href{{$data->id }}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('view Price') }}</a>
                                                                                                @else 
                                                                                                    {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a> --}}
                                                                                                    <a class="add-cart-btn add_vendor_product"
                                                                                                        id="aadd_button_href{{ $data->id }}"
                                                                                                        data-variant_id="{{ $data->variant[0]->id }}"
                                                                                                        data-add_to_cart_url="{{ route('addToCart') }}"
                                                                                                        data-vendor_id="{{ $data->vendor_id }}"
                                                                                                        data-product_id="{{ $data->id }}"
                                                                                                        data-addon="{{ $isAddonExist }}"
                                                                                                        data-batch_count="{{ $batch_count }}"
                                                                                                        data-minimum_order_count="{{ $minimum_order_count }}"
                                                                                                        href="javascript:void(0)">{{ __('Add') }}
                                                                                                        @if ($minimum_order_count > 1)
                                                                                                            ({{ $minimum_order_count }})
                                                                                                        @endif
                                                                                                    </a>
                                                                                                @endif
                                                                                                @if(isset($data->category_type_id) && (!in_array($data->category_type_id,$category_type_idForNotShowshPlusMinus)) )
                                                                                                    <div class="number"
                                                                                                        style="display:none;"
                                                                                                        id="ashow_plus_minus{{ $data->id }}">
                                                                                                        <span
                                                                                                            class="minus qty-minus-product"
                                                                                                            data-parent_div_id="show_plus_minus{{ $data->id }}"
                                                                                                            data-id="{{ $data->id }}"
                                                                                                            data-base_price="{{ decimal_format($data->variant_price * $data->variant_multiplier) }}"
                                                                                                            data-vendor_id="{{ $data->vendor_id }}"
                                                                                                            data-batch_count="{{ $batch_count }}"
                                                                                                            data-minimum_order_count="{{ $minimum_order_count }}">
                                                                                                            <i class="fa fa-minus"
                                                                                                                aria-hidden="true"></i>
                                                                                                        </span>
                                                                                                        <input
                                                                                                            style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;"
                                                                                                            id="quantity_ondemand_d{{ $data->id }}"
                                                                                                            readonly
                                                                                                            placeholder="{{ $minimum_order_count }}"
                                                                                                            type="text"
                                                                                                            value="{{ $minimum_order_count }}"
                                                                                                            class="input-number input_qty"
                                                                                                            step="0.01">
                                                                                                        <span
                                                                                                            class="plus qty-plus-product"
                                                                                                            data-id=""
                                                                                                            data-base_price="{{ decimal_format($data->variant_price * $data->variant_multiplier) }}"
                                                                                                            data-vendor_id="{{ $data->vendor_id }}"
                                                                                                            data-batch_count="{{ $batch_count }}"
                                                                                                            data-minimum_order_count="{{ $minimum_order_count }}">
                                                                                                            <i class="fa fa-plus"
                                                                                                                aria-hidden="true"></i>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                @else
                                                                                                    <a class="btn btn-solid "  style="display:none;" id="added_button_href{{$data->id}}" data-variant_id = {{$data->variant[0]->id}} data-add_to_cart_url = "{{ $add_to_cart }}" data-vendor_id="{{$data->vendor_id}}" data-product_id="{{$data->id}}" href="javascript:void(0)">{{ __('Added') }}</a>
                                                                                                @endif
                                                                                            @else
                                                                                                <span
                                                                                                    class="text-danger">{{ __('Out of stock') }}</span>
                                                                                            @endif
                                                                                        @endif
                                                                                        @if ($is_customizable)
                                                                                            <div class="customizable-text">
                                                                                                {{ __('customizable') }}
                                                                                            </div>
                                                                                        @endif
                                                                                    @endif
                                                                                @else
                                                                                    <a class="btn btn-solid"  href="{{  $product_url }}">{{ __('View') }}</a>
                                                                                @endif
                                                                        </div>
                                                                    </div>
                                                                    @if ($prod->averageRating > 0)
                                                                        <div class="rating-text-box">
                                                                            <span>{{ number_format($prod->averageRating, 1, '.', '') }}
                                                                            </span>
                                                                            <i class="fa fa-star"
                                                                                aria-hidden="true"></i>
                                                                        </div>
                                                                    @endif

                                                                    @if ($prod->minimum_order_count > 0)
                                                                        {{-- <p class="mb-1 product_price">   {{__('Minimum Quantity') }} : {{ $prod->minimum_order_count }} </p>
                                                                        <p class="mb-1 product_price">   {{__('Batch') }} : {{ $prod->batch_count }} </p> --}}
                                                                    @endif

                                                                    <p class="mb-1 product_price ">
                                                                        @if($is_service_product_price_from_dispatch_forOnDemand !=1) 
                                                                        {{-- price  not showing in vencor type in on demand and get price from dispatche--}}
                                                                            {{ Session::get('currencySymbol') . decimal_format($prod->variant_price * $prod->variant_multiplier,',') }}
                                                                            @if ($prod->variant[0]->compare_at_price > 0)
                                                                                <span
                                                                                    class="org_price ml-1  font-14">{{ Session::get('currencySymbol') .decimal_format($prod->variant[0]->compare_at_price * $prod->variant_multiplier) }}</span>
                                                                            @endif
                                                                        @endif
                                                                    </p>
                                                                    <div class="member_no d-block mb-0">

                                                                        <span>{!! $prod->translation_description !!}</span>
                                                                    </div>
                                                                    <div id="product_variant_options_wrapper">
                                                                        @if (!empty($prod->variantSet))
                                                                            @php
                                                                                $selectedVariant = $productVariantIdInCart > 0 ? $productVariantIdInCart : $prod->variant_id;
                                                                            @endphp
                                                                            @foreach ($prod->variantSet as $key => $variant)
                                                                                @if ($variant->type == 1 || $variant->type == 2)
                                                                                    <?php $var_id = $variant->variant_type_id; ?>
                                                                                    <select
                                                                                        name="{{ 'var_' . $var_id }}"
                                                                                        vid="{{ $var_id }}"
                                                                                        class="changeVariant dataVar{{ $var_id }}">
                                                                                        <option value="" disabled>
                                                                                            {{ $variant->title }}
                                                                                        </option>
                                                                                        @foreach ($variant->option2 as $k => $optn)
                                                                                            <?php
                                                                                            $opt_id = $optn->variant_option_id;
                                                                                            $selected = $selectedVariant == $optn->product_variant_id ? 'selected' : '';
                                                                                            ?>
                                                                                            <option
                                                                                                value="{{ $opt_id }}"
                                                                                                {{ $selected }}>
                                                                                                {{ $optn->title }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                @else
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                    <div class="variant_response">
                                                                        <span
                                                                            class="text-danger mb-2 mt-2 font-14"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                @endforelse
                                            @else
                                                <h4 class="mt-3 mb-3 text-center">No product found</h4>
                                            @endif
                                        </section>
                                        @empty
                                            <h4 class="mt-3 mb-3 text-center">No product found</h4>
                                        @endforelse
                                    </div>
                                    <div class="col-12 col-lg-3 d-lg-inline-block d-none">
                                        <div class="card-box p-0 cart-main-box">
                                            <div class="p-2 d-flex align-items-center justify-content-between border-bottom">
                                                <h4 class="right-card-title">{{ __('Cart') }}</h4>
                                            </div>
                                            <div class="cart-main-box-inside d-flex align-items-center">
                                                <div class="spinner-box">
                                                    <div class="circle-border">
                                                        <div class="circle-core"></div>
                                                    </div>
                                                </div>
                                                <div class="show-div shopping-cart flex-fill w-100"
                                                    id="header_cart_main_ul_ondemand"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="d-none d-md-block">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/template" id="header_cart_template_ondemand">
        <ul class="pl-2 pr-2 pb-2 pt-0 ">
            <% _.each(cart_details.products, function(product, key){%>
            <li class="p-0">
                <h6 class="d-flex justify-content-center badge badge-light font-14"><b><%= product.vendor.name %></b></h6>
            </li>

            <% if( (product.isDeliverable != undefined) && (product.isDeliverable == 0) ) { %>
            <li class="border_0">
                <th colspan="7">
                    <div class="text-danger">
                        {{ __('Products for this vendor are not deliverable at your area. Please change address or remove product.') }}
                    </div>
                </th>
            </li>
            <% } %>
            <% _.each(product.vendor_products, function(vendor_product, vp){%>
            <li class="p-0" id="cart_product_<%= vendor_product.id %>" data-qty="<%= vendor_product.quantity %>">
                <div class='media-body'>
                    <h6 class="d-flex align-items-center justify-content-between">
                        <%
                            translationOneTitle = '';
                            count = 20;
                            if(vendor_product.product.translation_one != ''){
                                title = vendor_product.product.translation_one.title;
                                translationOneTitle = title.slice(0, count) + (title.length > count ? "..." : "");
                            }
                        %>

                        <span class="ellips"><%= vendor_product.quantity %>x <%=
                        vendor_product.product.translation_one ? translationOneTitle :  vendor_product.product.sku %></span>
                        
                            <% if(cart_details.is_token_enable == 1) { %>
                                <span class="alPriceValue"><i class='fa fa-money mr-1' aria-hidden='true'></i><%=  Helper.formatPrice(vendor_product.quantity_price * cart_details.tokenAmount) %></span>
                                <% }else{ %>
                                <span>{{ Session::get('currencySymbol') }}<%=  Helper.formatPrice(vendor_product.quantity_price) %></span>
                            <% } %>
                        
                        <a class="action-icon remove_product_via_cart text-danger" style="cursor: pointer;" data-product="<%= vendor_product.id %>" data-product_id="<%= vendor_product.product_id %>" data-vendor_id="<%= vendor_product.vendor_id %>">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                    </h6>
                </div>
            </li>
            <!--  -->
            <% if(vendor_product.addon.length != 0) { %>
                <hr class="my-2">
                <div class="row align-items-md-center">
                    <div class="col-12">
                        <h6 class="m-0 font-12"><b>{{ __('Add Ons') }}</b></h6>
                    </div>
                </div>
                <% _.each(vendor_product.addon, function(addon, ad){%>
                <div class="row mb-1">
                    <div class="col-md-6 col-sm-4 items-details text-left">
                        <p class="m-0 font-14 p-0"><%= vendor_product.quantity %>x <%= addon.option.title %></p>
                    </div>
                    <div class="col-md-3 col-sm-4 text-center">
                        <div class="extra-items-price font-14">
                            <% if(cart_details.is_token_enable == 1) { %>
                                    <i class='fa fa-money' aria-hidden='true'></i><%=  Helper.formatPrice(addon.option.price_in_cart * cart_details.tokenAmount) %>
                                <% }else{ %>
                                    {{ Session::get('currencySymbol') }}<%= Helper.formatPrice(addon.option.price_in_cart) %>
                            <% } %>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 text-right">
                        <div class="extra-items-price font-14 mr-xl-3">
                            <% if(cart_details.is_token_enable == 1) { %>
                                <i class='fa fa-money' aria-hidden='true'></i><%=  Helper.formatPrice(addon.option.quantity_price * cart_details.tokenAmount) %>
                            <% }else{ %>
                            {{ Session::get('currencySymbol') }}<%= Helper.formatPrice(addon.option.quantity_price) %>
                            <% } %>
                        </div>
                    </div>
                </div>
                <% }); %>
            <% } %>
            <hr class="my-2 mt-3 line_diff_between_products">
            <% }); %>
            <% if(cart_details.delivery_charges > 0) { %>
                {{-- <hr class="my-2"> --}}
                <div class="row justify-content-between">
                    <div class="col-md-6 col-sm-6 text-left">
                        <h6 class="m-0 font-14">{{ __('Delivery fee') }}</h6>
                    </div>
                    <div class="col-md-6 col-sm-6 text-right">
                        <div class="font-14 mr-xl-2">
                            <% if(cart_details.is_token_enable == 1) { %>
                                <span class="alPriceValue"><i class='fa fa-money mr-1' aria-hidden='true'></i><%=  Helper.formatPrice(cart_details.delivery_charges * cart_details.tokenAmount) %></span>
                            <% }else{ %>
                                {{ Session::get('currencySymbol') }}<%= Helper.formatPrice(cart_details.delivery_charges) %>
                            <% } %>
                            </div>
                    </div>
                </div>
            <% } %>

            <% }); %>

            <h5 class="d-flex align-items-center justify-content-between pb-2">{{ __('PRICE DETAILS') }} </h5>
            <li class="p-0 alSixCart">
                <div class='media-body'>
                    <h6 class="d-flex align-items-center justify-content-between">
                        <span class="ellips">{{ __('Total') }}</span>
                        <% if(cart_details.is_token_enable == 1) { %>
                            <span class="alPriceValue"><i class='fa fa-money mr-1' aria-hidden='true'></i><%= Helper.formatPrice(cart_details.gross_amount * cart_details.tokenAmount) %></span>
                            <% }else{ %>
                                <span >{{ Session::get('currencySymbol') }}<%= Helper.formatPrice(cart_details.gross_amount) %></span>
                            <% } %>
                    </h6>
                </div>
            </li>
            <% if((cart_details.total_taxable_amount != undefined) && (cart_details.total_taxable_amount > 0)) { %>
            <li class="p-0 alSixCart">
                <div class='media-body'>
                    <h6 class="d-flex align-items-center justify-content-between">
                        <span class="ellips">{{ __('Tax') }}</span>
                        <% if(cart_details.is_token_enable == 1) { %>
                            <span class="alPriceValue"><i class='fa fa-money mr-1' aria-hidden='true'></i><%= (cart_details.total_taxable_amount * cart_details.tokenAmount) %></span>
                            <% }else{ %>
                            <span>{{ Session::get('currencySymbol') }}<%= cart_details.total_taxable_amount %></span>
                        <% } %>
                    </h6>
                </div>
            </li>
            <% } %>

            <% if((cart_details.total_subscription_discount != undefined) && (cart_details.total_subscription_discount > 0)) { %>
                <li class="p-0 alSixCart">
                <div class='media-body'>
                    <h6 class="d-flex align-items-center justify-content-between">
                        <span class="ellips"> {{ __('Subscription Discount') }}</span>
                        <% if(cart_details.is_token_enable == 1) { %>
                            <span class="alPriceValue"><i class='fa fa-money mr-1' aria-hidden='true'></i><%= (cart_details.total_subscription_discount * cart_details.tokenAmount) %></span>
                            <% }else{ %>
                            <span>{{ Session::get('currencySymbol') }}<%= cart_details.total_subscription_discount %></span>
                            <% } %>
                    </h6>
                </div>
            </li>
            <% } %>

            <% if(cart_details.loyalty_amount > 0) { %>
            <li class="p-0 alSixCart">
                <div class='media-body'>
                    <h6 class="d-flex align-items-center justify-content-between">
                        <span class="ellips"> {{ __('Loyalty Amount') }} </span>
                        <% if(cart_details.is_token_enable == 1) { %>
                            <span class="alPriceValue"><i class='fa fa-money mr-1' aria-hidden='true'></i><%= (cart_details.loyalty_amount * cart_details.tokenAmount) %></span>
                            <% }else{ %>
                            <span>{{ Session::get('currencySymbol') }}<%= cart_details.loyalty_amount %></span>
                            <% } %>
                    </h6>
                </div>
            </li>
            <% } %>

            <% if(cart_details.wallet_amount_used > 0) { %>
            <li class="p-0 alSixCart">
                <div class='media-body'>
                    <h6 class="d-flex align-items-center justify-content-between">
                        <span class="ellips"> {{ __('Wallet Amount') }} </span>
                        <% if(cart_details.is_token_enable == 1) { %>
                            <span class="alPriceValue"><i class='fa fa-money mr-1' aria-hidden='true'></i><%= (cart_details.wallet_amount_used * cart_details.tokenAmount) %></span>
                            <% }else{ %>
                            <span>{{ '-'.Session::get('currencySymbol') }}<%= cart_details.wallet_amount_used %></span>
                            <% } %>
                    </h6>
                </div>
            </li>
            <% } %>
        </ul>
        <div class="cart-sub-total d-flex align-items-center justify-content-between">
            <span>{{ __('Total') }}</span>
            <% if(cart_details.is_token_enable == 1) { %>
                <span class="alPriceValue"><i class='fa fa-money' aria-hidden='true'></i> <%= (cart_details.total_payable_amount * cart_details.tokenAmount) %></span>
                <% }else{ %>
                <span>{{ Session::get('currencySymbol') }}<%= cart_details.total_payable_amount %></span>
                <% } %>
        </div>
        <a class="checkout-btn text-center d-block" href="{{ route('showCart') }}">{{ __('Checkout') }}</a>
    </script>
    <script type="text/template" id="empty_cart_template">
        <div class="row">
            <div class="col-12 text-center pb-3">
                <img class="w-50 pt-3 pb-1" src="{{ asset('front-assets/images/ic_emptycart.svg') }}" alt="">
                <h5>{{ __('Your cart is empty') }}<br/>{{ __('Add an item to begin') }}</h5>
            </div>
        </div>
    </script>
    <script type="text/template" id="variant_image_template">
        <img src="<%= media.image_fit %>300/300<%= media.image_path %>" alt="">
                            </script>
    <script type="text/template" id="variant_template">
        <% if(variant.product.inquiry_only == 0) { %>
            <% if(is_token_enable == 1) { %>
                <i class='fa fa-money' aria-hidden='true'></i> <%= (variant.productPrice * tokenAmount)%>
                <% }else{ %>
                    {{ Session::get('currencySymbol') }}<%= variant.productPrice %>
                <% } %>
            <% if(variant.compare_at_price > 0 ) { %>
                <% if(is_token_enable == 1) { %>
                    <span class="org_price ml-1 font-14"><i class='fa fa-money' aria-hidden='true'></i> <%= (variant.compare_at_price* tokenAmount) %></span>
                    <% }else{ %>
                        <span class="org_price ml-1 font-14">{{ Session::get('currencySymbol') }}<%= variant.compare_at_price %></span>
                    <% } %>
                
            <% } %>
        <% } %>
    </script>
    <script type="text/template" id="variant_quantity_template">
        <% if(variant.quantity > 0){ %>
            <%
            var is_customizable = false;
            if(variant.isAddonExist > 0){
                is_customizable = true;
            }
            %>
            <% if(variant.check_if_in_cart != '') { %>
                {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a> --}}
                <a class="add-cart-btn add_vendor_product" style="display:none;" id="add_button_href<%= variant.check_if_in_cart.id %>" data-variant_id="<%= variant.id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>" data-product_id="<%= variant.product_id %>" href="javascript:void(0)">{{ __('Add') }}</a>
                <div class="number" id="show_plus_minus<%= variant.check_if_in_cart.id %>">
                    <span class="minus qty-minus-product <% if(is_customizable){ %> remove-customize <% } %>"  data-parent_div_id="show_plus_minus<%= variant.check_if_in_cart.id %>" data-id="<%= variant.check_if_in_cart.id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>" data-product_id="<%= variant.product_id %>" data-cart="<%= variant.check_if_in_cart.cart_id %>">
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </span>
                    <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="<%= variant.check_if_in_cart.quantity %>" class="input-number" step="0.01" id="quantity_ondemand_<%= variant.check_if_in_cart.id %>" readonly>
                    <span class="plus qty-plus-product <% if(is_customizable){ %> repeat-customize <% } %>"  data-id="<%= variant.check_if_in_cart.id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.check_if_in_cart.vendor_id %>" data-product_id="<%= variant.product_id %>" data-cart="<%= variant.check_if_in_cart.cart_id %>">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </span>
                </div>
            <% }else{ %>
                {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a> --}}
                <a class="add-cart-btn add_vendor_product" id="aadd_button_href<%= variant.product_id %>" data-variant_id="<%= variant.id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= variant.product.vendor_id %>" data-product_id="<%= variant.product_id %>" data-addon="<%= variant.isAddonExist %>" href="javascript:void(0)">{{ __('Add') }}</a>
                <div class="number" style="display:none;" id="ashow_plus_minus<%= variant.product_id %>">
                    <span class="minus qty-minus-product"  data-parent_div_id="show_plus_minus<%= variant.product_id %>" readonly data-id="<%= variant.product_id %>" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.product.vendor_id %>">
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </span>
                    <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" id="quantity_ondemand_d<%= variant.product_id %>" readonly placeholder="1" type="text" value="2" class="input-number input_qty" step="0.01">
                    <span class="plus qty-plus-product"  data-id="" data-base_price="<%= variant.price * variant.variant_multiplier %>" data-vendor_id="<%= variant.product.vendor_id %>">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </span>
                </div>
            <% } %>
            <% if(is_customizable){ %>
                <div class="customizable-text">customizable</div>
            <% } %>
        <% }else{ %>
            <span class="text-danger">{{ __('Out of stock')}}</span>
        <% } %>
    </script>
    <script type="text/template" id="addon_template">
        <% if(addOnData != ''){ %>
        <% if(addOnData.product_image){ %>
            <div class="d-flex" style="height:200px">
                <img class="w-100" src="<%= addOnData.product_image %>" alt=""  style="object-fit:cover">
            </div>
        <% } %>
        <div class="modal-header">
            <div class="d-flex flex-column">
                <h5 class="modal-title" id="product_addonLabel"><%= addOnData.translation_title %></h5>
                <% if(addOnData.averageRating > 0){ %>
                <div class="rating-text-box justify-content-start" style="width: max-content;">
                    <span><%= addOnData.averageRating %></span>
                    <i class="fa fa-star" aria-hidden="true"></i>
                </div>
                <% } %>
                <span><small><%= addOnData.translation_description %></small></span>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body p-0">
            <% _.each(addOnData.add_on, function(addon, key1){ %>
                <div class="border-product border-top">
                    <div class="addon-product" style="padding: 16px;">
                        <h4 addon_id="<%= addon.addon_id %>" class="header-title productAddonSet mb-0"><%= addon.title %></h4>
                        <div class="addonSetMinMax mb-2">
                            <%
                                var min_select = '';
                                if(addon.min_select > 0){
                                    min_select = "{{ __('Minimum')}} " + addon.min_select;
                                }
                                var max_select = '';
                                if(addon.max_select > 0){
                                    max_select = "{{ __('Maximum')}} " + addon.max_select;
                                }
                                if( (min_select != '') && (max_select != '') ){
                                    min_select = min_select + " {{ __('and')}} ";
                                }
                            %>
                            <% if( (min_select != '') || (max_select != '') ) { %>
                                <small><%=min_select + max_select %> {{ __('Selections Allowed')}}</small>
                            <% } %>
                        </div>
                        <div class="productAddonSetOptions" data-min="<%= addon.min_select %>" data-max="<%= addon.max_select %>" data-addonset-title="<%= addon.title %>">
                            <% _.each(addon.setoptions, function(option, key2){ %>
                                <% if(key2 == '5')  { %>
                                    <div class="d-flex justify-content-end">
                                        <a class="show_subet_addeon" data-div_id_show="subOption<%= addon.addon_id  %>_<%= key2  %>"  href="javascript:void(0)">{{ __('Show more') }}</a>
                                    </div>
                                    <div class="more-subset d-none" id="subOption<%= addon.addon_id %>_<%= key2 %>" >
                                <% } %>
                                <div class="checkbox-success d-flex mb-1 " <%= key2  %> >
                                    <label class="pr-2 mb-0 flex-fill font-14" for="inlineCheckbox_<%= key1 %>_<%= key2 %>">
                                        <%= option.title %>
                                    </label>
                                    <div>
                                        <span class="addon_price mr-1 font-14">{{ Session::get('currencySymbol') }}<%= Helper.formatPrice(option.price) %></span>
                                        <input type="checkbox" id="inlineCheckbox_<%= key1 %>_<%= key2 %>" class="product_addon_option" name="addonData[<%= key1 %>][]" addonId="<%= addon.addon_id %>" addonOptId="<%= option.id %>" addonPrice="<%= option.price %>">
                                    </div>
                                </div>
                                <% if((key2 > 5) && (key2 == (_.size(addon.setoptions) - 1 )) ){ %>
                                </div>
                                <% } %>
                            <% }); %>
                        </div>
                    </div>
                </div>
            <% }); %>
            <div class="addon_response text-danger font-14 d-none" style="padding:0 16px"></div>
        </div>
        <div class="modal-footer flex-nowrap align-items-center">
            <div class="counter-container d-flex align-items-center">
                <span class="minus qty-action" >
                    <i class="fa fa-minus" aria-hidden="true"></i>
                </span>
                <input style="text-align:center; width:60px; height:24px; padding-bottom: 3px; border:none" placeholder="1" type="text" value="1" class="addon-input-number" step="1" readonly>
                <span class="plus qty-action" >
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
            </div>
            <input type="hidden" id="addonVariantPriceVal" value="<%= addOnData.variant_price %>">
            <a class="btn btn-solid add-cart-btn flex-fill add_vendor_addon_product" id="add_vendor_addon_product" href="javascript:void(0)" data-variant_id="<%= addOnData.variant[0].id %>" data-add_to_cart_url="{{ route('addToCart') }}" data-vendor_id="<%= addOnData.vendor_id %>" data-product_id="<%= addOnData.id %>">{{ __('Add') }} {{ Session::get('currencySymbol') }}<span class="addon_variant_price"><%= addOnData.variant_price %></span></a>
        </div>
    <% } %>
</script>
    <div class="modal fade remove-item-modal" id="remove_item_modal" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-labelledby="remove_itemLabel" aria-hidden="true"
        style="background-color: rgba(0,0,0,0.8); z-index: 1051">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <h5 class="modal-title" id="remove_itemLabel">{{ __('Remove Item') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <input type="hidden" id="vendor_id" value="">
                    <input type="hidden" id="product_id" value="">
                    <input type="hidden" id="cartproduct_id" value="">
                    <h6 class="m-0 px-3">{{ __('Are You Sure You Want To Remove This Item?') }}</h6>
                </div>
                <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                    <button type="button" class="btn btn-solid black-btn"
                        data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-solid" id="remove_product_button">{{ __('Remove') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade product-addon-modal" id="product_addon_modal" tabindex="-1" aria-labelledby="product_addonLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

            </div>
        </div>
    </div>
    <div class="modal fade repeat-item-modal" id="repeat_item_modal" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-labelledby="repeat_itemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header pb-0">
                    <h5 class="modal-title" id="repeat_itemLabel">{{ __('Repeat last used customization') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="last_cart_product_id" value="">
                    <input type="hidden" class="curr_variant_id" value="">
                    <input type="hidden" class="curr_vendor_id" value="">
                    <input type="hidden" class="curr_product_id" value="">
                    <input type="hidden" class="curr_product_has_addons" value="">
                    <input type="hidden" add_to_cart_url="cart" value="{{ route('addToCart') }}">
                </div>
                <div class="modal-footer flex-nowrap justify-content-center align-items-center">
                    <button type="button" class="btn btn-solid black-btn" id="repeat_item_with_new_addon_btn"
                        data-dismiss="modal">{{ __('Add new') }}</button>
                    <button type="button" class="btn btn-solid" id="repeat_item_btn">{{ __('Repeat last') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="social-media-links-modal" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-labelledby="repeat_itemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content social-icon-list">
                <div class="modal-header pb-0">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   @if(!empty($socialMediaUrls))
                   @foreach($socialMediaUrls as $url)
                   <div class="text-center">
                        @php
                            if($url->icon == 'facebook'){
                                $iconUrl = asset('assets/images/social-media/facebook.png');
                            }else if($url->icon == 'github'){
                                $iconUrl = asset('assets/images/social-media/github.png');
                            }else if($url->icon == 'reddit'){
                                $iconUrl = asset('assets/images/social-media/reddit.png');
                            }else if($url->icon == 'whatsapp'){
                                $iconUrl = asset('assets/images/social-media/whatsapp-img.png');
                            }else if($url->icon == 'instagram'){
                                $iconUrl = asset('assets/images/social-media/instagram.png');
                            }else if($url->icon == 'tumblr'){
                                $iconUrl = asset('assets/images/social-media/tumblr.png');
                            }else if($url->icon == 'twitch'){
                                $iconUrl = asset('assets/images/social-media/twitch.png');
                            }else if($url->icon == 'twitter'){
                                $iconUrl = asset('assets/images/social-media/twitter.png');
                            }else if($url->icon == 'pinterest'){
                                $iconUrl = asset('assets/images/social-media/pinterest.png');
                            }else if($url->icon == 'youtube'){
                                $iconUrl = asset('assets/images/social-media/youtube.png');
                            }else if($url->icon == 'snapchat'){
                                $iconUrl = asset('assets/images/social-media/snapchat.png');
                            }else if($url->icon == 'linkedin'){
                                $iconUrl = asset('assets/images/social-media/linkedin.png');
                            }
                        @endphp
                        <a target="_blank" href="{{$url->url}}"><img src="{{$iconUrl}}" alt=""></a>
                    </div>
                   @endforeach
                   @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade customize-repeated-item-modal" id="customize_repeated_item_modal" data-backdrop="static"
        data-keyboard="false" tabindex="-1" aria-labelledby="customize_repeated_itemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

            </div>
        </div>
    </div>
    <!-- vendorStories -->
    <!-- <div id="vendorStories" class="modal fade" tabindex="-1" aria-labelledby="vendorStoriesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
                <img class="modal-content" id="img01">
        </div>
    </div> -->

    @if($is_service_product_price_from_dispatch_forOnDemand ==1)
        @include('frontend.ondemand.productPriceModel');
    @endif
@endsection
@if($is_service_product_price_from_dispatch_forOnDemand ==1)
    @section('custom-js')
    <script src="{{ asset('js/onDemand/GetDispatcherPrice.js') }}"></script>
    @endsection
@endif
@section('script')

    <script src="{{ asset('front-assets/js/rangeSlider.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/my-sliders.js') }}"></script>
    <script>
        @if(!empty($vendor->banner))
            $(document).ready(function() {
                $("body").addClass("homeHeader");
            });
        @endif

         //Get the modal vendorStories
        var modal = document.getElementById("vendorStories");

         //Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById("vendorStoriesImg");
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
            img.onclick = function(){
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

         //When the user clicks on <span> (x), close the modal
        span.onclick = function() {
        modal.style.display = "none";
        }
    </script>
    <script>
        var get_product_addon_url = "{{ route('vendorProductAddons') }}"

        // jQuery(window).scroll(function() {

        //     var scroll = jQuery(window).scrollTop();
        //     var categories_list_height = $('.vendor-products-wrapper').height() +400;

        //     if (scroll >= 600) {
        //         jQuery(".categories-product-list").addClass("fixed-bar");
        //     } else {
        //         jQuery(".categories-product-list").removeClass("fixed-bar");
        //     }
        //     if(scroll >= categories_list_height){
        //         jQuery(".categories-product-list").removeClass("fixed-bar");
        //     }
        // });

        var addonids = [];
        var addonoptids = [];
        var showChar = 136;
        var ellipsestext = "...";
        var moretext = "Read more";
        var lesstext = "Read less";

        function addReadMoreLink(){
            $('.price_head .member_no span').each(function() {
                var content = $(this).html();
                if (content.length > showChar) {

                    var firstContent = content.substr(0, showChar);
                    var lastContent = content.substr(showChar, content.length - showChar);
                    firstContent = firstContent.trim();
                    var html = firstContent + '<span class="moreellipses">' + ellipsestext +
                        '</span><span class="morecontent"><span style="display:none;">' + lastContent +
                        '</span><a href="" class="morelink">' + moretext + '</a></span>';

                    $(this).html(firstContent+lastContent);
                }

            });
        }
        addReadMoreLink();

        $(document).on('click', '.morelink', function() {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });

        $(document).ready(function(){
            vendorProductsSearchResults();
        });

        $(document).delegate(".product_tag_filter", "change", function() {
            vendorProductsSearchResults();
        });
    </script>
    <script>
        var base_url = "{{ url('/') }}";
        var place_order_url = "{{ route('user.placeorder') }}";
        var payment_stripe_url = "{{ route('payment.stripe') }}";
        var user_store_address_url = "{{ route('address.store') }}";
        var promo_code_remove_url = "{{ route('remove.promocode') }}";
        var payment_paypal_url = "{{ route('payment.paypalPurchase') }}";
        var update_qty_url = "{{ url('product/updateCartQuantity') }}";
        var promocode_list_url = "{{ route('verify.promocode.list') }}";
        var payment_option_list_url = "{{ route('payment.option.list') }}";
        var apply_promocode_coupon_url = "{{ route('verify.promocode') }}";
        var payment_success_paypal_url = "{{ route('payment.paypalCompletePurchase') }}";
        var getTimeSlotsForOndemand = "{{ route('getTimeSlotsForOndemand') }}";
        var update_cart_schedule = "{{ route('cart.updateSchedule') }}";
        var showCart = "{{ route('showCart') }}";
        var update_addons_in_cart = "{{ route('addToCartAddons') }}";
        var vendor_products_page_search_url = "{{ route('vendorProductsSearchResults') }}";
        var get_last_added_product_variant_url = "{{ route('getLastAddedProductVariant') }}";
        var get_product_variant_with_different_addons_url = "{{ route('getProductVariantWithDifferentAddons') }}"
        var addonids = [];
        var addonoptids = [];
        var ajaxCall = 'ToCancelPrevReq';


        $(document).on('click', '.open-social-medialinks', function(e) {
            $('#social-media-links-modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });
        $(document).on('click', '.show_subet_addeon', function(e) {
            e.preventDefault();
            var show_class = $(this).data("div_id_show");
            $(this).addClass("d-none");
            $("#" + show_class).removeClass("d-none");
        });

        $(document).delegate('.changeVariant', 'change', function() {
            var variants = [];
            var options = [];
            var product_variant_url = "{{ route('productVariant', ':sku') }}";
            var sku = $(this).parents('.product_row').attr('data-p_sku');
            var that = this;
            $(that).parents('.product_row').find('.changeVariant').each(function() {
                if (this.val != '') {
                    variants.push($(this).attr('vid'));
                    options.push($(this).val());
                }
            });
            // console.log(variants);
            // console.log(options);
            // return 0;
            ajaxCall = $.ajax({
                type: "post",
                dataType: "json",
                url: product_variant_url.replace(":sku", sku),
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
                success: function(response) {
                    if (response.status == 'Success') {
                        response = response.data;
                        // console.log(response);
                        $(that).parents('.product_row').find(".variant_response span").html('');
                        if (response.variant != '') {

                            $(that).parents('.product_row').find(".add-cart-btn").attr(
                                'data-variant_id', response.variant.id);

                            $(that).parents('.product_row').find('.product_price').html('');
                            let variant_template = _.template($('#variant_template').html());
                            $(that).parents('.product_row').find('.product_price').append(
                                variant_template({
                                    variant: response.variant, tokenAmount: response.tokenAmount, is_token_enable: response.is_token_enable
                                }));

                            $(that).parents('.product_row').find('.product_variant_quantity_wrapper')
                                .html('');
                            let variant_quantity_template = _.template($('#variant_quantity_template')
                                .html());
                            $(that).parents('.product_row').find('.product_variant_quantity_wrapper')
                                .append(variant_quantity_template({
                                    variant: response.variant
                                }));

                            let variant_image_template = _.template($('#variant_image_template')
                                .html());

                            $(that).parents('.product_row').find('.product_image').html('');
                            $(that).parents('.product_row').find('.product_image').append(
                                variant_image_template({
                                    media: response.variant
                                }));
                        }
                    } else {
                        $(that).parents('.product_row').find(".variant_response span").html(response
                            .message);
                        $(that).parents('.product_row').find(".add-cart-btn").hide();
                        $(that).parents('.product_row').find(
                            ".product_variant_quantity_wrapper .text-danger").remove();
                    }
                },
                error: function(data) {

                },
            });
        });

        $(document).delegate("#vendor_search_box", "input", function() {
            let keyword = $(this).val();
            vendorProductsSearchResults();
        });

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
            $("#show_copy_msg_on_click_copy").hide();
            $("#show_copy_msg_on_click_copied").show();
            setTimeout(function() {
                $("#show_copy_msg_on_click_copied").hide();
                $("#show_copy_msg_on_click_copy").show();
            }, 1000);
        }

        function vendorProductsSearchResults() {
            let keyword = $("#vendor_search_box").val();
            let order_type = $("#order_type").val();
            var checkboxesChecked = [];
            $("input:checkbox[name=tag_id]:checked").each(function() {
                checkboxesChecked.push($(this).val());
            });
            var checkedvalus = checkboxesChecked.length > 0 ? checkboxesChecked : null;
            // if (keyword.length > 2 || keyword.length == 0) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            ajaxCall = $.ajax({
                type: "post",
                dataType: 'json',
                url: vendor_products_page_search_url,
                data: {
                    tag_id: checkedvalus,
                    keyword: keyword,
                    order_type: order_type,
                    vendor: "{{ $vendor->id }}",
                    vendor_category: "{{ $vendor_category ?? '' }}"
                },
                beforeSend: function() {
                    if (ajaxCall != 'ToCancelPrevReq' && ajaxCall.readyState < 4) {
                        ajaxCall.abort();
                    }
                },
                success: function(response) {
                    if (response.status == 'Success') {
                        var cart_html = $('.vendor-products-wrapper #header_cart_main_ul_ondemand').html();
                        $('.vendor-products-wrapper').html(response.html);
                        $('.vendor-products-wrapper #header_cart_main_ul_ondemand').html(cart_html);
                        addReadMoreLink();
                    }
                }
            });
            // }
        }
    </script>

@endsection
