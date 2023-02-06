<div class="col-12 text-right mt-2">
    <select name="order_type" id='order_type' class="sortingFilter p-1">
     <option value="">{{__('Sort By')}}</option>
        <option value="featured" {{isset($data['order_type']) && $data['order_type'] == "featured" ? 'selected' : ''}}>{{__('Featured')}}</option>
        <option value="a_to_z" {{isset($data['order_type']) && $data['order_type'] == "a_to_z" ? 'selected' : ''}}>{{__('A to Z')}}</option>
        <option value="z_to_a" {{isset($data['order_type']) && $data['order_type'] == "z_to_a" ? 'selected' : ''}}>{{__('Z to A')}}</option>
        <option value="low_to_high" {{isset($data['order_type']) && $data['order_type'] == "low_to_high" ? 'selected' : ''}}>{{__('Cost : Low to High')}}</option>
        <option value="high_to_low" {{isset($data['order_type']) && $data['order_type'] == "high_to_low" ? 'selected' : ''}}>{{__('Cost : High to Low')}}</option>
        <option value="rating" {{isset($data['order_type']) && $data['order_type'] == "rating" ? 'selected' : ''}}>{{__('Avg. Customer Review')}}</option>
        <option value="newly_added" {{isset($data['order_type']) && $data['order_type'] == "newly_added" ? 'selected' : ''}}>{{__('Newest Arrivals')}}</option>
    </select>
</div>
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
                        @if($data->inquiry_only == 0)
                            <h4 class="mt-1">{{Session::get('currencySymbol').' '.(decimal_format($data->variant_price * $data->variant_multiplier))}}</h4>
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
