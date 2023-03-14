<div class="col">
    <div class="deals-product product-card-box position-relative text-center al_custom_vendors_sec"  >
        <a class="suppliers-box d-block" href="{{ $product["vendor"]->slug }}/product/{{ $product["url_slug"] }}">
            <div class="suppliers-img-outer position-relative ">
                <img  class="fluid-img mx-auto blur-up lazyload" data-src="{{ $product['image_url']  }}" alt="" title="">
            </div>
            <div class="supplier-rating">
                <h4>{{  $product['title'] }}</h4>
                
                <h5>{{ (($product['discount_percentage'])>0)?$product['discount_percentage'].' %OFF':''}}</h5>
                <a href="{{ $product["vendor"]->slug }}/product/{{ $product["url_slug"] }}">SHOP NOW</a>
            </div>
        </a>
    </div>
</div>