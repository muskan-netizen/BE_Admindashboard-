
$(document).on('click','.dispatch_agent',function(){

    var agent_id = $(this).data('agent_id');
    var agentData = dispatch_agents.agent;
    var agent = agentData.find(item => item.id === agent_id);
    console.log(agent);
    initSlots(agent);

})

function initSlots(agent){
    var html=`<div class="grid-item main radios agent_${agent.id}">`;
    html+=`<div class="alCustomHomeServiceRadio items">`;
    if(agent.slotings.length < 0) {
        html+='<span>No available slot found !!</span></div>';
        html+=`</div>`;
        
    } else{
        console.log(cart_product_id);
        agent.slotings.forEach(function(data) {
            // code
            html+=`<div class="item"><input type="radio" value='${data.value}' name='booking_time' id='time${data.value}'/>          
                    <label for='time${data.value}'><span class="customCheckbox selected-time" aria-hidden="true"  data-value='${data.value}' data-cart_product_id='${cart_product_id}'>${data.name}</span></label></div>`;
        });
    }
    html+='</div>';
    $('.agent_slots').html(html);
    initSlideDrag();
}
function initSlideDrag(){
    const slider = document.querySelector('.items');
    let isDown = false;
    let startX;
    let scrollLeft;

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
        console.log(walk);
    });
}
