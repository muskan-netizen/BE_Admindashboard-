<div class="row">
    <div class="col-md-12">
        <div class="row mb-2">
            <div class="col-md-12">              
                <label>{{ __("Upload image") }}</label>
                <input type="file" accept="image/*" class="dropify" data-plugins="dropify" name="image" data-default-file="{{$brand->image['proxy_url'].'400/400'.$brand->image['image_path']}}" />
                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 350x200</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('title', __('Select Category'),['class' => 'control-label']) !!}
                    <select class="form-control selectize-select" data-toggle="select2" id="cateSelectBox" name="cate_id[]" multiple="multiple" >
                        @foreach($categories as $cate)
                            @if($brand->bc)
                                <option value="{{$cate->id}}" @foreach($brand->bc as $cat)@if($cat->category_id == $cate->id) selected @endif 
                                    @endforeach>{{$cate->translation_one['name']}}</option>
                            @else
                                <option value="{{$cate->id}}">{{$cate->translation_one['name']}}</option>
                            @endif
                        @endforeach
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
        </div>
        <div class="row rowYK">
            <div class="col-md-12">
                <h5>{{ __("Brand Title") }}</h5>
            </div>
            <div class="col-md-12" style="overflow-x: auto;">
                <table class="table table-borderless mb-0" id="edit_brand-datatable" >
                    <tr>
                        @foreach($languages as $langs)
                            <th>{{$langs->language->name}}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach($languages as $langs)
                            <td>
                                {!! Form::hidden('language_id[]', $langs->language_id) !!}
                                <input type="text" name="title[]" value="{{(isset($langs->brand_trans) && !empty($langs->brand_trans->title)) ? $langs->brand_trans->title : ''}}" class="form-control" @if($langs->is_primary == 1) required @endif>
                            </td>
                        @endforeach
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>