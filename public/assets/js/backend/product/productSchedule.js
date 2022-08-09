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

    function add_manual_block_time(product_id,variant_id,product_title){
        console.log(product_id);
        console.log(variant_id);
        console.log(product_title);
        Swal.fire({
            title: 'Add Manual Time',
            html: `<div class="addManualTime">
                        <div class="addManualTimeGroup" style="text-align:left;">
                            <label class="text-left">Start/End Date Time</label>    
                            <input id="blocktime" class="form-control" autofocus>
                        </div>
                        <div class="addManualTimeGroup mt-2" style="text-align:left;">
                            <label class="text-left">Memo</label>
                            <textarea style="height:100px" type="text" id="memo" class="swal2-input m-0" placeholder="Memo"></textarea>
                        </div>
                    </div>`,
            confirmButtonText: 'Submit',
            focusConfirm: false,
            preConfirm: () => {
              const memo = Swal.getPopup().querySelector('#memo').value
              const blocktime = Swal.getPopup().querySelector('#blocktime').value
              if (!memo || !blocktime) {
                Swal.showValidationMessage(`All feilds are required!!`)
              }
              return { blocktime: blocktime, memo: memo }
            },onOpen: function() {
                $(function() {
                    $('#blocktime').daterangepicker({
                      timePicker: true,
                      startDate: moment().startOf('hour'),
                      endDate: moment().startOf('hour').add(24, 'hour'),
                      minDate:new Date(),
                      locale: {
                        format: 'M/DD/YY hh:mm A'
                      }
                    });
                  });
            }
          }).then(async (result) => {
            var formData = {
              blocktime:result.value.blocktime,
              memo:result.value.memo,
              variant_id:variant_id,
              product_id:product_id,
              booking_slot:$('#blocktime').val()
            }
            await add_blocked_time(formData)
            // Swal.fire(`
            // blocktime: ${result.value.blocktime}
            //   memo: ${result.value.memo}
            // `.trim())
          })
    } 

    async function add_blocked_time(formData){
        console.log(formData);
        axios.post(`/client/booking/addBlockSlot`, formData)
        .then(async response => {
         
            
        })
        .catch(e => {
            Swal.fire(
                'Something went wrong, try again later!',                                    
                'error'
            )
        })    
    } 
    
})
