
//jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

$(".next").click(function(){


    var div_id = $(this).attr('id');
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parent();
	next_fs = $(this).parent().next();

	//activate next step on progressbar using the index of next_fs
	
	//show the next fieldset
	next_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
			scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
			left = (now * 50)+"%";
			//3. increase opacity of next_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({
        'transform': 'scale('+scale+')',
        'position': 'absolute'
      });
			next_fs.css({'left': left, 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});

    if(div_id == "select_vendor")
    {
        var rental_time = $('#datetime-picker').val();
        var rental_price = $('#rental_price').val();
        var vendorId = $("#default_cab_vendor_id").val($(this).data('vendor'));
        fetch(get_rental_vehicle_list, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded' // Set the appropriate content type
            },
            body: new URLSearchParams({
                schedule_date_delivery: rental_time,
                category_id: category_id,
                vendor_id: vendorId,
                "_token": csrf_token,
                rental_price :rental_price
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.status === 'Success') {
                $('#search_product_main_div').html('');
                $('#search_product_rider_main_div').html('');
        
                if (data.data.length !== 0) {
                    var productData = _.extend({ Helper: NumberFormatHelper }, { results: data.data.products });
                    let products_template = _.template($('#products_template').html());
                    let products_rider_template = _.template($('#products_rider_template').html());
        
                    $("#search_product_main_div").append(products_template(productData));
                    $("#search_product_rider_main_div").append(products_rider_template(productData));
        
                    if ($('input[name="is_cab_pooling_radio"]:checked').val() == 0 || $('input[name="is_cab_pooling_radio"]:checked').val() === undefined) {
                        $("#search_product_main_div .double_price_p").hide();
                        $("#search_product_main_div .single_price_p").show();
        
                        $(".TypeBookingRec").hide();
                        $(".TypeBookingNow").show();
                    } else if (is_cab_pooling == 4) {
                        $("#search_product_main_div .double_price_p").show();
                        $("#search_product_main_div .single_price_p").hide();
        
                        $(".TypeBookingRec").show();
                        $(".TypeBookingNow").hide();
                    } else {
                        $("#search_product_main_div .double_price_p").show();
                        $("#search_product_main_div .single_price_p").hide();
        
                        $(".TypeBookingRec").hide();
                        $(".TypeBookingNow").show();
                    }
        
                    let is_friend = $('input[name="is_for_friend"]:checked').val();
                    if (is_friend == undefined || is_friend == '0') {
                        $("#search_product_main_div").show();
                        $("#search_product_rider_main_div").hide();
                    } else {
                        $("#search_product_main_div").hide();
                        $("#search_product_rider_main_div").show();
                    }
                } else {
                    $("#search_product_main_div ").html('<p class="text-center my-3">'+ no_result_message +'</p>').show();
                    $("#search_product_rider_main_div ").html('<p class="text-center my-3">'+ no_result_message +'</p>');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
        
    }
});

$(".previous").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parent();
	previous_fs = $(this).parent().prev();
	
	//de-activate current step on progressbar
	$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
	
	//show the previous fieldset
	previous_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale previous_fs from 80% to 100%
			scale = 0.8 + (1 - now) * 0.2;
			//2. take current_fs to the right(50%) - from 0%
			left = ((1-now) * 50)+"%";
			//3. increase opacity of previous_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'left': left});
			previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});



const plusButton = document.getElementById('plusButton');
        const minusButton = document.getElementById('minusButton');
        const boxes = document.querySelectorAll('.custom-box');
        const buttonText = document.getElementById('buttonText');
        let currentIndex = 0;

        plusButton.addEventListener('click', () => {
            if (currentIndex < boxes.length) {
                boxes[currentIndex].classList.add('filled-box');
                currentIndex++;
                updateRentalPrice();
                $('#rental_hours').val(currentIndex);

                console.log($('#rental_hours').val());
                
            }
        });

        minusButton.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                boxes[currentIndex].classList.remove('filled-box');
                updateRentalPrice();
                $('#rental_hours').val(currentIndex);
                console.log($('#rental_hours').val());
            }
        });

        function updateRentalPrice() {

            var rental_hours = currentIndex.toString();
            fetch(hourly_rental_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                rental_hours:parseInt(rental_hours),
                "_token": csrf_token
             })
            })
            .then(response => response.json())
            .then(data => {
                const buttonTextElement = $('.hourly_price');
                if (buttonTextElement) {
                    $('.hourly_price').text("$"+data.total_rental_price+"/hr");
                    $('#rental_price').val(data.total_rental_price);
                    $('#buttonText').text(currentIndex.toString());
                    
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });

        }
		
		$("#datetime-picker").flatpickr({
        enableTime: true,
        disableMobile: true,
        dateFormat: "Y-m-d H:i:S",
        minDate: "today",
        time_24hr: true,
        locale: "en"
        });

        document.getElementById('leave-now').addEventListener('click', function (e) {
            e.preventDefault();
            const currentDateTime = new Date();
            const formattedDateTime = formatDateTime(currentDateTime);
            document.getElementById('datetime-picker').value = formattedDateTime;
            document.getElementById('booking-time').value = formattedDateTime;
        });
        
        function formatDateTime(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Month starts from 0
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
        
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }


        