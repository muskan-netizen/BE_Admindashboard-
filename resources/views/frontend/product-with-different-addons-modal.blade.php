<div class="modal-header pb-0">
    <h5 class="modal-title" id="customize_repeated_itemLabel">{{__('Customization for ').$product->translation_name}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @forelse($data->products as $prod)
        <div class="row cart-box-outer product_row classes_wrapper no-gutters mb-3" data-p_sku="{{ $prod->sku }}" data-slug="{{ $prod->url_slug }}">
            <div class="col-2">
                <div class="class_img product_image">
                    <img src="{{ $prod->product_image }}" alt="{{ $prod->translation_title }}">
                </div>
            </div>
            <div class="col-10">
                <div class="row price_head pl-2">
                    <div class="col-sm-12 pl-2">
                        <div class="d-flex align-items-start justify-content-between">    
                            <h5 class="mt-0">
                                {{$prod->translation_title}} 
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
                                if(count($data->addOn) > 0){
                                    $isAddonExist = 1;
                                }
                            @endphp

                            @foreach($data->variant as $var)
                                @if(isset($var->checkIfInCart) && (count($var->checkIfInCart) > 0))
                                    @php
                                        $productVariantInCart = 1;
                                        $productVariantIdInCart = $var->checkIfInCart['0']['variant_id'];
                                        $cartProductId = $var->checkIfInCart['0']['id'];
                                        $cart_id = $var->checkIfInCart['0']['cart_id'];
                                        // $variant_quantity = $var->checkIfInCart['0']['quantity'];
                                        $variant_quantity = 0;
                                        $vendor_id = $data->vendor_id;
                                        $product_id = $data->id;
                                        $variant_price = $var->price * $data->variant_multiplier;
                                        if(count($var->checkIfInCart) > 1){
                                            $productVariantInCartWithDifferentAddons = 1;
                                        }
                                        foreach($var->checkIfInCart as $cartVar){
                                            $variant_quantity = $variant_quantity + $cartVar['quantity'];
                                        }
                                    @endphp
                                    @break;
                                @endif
                            @endforeach
                            
                            @if($vendor->is_vendor_closed == 0)

                                @php
                                    $is_customizable = false;
                                    if( ($isAddonExist > 0) && ($variant_quantity > 0 || $prod->sell_when_out_of_stock == 1) ){
                                        $is_customizable = true;
                                    }
                                @endphp

                                @if($productVariantInCart > 0)
                                    {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a> --}}
                                    <a class="add-cart-btn add_vendor_product" style="display:none;" id="add_button_href{{$cartProductId}}" 
                                        data-variant_id="{{$productVariantIdInCart}}" 
                                        data-add_to_cart_url="{{ route('addToCart') }}" 
                                        data-vendor_id="{{$vendor_id}}" 
                                        data-product_id="{{$product_id}}" 
                                        data-addon="{{$isAddonExist}}" 
                                        href="javascript:void(0)">Add</a>
                                    <div class="number" id="show_plus_minus{{$cartProductId}}">
                                        <span class="minus qty-minus-product {{$productVariantInCartWithDifferentAddons ? 'remove-customize' : ''}}"
                                            data-variant_id="{{$productVariantIdInCart}}" 
                                            data-parent_div_id="show_plus_minus{{$cartProductId}}" 
                                            data-id="{{$cartProductId}}" 
                                            data-base_price="{{$variant_price}}" 
                                            data-vendor_id="{{$vendor_id}}"
                                            data-product_id="{{$product_id}}" 
                                            data-addon="{{$isAddonExist}}">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </span>
                                        <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" placeholder="1" type="text" value="{{$variant_quantity}}" class="input-number" step="0.01" id="quantity_ondemand_{{$cartProductId}}" readonly>
                                        <span class="plus qty-plus-product {{$is_customizable ? 'repeat-customize' : ''}}" 
                                            data-variant_id="{{$productVariantIdInCart}}" 
                                            data-id="{{$cartProductId}}" 
                                            data-base_price="{{$variant_price}}" 
                                            data-vendor_id="{{$vendor_id}}"
                                            data-product_id="{{$product_id}}" 
                                            data-cart="{{$cart_id}}"
                                            data-addon="{{$isAddonExist}}">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                @else
                                
                                    @if($variant_quantity > 0 || $prod->sell_when_out_of_stock == 1)
                                    {{-- <a class="add_vendor-fav" href="#"><i class="fa fa-heart"></i></a> --}}
                                    <a class="add-cart-btn add_vendor_product" id="aadd_button_href{{$data->id}}" 
                                        data-variant_id="{{$data->variant[0]->id}}" 
                                        data-add_to_cart_url="{{ route('addToCart') }}" 
                                        data-vendor_id="{{$data->vendor_id}}" 
                                        data-product_id="{{$data->id}}" 
                                        data-addon="{{$isAddonExist}}" 
                                        href="javascript:void(0)">Add</a>
                                    <div class="number" style="display:none;" id="ashow_plus_minus{{$data->id}}">
                                        <span class="minus qty-minus-product"
                                            data-parent_div_id="show_plus_minus{{$data->id}}" 
                                            data-id="{{$data->id}}" 
                                            data-base_price="{{$data->variant_price * $data->variant_multiplier}}" 
                                            data-vendor_id="{{$data->vendor_id}}">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </span>
                                        <input style="text-align:center;width: 80px;margin:auto;height: 24px;padding-bottom: 3px;" id="quantity_ondemand_d{{$data->id}}" readonly placeholder="1" type="text" value="1" class="input-number input_qty" step="0.01">
                                        <span class="plus qty-plus-product"  
                                            data-id="" 
                                            data-base_price="{{$data->variant_price * $data->variant_multiplier}}" 
                                            data-vendor_id="{{$data->vendor_id}}">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    @else
                                    <span class="text-danger">Out of stock</span>
                                    @endif
                                @endif
                                @if( $is_customizable )
                                    <div class="customizable-text">customizable</div>
                                @endif
                            @endif
                            </div>
                        </div>
                        @if($prod->averageRating > 0)
                            <div class="rating-text-box">
                                <span>{{$prod->averageRating}} </span>
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </div>
                        @endif
                        <p class="mb-1 product_price">
                            {{Session::get('currencySymbol').(number_format($prod->variant_price * $prod->variant_multiplier, 2, '.', ''))}} 
                            @if($prod->variant[0]->compare_at_price > 0 )
                                <span class="org_price ml-1 font-14">{{Session::get('currencySymbol').(number_format($prod->variant[0]->compare_at_price * $prod->variant_multiplier, 2, '.', ''))}}</span>
                            @endif
                        </p>
                        
                        <div id="variant_response">
                            <span class="text-danger mb-2 mt-2 font-14"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        @endforelse
</div>
<div class="modal-footer flex-nowrap justify-content-center align-items-center">
    <button type="button" class="btn btn-solid black-btn" id="repeat_item_new_addon_btn" data-dismiss="modal">{{__('Add new')}}</button>
    <button type="button" class="btn btn-solid" id="repeat_item_btn">{{__('Repeat last')}}</button>
</div>