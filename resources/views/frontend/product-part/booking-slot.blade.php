<style>

.al_body_template_three .single_product-input input {
    width: 48%;
    display: inline-block;
    border: none;
    height: auto;
    padding: 30px 0px 10px 4px;
    font-size: 13px;
}
.al_body_template_three .single_product-input {
    border: 1px solid#cfc9c9;
    width: 45%;
    border-radius: 5px;
    position: relative;
}
.al_body_template_three .single_cart-temp_label{
  width: 45%;
  position: absolute;
  z-index: 999;
}
.al_body_template_three .single_cart-temp_label label{
    display: inline-block;
    width: 45%;
    font-size: 12px;
    padding: 6px 0px 0px 6px;
    color: #000;
}
.al_body_template_three .single_product-input input:nth-child(1) {
  border-right: 1px solid#cfc9c9;
    border: 1px solid#cfc9c9;
    border-top: none;
    border-bottom: none;
    border-left: none;
}
</style>

<div class="addManualTime">
  <div class="addManualTimeGroup" style="text-align:left;">
    <div class="single_cart-temp_label">
      <label class="text-left mb-0">Start/Date Time</label>    
      <label class="text-left mb-0">End/Date Time</label>    
    </div>
      <div class="single_product-input mb-2">
        <input id="blocktime" class="form-control" autofocus>
        <input id="blocktime2" class="form-control">
      </div>
  </div>
  
</div>
<div id="product_variant_additional_increment_wrapper">
  <div class="product-description border-product pb-0">
      <h6 class="product-title mt-0">{{__('Extended duration By('.@$product->additional_increments.'hr:'.@$product->additional_increments_min.'min/'.Session::get('currencySymbol').number_format(@$product->variant[0]->incremental_price * @$product->variant[0]->multiplier,2,".",",").')')}}:
        {{-- <b class="">{{Session::get('currencySymbol')}} {{number_format($product->variant[0]->incremental_price * $product->variant[0]->multiplier,2,".",",")}}</b> --}}
      </h6>
      <div class="qty-box mb-3">
          <div class="input-group">
              <span class="input-group-prepend">
                  <button type="button" class="btn incremental-left-minus" data-type="minus" data-field="" data-batch_count={{$product->batch_count}} data-minimum_order_count={{$product->minimum_order_count}}><i class="ti-angle-left"></i>
                  </button>
              </span>
              <input readonly  step="{{$product->additional_increments*60+$product->additional_increments_min}}" type="number" min="0" name="incremental_hrs"  onkeypress="return event.charCode > 47 && event.charCode < 58;" pattern="[0-9]{5}" id="incremental_hrs" class="form-control input-qty-number incremental_hrs"  value="0" data-incremental_hrs={{$product->additional_increments}}>
              <span class="input-group-prepend quant-plus">
                  <button type="button" class="btn incremental-right-plus" data-type="plus" data-field="" data-batch_count={{$product->batch_count}} data-incremental_hrs={{$product->additional_increments}}>
                      <i class="ti-angle-right"></i>
                  </button>
              </span>
          </div>
      </div>
      
  </div>
</div>



@section('script-bottom-js')

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" ></script> --}}
  {{-- <script src="{{ asset('assets/js/backend/product/productSchedule.js')}}"></script> --}}

  <script type="text/javascript">
     $(function(e) {
        var selectedStartDate = ''; // selected start
        var selectedEndDate = ''; // selected end
        var currentDate = moment().format("M/DD/YY hh:mm A");
        $checkinInput = $('#blocktime');
        $checkoutInput = $('#blocktime2');
        var min_dur_hrs = '{{ $product->minimum_duration }}';
        var min_dur_min = '{{ $product->minimum_duration_min }}';
        //$checkinInput.val( moment().add(min_dur_hrs,'hours').add(min_dur_min,'minutes').format("M/DD/YY hh:mm A"));
        $checkinInput.val( moment().format("M/DD/YY hh:mm A"));
        $checkoutInput.val(moment().add(min_dur_hrs,'hours').add(min_dur_min,'minutes').format("M/DD/YY hh:mm A"));
       
        
        $(".incremental-left-minus").on("click", function() {
            document.getElementById('incremental_hrs').stepDown();
            var incremental_hrs = document.getElementById('incremental_hrs').value;
            var start_current_time = $($checkinInput).val();
            var end_current_time = $($checkoutInput).val();
            if(incremental_hrs > 0) {
              var add_min = '{{ $product->additional_increments_min }}';
              var add_hrs = '{{ $product->additional_increments }}';
              $checkoutInput.val(moment(end_current_time).add(add_hrs,'hours').add(add_min,'minutes').format("M/DD/YY hh:mm A"));
              var checkOutPicker = $checkoutInput.data('daterangepicker');
            //checkOutPicker.setStartDate(selectedStartDate);
              checkOutPicker.setEndDate(moment(end_current_time).add(add_hrs,'hours').add(add_min,'minutes').format("M/DD/YY hh:mm A"));

              var checkInPicker = $checkinInput.data('daterangepicker');
              checkInPicker.setStartDate(selectedStartDate);
              checkInPicker.setEndDate(selectedEndDate);
              var formData = {
                variant_option_id:$('.changeVariant:checked').val(),
                product_id:$("input[name='product_id']").val(),
                selectedStartDate:start_current_time,
                selectedEndDate:end_current_time
              }
              check_product_availibility(formData);
            }
            
            console.log($($checkoutInput).val());
            console.log(document.getElementById('incremental_hrs').value);

        });

        $(".incremental-right-plus").on("click", function() {
          document.getElementById('incremental_hrs').stepUp();
            var incremental_hrs = document.getElementById('incremental_hrs').value;
            var end_current_time = $($checkoutInput).val();

            var start_current_time = $($checkinInput).val();
            var end_current_time = $($checkoutInput).val();
            // var minutes = (secondsToMinutes!=undefined) ? secondsToMinutes.split('.')??[1] : 0;
            // var hrs = (secondsToMinutes!=undefined) ? secondsToMinutes.split('.')??[0]:secondsToMinutes;
            var add_min = '{{ $product->additional_increments_min }}';
            var add_hrs = '{{ $product->additional_increments }}';
            $checkoutInput.val(moment(end_current_time).add(add_hrs,'hours').add(add_min,'minutes').format("M/DD/YY hh:mm A"));
            var checkOutPicker = $checkoutInput.data('daterangepicker');
            //checkOutPicker.setStartDate(selectedStartDate);
            checkOutPicker.setEndDate(moment(end_current_time).add(add_hrs,'hours').add(add_min,'minutes').format("M/DD/YY hh:mm A"));

              var formData = {
                variant_option_id:$('.changeVariant:checked').val(),
                product_id:$("input[name='product_id']").val(),
                selectedStartDate:start_current_time,
                selectedEndDate:end_current_time
              }
              check_product_availibility(formData);
            console.log($($checkoutInput).val());
            console.log(document.getElementById('incremental_hrs').value);
        });


        $('#blocktime, #blocktime2').daterangepicker({
            locale: {
                  format: 'M/DD/YY hh:mm A'
            },
            timePicker: true,
            startDate: moment(),
            endDate: moment().add(min_dur_hrs,'hours').add(min_dur_min,'minutes'),
            minDate:new Date(),
            //"alwaysShowCalendars": true,
            // "minDate": currentDate,
            // "maxDate": moment().add('months', 1),
            autoApply: true,
            autoUpdateInput: false
        }, function(start, end, label) {
           selectedStartDate = start.format('M/DD/YY hh:mm A'); // selected start
           selectedEndDate = end.format('M/DD/YY hh:mm A'); // selected end

          // Updating Fields with selected dates
          $checkinInput.val(selectedStartDate);
          $checkoutInput.val(selectedEndDate);

          // Setting the Selection of dates on calender on CHECKOUT FIELD (To get this it must be binded by Ids not Calss)
          var checkOutPicker = $checkoutInput.data('daterangepicker');
          checkOutPicker.setStartDate(selectedStartDate);
          checkOutPicker.setEndDate(selectedEndDate);

          // Setting the Selection of dates on calender on CHECKIN FIELD (To get this it must be binded by Ids not Calss)
          var checkInPicker = $checkinInput.data('daterangepicker');
          checkInPicker.setStartDate(selectedStartDate);
          checkInPicker.setEndDate(selectedEndDate);
          console.log(selectedEndDate);
          $('.incremental_hrs').val(0)
          var formData = {
            variant_option_id:$('.changeVariant:checked').val(),
            product_id:$("input[name='product_id']").val(),
            selectedStartDate:selectedStartDate,
            selectedEndDate:selectedEndDate
          }
          check_product_availibility(formData);

        });

        async function check_product_availibility(formData){
            console.log(formData);
            axios.post(`/booking/checkProductAvailibility`, formData)
            .then(async response => {
            console.log(response);
                var data = response.data.variant_data;
                console.log
                if(response.data.success){
                  var available_product_variant = data.available_product_variant;
                  var end_time = data.end_time;
                  var start_time = data.start_time;
                  if(available_product_variant) {
                    $('#available_product_variant').val(available_product_variant);
                    $('#start_time').val(start_time);
                    $('#end_time').val(end_time);
                  } else {
                    Swal.fire(
                      'Already booked, Please select diffrent slot!',                                    
                      'error'
                    )
                  }
                 
                } else{
                  Swal.fire(
                    'Something went wrong, try again later!',                                    
                    'error'
                  )
                }
            })
            .catch(e => {
                Swal.fire(
                    'Something went wrong, try again later!',                                    
                    'error'
                )
            })    
        } 

      });
  </script>
@endsection