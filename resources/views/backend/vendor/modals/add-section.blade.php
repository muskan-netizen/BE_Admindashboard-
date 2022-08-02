<div id="add_section" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header border-bottom">
               
                <h4 class="modal-title">{{ __("Add Section") }} </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <form id="save_banner_form" class="al_overall_form" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="new_model" value="1">
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group" id="nameInput">
                                {!! Form::label('title', __('Section Heading'),['class' => 'control-label']) !!}
                                {!! Form::text('name', null, ['class'=>'form-control']) !!}
                                <span class="invalid-feedback" role="alert">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>

                    
                          
                         
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect waves-light " submitEditForm id="add_vendor_form">{{ __('Submit') }}</button>
                </div>
            </form>
           
        </div>
    </div>
</div>


