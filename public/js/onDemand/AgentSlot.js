
$(document).on('click','.dispatch_agent',function(){

    var agent_id = $(this).data('agent_id');
    var cart_product_id = $(this).data('cart_product_id');
    var agentData = dispatch_agents.agent;
    var agent = agentData.find(item => item.id === agent_id);
    console.log(agent);
   // initSlots(agent,agent_id,cart_product_id);

})
//document.querySelectorAll('.checked_item').focus();
function initSlots(agent,agent_id,cart_product_id){
    var html=`<div class="grid-item main radios agent_${agent.id}">`;
    html+=`<div class="alCustomHomeServiceRadio items">`;
    if(agent.slotings.length < 0) {
        html+='<span>No available slot found !!</span></div>';
        html+=`</div>`;
        
    } else{
        console.log(cart_product_id);
        agent.slotings.forEach(function(data) {
            // code
            html+=`<div class="item"><input type="radio" value='${data.value}' name='booking_time' id='time${data.value}_${cart_product_id}'/>          
                    <label for='time${data.value}_${cart_product_id}'><span class="customCheckbox selected-time" aria-hidden="true" data-agent_id='${agent_id}'  data-value='${data.value}' data-cart_product_id='${cart_product_id}'>${data.name}</span></label></div>`;
        });
    }
    html+='</div>';
    $(`.agent_slots${cart_product_id}`).html(html);
    initSlideDrag();
}
initSlideDrag();
function initSlideDrag(){
    const slider = document.querySelector('.items');
    let isDown = false;
    let startX;
    let scrollLeft;
    slider.scrollTo(slider.scrollLeft + 1, 0);
    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('active');
    });
    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('active');
    });
    slider.addEventListener('mousemove', (e) => {
        if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 3; //scroll-fast
            slider.scrollLeft = scrollLeft - walk;
    });
}

async  function showDispatchDriver(driver_ids,cart_product_id){
    var driverIdArray = driver_ids.split(",");
    var agentData = dispatch_agents.agent;
    if(driverIdArray.includes(driverIdArray[0]))
    {
        console.log('its working' );
    }
   console.log(driverIdArray);
   console.log(driverIdArray.includes(driverIdArray[0]));
   var html=`<div class="grid-item main alCustomHomeServiceAgentRadio d-flex justify-content-center radios agentS_${cart_product_id}">`;
    agentData.forEach(function(data,index) {
        if(driverIdArray.includes(String(data.id))){
            html +=`<div class="agent_slot">
            <div>
                <a class="agentInfo d-block dispatch_agent ${(index==0)? 'selected_agent': ''} black-box" data-agent_id="${data.id}" href="javascript:void(0)">
                    <div class="brand-ing">
                        <img class="agentImg" src="${data.image_url}" alt="${data.name}" title="">
                    </div>
                    <h6>${data.name}</h6>
                </a>
            </div>
         </div>`
        }
        
    });
   
    html +=`</div>`;
    $(`.agent_slots${cart_product_id}`).html(html);
}
