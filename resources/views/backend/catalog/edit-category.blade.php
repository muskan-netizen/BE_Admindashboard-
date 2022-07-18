
<div class="row ">    
<div class="col-md-12">
    <div class="row border-bottom">
        <div class="col-md-3">
            @csrf
            <div class="form-group" id="slugInputEdit">
                {!! Form::label('title', __('URL Slug'),['class' => 'control-label']) !!}
                {!! Form::text('slug', $category->slug, ['class'=>'form-control','id' => 'slug', 'onkeypress' => "return alphaNumeric(event)"]) !!}
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
                {!! Form::hidden('login_user_type', session('preferences.login_user_type'), ['class'=>'form-control']) !!}
                {!! Form::hidden('login_user_id', auth()->user()->id, ['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('title', __('Select Parent Category'),['class' => 'control-label']) !!}
                <select class="selectize-select1 form-control parent-category" id="cateSelectBox" name="parent_cate">
                    @foreach($parCategory as $pc)
                    @if($pc->translation_one)
                    <option value="{{$pc->id}}" {{ ($pc->id == $category->parent_id) ? 'selected' : '' }}> {{ucfirst($pc->translation_one['name'])}}</option>
                    @endif
                    @endforeach
                </select>
                <span class="invalid-feedback" role="alert">
                    <strong></strong>
                </span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('title', __('Visible In Menus'),['class' => 'control-label']) !!}
                <div>
                    @if($category->is_visible == '1')
                    <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control edit-switch_menu" data-color="#43bee1" checked='checked'>
                    @else
                    <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control edit-switch_menu" data-color="#43bee1">
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('title', __('Show Wishlist'),['class' => 'control-label']) !!}
                <div>
                    @if($category->show_wishlist == '1')
                    <input type="checkbox" data-plugin="switchery" name="show_wishlist" class="form-control edit-wishlist_switch" data-color="#43bee1" checked='checked'>
                    @else
                    <input type="checkbox" data-plugin="switchery" name="show_wishlist" class="form-control edit-wishlist_switch" data-color="#43bee1">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" style="{{ ($category->type_id != 2) ? 'display:none;' : '' }}" id="editDispatcherHide">
            <div class="form-group mb-0">
            </div>
        </div>
    </div>
    <div class="row border-bottom category-icon-image pt-1">
        <div class="col-md-3">
            <label>{{ __("Category Icon") }}</label>
            <input type="file" accept="image/*" data-plugins="dropify" name="icon" class="dropify" data-default-file="{{$category->icon['proxy_url'].'80/80'.$category->icon['image_path']}}" />
            <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 150x150</label>
        </div>
        <div class="col-md-3">
            <label>{{ __("Hover Icon") }}</label>
            <input type="file" accept="image/*" data-plugins="dropify" name="icon_two" class="dropify" data-default-file="{{ !is_null($category->icon_two ) ? $category->icon_two['proxy_url'].'80/80'.$category->icon_two['image_path'] : ''}}" />
            <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 150x150</label>
        </div>
        <div class="col-md-6">
            <label>Banner image</label>
            <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="{{$category->image['proxy_url'].'1000/200'.$category->image['image_path']}}" />
            <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 1370x300</label>
        </div>            
    </div>
    <div class="row border-bottom pt-1">
        <div class="px-2 ">
            <div class="mb-1 Category-select_option">
                <select class="form-control w-auto" id="client-cat-language">
                    @foreach($category->translationSetUnique as $trans)
                        <option value="{{ $trans->language_id }}">{{ $trans->langName.' Language' }}</option>
                    @endforeach
                    @if(count($langIds) != count($existlangs))
                        @foreach($languages as $langs)
                            @if(!in_array($langs->langId, $existlangs) && in_array($langs->langId, $langIds))
                                <option value="{{ $langs->langId }}">{{ $langs->langName.' Language' }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="row rowYK">
                @foreach($category->translationSetUnique as $trans)
                <div class="col-md-6">
                    <div class="form-group" id="nameInputEdit">
                        <label for="title" class="control-label">Name</label>
                            <input class="form-control" required="required" name="cat_lang[name]" id="cat-lang-name" type="text" value="{{$trans->name}}">
                            <span class="invalid-feedback" role="alert"><strong></strong></span>
                    </div>
                    <div class="form-group">
                        <label for="title" class="control-label">Meta Description</label>
                        <textarea class="form-control" rows="3" name="cat_lang[meta_description]" id="cat-lang-meta-description" cols="50">{{$trans->meta_description}}</textarea>
                    </div>
                </div>
                <input type="hidden" id="category_id" value="{{$category->id}}">
                <input name="cat_lang[language_id]" id="cat-lang-language-id" type="hidden" value="{{$trans->langId}}">
                <input name="cat_lang[trans_id]" id="cat-lang-trans-id" type="hidden" value="{{$trans->id}}">
                <div class="col-md-6">
                    <div class="form-group" id="meta_titleInput">
                        <label for="title" class="control-label">Meta Title</label>
                        <input class="form-control" name="cat_lang[meta_title]" id="cat-lang-meta-title" type="text" value="{{$trans->meta_title}}">
                    </div>
                    <div class="form-group">
                        <label for="title" class="control-label">Meta Keywords</label>
                        <textarea class="form-control" rows="3" name="cat_lang[meta_keywords]" id="cat-lang-meta-keywords" cols="50">{{$trans->meta_keywords}}</textarea>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <input type="hidden" id="cateId" url="{{route('category.update', $category->id)}}">
    <div class="row mt-3 edit-category modal-category-list">
        @foreach($typeArray as $type)
        @if($type->title == 'Celebrity' && $preference->celebrity_check == 0)
        @continue
        @endif
        <div class="col-sm">
            <div class="card p-0 text-center select-category" id="tooltip-container">
                <input class="form-check-input type-select" for="edit" type="radio" id="type_id_{{$type->id}}" name="type_id" @if($category->type_id == $type->id) checked @endif value="{{$type->id}}">
                <label for="type_id_{{$type->id}}" class="card-body p-0 mb-0">
                    <div class="category-img">
                        <img style="height:208px;" src="{{url('images/'.$type->image)}}" alt="">
                    </div>
                    <div class="form-check form-check-info modal-category-btm-title p-2">
                        <h6 for="customradio5">{{$type->title}}</h6>
                        <p class="add-cat-text text-left">Lorem ipsum... </p>
                    </div>
                </label>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row" id="additional-fields-dv" style="display:none">
        <div class="px-3 py-2 mb-3">
            <div class="row rowYK">
                <h4 class="col-md-12">{{ __("Additional Fields") }}</h4>
                <div class="col-md-12">
                    <div class="row w-100" style="{{($category->type_id != 1) ? 'display:none;' : ''}}" id="editProductHide">
                        <div class="form-group">
                            {!! Form::label('title', __('Can Add Products'),['class' => 'control-label']) !!}
                            <div>
                                @if($category->can_add_products == '1')
                                <input type="checkbox" data-plugin="switchery" class="form-control edit-add_product_switch" data-color="#43bee1" name="can_add_products" checked='checked'>
                                @else
                                <input type="checkbox" data-plugin="switchery" class="form-control edit-add_product_switch" data-color="#43bee1" name="can_add_products">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div style="{{($category->type_id != 1) ? 'display:none;' : ''}}" class="border-bottom cat-banners pt-1">
                        <div class="row w-100">
                            <div class="col">
                                <label>{{ __("Banner 1") }}</label>
                                <input type="file" accept="image/*" data-plugins="dropify" name="icon" class="dropify" data-default-file="" />
                                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 1370x300</label>
                            </div>
                            <div class="col">
                                <label>{{ __("Banner 2") }}</label>
                                <input type="file" accept="image/*" data-plugins="dropify" name="icon_two" class="dropify" data-default-file="" />
                                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 1370x300</label>
                            </div>
                            <div class="col">
                                <label>{{ __("Banner 3") }}</label>
                                <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 1370x300</label>
                            </div>            
                            <div class="col">
                                <label>{{ __("Banner 4") }}</label>
                                <input type="file" accept="image/*" data-plugins="dropify" name="icon_two" class="dropify" data-default-file="" />
                                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 1370x300</label>
                            </div> 
                            <div class="col">
                                <label>{{ __("Banner 5") }}</label>
                                <input type="file" accept="image/*" data-plugins="dropify" name="icon_two" class="dropify" data-default-file="" />
                                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 1370x300</label>
                            </div>
                        </div>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script>
$(function() {

    var inputs = $('input.radio-none');
    var checked = inputs.filter(':checked').val();

    inputs.on('click', function() {

        if ($(this).val() === checked) {

            $(this).prop('checked', false);

            checked = '';

        } else {
            $(this).prop('checked', true);
            checked = $(this).val();

        }
    });

});
</script>