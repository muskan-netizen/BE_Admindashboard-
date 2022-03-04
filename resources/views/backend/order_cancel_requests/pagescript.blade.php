<script>
    $(document).ready(function() {
        var table;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });
        setTimeout(function(){$('#approved-requests').trigger('click');}, 200);
        
        $(document).on("click",".nav-link",function() {
            let rel= $(this).data('rel');
            let status= $(this).data('status');
            initDataTable(rel, status);
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
                    d.search = $('.dataTables_filter input[type="search"]').val();
                    // d.date_filter = $('#range-datepicker').val();
                    // d.payment_option = $('#payment_option_select_box option:selected').val();
                    // d.tax_type_filter = $('#tax_type_select_box option:selected').val();
                  }
                },
                columns: dynamic_columns
            });
        }

        $(document).on('click', '.complete_request_btn', function(e) {
            let id = $(this).attr('data-id');
            let status = $(this).attr('data-status');
            let title = $(this).attr('title');
            Swal.fire({
                title: "Are you sure?",
                text: "You really want to "+ title +" this request?",
                icon: 'warning',
                iconColor: '{{getClientPreferenceDetail()->web_color}}',
                showCancelButton: true,
                confirmButtonText: 'Yes, '+ title + ' it!',
                confirmButtonColor: '{{getClientPreferenceDetail()->web_color}}'
            }).then((result) => {
                if(result.value)
                {
                    $.ajax({
                        type: "POST",
                        data: {id: id, status: status},
                        url: cancel_request_update_url,
                        headers: {Accept: "application/json"},
                        success: function(response) {
                            if (response.status == 'Success') {
                                $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                                setTimeout(function(){location.reload();}, 2500);
                            } else {
                                Swal.fire({
                                    text: response.message,
                                    icon : "error",
                                    button: "OK",
                                });
                                return false;
                            }
                        },
                        beforeSend: function(){
                            $(".loader_box").show();
                        },
                        complete: function(){
                            $(".loader_box").hide();
                        },
                        error: function(response) {
                            let error = response.responseJSON;
                            Swal.fire({
                                text: error.message,
                                icon : "error",
                                button: "OK",
                            });
                            return false;
                        }
                    });
                }
            });
        });
    });

</script>
