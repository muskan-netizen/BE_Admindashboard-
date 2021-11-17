@extends('layouts.vertical', ['demo' => 'creative', 'title' => getNomenclatureName('vendors', true)])

@section('css')
    <link href="{{ asset('assets/libs/fullcalendar-list/fullcalendar-list.min.css') }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .pac-container,
        .pac-container .pac-item {
            z-index: 99999 !important;
        }

        .fc-v-event {
            border-color: #43bee1;
            background-color: #43bee1;
        }

        .dd-list .dd3-content {
            position: relative;
        }

        span.inner-div {
            top: 50%;
            -webkit-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        .button {
            position: relative;
            padding: 8px 16px;
            background: #009579;
            border: none;
            outline: none;
            border-radius: 50px;
            cursor: pointer;
        }

        .button:active {
            background: #007a63;
        }

        .button__text {
            font: bold 20px "Quicksand", san-serif;
            color: #ffffff;
            transition: all 0.2s;
        }

        .button--loading .button__text {
            visibility: hidden;
            opacity: 0;
        }

        .button--loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            border: 4px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: button-loading-spinner 1s ease infinite;
        }

        @keyframes button-loading-spinner {
            from {
                transform: rotate(0turn);
            }

            to {
                transform: rotate(1turn);
            }
        }

    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 d-flex align-items-center">
                <div class="page-title-box">
                    <h4 class="page-title">{{ ucfirst($vendor->name) }} {{ __('profile') }}</h4>
                </div>
                <div class="form-group mb-0 ml-3">
                    <div class="site_link position-relative">
                        <a href="{{ route('vendorDetail', $vendor->slug) }}" target="_blank"><span id="pwd_spn"
                                class="password-span">{{ route('vendorDetail', $vendor->slug) }}</span></a>
                        <label class="copy_link float-right" id="cp_btn" title="copy">
                            <img src="{{ asset('assets/icons/domain_copy_icon.svg') }}" alt="">
                            <span class="copied_txt" id="show_copy_msg_on_click_copy"
                                style="display:none;">{{ __('Copied') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col-sm-12">
                <div class="text-sm-left">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <span>{!! \Session::get('success') !!}</span>
                        </div>
                    @endif
                    @if (\Session::has('error_delete'))
                        <div class="alert alert-danger">
                            <span>{!! \Session::get('error_delete') !!}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-xl-3">
                @include('backend.vendor.show-md-3')
            </div>
            <div class="col-lg-9 col-xl-9">
                <div>
                    <ul class="nav nav-pills navtab-bg nav-justified">
                        <li class="nav-item">
                            <a href="{{ route('vendor.catalogs', $vendor->id) }}" aria-expanded="false"
                                class="nav-link {{ $tab == 'catalog' ? 'active' : '' }} {{ $vendor->status == 1 ? '' : 'disabled' }}">
                                {{ __('Catalog') }}
                            </a>
                        </li>
                        @if ($client_preference_detail->business_type != 'taxi')
                            <li class="nav-item">
                                <a href="{{ route('vendor.show', $vendor->id) }}" aria-expanded="false"
                                    class="nav-link {{ $tab == 'configuration' ? 'active' : '' }} {{ $vendor->status == 1 ? '' : 'disabled' }}">
                                    {{ __('Configuration') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('vendor.categories', $vendor->id) }}" aria-expanded="true"
                                    class="nav-link {{ $tab == 'category' ? 'active' : '' }} {{ $vendor->status == 1 ? '' : 'disabled' }}">
                                    {{ __('Categories & Add Ons') }}
                                </a>
                            </li>
                        @endif
                        @if ($is_payout_enabled == 1)
                            <li class="nav-item">
                                <a href="{{ route('vendor.payout', $vendor->id) }}" aria-expanded="false"
                                    class="nav-link {{ $tab == 'payout' ? 'active' : '' }} {{ $vendor->status == 1 ? '' : 'disabled' }}">
                                    {{ __('Payout') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card widget-inline">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                            <div class="text-center">
                                                <h3>
                                                    <i class="fas fa-money-check-alt text-info"></i>
                                                    <span data-plugin="counterup" >{{ $total_order_value }}</span>
                                                </h3>
                                                <p class="text-muted font-15 mb-0">{{ __('Lifetime Order Value') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                            <div class="text-center">
                                                <h3>
                                                    <i class="fas fa-money-check-alt text-info"></i>
                                                    <span data-plugin="counterup" >{{ $total_admin_commissions }}</span>
                                                </h3>
                                                <p class="text-muted font-15 mb-0">{{ __('Admin Charges') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                            <div class="text-center">
                                                <h3>
                                                    <i class="fas fa-money-check-alt text-info"></i>
                                                    <span data-plugin="counterup" >{{ $total_promo_amount }}</span>
                                                </h3>
                                                <p class="text-muted font-15 mb-0">{{ __('Promo Discount') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                            <div class="text-center">
                                                <h3>
                                                    <i class="fas fa-money-check-alt text-info"></i>
                                                    <span data-plugin="counterup" >{{ $past_payout_value }}</span>
                                                </h3>
                                                <p class="text-muted font-15 mb-0">{{ __('Past Payouts') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-3 col-lg mb-3 mb-md-0">
                                            <div class="text-center">
                                                <h3>
                                                    <i class="fas fa-money-check-alt text-info"></i>
                                                    <span data-plugin="counterup" >{{ $available_funds }}</span>
                                                </h3>
                                                <p class="text-muted font-15 mb-0">{{ __('Available Funds') }}</p>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane {{ $tab == 'configuration' ? 'active show' : '' }} card-body"
                            id="configuration"></div>
                        <div class="tab-pane {{ $tab == 'category' ? 'active show' : '' }}" id="category"></div>
                        <div class="tab-pane {{ $tab == 'catalog' ? 'active show' : '' }}" id="catalog"></div>
                        <div class="tab-pane {{ $tab == 'payout' ? 'active show' : '' }}" id="payout">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="mb-0"> {{ __('Payout') }}</h4>
                                    </div>
                                    <div class="col-6 d-flex align-items-center justify-content-end mb-3">
                                        {{-- <a class="btn btn-info  waves-effect waves-light text-sm-right action_product_button" dataid="0"
                                            id="action_product_button" href="javascript:void(0);"
                                            style="display: none;"><i class="mdi mdi-plus-circle mr-1"></i>
                                            {{ __('Action') }}
                                        </a>
                                        <a class="btn btn-info waves-effect waves-light text-sm-right importProductBtn mx-2 {{ $vendor->status == 1 ? '' : 'disabled' }}"
                                            dataid="0" href="javascript:void(0);"
                                            {{ $vendor->status == 1 ? '' : 'disabled' }}><i
                                                class="mdi mdi-plus-circle mr-1"></i> {{ __('Import') }}
                                        </a> --}}
                                        {{-- <a class="btn btn-info waves-effect waves-light text-sm-right addProductBtn {{ $vendor->status == 1 ? '' : 'disabled' }}"
                                            dataid="0" href="javascript:void(0);"><i
                                                class="mdi mdi-plus-circle mr-1"></i> {{ __('Add Product') }}
                                        </a> --}}
                                        @if($is_stripe_connected == 1)
                                            <h5><i class="fa fa-check text-success mr-2"></i><b>Connected to Stripe</b></h5>
                                        @else
                                            <button type="button" class="btn btn-info waves-effect text-sm-right" onclick="location.href='{{$stripe_connect_url}}'">{{ __("Connect to Stripe") }}</button>
                                        @endif
                                        <button type="button" class="btn btn-info waves-effect text-sm-right ml-2" data-toggle="modal" data-target="#pay-receive-modal">{{ __("Payout") }}</button>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap table-striped" id="vendor_payout_history_datatable" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __("Date") }}</th>
                                                        <th >{{ __("Amount") }}</th>
                                                        <th>{{ __("Type") }}</th>
                                                        <th>{{ __("Action") }}</th>
                                                        <th>{{ __("Status") }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="vendor_payout_history_tbody_list">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="pay-receive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h4 class="modal-title">Payout</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="payout_form">
                    @csrf
                    <div class="modal-body px-3 py-0">
                        <div class="row">
                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <div class="login-form setmodal">
                                        <ul class="list-inline">
                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="teacher" name="payment_type" value="1" checked="">
                                                <label for="teacher"><span class="showspan">Pay</span></label>
                                                </li>
                                            <li class="d-inline-block mr-2">
                                                <input type="radio" id="student" name="payment_type" value="2">
                                                <label for="student"><span class="showspan">Receive</span></label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="field-2" class="control-label">Amount</label>
                                    <input name="payout_amount" id="payout_amount" type="text" class="form-control" placeholder="3000" required onkeypress="return isNumberKey(event)">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            @foreach($payout_options as $opt)
                                <div class="col-md-12 mb-2">
                                    <div class="radio radio-blue form-check-inline">
                                        <input type="radio" id="{{$opt->code}}" value="{{$opt->id}}" name="payout_option">
                                        <label for="{{$opt->code}}"> {{ $opt->title }} </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div id="payout_response">
                                    <div class="alert alert-danger p-1" style="display:none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-info waves-effect waves-light">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form id="new_vendor_payout_form" method="POST" action="{{route('vendor.payout.create', $vendor->id)}}">
                @csrf
                <input type="hidden" name="payout_option_id" id="payout_option_id" value="">
                <input type="hidden" name="transaction_id" id="transaction_id" value="">
                <input type="hidden" name="amount" id="amount" value="">
                <input type="hidden" name="status" id="status" value="">
            </form>
        </div>
    </div>

    <!-- end product popup -->
    <script type="text/javascript">
        $(".all-product_check").click(function() {
            if ($(this).is(':checked')) {
                $("#action_product_button").css("display", "block");
                $('.single_product_check').prop('checked', true);
            } else {
                $("#action_product_button").css("display", "none");
                $('.single_product_check').prop('checked', false);
            }
        });

        $(document).on('change', '#action_for', function() {
            var actionfor = $('#action_for').val();
            $("#for_new").css("display", "none");
            $("#for_featured").css("display", "none");
            $("#for_last_mile").css("display", "none");
            $("#for_live").css("display", "none");
            $("#for_tax").css("display", "none");
            $("#for_sell_when_out_of_stock").css("display", "none");
            $("#"+ actionfor).css("display", "block");
        });

        $(document).on('change', '.single_product_check', function() {
            if ($('input:checkbox.single_product_check:checked').length > 0) {
                $("#action_product_button").css("display", "block");
            } else {
                $('.all-product_check').prop('checked', false);
                $("#action_product_button").css("display", "none");
            }
        });

        ////////   *******************  Save product action data ******************* ////////////////////////
        // $('#save_product_action_modal').on('submit', function(e) {
        //     e.preventDefault(); 
        //     var is_new = $('#is_new').val();
        //     var is_featured = $('#is_featured').val();
        //     var is_live = $('#is_live').val();
        //     var tax_category = $('#tax_category').val();
        //     var product_id = [];
        //      $('.single_product_check:checked').each(function(i){
        //         product_id[i] = $(this).val();
        //     });
        //     if (product_id.length == 0) {
               
        //         $("#action-product-modal .close").click();
        //         return;
        //     }
        //     console.log(product_id);
        //     return false;
        //     $.ajax({
        //         type: "POST",
        //         url: '{{route("product.update.action")}}',
        //         data: {_token: CSRF_TOKEN, is_new: is_new, is_featured: is_featured, is_live: is_live, tax_category: tax_category, product_id: product_id},
        //         success: function( msg ) {
        //             location.reload();
        //         }
        //     });
        // });

        $(document).on('click', '.submitProductAction', function(e) {
            var CSRF_TOKEN = $("input[name=_token]").val();
            var is_new = $('#is_new').prop('checked');
            var is_featured = $('#is_featured').prop('checked');
            var is_live = $('#is_live').val();
            var tax_category = $('#tax_category_for').val();
            var action_for = $('#action_for').val();
            var last_mile = $('#last_mile').prop('checked');
            var sell_when_out_of_stock = $('#sell_when_out_of_stock').prop('checked');
            var product_id = [];
             $('.single_product_check:checked').each(function(i){
                product_id[i] = $(this).val();
            });
            if (product_id.length == 0) {
               
                $("#action-product-modal .close").click();
                return;
            }
            if(action_for == 0){
                return false;
            }
            
            $.ajax({
                type: "post",
                url: '{{route("product.update.action")}}',
                data: {_token: CSRF_TOKEN,action_for:action_for,sell_when_out_of_stock:sell_when_out_of_stock,last_mile:last_mile, is_new: is_new, is_featured: is_featured, is_live: is_live, tax_category: tax_category, product_id: product_id},
                 success: function(resp) {
                    if (resp.status == 'success') {
                        $.NotificationApp.send("Success", resp.message, "top-right", "#5ba035",
                            "success");
                        location.reload();
                    }
                },
                beforeSend: function() {
                    $(".loader_box").show();
                },
                complete: function() {
                    $(".loader_box").hide();
                },
                error: function(response) {
                    
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text(
                            'Something went wrong, Please try Again.');
                    
                    return response;
                }
            });
        });
        ////////  ****************** End save product action data *****************//////////////////////////

        $('#action_product_button').click(function() {
            $('#action-product-modal').modal({
                keyboard: false
            });
        });

        $('.addProductBtn').click(function() {
            $.ajax({
                type: "get",
                url: "{{route('vendor.specific_categories',$vendor->id)}}",
                success: function(response) {
                    if(response.status == 1){
                        $("#category_list").find('option').remove();
                        $("#category_list").append(response.options);
                        $('#category_list').selectize()[0].selectize.destroy();
                    }
                },
                error:function(error){

                }
            });
            $('#add-product').modal({
                keyboard: false
            });
        });
        $('.importProductBtn').click(function() {
            $('#import-product').modal({
                keyboard: false
            });
        });

        $("#csv_button").click(function() {
            $("#import_woocommerce").show();
            $("#import_csv").hide();
        });

        $("#import_woocommerce").hide();
        $("#woocommerce_button").click(function() {
            $("#import_csv").show();
            $("#import_woocommerce").hide();
        });

        var regexp = /^[a-zA-Z0-9-_]+$/;

        function setSkuFromName() {
            var n1 = $('#product_name').val();
            var sku_start = "{{ $sku_url }}" + ".";
            var total_sku = sku_start + n1;
            $('#sku').val(sku_start + n1);

            if (regexp.test(n1)) {
                var n1 = $('#product_name').val();
                $('#url_slug').val(n1);
                slugify();
            } else {
                $('#sku').val(total_sku.split(' ').join(''));
            }

            alplaNumeric();

        }

        function alplaNumeric() {
            var n1 = $('#sku').val();
            if (regexp.test(n1)) {
                var n1 = $('#sku').val();
                $('#url_slug').val(n1);
                slugify();
            } else {
                $('#sku').val(n1.split(' ').join(''));
            }
            // var charCode = String.fromCharCode(event.which || event.keyCode);
            // if (!regexp.test(charCode)) {
            //     console.log(">>>ne");
            //     return false;
            // }
            // console.log(">>>ne2");
            // var n1 = $('#sku').val();
            // $('#url_slug').val(n1+charCode)

            // return true;
        }

        function slugify() {
            //   var charCode = String.fromCharCode(event.which || event.keyCode);
            //   if (!regexp.test(charCode)) {
            //     return false;
            //   }
            var string = $('#url_slug').val();
            var slug = string.toString().trim().toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "").replace(
                /\-\-+/g, "-").replace(/^-+/, "").replace(/-+$/, "");
            $('#url_slug').val(slug);
        }
        $(document).on('click', '#save_woocommerce_btn', function(e) {
            var that = $(this);
            $('.text-danger').html('');
            that.attr('disabled', true);
            $('#import_product_from_woocomerce').attr('disabled', true);
            var form = document.getElementById('woocommerces_form');
            var formData = new FormData(form);
            $.ajax({
                type: "post",
                url: "{{ route('woocommerce.save') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    that.attr('disabled', false);
                    that.removeClass('button--loading');
                    $('#import_product_from_woocomerce').attr('disabled', false);
                    if (response.status == 'success') {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035",
                            "success");
                    } else {
                        $.NotificationApp.send("Error", response.message, "top-right", "#FF0000",
                            "error");
                    }
                },
                error: function(error) {
                    that.attr('disabled', false);
                    that.removeClass('button--loading');
                    $('#import_product_from_woocomerce').attr('disabled', false);
                    var response = $.parseJSON(error.responseText);
                    let error_messages = response.errors;
                    $.each(error_messages, function(key, error_message) {
                        $('#' + key + '_error').html(error_message[0]).show();
                    });
                }
            });
        });
        $(document).on('click', '#import_product_from_woocomerce', function(e) {
            var that = $(this);
            $('#save_woocommerce_btn').attr('disabled', true);
            that.attr('disabled', true);
            var vendor_id = $(this).data('vendor');
            $.ajax({
                type: "POST",
                data: {
                    vendor_id: vendor_id
                },
                url: "{{ route('product.import.woocommerce') }}",
                dataType: 'json',
                success: function(response) {
                    that.attr('disabled', false);
                    that.removeClass('button--loading');
                    $('#save_woocommerce_btn').attr('disabled', false);
                    if (response.status == 'success') {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035",
                            "success");
                    } else {
                        $.NotificationApp.send("Error", response.message, "top-right", "#FF0000",
                            "error");
                    }
                }
            });
        });
        $(document).on('click', '.submitProduct', function(e) {
            var form = document.getElementById('save_product_form');
            var formData = new FormData(form);
            $.ajax({
                type: "post",
                url: "{{ route('product.validate') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(resp) {
                    if (resp.status == 'success') {
                        $('#save_product_form').submit();
                    }
                },
                beforeSend: function() {
                    $(".loader_box").show();
                },
                complete: function() {
                    $(".loader_box").hide();
                },
                error: function(response) {
                    if (response.status === 422) {
                        let errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            if (key == 'category.0') {
                                $("#categoryInput input").addClass("is-invalid");
                                $("#categoryInput span.invalid-feedback").children("strong")
                                    .text('The category field is required.');
                                $("#categoryInput span.invalid-feedback").show();
                            } else {
                                $("#" + key + "Input input").addClass("is-invalid");
                                $("#" + key + "Input span.invalid-feedback").children("strong")
                                    .text(errors[key][0]);
                                $("#" + key + "Input span.invalid-feedback").show();
                            }
                        });
                    } else {
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text(
                            'Something went wrong, Please try Again.');
                    }
                    return response;
                }
            });
        });

        $(document).delegate("#payout_form", "submit", function(e){
            e.preventDefault();
            var valid = true, message = '';
            var payout_opt_el = $('input[name="payout_option"]');
            if ($('#payout_amount').val() == '') {
                message = "Please enter payout amount";
                valid = false;
            }
            if (!payout_opt_el.is(":checked")) {
                message = "Please select a valid payout option";
                valid = false;
            }
            if(!valid) {
                $("#payout_response .alert").html(message).show();
                setTimeout(function(){
                    $("#payout_response .alert").hide();
                },5000);
                return false;
            }

            if(payout_opt_el.val() == 1){
                var amount = $('#payout_amount').val();
                var payout_opt = payout_opt_el.val();
                $("#payout_option_id").val(payout_opt);
                $("#transaction_id").val('');
                $("#amount").val(amount);
                $("#status").val(1);
                $("#new_vendor_payout_form").submit();
            }
        });

        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

        // function payoutViaStripe(amount, payment_option_id) {
        //     let total_amount = 0;
        //     let tip = 0;
        //     let cartElement = $("input[name='cart_total_payable_amount']");
        //     let walletElement = $("input[name='wallet_amount']");
        //     let subscriptionElement = $("input[name='subscription_amount']");
        //     let tipElement = $("#cart_tip_amount");

        //     let ajaxData = [];
        //     if (cartElement.length > 0) {
        //         total_amount = cartElement.val();
        //         tip = tipElement.val();
        //         ajaxData.push({ name: 'tip', value: tip });
        //     } else if (walletElement.length > 0) {
        //         total_amount = walletElement.val();
        //     } else if (subscriptionElement.length > 0) {
        //         total_amount = subscriptionElement.val();
        //         ajaxData = $("#subscription_payment_form").serializeArray();
        //     }
        //     ajaxData.push({ name: 'stripe_token', value: stripe_token }, { name: 'amount', value: total_amount }, { name: 'payment_option_id', value: payment_option_id });
        //     $.ajax({
        //         type: "POST",
        //         dataType: 'json',
        //         url: payout_stripe_url,
        //         data: ajaxData,
        //         success: function(resp) {
        //             if (resp.status == 'Success') {
                        
        //             } else {
        //                 $("#payout_response .alert").html(resp.message).show();
        //                 setTimeout(function(){
        //                     $("#payout_response .alert").hide();
        //                 },5000);
        //                 return false;
        //             }
        //         },
        //         error: function(error) {
        //             var response = $.parseJSON(error.responseText);
        //             $("#payout_response .alert").html(response.message).show();
        //             setTimeout(function(){
        //                 $("#payout_response .alert").hide();
        //             },5000);
        //             return false;
        //         }
        //     });
        // }

    </script>
    {{-- @include('backend.vendor.modals') --}}
@endsection
@section('script')
    @include('backend.vendor.pagescript')
    <script>
        $(document).on('click', '.copy_link', function() {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($('#pwd_spn').text()).select();
            document.execCommand("copy");
            $temp.remove();
            $("#show_copy_msg_on_click_copy").show();
            setTimeout(function() {
                $("#show_copy_msg_on_click_copy").hide();
            }, 1000);
        })
    </script>
@endsection
