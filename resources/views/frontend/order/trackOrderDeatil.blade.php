@extends('layouts.store', ['title' => 'Order Detail'])
@section('css')
<style>
ul.timeline-3 {
  list-style-type: none;
  position: relative;
}
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
  </style>
@endsection
@section('content')

<section class="section-b-space light-layout">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mt-2 mb-3 track-ordr">
                <div class="order-heading">
                    <h4>Order Status</h4>
                </div>
                @if($order)
                <ul class="timeline-3">
                   
                        @if(isset($order->orderStatusVendor))
                            @foreach ($order->orderStatusVendor as $status)
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

                                <li class="d-block">
                                    <a href="#!">{{ $title }}</a>
                                    <a href="#!" class="d-block">{{ $status->updated_at }}</a> 
                                </li>
                            @endforeach
                              
                       
                         @endif
                   
                  </ul>
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
        </div>
    </div>
</section>
@endsection