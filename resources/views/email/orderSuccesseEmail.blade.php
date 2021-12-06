<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" href="../assets/images/favicon/1.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/images/favicon/1.png" type="image/x-icon">
    <title>Order </title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style type="text/css">
        body {
            text-align: center;
            margin: 0 auto;
            width: 650px;
            font-family: 'Inter', sans-serif;
            background-color: #e2e2e2;
            display: block;
        }

        ul {
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 0 33px;
            max-width: 100%;
            margin: 0 auto;
            width: 600px;
         }

         .container table{
             width: 100% !important;
         }

        li {
            display: inline-block;
            text-decoration: unset;
        }

        a {
            text-decoration: none;
        }

        p {
            margin: 15px 0;
        }

        h5 {
            color: #444;
            text-align: left;
            font-weight: 400;
        }

        .text-center {
            text-align: center
        }

        .main-bg-light {
            background-color: #fafafa;
        }

        .title {
            color: #444444;
            font-size: 22px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
            padding-bottom: 0;
            text-transform: uppercase;
            display: inline-block;
            line-height: 1;
        }

        table {
            margin-top: 10px;
        }
        table th {
            font-size: 14px;
        }

        table.top-0 {
            margin-top: 0;
        }

        table.order-detail {
            border: 1px solid #ddd;
            border-collapse: collapse;
        }

        table.order-detail tr:nth-child(even) {
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        table.order-detail tr:nth-child(odd) {
            border-bottom: 1px solid #ddd;
        }

        .pad-left-right-space {
            border: unset !important;
        }

        .pad-left-right-space td{
            padding: 5px 10px;
        }

        .pad-left-right-space td p {
            margin: 0;
        }

        .pad-left-right-space td b {
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
        }

        .order-detail th {
            font-size: 14px;
            padding: 10px;
            background: #fafafa;
        }

        .footer-social-icon tr td img {
            margin-left: 5px;
            margin-right: 5px;
        }
        .flex-set{
            flex-direction: column;
            justify-content: space-between;
        }
        .flex-set-scd{
            flex-direction: column;
            justify-content: end;
            align-items: end;
        }
    </style>
</head>

<body style="margin: 20px auto;max-width:100%;width:700px;">
    <div class="container" style="background: #ECBA78;">
        {!! $mailData['email_template_content'] !!}
        <table class="main-bg-light text-center top-0" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; background-color:#fff; padding: 0 15px;">
            <tr>
                <td>
                    @php
                        $currYear = \Carbon\Carbon::now()->year;
                        $prevYear = $currYear - 1;
                        $currYear = substr($currYear, -2);
                    @endphp
                    <p>&copy; {{$prevYear}}-{{$currYear}} | All rights reserved</p>
                    
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
