<style>
    li{list-style:none;}
    .userDetailsImage {
        background-color: #ddd;
        width: 50px;
        border-radius: 50px;
        height: 50px;
        overflow: hidden;
    }
    .fa-star{
        color: gold;
    }
    #productPriceModel .search input[type="datetime-local"] {
    width: 80%;
    margin: 0 !important;border-radius:  7px 0 0 7px;
}
#productPriceModel button.btn.btn-danger {
    border-radius: 50px;
}
li.userDetailsName.d-block {
    color: #000;
    font-weight: 500;
    font-size: 15px;
}
li.userDetailsJobDone.d-block {
    font-size: 12px;
}
li.userDetailsJobDone.d-block span.text-right {
    font-size: 14px;
    font-weight: 600;
}
.listofdrivers .card:hover {
    box-shadow: 0 6px 14px rgb(0 0 0 / 10%);
}
.listofdrivers .card{
  border-radius: 10px;
  cursor: pointer;
  overflow: hidden;
}
#productPriceModel .search button#search_Driver_fee {
    margin-top: -1px !important;
    padding-bottom: 8px;
    margin-left: -1px;border-radius: 0 7px 7px 0;
}
.empty_driver_price {
    text-align: center;
}
.empty_driver_price h2 {
    font-size: 20px;
    color: #000;
}

.empty_driver_price p {
    font-size: 14px;
    line-height: 18px;
}
    </style>
@php
  $client_timezone = \DB::table('clients')->first('timezone');
  $timezone = auth()->user() ? auth()->user()->timezone : $client_timezone->timezone;
  $minDate = \Carbon\Carbon::now()->setTimezone($timezone)->format('Y-m-d H:m');
@endphp

<div class="modal  fade" id="productPriceModel">
    <div class="modal-dialog  modal-dialog-centered "  >
      <div class="modal-content ">
  
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Prices</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
  
        <!-- Modal body -->
        <div class="modal-body">
            <div class="search border-bottom mb-3 pb-3">
              <form class="form-inline my-2 my-lg-0 d-flex">
                <input class="form-control mr-sm-2" type="hidden" id="driver_product_variant_id">
                <input class="form-control mr-sm-2" type="datetime-local" time-zone="{{  $timezone  }}" min="{{ $minDate }}" value="{{ $minDate }}" id="onDemandBookingdate" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" id="search_Driver_fee">Search</button>
              </form>
          </div>
          <div class="listofdrivers" id ="listofdrivers">
             
          </div>
        </div>
  
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
  
      </div>
    </div>
  </div>