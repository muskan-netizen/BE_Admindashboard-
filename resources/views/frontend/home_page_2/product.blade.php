<div>
    <a class="card scale-effect text-center" href="{{ $product['vendor']->slug }}/product/{{ $product['url_slug'] }}">
        <label class="product-tag">@if($product["tag_title"] != 0) {{$product["tag_title"]}} @else {{$homePageLabel->title}}@endif </label>
        <div class="product-image">
            <img class="blur-up lazyloaded" src="{{ $product['image_url'] }}" alt="">
        </div>
        <div class="media-body align-self-center">
            <div class="inner_spacing px-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="m-0">{{ $product["title"] }}</h3>
                    @if($client_preference_detail)
                        @if($client_preference_detail->rating_check == 1)
                            @if($product["averageRating"] >0)
                                <span class="rating">{{ $product["averageRating"] }} <i class="fa fa-star text-white p-0"></i></span>
                            @endif 
                        @endif
                    @endif
                </div>
                <p>{{ $product["vendor_name"] }}</p>
                <h4>
                    @if($product["inquiry_only"] == 0)
                    {!!$product["price"] ?? ''!!}
                    @endif
                </h4>
            </div>
        </div>
    </a>
</div>