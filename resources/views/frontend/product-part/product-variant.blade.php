<div id="product_variant_options_wrapper">
        @php
            $selectedVariant = isset($product->variant[0]) ? $product->variant[0]->id : 0;
            if($product->minimum_order_count > 0)
            $product->minimum_order_count = $product->minimum_order_count;
            else
            $product->minimum_order_count = 1;
        @endphp
        @foreach($product->variantSet as $key => $variant)
            @if($variant->type == 1 || $variant->type == 2)
            <div class="size-box">
                <ul class="productVariants">
                    <li class="firstChild">{{$variant->title}}</li>
                    <li class="row otherSize">
                        {{-- @php
                                echo "<pre/>";
                            print_r($variant->option2->toArray());
                        @endphp --}}
                        @foreach($variant->option2 as $k => $optn)
                        <?php $var_id = $variant->variant_type_id;
                        $opt_id = $optn->variant_option_id;
                        $checked = ($selectedVariant == $optn->product_variant_id) ? 'checked' : '';
                        ?>
                            <label class="radio d-inline-block txt-14 col-4 position-relative pl-4 pr-2"> <span class="color_name ellipsis">{{$optn->title}}</span>
                              @if($variant->type == 2)
                            <span class="color_var var_{{$var_id}}" style="padding:8px; border: 1px dotted #CCC; background:{{$optn->hexacode}};" data-id="{{$var_id}}"></span>
                            @else
                            <span class="color_var radio_var radio_{{$var_id}}" style="padding:8px; border: 1px dotted #CCC; background:#fff;" data-id="{{$var_id}}"></span>
                                @endif
                            <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}" vid="{{$var_id}}" optid="{{$opt_id}}" value="{{$opt_id}}" type="radio" class="changeVariant dataVar{{$var_id}}" {{$checked}}>
                            <span class="checkround"></span>
                        </label>
                        @endforeach
                    </li>
                </ul>
            </div>
            @else
            @endif
        @endforeach
</div>