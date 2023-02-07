<div class="product-card-box position-relative al_box_four_template al">
    <a class="common-product-box text-center" href="{{ $product["vendor"]->slug }}/product/{{ $product["url_slug"] }}">
        <div class="img-outer-box position-relative"> <img class="blur-up lazyload" data-src="{{ $product["image_url"] }}" alt="" title="">
            <div class="pref-timing"> </div>
        </div>
        <div class="media-body align-self-start">
            <div class="inner_spacing px-0">
                <div class="product-description mt-2 text-left">
                    <div class="al_productName">
                        <p class="al_vendorName mb-0 ellips">{{ $product["vendor_name"] }}</p>
                    </div>
                    <h6 class="card_title m-0 ellips">{{ $product["title"] }}</h6> 
                    @if($client_preference_detail && $client_preference_detail->rating_check==1)
                    @if($product["averageRating"] >0)

                    @endif @endif
                    <div class="product-description_list">
                        <p class="al_ratingNumber mb-0">
                            <span class="Stars" style="--rating: {{ $product["averageRating"] }}" aria-label="Rating of this product is {{ $product["averageRating"] }} out of 5."></span>
                        </p>
                        <p class="al_product_category mb-0">
                            <span>{{__('In') . $product["category"]}} </span>
                        </p>
                    </div>
                    @if($is_service_product_price_from_dispatch_forOnDemand!=1) 
                    <div class="d-flex align-items-center justify-content-end al_clock px-2">
                        <b>@if($product['inquiry_only']==0) {!!$product["price"] ?? ''!!} @endif</b>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </a>
</div>