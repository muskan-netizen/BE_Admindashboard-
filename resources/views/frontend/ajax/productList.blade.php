@php
    $additionalPreference = getAdditionalPreference(['is_token_currency_enable','is_service_product_price_from_dispatch']);
    $is_service_product_price_from_dispatch_forOnDemand = 0;

    if(($additionalPreference['is_service_product_price_from_dispatch'] == 1) && ( Session::get('vendorType') == 'on_demand')){
        $is_service_product_price_from_dispatch_forOnDemand =1;
    }
@endphp

@if(@$data['filter_type'] && $data['filter_type'] == 1)
<div class="col-12 custom_filtter">
    <ul>
        <input type="hidden" name="order_type" id='order_type' class="sortingFilter" />
        <li><span>{{__('Sort By:')}}</span></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "newly_added" ? 'active' : ''}}" data-value="newly_added">{{__('Newest Arrivals')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "featured" ? 'active' : ''}}" data-value="featured">{{__('Featured')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "a_to_z" ? 'active' : ''}}" data-value="a_to_z">{{__('A to Z')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "z_to_a" ? 'active' : ''}}" data-value="z_to_a">{{__('Z to A')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "low_to_high" ? 'active' : ''}}" data-value="low_to_high">{{__('Cost : Low to High')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "high_to_low" ? 'active' : ''}}" data-value="high_to_low">{{__('Cost : High to Low')}}</a></li>
        <li><a href="javascript:void(0)" class="sortingFilterOther {{isset($data['order_type']) && $data['order_type'] == "rating" ? 'active' : ''}}" data-value="rating">{{__('Avg. Customer Review')}}</a></li>
        
    </ul>
</div>
@else
<div class="col-12 text-right mt-2">
    <select name="order_type" id='order_type' class="sortingFilter p-1">
     <option value="">{{__('Sort By')}}</option>
     <option value="newly_added" {{isset($data['order_type']) && $data['order_type'] == "newly_added" ? 'selected' : ''}}>{{__('Newest Arrivals')}}</option>
        <option value="featured" {{isset($data['order_type']) && $data['order_type'] == "featured" ? 'selected' : ''}}>{{__('Featured')}}</option>
        <option value="a_to_z" {{isset($data['order_type']) && $data['order_type'] == "a_to_z" ? 'selected' : ''}}>{{__('A to Z')}}</option>
        <option value="z_to_a" {{isset($data['order_type']) && $data['order_type'] == "z_to_a" ? 'selected' : ''}}>{{__('Z to A')}}</option>
        <option value="low_to_high" {{isset($data['order_type']) && $data['order_type'] == "low_to_high" ? 'selected' : ''}}>{{__('Cost : Low to High')}}</option>
        <option value="high_to_low" {{isset($data['order_type']) && $data['order_type'] == "high_to_low" ? 'selected' : ''}}>{{__('Cost : High to Low')}}</option>
        <option value="rating" {{isset($data['order_type']) && $data['order_type'] == "rating" ? 'selected' : ''}}>{{__('Avg. Customer Review')}}</option>
       
    </select>
</div>
@endif
<div class="product-wrapper-grid">
    <div class="row margin-res">
      @if($listData->isNotEmpty())
        @foreach($listData as $key => $data)
        <div class="col-xl-3 col-md-3 col-6 col-grid-box mt-3">
            <a href="{{route('productDetail', [$data->vendor->slug,$data->url_slug])}}" target="_blank" class="product-box scale-effect mt-0">
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
                        <h6 class="mt-0 mb-1"><b>{{$data->vendor->name}}</b></h6>
                        @if (strlen($data->translation_description) >= 65)
                            <p title="{{$data->translation_description}}">{{ substr($data->translation_description, 0, 64)." ..." }}</p>
                        @else
                            <p>{{ $data->translation_description }}</p>
                        @endif
                        @if($is_service_product_price_from_dispatch_forOnDemand !=1)
                            @if($data->inquiry_only == 0)
                                <h4 class="mt-1">
                                    @if( $additionalPreference["is_token_currency_enable"]) 
                                    {!!"<i class='fa fa-money' aria-hidden='true'></i> "!!}{{ getInToken((decimal_format($data->variant_price * $data->variant_multiplier))) }}
                                    @else
                                    {{Session::get('currencySymbol').' '.(decimal_format($data->variant_price * $data->variant_multiplier))}}
                                    @endif
                                </h4>
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

@if(count($listData))
<div class="pagination pagination-rounded justify-content-end mb-0">
    {{ $listData->links() }}
</div>
@endif
