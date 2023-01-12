$(function(){
    // initSlideDrag();
    // showSelectedAgent()
    // var slotValidater = 2;
    var dispatch_agent = '';
})



$(document).on('click','.view_on_demand_price',function(){
    var variant_id = $(this).data('variant_id');
  //  alert(variant_id);
    document.getElementById('driver_product_variant_id').value = variant_id;
    $('#productPriceModel').modal('show');
    // $('.dispatch_agent').removeClass('selected_agent');
    // $(this).addClass('selected_agent');
    
    // var agent_id = $(this).data('agent_id');
    // var cart_product_id = $(this).data('cart_product_id');
    // console.log(agent_id);
    // console.log(cart_product_id);
    // console.log(update_cart_product_schedule_agnet);
    // var formData ={
    //     "dispatch_agent_id" : agent_id,
    //     "cart_product_id" : cart_product_id
    // }
    // axios.post(update_cart_product_schedule_agnet, formData)
    //     .then(async response => {
    //      console.log(response);
    //         if(response.data.status == "Success"){
    //             // Swal.fire({
    //             //     icon: 'success',
    //             //     title: 'Success',
    //             //     text: response.data.message,
    //             // })
    //         } else{
    //             // Swal.fire({
    //             //     icon: 'error',
    //             //     title: 'Oops',
    //             //     text: response.data.message,
    //             // })
    //         }
    //     })
    //     .catch(e => {
    //         console.log(e);
    //         Swal.fire({
    //             icon: 'error',
    //             title: 'Oops...',
    //             text: 'Something went wrong, try again later!',
    //         })
    //     })  

})
$(document).on('click','#search_Driver_fee',function(e){
    e.preventDefault();
    var variant_id = document.getElementById('driver_product_variant_id').value
    var onDemandBookingdate = document.getElementById('onDemandBookingdate').value
//     console.log(variant_id);
// console.log(onDemandBookingdate);
getDiverPrice(variant_id , onDemandBookingdate)
})
async function getDiverPrice(variant_id , onDemandBookingdate){
    var formData ={
        "variant_id" : variant_id,
        "onDemandBookingdate" : onDemandBookingdate
    }
    var html='';
     axios.post(`/get_price_from_dispatcher`, formData)
        .then(async response => {
         console.log(response.data);
            if(response.data.status == "Success"){
              var dispatch_agent = response.data.data;
              console.log(dispatch_agent);
              dispatch_agent.forEach(function(data,index) {
             console.log(data.rating);
                    html +=`<div class="card">
                    <div class="card-body border-none bg-light">
                      <div class="d-flex justify-content-between">
                          <div class="userDetails d-flex">
                              <div class="userDetailsImage mr-2"> <img class="w-100" src="${data.image_url}" alt="${data.name}" title=""></div>
                              <ul class="userDetailsNameJob p-0 m-0">
                                  <li class="userDetailsName">${data.name}</li>
                                  <li class="userDetailsJobDone">78%</li>
                              </ul>
                          </div>
                          <div class="userDetailsRating">
                              <ul class="userDetailsNameJob p-0 m-0 text-right">
                                  <li class="userDetailsRating text-right">  
                                  <label class="rating-star "  >
                                    <i class="fa fa-star${ (data.rating >= 1) ? ' checked' : '-o'}"></i>
                                    <i class="fa fa-star${ (data.rating >= 2) ? ' checked' : '-o'}"></i>
                                    <i class="fa fa-star${ (data.rating >= 3) ? ' checked' : '-o'}"></i>
                                    <i class="fa fa-star${ (data.rating >= 4) ? ' checked' : '-o'}"></i>
                                    <i class="fa fa-star${ (data.rating >= 5) ? ' checked' : '-o'}"></i>
                                   </label>
                                   </li>
                                 
                              </ul>
                          </div>
                      </div>
                    </div>
                  </div>`;
                   
                
            });

            $(`#listofdrivers`).html(html);
           
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
