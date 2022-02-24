<html>

<head>
  <title>{{__('My Payment Flow')}}</title>
</head>

<body>
    <form method="post" id="kongapay" action="https://www.kongapay.com/paymentgateway">
        {!! $inputs !!}
        <input type="submit" value="submit">
      </form>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</body>
</html>
