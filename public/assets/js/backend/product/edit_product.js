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
    $(document).on('click', '.addExistRow', function() {
        
        var product_sku = $('#sku').val();
        var pid = $(this).attr('data-product_id');
        var vid = $(this).attr('data-varient_id');
        var variant_ids = [];
        var variant_name = $("input[name='variant_titles[]']").val();;
        var exist = [];
        var $thisRow = $(this);
        $(".product_varient_ids").each(function() {
            var $this = $(this);
            variant_ids.push($this.attr('data-varient_id'));
        });
        $("#exist_variant_div .exist_sets").each(function() {
            exist.push($(this).val());
        });

        axios.post(`/client/rentalVariantRow`, {
            sku:product_sku,   
            existing:exist,
            variant_ids:variant_ids,
            variant_name:variant_name,
            pid:pid,
            vid:vid,
        })
        .then(async response => {
            $($thisRow).hide();
             console.log(response);
             if(response.data.htmlData != undefined) {
               $('.product_variant_table').append(response.data.htmlData);
             }
             
        })
        .catch(e => {
            Swal.fire(
                'Something went wrong, try again later!',                                    
                'error'
            )
        })    
    });


    
})




function isNumberKeyMax(evt) {

    if(((evt.target.value > 59)  || (evt.target.value < 0))){
        $(evt.target).val(0);
    }
    return true;
}