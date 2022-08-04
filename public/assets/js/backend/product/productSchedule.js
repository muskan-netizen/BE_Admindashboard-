$(function(){
    $(document).on('click', '.getScheduledTable', function() {
        var psku = $('#sku').val();
        var pid = $(this).attr('data-product_id');
        var vid = $(this).attr('data-varient_id');
        var title = $(this).attr('data-variant_title');
    
            $("#scheduleTable").dataTable().fnDestroy()
            $('#scheduleTable').DataTable({
                processing: true,
                scrollY: '200px',
                scrollCollapse: true,   
                responsive: true,
                ajax: '/client/getScheduleTableData',
                columns: [
                    { data: 'name' },
                    { data: 'hr.position' },
                    { data: 'hr.start_date' },
                    { data: 'hr.start_date' },
                    // { data: 'hr.salary' },
                ],
            });
            $("#blockTimeTable").dataTable().fnDestroy()
            $('#blockTimeTable').DataTable({
                processing: true,
                scrollY: '200px',
                responsive: true,
                scrollCollapse: true,
                ajax: '/client/getScheduleTableData',
                columns: [
                    { data: 'name' },
                    { data: 'hr.start_date' },
                    { data: 'hr.position' },
                    
                    // { data: 'hr.salary' },
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: 'Add Manual Time',
                        attr: {id: 'add_manual_time' },
                        action: function ( e, dt, node, config ) {
                            //alert( 'Button activated' );
                            add_manual_block_time(pid,vid,title);
                        }
                    }
                ]
            });
            $('.sku-name').html(`(${title})`);
            $('#scheduleTablePopup').modal('show'); 
            
        
    });

    function add_manual_block_time(product_id,varient_id,product_title){
        console.log(product_id);
        console.log(varient_id);
        console.log(product_title);
        $('#addBlockTime').modal('show'); 
    } 
})
