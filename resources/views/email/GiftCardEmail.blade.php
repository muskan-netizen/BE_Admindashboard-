<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Order</title>
      <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
      <style type="text/css">
         body{
            padding: 0;
            margin: 0;font-family: 'Lato', sans-serif;
            font-weight: 400;
         }
         a{
            text-decoration: none;
         }
         h1,h2,h3,h4{
            font-weight: 700;
            margin: 0;
         }
         p{
            font-size: 16px;
            line-height: 22px;
            margin: 0 0 5px;
         }
         .container {
            background: #fff;
            padding: 0 33px;
            max-width: 100%;
            margin: 0 auto;
            width: 600px;
         }
         table {
            border-collapse: separate;
            text-indent: initial;
            border-spacing: 0;
            text-align: left;
         }
         table th,table td{
            padding: 10px 30px;
            border: 0 !important;
         }
         ul {
            margin: 0;padding: 0;
         }
         ul li{
            list-style: none;
         }
         .order-total-price td {
            padding: 0 0 10px;
         }
         .payment-method th,.payment-method td{
            padding: 10px 0;
         }
         box {
    position: relative;
    max-width: 450px;
    width: 90%;
    height: 262px;
    background: #fff;
    box-shadow: 0 0 15px rgba(0,0,0,.1);
    margin: 20px auto;
}

/* common */
.ribbon {
  width: 150px;
  height: 150px;
  overflow: hidden;
  position: absolute;
  z-index: 1;
}
.ribbon::before,
.ribbon::after {
  position: absolute;
  z-index: -1;
  content: '';
  display: block;
  
}
.ribbon span {
  position: absolute;
  display: block;
  width: 225px;
  padding: 15px 0;
  box-shadow: 0 5px 10px rgba(0,0,0,.1);
  color: #fff;
  font: 700 18px/1 'Lato', sans-serif;
  text-shadow: 0 1px 1px rgba(0,0,0,.2);
  text-transform: uppercase;
  text-align: center;
}

/* top left*/
.ribbon-top-left {
  top: -10px;
  left: -10px;
}
.ribbon-top-left::before,
.ribbon-top-left::after {
  border-top-color: transparent;
  border-left-color: transparent;
}
.ribbon-top-left::before {
  top: 0;
  right: 0;
}
.ribbon-top-left::after {
  bottom: 0;
  left: 0;
}
.ribbon-top-left span {
  right: -25px;
  top: 30px;
  transform: rotate(-45deg);
}


.gift-card__msg {
  font-size: 10px;
  display: block;
  margin-top: 10px;
}

.gift-card__details {
  margin-top: auto;
  align-items: center;
  line-height: 1;
}

.gift-card__code {
  display: inline-block;
  background: white;
  color: black;
  padding: 10px 13px;
  margin-top: 20px;
  font-size: 20px;
  border: 1px solid #e3e3e3;
}

.gift-card__amount {
  font-size: 70px;
}
.gift-card__amount-remaining {
  font-size: 14px;
  margin-top: 7px;
}
.gift-card__image {
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  max-width: 150px;
  background-size: cover;
  background-image: url({{  $mailData['GiftCard']['image']['proxy_url'].'100/100'.$mailData['GiftCard']['image']['image_path'] }});
}
.green__ribbon span{
    background-color: #93C63C;
}
.green__ribbon:before,
.green__ribbon:after{
    border: 5px solid #4E7212;
}
.red__ribbon span{
    background-color: #EF1313;
}
.red__ribbon:before,
.red__ribbon:after{
    border: 5px solid #780C0C;
}
      </style>
   </head>
   <body>
      <section class="wrapper">
         <div class="container" style="background: #308fe442;">
        {!! $mailData['email_template_content'] !!}
            <div class="wrapper ">
               <div class="box">
                  <div class="ribbon ribbon-top-left green__ribbon"><span>{{ $mailData['GiftCard']['title'] }}</span></div>
                  <article class="gift-card row m-0">
                     <div class="gift-card__image col-4 p-0">
                     </div>
                     <section class="gift-card__content col-8 text-right pt-4">
                           <div class="gift-card__amount">{{$mailData['currSymbol']. $mailData['GiftCard']['amount'] }}</div>
                           
                           <div class="gift-card__code w-100 text-center bg-light">{{ $mailData['GiftCard']['name'] }}</div>
                           <div class="gift-card__msg mb-2 text-truncate">{{ $mailData['GiftCard']['short_desc'] }}</div>
                     </section>
                     </article>
               </div>
               
            </div>
        <table class="main-bg-light text-center top-0" align="center" border="0" cellpadding="0" cellspacing="0" style="width:100%; background-color:#fff; padding: 0 15px;">
            <tr>
                <td>
                    @php
                        $currYear = \Carbon\Carbon::now()->year;
                        $prevYear = $currYear - 1;
                        $currYear = substr($currYear, -2);
                    @endphp
                    <p>&copy; {{$prevYear}}-{{$currYear}} | {{ __("All rights reserved") }}</p>
                    
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
