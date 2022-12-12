$(document).on("click", ".order_edit_button", function() {
    var orderid = $(this).data('order_id')
    Swal.fire({
        title: confirm_edit_order_title,
        text: confirm_edit_order_desc,
        showCancelButton: true,
        confirmButtonText: 'Ok',
    }).then((result) => {
        if(result.value)
        {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: edit_order_by_user_url,
                data: { orderid: orderid},
                success: function(response) {
                    if (response.status == "Success"){
                        success_error_alert('success', response.message, ".order_edit_button");
                    }else{
                        alert(response.message);
                    }
                },
                error: function(error) {
                    alert(response.message);
                }
            });
        }else{
            alert("Not edit");
            return false;
        }
    });
});