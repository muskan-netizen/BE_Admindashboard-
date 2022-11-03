


<div class="row"> 
    <div class="col-md-12">
        <div class="row">
            
        <select class="form-control select2-multiple" id='product_ids' data-toggle="select2" name="product_ids[]" multiple data-placeholder="Choose ..." required>
            <option value="">{{ __("Select Product") }}</option>
            @foreach($products as $product)
            <option value="{{$product->id}}" @if(@$selectedProducts && in_array($product->id, $selectedProducts)) selected="selected" @endif>
               {{$product->translation[0]->title}}
            </option>
            @endforeach
        </select>


        </div>
        
    </div>
</div>

<script>

$('.select2-multiple').select2();
</script>