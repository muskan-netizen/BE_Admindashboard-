$(document).ready(function () {
    
    let queryString = window.location.search;
    let path = window.location.pathname;
    let urlParams = new URLSearchParams(queryString);
    if((urlParams.has('gateway')) && (urlParams.get('gateway') == 'paystack')) {
        let tipAmount = 0;
        if (urlParams.has('tip')) {
            tipAmount = urlParams.get('tip');
        }
        paymentSuccessViaPaystack(urlParams.get('amount'), urlParams.get('trxref'), path, tipAmount);
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
            url: payment_paystack_url,
            data: ajaxData,
            success: function (response) {
                if (response.status == "Success") {
                    window.location.href = response.data;
                } else {
                    if (cartElement.length > 0) {
                        success_error_alert('error', response.message, "#paystack-payment-form .payment_response");
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
                    success_error_alert('error', response.message, "#paystack-payment-form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                }
                else if (walletElement.length > 0) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }
    function paymentSuccessViaPaystack(amount, reference, path, tip = 0) {
        let address_id = 0;
        if (path.indexOf("cart") !== -1) {
            $('#order_placed_btn').trigger('click');
            $('#v-pills-paystack-tab').trigger('click');
            $("#order_placed_btn, .proceed_to_pay").attr("disabled", true);
            address_id = $("input:radio[name='address_id']:checked").val();
        }
        else if (path.indexOf("wallet") !== -1) {
            $('#topup_wallet_btn').trigger('click');
            $('#wallet_topup_form #radio-paystack').prop("checked", true);
            $(".topup_wallet_confirm").attr("disabled", true);
        }
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_success_paystack_url,
            data: { 'amount': amount, 'reference': reference },
            success: function (response) {
                if (response.status == "Success") {
                    if (path.indexOf("cart") !== -1) {
                        placeOrder(address_id, 5, response.data, tip);
                    }
                    else if (path.indexOf("wallet") !== -1) {
                        creditWallet(amount, 5, response.data);
                    }
                } else {
                    if (path.indexOf("cart") !== -1) {
                        success_error_alert('error', response.message, "#paystack-payment-form .payment_response");
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
                    success_error_alert('error', response.message, "#paystack-payment-form .payment_response");
                    $("#order_placed_btn, .proceed_to_pay").removeAttr("disabled");
                }
                else if (path.indexOf("wallet") !== -1) {
                    success_error_alert('error', response.message, "#wallet_topup_form .payment_response");
                    $(".topup_wallet_confirm").removeAttr("disabled");
                }
            }
        });
    }
});