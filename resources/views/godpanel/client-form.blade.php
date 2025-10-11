@extends('layouts.god-vertical', ['title' => 'Client'])
@section('css')
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('assets/css/intlTelInput.css') }}" type="text/css">
@endsection
@section('content')
<style type="text/css">
    .sub-domain-input #sub_domain {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    span#inputGroupPrepend2 {
        border-top-right-radius: 20px !important;
        border-bottom-right-radius: 20px !important;
    }
    
    .sub-domain-input #inputGroupPrepend2 {
        font-size: 18px;
        padding: 0 30px;
    }

    input#phone_number{
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }

    span#basic-addon1{
        border-top-left-radius: 20px !important;
        border-bottom-left-radius:20px !important;   
    }

    .domain-name {
        position: absolute;
        right: 1px;
        background: #f5f5f5;
        height: 96%;
        display: flex;
        align-items: center;
        padding: 0 40px 0 20px;
        border-radius: 0 20px 20px 0;
    }

    input#domainname {
        padding-right: 160px;
    }


    #sell_promptInput textarea#sell_prompt {
        height: 100%;
    }

    div#sell_promptInput {
        margin: 0;
    }

    .green-icon svg {
        width: 22px;
        fill: green;
    }

    .red-icon svg {
        fill: red;
    }

    .green-icon {
        position: absolute;
        right: 10px;
    }

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Create Client</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="text-sm-left">
                                <div class="alert alert-error">
                                    @foreach ($errors->all() as $error)
                                        <span>{{$error}}</span>
                                    @endforeach
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <?php $disable = $style = ""; ?>
                    @if(isset($client_data->id))
                    <?php $disable = 'disabled';
                    $style = "cursor:not-allowed;"; ?>
                    <form id="UpdateClient" method="post" action="{{route('client.update', $client_data->id)}}"
                        enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" name="client_id" id="client_id" value="{{$client->id}}">
                        @method('PUT')
                    @else
                        <form id="StoreClient" method="post" action="" enctype="multipart/form-data" autocomplete="off">
                    @endif
                        @csrf
                        <div class="row mb-2 aaa">
                             <div class="col-sm-6 text-left">
                               <h4 class=" waves-effect waves-light text-sm-right">{{isset($client_data->id) ? "Update" : "Create"}}</h4>
                            </div>
                            <div class="col-sm-6 text-right">
                                <a class="btn btn-info waves-effect waves-light text-sm-right" href="{{route('client.index')}}">< Back </a>
                            </div>
                        </div>
                        <!-- <div class="row mb-2">
                            <div class="col-md-3 col-6">
                                <label>Upload Logo</label>
                                <input type="file" accept="image/*" data-plugins="dropify" name="logo" data-default-file="{{isset($client->logo) ? $client->logo['proxy_url'].'400/400'.$client->logo['image_path'] : ''}}" />
                            </div>
                        </div> -->
                        <div class="row mb-2">
                            <div class="col-md-3 col-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Name') }}</label>
                                    <div class="position-relative"  id="nameInput">
                                        <input type="text" class="form-control" name="name" id="name"
                                            value="{{isset($client_data->name) ? $client_data->name : ''}}" placeholder="{{ __('Enter Your Name') }}" required>
                                        <span class="invalid-feedback" role="alert">
                                            <span></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group" id="email_valueInput">
                                    <label class="form-label">{{ __('E-mail') }}</label>
                                    <div class="position-relative">
                                        <input type="email" class="form-control" name="email" id="email"
                                            value="{{isset($client_data->email) ? $client_data->email : ''}}" placeholder="{{ __('Enter email address') }}">
                                        <div class="green-icon" style="top:10px;display: none;" id="email_success">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path
                                                    d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
                                            </svg>
                                        </div>
                                        <div class="green-icon red-icon d-none" id="email_error" style="top:10px">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <title>close-thick</title>
                                                <path
                                                    d="M20 6.91L17.09 4L12 9.09L6.91 4L4 6.91L9.09 12L4 17.09L6.91 20L12 14.91L17.09 20L20 17.09L14.91 12L20 6.91Z" />
                                            </svg>
                                        </div>
                                        <span class="invalid-feedback" role="alert">
                                            <span></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="form-group" id="phone_valueInput">
                                    <label class="form-label">{{ __('Phone No.') }}</label>
                                    <div class="ipnuts">
                                        <input type="tel" class="form-control" name="phone" id="phone"
                                            value="{{isset($client_data->phone_number) ? $client_data->phone_number : ''}}" placeholder="{{ __('Enter Number') }}">
                                        <input type="hidden" id="dialCode" name="dialCode" value="{{isset($client_data->dial_code) ? $client_data->dial_code : '91'}}">
                                        <input type="hidden" id="countryData" name="countryData" value="in">                                        
                                    </div>
                                    <span class="invalid-feedback" role="alert">
                                        <span></span>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="form-group" id="default_location_nameInput">
                                    <label class="form-label" for="default_location_name">{{ __('Business Address') }}</label>
                                    <div class="input-group">
                                        <input type="text" name="default_location_name"
                                            id="default_location_name" placeholder="Delhi, India"
                                            class="form-control" value="{{isset($client_data->default_location_name) ? $client_data->default_location_name : $formData['defaultLocation']}}">
                                    </div>
                                    <span class="invalid-feedback" role="alert">
                                        <span></span>
                                    </span>
                                </div>
                            </div>

                            <div class="col-12 mb-2">
                                <div class="row">
                                    <div class="col-md-{{isset($client_data->id) ? '12' : '6'}}"> 
                                        <div class="row">
                                            <div class="col-md-{{isset($client_data->id) ? '3' : '6'}}">
                                                <div class="form-group" id="businessnameInput">
                                                    <label class="form-label">{{ __('Business Name') }}</label>
                                                    <input type="text" class="form-control" name="businessname" id="businessname"
                                                        value="{{isset($client_data->businessname) ? $client_data->businessname : ''}}" placeholder="{{ __('Enter Business Name') }}">
                                                    <span class="invalid-feedback" role="alert">
                                                        <span></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-{{isset($client_data->id) ? '3' : '6'}}">
                                                    <div class="form-group" id="domainnameInput">
                                                        <label class="form-label">{{ __('Domain Name') }}</label>
                                                        <div class="domain-input position-relative d-flex align-items-center">
                                                            <input type="name" class="form-control" name="domainname" id="domainname"
                                                                value="{{isset($client_data->domainname) ? $client_data->domainname : ''}}" placeholder="{{ __('Enter Domain Name') }}"
                                                                {{isset($client_data->domainname) ? 'readonlyasd' : ''}}>
                                                            <div class="domain-name">
                                                                .vendsuite.ai
                                                            </div>
                                                            <span class="invalid-feedback" role="alert">
                                                                <span></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-{{isset($client_data->id) ? '3' : '6'}}">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('Language') }}</label>
                                                    <div class="select">
                                                        <select class="Language-selector form-control" id="primary_language"
                                                            name="primary_language">
                                                            @foreach ($languages as $lang)
                                                                <option value="{{ $lang->id }}"
                                                                    {{ isset($client_data->primary_language) && $client_data->primary_language == $lang->id ? 'selected' : '' }}>
                                                                    {{ $lang->name }} </option>
                                                            @endforeach                                          
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-{{isset($client_data->id) ? '3' : '6'}}">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('Currency') }}</label>
                                                    <div class="select">
                                                        <select class="currency-selector form-control" id="primary_currency"
                                                            name="primary_currency">
                                                            @foreach ($currencies as $currency)
                                                                <option iso="{{ $currency->iso_code . ' ' . $currency->symbol }}"
                                                                    value="{{ $currency->id }}"
                                                                    {{ !isset($client_data->primary_currency) && $formData['primaryCurrency'] == $currency->id ? 'selected' : '' }}
                                                                    {{ isset($client_data->primary_currency) && $client_data->primary_currency == $currency->id ? 'selected' : '' }}>                                                    
                                                                    {{ $currency->iso_code . ' ' . $currency->symbol }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="col-md-{{isset($client_data->id) ? '3' : '6'}}">
                                                <label for="languages" class="control-label">{{ __('Client Type') }}</label>
                                                <select class="form-control" id="client_type" name="client_type">
                                                    @foreach($client_types as $key => $value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-{{isset($client_data->id) ? '3' : '6'}}">
                                                <label for="languages" class="control-label">{{ __('Business Type') }}</label>
                                                <select class="form-control" id="business_type" name="business_type" >
                                                    @foreach($business_types as $key => $business)
                                                        <option value="{{$key}}"
                                                        {{ isset($client_data->business_type) && $client_data->business_type == $key ? 'selected' : '' }}>    
                                                        {{$business->title}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 {{isset($client_data->id) ? 'd-none' : ''}}">
                                        <div class="form-group" id="sell_promptInput" >
                                            <label class="form-label">{{ __('What do You Sell') }} ?</label>
                                            <textarea class="form-control" placeholder="{{ __('AI Text Prompt Generator') }} ...." id="sell_prompt" name="sell_prompt" rows="8"></textarea>
                                            <span class="invalid-feedback" role="alert">
                                                <span></span>
                                            </span>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                          
                            <div class="col-md-3 ">
                                <div class="choose-sec br-top">
                                    <div class="colormainclass">
                                        <div class="choose-color" id="">
                                            <!-- <h4 class="title">{{ __('Choose a color scheme for your website') }}.</h4> -->
                                            <!-- <label class="form-label">{{ __('Choose a color scheme for your website') }}</label> -->
                                            <div class="row">
                                                <div class="col-6 col-sm-6 col-lg-6 col-xl-6">
                                                    <div class="form-group">
                                                        <label class="form-label nobold">{{ __('Primary Color') }}</label>
                                                        <input type="text" data-jscolor="{}" id="primarycolor" class="form-control"
                                                            name="primarycolor" value="{{isset($client_data->primarycolor) ? $client_data->primarycolor : '#FF3F6C'}}">
                                                    </div>
                                                </div>
                                                <div class="col-6 col-sm-6 col-lg-6 col-xl-6">
                                                    <div class="form-group">
                                                        <label class="form-label nobold">{{ __('Secondary Color') }}</label>
                                                        <input type="text" data-jscolor="{}" id="secondarycolor" class="form-control"
                                                            name="secondarycolor" value="{{isset($client_data->secondarycolor) ? $client_data->secondarycolor : '#FEAF64'}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="country">{{ __('Select Country') }}</label>
                                <select name="country" id="country" class="form-control" required="required">
                                    @foreach ($countries as $co)
                                        <option value="{{ $co->id }}"
                                            @if ($formData['country'] == $co->id) selected @endif>{{ $co->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="country_error"></span>
                            </div>
                        </div>
                        <div class="col-md-3 d-none">
                            <div class="form-group">
                                <label class="form-label">{{ __('Time Zone') }}</label>
                                <select class="form-control" id="timezone" name="timezone"
                                    placeholder="Timezone">
                                    @foreach ($tzlist as $tz)
                                        <option value="{{ $tz->timezone }}"
                                            @if ($formData['timezone'] == $tz->timezone) selected @endif>{{ $tz->timezone }}
                                            {{ $tz->diff_from_gtm }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback" role="alert">
                                    <span></span>
                                </span>
                            </div>
                        </div>
                        </div>
                        
                        <div class="form-group">
                         <input type="hidden" name="default_latitude" id="default_latitude" value="{{ $formData['defaultLatitude'] }}">
                        <input type="hidden" name="default_longitude" id="default_longitude" value="{{ $formData['defaultLongitude'] }}">
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-info waves-effect waves-light w-50">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($client))
<div class="row">
        <div class="col-6">    
            <div class="card">
                <div class="card-body"><h3>Import Demo Content [Warning! All data will be lost.]</h3>
                <form id="update_default_data" method="post" action="{{route('client.migrateDefaultData', $client->id)}}"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="languages">Business Type </label>
                            <select class="form-control" id="business_typesa" name="business_type">
                                <option value="">select </option>
                                @foreach(config('constants.BusinessTypesDataBase') as $key => $val)
                                <option value="{{$val}}">{{$key}}</option>
                                @endforeach
                                <option value="elixir.sql">Elixir - Pharmacy Delivery </option>
                                <!-- <option value="grub.sql">Grub - Food Delivery</option>
                                <option value="homeric.sql">Homeric - Home Service </option>
                                <option value="gokab.sql">GoKab - Cab Booking </option>
                                <option value="ace.sql">Ace - Super App </option>
                                <option value="punnet.sql">Punnet - Single Vendor Food Delivery </option>
                                <option value="suel.sql">Suel - Single Vendor Ecommerce </option>
                                <option value="voltaic.sql">Voltaic - Ecommerce </option>
                                <option value="gusto.sql">Gusto - Grocery Delivery </option>
                                <option value="elixir.sql">Elixir - Pharmacy Delivery </option>
                                <option value="zest.sql">Zest - Pickup & Delivery </option> -->
                            </select>
                        </div>    
                    </div>

                    <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light w-50">{{__('Submit')}}</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
        <div class="col-6">    
            <div class="card">
                <div class="card-body"><h3>fetch Data with AI [Warning! All data will be lost.]</h3>
                <form id="update_default_data" method="post" action="{{route('client.fetch_data', $client->id)}}"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <input type="hidden" name="primary_language" value="{{ $client_data->primary_language ? $client_data->primary_language : 1 }}">
                    <div class="col-md-12">
                        <div class="form-group" id="sell_promptInput" >
                            <label class="form-label">{{ __('What do You Sell') }} ?</label>
                            <textarea class="form-control" placeholder="{{ __('AI Text Prompt Generator') }} ...." id="sell_prompt" name="sell_prompt" rows="4"></textarea>
                            <span class="invalid-feedback" role="alert">
                                <span></span>
                            </span>
                        </div> 
                    </div>
                    <div class="col-md-12 mt-1 text-right">
                            <button type="submit" class="btn btn-info waves-effect waves-light w-50">{{__('Submit')}}</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
@endif
</div>


<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key={{ $mapapikey }}&v=3.exp&libraries=places,drawing"></script>
<script src="{{ asset('assets/js/intlTelInput.js') }}"></script>
<script src="{{ asset('js/phone_number_validation.js') }}"></script>
<script src="{{ asset('assets/libs/jquery-toast-plugin/jquery-toast-plugin.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/toastr.init.js') }}"></script>
<script src="{{ asset('assets/js/jscolor.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js">
</script>
<script type="text/javascript">
$(document).ready(function(){
    initialize();
    var loc = "{{route('client.index')}}";
    $('#side-menu').find('a').each(function() {
        if($(this).attr('href') == loc)
        {  
            $(this).toggleClass('active');
            $(this).parent().toggleClass('menuitem-active');
        }
    });
    $(document).on('click', '.iti__country', function() {
        var code = $(this).attr('data-country-code');
        // $('#countryData').val(code);
        var dial_code = $(this).attr('data-dial-code');
        $('#dialCode').val(dial_code);
    });
    $('#businessname').on('keyup', function(evt) {
        var businessname = evt.target.value;
        //var domain = businessname.toLowerCase().replace(/\s/g, '');
        var charactersToReplace = /[_*\/\\}{\[\]'"\":;><,().?&^%$#@!~`+=]/g;
        // Replace the characters and convert to lowercase, removing spaces
        var domain = businessname.replace(charactersToReplace, '').toLowerCase().replace(/\s/g, '');       
       //var domainUpdate = "{{isset($client_data->id) ? 1 : 0}}";
       // if(domainUpdate == 0){
            $('#domainname').val(domain);
      //  }
        //var slug = $('#domainname').val();
    });

    var colorJson = {
                'vc_ecommerce': {
                    "primary_color": '#FF3F6C',
                    "secondary_color": '#FE70AB'
                },
                'taxi': {
                    "primary_color": '#FFB900',
                    "secondary_color": '#282C3F'
                },
                'food_grocery_ecommerce': {
                    "primary_color": '#FC8019',
                    "secondary_color": '#282C3F'
                },
                'food': {
                    "primary_color": '#FC8019',
                    "secondary_color": '#282C3F'
                },
                'grocery': {
                    "primary_color": '#29ac62',
                    "secondary_color": '#137FB1'
                },
                'home_service': {
                    "primary_color": '#b86f02',
                    "secondary_color": '#4B57B1'
                },
                'super_app': {
                    "primary_color": '#C41D49',
                    "secondary_color": '#7D80B1'
                },
                'laundry': {
                    "primary_color": '#1090ff',
                    "secondary_color": '#346CB1'
                }
            };

    $('#business_type').change(function() {
        var primary_color = colorJson[this.value].primary_color;
        var secondary_color = colorJson[this.value].secondary_color;       
        $('#primarycolor')[0].jscolor.fromString(primary_color);
        $('#secondarycolor')[0].jscolor.fromString(secondary_color);    

    });

    $('#StoreClient').submit(function(e) {     
        e.preventDefault();
        $('.invalid-feedback').hide();
        $('.invalid-feedback span').text('');
        var is_required = true;    
        var email = $('#email').val();
        if (email == '') {
            $('#email_valueInput .invalid-feedback').show();
            $('#email_valueInput .invalid-feedback span').html("{{ __('Please enter Email') }}");              
            is_required = false;
        }
        if (email != '') {
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            var emailValidate = emailPattern.test(email);
            if(emailValidate == false){
                $('#email_valueInput .invalid-feedback').show();
                 $('#email_valueInput .invalid-feedback span').html("{{ __('Please enter Valid Email') }}");              
                 is_required = false;
            }           
        }
        var phonevalue = $('#phone').val();
        if (phonevalue == '') {
            $('#phone_valueInput .invalid-feedback').show();
            $('#phone_valueInput .invalid-feedback span').html("{{ __('Please enter Phone') }}");              
            is_required = false;
        }
        var default_location_name = $('#default_location_name').val();
        if (default_location_name == '') {
            $('#default_location_nameInput .invalid-feedback').show();
            $('#default_location_nameInput .invalid-feedback span').html("{{ __('Please enter Business Address') }}");              
            is_required = false;
        }
        var businessname = $('#businessname').val();
        if (businessname == '') {
            $('#businessnameInput .invalid-feedback').show();
            $('#businessnameInput .invalid-feedback span').html("{{ __('Please enter Business Name') }}");              
            is_required = false;
        }
        var domainname = $('#domainname').val();
        if (domainname == '') {
            $('#domainnameInput .invalid-feedback').show();
            $('#domainnameInput .invalid-feedback span').html("{{ __('Please enter Domain Name') }}");              
            is_required = false;
        }
        var sell_prompt = $('#sell_prompt').val();
        if (sell_prompt == '') {
            $('#sell_promptInput .invalid-feedback').show();
            $('#sell_promptInput .invalid-feedback span').html("{{ __('Please enter Business Prompt') }}");              
            is_required = false;
        }     
        if(is_required == false){
            return false;
        }
        $form = $(this);        
        $.ajax({
            type: "post",
            url: "{{route('client.store')}}",
            data: $form.serialize(),
            dataType: 'json',
            success: function(resp) {
                if(resp.success === true){
                    $.NotificationApp.send("Success", 'Client has been created successfully!', "top-right", "#5ba035", "success"); 
                    window.location.href = resp.url; 
                }
            },
            error: function(response) {
                $.NotificationApp.send("Error", 'Something went wrong!', "top-right", "#5ba035", "success");
            },
        });

    });
    $(document).on('click', '.deleteGiftCard', function(e) {
        e.preventDefault();
        var giftcardId =$(this).attr('data-gify-card');
    
        Swal.fire({
            title: 'Warning!',
            text: 'Are you sure?',
            icon: 'warning',
        }).then(({value}) => {
            console.log(value);
                if (value === true) {
                    // deleteGiftCard(giftcardId);
                } 
        });
});
    $('#UpdateClient').submit(function(e) { 
        $('.invalid-feedback').hide();
        $('.invalid-feedback span').text('');
        var is_required = true;    
        var email = $('#email').val();
        if (email == '') {
            $('#email_valueInput .invalid-feedback').show();
            $('#email_valueInput .invalid-feedback span').html("{{ __('Please enter Email') }}");              
            is_required = false;
        }
        if (email != '') {
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            var emailValidate = emailPattern.test(email);
            if(emailValidate == false){
                $('#email_valueInput .invalid-feedback').show();
                 $('#email_valueInput .invalid-feedback span').html("{{ __('Please enter Valid Email') }}");              
                 is_required = false;
            }           
        }
        var phonevalue = $('#phone').val();
        if (phonevalue == '') {
            $('#phone_valueInput .invalid-feedback').show();
            $('#phone_valueInput .invalid-feedback span').html("{{ __('Please enter Phone') }}");              
            is_required = false;
        }
        var default_location_name = $('#default_location_name').val();
        if (default_location_name == '') {
            $('#default_location_nameInput .invalid-feedback').show();
            $('#default_location_nameInput .invalid-feedback span').html("{{ __('Please enter Business Address') }}");              
            is_required = false;
        }
        var businessname = $('#businessname').val();
        if (businessname == '') {
            $('#businessnameInput .invalid-feedback').show();
            $('#businessnameInput .invalid-feedback span').html("{{ __('Please enter Business Name') }}");              
            is_required = false;
        }
        if(is_required == false){
            e.preventDefault();
        }

    });

    $('#domainname').on('keyup', function(evt) {
        var domain = evt.target.value;
        //var domain = businessname.toLowerCase().replace(/\s/g, '');
        var charactersToReplace = /[_*\/\\}{\[\]'"\":;><,().?&^%$#@!~`+=]/g;
        // Replace the characters and convert to lowercase, removing spaces
        var domain = domain.replace(charactersToReplace, '').toLowerCase().replace(/\s/g, '');       
       //var domainUpdate = "{{isset($client_data->id) ? 1 : 0}}";
       // if(domainUpdate == 0){
            $('#domainname').val(domain);
      //  }
        //var slug = $('#domainname').val();
    });

    function initialize() {
        var input = document.getElementById('default_location_name');
        var autocomplete = new google.maps.places.Autocomplete(input);   
        
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();                       
            $('#default_latitude').val(place.geometry.location.lat());
            $('#default_longitude').val(place.geometry.location.lng());

            for (let i = 1; i < place.address_components.length; i++) {
                let mapAddress = place.address_components[i];
                if (mapAddress.long_name != '') {
                    if (mapAddress.types[0] == "country") {
                        var country = document.getElementById('country');
                        for (let i = 0; i < country.options.length; i++) {
                            if (country.options[i].text.toUpperCase() == mapAddress.long_name.toUpperCase()) {
                                country.value = country.options[i].value;
                                break;
                            }
                            iti.setCountry(mapAddress.short_name.toLowerCase());
                            var selectedCountryCode = iti.getSelectedCountryData().iso2;
                            var selectedDialCode = iti.getSelectedCountryData().dialCode;                           
                            document.getElementById('countryData').value = selectedCountryCode;
                            document.getElementById('dialCode').value = selectedDialCode;
                            
                        }
                        const apiKey = "{{ $mapapikey }}";

                        const latitude = place.geometry.location.lat();
                        const longitude = place.geometry.location.lng();

                        const currentTimestamp = Date.now() / 1000; // Convert milliseconds to seconds

                        const apiUrl = `https://maps.googleapis.com/maps/api/timezone/json?location=${latitude},${longitude}&timestamp=${currentTimestamp}&key=${apiKey}`;

                        fetch(apiUrl)
                        .then((response) => response.json())
                        .then((data) => {
                            console.log(data);
                            if(data?.timeZoneId != '' && data.timeZoneId == 'Asia/Calcutta')
                                document.getElementById('timezone').value = 'Asia/Kolkata';
                            else if(data?.timeZoneId != '' )
                                document.getElementById('timezone').value = data.timeZoneId;
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
                    }
                }
            }

        });

        var input = document.querySelector("#phone");
        var iti = window.intlTelInput(input, {
            separateDialCode: true,
            hiddenInput: "full_number",
            utilsScript: "{{ asset('assets/js/utils.js') }}",
            initialCountry: "IN",
        });
        var primarycolor = "{{isset($client_data->primarycolor) ? $client_data->primarycolor : '#FF3F6C'}}"
        var secondarycolor = "{{isset($client_data->secondarycolor) ? $client_data->secondarycolor : '#FEAF64'}}"
        $('#primarycolor')[0].jscolor.fromString(primarycolor);
        $('#secondarycolor')[0].jscolor.fromString(secondarycolor);
        
        
    }
});
</script>
@endsection
@section('script')
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>

@endsection