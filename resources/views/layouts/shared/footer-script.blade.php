<!-- bundle -->
<!-- Vendor js -->
<?php
    $theme = \App\Models\ClientPreference::where(['id' => 1])->first();
    $analytics = getAdditionalPreference(['gtag_id', 'fpixel_id']);
if (Session::has('toaster')) {
    $toast = Session::get('toaster');
    echo '<script>
            $(document).ready(function(){
                $.NotificationApp.send("' . $toast["title"] . '", "' . $toast["body"] . '", "top-right", "' . $toast["color"] . '", "' . $toast["type"] . '");
            });
        </script>';
}
?>
<script src="{{asset('assets/libs/moment/moment.min.js')}}"></script>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{asset('assets/libs/selectize/selectize.min.js')}}"></script>
<script src="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js')}}"></script>
<script src="{{asset('assets/libs/multiselect/multiselect.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{asset('front-assets/js/underscore.min.js')}}"></script>
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script>
<script src="{{asset('assets/libs/dropify/dropify.min.js')}}"></script>
<script src="{{asset('front-assets/js/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/clockpicker/clockpicker.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('assets/libs/devbridge-autocomplete/devbridge-autocomplete.min.js')}}"></script>
<script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script>
<script src="{{asset('assets/js/pages/my-form-advanced.init.js')}}"></script>
<script src="{{asset('assets/libs/jquery-toast-plugin/jquery-toast-plugin.min.js')}}"></script>
<script src="{{asset('assets/js/pages/toastr.init.js')}}"></script>
<script src="{{asset('assets/libs/datatables/datatables.min.js')}}"></script>
<script src="https://cdn.socket.io/4.1.2/socket.io.min.js" integrity="sha384-toS6mmwu70G0fw54EGlWWeA4z3dyJ+dlXBtSURSKN4vyRFOcxd3Bzjj/AoOwY+Rg" crossorigin="anonymous"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" ></script> --}}
<script src="{{asset('assets/libs/datetimepicker/daterangepicker.min.js')}}" ></script>
<script src="{{ asset('assets/js/alert/alert.js') }}"></script>
<script src="{{asset('assets/js/backend/backend_common.js')}}"></script>


{{-- <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" ></script> --}}
{{-- add translation file  --}}
@include('layouts.language')
@yield('headerJs')
{{-- end translation file  --}}

{{-- <script src="{{asset('assets/libs/datetimepicker/jquery.datetimepicker.min.js')}}"></script> --}}
@if((!empty($socket_url)))
    <script src="{{$socket_url}}/socket.io/socket.io.js"></script>
@endif

<script>
    
    let stripe_publishable_key = "{{ $stripe_publishable_key }}";
    let is_hyperlocal = 0;
    var business_type = '';

    @if($client_preference_detail)
        @if((isset($client_preference_detail->is_hyperlocal)) && ($client_preference_detail->is_hyperlocal == 1))
            is_hyperlocal = 1;
        @endif

        @if((isset($client_preference_detail->business_type)) && ($client_preference_detail->business_type != ''))
            business_type = "{{$client_preference_detail->business_type}}";
        @endif
    @endif
    var base_url = "{{ url('/')}}";

    function gm_authFailure() {
        $('.excetion_keys').append('<span><i class="mdi mdi-block-helper mr-2"></i> <strong>Google Map</strong> key is not valid</span><br/>');
        $('.displaySettingsError').show();
    };

    const startLoader = function(element) {
        // check if the element is not specified
        if (typeof element == 'undefined') {
            element = "body";
        }
        // set the wait me loader
        $(element).waitMe({
            effect: 'bounce',
            text: 'Please Wait..',
            bg: 'rgba(255,255,255,0.7)',
            //color : 'rgb(66,35,53)',
            color: '#EFA91F',
            sizeW: '20px',
            sizeH: '20px',
            source: ''
        });
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {

            return false;
        }
        return true;
    }

    const stopLoader = function(element) {
        // check if the element is not specified
        if (typeof element == 'undefined') {
            element = 'body';
        }
        // close the loader
        $(element).waitMe("hide");
    }
</script>
@if(!str_contains(url()->current(), '/godpanel'))
@if((!empty(Auth::user())))
@if((!empty($socket_url)))
    <script>
        //createSocketConnection();
    </script>
@endif
<script>
     //createSocketConnection();
      $(document).ready( async function() {
       
        // Audio.prototype.play = (function(play) {

        //     return function() {
        //         var audio = this,
        //             args = arguments,
        //             promise = play.apply(audio, args);
        //             console.log('as');
        //         if (promise !== undefined) {
        //             promise.catch(_ => {
        //                 // Autoplay was prevented. This is optional, but add a button to start playing.
        //                 var el = document.createElement("button");
        //                 el.innerHTML = "Play";
        //                 el.addEventListener("click", function() {
        //                     play.apply(audio, args);
        //                 });
        //                 this.parentNode.insertBefore(el, this.nextSibling)
        //             });
        //         }
        //     };
        // })(Audio.prototype.play);
    //     var x = document.getElementById("orderAudio");
    //     console.log(x);
    //    x.play();
          //alert('hllo');
         //get_latest_order_socket('54855119');
      });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    function get_reached_vendor_location_socket(order_number){
        Audio.prototype.play = (function(play) {
            return function() {
                var audio = this,
                    args = arguments,
                    promise = play.apply(audio, args);
                if (promise !== undefined) {
                    promise.catch(_ => {
                        // Autoplay was prevented. This is optional, but add a button to start playing.
                        var el = document.createElement("button");
                        el.innerHTML = "Play";
                        el.addEventListener("click", function() {
                            play.apply(audio, args);
                        });
                        this.parentNode.insertBefore(el, this.nextSibling)
                    });
                }
            };
        })(Audio.prototype.play);
        var x = document.getElementById("orderAudio");
        x.play();
        $("#reached_location_new_order #orderNo").html(order_number)
        $("#reached_location_new_order").modal('show');
    }
    
    function get_latest_order_socket(order_number){
        Audio.prototype.play = (function(play) {
            return function() {
                var audio = this,
                    args = arguments,
                    promise = play.apply(audio, args);
                if (promise !== undefined) {
                    promise.catch(_ => {
                        // Autoplay was prevented. This is optional, but add a button to start playing.
                        var el = document.createElement("button");
                        el.innerHTML = "Play";
                        el.addEventListener("click", function() {
                            play.apply(audio, args);
                        });
                        this.parentNode.insertBefore(el, this.nextSibling)
                    });
                }
            };
        })(Audio.prototype.play);
        var x = document.getElementById("orderAudio");
        x.play();
        $.ajax({
            url: "{{ route('orders.filter') }}",
            type: "POST",
            dataType: "JSON",
            data: {
                filter_order_status: "pending_orders",
                search_keyword: order_number,
                className : 'col-xl-12'
            },
            success: function(response) {
                if (response.status == 'Success') {
                    if (response.data.html != '') {
                        $("#received_new_orders").find(".modal-body").html('');
                        let latest_order_template = _.template($('#latest_order_template').html());
                        $("#received_new_orders").find(".modal-body").append(response.data.html);
                        if(response.data.auto_accept_status == 0){
                        	$("#received_new_orders").modal('show');
                        }
                    }
                }
            },
            error: function(data) {

            },
        });
    }
</script>
@if(@Session::has('preferences') && !empty(@Session::get('preferences')['fcm_api_key']))
<script>
    var firebaseCredentials = {!!json_encode(Session::get('preferences')) !!};
    //console.log(firebaseCredentials);
    var firebaseConfig = {
        apiKey: firebaseCredentials.fcm_api_key,
        authDomain: firebaseCredentials.fcm_auth_domain,
        projectId: firebaseCredentials.fcm_project_id,
        storageBucket: firebaseCredentials.fcm_storage_bucket,
        messagingSenderId: firebaseCredentials.fcm_messaging_sender_id,
        appId: firebaseCredentials.fcm_app_id,
        measurementId: firebaseCredentials.fcm_measurement_id
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);

    const messaging = firebase.messaging();
    function initFirebaseMessagingRegistration() {

        messaging.requestPermission().then(function() {
            return messaging.getToken()
        }).then(function(token) {
            
            $.ajax({
                url: "{{ route('client.save_fcm') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    fcm_token: token,
                },
                success: function(response) {

                },
            });
            console.log(token);
            console.log("token");

        }).catch(function(err) {
            console.log(`Token Error :: ${err}`);
        });
         @if(empty(Session::get('current_fcm_token')))
        @endif
    }

    initFirebaseMessagingRegistration();
    messaging.onMessage( async function(payload) {

        if (!("Notification" in window)) {
            console.log("This browser does not support system notifications.");
        }
        else if (Notification.permission === "granted") {
            console.log(payload);
            console.log("payload");
            if(payload && payload.data && payload.data.data){
                if(payload.data.type && payload.data.type=="order_created"){
                    var payload_data = JSON.parse(payload.data.data);
                    console.log('firepase msg order number');
                    console.log(payload_data.order_number);
                    setTimeout(()=>{
                         get_latest_order_socket(payload_data.order_number);
                    },3000);
                   
                }
                else if(payload.data.type=="order_cancellation_request"){
                    var notificationTitle = payload.notification.title;
                    var notificationOptions = {
                        body: payload.notification.body,
                        icon: payload.notification.icon
                    };
                    await fireNotification(notificationOptions,notificationTitle);
                    push_notification.onclick = function(event) {
                        event.preventDefault();
                        window.open(payload.notification.click_action, "_blank");
                        push_notification.close();
                    };
                }else if(payload.data.type=="reached_location"){
                    var payload_data = JSON.parse(payload.data.data);
                    get_reached_vendor_location_socket(payload_data.order_number,payload.data.type);
                } else {
                    //alert();
                    //setTimeout(()=>{
                        var notificationTitle = payload.notification.title;
                        var notificationOptions = {
                            body: payload.notification.body,
                            icon: payload.notification.icon
                        };
                        //console.log(notificationOptions);
                        await fireNotification(notificationOptions,notificationTitle);
                
                  
                     
                    //},2000);
                   
                }
            } 
        }
    });
    async function fireNotification(notificationOptions,notificationTitle){
        await new Notification(
            notificationTitle,
            notificationOptions
        );
    }
</script>
@endif
@endif
<script>
    @if(Auth::user())
    $(document).on("change",".admin_panel_theme", function(){
        if($(this).prop('checked')){
            var theme_admin = 'dark';
        }else{
            var theme_admin = 'light';
        }
        $.ajax({
            url: "{{route('configure.update', Auth::user()->code)}}",
            type: "POST",
            data: {
                theme_admin: theme_admin,
                "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
                location.reload();
            },
        });

    });
    @endif
    @if(\Request::route()->getName() != 'order.index')
    $(document).on("click", ".update-status-ar", function() {
        Swal.fire({
            title: "{{__('Are you Sure?')}}",
            // icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ok',
        }).then((result) => {

            if (result.value) {
                let that = $(this);
                var count = that.data("count");
                var full_div = that.data("full_div");
                var single_div = that.data("single_div");
                var status_option_id = that.data("status_option_id");
                var status_option_id_next = status_option_id + 1;
                var order_vendor_id = that.data("order_vendor_id");
                var order_id = that.data("order_id");
                var vendor_id = that.data("vendor_id");
                var count = that.data("count");

                $.ajax({
                    url: "{{ route('order.changeStatus') }}",
                    type: "POST",
                    data: {
                        order_id: order_id,
                        vendor_id: vendor_id,
                        "_token": "{{ csrf_token() }}",
                        status_option_id: status_option_id,
                        order_vendor_id: order_vendor_id,
                    },
                    success: function(response) {
                        if(response.status=='error'){
                            if (count == 0) {
                                $(full_div).slideUp(1000, function() {
                                    $(this).remove();
                                });

                            } else {
                                $(single_div).slideUp(1000, function() {
                                    $(this).remove();
                                });

                            }
                            $.NotificationApp.send('{{__("Error")}}', response.message, "top-right", "#ff0808", "error");
                            return 0;
                        }
                        if (status_option_id == 4 || status_option_id == 5) {
                            if (status_option_id == 4)
                                var next_status = '{{__("Out For Delivery")}}';
                            else
                                var next_status = '{{__("Delivered")}}';
                            that.replaceWith("<button class='update-status-ar btn-warning' data-full_div='" + full_div + "' data-single_div='" + single_div + "'  data-count='" + count + "'  data-order_id='" + order_id + "'  data-vendor_id='" + vendor_id + "'  data-status_option_id='" + status_option_id_next + "' data-order_vendor_id=" + order_vendor_id + ">" + next_status + "</button>");
                            return false;
                        } else {
                            $(that).parents(single_div).slideUp(1000, function() {
                                $(this).remove();
                            });
                            setTimeout(function() {
                                if ($("#received_new_orders").find(".update-status-ar").length == 0) {
                                    $("#received_new_orders").modal('hide');
                                }
                            }, 2000);
                        }

                        if (status_option_id == 2)
                            $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        // location.reload();
                        if (typeof init === 'function') {
                            init("pending_orders", "{{ route('orders.filter') }}", '', false);
                        }
                    },
                });
            }
        });
    });
    @endif
</script>
@endif


@yield('script-bottom')
{{-- <script  src="{{asset('assets/js/chat/chatNotifications.js')}}"></script> --}}
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-5LPF1QP3Y3"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'G-5LPF1QP3Y3');
@if(isset($analytics['gtag_id']))
    gtag('config', "{{$analytics['gtag_id'] ?? ''}}");
@endif   

$("#change_password").on("hidden.bs.modal", function(){
    $('.pwd-msg').html("");
    $('#change_password_form').trigger("reset");
});


$("#change_password_form").submit(function(e){
   // return false;
    e.preventDefault();
    $('.pwd-msg').html("");
    $.ajax({
            url:"{{route('cl.password.update')}}",
            type:'POST',
            data:$(this).serialize(),
            dataType:'JSON',
            success:function(result){
                if(result.type=="error")
                {
                    var pwderror = '<span class="text-danger" role="alert"><strong>'+result.message+'</strong></span>';
                    $('.pwd-msg').html(pwderror);                    
                }else{
                    var pwderror = '<span class="text-success" role="alert"><strong>'+result.message+'</strong></span>';
                    $('.pwd-msg').html(pwderror);
                    $('#change_password_form').trigger("reset");
                    setTimeout(function () {                        
                        $('#change_password').modal('toggle');
                    }, 1000);
                }
            }

    });
});
</script> 
@if(isset($analytics['fpixel_id']))
    <!-- Meta Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', "{{$analytics['fpixel_id']}}");
        fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{$analytics['fpixel_id']}}&ev=PageView&noscript=1"/></noscript>
    <!-- End Meta Pixel Code -->
@endif
<script>
    $(".menu_cta").click(function(){
        $("html").toggleClass("active_slidebar");
    });
</script> 