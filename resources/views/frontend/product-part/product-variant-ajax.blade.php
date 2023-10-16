
@php
    $t_var = count($availableSets);
@endphp
{{-- @dd($is_variant_checked) --}}
@foreach($availableSets as $key => $sets)
    @php
        $notChecked = 1;
        $first_iteration = true;
    @endphp
    @if($sets->variant_detail->title != $selected_variant_title)
    @php
        $flag = 0;
    @endphp
        <div class="size-box row_{{$key+1}}" >
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
                        <label class="radio d-inline-block txt-14 col-4 position-relative pl-4 pr-2 label_{{$key}} {{ $optn->quantity == 0 ? 'label-disabled' : '' }} @if(@$notChecked && @$flag) radio-active @endif" data-title="{{ $key === 0 ? $optn->title : '' }}">
                            <span class="color_name ellipsis">{{$optn->title}}</span>
                            <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}" data-variant-id="{{$optn->product_variant_id}}" data-variant-price="{{$optn->price}}" vid="{{$var_id}}" optid="{{$opt_id}}" data-option-title="{{$sets->variant_detail->title}}" value="{{$opt_id}}" type="radio" class="dataVar{{$var_id}} changeVariant_{{$sets->variant_detail->title}} selected_variant selected_variant_{{$optn->product_variant_id}} changeVariant input_{{$key}}" @if(@$notChecked  && @$flag) checked @endif @if (!empty($is_variant_checked) && in_array($optn->product_variant_id, $is_variant_checked)) checked @endif data-row-key = {{$key+1}}>
                               @if($keyss==0)
                                <span class="span_{{$key}} color_var opt_{{$opt_id}} var_{{$var_id}}   @if ($kk==0) radio-active @endif" style="padding:8px; border: 1px dotted #CCC; background:{{$optn->hexacode}};" data-id="{{$var_id}}"  ></span>
                               @else
                                <span class="span_{{$key}} color_var opt_{{$opt_id}} var_{{$var_id}}   @if ((!empty($is_variant_checked) && in_array($optn->product_variant_id, $is_variant_checked))  ) radio-active @endif" style="padding:8px; border: 1px dotted #CCC; background:{{$optn->hexacode}};" data-id="{{$var_id}}"  ></span>

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
