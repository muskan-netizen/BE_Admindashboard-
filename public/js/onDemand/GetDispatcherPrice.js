/**
 * Store all portal localstorage
 * @Author  Mr Harbans singh
 */

$(function(){
    
    OrderSessionStorage.removeStorageSingle('variant_id');
    OrderSessionStorage.removeStorageSingle('dispatcherAgent');
    OrderSessionStorage.removeStorageSingle('add_to_cart_url');
    OrderSessionStorage.removeStorageSingle('vendor_id');
    OrderSessionStorage.removeStorageSingle('product_id');
    OrderSessionStorage.removeStorageSingle('this');
    OrderSessionStorage.removeStorageSingle('onDemandBookingdate');
})



$(document).on('click','.view_on_demand_price',function(){
    var variant_id = $(this).data('variant_id');
    //  alert(variant_id);
    console.log($(this));
    OrderSessionStorage.setStorageSingle('variant_id',variant_id);
    OrderSessionStorage.setStorageSingle('add_to_cart_url',$(this).data('add_to_cart_url'));
    OrderSessionStorage.setStorageSingle('vendor_id',$(this).data('vendor_id'));
    OrderSessionStorage.setStorageSingle('product_id',$(this).data('product_id'));
    OrderSessionStorage.setStorageSingle('this',JSON.stringify($(this)));
    document.getElementById('driver_product_variant_id').value = variant_id;
    $('#productPriceModel').modal('show');
    $(`#listofdrivers`).html('');
   console.log( JSON.parse(OrderSessionStorage.getStorage('this')));
})

$(document).on('click','#search_Driver_fee',function(e){
    e.preventDefault();
    var variant_id = document.getElementById('driver_product_variant_id').value
    var onDemandBookingdate = document.getElementById('onDemandBookingdate').value
    OrderSessionStorage.setStorageSingle('onDemandBookingdate',onDemandBookingdate);
    getDiverPrice(variant_id , onDemandBookingdate)
})
async function getDiverPrice(variant_id , onDemandBookingdate){
    var formData ={
        "variant_id" : variant_id,
        "onDemandBookingdate" : onDemandBookingdate
    }
  
     axios.post(`/get_price_from_dispatcher`, formData)
        .then(async response => {
         console.log(response.data);
            if(response.data.status == "Success"){
                console.log('success');
              var dispatch_agent = response.data.data;
               OrderSessionStorage.setStorageSingle('dispatcherAgent',JSON.stringify(dispatch_agent));
               await renderAgent();
            } else{
                Swal.fire({
                    icon: 'error',
                    title:_language.getLanString('Oops...'),
                    text: _language.getLanString('Something went wrong, try again later!'),
                })
            }
        })
        .catch(e => {
            console.log(e);
            Swal.fire({
                icon: 'error',
                title:_language.getLanString('Oops...'),
                text: _language.getLanString('Something went wrong, try again later!'),
            })
        })  

}
//filter 0 =  rendom ,1= by price , 2
async function renderAgent(filter=0){
    var html = '';
    var AgentData= JSON.parse(OrderSessionStorage.getStorage('dispatcherAgent'));
    var product_variant_id = OrderSessionStorage.getStorage('variant_id');
   
    if(AgentData.length > 0){
        AgentData.forEach(function(data,index) {
            var dirvePrice = data?.product_prices[0]?.price || 0;
            let price = NumberFormatHelper.formatPrice(dirvePrice);
                html +=`<div class="card dispatcherAgent" data-agent_id="${data?.id}" data-agent_price="${dirvePrice}" data-product_variant_id=${product_variant_id}>
                   <div class="card-body p-3 bg-light">
                     <div class="d-flex justify-content-between">
                         <div class="userDetails d-flex align-items-center">
                             <div class="userDetailsImage mr-2"> <img class="w-100" src="${data.image_url}" alt="${data.name}" title=""></div>
                             <ul class="userDetailsNameJob p-0 m-0">
                                 <li class="userDetailsName d-block">${data.name}</li>
                                 <li class="userDetailsJobDone  d-block">${_language.getLanString('Jobs Done ')} <b class="text-success">${data?.complete_order_count}</b></li>
                             </ul>
                         </div>
                         <div class="userDetailsRating">
                             <ul class="userDetailsNameJob p-0 m-0 text-right">
                                 <li class="userDetailsRating text-right d-block">  
                                   <label class="rating-star "  >
                                       <i class="fa fa-star${ (data.rating >= 1) ? ' checked' : '-o'}"></i>
                                       <i class="fa fa-star${ (data.rating >= 2) ? ' checked' : '-o'}"></i>
                                       <i class="fa fa-star${ (data.rating >= 3) ? ' checked' : '-o'}"></i>
                                       <i class="fa fa-star${ (data.rating >= 4) ? ' checked' : '-o'}"></i>
                                       <i class="fa fa-star${ (data.rating >= 5) ? ' checked' : '-o'}"></i>
                                   </label>
                                  </li>
                                  <li class="userDetailsJobDone  d-block"><span class="text-right text-success">${currencySymbol +' '+ price } </span> </li>
                                
                             </ul>
                         </div>
                     </div>
                   </div>
                 </div>`;
           });
    }else{
       html +=`<div class="empty_driver_price"><h2>No Results Found</h2>
       <p>We Couldn't Find what you searched.for <br/> Try Searching Again </p></div>`; 
    }

       $(`#listofdrivers`).html(html);
}

$(document).on('click','.dispatcherAgent',function(e){
    e.preventDefault();
   
    var agent_price = $(this).data('agent_price');
    var agent_id = $(this).data('agent_id');
    var ajaxCall = 'ToCancelPrevReq';

    var variant_id      = OrderSessionStorage.getStorage('variant_id');
    var add_to_cart_url = OrderSessionStorage.getStorage('add_to_cart_url');
    var vendor_id       = OrderSessionStorage.getStorage('vendor_id');
    var product_id      = OrderSessionStorage.getStorage('product_id');
    let that            = JSON.parse(OrderSessionStorage.getStorage('this'));
    var dispatcherAgentData ={
        "agent_price"         : agent_price,
        "agent_id"            : agent_id,
        "onDemandBookingdate" : OrderSessionStorage.getStorage('onDemandBookingdate')
    }
    var show_plus_minus = "#show_plus_minus" + product_id;
     Swal.fire({
        title: _language.getLanString('Warning!'),
        text: _language.getLanString('You want to add this price to cart?'),
        icon: 'warning',
      }).then(({value}) => {
        console.log(value);
            if (value === true) {
                console.log(that);
                $(`#listofdrivers`).html('');
                $('#productPriceModel').modal('hide');
                addToCartOnDemand(ajaxCall, vendor_id, product_id, addonids, addonoptids, add_to_cart_url, variant_id, show_plus_minus, that,dispatcherAgentData);
            } 
      });
   
    // console.log('variant_id '+ variant_id + " agent_price "+ agent_price + " agent_id " + agent_id+ " vendor_id " + vendor_id + " add_to_cart_url " + add_to_cart_url+ " product_id " + product_id+ " that " + that);
})