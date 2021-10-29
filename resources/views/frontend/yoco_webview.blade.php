<head>
    <script src="https://js.yoco.com/sdk/v1/yoco-sdk-web.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container bg-light" style="margin-left:600px;margin-top:300px;">
        <div class="col-md-6 text-center">
            <div class="d-flex justify-content-center">
                <h2>Yoco Payment Gateway</h2>
                <form id="payment-form" method="POST" style="border:1px solid black;padding:10px;">
                    <div class="one-liner">
                        <div id="card-frame">
                            <!-- Yoco Inline form will be added here -->
                        </div>


                    </div>


                    <br><br>

                    <button class="btn btn-primary" type="submit" id="pay-button">Pay</button>
            </div>
        </div>

    </div>
    <p class="success-payment-message" />
    </form>
</body>

<script>
    // Run our code when your form is submitted
<<<<<<< HEAD
   //var yoco_public_key = "<?php //echo $public_key_yoco; ?>";

    // console.log(yoco_public_key);
    var sdk = new window.YocoSDK({
        publicKey: 'pk_test_657b29ffyL0dlr389b04'
=======
   var yoco_public_key = "<?php echo $public_key_yoco; ?>";

    // console.log(yoco_public_key);
    var sdk = new window.YocoSDK({
        publicKey: yoco_public_key
>>>>>>> f70fa067f9634e170feb8f4839ad3bb2eea9e264
    });
    var yoco_amount_payable = "<?php echo $amount; ?>";
    // Create a new dropin form instance
    var inline = sdk.inline({
        layout: 'basic',
        amountInCents: yoco_amount_payable * 100,
        currency: 'ZAR'
    });
    // this ID matches the id of the element we created earlier.
    inline.mount('#card-frame');
<<<<<<< HEAD
    var payment_yoco_url = "{{route('payment.yocoPurchase')}}";
=======
    var payment_yoco_url = "{{route('payment.yocoFunctionality')}}";
>>>>>>> f70fa067f9634e170feb8f4839ad3bb2eea9e264
    var form = document.getElementById('payment-form');
    var submitButton = document.getElementById('pay-button');
    form.addEventListener('submit', function(event) {
        event.preventDefault()
        // Disable the button to prevent multiple clicks while processing
        submitButton.disabled = true;
        // This is the inline object we created earlier with the sdk
        inline.createToken().then(function(result) {
            // Re-enable button now that request is complete
            // (i.e. on success, on error and when auth is cancelled)
            submitButton.disabled = false;
            if (result.error) {
                const errorMessage = result.error.message;
                errorMessage && alert("error occured: " + errorMessage);
            } else {
                const token = result;
                var order_number = "<?php echo $order_number; ?>";
                var yoco_amount_payable = "<?php echo $amount; ?>";
                paymentViaYoco(order_number, token.id, yoco_amount_payable * 100);
                //alert("card successfully tokenised: " + token.id);
            }

        }).catch(function(error) {
            // Re-enable button now that request is complete
            submitButton.disabled = false;
            alert("error occured: " + error);
        });
    });
    // Any additional form data you want to submit to your backend should be done here, or in another event listener
    window.paymentViaYoco = function paymentViaYoco(order_number, token, amount) {

        ajaxData.payment_form = 'cart';
        ajaxData.token = token;

        ajaxData.order_number = order_number;

        ajaxData.amount = amount;

        $.ajax({
            type: "POST",
            dataType: 'json',
            url: payment_yoco_url,
            data: ajaxData,
            success: function(response) {
                if (response.status == "Success") {
                    window.location.href = response.data;
                } else {
                    success_error_alert('error', response.message, ".success-payment-message");

                }
            },
            error: function(error) {
                var response = $.parseJSON(error.responseText);
                success_error_alert('error', response.message, ".success-payment-message");
            }
        });
    }
</script>