   ////////   **************  cab details page  *****************  ////////
// get driver details 
var maplat  = 30.7046;
var maplong = 76.7179;
var map = '';
var product_image = '';
themeType = [
    {
        featureType: "poi",
        elementType: "labels",
        stylers: [
            { visibility: "off" }
        ]
    }
];

initMap();

function initMap() {
      map = new google.maps.Map(document.getElementById("map_canvas"), {
        zoom: 12,
        center: {
            lat: maplat,
            lng: maplong
        },
        styles: themeType,
    });
   
}


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
                    product_image = response.data.product_image;
                    getOrderDriverDetails(response.data.dispatch_traking_url,response.data.id,product_image)
                },3000);
            }
        }
    });
}




function getOrderDriverDetails(dispatch_traking_url,order_id,product_image) {
    var new_dispatch_traking_url = dispatch_traking_url.replace('/order/','/order-details/');
    $.ajax({
        type:"POST",
        dataType: "json",
        url: order_tracking_details_url,
        data:{new_dispatch_traking_url:new_dispatch_traking_url,order_id:order_id},
        success: function( response ) {

            var alltask = response.data.tasks;
            var agent_location = response.data.agent_location;
            showroute(alltask,agent_location,map,product_image);
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



function showroute(alltask,agent_location,map,product_image){

    var url = window.location.origin;

    if(alltask.length > 0){
        var maplat  = parseFloat(alltask[0]['latitude']);
        var maplong = parseFloat(alltask[0]['longitude']);
    }else{
        var maplat  = 30.7046;
        var maplong = 76.7179;
    }        
    
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({suppressMarkers: true});
   

    directionsRenderer.setMap(map);
    calculateAndDisplayRoute(directionsService, directionsRenderer,map);
    addMarker(agent_location,map,product_image);

    function calculateAndDisplayRoute(directionsService, directionsRenderer,map) {
        const waypts = [];
        const checkboxArray = document.getElementById("waypoints");

        for (let i = 0; i < alltask.length; i++) {
            if (i != alltask.length - 1 && alltask[i].task_status != 4 && alltask[i].task_status != 5 ) {
               console.log(alltask[i]);
                waypts.push({
                    location: new google.maps.LatLng(parseFloat(alltask[i].latitude), parseFloat(alltask[i]
                        .longitude)),
                    stopover: true,
                });

                
            }
            var image = url+'/assets/newicons/'+alltask[i].task_type_id+'.png';

            makeMarker({lat: parseFloat(alltask[i].latitude),lng:  parseFloat(alltask[i]
                        .longitude)},image,map);
        }

        directionsService.route({
                origin: new google.maps.LatLng(parseFloat(agent_location.lat), parseFloat(agent_location.long)),
                destination: new google.maps.LatLng(parseFloat(alltask[alltask.length - 1].latitude),
                    parseFloat(alltask[alltask.length - 1].longitude)),
                waypoints: waypts,
                optimizeWaypoints: false,
                travelMode: google.maps.TravelMode.DRIVING,
            },
            (response, status) => {
                if (status === "OK" && response) {
                    directionsRenderer.setDirections(response);
                    const route = response.routes[0];
                    const summaryPanel = document.getElementById("directions-panel");
                   // summaryPanel.innerHTML = "";

                    // For each route, display summary information.
                    // for (let i = 0; i < route.legs.length; i++) {
                    //     const routeSegment = i + 1;
                    //     summaryPanel.innerHTML +=
                    //         "<b>Route Segment: " + routeSegment + "</b><br>";
                    //     summaryPanel.innerHTML += route.legs[i].start_address + " to ";
                    //     summaryPanel.innerHTML += route.legs[i].end_address + "<br>";
                    //     summaryPanel.innerHTML += route.legs[i].distance.text + "<br><br>";
                    // }
                } else {
                    //window.alert("Directions request failed due to " + status);
                }
            }
        );
    }

    // Adds a marker to the map.
    function addMarker(agent_location,map,product_image) {
     // Add the marker at the clicked location, and add the next-available label
     // from the array of alphabetical characters.

     var image = {
     url: location_icon, // url
     scaledSize: new google.maps.Size(50, 50), // scaled size
     origin: new google.maps.Point(0,0), // origin
     anchor: new google.maps.Point(22,22) // anchor
    }; 
     new google.maps.Marker({
        position: {lat: parseFloat(agent_location.lat),lng:  parseFloat(agent_location.long)},
        label: null,
        icon: image,
        map: map,
        
     });
     }

     function makeMarker( position,icon,map) {
        new google.maps.Marker({
        position: position,
        map: map,
        icon: icon,
        });
     }


}
    
      
