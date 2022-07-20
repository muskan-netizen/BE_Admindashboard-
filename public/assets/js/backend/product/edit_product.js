$(document).ready(function(){
    $("#range-datepicker").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: false
    });
    $('#is_fix_check_in_time').change(function() {
        var val = $(this).prop('checked');
        if (val == true) {
           // $('.check_in_time').show();
            $('.check_in_time').removeClass('d-none');
        } else {
            $('.check_in_time').addClass('d-none');
            //$('.check_in_time').hide();
        }
    });
})


