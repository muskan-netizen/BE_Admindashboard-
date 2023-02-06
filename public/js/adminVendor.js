$(function(){
    var longTermServiceTable = '' ;
    initServiceDataTable();
    $(document).on("click",".addServiceBtn",function() {
        $('#add-service').modal({
            keyboard: false
        });
    });


    $(document).on("change","#service_product_list",function() {
        var product_id = $(this).val();
        if(product_id !='' && product_id !=undefined ){
            setProductVariant(product_id)
        }
    });



    $(document).on('click', '.submitServiceProduct', function(e) {
        e.preventDefault();
        var form = document.getElementById('save_service_form');
        var formData = new FormData(form);
        var url = `/client/long_term_service/store`;
    
        saveServiceData(formData, url);

    }); 
    $(document).on('click', '.edit_service', function(e) {
        e.preventDefault();
        var service_id = $(this).data('service_id');
        GetServiceData(service_id);
    
    });
    $(document).on('click', '.delete_service', function(e) {
        e.preventDefault();
        var service_id = $(this).data('service_id');
        deleteService(service_id);
    
    });

});
async function deleteService(id){
        
    axios.get(`/client/long_term_service/delete/${id}`)
    .then(async response => {
     console.log(response);
        if(response.data.success){
            sweetAlert.success('Success',response.data.message);
        } else{
            sweetAlert.error('',response.data.message);
        }
        setTimeout(() => {
            $('#add-service').modal('hide');
        },1000);
    })
    .catch(e => {
        sweetAlert.error();
    })    

}

function setProductVariant(product_id,selected_variant=''){
    axios.post(`/client/product/getVariant`, {product_id: product_id})
    .then(async response => {
        if(response.data.status == "Success"){
            $('#service_product_variant').selectize()[0].selectize.destroy();
            $("#service_product_variant").find('option').remove();
            $("#service_product_variant").append(response.data.data);
            if(selected_variant!=''){
                var $select = $("#service_product_variant").selectize();
                var selectize = $select[0].selectize;
                selectize.setValue(selected_variant)
            }
        } else{
           
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
}

function GetServiceData(service_id) {

    axios.get(`/client/long_term_service/edit/${service_id}`)
        .then(async response => {
            $('#save_service_form')[0].reset();
            if(response.data.status == "Success"){
                var service = response.data.data;
                $('#add-service input[name=long_term__service_id]').val(service_id);
                $('#add-service .modal-title').html('Edit Service');
                $("#add-service input[name=serviceSku]").val(service.sku);
                $("#add-service input[name=serice_price]").val(service.price);
                $("#add-service input[name=product_quantity]").val(service.product.quantity);
                var $select = $("#service_product_list").selectize();
                var selectize = $select[0].selectize;
                selectize.setValue(service.product.product_id)
                setProductVariant(service.product.product_id)
                var image = service.image.proxy_url+'100/100'+service.image.image_path;
                var html = `<input type="file" id="service_image" name="image" class="dropify form-control" data-default-file="${image}" required />`;
                $('.service_image').html(html);
                $('#add-service .dropify').dropify();
              
                $('#add-service').modal('show');service_product_list
                $.each(service.translations, function( index, value ) {
                    $('#add-service #service_name_'+value.language_id).val(value.name);
                });
                
            }
        })
        .catch(e => {
            sweetAlert.error();
        })  
       
      

}
function saveServiceData(formData, data_uri) {

    axios.post(data_uri,formData )
        .then(async response => {
            console.log(response);
            if(response.data.status == "Success"){
                sweetAlert.success('Success',response.data.message);
            } else{
                sweetAlert.error('',response.data.message);
            }
            $('#save_service_form')[0].reset();
            setTimeout(() => {
                $('#add-service').modal('hide');
                longTermServiceTable.ajax.reload();
            },1000);
            document.getElementById("save_service_form").reset();
        })
        .catch(e => {
          
            if (e.response.status === 422) {
               
                let errors = e.response.data.errors;
                Object.keys(errors).forEach(function(key) {
                    if(key  == 'name.0'){
                        $("#Service_nameInput span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#Service_nameInput span.invalid-feedback").show();
                    }
                    $("#" + key + "Input input").addClass("is-invalid");
                    $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[key][0]);
                    $("#" + key + "Input span.invalid-feedback").show();
                });
            } else {
                $(".show_all_error.invalid-feedback").show();
                $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
            }
            
        })  

}
function setServiceSkuFromName(event,getVal='',setVal='') {
    var n1 = $('#'+getVal).val()
    n1 = n1.replace(/[.*+?^${}()/|[\]\\]+/g, '-');
    var total_sku = sku_start+ n1;
    $('#'+setVal).val( n1);
    $('#'+setVal).val(n1.split(' ').join(''));
    var string =  $('#'+setVal).val();
    var slug = string.toString().trim().toLowerCase().replace(/\s+/g, "-").replace(/[^\w\-]+/g, "").replace(
        /\-\-+/g, "-").replace(/^-+/, "").replace(/-+$/, "");
    $('#'+setVal).val(slug);
}
function initServiceDataTable(){
  
  
    longTermServiceTable =  $('#vendor_longTerm_service_table').DataTable({
        processing: true,
        scrollY: true,
        scrollX: true,
        searching: false,
        destroy: true,
        scrollCollapse: true,   
        responsive: true,
        serverSide: true,
        ordering: false,
        lengthChange: false,

        ajax: `/client/long_term_service/index/${vendor_id}`,
        columns: [  
            { data: 'service_image' },
            { data: 'service_title' },
            { data: 'service_product_title' },
            { data: 'service_product_quantity' },
            { data: 'time_period' },
            { data: 'price' },
            { data: 'action' },
        ],
       
    });
}