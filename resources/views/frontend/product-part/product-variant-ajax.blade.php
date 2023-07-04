@php
    $t_var = count($availableSets);
@endphp
@foreach($availableSets as $key => $sets)
    @php
        $notChecked = 1;
        $first_iteration = true;
    @endphp
    @if($sets->variant_detail->title != $selected_variant_title)
    @php
        $flag = 0;
    @endphp
        <div class="size-box">
            <ul class="productVariants">
                <li class="firstChild">{{$sets->variant_detail->title}}</li>
                <li class="row otherSize">
                    @foreach($sets->option2 as $kk => $optn)
                        <?php $var_id = $optn->variant_type_id;
                        $opt_id = $optn->variant_option_id;
                        if($optn->quantity > 0 && @$notChecked){
                            $flag = 1;
                        }
                        ?>
                        <label class="radio d-inline-block txt-14 col-4 position-relative pl-4 pr-2 {{ $optn->quantity == 0 ? 'label-disabled' : '' }} @if(@$notChecked && @$flag) radio-active @endif" data-title="{{ $key === 0 ? $optn->title : '' }}">
                            <span class="color_name ellipsis">{{$optn->title}}</span>
                            <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}" data-variant-id="{{$optn->product_variant_id}}" data-variant-price="{{$optn->price}}" vid="{{$var_id}}" optid="{{$opt_id}}" data-option-title="{{$sets->variant_detail->title}}" value="{{$opt_id}}" type="radio" class="dataVar{{$var_id}} changeVariant_{{$sets->variant_detail->title}} selected_variant {{ $key != $t_var - 1 ? 'changeVariant' : '' }}" @if(@$notChecked  && @$flag) checked @endif>
                            @if($sets->variant_detail->type == 2)
                                <span class="color_var var_{{$var_id}}" style="padding:8px; border: 1px dotted #CCC; background:{{$optn->hexacode}};" data-id="{{$var_id}}"></span>
                            @else
                                <span class="color_var radio_var radio_{{$var_id}}" style="padding:8px; border: 1px dotted #CCC; background:{{$optn->hexacode}};" data-id="{{$var_id}}"></span>
                            @endif
                           
                            {{-- <span class="checkround"></span> --}}
                        </label>
                        @if(($optn->quantity > 0))
                        @php
                             $notChecked = 0;
                        @endphp
                    @endif
                       
                    @endforeach
                </li>
            </ul>
        </div>
    @endif
@endforeach
