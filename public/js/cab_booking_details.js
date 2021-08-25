   ////////   **************  cab details page  *****************  ////////

   function setOrderDetailsPage() {
    $('.address-form').addClass('d-none');
    $('.cab-detail-box').removeClass('d-none');
     $.ajax({
        type: "POST",
        dataType: 'json',
        url: order_place_driver_details_url,
        success: function(response) {
            $('#pickup_now').attr('disabled', false);
            $('#pickup_later').attr('disabled', false);
            if(response.status == '200'){
                $('#cab_detail_box').html('');
                let order_success_template = _.template($('#order_success_template').html());
                $("#cab_detail_box").append(order_success_template({result: response.data, product_image: response.data.product_image})).show();
                setInterval(function(){
                    getOrderDriverDetails(response.data.dispatch_traking_url,response.data.id)
                },3000);
            }
        }
    });
}




function getOrderDriverDetails(dispatch_traking_url,order_id) {
    var new_dispatch_traking_url = dispatch_traking_url.replace('/order/','/order-details/');
    $.ajax({
        type:"POST",
        dataType: "json",
        url: order_tracking_details_url,
        data:{new_dispatch_traking_url:new_dispatch_traking_url,order_id:order_id},
        success: function( response ) {
            if(response.data.agent_location != null){
                $('#searching_main_div').remove();
                $('#driver_details_main_div').show();
                $('#driver_name').html(response.data.order.name).show();
                $('#driver_image').attr('src', response.data.agent_image).show();
                $('#driver_phone_number').html(response.data.order.phone_number).show();
                $("#dispatcher_status_show").html(response.data.order_details.dispatcher_status);
            }
        }
    });
}

// get driver details 



$(document).ready(function () {
    var selected_address = '';
    const styles = [{"stylers":[{"visibility":"on"},{"saturation":-100},{"gamma":0.54}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"water","stylers":[{"color":"#4d4946"}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels.text","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"labels.text","stylers":[{"visibility":"simplified"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"gamma":0.48}]},{"featureType":"transit.station","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"gamma":7.18}]}];
    $(document).on("click","#show_dir",function() {
        initMap2();
    });
    $(document).on("click", "#pickup_now, #pickup_later",function() {
        var schedule_datetime = '';
        if($(this).data('rel') =='pickup_later'){
            let temp_schedule_datetime = $('#schedule_datetime').val();
            if(!temp_schedule_datetime){
                $('#schedule_datetime_main_div').show();
                return false;
            }
            schedule_datetime = moment(temp_schedule_datetime).format('YYYY-MM-DD HH:MM')
        }
        var tasks = [];
        $('#pickup_now').attr('disabled', true);
        $('#pickup_later').attr('disabled', true);
        var pickup_location_names = $('input[name="pickup_location_name[]"]').map(function(){return this.value;}).get();
        var destination_location_names = $('input[name="destination_location_name[]"]').map(function(){return this.value;}).get();
        var pickup_location_latitudes = $('input[name="pickup_location_latitude[]"]').map(function(){return this.value;}).get();
        var pickup_location_longitudes = $('input[name="pickup_location_longitude[]"]').map(function(){return this.value;}).get();
        var destination_location_latitudes = $('input[name="destination_location_latitude[]"]').map(function(){return this.value;}).get();
        var destination_location_longitudes = $('input[name="destination_location_longitude[]"]').map(function(){return this.value;}).get();
        $(pickup_location_latitudes).each(function(index, latitude) {
            var sample_array = {};
            sample_array.barcode = null;
            sample_array.task_type_id = 1;
            sample_array.post_code = null;
            sample_array.short_name = null;
            sample_array.latitude = latitude;
            sample_array.appointment_duration = null;
            sample_array.address = pickup_location_names[index];
            sample_array.longitude = pickup_location_longitudes[index];
            tasks.push(sample_array);
        });
        $(destination_location_latitudes).each(function(index, latitude) {
            var sample_array = {};
            sample_array.barcode = null;
            sample_array.task_type_id = 2;
            sample_array.post_code = null;
            sample_array.short_name = null;
            sample_array.latitude = latitude;
            sample_array.appointment_duration = null;
            sample_array.address = destination_location_names[index];
            sample_array.longitude = destination_location_longitudes[index];
            tasks.push(sample_array);
        });
        let amount = $(this).data('amount');
        let product_image = $(this).data('image');
        let vendor_id = $(this).data('vendor_id');
        let coupon_id = $(this).data('coupon_id');
        let task_type = $(this).data('task_type');
        let product_id = $(this).data('product_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: cab_booking_create_order,
            data: {payment_option_id: 1, vendor_id: vendor_id, product_id: product_id,coupon_id: coupon_id, amount: amount, tasks: tasks, task_type:task_type, schedule_datetime:schedule_datetime},
            success: function(response) {
                $('#pickup_now').attr('disabled', false);
                $('#pickup_later').attr('disabled', false);
                if(response.status == '200'){
                    window.location.replace(response.data.route);
                    $('#cab_detail_box').html('');
                    let order_success_template = _.template($('#order_success_template').html());
                    $("#cab_detail_box").append(order_success_template({result: response.data, product_image: product_image})).show();
                    setInterval(function(){
                        getDriverDetails(response.data.dispatch_traking_url)
                    },3000);
                }
            }
        });
    });

 
 

    function getDriverDetails(dispatch_traking_url) {
        var new_dispatch_traking_url = dispatch_traking_url.replace('/order/','/order-details/')
        $.ajax({
            type:"POST",
            dataType: "json",
            url: order_tracking_details_url,
            data:{new_dispatch_traking_url:new_dispatch_traking_url},
            success: function( response ) {
                if(response.data.agent_location != null){
                    $('#searching_main_div').remove();
                    $('#driver_details_main_div').show();
                    $('#driver_name').html(response.data.order.name).show();
                    $('#driver_image').attr('src', response.data.agent_image).show();
                    $('#driver_phone_number').html(response.data.order.phone_number).show();
                }
            }
        });
    }

    $(document).on("click", ".add-more-location",function() {
        let random_id = Date.now();
        let destination_location_template = _.template($('#destination_location_template').html());
        $("#location_input_main_div").append(destination_location_template({random_id:random_id})).show();
        initializeNew(random_id);
        var destination_location_names = $('input[name="destination_location_name[]"]').map(function(){
           return this.value;
        }).get();
        if(destination_location_names.length == 5){
            $('.add-more-location').hide();
        }
    });
    $(document).on("click", ".location-inputs .apremove",function() {
        if($('#dots_'+$(this).data('rel')).length != 0){
            $('#dots_'+$(this).data('rel')).remove();
            var destination_location_names = $('input[name="destination_location_name[]"]').map(function(){
               return this.value;
            }).get();
            if(destination_location_names.length < 5){
                $('.add-more-location').show();
            }else{
                $('.add-more-location').hide();
            }
            initMap2();
        }
    });
    function initializeNew(random_id) {
      var input2 = document.getElementById('destination_location_'+random_id);
      if(input2){
        var autocomplete = new google.maps.places.Autocomplete(input2);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place2 = autocomplete.getPlace();
            $('#destination_location_latitude_'+random_id).val(place2.geometry.location.lat());
            $('#destination_location_longitude_'+random_id).val(place2.geometry.location.lng());
            initMap2();
        });
      }
    }
    $(document).on("click",".search-location-result",function() {
        $('#pickup_location').val($(this).data('address'));
        var latitude = $(this).data('latitude');
        var longitude = $(this).data('longitude');
        displayLocationCab(latitude, longitude);
    });
    function getVendorList(){
        var locations = [];
        let vendor_id = $(this).data('vendor');
        var pickup_location_latitude = $('input[name="pickup_location_latitude[]"]').map(function(){return this.value;}).get();
        var pickup_location_longitude = $('input[name="pickup_location_longitude[]"]').map(function(){return this.value;}).get();
        var destination_location_latitudes = $('input[name="destination_location_latitude[]"]').map(function(){return this.value;}).get();
        var destination_location_longitudes = $('input[name="destination_location_longitude[]"]').map(function(){return this.value;}).get();
        $(pickup_location_latitude).each(function(index, latitude) {
            var data = {};
            data.latitude = latitude;
            data.longitude = pickup_location_longitude[index];
            locations.push(data);
        });
        $(destination_location_latitudes).each(function(index, destination_location_latitude) {
            var data = {};
            data.latitude = destination_location_latitude;
            data.longitude = destination_location_longitudes[index];
            locations.push(data);
        });
        var post_data = JSON.stringify(locations);
        let pickup_location = $('#pickup_location').val();
        let destination_location = $('#destination_location').val();
        if(pickup_location && destination_location){
            $('.location-list').hide();
            $('.cab-booking-main-loader').show();
            $.ajax({
                data: {locations: post_data},
                type: "POST",
                dataType: 'json',
                url: autocomplete_urls,
                success: function(response) {
                    if(response.status == 'Success'){
                        $('.cab-booking-main-loader').hide();
                        $('#vendor_main_div').html('');
                        if(response.data.length != 0){
                            let vendors_template = _.template($('#vendors_template').html());
                            $("#vendor_main_div").append(vendors_template({results: response.data})).show();
                            if(response.data.length == 1){
                                $('.vendor-list').trigger('click');
                                $('.table-responsive').remove();
                            }else{
                                $('.vendor-list').first().trigger('click');
                            }
                        }else{
                            $("#vendor_main_div").html('<p class="text-center my-3">No result found. Please try a new search</p>').show();
                        }
                    }
                }
            });
        }
    }
    $(document).on("click",".vendor-list",function() {
        var locations = [];
        $('.cab-booking-main-loader').show();
        let vendor_id = $(this).data('vendor');
        var pickup_location_latitude = $('input[name="pickup_location_latitude[]"]').map(function(){return this.value;}).get();
        var pickup_location_longitude = $('input[name="pickup_location_longitude[]"]').map(function(){return this.value;}).get();
        var destination_location_latitudes = $('input[name="destination_location_latitude[]"]').map(function(){return this.value;}).get();
        var destination_location_longitudes = $('input[name="destination_location_longitude[]"]').map(function(){return this.value;}).get();
        $(pickup_location_latitude).each(function(index, latitude) {
            var data = {};
            data.latitude = latitude;
            data.longitude = pickup_location_longitude[index];
            locations.push(data);
        });
        $(destination_location_latitudes).each(function(index, destination_location_latitude) {
            var data = {};
            data.latitude = destination_location_latitude;
            data.longitude = destination_location_longitudes[index];
            locations.push(data);
        });
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {locations:locations},
            url: get_vehicle_list+'/'+vendor_id,
            success: function(response) {
                if(response.status == 'Success'){
                    $('.cab-booking-main-loader').hide();
                    $('#search_product_main_div').html('');
                    if(response.data.length != 0){
                        let products_template = _.template($('#products_template').html());
                        $("#search_product_main_div").append(products_template({results: response.data.products})).show();
                    }else{
                        $("#search_product_main_div ").html('<p class="text-center my-3">No result found. Please try a new search</p>').show();
                    }
                }
            }
        });
    });
    $(document).on("click","#promo_code_list_btn_cab_booking",function() {
        let amount = $(this).data('amount');
        let vendor_id = $(this).data('vendor_id');
        let product_id = $(this).data('product_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: promo_code_list_url,
            data: {amount:amount, vendor_id:vendor_id},
            success: function(response) {
                if(response.status == 'Success'){
                    $('#cab_booking_promo_code_list_main_div').html('');
                    if(response.data.length != 0){
                        $('.promo-box').removeClass('d-none');
                        $('.cab-detail-box').addClass('d-none');
                        let cab_booking_promo_code_template = _.template($('#cab_booking_promo_code_template').html());
                        $("#cab_booking_promo_code_list_main_div").append(cab_booking_promo_code_template({promo_codes: response.data, vendor_id:vendor_id, product_id:product_id, amount:amount})).show();
                    }else{
                        $("#cab_booking_promo_code_list_main_div").html(no_coupon_available_message).show();
                    }
                }
            }
        });
    });
    $(document).on("click","#remove_promo_code_cab_booking_btn",function() {
        let amount = $(this).data('amount');
        let vendor_id = $(this).data('vendor_id');
        let product_id = $(this).data('product_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: cab_booking_promo_code_remove_url,
            data: {amount:amount, vendor_id:vendor_id},
            success: function(response) {
                if(response.status == 'Success'){
                    $('#promo_code_list_btn_cab_booking').show();
                    $('#remove_promo_code_cab_booking_btn').hide();
                    $('.cab-detail-box #discount_amount').text('').hide();
                    $('.cab-detail-box .code-text').text("Select A Promo Code").show();
                    $('.cab-detail-box #real_amount').text(response.data.currency_symbol+' '+amount);
                }
            }
        });
    });
    $(document).on("click",".cab_booking_apply_promo_code_btn",function() {
        let amount = $(this).data('amount');
        let vendor_id = $(this).data('vendor_id');
        let coupon_id = $(this).data('coupon_id');
        let product_id = $(this).data('product_id');
        $.ajax({
            type: "POST",
            dataType: 'json',
            url:  apply_cab_booking_promocode_coupon_url,
            data: {amount:amount, vendor_id:vendor_id, product_id:product_id, coupon_id},
            success: function(response) {
                if(response.status == 'Success'){
                    $('.promo-box').addClass('d-none');
                    $('.cab-detail-box').removeClass('d-none');
                    $('#promo_code_list_btn_cab_booking').hide();
                    $('#remove_promo_code_cab_booking_btn').show();
                    let real_amount = $('.cab-detail-box #real_amount').text();
                    $('.cab-detail-box #discount_amount').text(real_amount).show();
                    $('.cab-detail-box .code-text').text('Code '+response.data.name+' applied').show();
                    $('.cab-detail-box #real_amount').text(response.data.currency_symbol+''+response.data.new_amount);
                }
            }
        });
    });
    $(document).on("click",".close-promo-code-detail-box",function() {
        $('.promo-box').addClass('d-none');
        $('.cab-detail-box').removeClass('d-none');
    });
    $(document).on("click",".close-cab-detail-box",function() {
        $('.cab-detail-box').addClass('d-none');
        $('.address-form').removeClass('d-none');
    });
    $(document).on("click",".vehical-view-box",function() {
        var locations = [];
        let product_id = $(this).data('product_id');
        var pickup_location_latitude = $('input[name="pickup_location_latitude[]"]').map(function(){return this.value;}).get();
        var pickup_location_longitude = $('input[name="pickup_location_longitude[]"]').map(function(){return this.value;}).get();
        var destination_location_latitudes = $('input[name="destination_location_latitude[]"]').map(function(){return this.value;}).get();
        var destination_location_longitudes = $('input[name="destination_location_longitude[]"]').map(function(){return this.value;}).get();
        $(pickup_location_latitude).each(function(index, latitude) {
            var data = {};
            data.latitude = latitude;
            data.longitude = pickup_location_longitude[index];
            locations.push(data);
        });
        $(destination_location_latitudes).each(function(index, destination_location_latitude) {
            var data = {};
            data.latitude = destination_location_latitude;
            data.longitude = destination_location_longitudes[index];
            locations.push(data);
        });
        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {locations:locations},
            url: get_product_detail+'/'+product_id,
            success: function(response) {
                if(response.status == 'Success'){
                    $('#cab_detail_box').html('');
                    if(response.data.length != 0){
                        $('.address-form').addClass('d-none');
                        $('.cab-detail-box').removeClass('d-none');
                        let cab_detail_box_template = _.template($('#cab_detail_box_template').html());
                        $("#cab_detail_box").append(cab_detail_box_template({result: response.data})).show();
                        getDistance();
                    }else{
                        $("#cab_detail_box ").html('<p class="text-center my-3">No result found. Please try a new search</p>').show();
                    }
                }
            }
        });
    });
    function initMap2() {
        var locations = [];
        let pickup_location_latitude = $('#pickup_location_latitude').val();
        let pickup_location_longitude = $('#pickup_location_longitude').val();
        var pointA = new google.maps.LatLng(pickup_location_latitude, pickup_location_longitude);
        map = new google.maps.Map(document.getElementById('booking-map'), {zoom: 7,center: pointA});
        map.setOptions({ styles:  styles});
        directionsService = new google.maps.DirectionsService;
        directionsDisplay = new google.maps.DirectionsRenderer({map: map});
        calculateAndDisplayRoute(directionsService, directionsDisplay);
    }
    function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        const waypts = [];
        var destination_location_names = $('input[name="destination_location_name[]"]').map(function(){
           return this.value;
        }).get();
        $(destination_location_names).each(function(index, destination_location_name) {
            waypts.push({
                location: destination_location_name,
                stopover: true,
              });
        });
        let origin = $('#pickup_location').val();
        let destination = $('#destination_location').val();
        if(origin && destination){
            directionsService.route({
                origin: origin,
                waypoints:waypts,
                optimizeWaypoints:true,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING,
            }, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                  var point = response.routes[0].legs[0];
                  directionsDisplay.setDirections(response);
                  getVendorList();
                } else {
                  window.alert('Directions request failed due to ' + status);
                  return false;
                }
            });
        }
    }
    initialize();
    function initialize() {
      var input = document.getElementById('pickup_location');
      var input2 = document.getElementById('destination_location');
      if(input){
        var autocomplete = new google.maps.places.Autocomplete(input);
        var autocomplete2 = new google.maps.places.Autocomplete(input2);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
           $('#pickup_location_latitude').val(place.geometry.location.lat());
           $('#pickup_location_longitude').val(place.geometry.location.lng());
           initMap2();
        });
        google.maps.event.addListener(autocomplete2, 'place_changed', function () {
            var place2 = autocomplete2.getPlace();
            $('#destination_location_latitude').val(place2.geometry.location.lat());
            $('#destination_location_longitude').val(place2.geometry.location.lng());
            initMap2();
        });
      }
    }
    function getDistance(){
     //Find the distance
     var distanceService = new google.maps.DistanceMatrixService();
     distanceService.getDistanceMatrix({
        origins: [$("#pickup_location").val()],
        destinations: [$("#destination_location").val()],
        travelMode: google.maps.TravelMode.WALKING,
        unitSystem: google.maps.UnitSystem.METRIC,
        durationInTraffic: true,
        avoidHighways: false,
        avoidTolls: false
    },
    function (response, status) {
        if (status !== google.maps.DistanceMatrixStatus.OK) {
            console.log('Error:', status);
        } else {
            console.log('distance is'+response.rows[0].elements[0].distance.text);
            console.log('duration is'+response.rows[0].elements[0].duration.text);
            $("#distance").text(response.rows[0].elements[0].distance.text).show();
            $("#duration").text(response.rows[0].elements[0].duration.text).show();
        }
    });
  }
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, null);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
    function showPosition(position) {
        let lat = position.coords.latitude;
        let long = position.coords.longitude;
        $('#addHeader1-latitude').val(lat);
        $('#addHeader1-longitude').val(long);
        displayLocationCab(lat, long);
    }
    if (!selected_address) {
        getLocation();
    }
    let lat = $("#booking-latitude").val();
    let long = $("#booking-longitude").val();
    displayLocationCab(lat, long);
    function displayLocationCab(latitude, longitude) {
        var geocoder;
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(latitude, longitude);
        const map = new google.maps.Map(document.getElementById('booking-map'), {
            center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            zoom: 13
        });
        map.setOptions({ styles:  styles});
        var icon_set = {
            url: live_location, // url
            scaledSize: new google.maps.Size(30, 30), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0, 0) // anchor
        };
        const marker = new google.maps.Marker({
            map: map,
            position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            icon : icon_set,
        });
        geocoder.geocode(
            { 'latLng': latlng },
            function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var add = results[0].formatted_address;
                        var value = add.split(",");
                        count = value.length;
                        country = value[count - 1];
                        state = value[count - 2];
                        city = value[count - 3];
                        $("#addHeader1-input").val(add);
                        $("#location_search_wrapper .homepage-address span").text(value).attr({ "title": value, "data-original-title": value });
                    }else {
                    }
                }else {
                    $("#address-input").val("Geocoder failed due to: " + status);
                }
            }
        );
    }
});

function addressInputDisplay(locationWrapper, inputWrapper, input) {
    $(inputWrapper).removeClass("d-none").addClass("d-flex");
    $(locationWrapper).removeClass("d-flex").addClass("d-none");
    var val = $(input).val();
    $(input).focus().val('').val(val);
}

function addressInputHide(locationWrapper, inputWrapper, input) {
    $(inputWrapper).addClass("d-none").removeClass("d-flex");
    $(locationWrapper).addClass("d-flex").removeClass("d-none");
}

function initMap() {
    const autocompletes = [];
    const locationInputs = document.getElementsByClassName("map-input");
    const geocoder = new google.maps.Geocoder;
    for (let i = 0; i < locationInputs.length; i++) {
        const input = locationInputs[i];
        const fieldKey = input.id.replace("-input", "");
        const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(fieldKey + "-longitude").value != '';
        const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || -33.8688;
        const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 151.2195;
        const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
            center: { lat: latitude, lng: longitude },
            zoom: 13
        });
        const marker = new google.maps.Marker({
            map: map,
            position: { lat: latitude, lng: longitude }
        });
        marker.setVisible(isEdit);
        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.key = fieldKey;
        autocompletes.push({ input: input, map: map, marker: marker, autocomplete: autocomplete });
    }
    for (let i = 0; i < autocompletes.length; i++) {
        const input = autocompletes[i].input;
        const autocomplete = autocompletes[i].autocomplete;
        const map = autocompletes[i].map;
        const marker = autocompletes[i].marker;
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            marker.setVisible(false);
            const place = autocomplete.getPlace();
            geocoder.geocode({ 'placeId': place.place_id }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    const lat = results[0].geometry.location.lat();
                    const lng = results[0].geometry.location.lng();
                    $(".homepage-address span").text(place.formatted_address);
                    setLocationCoordinates(autocomplete.key, lat, lng);
                }
            });
            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                input.value = "";
                return;
            }
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(13);
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
        });
    }
}
function setLocationCoordinates(key, lat, lng) {
    const latitudeField = document.getElementById(key + "-" + "latitude");
    const longitudeField = document.getElementById(key + "-" + "longitude");
    latitudeField.value = lat;
    longitudeField.value = lng;
}
google.maps.event.addDomListener(window, 'load', initMap);





