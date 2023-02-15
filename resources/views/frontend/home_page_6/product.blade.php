<div class="product-card-box position-relative al_box_third_template al">
    <a class="common-product-box text-center" href="{{ $product["vendor"]->slug }}/product/{{ $product["url_slug"] }}">
       <div class="img-outer-box position-relative">
                <img class="blur-up lazyload" data-src="{{ $product["image_url"] }}" alt="" title="">
          <div class="pref-timing"> </div>
       </div>
       <div class="media-body align-self-start">
          <div class="inner_spacing px-0">
             <div class="product-description">
                <div class="d-flex align-items-center justify-content-between">
                   <h6 class="card_title ellips">{{ $product["title"] }}</h6> 
                   @if($client_preference_detail && $client_preference_detail->rating_check==1 && $product["averageRating"] >0)
                      <span class="rating-number"><i class="fa fa-star"></i> {{$product["averageRating"]}}</span>  
                   @endif
                </div>
                <div class="product-description_list border-bottom">
                   <p>
                      {{ $product["vendor_name"] }}
                   </p>
                   <p class="al_product_category">
                      <span>
                      {{__('In') . $product["category"]}}
                      </span>
                   </p>
                </div>
                @if($is_service_product_price_from_dispatch_forOnDemand!=1) 
                  <div class="d-flex align-items-center justify-content-between al_clock pt-2">
                     <b>@if($product['inquiry_only']==0) {!!$product["price"] ?? ''!!}  @endif</b>
                  </div>
                @endif
             </div>
          </div>
       </div>
    </a>
 </div>