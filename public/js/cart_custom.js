$(function(){
    
    $(document).on('change', '.vendor_product_schedule_datetime', function () {
        let date = $(this).val();
        let product_id = $(this).data('product_id');
        let cart_product_id = $(this).data('cart_product_id');
        console.log(date);
        console.log(product_id);
        var formData ={
            "cur_date" : date,
            "product_id" : product_id
        }
        axios.post(get_dispatch_slot, formData)
        .then(async response => {
         console.log(response);
         $(`#vendor_schedule_slot_selecter_${cart_product_id}`).html();
            if(response.data.status == "Success"){
                $(`#vendor_schedule_slot_selecter_${cart_product_id}`).html(response.data.html);
            } else{
                Swal.fire({
                    icon: 'error',
                    title: 'Oops',
                    text: response.data.message,
                })
            }
        })
        .catch(e => {
            console.log(e);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong, try again later!',
            })
        }) 
    });
    $(document).on('click', '.vendor_product_schedule_slot', function () {

        let cart_product_id   =  $(this).data('cart_product_id');
       
        const vendor_schedule_slot_selecter = document.getElementById(`vendor_schedule_slot_selecter_${cart_product_id}`);
        let selected_time = vendor_schedule_slot_selecter.value;
        const vendor_schedule_date = document.getElementById(`vendor_schedule_date_${cart_product_id}`);
        let schedule_date = vendor_schedule_date.value;
        // get selected varival
        var option= vendor_schedule_slot_selecter.options[vendor_schedule_slot_selecter.selectedIndex];
        let agent_ids = option.getAttribute("data-show_agent");
     console.log(agent_ids);
        let dispatch_agent_id = '';
        if((agent_ids != undefined && agent_ids !='' )  ){
            dispatch_agent_id ='18';// agent_ids[0] ; 
        }
        var task_type = 'schedule';
        
        var schedule_dt = schedule_date;
        var formData ={
            "task_type" : task_type,
            "schedule_dt" : schedule_dt,
            "cart_product_id" : cart_product_id,
            "dispatch_agent_id" : dispatch_agent_id,
            "schedule_time" : selected_time
        };
        console.log(formData);
        axios.post(update_cart_product_schedule, formData)
        .then(async response => {
         
        })
        .catch(e => {
            console.log(e);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong, try again later!',
            })
        }) 
        // $.ajax({
        //     type: "POST",
        //     dataType: 'json',
        //     url: update_cart_product_schedule,
        //     data: { task_type: task_type, schedule_dt: schedule_dt,schedule_time:selected_time,cart_product_id: cart_product_id,dispatch_agent_id:dispatch_agent_id },
        //     success: function (response) {
        //         if (response.status == "Success") {
        //         }
        //     },
        //     error: function (error) {
        //         var response = $.parseJSON(error.responseText);
        //         success_error_alert('error', response.message, ".cart_response");
        //     }
        // });

    });
})