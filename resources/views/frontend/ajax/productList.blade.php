<div class="product-wrapper-grid">
    <div class="row margin-res">
      @if($listData->isNotEmpty())
        @foreach($listData as $key => $data)
            @if(($data->variant)->isNotEmpty())
                <?php /*$imagePath = $imagePath2 = '';
                $mediaCount = count($data->media);
                for ($i = 0; $i < $mediaCount && $i < 2; $i++) {
                    if($i == 0){
                        $imagePath = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
                    }
                    $imagePath2 = $data->media[$i]->image->path['proxy_url'].'300/300'.$data->media[$i]->image->path['image_path'];
                }*/ ?>
                <div class="col-xl-3 col-6 col-grid-box mt-3">
                                                    <div class="product-box scale-effect">
                                                        <div class="img-wrapper">
                                                            <div class="front">
                                                                <a href="{{route('productDetail', [$data->vendor->slug,$data->sku])}}"><img class="img-fluid blur-up lazyload" data-src="{{$data->image_url}}" alt=""></a>
                                                            </div>
                                                            <div class="cart-info cart-wrap">
                                                                <button data-toggle="modal" data-target="#addtocart" title="Add to cart"><i class="ti-shopping-cart"></i></button>
                                                                <a href="javascript:void(0)" title="Add to Wishlist" class="addWishList" proSku="{{$data->sku}}"><i class="ti-heart" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="product-detail">
                                                            <div class="inner_spacing">
                                                                <a href="{{route('productDetail', [$data->vendor->slug,$data->sku])}}">
                                                                    <h3>{{ $data->translation_title }}</h3>
                                                                </a>
                                                                <h6 class="mt-0"><b>{{$data->vendor->name}}</b></h6>
                                                                <h4 class="mt-1">{{Session::get('currencySymbol').(number_format($data->variant_price * $data->variant_multiplier,2))}}</h4>
                                                                @if($client_preference_detail)
                                                                    @if($client_preference_detail->rating_check == 1)
                                                                        @if($data->averageRating > 0)
                                                                            <span class="rating">{{ number_format($data->averageRating, 1, '.', '') }} <i class="fa fa-star text-white p-0"></i></span>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
            @endif
        @endforeach 
	  @else
		<div class="col-xl-12 col-12 mt-4"><h5 class="text-center">No Product Found</h5></div>
	  @endif
    </div>
</div>

@if(count($listData))
<div class="pagination pagination-rounded justify-content-end mb-0">
    {{ $listData->links() }} 
</div>
@endif
