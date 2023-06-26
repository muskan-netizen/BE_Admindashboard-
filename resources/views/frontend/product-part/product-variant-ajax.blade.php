@foreach($availableSets as $key => $sets)
    <ul class="productVariants">
        <li class="firstChild">{{$sets->title}}</li>
        <li class="row otherSize">
            @foreach($sets->option2 as $kk => $optn)
                <?php $var_id = $optn->variant_type_id;
                $opt_id = $optn->variant_option_id;
                ?>
                <label class="radio d-inline-block txt-14 col-4 position-relative pl-4 pr-2" data-title="{{ $key === 0 ? $optn->title : '' }}">
                    <span class="color_name ellipsis">{{$optn->title}}</span>
                    @if($sets->type == 2)
                        <span class="color_var var_{{$var_id}}" style="padding:8px; border: 1px dotted #CCC; background:{{$optn->hexacode}};" data-id="{{$var_id}}"></span>
                    @else
                        <span class="color_var radio_var radio_{{$var_id}}" style="padding:8px; border: 1px dotted #CCC; background:#fff;" data-id="{{$var_id}}"></span>
                    @endif
                    <input id="lineRadio-{{$opt_id}}" name="{{'var_'.$var_id}}" vid="{{$var_id}}" optid="{{$opt_id}}" value="{{$opt_id}}" type="radio" class="changeVariant dataVar{{$var_id}}">
                    <span class="checkround"></span>
                </label>
            @endforeach
        </li>
    </ul>
@endforeach
