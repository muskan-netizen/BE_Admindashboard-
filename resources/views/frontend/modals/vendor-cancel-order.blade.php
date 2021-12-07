<form id="addRejectForm" method="post" enctype="multipart/form-data">
    @csrf
         <p id="error-case" style="color:red;"></p>
        <label style="font-size:medium;">Enter reason for cancel the order.</label>
        <textarea class="reject_reason" data-name="reject_reason" name="reject_reason" id="reject_reason" cols="50" rows="5"></textarea>

   
   
        <button type="button" class="btn btn-info waves-effect waves-light addrejectSubmit">{{ __("Submit") }}</button>
   
</form>

<script>
     $('.addrejectSubmit').on('click', function(e) {
                e.preventDefault();
                var reject_reason = $('#reject_reason').val();
                var order_id = "{{$order_vendor->order_id??0}}";
                var vendor_id = "{{$order_vendor->vendor_id??0}}";
                var order_vendor_id = "{{$order_vendor->id??0}}";
                $.ajax({
                    url: "{{ route('order.cancel.customer') }}",
                    type: "POST",
                    data: {
                        vendor_id: vendor_id,
                        order_id: order_id,
                        reject_reason: reject_reason,
                        "_token": "{{ csrf_token() }}",
                        order_vendor_id: order_vendor_id,
                    },

                    success: function(response) {
                        if (response.status == 'success') {
                            // $(".modal .close").click();
                            location.reload();
                        } else if (response.status == 'error') {
                            $('#error-case').empty();
                            $('#error-case').append(response.message);
                        }
                    
                    },
                    error: function(response) {
                        if (response.status == 'error') {
                            $('#error-case').empty();
                            $('#error-case').append(response.message);
                        }
                    }

                });


            });
    </script>