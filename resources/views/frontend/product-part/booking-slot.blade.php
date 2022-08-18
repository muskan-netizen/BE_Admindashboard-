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


@section('script')

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" ></script> --}}
  {{-- <script src="{{ asset('assets/js/backend/product/productSchedule.js')}}"></script> --}}

  <script type="text/javascript">
     $(function() {
        var currentDate = moment().format("M/DD/YY hh:mm A");
        $checkinInput = $('#blocktime');
        $checkoutInput = $('#blocktime2');
        $checkinInput.val( moment().startOf('hour').format("M/DD/YY hh:mm A"));
        $checkoutInput.val(moment().startOf('hour').add(24, 'hour').format("M/DD/YY hh:mm A"));

        $('#blocktime, #blocktime2').daterangepicker({
            locale: {
                  format: 'M/DD/YY hh:mm A'
            },
            timePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(24, 'hour'),
            minDate:new Date(),
            //"alwaysShowCalendars": true,
            // "minDate": currentDate,
            // "maxDate": moment().add('months', 1),
            autoApply: true,
            autoUpdateInput: false
        }, function(start, end, label) {
          var selectedStartDate = start.format('M/DD/YY hh:mm A'); // selected start
          var selectedEndDate = end.format('M/DD/YY hh:mm A'); // selected end

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
          var formData = {
            variant_id:$('.product_id:checked').val(),
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
                // if(response.data.success){
                //     Swal.fire(
                //         'Manual time added successfully!',                                    
                //         'success'
                //     )
                // } else{
                //     Swal.fire(
                //         'This slot is already booked, Please try other.',                                    
                //         'error'
                //     )
                // }
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