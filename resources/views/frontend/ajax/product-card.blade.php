@php
$additionalPreference = getAdditionalPreference(['is_service_product_price_from_dispatch']);
$is_service_product_price_from_dispatch_forOnDemand = 0;

if(($additionalPreference['is_service_product_price_from_dispatch'] == 1) && ( Session::get('vendorType') == 'on_demand')){
    $is_service_product_price_from_dispatch_forOnDemand =1;
}
@endphp
<div class="product-wrapper-grid">
    <div class="row margin-res vendor_first">
        @if($listData->isNotEmpty())
        @foreach($listData as $key => $data)
        <div class="col-xl-3 col-md-3 col-6 mt-3">
            <a href="{{route('productDetail', [$data->vendor->slug,$data->url_slug])}}" target="_blank" class="product-box scale-effect mt-0 product-card-box position-relative al_box_third_template al">
                <div class="product-image">
                    <img class="img-fluid blur-up lazyload" data-src="{{$data->image_url}}" alt="">
                </div>
                <div class="media-body align-self-center">
                    <div class="inner_spacing w-100">
                        <h3 class="d-flex align-items-center justify-content-between">
                            <label class="mb-0"><b>{{ $data->translation_title }}</b></label>
                            @if($client_preference_detail)
                                @if($client_preference_detail->rating_check == 1)
                                    @if($data->averageRating > 0)
                                        <span class="rating">{{ number_format($data->averageRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                    @endif
                                @endif
                            @endif
                        </h3>
                        <div class="product-description_list border-bottom">
                            @if($dicountPercentage = productDiscountPercentage($data->variant_price, $data->variant_compare_at_price))
                                <span class="flag-discount">{{$dicountPercentage}}% Off</span>
                            @endif
                            <h6 class="mt-0 mb-1"><b>{{$data->vendor->name}}</b></h6>
                            @if(@$data->vendor->is_seller == 1)
                                <h6 class="sold-by d-flex">
                                    <b> <img class="blur-up lazyload" data-src="{{$favicon}}" alt="{{$data->vendor->Name}}" style="width: 25px !important; height: 25px;"></b> <b> Order by clickokart </b>
                                </h6>
                            @endif
                            </div>
                            @if(($data->inquiry_only == 0) && ($is_service_product_price_from_dispatch_forOnDemand !=1) )
                                @if ($additionalPreference ['is_token_currency_enable'] )
                                <i class='fa fa-money' aria-hidden='true'></i> {{ getInToken($data->variant_price * $data->variant_multiplier)}}
                                @else
                                    <h4 class="mt-1">{{Session::get('currencySymbol').' '.(decimal_format($data->variant_price * $data->variant_multiplier))}}</h4>
                                @endif
                            @endif
                        
                    </div>
                </div>
            </a>
        </div>
        @endforeach
        @else
        <div class="col-xl-12 col-12 mt-4"><h5 class="text-center">{{ __('No Product Found') }}</h5></div>
        @endif
    </div>
</div>
