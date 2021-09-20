<form id="pickup-edit-section-form" action="javascript:void(0)" method="post">
    <div class="dd-item dd3-item" data-id="1" data-row-id="0">
        <div class="language-inputs w-100 style-4">
            <div class="row no-gutters align-items-center my-2">
                @foreach($langs as $key => $lang)
                @php
                $exist = 0;
                $value = '';
                @endphp
                <div class="col-md-12 mb-3">
                    <input class="form-control" type="hidden" value="{{$lang->langId}}" name="languages[]">
                    <input class="form-control" value="" type="text" name="names[]" placeholder="{{ $lang->langName }}" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="title" class="control-label">Description ({{$lang->langName}})</label>
                    <textarea class="form-control description_ck"  rows="5" name="description[]" id="description{{$key}}" cols="50"></textarea>
                    <span class="text-danger error-text updatedescrpitionError"></span>
                </div>

                @endforeach
            </div>
        </div>
        <div class="px-1 mb-3">
           <label class="mb-2 d-block">Categories</label>
           <select class="form-control select2-multiple" required id="categories" name="categories[]" data-toggle="select2"  data-placeholder="Choose ...">
                @foreach ($all_pickup_category as $category)
                <option value="{{$category->id}}">{{$category->translation_one->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="px-1 d-flex mb-3">
            <label class="mb-2 d-block mr-4">Toggle</label>
            <input type="checkbox"  data-plugin="switchery" name="is_active" class="chk_box2" data-color="#43bee1">
        </div>

       

        <div class="mt-3 mb-2">
            <button class="btn btn-info waves-effect waves-light text-center w-100"  type="submit" id="submit_exist_pickup_section">{{ __('Update') }}</button>
        </div>
    </div>
</form>