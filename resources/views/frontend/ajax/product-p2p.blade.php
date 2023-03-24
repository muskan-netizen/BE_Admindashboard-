<div class="product-wrapper-grid">
    <div class="row margin-res test">
      @if($listData->isNotEmpty())
        @foreach($listData as $key => $data)
        {{-- @dd($data) --}}
        <?php /*$imagePath = $imagePath2 = '';
$mediaCount = count($data->media);
for ($i = 0; $i < $mediaCount && $i < 2; $i++) {
    if($i == 0){
        $imagePath = $data->media[$i]->image->path['image_fit'].'300/300'.$data->media[$i]->image->path['image_path'];
    }
    $imagePath2 = $data->media[$i]->image->path['image_fit'].'300/300'.$data->media[$i]->image->path['image_path'];
}*/ ?>
<div class="col-xl-3 col-md-4 col-6 mt-3">
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
                <div class="product-description_list">
                    <span class="flag-discount">30% Off</span>
                    <h6 class="mt-0 mb-1"><b>{{$data->vendor->name}}</b></h6>
                    @if (strlen($data->translation_description) >= 65)
                        <p title="{{$data->translation_description}}">{{ substr($data->translation_description, 0, 64)." ..." }}</p>
                    @else
                        <p>{{ $data->translation_description }}</p>
                    @endif
                    </div>
                    @if(!empty($data->ProductAttribute))
                        @foreach ($data->ProductAttribute as $attribute) 
                            @if(@$attribute && $attribute->key_name == "Location") 
                                <div class="d-flex align-items-center justify-content-between prod_location pt-2">
                                    <b class="flex nowrap"><span class="loction ellips"><i class="fa fa-map-marker" aria-hidden="true"></i>   {{$attribute->key_value}}</span></b>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    <div class="d-flex align-items-center justify-content-between al_clock pt-2 update_year">
                        <b>Updated {{ convertDateToHumanReadable($data->updated_at) }} </b>
                    </div>
                    <div class="product-price-chat-sec">
                        @if($data->inquiry_only == 0)
                            <h4 class="mt-1">{{Session::get('currencySymbol').' '.(decimal_format($data->variant_price * $data->variant_multiplier))}}</h4>
                        @endif
                        <div class="prod-details">
                            <div class="chat-button">
                                @if(getAdditionalPreference(['chat_button'])['chat_button'])
                                    <button class="start_chat chat-icon btn btn-solid"  data-vendor_order_id="" data-chat_type="userToUser" data-vendor_id="{{$data->vendor->id}}" data-orderid="" data-order_id="" data-product_id="{{$data->id}}" style="margin-right: 5px !important;"><i class="fa fa-comments" aria-hidden="true"></i></button>
                                            
                                        @endif
                                        @if(getAdditionalPreference(['call_button'])['call_button'])
                                            <button class="call-icon btn btn-solid" href="tel:"><i class="fa fa-phone-square" aria-hidden="true"></i></button>
                                            
                                        @endif
                                    </div>
                                </div>
                            </div>
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