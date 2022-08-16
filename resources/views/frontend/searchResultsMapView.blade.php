@extends('layouts.store', ['title' => "Search Results"])
@section('css')
<style type="text/css">
.main-menu .brand-logo{display:inline-block;padding-top:20px;padding-bottom:20px}.slick-track{margin-left:0}.product-box .product-detail h4,.product-box .product-info h4{font-size:16px}
</style>
<link rel="stylesheet" type="text/css" href="{{asset('front-assets/css/price-range.css')}}">
@endsection
@section('content')

<section class="section-b-space ratio_asos alPageSearchView">
    <div class="collection-wrapper">
        <div class="container">
            <div class="collection-content my-4">
                <div class="page-main-content">
                    <div class="collection-product-wrapper w-100">
                        
                        <h4>Showing Results for "{{$keyword}}"</h4>
                        <div class="displayProducts">
                            <div class="product-wrapper-grid">
                                <div class="googleMapArea col-md-12 p-0">
                                    <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13720.904154980397!2d76.81441854999998!3d30.71204525!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1657101273720!5m2!1sen!2sin" width="100%" height="550" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
                                    <div id="vendor-map-container">
                                        <div id="vendor-map" class="w-100" style="height:400px"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{asset('front-assets/js/rangeSlider.min.js')}}"></script>
<script src="{{asset('front-assets/js/my-sliders.js')}}"></script>
<script>
    vendorAllOnMap();
    function vendorAllOnMap() {
        var latitude = "{{ $vendorLatLong[0][0] }}";
        var longitude = "{{ $vendorLatLong[0][1] }}";
        var latlng = new google.maps.LatLng(latitude, longitude);

        map = new google.maps.Map(document.getElementById('vendor-map'), {
            center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
            zoom: 12
        });

        var url = window.location.origin;
        var vendorData = {!!json_encode($listData)!!};
        console.log('vendorData', vendorData);
        //    vendor  markers
        for (let i = 0; i < vendorData.length; i++) {
            vendor = vendorData[i];

            if(vendor.address != null && vendor.latitude != "0.00000000" && vendor.longitude != "0.00000000" ){
                var contentString = '';

                contentString =
                    '<div id="content">' +
                    '<div id="siteNotice">' +
                    "</div>" +
                    '<h5 id="firstHeading" class="firstHeading">'+vendor.name+'</h5>' +
                    '<div id="bodyContent">' +
                    "<p><b>Address :- </b> " +vendor.address+ " " +
                    ".</p>" +
                    '<p><b>Contact: +'+ vendor?.dial_code +vendor?.phone_no+' </p>' +
                    "</div>" +
                    "</div>";


                const infowindow = new google.maps.InfoWindow({
                    content: contentString,
                    minWidth: 250,
                    minheight: 250,
                });
                // images = 'https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/Clientlogo/612e24163debe.png@webp';

                var image = {
                    //url: images, // url
                    scaledSize: new google.maps.Size(50, 50), // scaled size
                    origin: new google.maps.Point(0,0), // origin
                    anchor: new google.maps.Point(22,22) // anchor
                };
                const marker = new google.maps.Marker({
                    map: map,
                    position: { lat: parseFloat(vendor.latitude), lng: parseFloat(vendor.longitude) },
                //icon: image,
                });
                marker.addListener("click", () => {
                    infowindow.open(map, marker);
                });
            }

        }
    }

</script>
@endsection