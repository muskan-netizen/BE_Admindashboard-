<style>
ul.list.week-list {
    display: flex;
    align-items: center;
    justify-content: space-around;
    margin-bottom: 10px;
}
ul.list.week-list li {
    height: 50px;
    width: 50px;
    border-radius: 50px;
    line-height: 50px;
    border: 1px solid #ddd;
    text-align: center;
    cursor: pointer;
}
ul.list.week-list li.active,
ul.list.week-list li:hover{background-color: #ddd}
.datetime-datepicker.error {
    border: 1px solid red;
}

.alRecurringBookingSinglePageView .single_product-input input {
    width: 48%;
    display: inline-block;
    border: none;
    height: auto;
    padding: 30px 0px 10px 4px;
    font-size: 13px;
}
.alRecurringBookingSinglePageView .single_product-input {
    border: 1px solid#cfc9c9;
    width: 95%;
    border-radius: 5px;
    position: relative;
}
.alRecurringBookingSinglePageView .single_cart-temp_label{
  width: 88%;
  position: absolute;
  z-index: 1;
}
.alRecurringBookingSinglePageView .single_cart-temp_label label{
    display: inline-block;
    width: 46%;
    font-size: 12px;
    padding: 6px 0px 0px 6px;
    color: #000;
}

.alRecurringBookingSinglePageView .single_product-input input:nth-child(1) {
  border-right: 1px solid#cfc9c9;
    border: 1px solid#cfc9c9;
    border-top: none;
    border-bottom: none;
    border-left: none;
}
.disclaimer{
    font-style: italic;
}
.check_recurring  {
  display: block;
  position: relative;
  padding-left: 22px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 12px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.check_recurring  input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.check_recurring  .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #eee;
    border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.check_recurring :hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.check_recurring  input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.check_recurring :after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.check_recurring  input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.check_recurring  .checkmark:after {
    top: 7px;
    left: 8px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: white;
}
.check_recurring span {
    font-size: 16px;
    font-weight: 400;
}
div#date_recurring {
    width: 100%;
    margin-top: 18px;
}
div#custom_date_recurring {
    width: 100%;
    margin-top: 18px;
}
.recurring_booking_warpper{
    border: 1px solid #eee;
    padding: 10px;
    margin-left: 10px;
}

</style>


<div class="alRecurringBookingSinglePageView">
  <div class="addManualTime">
    <div class="addManualTimeGroup" style="text-align:left;">
        <div class="row mb-3 recurring_booking_warpper">
            <div class="col-md-2">
                <label class="check_recurring m-0">
                    <span>{{__('Once')}}</span>
                    <input type="radio" name="booking_type" checked value="5">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="col-md-2">
                <label class="check_recurring m-0">
                    <span>{{__('Daily')}}</span>
                    <input type="radio" name="booking_type" value="1">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="col-md-2">
                <label class="check_recurring m-0">
                    <span>{{__('Weekly')}}</span>
                    <input type="radio" name="booking_type" value="2">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="col-md-3">
                <label class="check_recurring m-0">
                    <span>{{__('Monthly')}}</span>
                    <input type="radio" name="booking_type" value="3">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="col-md-3">
                <label class="check_recurring m-0">
                    <span>{{__('Custom')}}</span>
                    <input type="radio" name="booking_type" value="4">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div id="daily_booking" class="d-none">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group" id="daily_timeInput">
                            {!! Form::text('daily_date_time','', ['class' => 'form-control downside datetime-datepicker','id' => 'daily-datepicker','readonly'=>'true','placeholder'=>'Select day']) !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="booking-time-section w-100">
                            <input class="time booking-time form-control" type="time" name ="daily_booking_time" placeholder="Select time" id="daily_booking_time"  />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="weekly_booking" class="d-none">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-12">
                        <div class="weeknames">
                            @php  $weekDay = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']; @endphp
                            <ul class="list week-list">
                                @for ($i =1; $i <count($weekDay); $i++)
                                    <li> {{ $weekDay[$i]}}</li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-7">

                        <div class="form-group" id="weekly_timeInput">

                            {!! Form::text('weekly_date_time','', ['class' => 'form-control downside datetime-datepicker','id' => 'weekly-datepicker','readonly'=>'true','placeholder'=>'Select Week Days']) !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="booking-time-section w-100">
                            <input class="time booking-time form-control" type="time" name ="weekly_booking_time" placeholder="Select time" id="weekly_booking_time"  />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="month_booking" class="d-none">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group" id="month_timeInput">
                            {!! Form::text('month_date_time','', ['class' => 'form-control downside datetime-datepicker','id' => 'month-datepicker','readonly'=>'true','placeholder'=>'Select Month Days']) !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="booking-time-section w-100">
                            <input class="time booking-time form-control" type="time" name ="month_booking_time" placeholder="Select time" id="month_booking_time"  />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="custom_booking" class="d-none">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group" id="custom_timeInput">
                            {!! Form::text('custom_date_time','', ['class' => 'form-control downside datetime-datepicker','id' => 'custom-datepicker','readonly'=>'true','placeholder'=>'Select Custom Days']) !!}
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="booking-time-section w-100">
                            <input class="time booking-time form-control" type="time" name ="custom_booking_time" placeholder="Select Time" id="custom_booking_time"  />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="is_recurring_booking" value="0">


        <!--<div id="date_recurring" class="d-none">
            <div class="single_cart-temp_label">
                <label class="text-left mb-0">Start/Date</label>
                <label class="text-left mb-0">End/Date</label>
            </div>
            <div class="single_product-input mb-2">
                <input id="blocktime" class="form-control" autofocus readonly>
                <input id="blocktime2" class="form-control" readonly>
            </div>
        </div>
        <div id="custom_date_recurring" class="d-none">
			<div class="col-md-12">
				<div class="form-group" id="start_date_timeInput">
					{!! Form::text('start_date_time','', ['class' => 'form-control downside datetime-datepicker','id' => 'start-datepicker','readonly'=>'true','placeholder'=>'Select Custom days']) !!}
					<span class="invalid-feedback" role="alert">
						<strong></strong>
					</span>
				</div>
			</div>
		</div>-->

    </div>
  </div>
</div>

@section('script-bottom-js')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" ></script> --}}
  {{-- <script src="{{ asset('assets/js/backend/product/productSchedule.js')}}"></script> --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="{{asset('assets/libs/jquery-clock-timepicker/jquery-clock-timepicker.js')}}"></script>
  <script type="text/javascript">
    $( document ).ready(function() {
        //$('.booking-time').clockTimePicker();
        $(document).delegate( ".check_time_availability", "click", function() {
            var selectedStartDate   = $("#blocktime").val();
            var selectedEndDate     = $("#blocktime2").val();
            var timeSlot		    = $("#booking-time").val();

            var formData            =  {
                                            product_id:$("input[name='product_id']").val(),
                                            selectedStartDate:selectedStartDate,
                                            selectedEndDate:selectedEndDate,
                                            timeSlot:timeSlot
                                        }

           check_product_availibility(formData);
        });
        $(document).delegate( ".week-list li", "click", function() {
            $(this).toggleClass('active');
        });

    });

    /*$('.datetime-datepicker').datepicker({
        startDate: new Date(),
        multidate: true,
        format: 'yyyy-mm-dd',
        clearBtn: true
    }).on('changeDate', function(e) {
        console.log('date change')
    });*/



    $(document).on("click", ".check_recurring input", async function () {
        if($(this).val() == 1){
            $("#daily_booking").removeClass('d-none').addClass('d-block');
            $("#weekly_booking").removeClass('d-block').addClass('d-none');
            $("#month_booking").removeClass('d-block').addClass('d-none');
            $("#custom_booking").removeClass('d-block').addClass('d-none');
            $("#is_recurring_booking").val(1);
            $("#weekly-datepicker").val('');
            $("#month-datepicker").val('');
            $("#custom-datepicker").val('');
        }
        else if($(this).val() == 2){
            $("#weekly_booking").removeClass('d-none').addClass('d-block');
            $("#daily_booking").removeClass('d-block').addClass('d-none');
            $("#month_booking").removeClass('d-block').addClass('d-none');
            $("#custom_booking").removeClass('d-block').addClass('d-none');
            $("#is_recurring_booking").val(1);
            $("#daily-datepicker").val('');
            $("#month-datepicker").val('');
            $("#custom-datepicker").val('');
        }
        else if($(this).val() == 3){
            $("#month_booking").removeClass('d-none').addClass('d-block');
            $("#daily_booking").removeClass('d-block').addClass('d-none');
            $("#weekly_booking").removeClass('d-block').addClass('d-none');
            $("#custom_booking").removeClass('d-block').addClass('d-none');
            $("#is_recurring_booking").val(1);
            $("#daily-datepicker").val('');
            $("#weekly-datepicker").val('');
            $("#custom-datepicker").val('');
        }
        else if($(this).val() == 4){
            $("#custom_booking").removeClass('d-none').addClass('d-block');
            $("#daily_booking").removeClass('d-block').addClass('d-none');
            $("#weekly_booking").removeClass('d-block').addClass('d-none');
            $("#month_booking").removeClass('d-block').addClass('d-none');
            $("#is_recurring_booking").val(1);
            $("#daily-datepicker").val('');
            $("#weekly-datepicker").val('');
            $("#month-datepicker").val('');
        }
        else if($(this).val() == 5){
            $("#custom_booking").removeClass('d-block').addClass('d-none');
            $("#daily_booking").removeClass('d-block').addClass('d-none');
            $("#weekly_booking").removeClass('d-block').addClass('d-none');
            $("#month_booking").removeClass('d-block').addClass('d-none');
            $("#is_recurring_booking").val(0);
            $("#daily-datepicker").val('');
            $("#weekly-datepicker").val('');
            $("#month-datepicker").val('');
            $("#custom-datepicker").val('');
        }
    });

    $(function(e) {
        actual_price            = '{{@$product->variant[0]->actual_price}}';
        default_currency        = "{{Session::get('currencySymbol')}}";
        var selectedStartDate   = ''; // selected start
        var selectedEndDate     = ''; // selected end
        var currentDate         = moment().format("M/DD/YY");
        $checkinInput           = $('#blocktime');
        $checkoutInput          = $('#blocktime2');

        $('#daily-datepicker').daterangepicker({
            locale: {
                  format: 'M/DD/YY'
            },
            opens: 'left',
            startDate: moment(),
            endDate: moment(),
            minDate:new Date(),
            autoUpdateInput: false,
        }, function(start, end, label) {
            //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            var date = start.format('YYYY-MM-DD')+','+ end.format('YYYY-MM-DD');
            var start_date  = start.format('YYYY-MM-DD');
            var end_date    = end.format('YYYY-MM-DD');

            if (start_date == end_date) {
                $("#daily-datepicker").val('');
                $("#daily-datepicker").addClass('error');
            }else{
                $("#daily-datepicker").val(date);
                $("#daily-datepicker").removeClass('error');
            }

            $("#is_recurring_booking").val(1);
        });


        $('#weekly-datepicker').daterangepicker({
            locale: {
                  format: 'M/DD/YY'
            },
            opens: 'left',
            startDate: moment(),
            endDate: moment(),
            minDate:new Date(),
            autoUpdateInput: false,
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            var date = start.format('YYYY-MM-DD')+','+ end.format('YYYY-MM-DD');
            $("#weekly-datepicker").val(date);
            $("#is_recurring_booking").val(1);
        });


        $('#month-datepicker').daterangepicker({
            locale: {
                  format: 'M/DD/YY'
            },
            opens: 'left',
            startDate: moment(),
            endDate: moment(),
            minDate:new Date(),
            autoUpdateInput: false,
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            var date = start.format('YYYY-MM-DD')+','+ end.format('YYYY-MM-DD');
            $("#month-datepicker").val(date);
            $("#is_recurring_booking").val(1);
        });

        $('#custom-datepicker').daterangepicker({
            locale: {
                  format: 'M/DD/YY'
            },
            opens: 'left',
            startDate: moment(),
            endDate: moment(),
            minDate:new Date(),
            autoUpdateInput: false,
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            var date = start.format('YYYY-MM-DD')+','+ end.format('YYYY-MM-DD');
            $("#custom-datepicker").val(date);
            $("#is_recurring_booking").val(1);
        });

        async function check_product_availibility(formData){

            console.log('formData',formData);

            if(formData.selectedEndDate == undefined){
                formData.selectedEndDate = '';
            }
            if(formData.selectedStartDate == undefined){
                formData.selectedStartDate = '';
            }
            if(formData.selectedStartDate!='' && formData.selectedStartDate!=''){
                $(".booking-time-section").removeClass('d-none').addClass('d-block');
            }

            axios.post(`vendor-time-slot`, formData)
            .then(async response => {
            //console.log(response);
                var data = response.data.variant_data;

                if(response.data.success){

                  var available_product_variant = data.available_product_variant;
                  var end_time = data.end_time;
                  var start_time = data.start_time;
                  if(available_product_variant) {
                    //console.log( data);
                    $('#available_product_variant').val(available_product_variant);
                    $('#start_time').val(start_time);
                    $('#end_time').val(end_time);
                    product_variant_data = data.product_variant_data;
                    if(product_variant_data) {
                      var incremental_hrs = document.getElementById('incremental_hrs').value;
                      populateProductData(product_variant_data);
                      calculation(incremental_hrs,product_variant_data.incremental_price,product_variant_data.incremental_price_per_min);
                      if(product_variant_data.check_if_in_cart.length > 0){
                          product_variant_data.check_if_in_cart.map(checkIfInCart);
                        //checkIfInCart(product_variant_data.check_if_in_cart);
                      } else {
                        localStorage.setItem('in_cart','false');
                      }
                    }
                  } else {
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Already booked, Please select diffrent slot!',
                    })
                  }

                } else{
                  Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Something went wrong, try again later!',
                    })
                }
            })
            .catch(e => {
              //console.log(e);
                Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Something went wrong, try again later!',
                })
            })
        }

        function calculation(incremental_min,incremental_price,incremental_price_per_min){
            var total_additional_price = NumberFormatHelper.formatPrice((incremental_min/incremental_price_per_min));
           // //console.log(total_additional_price);
            var total_minutes =  (parseInt(default_minutes)+parseInt(incremental_min));
            var total_calculated_price =  (parseInt(actual_price)+parseInt(total_additional_price));
            //$('.total_duration').html(total_minutes);
            //$('.total_price').html(NumberFormatHelper.formatPrice(total_calculated_price));

        }

        function populateProductData(productData){
            if(productData){
              actual_price = NumberFormatHelper.formatPrice(productData.actual_price);
              incremental_price = productData.incremental_price;
              $('.product_fixed_price').html(actual_price);
              default_minutes = timeConvertCal(productData.product.minimum_duration,productData.product.minimum_duration_min);
              default_step = timeConvertCal(productData.product.additional_increments,productData.product.additional_increments_min)
              //$('.total_duration').html(default_minutes);
              $('.total_price').html(actual_price);
              $('.min_hrs').html(productData.product.minimum_duration);
              $('.min_min').html(productData.product.minimum_duration_min);
              $('.addtional_hrs').html(productData.product.additional_increments);
              $('.addtional_min').html(productData.product.additional_increments_min);
              $('.variant_incremental_price').html(productData.incremental_price);
              //$('.disclaimer').html(`<p>(Extra minutes will be calculated in multiple of ${default_currency}${incremental_price} per ${default_step} min)</p>`);
              $("#incremental_hrs").attr('step', default_step);
            }
        }

        function init(){
          $('.incremental_hrs').val(0);
          $('#incremental_hrs_hidden').val(base_hours_min);
          var formData = {
            variant_option_id:$('.changeVariant:checked').val(),
            product_id:$("input[name='product_id']").val(),
            selectedStartDate:$('#blocktime').val(),
            selectedEndDate:$('#blocktime2').val()
          }
          check_product_availibility(formData);
        }

        function diff_minutes(dt2, dt1) {
          var start_date    = new Date(dt2);
          var end_date      = new Date(dt2);
          return Math.abs(new Date(dt2) - new Date(dt1))/60000;
        }
        function checkIfInCart(v_p) {
          localStorage.setItem('in_cart','false');
            if(v_p.variant_id == $('#prod_variant_id').val()){
                localStorage.setItem('in_cart','true');
            }
        }
        //init();

      });
  </script>
@endsection
