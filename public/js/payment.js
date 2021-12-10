// Razor Pay script
var razorpay_options = {
    "key": razorpay_api_key, // Enter the Key ID generated from the Dashboard
    "amount": "50000", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "INR",
    "name": client_company_name,
    "description": "Test Transaction",
    "image": client_logo_url,
    "order_id": "order_9A33XWu170gUtm", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "handler": function (response){
        alert(response.razorpay_payment_id);
        alert(response.razorpay_order_id);
        alert(response.razorpay_signature);
    },
    "prefill": {
        "name": logged_in_user_name,
        "email": logged_in_user_email,
        "contact": "+"+logged_in_user_dial_code+""+logged_in_user_phone
    },
    "notes": {
        "address": "Razorpay Corporate Office"
    },
    "theme": {
        "color": client_preference_web_color
    }
};

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    let queryString = window.location.search;
    let path = window.location.pathname;
    let urlParams = new URLSearchParams(queryString);
    if ((urlParams.has('gateway')) && (urlParams.get('gateway') == 'paystack')) {
        $('.spinner-overlay').show();
        let tipAmount = 0;
        if (urlParams.has('tip')) {
            tipAmount = urlParams.get('tip');
        }
        order_number = 0;
        if (urlParams.has('ordernumber')) {
            order_number = urlParams.get('ordernumber');

        }
        paymentSuccessViaPaystack(urlParams.get('amount'), urlParams.get('trxref'), path, tipAmount, order_number);
    }



    window.paymentViaPaystack = function paymentViaPaystack() {
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
        } else if (walletElement.length > 0) {
            total_amount = walletElement.val();
        }
        ajaxData.amount = total_amount;
        ajaxData.returnUrl = path;
        ajaxData.cancelUrl = path;

        if (typeof tip_for_past_order !== 'undefined') {
            if (tip_for_past_order != undefined && tip_for_past_order == 1) {
                let order_number = $("#order_number").val();
                ajaxData.order_number = order_number;

            }

        }

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_paystack_url,
            data: ajaxData,
            success: function(response) {
                if (response.status == "Success") {
                    window.location.href = response.data;
                } else {
                    if (cartElement.length > 0) {
                        success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    } else if (walletElement.length > 0) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                if (cartElement.length > 0) {
                    success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                } else if (walletElement.length > 0) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }

    function paymentSuccessViaPaystack(amount, reference, path, tip = 0, order_number = 0) {
        let address_id = 0;
        if (path.indexOf("cart") !== -1) {
            // $('#order_placed_btn').trigger('click');
            // $('#v-pills-paystack-tab').trigger('click');
            $("#order_placed_btn, .proceed_to_pay").attr("disabled", true);
            address_id = $("input:radio[name='address_id']:checked").val();
        } else if (path.indexOf("wallet") !== -1) {
            // $('#topup_wallet_btn').trigger('click');
            // $('#wallet_topup_form #radio-paystack').prop("checked", true);
            $("#topup_wallet_btn, .topup_wallet_confirm").attr("disabled", true);
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_success_paystack_url,
            data: { 'amount': amount, 'reference': reference },
            success: function(response) {
                if (response.status == "Success") {
                    if (path.indexOf("cart") !== -1) {
                        placeOrder(address_id, 5, response.data, tip);
                    } else if (path.indexOf("wallet") !== -1) {
                        creditWallet(amount, 5, response.data);
                    } else if (path.indexOf("orders") !== -1) {
                        creditTipAfterOrder(amount, 3, response.data, order_number);
                    }
                } else {
                    $('.spinner-overlay').hide();
                    if (path.indexOf("cart") !== -1) {
                        success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    } else if (path.indexOf("wallet") !== -1) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $("#topup_wallet_btn, .topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function(error) {
                $('.spinner-overlay').hide();
                var response = $.parseJSON(error.responseText);
                if (path.indexOf("cart") !== -1) {
                    success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                } else if (path.indexOf("wallet") !== -1) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $("#topup_wallet_btn, .topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }

    window.paymentViaPayfast = function paymentViaPayfast() {
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
            ajaxData.address_id = $("input:radio[name='address_id']:checked").val();
            ajaxData.payment_form = 'cart';
        } else if (walletElement.length > 0) {
            total_amount = walletElement.val();
            ajaxData.payment_form = 'wallet';
        }
        ajaxData.amount = total_amount;
        ajaxData.returnUrl = path;
        ajaxData.cancelUrl = path;
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_payfast_url,
            data: ajaxData,
            success: function(response) {
                if (response.status == "Success") {
                    var res = response.data;
                    if (res.formData != '') {
                        $("#payfast_offsite_form").remove();
                        var form = '';
                        $.each(res.formData, function(key, value) {
                            form += '<input type="hidden" name="' + key + '" value="' + value + '">';
                        });
                        form = $('<form id="payfast_offsite_form" action="' + res.redirectUrl + '" method="post">' + form + '</form>');
                        if (cartElement.length > 0) {
                            $('#proceed_to_pay_modal .modal-body').append(form);
                        } else if (walletElement.length > 0) {
                            $('#topup_wallet .modal-content').append(form);
                        }
                        form.submit();
                    }
                } else {
                    if (cartElement.length > 0) {
                        success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    } else if (walletElement.length > 0) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                if (cartElement.length > 0) {
                    success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                } else if (walletElement.length > 0) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }

    window.paymentViaMobbex = function paymentViaMobbex(address_id, order) {
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
            ajaxData.address_id = address_id;
            ajaxData.payment_form = 'cart';
            ajaxData.order_number = order.order_number;
        } else if (walletElement.length > 0) {
            total_amount = walletElement.val();
            ajaxData.payment_form = 'wallet';
        }
        ajaxData.amount = total_amount;
        ajaxData.returnUrl = path;
        ajaxData.cancelUrl = path;
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_mobbex_url,
            data: ajaxData,
            success: function(response) {
                if (response.status == "Success") {
                    window.location.href = response.data;
                } else {
                    if (cartElement.length > 0) {
                        success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    } else if (walletElement.length > 0) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                if (cartElement.length > 0) {
                    success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                } else if (walletElement.length > 0) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }

    window.paymentViaYoco = function paymentViaYoco(token, address_id, order) {
        let total_amount = 0;
        let tip = 0;
        let tipElement = $("#cart_tip_amount");
        let cartElement = $("input[name='cart_total_payable_amount']");
        let cart_id = $("#cart_total_payable_amount").data("cart_id");
        let walletElement = $("input[name='wallet_amount']");
        let subscriptionElement = $("input[name='subscription_amount']");
        let ajaxData = [];

        if (path.indexOf("cart") !== -1) {
            total_amount = cartElement.val();
            tip = tipElement.val();
            ajaxData.push(
                {name: 'tip', value: tip }, 
                {name: 'address_id', value: address_id}, 
                {name: 'payment_form', value: 'cart'},
                {name: 'cart_id', value: cart_id},
                {name: 'order_number', value: order_number}
            );
            // ajaxData.tip = tip;
            // ajaxData.address_id = address_id;
            // ajaxData.payment_form = 'cart';
            // ajaxData.cart_id = cart_id;
            // ajaxData.order_number = order.order_number;
        } else if (path.indexOf("wallet") !== -1) {
            total_amount = walletElement.val();
            // ajaxData.payment_form = 'wallet';
            ajaxData.push({name: 'payment_form', value: 'wallet'});
        } else if (path.indexOf("subscription") !== -1) {
            total_amount = subscriptionElement.val();
            ajaxData = $("#subscription_payment_form").serializeArray();
            ajaxData.push({name: 'payment_form', value: 'subscription'});
        } else if ((tip_for_past_order != undefined) && (tip_for_past_order == 1)) {
            total_amount = walletElement.val();
            ajaxData.push( 
                {name: 'payment_form', value: 'tip'},
                {name: 'order_number', value: $("#order_number").val()}
            );
            // ajaxData.payment_form = 'tip';
            // ajaxData.order_number = $("#order_number").val();
        }
        ajaxData.push(
            {name: 'token', value: token }, 
            {name: 'amount', value: total_amount}, 
            {name: 'returnUrl', value: path}
        );
        // ajaxData.token = token;
        // ajaxData.amount = total_amount;
        // ajaxData.returnUrl = path;
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_yoco_url,
            data: ajaxData,
            success: function(response) {
                if (response.status == "Success") {
                    if (path.indexOf("cart") !== -1) {
                        window.location.href = order_success_return_url;
                    } else if (path.indexOf("wallet") !== -1) {
                        creditWallet(total_amount, 8, response.data.id);
                    } else if (path.indexOf("subscription") !== -1) {
                        userSubscriptionPurchase(total_amount, 8, response.data.id);
                    } else if ((tip_for_past_order != undefined) && (tip_for_past_order == 1)) {
                        let order_number = $("#order_number").val();
                        if (order_number.length > 0) {
                            order_number = order_number;
                        }
                        creditTipAfterOrder(total_amount, 8, response.data.id, order_number);
                    }
                } else {
                    if (path.indexOf("cart") !== -1) {
                        success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    } else if (path.indexOf("wallet") !== -1) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    } else if (path.indexOf("subscription") !== -1) {
                        success_error_alert('error', response.message, "#subscription_payment_form .payment_response");
                        $(".subscription_confirm_btn").removeAttr("disabled");
                    }
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                if (cartElement.length > 0) {
                    success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                } else if (walletElement.length > 0) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }

    // window.paymentViaYoco_wallet = function paymentViaYoco_wallet(token, address_id, payment_option_id) {
    //     let total_amount = 0;
    //     let ajaxData = [];
    //     total_amount = $("input[name='wallet_amount']").val();

    //     ajaxData.push({ name: 'token', value: token }, { name: 'amount', value: total_amount }, { name: 'payment_option_id', value: payment_option_id });
    //     $.ajax({
    //         type: "POST",
    //         dataType: 'json',
    //         url: payment_yoco_url,
    //         data: ajaxData,
    //         success: function(response) {
    //             creditWallet(total_amount, payment_option_id, response.data.id);
    //         },
    //     });
    // }

    window.paymentViaPaylink = function paymentViaPaylink(address_id, order) {
        let total_amount = 0;
        let tip = 0;
        let tipElement = $("#cart_tip_amount");
        let cartElement = $("input[name='cart_total_payable_amount']");
        let cart_id = $("#cart_total_payable_amount").data("cart_id");
        let subscriptionElement = $("input[name='subscription_amount']");
        let walletElement = $("input[name='wallet_amount']");
        let ajaxData = [];
        
        if (path.indexOf("cart") !== -1) {
            total_amount = cartElement.val();
            tip = tipElement.val();
            ajaxData.push(
                {name: 'tip', value: tip }, 
                {name: 'address_id', value: address_id}, 
                {name: 'payment_form', value: 'cart'},
                {name: 'cart_id', value: cart_id},
                {name: 'order_number', value: order.order_number}
            );
            // ajaxData.tip = tip;
            // ajaxData.address_id = address_id;
            // ajaxData.payment_form = 'cart';
            // ajaxData.cart_id = cart_id;
            // ajaxData.order_number = order.order_number;
        } else if (path.indexOf("wallet") !== -1) {
            total_amount = walletElement.val();
            ajaxData.payment_form = 'wallet';
            ajaxData.push({name: 'payment_form', value: 'wallet'});
        } else if (path.indexOf("subscription") !== -1) {
            total_amount = subscriptionElement.val();
            ajaxData = $("#subscription_payment_form").serializeArray();
            ajaxData.push({name: 'payment_form', value: 'subscription'});
        } else if ((tip_for_past_order != undefined) && (tip_for_past_order == 1)) {
            total_amount = walletElement.val();
            ajaxData.push( 
                {name: 'payment_form', value: 'tip'},
                {name: 'order_number', value: $("#order_number").val()}
            );
        }
        ajaxData.push(
            {name: 'amount', value: total_amount}, 
            {name: 'returnUrl', value: path},
            {name: 'cancelUrl', value: path}
        );
        // ajaxData.amount = total_amount;
        // ajaxData.returnUrl = path;
        // ajaxData.cancelUrl = path;
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_paylink_url,
            data: ajaxData,
            success: function(response) {
                if (response.status == "Success") {
                    window.location.href = response.data;
                } else {
                    if (path.indexOf("cart") !== -1) {
                        success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    } else if (path.indexOf("wallet") !== -1) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                if (path.indexOf("cart") !== -1) {
                    success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                } else if (path.indexOf("wallet") !== -1) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }

    // function paymentViaPaylink_wallet(address_id, payment_option_id) {
    //     let total_amount = 0;
    //     let ajaxData = [];
    //     total_amount = $("input[name='wallet_amount']").val();
    //     ajaxData.push({ name: 'amount', value: total_amount }, { name: 'payment_option_id', value: payment_option_id });
    //     $.ajax({
    //         type: "POST",
    //         dataType: 'json',
    //         url: payment_paylink_url,
    //         data: ajaxData,
    //         success: function(response) {
    //             if (response.status == "Success") {
    //                 //  creditWallet(total_amount, payment_option_id, data.result.id);
    //                 window.location.href = response.data;
    //             }
    //         }
    //     });
    // }

    window.paymentViaRazorpay = function paymentViaRazorpay(address_id, order, payment_from) {
        let total_amount = 0;
        let tip = 0;
        let tipElement = $("#cart_tip_amount");
        let cartElement = $("input[name='cart_total_payable_amount']");
        let cart_id = $("#cart_total_payable_amount").data("cart_id");

        let walletElement = $("input[name='wallet_amount']");
        let ajaxData = {};
        if (cartElement.length > 0) {
            total_amount = cartElement.val();
            tip = tipElement.val();
            ajaxData.tip = tip;
            ajaxData.address_id = address_id;
            ajaxData.payment_form = 'cart';
            ajaxData.cart_id = cart_id;
            ajaxData.order_number = order.order_number;
        } else if (walletElement.length > 0) {
            total_amount = walletElement.val();
            ajaxData.payment_form = 'wallet';
        } else if (path.indexOf("subscription") !== -1) {
            total_amount = subscriptionElement.val();
            // ajaxData = $("#subscription_payment_form").serializeArray();
            // ajaxData.push({name: 'payment_form', value: 'subscription'});
        } else if ((tip_for_past_order != undefined) && (tip_for_past_order == 1)) {
            total_amount = walletElement.val();
            // ajaxData.push( 
            //     {name: 'payment_form', value: 'tip'},
            //     {name: 'order_number', value: $("#order_number").val()}
            // );
        }
        ajaxData.amount = total_amount;
        ajaxData.payment_from = 'cart';
        ajaxData.returnUrl = path;
        ajaxData.cancelUrl = path;
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_razorpay_url,
            data: ajaxData,
            success: function(response) {
                if (response.status == "Success") {
                    // razorpay_options.key = response.data.api_key;
                    razorpay_options.amount = response.data.amount;
                    razorpay_options.order_id = response.data.order_id;
                    razorpay_options.currency = response.data.currency;
                    $('#proceed_to_pay_modal').modal('hide');
                    razourPayView(response.data);
                    // window.location.href = response.data;
                } else {
                    if (cartElement.length > 0) {
                        success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                        $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                    } else if (walletElement.length > 0) {
                        success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                        $(".topup_wallet_confirm").removeAttr("disabled");
                    }
                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                if (cartElement.length > 0) {
                    success_error_alert('error', response.message, "#cart_payment_form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                } else if (walletElement.length > 0) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }

    window.razourPayCompletePayment = function razourPayCompletePayment(data, response) {
        data.razorpay_payment_id = response.razorpay_payment_id;
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: razorpay_complete_payment_url,
            data: data,
            success: function(response) {
                console.log(response);
                if (response.status == "Success") {
                    window.location.href = response.data;
                } else {
                   
                }
            },
            error: function(error) {

            }
        });
    }
     // RazourPay payment gateway
    window.razourPayView = function razourPayView(data) {
        razorpay_options.handler = function (response){
            startLoader('body','We are processing your transaction...');
            razourPayCompletePayment(data,response);
            // alert(response.razorpay_payment_id);
            // alert(response.razorpay_order_id);
            // alert(response.razorpay_signature);
        }
        var rzp1 = new Razorpay(razorpay_options);
        rzp1.on('payment.failed', function (response){
                // alert(response.error.code);
                // alert(response.error.description);
                // alert(response.error.source);
                // alert(response.error.step);
                // alert(response.error.reason);
                // alert(response.error.metadata.order_id);
                // alert(response.error.metadata.payment_id);
        });
        rzp1.open();
    }
     // RazourPay payment gateway

/////////////////////////////////////////////GCash payment Gateway Integration/////////////
    window.paymentViaGCash = function paymentViaGCash(address_id,payment_option_id){
        let walletElement = $("input[name='wallet_amount']");
        let subscriptionElement = $("input[name='subscription_amount']");
        let total_amount = 0;
        let ajaxData = [];
        if (path.indexOf("wallet") !== -1) {
            total_amount = walletElement.val();
            ajaxData.push({name: 'payment_from', value: 'wallet'});
        } else if (path.indexOf("subscription") !== -1) {
            total_amount = subscriptionElement.val();
            ajaxData = $("#subscription_payment_form").serializeArray();
            ajaxData.push({name: 'payment_from', value: 'subscription'});
        } else if ((tip_for_past_order != undefined) && (tip_for_past_order == 1)) {
            total_amount = walletElement.val();
            ajaxData.push( 
                {name: 'payment_from', value: 'tip'},
                {name: 'order_number', value: $("#order_number").val()}
            );
        }
        ajaxData.push(
            {name: 'amount', value: total_amount}, 
            {name: 'returnSuccessUrl', value: path},
            {name: 'returnFailureUrl', value: path}
        );
        ajaxData.push({ name: 'payment_option_id', value: payment_option_id });
        console.log(ajaxData);
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: gcash_before_payment,
            data: ajaxData, 
            success: function(response) {
                if (response.status == "Success") {
                    console.log(response);
                    window.location.href = response.data.data.checkouturl;
                }
            }
        });

    }

//////////////////////////////////////Simplify Payment Gateway////////////////////////////////////

    window.paymentViaSimplify = function paymentViaSimplify(address_id,order){
        let total_amount = 0;
        let tip = 0;
        let tipElement = $("#cart_tip_amount");
        let cartElement = $("input[name='cart_total_payable_amount']");
        let cart_id = $("#cart_total_payable_amount").data("cart_id");
        let subscriptionElement = $("input[name='subscription_amount']");
        let subscription_id = $("input[name='subscription_id']");
        let walletElement = $("input[name='wallet_amount']");
        let ajaxData = [];
        let data = [];
        
        if (path.indexOf("cart") !== -1) {
            total_amount = cartElement.val();
            tip = tipElement.val();
            data.tip = tip;
            data.address_id = address_id;
            data.payment_from = 'cart';
            data.cart_id = cart_id;
            data.order_number = order.order_number;

        } else if (path.indexOf("wallet") !== -1) {
            total_amount = walletElement.val();
            data.payment_from ='wallet';
        } else if (path.indexOf("subscription") !== -1) {
            total_amount = subscriptionElement.val();
            // ajaxData = $("#subscription_payment_form").serializeArray();
            data.subscription_id = subscription_id.val();
            data.payment_from ='subscription';
        } else if ((tip_for_past_order != undefined) && (tip_for_past_order == 1)) {
            total_amount = walletElement.val();
            data.payment_from ='tip';
            data.order_number = $("#order_number").val();
        }
        data.amount = total_amount;
        data.payment_option_id =12;
        data._token = $('input[name=_token]').val();
        $.redirect(simplify_before_payment, data);
    }
/////////////////////////////////////////////Square Pamyent Gateway /////////////////////////////////////////
    window.paymentViaSquare = function paymentViaSquare(address_id,order){
        let total_amount = 0;
        let tip = 0;
        let tipElement = $("#cart_tip_amount");
        let cartElement = $("input[name='cart_total_payable_amount']");
        let cart_id = $("#cart_total_payable_amount").data("cart_id");
        let subscriptionElement = $("input[name='subscription_amount']");
        let subscription_id = $("input[name='subscription_id']");
        let walletElement = $("input[name='wallet_amount']");
        let ajaxData = [];
        let data = [];
        
        if (path.indexOf("cart") !== -1) {
            total_amount = cartElement.val();
            tip = tipElement.val();
            data.tip = tip;
            data.address_id = address_id;
            data.payment_from = 'cart';
            data.cart_id = cart_id;
            data.order_number = order.order_number;

        } else if (path.indexOf("wallet") !== -1) {
            total_amount = walletElement.val();
            data.payment_from ='wallet';
        } else if (path.indexOf("subscription") !== -1) {
            total_amount = subscriptionElement.val();
            // ajaxData = $("#subscription_payment_form").serializeArray();
            data.subscription_id = subscription_id.val();
            data.payment_from ='subscription';
        } else if ((tip_for_past_order != undefined) && (tip_for_past_order == 1)) {
            total_amount = walletElement.val();
            data.payment_from ='tip';
            data.order_number = $("#order_number").val();
        }
        data.amount = total_amount;
        data.payment_option_id =13;
        data._token = $('input[name=_token]').val(); 
        $.redirect(square_before_payment, data);
    }

//////////////////////////Ozow Payment Gateway /////////////////////////////////////////
    window.paymentViaOzow = function paymentViaOzow(address_id,order){
        let total_amount = 0;
        let tip = 0;
        let tipElement = $("#cart_tip_amount");
        let cartElement = $("input[name='cart_total_payable_amount']");
        let cart_id = $("#cart_total_payable_amount").data("cart_id");
        let subscriptionElement = $("input[name='subscription_amount']");
        let subscription_id = $("input[name='subscription_id']");
        let walletElement = $("input[name='wallet_amount']");
        let ajaxData = [];
        let data = [];
        
        if (path.indexOf("cart") !== -1) {
            total_amount = cartElement.val();
            tip = tipElement.val();
            data.tip = tip;
            data.address_id = address_id;
            data.payment_from = 'cart';
            data.cart_id = cart_id;
            data.order_number = order.order_number;

        } else if (path.indexOf("wallet") !== -1) {
            total_amount = walletElement.val();
            data.payment_from ='wallet';
        } else if (path.indexOf("subscription") !== -1) {
            total_amount = subscriptionElement.val();
            // ajaxData = $("#subscription_payment_form").serializeArray();
            data.subscription_id = subscription_id.val();
            data.payment_from ='subscription';
        } else if ((tip_for_past_order != undefined) && (tip_for_past_order == 1)) {
            total_amount = walletElement.val();
            data.payment_from ='tip';
            data.order_number = $("#order_number").val();
        }
        data.amount = total_amount;
        data.payment_option_id =14;
        data._token = $('input[name=_token]').val(); 
        $.redirect(ozow_before_payment, data);
    }


});




