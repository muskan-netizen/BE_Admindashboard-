@extends('layouts.store', ['title' => 'Order Detail'])
@section('script')
<script>
    $(function(){
        $(window).on('load', function(e){
        setInterval(()=>{
            $("#tracking-frm").contents().find("body").css("display", "none");
            console.log('jhfjk');
        },5000);
      });
});
    </script>
    @endsection
@section('css')
<style>
ul.timeline-3 {
  list-style-type: none;
  position: relative;
}
iframe section.location_wrapper.py-xl-5.position-relative.d-lg-flex.align-items-lg-center {
    padding: 0 !important;
    min-height: 100% !important;
}
iframe body{margin: 0 !important;}
.dispatcher-section iframe{border: none !important; overflow: hidden !important;}
ul.timeline-3:before {
  content: " ";
  background: #d4d9df;
  display: inline-block;
  position: absolute;
  left:0px;
  width: 2px;
  height: 100%;
  z-index: 1;
  margin-top: 6px;
}
ul.timeline-3 > li {
  margin: 20px 0;
  padding-left: 20px;
}
ul.timeline-3 > li:before {
  content: " ";
  background: white;
  display: inline-block;
  position: absolute;
  border-radius: 50%;
  border: 1px solid rgb(133, 126, 126);
    left: -4px;
    width: 10px;
    height: 10px;
    z-index: 1;
    margin-top: 6px;
}

.track-ordr .order-heading h4 {
    font-size: 22px;
    font-weight: 600;
}
.track-ordr .timeline-3 li a:first-child {
    color: #685e5e;
    font-size: 16px;
}
.track-ordr .timeline-3 li a {
    font-size: 12px;
}
.no_order_found_track .card-body p {
    font-size: 20px;
}

/*  */
ul.timeline-3:before {
    content: " ";
    background: #d4d9df;
    display: inline-block;
    position: absolute;
    left: 0px;
    width: 100%;
    height: 2px;
    z-index: 1;
    margin-top: 6px;
    top: 0;
}
ul.timeline-3 > li {
    margin: 20px 0;
    padding-left: 0;
    padding-right: 10px;
    position: relative;
}
ul.timeline-3 > li:before {
    content: " ";
    background: white;
    display: inline-block;
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgb(133, 126, 126);
    left: 0;
    width: 10px;
    height: 10px;
    z-index: 1;
    margin-top: 0;
    top: -18px;
}
@media(max-width: 767px){
ul.timeline-3 li a{display: none !important;}
ul.timeline-3 li.last-active a{display: block !important;}
ul.timeline-3 > li:before {
    top: -38px;
}
ul.timeline-3 li.last-active::before{top: -18px;}
}
  </style>
@endsection
@section('content')

<section class="section-b-space light-layout">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-2 mb-3 track-ordr">
                <div class="order-heading text-center">
                    <h4>Order Status</h4>
                </div>
                @if($order)
                    @if(isset($order->orderStatusVendor))
                        <ul class="timeline-3 d-flex align-items-center justify-content-around">
                            @php $count = count($order->orderStatusVendor); $num = 0; @endphp
                        
                            @foreach ($order->orderStatusVendor as $key =>$status)
                            @if($status->order_status_option_id  == 1)
                                    @php 
                                        $title = "Order Placed";
                                    @endphp
                            @endif
                            @if($status->order_status_option_id  == 2)
                                    @php 
                                        $title = "Order Accepted";
                                    @endphp
                            @endif
                            @if($status->order_status_option_id  == 4)
                                    @php 
                                        $title = "Order Processing";
                                    @endphp
                            @endif
                            @if($status->order_status_option_id  == 5)
                                    @php 
                                        $title = "Order Out For Delivery";
                                    @endphp
                            @endif
                            @if($status->order_status_option_id  == 6)
                                    @php 
                                        $title = "Delivered";
                                    @endphp
                                @endif

                                <li <?php if($num == $count-1){ ?> class="last-active" <?php } ?>>
                                    <a href="#!">{{ $title }}</a>
                                    <a href="#!" class="d-block">{{ $status->updated_at }}</a> 
                                </li>
                                @php  $num++;  @endphp
                            @endforeach
                        </ul>
                    @endif
                  @else
                    <div class="no_order_found_track">
                        <div class="card">
                            <div class="card-body">
                                <p>{{__('Result not found')}}</p>
                            </div>
                        </div>
                    </div>
                  @endif
                <!-- <div class="success-text">
                	<i class="fa fa-check-circle" aria-hidden="true"></i>
                    <h2>{{__('Thank You')}}</h2>
                    <p>{{__('Your order has been placed successfully')}}</p>
                    <p><a href="{{ route('user.orders') }}">{{__('View Order')}}</a></p>
                </div> -->
            </div>
            <div class="col-md-12">
                @if(isset($order->ordervendor))
                <div class="dispatcher-section">
                    @if(!empty($order->ordervendor->dispatch_traking_url))
                        <iframe id="tracking-frm" src="{{ $order->ordervendor->dispatch_traking_url }}" width="100%" height="800"></iframe>
                    @endif
                </div>
            @endif
            </div>
        </div>
    </div>
</section>
@endsection

