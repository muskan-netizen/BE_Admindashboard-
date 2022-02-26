<script>
    $(document).ready(function() {
        var table;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        setTimeout(function(){$('#approved-requests').trigger('click');}, 200);
        $(document).on('click', '#edit_vendor_modal #update_vendor_modal', function(e) {
            e.preventDefault();
            let myForm = document.getElementById('update_vendor_form');
            let formData = new FormData(myForm);
            var vendor_id = $("#edit_vendor_modal input[name='vendor_id']").val();
            var vendor_update_url =  base_url+'/client/vendor/'+vendor_id;
            $.ajax({
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                url: vendor_update_url,
                headers: {Accept: "application/json"},
                success: function(response) {
                    if (response.status == 'success') {
                        $("#edit_vendor_modal .close").click();
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        setTimeout(function(){location.reload();}, 2500);
                    } else {
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text(response.message);
                    }
                    return response;
                },
                beforeSend: function(){
                    $(".loader_box").show();
                },
                complete: function(){
                    $(".loader_box").hide();
                },
                error: function(response) {
                    if (response.status === 422) {
                        let errors = response.responseJSON.errors;
                        Object.keys(errors).forEach(function(key) {
                            $("#" + key + "Input input").addClass("is-invalid");
                            $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[key][0]);
                            $("#" + key + "Input span.invalid-feedback").show();
                        });
                    } else {
                        $(".show_all_error.invalid-feedback").show();
                        $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                    }
                    return response;
                }
            });
        });
        $(document).on("click",".edit_vendor",function() {
            var vendor_id = $(this).data('vendor_id');
            $.ajax({
                data: '',
                type: "get",
                dataType: 'json',
                url: base_url+"/client/vendor/"+vendor_id+"/edit",
                success: function (data) {
                    $('#edit_vendor_modal').modal('show');
                    $('.selectize-select').selectize();
                    $('#edit_vendor_modal #editVendorBox').html(data.html);
                    dine = document.getElementsByClassName('dine_in');
                    var switchery = new Switchery(dine[0]);
                    take = document.getElementsByClassName('takeaway');
                    var switchery = new Switchery(take[0]);
                    delivery = document.getElementsByClassName('delivery');
                    var switchery = new Switchery(delivery[0]);
                    autocompletesWraps.push('edit');
                    loadMap(autocompletesWraps);
                    $('.dropify').dropify();
                }
            });
        });
        $(document).on("click",".nav-link",function() {
            let rel= $(this).data('rel');
            let status= $(this).data('status');
            initDataTable(rel, status);
        });
        $(document).on("click",".delete-vendor",function() {
            var destroy_url = $(this).data('destroy_url');
            var id = $(this).data('rel');
            Swal.fire({
                title: "Are you sure?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ok',
            }).then((result) => {
                if(result.value)
                {
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: destroy_url,
                        data:{'_method':'DELETE'},
                        success: function(response) {
                            if (response.status == "Success") {
                                $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        });
        function initDataTable(table, status) {
            var dynamic_columns = [
                {data: 'order_number', name: 'order_number', orderable: false, searchable: true,"mRender": function ( data, type, full ) {
                    return "<a href='"+full.order_detail_url+"'>"+data+"</a>";
                }},
                {data: 'vendor', name: 'vendor', orderable: false, searchable: true, "mRender": function ( data, type, full ) {
                    return "<a href='"+full.show_vendor_url+"'>"+data+"</a>";
                }},
                {data: 'reject_reason', name: 'reject_reason', orderable: false, searchable: true},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                // {data: 'offers', name: 'offers', class:'text-center', orderable: false, searchable: false, "mRender":function(data, type, full){
                //     var markup = '';
                //     for (var i = full.offers.length - 1; i >= 0; i--) {
                //         if(full.offers[i]){
                //             markup+="<span class='badge bg-soft-warning text-warning'>"+full.offers[i]+"</span>";
                //         }
                //     }
                //     return markup;
                // }},
                // {data: 'add_category_option', class:'text-center', name: 'add_category_option', orderable: false, searchable: false},
                // {data: 'commission_percent', class:'text-center', name: 'commission_percent', orderable: false, searchable: false},
                // {data: 'products_count', class:'text-center', class:'text-center', name: 'products_count', orderable: false, searchable: false},
                {data: 'updated_by', name: 'updated_by', orderable: false, searchable: false},
                // {data: 'currently_working_orders_count', class:'text-center', name: 'currently_working_orders_count', orderable: false, searchable: false},
            ];
            if(status == 0){
                dynamic_columns.push({data: 'action', class:'text-center', name: 'action', orderable: false, searchable: false});
            }

            $('#'+table).DataTable({
                "destroy": true,
                "scrollX": true,
                "processing": true,
                "serverSide": true,
                "iDisplayLength": 20,
                "dom": '<"toolbar">Bftrip',
                language: {
                    search: "",
                    info: table_info,
                    paginate: { previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>" },
                    searchPlaceholder: search_text
                },
                drawCallback: function () {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                },
                buttons: [],
                ajax: {
                  url: base_url+'/client/cancel-order/requests/filter',
                  complete: function(){
                    $('.vendor-products').removeClass('invisible');
                  },
                  data: function (d) {
                    d.status = status;
                    d.search = $('input[type="search"]').val();
                    // d.date_filter = $('#range-datepicker').val();
                    // d.payment_option = $('#payment_option_select_box option:selected').val();
                    // d.tax_type_filter = $('#tax_type_select_box option:selected').val();
                  }
                },
                columns: dynamic_columns
            });
        }
    });

</script>
