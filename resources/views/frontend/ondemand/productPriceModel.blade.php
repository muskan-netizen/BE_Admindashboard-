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
    </style>

<div class="modal fade" id="productPriceModel">
    <div class="modal-dialog">
      <div class="modal-content">
  
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal Heading</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
  
        <!-- Modal body -->
        <div class="modal-body">
            <div class="search border-bottom mb-3 pb-3">
              <form class="form-inline my-2 my-lg-0 d-flex">
                <input class="form-control mr-sm-2" type="hidden" id="driver_product_variant_id">
                <input class="form-control mr-sm-2" type="date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" id="onDemandBookingdate" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" id="search_Driver_fee">Search</button>
              </form>
          </div>
          <div class="listofdrivers" id ="listofdrivers">
              {{-- <div class="card">
                <div class="card-body border-none bg-light">
                  <div class="d-flex justify-content-between">
                      <div class="userDetails d-flex">
                          <div class="userDetailsImage mr-2"></div>
                          <ul class="userDetailsNameJob p-0 m-0">
                              <li class="userDetailsName">Ram Lal Chadd</li>
                              <li class="userDetailsJobDone">78%</li>
                          </ul>
                      </div>
                      <div class="userDetailsRating">
                          <ul class="userDetailsNameJob p-0 m-0 text-right">
                            <label class="rating-star "  >
                                    <i class="fa fa-star{{ $pro_rating >= 1 ? '' : '-o' }}"></i>
                                    <i class="fa fa-star{{ $pro_rating >= 2 ? '' : '-o' }}"></i>
                                    <i lass="fa fa-star{{ $pro_rating >= 3 ? '' : '-o' }}"></i>
                                    <i class="fa fa-star{{ $pro_rating >= 4 ? '' : '-o' }}"></i>
                                    <i class="fa fa-star{{ $pro_rating >= 5 ? '' : '-o' }}"></i>
                                     </label>
                              <li class="userDetailsRating text-right">&#9733; &#9733; &#9733; &#9733; &#9733;</li>
                              <li class="userDetailsJobDone"><span class="text-right">78%</span> </li>
                          </ul>
                      </div>
                  </div>
                </div>
              </div> --}}
          </div>
        </div>
  
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
  
      </div>
    </div>
  </div>