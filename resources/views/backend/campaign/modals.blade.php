<div class="modal fade bd-example-modal-lg addModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __('Add Campaign') }}</h4><br>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_loyality_form" class="p-3" enctype="multipart/form-data">
                @csrf
                {{-- <div class="row">
                    <div class="col-md-6" id="imageInput">
                        <label>{{ __('Upload image') }}</label>
                        <input data-default-file="" type="file" data-plugins="dropify" name="image" accept="image/*" class="dropify" />
                        <label class="logo-size d-block text-right mt-1">{{ __('Image Size') }} 150x150</label>                    
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </div> --}}

                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group" id="nameInput">
                            {!! Form::label('title', __('Title'),['class' => 'control-label']) !!}
                            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        {{__('Notification Type')}}
                        <div class="form-group" id="nameInput">
                            {!! Form::label('sms', __('SMS'),['class' => 'control-label']) !!}
                            {!! Form::radio('type', 1, ['class' => 'form-control']) !!}

                            {!! Form::label('email', __('Email'),['class' => 'control-label']) !!}
                            {!! Form::radio('type', 2, ['class' => 'form-control']) !!}

                            {!! Form::label('push', __('Push Notification'),['class' => 'control-label']) !!}
                            {!! Form::radio('type', 3, ['class' => 'form-control']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    {{-- <div class="col-md-6" id="slugInput">
                        <div class="form-group">
                            {!! Form::label('title', __('Slug'),['class' => 'control-label']) !!} 
                            {!! Form::text('slug', null, ['class'=>'form-control', 'required' => 'required', 'onkeypress' => "return alphaNumeric(event)", 'id' => 'slug']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div> --}}
                    
                    <!-- <div class="col-md-6">
                        <div class="form-group" id="emailInput">
                            {!! Form::label('title', 'Email *',['class' => 'control-label']) !!}
                            {!! Form::text('email', null, ['class' => 'form-control']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" id="phone_numberInput">
                            {!! Form::label('title', 'Phone number',['class' => 'control-label']) !!}
                            {!! Form::text('phone_number', null, ['class' => 'form-control', 'placeholder' => 'Phone Number' , 'onkeypress' => 'return isNumberKey(event);']) !!}
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div> -->
                    <div class="col-md-12">
                        <div class="form-group" id="addressInput">
                            {!! Form::label('title', __('Description'),['class' => 'control-label']) !!}
                            <!-- {!! Form::text('address', null, ['class' => 'form-control']) !!} -->
                            <textarea class='form-control' rows="3" name="description"></textarea>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>

                    

                </div>

                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-info waves-effect waves-light submitAddForm">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="edit-loyalty-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h4 class="modal-title">{{ __("Edit Celebrity") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="update_loyality_form" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editLoyaltyBox">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light submitEditForm">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>