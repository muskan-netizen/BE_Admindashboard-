
<div class="card-box" >
    <div class="row mb-2 bg-light">
        <div class="col-8" style="margin:auto;">
            <h5 class="text-uppercase mt-0 bg-light p-2">{{ __("Variant Information") }}</h5>
        </div>
        @if($productVariants->count() > 0)
        <div class="col-4 p-2 mt-0 text-right" style="margin:auto; ">
            <button type="button" class="btn btn-info makeVariantRow"> {{ __("Make Variant Sets") }}</button>
        </div>
        @endif
    </div>
    <p>{{ __("Select or change category to get variants") }}</p>

    <div class="row" style="width:100%; overflow-x: scroll;">
        <div id="variantAjaxDiv" class="col-12 mb-2">
            <h5 class="">{{__('Variant List')}}</h5>
            <div class="row mb-2">
                @foreach($productVariants as $vk => $var)
                <div class="col-sm-3">
                    <label class="control-label">{{$var->title??null}}</label>
                </div>
                <div class="col-sm-9">
                    @foreach($var->option as $key => $opt)
                    @if(isset($opt) && !empty($opt->title) && isset($var) && !empty($var->title) )
                        <div class="checkbox checkbox-success form-check-inline pr-3">
                            <input type="checkbox" name="variant{{$var->id}}" class="intpCheck" opt="{{$opt->id.';'.$opt->title}}" varId="{{$var->id.';'.$var->title}}" id="opt_vid_{{$opt->id}}" @if(in_array($opt->id, $existOptions)) checked @endif>
                            <label for="opt_vid_{{$opt->id}}">{{$opt->title}}</label>
                        </div>
                    @endif
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>

        {{-- @if($product->has_variant == 1) --}}
        <div class="col-12" id="exist_variant_div">
            <h5 class="">{{ __("Applied Variants Set") }}</h5>
            <table class="table table-centered table-nowrap table-striped">
                <thead>
                    <th>{{ __("Image") }}</th>
                    <th>{{ __("Name") }}</th>
                    <th>{{ __("Variants") }}</th>
                    <th>{{ __("Price") }}</th>
                    <th>{{ __('Compare at price') }}</th>
                    <th>{{ __('Cost Price') }}</th>
                    <th class="check_inventory">{{ __("Quantity") }}</th>
                    <th>{{ __("Action") }}</th>
                </thead>
                <tbody id="product_tbody_{{$product->id}}">
                    @foreach($product->variant as $varnt)
                    <?php
                    $existSet = array();

                    $mediaPath = Storage::disk('s3')->url('default/default_image.png');

                    if (!empty($varnt->vimage) && isset($varnt->vimage->pimage->image)) {
                        $mediaPath = $varnt->vimage->pimage->image->path['proxy_url'] . '100/100' . $varnt->vimage->pimage->image->path['image_path'];
                    }
                    $existSet = explode('-', $varnt->sku);
                    $vsets = '';

                    foreach ($varnt->set as $vs) {
                        if(isset($vs) && !empty($vs->title)){
                            $vsets .= $vs->title . ', ';
                        }


                    }
                    ?>
                    <tr id="tr_{{$varnt->id}}">
                        <td>
                            <div class="image-upload">
                                <label class="file-input uploadImages" for="{{$varnt->id}}">
                                    <img src="{{$mediaPath}}" width="30" height="30" for="{{$varnt->id}}" />
                                </label>
                            </div>
                            <div class="imageCountDiv{{$varnt->id}}"></div>
                        </td>
                        <td>
                            <input type="hidden" name="variant_ids[]" value="{{$varnt->id}}">
                            <input type="hidden" class="exist_sets" value="{{$existSet[(count($existSet) - 1)]}}">
                            <input type="text" name="variant_titles[]" value="{{$varnt->title??null}}">
                        </td>
                        <td>{{rtrim($vsets, ', ')}}</td>
                        <td>
                            <input type="text" style="width: 70px;" name="variant_price[]" value="{{decimal_format($varnt->price)}}" onkeypress="return isNumberKey(event)">
                        </td>
                        <td>
                            <input type="text" style="width: 100px;" name="variant_compare_price[]" value="{{decimal_format($varnt->compare_at_price)}}" onkeypress="return isNumberKey(event)">
                        </td>
                        <td>
                            <input type="text" style="width: 70px;" name="variant_cost_price[]" value="{{decimal_format($varnt->cost_price)}}" onkeypress="return isNumberKey(event)">
                        </td>
                        <td class="check_inventory">
                            <input type="text" style="width: 70px;" name="variant_quantity[]" value="{{$varnt->quantity}}" onkeypress="return isNumberKey(event)">
                        </td>
                        <td>
                            <a href="javascript:void(0);" data-varient_id="{{$varnt->id}}" class="action-icon deleteExistRow">
                                <i class="mdi mdi-delete"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- @endif --}}
        <div id="variantRowDiv" class="col-12"></div>
    </div>
</div>
