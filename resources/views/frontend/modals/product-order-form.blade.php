<form id="product-order-form-name" name="product-order-form-name" class="theme-form" action="javascript:void(0)" method="post" enctype="multipart/form-data">
    <div class="form-row"> 

                            @foreach($product_faqs as $key => $qs)
                            <div class="col-md-12 mb-3">
                                <label for="review">{{$qs->translations->first()->name}}</label>
                                {{-- <input type="hidden" name="product_order_form[{{$key}}]['question']" value="{{$qs->translations->first()->name}}">
                         --}}
                                <input type="text" class="form-control mb-0" name="{{$qs->translations->first()->name}}" >
                              
                              
                            </div>

                            @endforeach                   
       
        <span class="text-danger" id="error-msg"></span>
        <span class="text-success" id="success-msg"></span>
        <div class="col-md-12">
            <button class="btn btn-solid buttonload" type="submit" id="submit_productfaq">{{__('Submit')}}</button>
        </div>
        
    </div>
  </form>


