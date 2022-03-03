
@extends('layouts.vertical', ['title' =>  'Customer' ])


@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                @if(isset($subadmin))
                <h4 class="page-title">{{ __('Update Customer') }}</h4>
                @endif
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(isset($subadmin))
                    <form id="UpdateSubadmin"  enctype="multipart/form-data" method="post" action="{{route('customer.new.update', $subadmin->id)}}"
                        enctype="multipart/form-data">
                        @method('PUT')
                        @else
                        <form id="StoreSubadmin"  enctype="multipart/form-data" method="post" action="{{route('acl.store')}}"
                            enctype="multipart/form-data">
                            @endif
                            @csrf

                            <div class=" row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="control-label">{{ __("NAME") }}</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{ old('name', $subadmin->name ?? '')}}" placeholder="John Doe" readonly>
                                        @if($errors->has('name'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="control-label">{{ __('EMAIL') }}</label>
                                         <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', $subadmin->email ?? '')}}" placeholder="Enter email address" readonly>
                                        @if($errors->has('email'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="control-label">{{ __("Status") }}</label>
                                        <select name="status" class="form-control">
                                            <option value="1" @if($subadmin->status==1) selected @endif>{{ __("Active") }}</option>
                                            <option value="3" @if($subadmin->status==3) selected @endif>{{ __("Inactive") }}</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="control-label">{{ __("Provide Admin Access") }}</label>
                                         <select name="is_admin" class="form-control">
                                            <option value="0" @if($subadmin->is_admin==0) selected @endif>{{ __("No") }}</option>
                                            <option value="1" @if($subadmin->is_admin==1) selected @endif>{{ __("Yes") }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                @if(!empty($user_registration_documents) && count($user_registration_documents) > 0)
                                @foreach($user_registration_documents as $user_registration_document)
                                @php
                                    $field_value = "";
                                    if(!empty($user_docs) && count($user_docs) > 0){
                                        foreach($user_docs as $key => $user_doc){
                                            if($user_registration_document->id == $user_doc->user_registration_document_id){
                                                if($user_registration_document->file_type == 'Text' || $user_registration_document->file_type == 'selector' ){
                                                    $field_value = $user_doc->file_name;
                                                } else {
                                                    $field_value = $user_doc->image_file['storage_url'];
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                @if(strtolower($user_registration_document->file_type) == 'selector')
                                        <div class="col-md-6 mb-3" id="{{$user_registration_document->primary->slug??''}}Input">
                                            <label for="">{{$user_registration_document->primary ? $user_registration_document->primary->name : ''}}</label>
                                            <select class="form-control {{ (!empty($user_registration_document->is_required))?'required':''}}" name="{{$user_registration_document->primary->slug}}"  id="input_file_selector_{{$user_registration_document->id}}">
                                                <option value="" >{{__('Please Select '). ($user_registration_document->primary ? $user_registration_document->primary->name : '') }}</option>
                                                @foreach ($user_registration_document->options as $key =>$value )
                                                    <option value="{{$value->id}}" {{ ($value->id == $field_value) ? 'selected':'' }} >{{$value->translation? $value->translation->name: ""}}</option>
                                                @endforeach
                                            </select>
                                            <span class="invalid-feedback" id="{{$user_registration_document->primary->slug}}_error"><strong></strong></span>
                                        </div>
                                @else
                                <div class="col-md-6" >
                                    <div class="form-group" id="{{$user_registration_document->primary->slug??''}}Input">
                                        @if(strtolower($user_registration_document->file_type) == 'text')
                                            <label for="">{{$user_registration_document->primary ? $user_registration_document->primary->name : ''}}</label>
                                            <input id="input_file_logo_{{$user_registration_document->id}}" type="text" name="{{$user_registration_document->primary->slug??''}}" class="form-control" value="{{ $field_value }}">
                                        @else
                                            @if(strtolower($user_registration_document->file_type) == 'image')
                                            <label for="">{{$user_registration_document->primary ? $user_registration_document->primary->name : ''}}</label>
                                            <input type="file" accept="image/*" data-plugins="dropify" name="{{$user_registration_document->primary->slug??''}}" class="dropify" data-default-file="{{ $field_value }}" />
                                            @else
                                            <label class="d-flex align-items-center justify-content-between" for="">{{$user_registration_document->primary ? $user_registration_document->primary->name : ''}}
                                                @if($field_value)
                                                    <a href="{{ $field_value }}" target="__blank">
                                                        <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                        </label>
                                            <input type="file" accept=".pdf" data-plugins="dropify" name="{{$user_registration_document->primary->slug??''}}" class="dropify" data-default-file="{{ $field_value }}" />
                                            @endif
                                        @endif
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-lg-0 mb-3 user_perm_section table-responsive">
                                    @php
                                        $userpermissions = [];
                                        if(isset($user_permissions))
                                        {
                                            foreach ($user_permissions as $singlepermission) {
                                                $userpermissions[] = $singlepermission->permission_id;
                                            }
                                        }
                                    @endphp
                                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                        <thead class="thead-light">
                                            <tr>
                                                <th>{{ __("Permission Name") }}</th>
                                                <th>{{ __("Status") }}</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @php
                                            $brity = \App\Models\ClientPreference::where(['id' => 1])->first('celebrity_check');
                                            @endphp
                                            @foreach($permissions as $singlepermission)
                                            @if($singlepermission->name == 'CELEBRITY')

                                                    @if(!empty($brity) && $brity->celebrity_check == 1)
                                                    <tr>
                                                    <td>
                                                        <h5 class="m-0 font-weight-normal">{{ ucwords(strtolower($singlepermission->name)) }}</h5>
                                                    </td>

                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input event_type" data-id="{{ $singlepermission->id }}" data-event-type="permission" id="permission_{{ $singlepermission->id}}" name="permissions[]" value="{{ $singlepermission->id }}" @if(in_array($singlepermission->id, $userpermissions)) checked @endif >

                                                            <label class="custom-control-label" for="permission_{{ $singlepermission->id}}"></label>
                                                        </div>
                                                    </td>
                                                    </tr>
                                                    @endif

                                            @elseif($singlepermission->name == 'CMS Pages' || $singlepermission->name == 'CMS Emails')
                                                <tr>
                                                    <td>
                                                        <h5 class="m-0 font-weight-normal">{{ $singlepermission->name }}</h5>
                                                    </td>

                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input event_type" data-id="{{ $singlepermission->id }}" data-event-type="permission" id="permission_{{ $singlepermission->id}}" name="permissions[]" value="{{ $singlepermission->id }}" @if(in_array($singlepermission->id, $userpermissions)) checked @endif >

                                                            <label class="custom-control-label" for="permission_{{ $singlepermission->id}}"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>
                                                        <h5 class="m-0 font-weight-normal">{{ ucwords(strtolower($singlepermission->name)) }}</h5>
                                                    </td>

                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input event_type" data-id="{{ $singlepermission->id }}" data-event-type="permission" id="permission_{{ $singlepermission->id}}" name="permissions[]" value="{{ $singlepermission->id }}" @if(in_array($singlepermission->id, $userpermissions)) checked @endif >

                                                            <label class="custom-control-label" for="permission_{{ $singlepermission->id}}"></label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-lg-6 team_perm_section table-responsive">

                                    <table class="table table-borderless table-nowrap table-hover table-centered m-0">

                                        <thead class="thead-light">
                                            <tr>
                                                <th>{{__('Vendors')}}</th>
                                                <th>{{ __("Status") }}</th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            {{-- <tr>
                                                <td>
                                                    <h5 class="m-0 font-weight-normal">{{__('Select All')}}</h5>
                                                </td>

                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input all_vendor_check" id="vendor_permission_all"
                                                        data-event-type="vendor_permission"
                                                        name="vendor_permission_all"
                                                       >
                                                        <label class="custom-control-label" for="vendor_permission_all"></label>
                                                    </div>
                                                </td>
                                            </tr>  --}}

                                            @foreach($vendors as $vendor)
                                            <tr>
                                                <td>
                                                    <h5 class="m-0 font-weight-normal">{{ $vendor->name }}</h5>
                                                </td>

                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input vendor_permission_check" data-id="{{ $vendor->id }}"
                                                        data-event-type="vendor_permission" id="vendor_permission_{{ $vendor->id}}"
                                                        name="vendor_permissions[]" value="{{ $vendor->id }}"
                                                        @if(in_array($vendor->id, $vendor_permissions)) checked @endif>
                                                        <label class="custom-control-label" for="vendor_permission_{{ $vendor->id}}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>





                            <div class="row mb-2 mt-4">
                                <div class="col-12">
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-blue btn-block" type="submit"> {{ __("Submit") }} </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')

    <script src="{{ asset('assets/js/jquery-ui.min.js') }}" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">
    <script>
         $(".all_vendor_check").click(function() {
            if ($(this).is(':checked')) {
                $('.vendor_permission_check').prop('checked', true);
            } else {
                $('.vendor_permission_check').prop('checked', false);
            }
        });
        </script>

@endsection
