
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
    {{-- <td>{{rtrim($vsets, ', ')}}</td> --}}
    <td>
        <input type="text" style="width: 70px;" name="variant_price[]" value="{{decimal_format($varnt->price)}}" onkeypress="return isNumberKey(event)">
    </td>
    {{-- <td>
        <input type="text" style="width: 100px;" name="variant_minimum_duration[]" value="{{decimal_format($varnt->minimum_duration)}}" onkeypress="return isNumberKey(event)">
    </td> --}}
    <td>
        <input type="text" style="width: 70px;" name="variant_incremental_price[]" value="{{decimal_format($varnt->incremental_price)}}" onkeypress="return isNumberKey(event)">
    </td>
    {{-- <td class="check_inventory">
        <input type="text" style="width: 70px;" name="variant_quantity[]" value="{{$varnt->quantity}}" onkeypress="return isNumberKey(event)">
    </td> --}}
    <td>
        
        <a href="javascript:void(0);" data-varient_id="{{$varnt->id}}" class="action-icon deleteExistRow">
            <i class="mdi mdi-delete"></i>
        </a>
        <a href="javascript:void(0);" data-varient_id="{{$varnt->id}}" class="action-icon viewC">
            <i class="mdi mdi-eye"></i>
        </a>
        @if(@$show)
            <a href="javascript:void(0);" data-varient_id="{{$varnt->id}}"  data-product_id="{{$product_id}}" class="action-icon product_varient_ids addExistRow">
                <i class="mdi mdi-plus"></i>
            </a>
        @else
            @if (@$key == $variant_count)
                <a href="javascript:void(0);" data-varient_id="{{$varnt->id}}"  data-product_id="{{$product->id}}" class="action-icon product_varient_ids addExistRow">
                    <i class="mdi mdi-plus"></i>
                </a>
            @endif
        @endif

      
    </td>
</tr>