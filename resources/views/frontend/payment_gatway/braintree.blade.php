<html>

<head>
  <title>{{__('My Payment Flow')}}</title>
  <!-- link to the Square web payment SDK library -->
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/font-awesome.min.css')}}">
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/custom.css')}}">
    <style>
        .spinner-overlay .page-spinner .circle-border {
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.5) 33%, rgba(255, 255, 255, 1) 100%);
        }
        @keyframes spin {
            from {
                transform:rotate(0deg);
            }
            to {
                transform:rotate(360deg);
            }
        }
        .payment-top-haeder{
            background: {{getClientPreferenceDetail()->web_color}}; 
        }
        button {
            background-color: {{getClientPreferenceDetail()->web_color}} ;
        }
    </style>
    <style type="text/css">
    .button {cursor: pointer;font-weight: 500;left: 3px;line-height: inherit;position: relative;text-decoration: none;text-align: center;border-style: solid;border-width: 1px;border-radius: 3px;-webkit-appearance: none;-moz-appearance: none;display: inline-block;}
    .button--small {padding: 10px 20px;font-size: 0.875rem;}
    .button--green {outline: none;background-color: #64d18a;border-color: #64d18a;color: white;transition: all 200ms ease;}
    .button--green:hover {background-color: #8bdda8;color: white;}
    </style>
</head>

<body>
    <div class="payment-top-haeder py-2 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <img src="{{ getClientDetail()->logo_image_url }}" alt="" height="50"> 
                </div>
            </div>
        </div>
    </div>
  <div id="dropin-container"></div>
  <button id="submit-button" class="button button--small button--green">Purchase</button>

  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://js.braintreegateway.com/web/dropin/1.33.1/js/dropin.js"></script>
  <script type="text/javascript">
    var button = document.querySelector('#submit-button');

    braintree.dropin.create({
      authorization: 'sandbox_g42y39zw_348pk9cgf3bgyw2b',
      selector: '#dropin-container'
    }, function (err, instance) {
      button.addEventListener('click', function () {
        instance.requestPaymentMethod(function (err, payload) {
          // Submit payload.nonce to your server
        });
      })
    });
  </script>
</body>

</html>
