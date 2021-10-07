<style type="text/css">
.add-category input[type="radio"] {
    display: none;
}
.add-category label{
    cursor: pointer;
}
/* .add-category input:checked ~ label {
    box-shadow: 0 0px 8px rgb(67 190 225 / 55%);
} */
</style>
<div class="row">
    <div class="col-md-12">
        <div class="row mb-6">
            <div class="col-sm-2">
                <label>{{ __("Upload Category Icon") }}</label>
                <input type="file" accept="image/*" data-plugins="dropify" name="icon" class="dropify" data-default-file="" />
                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 150x150</label>
            </div>
            <div class="col-sm-4">              
                <label>{{ __("Upload Category image") }}</label>
                <input type="file" accept="image/*" data-plugins="dropify" name="image" class="dropify" data-default-file="" />
                <label class="logo-size d-block text-right mt-1">{{ __("Image Size") }} 1026x200</label>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-md-6">
                         <div class="form-group" id="slugInput">
                            {!! Form::label('title', __('URL Slug'),['class' => 'control-label']) !!} 
                            {!! Form::text('slug', null, ['class'=>'form-control', 'required' => 'required', 'onkeypress' => "return alphaNumeric(event)", 'id' => 'slug']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                            {!! Form::hidden('login_user_type', session('login_user_type'), ['class'=>'form-control']) !!}
                            {!! Form::hidden('login_user_id', auth()->user()->id, ['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('title', __('Select Parent Category'),['class' => 'control-label']) !!}
                            <select class="selectize-select form-control parent-category" id="cateSelectBox" name="parent_cate">
                                <option value="">{{ __("Select") }}</option>
                                @foreach($parCategory as $pc)
                                    <option value="{{$pc->id}}">{{$pc->translation_one ? ucfirst($pc->translation_one['name']) : ' '}}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('title', __('Visible In Menus'),['class' => 'control-label']) !!} 
                            <div>
                                <input type="checkbox" data-plugin="switchery" name="is_visible" class="form-control switch_menu" data-color="#43bee1" checked='checked'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('title', __('Wishlist'),['class' => 'control-label']) !!} 
                            <div>
                                <input type="checkbox" data-plugin="switchery" name="show_wishlist" class="form-control wishlist_switch" data-color="#43bee1" checked='checked'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" id="addProductHide">
                        <div class="form-group">
                            {!! Form::label('title', __('Can Add Products'),['class' => 'control-label']) !!} 
                            <div>
                                <input type="checkbox" data-plugin="switchery" name="can_add_products" class="form-control add_product_switch" data-color="#43bee1" checked='checked'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="addDispatcherHide" style="display: none;">
                        <div class="form-group mb-0">
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3 add-category">
            @foreach($typeArray as $k => $type)
                @if($type->title == 'Celebrity' && $preference->celebrity_check == 0)
                    @continue
                @endif
                <div class="col-sm-6 col-md-2">
                  <div class="card p-0 text-center select-category" id="tooltip-container">
                     <input class="form-check-input type-select" for="add" type="radio" id="type_id_{{$type->id}}" {{$type->id == 1 ? 'checked=""' : " "}} name="type_id" @if($category->type_id == $type->id) checked @endif value="{{$type->id}}">
                     <label for="type_id_{{$type->id}}" class="card-body p-0 mb-0">
                        <div class="category-img">
                            <img src="{{url('images/'.$type->image)}}" alt="">
                        </div>
                        <div class="form-check form-check-info p-2">
                            <h5 class="mt-0" for="customradio5">{{$type->title}}</h5>
                        </div>
                     </label>
                  </div>
               </div>
           @endforeach
        </div>
        <div class="row">
            <div class="col-md-4" id="template_type_main_div" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', __('Template Type'),['class' => 'control-label']) !!}
                    <div class="row">
                        @foreach($dispatcher_warning_page_options as $dwpo => $dispatcher_warning_page_option)                       
                            <div class="col-lg-6 custom-radio radio_new mt-2">
                                <input type="radio" name="warning_page_id"
                                    value="{{$dispatcher_warning_page_option->id}}" id="dispatcher_warning_page_option_{{$dispatcher_warning_page_option->id}}" class="custom-control-input tab_bar_options">
                                <label class="custom-control-label" for="dispatcher_warning_page_option_{{$dispatcher_warning_page_option->id}}">
                                    <img class="card-img-top img-fluid" src="{{asset('images/'.$dispatcher_warning_page_option->image_path)}}" alt="Card image cap">
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4" id="warning_page_main_div" style="display:none;">
                <div class="form-group">
                    {!! Form::label('title', __('Warning Page'),['class' => 'control-label']) !!}
                    <div class="row">
                        @foreach($dispatcher_template_type_options as $dtto => $dispatcher_template_type_option)
                            <div class="col-lg-6 custom-radio radio_new mt-2">
                                <input type="radio" value="{{$dispatcher_template_type_option->id}}" id="dispatcher_template_type_option_{{$dispatcher_template_type_option->id}}" name="template_type_id" {{ ($dtto == 0) ? 'checked' : '' }} class="custom-control-input tab_bar_options">
                                <label class="custom-control-label" for="dispatcher_template_type_option_{{$dispatcher_template_type_option->id}}">
                                    <img class="card-img-top img-fluid" src="{{asset('images/'.$dispatcher_template_type_option->image_path)}}" alt="Card image cap">
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
           
        </div>
        <div class="row">
            @foreach($languages as $langs)
                <div class="col-lg-6">
                    <div class="outer_box px-3 py-2 mb-3">
                        <div class="row rowYK">
                            <h4 class="col-md-12"> {{ $langs->langName.' Language' }} </h4>
                            <div class="col-md-6">
                                <div class="form-group" id="{{ ($langs->langId == 1) ? 'nameInput' : 'nameotherInput' }}">
                                    {!! Form::label('title', __('Name'),['class' => 'control-label']) !!}
                                    @if($langs->is_primary == 1)
                                        {!! Form::text('name[]', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    @else
                                        {!! Form::text('name[]', null, ['class' => 'form-control']) !!}
                                    @endif
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                </div>
                            </div>
                            {!! Form::hidden('language_id[]', $langs->langId) !!}
                            <div class="col-md-6">
                                <div class="form-group" id="meta_titleInput">
                                    {!! Form::label('title', __('Meta Title'),['class' => 'control-label']) !!} 
                                    {!! Form::text('meta_title[]', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('title', __('Meta Description'),['class' => 'control-label']) !!} 
                                    {!! Form::textarea('meta_description[]', null, ['class'=>'form-control', 'rows' => '3']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('title', __('Meta Keywords'),['class' => 'control-label']) !!} 
                                    {!! Form::textarea('meta_keywords[]', null, ['class' => 'form-control', 'rows' => '3']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>