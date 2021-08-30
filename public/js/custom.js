$(function () {
    document.ajax_loading = false;
    $.hasAjaxRunning = function () {
        return document.ajax_loading;
    };
    $(document).ajaxStart(function () {
        document.ajax_loading = true;
    });
    $(document).ajaxStop(function () {
        document.ajax_loading = false;
    });
});

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $("#main_search_box").blur(function (e) {
        setTimeout(function () {
            $('#search_box_main_div').html('').hide();
        },
            500);
    });
    $("#mobile_search_box_btn").click(function () {
        $('.radius-bar').slideToggle();
    });
    // $("#search_viewall").click(function () {
    //     console.log("nsdhfjs");
    // });
    $(document).on("click", "#search_viewall", function(e) {
        let keyword = $("#main_search_box").val();
        let url = "/search-all/"+keyword;
        // url = url.replace(':id', keyword);
        // console.log(url);
        // document.location.href=url;
        window.location.href = url;
        return false;
    });
    $('input[type=search]').on('search', function () {
        $('#search_box_main_div').html('').hide();
    });
    $("#main_search_box").focus(function () {
        let keyword = $(this).val();
        searchResults(keyword);
    });
    $("#main_search_box").keyup(function () {
        let keyword = $(this).val();
        searchResults(keyword);
    });
    function searchResults(keyword){
        if (keyword.length <= 2) {
            $('#search_box_main_div').html('').hide();
        }
        if (keyword.length > 2) {
            $.ajax({
                type: "GET",
                dataType: 'json',
                url: autocomplete_url,
                data: { keyword: keyword },
                success: function (response) {
                    if (response.status == 'Success') {
                        $('#search_box_main_div').html('');
                        if (response.data.length != 0) {
                            let search_box_category_template = _.template($('#search_box_main_div_template').html());
                            $("#search_box_main_div").append(search_box_category_template({ results: response.data })).show();
                        } else {
                            $("#search_box_main_div").html('<p class="text-center my-3">No result found. Please try a new search</p>').show();
                        }
                    }
                }
            });
        }
    }
    // // Cabbooking Js Code 
    //     $('.add-more-location').click(function(){
    //         $(".location-inputs").append("<li class='d-block mb-3 dots apdots map-icon'><input class='form-control pickup-text' type='text' placeholder='Choose destination, or click on the map...' /><i class='fa fa-times ml-1 apremove' aria-hidden='true'></i></li>");
    //         const height = document.querySelector('.location-box').offsetHeight;
    //         var getheight = height;
    //         var abc = 156;
    //         var minheight = parseFloat(getheight + abc)+'px';
    //         $('.location-list').attr('style', 'height:calc(100vh - '+minheight+' !important');
    //     });

    //     $('.location-inputs').on('click','.apremove',function(){
    //         $(this).closest('.apdots').remove();
    //         const height = document.querySelector('.location-box').offsetHeight;
    //         var getheight = height;
    //         var abc = 156;
    //         var minheight = parseFloat(getheight + abc)+'px';
    //         $('.location-list').attr('style', 'height:calc(100vh - '+minheight+' !important');
    //     });
    // // Cabbooking Js Code

    // // Cabbooking Js Code 
    //     $('.add-more-location').click(function(){
    //         $(".location-inputs").append("<li class='d-block mb-3 dots apdots map-icon'><input class='form-control pickup-text' type='text' placeholder='Choose destination, or click on the map...' /><i class='fa fa-times ml-1 apremove' aria-hidden='true'></i></li>");
    //         const height = document.querySelector('.location-box').offsetHeight;
    //         var getheight = height;
    //         var abc = 190;
    //         var minheight = parseFloat(getheight + abc)+'px';
    //         $('.vehical-container').attr('style', 'height:calc(100vh - '+minheight+' !important');
    //     });

    //     $('.location-inputs').on('click','.apremove',function(){
    //         $(this).closest('.apdots').remove();
    //         const height = document.querySelector('.location-box').offsetHeight;
    //         var getheight = height;
    //         var abc = 190;
    //         var minheight = parseFloat(getheight + abc)+'px';
    //         $('.vehical-container').attr('style', 'height:calc(100vh - '+minheight+' !important');
    //     });
    // // Cabbooking Js Code  


    // top Moveble Tabbar on top js code
    /*$(".navigation-tab-item").click(function() {
        $(".navigation-tab-item").removeClass("active");
        $(this).addClass("active");
        if($('body').attr('dir') == 'rtl'){
            $(".navigation-tab-overlay").css({
                right: $(this).prevAll().length * 130 + "px"
            });
        }else{
            $(".navigation-tab-overlay").css({
                left: $(this).prevAll().length * 100 + "px"
            });
        }
    });*/

    if ($('#cart_main_page').length > 0) {
        let address_checked = $("input:radio[name='address_id']").is(":checked");
        if (address_checked) {
            $('#order_placed_btn').prop('disabled', false);
        } else {
            $('#order_placed_btn').prop('disabled', true);
        }
        $("form").submit(function (e) {
            let address_id = $("input:radio[name='address_id']").is(":checked");
            if (!address_id) {
                alert('Address field required.');
                return false;
            }
        });
    }
    var card = '';
    var stripe = '';

    $(".search_btn").click(function () {
        $(".search_warpper").slideToggle("slow");
    });

    $(".close_btn").click(function () {
        $(".search_warpper").slideUp("slow");
    });
    $(document).delegate(".mobile-back","click", function () {
        $(".sm-horizontal").css("right", "-410px");
    });
    function settingData(type = '', v1 = '', v2 = '') {
        $.ajax({
            type: "post",
            dataType: "json",
            url: change_primary_data_url,
            data: {
                "type": type,
                "value1": v1,
                "value2": v2
            },
            success: function (response) {
                location.reload();
            },
            error: function (data) {
                location.reload();
            },
        });
    }
    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        console.log(charCode);
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    $('.addWishList').click(function () {
        var sku = $(this).attr('proSku');
        var _this = $(this);
        $.ajax({
            type: "post",
            dataType: "json",
            url: add_to_whishlist_url,
            data: {
                "_token": $('meta[name="_token"]').attr('content'),
                "sku": sku,
                "variant_id": $('#prod_variant_id').val()
            },
            success: function (res) {
                if (res.status == "success") {
                    if (_this.hasClass('btn-solid')) {
                        if (res.message.indexOf('added') !== -1) {
                            _this.text('REMOVE FROM WISHLIST');
                        } else {
                            _this.text('ADD TO WISHLIST');
                        }
                    }
                } else {
                    location.reload();
                }
            }
        });
    });
    $('.customerLang').click(function () {
        var changLang = $(this).attr('langId');
        settingData('language', changLang);
    });

    $('.customerCurr').click(function () {
        var changcurrId = $(this).attr('currId');
        var changSymbol = $(this).attr('currSymbol');
        settingData('currency', changcurrId, changSymbol);
    });

    function stripeInitialize() {
        stripe = Stripe(stripe_publishable_key);
        var elements = stripe.elements();
        var style = {
            base: { fontSize: '16px', color: '#32325d', borderColor: '#ced4da' },
        };
        card = elements.create('card', { hidePostalCode: true, style: style });
        card.mount('#stripe-card-element');
    }

    if ($("#stripe-card-element").length > 0) {
        stripeInitialize();
    }

    $(document).delegate(".subscribe_btn", "click", function () {
        var sub_id = $(this).attr('data-id');
        $.ajax({
            type: "get",
            dataType: "json",
            url: check_active_subscription_url.replace(":id", sub_id),
            success: function (response) {
                if (response.status == "Success") {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        url: subscription_payment_options_url.replace(":id", sub_id),
                        success: function (response) {
                            if (response.status == "Success") {
                                $("#subscription_payment #subscription_title").html(response.sub_plan.title);
                                $("#subscription_payment #subscription_price").html(response.currencySymbol + response.sub_plan.price);
                                $("#subscription_payment #subscription_frequency").html(response.sub_plan.frequency);
                                $("#subscription_payment #features_list").html(response.sub_plan.features);
                                $("#subscription_payment #subscription_id").val(sub_id);
                                $("#subscription_payment #subscription_amount").val(response.sub_plan.price);
                                $("#subscription_payment #subscription_payment_methods").html('');
                                let payment_method_template = _.template($('#payment_method_template').html());
                                $("#subscription_payment #subscription_payment_methods").append(payment_method_template({ payment_options: response.payment_options }));
                                if (response.payment_options == '') {
                                    $("#subscription_payment .subscription_confirm_btn").hide();
                                }
                                $("#subscription_payment").modal("show");
                                stripeInitialize();
                            }
                        }, error: function (error) {
                            var response = $.parseJSON(error.responseText);
                            let error_messages = response.message;
                            $("#error_response .message_body").html(error_messages);
                            $("#error_response").modal("show");
                        }
                    });
                }
            }, error: function (error) {
                var response = $.parseJSON(error.responseText);
                let error_messages = response.message;
                $("#error_response .message_body").html(error_messages);
                $("#error_response").modal("show");
            }
        });
    });
    $(document).delegate(".subscription_confirm_btn", "click", function () {
        var _this = $(".subscription_confirm_btn");
        _this.attr("disabled", true);
        var selected_option = $("input[name='subscription_payment_method']:checked");
        var payment_option_id = selected_option.data("payment_option_id");
        if ((selected_option.length > 0) && (payment_option_id > 0)) {
            if (payment_option_id == 4) {
                stripe.createToken(card).then(function (result) {
                    if (result.error) {
                        $('#stripe_card_error').html(result.error.message);
                        _this.attr("disabled", false);
                    } else {
                        $("#card_last_four_digit").val(result.token.card.last4);
                        $("#card_expiry_month").val(result.token.card.exp_month);
                        $("#card_expiry_year").val(result.token.card.exp_year);
                        paymentViaStripe(result.token.id, '', payment_option_id);
                    }
                });
            } else {
                paymentViaPaypal('', payment_option_id);
            }
        } else {
            _this.attr("disabled", false);
            success_error_alert('error', 'Please select any payment option', "#subscription_payment .payment_response");
        }
    });

    function productRemove(cartproduct_id, vendor_id) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: delete_cart_product_url,
            data: { cartproduct_id: cartproduct_id },
            beforeSend: function () {
                if ($("#cart_table").length > 0) {
                    $(".spinner-box").show();
                    $("#cart_table").hide();
                }
            },
            success: function (data) {
                if (data.status == 'success') {
                    $('#cart_product_' + cartproduct_id).remove();
                    $('#shopping_cart1_' + cartproduct_id).remove();
                    $('#tr_vendor_products_' + cartproduct_id).remove();
                    if ($("#tbody_" + vendor_id + " > vendor_products_tr").length == 0) {
                        $('#tbody_' + vendor_id).remove();
                        $('#thead_' + vendor_id).remove();
                    }
                    // if ($("[id^=tr_vendor_products_]").length == 0) {
                    //     if ($("#cart_main_page").length) {
                    //         $("#cart_main_page").html('');
                    //         $('#tbody_' + vendor_id).remove()
                    //         let empty_cart_template = _.template($('#empty_cart_template').html());
                    //         $("#cart_main_page").append(empty_cart_template());
                    //     }
                    // }
                    if ($("[id^=cart_product_]").length == 0) {
                        $(".shopping-cart").html('');
                    }
                    cartTotalProductCount();
                    cartHeader();

                    if ($('#show_plus_minus' + cartproduct_id).length != 0) {
                        $('#show_plus_minus' + cartproduct_id).find('.input_qty').val(1);
                        $('#show_plus_minus' + cartproduct_id).hide();
                        $('#add_button_href' + cartproduct_id).show();

                    }
                    if ($('#next-button-ondemand-2').length != 0) {
                        $("#next-button-ondemand-2").hide();
                    }
                }
            }
        });
    }

    $(document).on("change", "input:radio[name='address_id']", function () {
        if ($(this).val()) {
            $('#order_placed_btn').prop('disabled', false);
            if ($("#cart_table").length > 0) {
                $(".spinner-box").show();
                $("#cart_table").hide();
            }
            cartHeader($(this).val());
        }
    });
    $(document).on("click", "#order_placed_btn", function () {
        $('.alert-danger').html('');
        if ((typeof guest_cart != undefined) && (guest_cart == 1)) {
            window.location.href = login_url;
        }
        var address = $("input[name='address_id']").val();
        if ((vendor_type == 'delivery') && ((address == '') || (address < 1))) {
            success_error_alert('error', 'Please add a valid address to continue', ".cart_response");
            return false;
        }
        var task_type = $("input[name='task_type']:checked").val();
        var schedule_dt = $("#schedule_datetime").val();
        var now = new Date().toISOString();
        if( task_type == 'schedule' ){
            if(schedule_dt == ''){
                success_error_alert('error', 'Schedule date time is required', ".cart_response");
                return false;
            }
            else if(schedule_dt < now){
                success_error_alert('error', 'Invalid schedule date time', ".cart_response");
                return false;
            }
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: update_cart_schedule,
            data: { task_type: task_type, schedule_dt: schedule_dt },
            success: function (response) {
                if (response.status == "Success") {
                    $.ajax({
                        data: {},
                        type: "POST",
                        dataType: 'json',
                        url: payment_option_list_url,
                        success: function (response) {
                            if (response.status == "Success") {
                                $('#v_pills_tab').html('');
                                $('#v_pills_tabContent').html('');
                                let payment_method_template = _.template($('#payment_method_template').html());
                                $("#v_pills_tab").append(payment_method_template({ payment_options: response.data }));
                                let payment_method_tab_pane_template = _.template($('#payment_method_tab_pane_template').html());
                                $("#v_pills_tabContent").append(payment_method_tab_pane_template({ payment_options: response.data }));
                                $('#proceed_to_pay_modal').modal('show');
                                $('#proceed_to_pay_modal #total_amt').html($('#cart_total_payable_amount').html());
                                stripeInitialize();
                            }
                        }, error: function (error) {
                            var response = $.parseJSON(error.responseText);
                            let error_messages = response.message;
                            $.each(error_messages, function (key, error_message) {
                                $('#min_order_validation_error_' + error_message.vendor_id).html(error_message.message).show();
                            });
                        }
                    });
                }
            },
            error: function (error) {
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, ".cart_response");
                $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
            }
        });
    });
    $(document).delegate("#topup_wallet_btn", "click", function () {
        $.ajax({
            data: {},
            type: "POST",
            async: false,
            dataType: 'json',
            url: wallet_payment_options_url,
            success: function (response) {
                if (response.status == "Success") {
                    $('#wallet_payment_methods').html('');
                    let payment_method_template = _.template($('#payment_method_template').html());
                    $("#wallet_payment_methods").append(payment_method_template({ payment_options: response.data }));
                    if (response.data == '') {
                        $("#topup_wallet .topup_wallet_confirm").hide();
                    } else {
                        stripeInitialize();
                    }
                }
            }, error: function (error) {
                var response = $.parseJSON(error.responseText);
                let error_messages = response.message;
            }
        });
    });

    let queryString = window.location.search;
    let path = window.location.pathname;
    let urlParams = new URLSearchParams(queryString);
    if ((urlParams.has('PayerID')) && (urlParams.has('token'))) {
        let tipAmount = 0;
        if (urlParams.has('tip')) {
            tipAmount = urlParams.get('tip');
        }
        $(document).ajaxStop(function () {
            paymentSuccessViaPaypal(urlParams.get('amount'), urlParams.get('token'), urlParams.get('PayerID'), path, tipAmount);
        });
    }

    function paymentViaStripe(stripe_token, address_id, payment_option_id) {
        let total_amount = 0;
        let tip = 0;
        let cartElement = $("input[name='cart_total_payable_amount']");
        let walletElement = $("input[name='wallet_amount']");
        let subscriptionElement = $("input[name='subscription_amount']");
        let tipElement = $("#cart_tip_amount");
        let ajaxData = [];
        if (cartElement.length > 0) {
            total_amount = cartElement.val();
            tip = tipElement.val();
            ajaxData.push({ name: 'tip', value: tip });
        }
        else if (walletElement.length > 0) {
            total_amount = walletElement.val();
        }
        else if (subscriptionElement.length > 0) {
            total_amount = subscriptionElement.val();
            ajaxData = $("#subscription_payment_form").serializeArray();
        }
        ajaxData.push(
            { name: 'stripe_token', value: stripe_token },
            { name: 'amount', value: total_amount },
            { name: 'payment_option_id', value: payment_option_id }
        );
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_stripe_url,
            data: ajaxData,
            success: function (resp) {
                if (resp.status == 'Success') {
                    if (path.indexOf("cart") !== -1) {
                        placeOrder(address_id, payment_option_id, resp.data.id, tip);
                    }
                    else if (path.indexOf("wallet") !== -1) {
                        creditWallet(total_amount, payment_option_id, resp.data.id);
                    }
                    else if (path.indexOf("subscription") !== -1) {
                        userSubscriptionPurchase(total_amount, payment_option_id, resp.data.id);
                    }
                } else {
                    if (path.indexOf("cart") !== -1) {
                        success_error_alert('error', resp.message, "#stripe-payment-form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    }
                    else if (path.indexOf("wallet") !== -1) {
                        success_error_alert('error', resp.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    }
                    else if (path.indexOf("subscription") !== -1) {
                        success_error_alert('error', resp.message, "#subscription_payment_form .payment_response");
                        $(".subscription_confirm_btn").removeAttr("disabled");
                    }
                }
            },
            error: function (error) {
                var response = $.parseJSON(error.responseText);
                if (path.indexOf("cart") !== -1) {
                    success_error_alert('error', response.message, "#stripe-payment-form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                }
                else if (path.indexOf("wallet") !== -1) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
                else if (path.indexOf("subscription") !== -1) {
                    success_error_alert('error', response.message, "#subscription_payment_form .payment_response");
                    $(".subscription_confirm_btn").removeAttr("disabled");
                }
            }
        });
    }
    function paymentViaPaypal() {
        let total_amount = 0;
        let tip = 0;
        let tipElement = $("#cart_tip_amount");
        let cartElement = $("input[name='cart_total_payable_amount']");
        let walletElement = $("input[name='wallet_amount']");
        let ajaxData = {};
        if (cartElement.length > 0) {
            total_amount = cartElement.val();
            tip = tipElement.val();
            ajaxData.tip = tip;
        }
        else if (walletElement.length > 0) {
            total_amount = walletElement.val();
        }
        ajaxData.amount = total_amount;
        ajaxData.returnUrl = path;
        ajaxData.cancelUrl = path;
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_paypal_url,
            data: ajaxData,
            success: function (response) {
                if (response.status == "Success") {
                    window.location.href = response.data;
                } else {
                    if (cartElement.length > 0) {
                        success_error_alert('error', response.message, "#paypal-payment-form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    }
                    else if (walletElement.length > 0) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function (error) {
                var response = $.parseJSON(error.responseText);
                if (cartElement.length > 0) {
                    success_error_alert('error', response.message, "#paypal-payment-form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                }
                else if (walletElement.length > 0) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }
    function paymentSuccessViaPaypal(amount, token, payer_id, path, tip = 0) {
        let address_id = 0;
        if (path.indexOf("cart") !== -1) {
            $('#order_placed_btn').trigger('click');
            $('#v-pills-paypal-tab').trigger('click');
            $("#order_placed_btn, .proceed_to_pay").attr("disabled", true);
            address_id = $("input:radio[name='address_id']:checked").val();
        }
        else if (path.indexOf("wallet") !== -1) {
            $('#topup_wallet_btn').trigger('click');
            $('#wallet_topup_form #radio-paypal').prop("checked", true);
            $(".topup_wallet_confirm").attr("disabled", true);
        }
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: payment_success_paypal_url,
            data: { 'amount': amount, 'token': token, 'PayerID': payer_id },
            success: function (response) {
                if (response.status == "Success") {
                    if (path.indexOf("cart") !== -1) {
                        placeOrder(address_id, 3, response.data, tip);
                    }
                    else if (path.indexOf("wallet") !== -1) {
                        creditWallet(amount, 3, response.data);
                    }
                } else {
                    if (path.indexOf("cart") !== -1) {
                        success_error_alert('error', response.message, "#paypal-payment-form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    }
                    else if (path.indexOf("wallet") !== -1) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function (error) {
                var response = $.parseJSON(error.responseText);
                if (path.indexOf("cart") !== -1) {
                    success_error_alert('error', response.message, "#paypal-payment-form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                }
                else if (path.indexOf("wallet") !== -1) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }
    
    window.placeOrder = function placeOrder(address_id = 0, payment_option_id, transaction_id, tip = 0) {
        var task_type = $("input[name='task_type']:checked").val();
        var schedule_dt = $("#schedule_datetime").val();
        if( (task_type == 'schedule') && (schedule_dt == '') ){
            $("#proceed_to_pay_modal").modal('hide');
            $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
            success_error_alert('error', 'Schedule date time is required', ".cart_response");
            return false;
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: place_order_url,
            data: { address_id: address_id, payment_option_id: payment_option_id, transaction_id: transaction_id, tip: tip, task_type: task_type, schedule_dt: schedule_dt },
            success: function (response) {
                if (response.status == "Success") {
                    window.location.href = base_url+'/order/success/'+response.data.id;
                }else{
                    $("#order_placed_btn, .proceed_to_pay").attr("disabled", false);
                }
            },
            error: function (error) {
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, ".payment_response");
                $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
            }
        });
    }
    $(document).on("click", ".proceed_to_pay", function () {
        $("#order_placed_btn, .proceed_to_pay").attr("disabled", true);
        let address_id = $("input:radio[name='address_id']:checked").val();
        let payment_option_id = $('#proceed_to_pay_modal #v_pills_tab').find('.active').data('payment_option_id');
        let tip = $("#cart_tip_amount").val();
        if (payment_option_id == 1) {
            placeOrder(address_id, payment_option_id, '', tip);
        } else if (payment_option_id == 4) {
            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    $('#stripe_card_error').html(result.error.message);
                    $("#order_placed_btn, .proceed_to_pay").attr("disabled", false);
                } else {
                    paymentViaStripe(result.token.id, address_id, payment_option_id);
                }
            });
        } else if (payment_option_id == 3) {
            paymentViaPaypal(address_id, payment_option_id);
        }
        else if (payment_option_id == 5) {
            paymentViaPaystack(address_id, payment_option_id);
        }
        else if (payment_option_id == 6) {
            paymentViaPayfast(address_id, payment_option_id);
        }
    });
    function creditWallet(amount, payment_option_id, transaction_id) {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: credit_wallet_url,
            data: { wallet_amount: amount, payment_option_id: payment_option_id, transaction_id: transaction_id },
            success: function (response) {
                if (response.status == "Success") {
                    location.href = path;
                    // $("#topup_wallet").modal("hide");
                    // $(".table.wallet-transactions table-body").html('');
                    $(".wallet_balance").text(response.data.wallet_balance);
                    success_error_alert('success', response.message, "#wallet_response");
                    // let wallet_transactions_template = _.template($('#wallet_transactions_template').html());
                    // $(".table.wallet-transactions table-body").append(wallet_transactions_template({wallet_transactions:response.data.transactions}));
                } else {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").attr("disabled", false);
                }
            },
            error: function (error) {
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                $(".topup_wallet_confirm").removeAttr("disabled");
            }
        });
    }
    function userSubscriptionPurchase(amount, payment_option_id, transaction_id) {
        var id = $("#subscription_payment_form #subscription_id").val();
        if (id != '') {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: user_subscription_purchase_url.replace(":id", id),
                data: { amount: amount, payment_option_id: payment_option_id, transaction_id: transaction_id },
                success: function (response) {
                    if (response.status == "Success") {
                        location.href = path;
                    } else {
                        success_error_alert('error', response.message, "#subscription_payment_form .payment_response");
                        $(".subscription_confirm_btn").attr("disabled", false);
                    }
                },
                error: function (error) {
                    var response = $.parseJSON(error.responseText);
                    success_error_alert('error', response.message, "#subscription_payment_form .payment_response");
                    $(".subscription_confirm_btn").removeAttr("disabled");
                }
            });
        } else {
            success_error_alert('error', 'Invalid data', "#wallet_topup_form .payment_response");
            $(".topup_wallet_confirm").removeAttr("disabled");
        }
    }
    $(document).on("click", ".topup_wallet_confirm", function () {
        // $(".topup_wallet_confirm").attr("disabled", true);
        let payment_option_id = $('#wallet_payment_methods input[name="wallet_payment_method"]:checked').data('payment_option_id');
        if (payment_option_id == 4) {
            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    $('#stripe_card_error').html(result.error.message);
                    $(".topup_wallet_confirm").attr("disabled", false);
                } else {
                    console.log(result.token.id);
                    // paymentViaStripe(result.token.id, '', payment_option_id);
                }
            });
        } else if (payment_option_id == 3) {
            paymentViaPaypal('', payment_option_id);
        }
    });
    $(document).on("click", ".remove_promo_code_btn", function () {
        let cart_id = $(this).data('cart_id');
        let coupon_id = $(this).data('coupon_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: promo_code_remove_url,
            data: { coupon_id: coupon_id, cart_id: cart_id },
            success: function (response) {
                if (response.status == "Success") {
                    cartHeader();
                }
            }
        });
    });
    $(document).on("click", ".promo_code_list_btn", function () {
        let amount = $(this).data('amount');
        let cart_id = $(this).data('cart_id');
        let vendor_id = $(this).data('vendor_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: promocode_list_url,
            data: { vendor_id: vendor_id, amount: amount, cart_id: cart_id },
            success: function (response) {
                $("#promo_code_list_main_div").html('');
                if (response.status == "Success") {
                    $('#refferal-modal').modal('show');
                    if (response.data.length != 0) {
                        let promo_code_template = _.template($('#promo_code_template').html());
                        $("#promo_code_list_main_div").append(promo_code_template({ promo_codes: response.data, vendor_id: vendor_id, cart_id: cart_id, amount: amount }));
                    } else {
                        let no_promo_code_template = _.template($('#no_promo_code_template').html());
                        $("#promo_code_list_main_div").append(no_promo_code_template());
                    }
                }
            }
        });
    });
    $(document).on("click", ".apply_promo_code_btn", function () {
        let amount = $(this).data('amount');
        let cart_id = $(this).data('cart_id');
        let vendor_id = $(this).data('vendor_id');
        let coupon_id = $(this).data('coupon_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: apply_promocode_coupon_url,
            data: { cart_id: cart_id, vendor_id: vendor_id, coupon_id: coupon_id, amount: amount },
            success: function (response) {
                if (response.status == "Success") {
                    $('#refferal-modal').modal('hide');
                    cartHeader();
                }
            },
            error: function (reject) {
                if (reject.status === 422) {
                    var message = $.parseJSON(reject.responseText);
                    alert(message.message);
                }
            }
        });
    });
    $(document).on("click", ".remove-product", function () {
        let vendor_id = $(this).data('vendor_id');
        let cartproduct_id = $(this).data('product');
        productRemove(cartproduct_id, vendor_id);
    });
    $(document).on("click", ".remove_product_via_cart", function () {
        $('#remove_item_modal').modal('show');
        let vendor_id = $(this).data('vendor_id');
        let cartproduct_id = $(this).data('product');
        $('#remove_item_modal #vendor_id').val(vendor_id);
        $('#remove_item_modal #cartproduct_id').val(cartproduct_id);
    });
    $(document).on("click", "#remove_product_button", function () {
        $('#remove_item_modal').modal('hide');
        let vendor_id = $('#remove_item_modal #vendor_id').val();
        let cartproduct_id = $('#remove_item_modal #cartproduct_id').val();
        productRemove(cartproduct_id, vendor_id);
    });
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    function initialize() {
        var input = document.getElementById('address');
        if (input) {
            var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                // console.log(place);
                // document.getElementById('city').value = place.name;
                document.getElementById('longitude').value = place.geometry.location.lng();
                document.getElementById('latitude').value = place.geometry.location.lat();
                for (let i = 1; i < place.address_components.length; i++) {
                    let mapAddress = place.address_components[i];
                    if (mapAddress.long_name != '') {
                        let streetAddress = '';
                        if (mapAddress.types[0] == "street_number") {
                            streetAddress += mapAddress.long_name;
                        }
                        if (mapAddress.types[0] == "route") {
                            streetAddress += mapAddress.short_name;
                        }
                        if ($('#street').length > 0) {
                            document.getElementById('street').value = streetAddress;
                        }
                        if (mapAddress.types[0] == "locality") {
                            document.getElementById('city').value = mapAddress.long_name;
                        }
                        if (mapAddress.types[0] == "administrative_area_level_1") {
                            document.getElementById('state').value = mapAddress.long_name;
                        }
                        if (mapAddress.types[0] == "postal_code") {
                            document.getElementById('pincode').value = mapAddress.long_name;
                        } else {
                            document.getElementById('pincode').value = '';
                        }
                        if (mapAddress.types[0] == "country") {
                            var country = document.getElementById('country');
                            if (typeof country.options != "undefined") {
                                for (let i = 0; i < country.options.length; i++) {
                                    if (country.options[i].text.toUpperCase() == mapAddress.long_name.toUpperCase()) {
                                        country.value = country.options[i].value;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            });
        }
    }
    initialize();
    // google.maps.event.addDomListener(window, 'load', initialize);
    function cartTotalProductCount() {
        let cart_qty_total = 0;
        $(".shopping-cart li").each(function (index) {
            if ($(this).data('qty')) {
                cart_qty_total += $(this).data('qty');
            }
        });
        if (cart_qty_total > 0) {
            $('#cart_qty_span, .cart_qty_cls').html(cart_qty_total).show();
        } else {
            $('#cart_qty_span, .cart_qty_cls').html(cart_qty_total).hide();
        }
    }
    function displayMapLocation(latitude, longitude, elementID) {
        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(latitude, longitude);

        const map = new google.maps.Map(document.getElementById(elementID), {
            center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            zoom: 13
        });

        const marker = new google.maps.Marker({
            map: map,
            position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
        });

        // geocoder.geocode(
        //     { 'latLng': latlng },
        //     function (results, status) {
        //         if (status == google.maps.GeocoderStatus.OK) {
        //         }
        //         else {
        //             $("#address-input").val("Geocoder failed due to: " + status);
        //         }
        //     }
        // );
    }
    function cartHeader(address_id) {
        $(".shopping-cart").html("");
        $(".spinner-box").show();
        $.ajax({
            data: { address_id: address_id },
            type: "get",
            dataType: 'json',
            url: cart_product_url,
            success: function (response) {
                if (response.status == "success") {
                    $("#cart_table").html('');
                    $(".spinner-box").hide();
                    var cart_details = response.cart_details;
                    if (response.cart_details.length != 0) {
                        if (response.cart_details.products.length != 0) {
                            let header_cart_template = _.template($('#header_cart_template').html());
                            $("#header_cart_main_ul").append(header_cart_template({ cart_details: cart_details, show_cart_url: show_cart_url }));
                            if ($('#cart_main_page').length != 0) {
                                let cart_template = _.template($('#cart_template').html());
                                $("#cart_table").append(cart_template({cart_details:cart_details}));
                                $('#placeorder_form .left_box').html(''); 
                                $('#placeorder_form .left_box').html(cart_details.left_section);
                                if (vendor_type != 'delivery') {
                                    var latitude = $('#latitude').val();
                                    var longitude = $('#longitude').val();
                                    displayMapLocation(latitude, longitude, 'vendor-address-map');
                                }
                                initialize();
                                if (cart_details.deliver_status == 0) {
                                    $("#order_placed_btn").attr("disabled", true);
                                    $("#order_placed_btn").addClass("d-none");
                                } else {
                                    $("#order_placed_btn").removeAttr("disabled");
                                    $("#order_placed_btn").removeClass("d-none");
                                }
                            }
                            cartTotalProductCount();
                            
                              
                            if($("#header_cart_template_ondemand").length != 0) { 
                          
                                let header_cart_template_ondemand = _.template($('#header_cart_template_ondemand').html());
                                $("#header_cart_main_ul_ondemand").append(header_cart_template_ondemand({ cart_details: cart_details, show_cart_url: show_cart_url }));
                                $("#next-button-ondemand-2").show();
                                $('#placeorder_form_ondemand .left_box').html(''); 
                                $('#placeorder_form_ondemand .left_box').html(cart_details.left_section);
                                initialize();
                                if(cart_details.deliver_status == 0){
                                    $("#order_placed_btn").attr("disabled", true);
                                    $("#order_placed_btn").addClass("d-none");
                                }else{
                                    $("#order_placed_btn").removeAttr("disabled");
                                    $("#order_placed_btn").removeClass("d-none");
                                }
                            }

                        } else {
                            if ($('#cart_main_page').length != 0) {
                                $('#cart_main_page').html('');
                                let empty_cart_template = _.template($('#empty_cart_template').html());
                                $("#cart_main_page").append(empty_cart_template());
                            }
                        }
                    }
                }
            },
            complete: function (data) {
                if ($("#cart_table").length > 0) {
                    $(".spinner-box").hide();
                    $("#cart_table").show();
                }

                if ($("#header_cart_main_ul_ondemand").length > 0) {
                    $(".spinner-box").hide();
                    $("#header_cart_main_ul_ondemand").show();
                    $(".number .qty-minus-ondemand .fa").removeAttr("class").addClass("fa fa-minus");
                    $(".number .qty-plus-ondemand .fa").removeAttr("class").addClass("fa fa-plus");
                }

                if ($(".number .fa-spinner fa-pulse").length > 0) {
                    $(".number .qty-minus .fa").removeAttr("class").addClass("fa fa-minus");
                    $(".number .qty-plus .fa").removeAttr("class").addClass("fa fa-plus");

                }
            }
        });
    }
    function updateQuantity(cartproduct_id, quantity, base_price, iconElem = '') {
        if (iconElem != '') {
            let elemClasses = $(iconElem).attr("class");
            $(iconElem).removeAttr("class").addClass("fa fa-spinner fa-pulse");
        }
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: update_qty_url,
            data: { "quantity": quantity, "cartproduct_id": cartproduct_id },
            success: function (response) {
                var latest_price = parseInt(base_price) * parseInt(quantity);
                $('#product_total_amount_' + cartproduct_id).html('$' + latest_price);
                cartHeader();
            },
            error: function (err) {
                if ($(".number .fa-spinner fa-pulse").length > 0) {
                    $(".number .qty-minus .fa").removeAttr("class").addClass("fa fa-minus");
                    $(".number .qty-plus .fa").removeAttr("class").addClass("fa fa-plus");
                }
            }
        });
    }
    $(document).on('click', '.tip_radio_controls .tip_radio', function () {
        var tip = $(this).val();
        var amount_payable = $("#cart_payable_amount_original").val();
        var currency = $("#cart_payable_amount_original").attr('data-curr');
        // if this was previously checked
        if ($(this).hasClass("active")) {
            $(this).prop('checked', false);
            $(this).removeClass('active');
            setTipAmount(0, amount_payable, currency);
        } else {
            $('.tip_radio_controls .tip_radio').removeClass("active");
            $(this).addClass('active');
            setTipAmount(tip, amount_payable, currency);
        }

    });
    function setTipAmount(tip, amount_payable, currency) {
        if (tip != 'custom') {
            if ((tip == '') || (isNaN(tip))) {
                tip = 0;
            }
            amount_payable = parseFloat(amount_payable) + parseFloat(tip);
            $("#cart_tip_amount").val(parseFloat(tip).toFixed(2));
            $("#cart_total_payable_amount").html(currency + parseFloat(amount_payable).toFixed(2));
            $(".custom_tip").addClass("d-none");
            $("#custom_tip_amount").val('');
        }
        else {
            $("#cart_total_payable_amount").text(currency + parseFloat(amount_payable).toFixed(2));
            $("#cart_tip_amount").val(0);
            $(".custom_tip").removeClass("d-none");
            $("#custom_tip_amount").focus();
        }
        $("input[name='cart_total_payable_amount']").val(parseFloat(amount_payable).toFixed(2));
    }
    $(document).on('keyup', '#custom_tip_amount', function () {
        var tip = $(this).val();
        if ((tip == '') || (isNaN(tip))) {
            tip = 0;
        }
        var amount_elem = $("#cart_payable_amount_original");
        var currency = amount_elem.attr('data-curr');
        var amount_payable = amount_elem.val();
        amount_payable = parseFloat(amount_payable) + parseFloat(tip);
        $("#cart_tip_amount").val(parseFloat(tip).toFixed(2));
        $("#cart_total_payable_amount").html(currency + parseFloat(amount_payable).toFixed(2));
        $("input[name='cart_total_payable_amount']").val(parseFloat(amount_payable).toFixed(2));
    });
    $(document).on('click', '.qty-minus', function () {
        let base_price = $(this).data('base_price');
        let cartproduct_id = $(this).attr("data-id");
        let qty = $('#quantity_' + cartproduct_id).val();
        $(this).find('.fa').removeClass("fa-minus").addClass("fa-spinner fa-pulse");
        if (qty > 1) {
            $('#quantity_' + cartproduct_id).val(--qty);
            updateQuantity(cartproduct_id, qty, base_price);
        } else {
            // alert('remove this product');
            $('#remove_item_modal').modal('show');
            let vendor_id = $(this).data('vendor_id');
            $('#remove_item_modal #vendor_id').val(vendor_id);
            $('#remove_item_modal #cartproduct_id').val(cartproduct_id);
        }
    });
    $(document).on('click', '.qty-plus', function () {
        let base_price = $(this).data('base_price');
        let cartproduct_id = $(this).attr("data-id");
        let qty = $('#quantity_' + cartproduct_id).val();
        $('#quantity_' + cartproduct_id).val(++qty);
        $(this).find('.fa').removeClass("fa-minus").addClass("fa-spinner fa-pulse");
        updateQuantity(cartproduct_id, qty, base_price);
    });
    cartHeader();
    $(document).on("click", "#cancel_save_address_btn", function () {
        $('#add_new_address').show();
        $('#add_new_address_btn').show();
        $('#add_new_address_form').hide();
    });
    $(document).on("click", "#add_new_address_btn", function () {
        $(this).hide();
        $('#add_new_address_form').show();
    });
    $(document).on("click", "#save_address", function () {
        let city = $('#add_new_address_form #city').val();
        let state = $('#add_new_address_form #state').val();
        let street = $('#add_new_address_form #street').val();
        let address = $('#add_new_address_form #address').val();
        let country = $('#add_new_address_form #country').val();
        let pincode = $('#add_new_address_form #pincode').val();
        let type = $("input[name='address_type']:checked").val();
        let latitude = $('#add_new_address_form #latitude').val();
        let longitude = $('#add_new_address_form #longitude').val();
        $.ajax({
            type: "post",
            dataType: "json",
            url: user_store_address_url,
            data: {
                "city": city,
                "type": type,
                "state": state,
                "address": address,
                "country": country,
                "pincode": pincode,
                "latitude": latitude,
                "longitude": longitude,
            },
            beforeSend: function () {
                if ($("#cart_table").length > 0) {
                    $(".spinner-box").show();
                    $("#cart_table").hide();
                }
            },
            success: function (response) {
                if ($("#add_edit_address").length > 0) {
                    $("#add_edit_address").modal('hide');
                    location.reload();
                }
                else {
                    $('#add_new_address_form').hide();
                   // let address_template = _.template($('#address_template').html());
                    if(address.length > 0){
                     //   $('#order_placed_btn').attr('disabled', false);
                     //   $("#address_template_main_div").append(address_template({address:response.address}));
                        cartHeader(response.address.id);
                    }
                }
            },
            error: function (reject) {
                if ($("#cart_table").length > 0) {
                    $(".spinner-box").hide();
                    $("#cart_table").show();
                }
                if (reject.status === 422) {
                    var message = $.parseJSON(reject.responseText);
                    $.each(message.errors, function (key, val) {
                        $("#" + key + "_error").text(val[0]);
                    });
                }
            }
        });
    });

    $(document).on("click", ".addToCart", function () {
        if (!$.hasAjaxRunning()) {
            addToCart();
        }
    });





    function addToCart() {
        var breakOut = false;
        $( ".productAddonSetOptions" ).each(function( index ) {
            var min_select = $(this).attr("data-min");
            var max_select = $(this).attr("data-max");
            var addon_set_title = $(this).attr("data-addonset-title");
            if( (min_select > 0) && ($(this).find(".productAddonOption:checked").length < min_select) ){
                alert("Minimum "+min_select+" "+addon_set_title+" required");
                breakOut = true;
                return false;
            }
            if( (max_select > 0) && ($(this).find(".productAddonOption:checked").length > max_select) ){
                alert("You can select maximum "+max_select+" "+addon_set_title);
                breakOut = true;
                return false;
            }
        });
        if(!breakOut){
            $.ajax({
                type: "post",
                dataType: "json",
                url: add_to_cart_url,
                data: {
                    "addonID": addonids,
                    "vendor_id": vendor_id,
                    "product_id": product_id,
                    "addonoptID": addonoptids,
                    "quantity": $('.quantity_count').val(),
                    "variant_id": $('#prod_variant_id').val(),
                },
                success: function (response) {
                    if (response.status == 'success') {
                        $(".shake-effect").effect("shake", { times: 3 }, 1200);
                        cartHeader();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (error) {
                    var response = $.parseJSON(error.responseText);
                    let error_messages = response.message;
                    alert(error_messages);
                },
            });
        }
    }


    // **********************************************   all function for ondemand services   *****************************************  ////////////////////////

    $(document).on("click", "#next-button-ondemand-3", function () {
        $('.alert-danger').html('');
        
        var task_type = 'schedule';
        var schedule_date = $("input[name='booking_date']:checked").val();
        var schedule_time = $("input[name='booking_time']:checked").val();
        var specific_instructions = $("#specific_instructions").val();
        
        var schedule_dt = schedule_date +' '+schedule_time;
        if( (task_type == 'schedule') && (schedule_dt == '') ){
            success_error_alert('error', 'Schedule date time is required', ".cart_response");
            return false;
        }
       
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: update_cart_schedule,
            data: { task_type: task_type, schedule_dt: schedule_dt ,specific_instructions:specific_instructions},
            success: function (response) {
                if (response.status == "Success") {
                    window.location.href = showCart;
                }
            },
            error: function (error) {
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, ".cart_response");
               
            }
        });
    });



    $(document).on("click", ".add_on_demand", function () {

        let that = $(this);

        var ajaxCall = 'ToCancelPrevReq';
        var vendor_id = that.data("vendor_id");
        var product_id = that.data("product_id");
        var add_to_cart_url = that.data("add_to_cart_url");
        var variant_id = that.data("variant_id");
        var addonids = [];
        var addonoptids = [];
        var show_plus_minus = "#show_plus_minus" + product_id;
        if (!$.hasAjaxRunning()) {
            addToCartOnDemand(ajaxCall, vendor_id, product_id, addonids, addonoptids, add_to_cart_url, variant_id, show_plus_minus, that);

        }

    });

    $(document).on('click', '.qty-minus-ondemand', function () {
        let base_price = $(this).data('base_price');
        let cartproduct_id = $(this).attr("data-id");
        let qty = $('#quantity_ondemand_' + cartproduct_id).val();
        $(this).find('.fa').removeClass("fa-minus").addClass("fa-spinner fa-pulse");
        if (qty > 1) {
            $('#quantity_ondemand_' + cartproduct_id).val(--qty);
            updateQuantityOnDemand(cartproduct_id, qty, base_price);
        } else {
            // alert('remove this product');
            $('#remove_item_modal').modal('show');
            let vendor_id = $(this).data('vendor_id');
            $('#remove_item_modal #vendor_id').val(vendor_id);
            $('#remove_item_modal #cartproduct_id').val(cartproduct_id);


        }
    });
    $(document).on('click', '.qty-plus-ondemand', function () {
        let base_price = $(this).data('base_price');
        let cartproduct_id = $(this).attr("data-id");
        let qty = $('#quantity_ondemand_' + cartproduct_id).val();
        $('#quantity_ondemand_' + cartproduct_id).val(++qty);
        $(this).find('.fa').removeClass("fa-minus").addClass("fa-spinner fa-pulse");
        updateQuantityOnDemand(cartproduct_id, qty, base_price);
    });


    function updateQuantityOnDemand(cartproduct_id, quantity, base_price, iconElem = '') {
        if (iconElem != '') {
            let elemClasses = $(iconElem).attr("class");
            $(iconElem).removeAttr("class").addClass("fa fa-spinner fa-pulse");
        }
        ajaxCall = $.ajax({
            type: "post",
            dataType: "json",
            url: update_qty_url,
            data: { "quantity": quantity, "cartproduct_id": cartproduct_id },
            success: function (response) {
                var latest_price = parseInt(base_price) * parseInt(quantity);
                $('#product_total_amount_' + cartproduct_id).html('$' + latest_price);
                cartHeader();
            },
            error: function (err) {
                if ($(".number .fa-spinner fa-pulse").length > 0) {
                    $(".number .qty-minus .fa").removeAttr("class").addClass("fa fa-minus");
                    $(".number .qty-plus .fa").removeAttr("class").addClass("fa fa-plus");
                }
            }
        });
    }

    // on demand add to cart 
    function addToCartOnDemand(ajaxCall, vendor_id, product_id, addonids, addonoptids, add_to_cart_url, variant_id, show_plus_minus, that) {
        $.ajax({
            type: "post",
            dataType: "json",
            url: add_to_cart_url,
            data: {
                "addonID": addonids,
                "vendor_id": vendor_id,
                "product_id": product_id,
                "addonoptID": addonoptids,
                "quantity": 1,
                "variant_id": variant_id,
            },
            success: function (response) {
                if (response.status == 'success') {
                    $(".shake-effect").effect("shake", { times: 3 }, 1200);
                    cartHeader();
                    $(that).next().show();
                    $(that).next().find('.minus').attr('data-id', response.cart_product_id);
                    $(that).next().find('.plus').attr('data-id', response.cart_product_id);
                    $(that).next().find('.input_qty').attr('id', "quantity_ondemand_" + response.cart_product_id);
                    $(that).next().find('.qty-minus-ondemand').attr('data-parent_div_id', "show_plus_minus" + response.cart_product_id);
                    $(that).next().attr('id', "show_plus_minus" + response.cart_product_id);

                    $(that).attr('id', "add_button_href" + response.cart_product_id);
                    $(that).hide();
                    $(that).next().show();
                } else {
                    alert(response.message);
                }
            },
            error: function (error) {
                var response = $.parseJSON(error.responseText);
                let error_messages = response.message;
                alert(error_messages);
            },
        });
    }




    // get time slots according to date 
    $(document).on('click', '.check-time-slots', function () {
        let cur_date = $(this).val();
        getTimeSlots(cur_date);

    });

    $(document).on('click', '.selected-time', function () {
        let selected_time = $(this).html();
        $("#show_time").html(selected_time);
        $('#message_of_time').html("Your service will start between " + selected_time);
        $("#next-button-ondemand-3").show();
    });

    // on demand add to cart 
    function getTimeSlots(cur_date) {
        $("#show_date").html(cur_date);


        $.ajax({
            type: "post",
            dataType: "json",
            url: getTimeSlotsForOndemand,
            data: {
                "cur_date": cur_date
            },
            success: function (response) {
                $(".booking-time").slick('destroy');
                $("#show-all-time-slots").show();
                $("#show-all-time-slots").html('');
                $('#show-all-time-slots').html(response);
                initializeSlider();
            },
            error: function (error) {
                var response = $.parseJSON(error.responseText);
                let error_messages = response.message;
                alert(error_messages);
            },
        });
    }

    function initializeSlider() {
        $(".booking-time").slick({
            dots: !1,
            infinite: !0,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 6,
            responsive: [
                { breakpoint: 1367, settings: { slidesToShow: 4, slidesToScroll: 4, infinite: !0 } },
                { breakpoint: 1024, settings: { slidesToShow: 4, slidesToScroll: 4, infinite: !0 } },
                { breakpoint: 767, settings: { slidesToShow: 3, arrows: true, slidesToScroll: 3, infinite: !0 } },
                { breakpoint: 480, settings: { slidesToShow: 2, arrows: true, slidesToScroll: 2 } },
            ],
        });

    }

    /// ***************************************       END show cart data for on demand services    ***************************************************************///////////////// 





    // end on demand add to cart
    $(document).delegate('.quantity-right-plus', 'click', function () {
        var quan = parseInt($('.quantity_count').val());
        var str = $('#instock').val();
        // var res = parseInt(str.substring(10, str.length - 1));
        if (quan > str) {
            alert("Quantity is not available in stock");
            $('.quantity_count').val(str);
        } else {
            var s = $(".qty-box .input-qty-number"),
                i = parseInt(s.val(), 10);
            isNaN(i) || s.val(i + 1);
        }
    });
    $(document).delegate(".quantity-left-minus", "click", function () {
        var s = $(".qty-box .input-qty-number"),
            i = parseInt(s.val(), 10);
        !isNaN(i) && i > 1 && s.val(i - 1);
    });
    $(document).delegate('.quantity_count', 'change', function () {
        var quan = $(this).val();
        var str = $('#instock').val();
        // var res = parseInt(str.substring(10, str.length - 1));
        if (quan > str) {
            alert("Quantity is not available in stock");
            $('.quantity_count').val(str);
        }
    });

    window.success_error_alert = function success_error_alert(responseClass, message, element) {
        $(element).find(".alert").html('');
        $(element).removeClass('d-none');
        if (responseClass == 'success') {
            $(element).find(".alert").html("<div class='alert-success p-1'>" + message + "</div>").show();
        } else if (responseClass == 'error') {
            $(element).find(".alert").html("<div class='alert-danger p-1'>" + message + "</div>").show();
        }
        $('html, body').animate({
            scrollTop: $(element).offset().top
        }, 500);
        setTimeout(function () {
            $(element).addClass('d-none');
            $(element).find(".alert").hide();
        }, 8000);
    }

    $(document).on('click', '.prescription_btn', function (e) {
        $("#product_id").val($(this).data("product"));
        $("#vendor_idd").val($(this).data("vendor_id"));
        $('#prescription_form').modal('show');
    });

    $(document).on('click', '.submitPrescriptionForm', function (e) {
        e.preventDefault();
        var form = document.getElementById('save_prescription_form');
        var formData = new FormData(form);
        var route_uri = "add/product/prescription";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: route_uri,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $(".loader_box").show();
            },
            success: function (response) {

                if (response.status == 'success') {
                    $(".modal .close").click();
                    location.reload();
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            complete: function () {
                $('.loader_box').hide();
            }
        });

    });

    $(document).on('click', '#tasknow', function () {
        $('#schedule_div').attr("style", "display: none !important");
    });
    $(document).on('click', '#taskschedule', function () {
        $('#schedule_div').attr("style", "display: flex !important");
    });
    // var x = document.getElementById("schedule_div").autofocus;
});