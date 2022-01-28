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

                <div class="row">
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
                </div>
                <div class="row sms-section">
                    <div class="col-md-12">
                        <div class="form-group" id="nameInput">
                            <label for="sms_title" class="control-label">{{__("Message")}}</label>
                            <textarea class="form-control" placeholder="{{__("Write your message")}}" name="sms_text" type="text" id="sms_text" maxlength="160"></textarea>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row email-section">
                    <div class="col-md-6">
                        <div class="form-group" id="emailTitle">
                            <label for="email_title" class="control-label">{{__("Title")}}</label>
                            <input class="form-control" placeholder="{{__("Email Title")}}" name="email_title" type="text" id="email_title">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="emailSubject">
                            <label for="email_subject" class="control-label">{{__("Subject")}}</label>
                            <input class="form-control" placeholder="{{__("Email Subject")}}" name="email_subject" type="text" id="email_subject">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="emailBody">
                            <label for="email_body" class="control-label">{{__("Email Body")}}</label>
                            <textarea class="form-control" placeholder="{{__("Write your email")}}" name="email_body" type="text" id="email_body"></textarea>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row push-section">
                    <div class="col-md-6">
                        <div class="form-group" id="pushTitle">
                            <label for="push_title" class="control-label">{{__("Title")}}</label>
                            <input class="form-control" placeholder="{{__("Title")}}" name="push_title" type="text" id="push_title">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6" id="pushimageInput">
                        <label>{{ __('Upload image') }}</label>
                        <input data-default-file="" type="file" data-plugins="dropify" name="push_image" accept="image/*" class="dropify" />
                        <label class="logo-size d-block text-right mt-1">{{ __('Image Size') }} 150x150</label>                    
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group" id="pushBody">
                            <label for="push_body" class="control-label">{{__("Description")}}</label>
                            <textarea class="form-control" placeholder={{__("Description")}} name="push_message_body" type="text" id="push_message_body"></textarea>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="pushOption">
                            <label for="push_option" class="control-label">{{__("Select Push Option")}}</label>
                            <select class="form-control" name="push_url_option" id="push_url_option">
                                <option value="1">URL</option>
                                <option value="2">Category</option>
                                <option value="3">Vendor</option>
                            </select>                            
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="pushTitle">
                            <label for="push_title" class="control-label">{{__("Push Option Value")}}</label>
                            <input class="form-control" placeholder="" name="push_url_option_value" type="text" id="push_url_option_value">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">


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