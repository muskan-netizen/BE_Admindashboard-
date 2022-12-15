$(document).on('click', '#open_gift_card', function(e) {
    e.preventDefault();
   
    let cart_total_payable_amount =  $("input[name='cart_total_payable_amount']");
    let cart_id =  $("#cart_id").val();
    $(".invalid-feedback.manual_giftCard").html("");
    $.ajax({
        type: "GET",
        //dataType: 'json',
        url: 'user/giftCard/list',
        data:{cart_id:cart_id },
        success: function (response) {

            if (response.success == true) {
                $('#giftCard-modal').modal('show');
                $("#promo_code_list_main_div").html('');
                $(document).find('.manual_promocode_input').val("");
                $('#giftCard_code_list_main_div').html(response.html);
                 $('.validate_giftCard_code_btn').attr('data-cart_id', cart_id);
                $('.validate_giftCard_code_btn').attr('data-amount', cart_total_payable_amount);
            }
        }
    });
   
});

$(document).on("click", ".apply_gifCard_code_btn", function () {
    let cart_id     =  $("#cart_id").val();
    let giftCard_id =  $(this).attr('data-giftcard_id');

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: `verify/giftCard`,
        data: { cart_id: cart_id, giftCard_id: giftCard_id},
        success: function (response) {
            if (response.status == "Success") {
                $('#giftCard-modal').modal('hide');
                cartHeader();
            }
        },
        error: function (reject) {
            if (reject.status === 422) {
                var message = $.parseJSON(reject.responseText);
                //alert(message.message);
                Swal.fire({
                    // title: "Warning!",
                    text: message.message,
                    icon: "error",
                    button: "OK",
                });
            }
        }
    });
});
$(document).on('click', '.validate_promo_code_btn', function () {
    let amount = $(this).attr('data-amount');
    let cart_id = $(this).attr('data-cart_id');
    let vendor_id = $(this).attr('data-vendor_id');
    let promocode = $(document).find('.manual_promocode_input').val();
    if (promocode && promocode != "") {
        // let coupon_id = $(this).data('coupon_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: validate_promocode_coupon_url,
            data: { cart_id: cart_id, vendor_id: vendor_id, amount: amount, promocode: promocode },
            success: function (response) {
                if (response.status == "Success") {
                    $('.validate_promo_div').find('.apply_promo_code_btn').attr('data-amount', amount);
                    $('.validate_promo_div').find('.apply_promo_code_btn').attr('data-cart_id', cart_id);
                    $('.validate_promo_div').find('.apply_promo_code_btn').attr('data-vendor_id', vendor_id);
                    $('.validate_promo_div').find('.apply_promo_code_btn').attr('data-coupon_id', response.data.id);
                    $('.validate_promo_div').find('.apply_promo_code_btn').trigger('click');
                    $('#refferal-modal').modal('hide');
                    cartHeader();
                }
            },
            error: function (reject) {
                if (reject.status === 422) {
                    var message = $.parseJSON(reject.responseText);
                    $(".invalid-feedback.manual_promocode").html("<strong>" + message.message + "</strong>");
                }
            }
        });
    } else {
        $(".invalid-feedback.manual_promocode").html("<strong>Please enter promocode</strong>");
    }
});

$(document).on("click", ".remove_giftCard", function () {
    let cart_id     =  $("#cart_id").val();
    let giftCard_id =  $(this).attr('data-giftcard_id');
    console.log(cart_id);
    console.log(giftCard_id);
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: `remove/giftCard`,
        data: { giftCard_id: giftCard_id, cart_id: cart_id },
        success: function (response) {
            if (response.status == "Success") {
                cartHeader();
            }
        }
    });
});