<!-- bundle -->
<!-- Vendor js -->
<?php
if (Session::has('toaster')) {
    $toast = Session::get('toaster');
    echo '<script>
            $(document).ready(function(){
                $.NotificationApp.send("' . $toast["title"] . '", "' . $toast["body"] . '", "top-right", "' . $toast["color"] . '", "' . $toast["type"] . '");
            });
        </script>';
}
?>


<script src="{{asset('assets/libs/selectize/selectize.min.js')}}"></script>
<script src="{{asset('assets/libs/mohithg-switchery/mohithg-switchery.min.js')}}"></script>
<script src="{{asset('assets/libs/multiselect/multiselect.min.js')}}"></script>
<script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js')}}"></script>
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
<script>
    let stripe_publishable_key = "{{ $stripe_publishable_key }}";
    let is_hyperlocal = 0;
    @if(Session::has('preferences'))
    @if((isset(Session::get('preferences')['is_hyperlocal'])) && (Session::get('preferences')['is_hyperlocal'] == 1))
    is_hyperlocal = 1;
    @endif;
    @endif;
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

@yield('script-bottom')