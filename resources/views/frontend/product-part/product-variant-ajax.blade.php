@foreach($availableSets as $key => $sets)
    @php
        $flag = true;
        $first_iteration = true;
    @endphp
    @if($sets->variantDetail->title != $selected_variant_title)
        <div class="size-box">
            <ul class="productVariants">
                <li class="firstChild">{{$sets->variantDetail->title}}</li>
                <li class="row otherSize">
                    @foreach($sets->option2 as $kk => $optn)
                    @if(($optn->quantity > 0) && ($flag == true))
                        @php
                            $flag = false;
                        @endphp
                    @endif
                        <?php $var_id = $optn->variant_type_id;
                        $opt_id = $optn->variant_option_id;
                        if($optn->quantity > 0){
                            $isChecked = true;
                        }
                        ?>
                        <label class="radio d-inline-block txt-14 col-4 position-relative pl-4 pr-2 {{ $optn->quantity == 0 ? 'label-disabled' : '' }} @if($flag == false && ($first_iteration)) radio-active @endif" data-title="{{ $key === 0 ? $optn->title : '' }}">
                            <span class="color_name ellipsis">{{$optn->title}}</span>
                            <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}" data-variant-id="{{$optn->product_variant_id}}" data-variant-price="{{$optn->price}}" vid="{{$var_id}}" optid="{{$opt_id}}" data-option-title="{{$sets->variantDetail->title}}" value="{{$opt_id}}" type="radio" class="dataVar{{$var_id}} changeVariant_{{$sets->variantDetail->title}} selected_variant" @if(($flag == false) && ($first_iteration)) checked @endif>
                            @if($sets->type == 2)
                                <span class="color_var var_{{$var_id}}" style="padding:8px; border: 1px dotted #CCC; background:{{$optn->hexacode}};" data-id="{{$var_id}}"></span>
                            @else
                                <span class="color_var radio_var radio_{{$var_id}}" style="padding:8px; border: 1px dotted #CCC; background:{{$optn->hexacode}};" data-id="{{$var_id}}"></span>
                            @endif
                           
                            {{-- <span class="checkround"></span> --}}
                        </label>
                        @php
                            $first_iteration = false;
                        @endphp
                    @endforeach
                </li>
            </ul>
        </div>
    @endif
@endforeach
