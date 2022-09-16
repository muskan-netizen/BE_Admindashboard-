<style>
    #avail_slot .grid-item {
    color: #fff;
    padding: 3.5em 1em;
    font-size: 1em;
    font-weight: 700;
  }

  #avail_slot .main {
    grid-area: main;
    padding: 0;
  }

  #avail_slot .items {
    position: relative;
    width: 100%;
    overflow-x: scroll;
    overflow-y: hidden;
    white-space: nowrap;
    will-change: transform;
    user-select: none;
    cursor: pointer;
  }
  #avail_slot .grid-item .item.active {
    background: #000;
}
#avail_slot .grid-item .item.active label span {
    color: #fff;
}
  #avail_slot  .item {
    display: inline-block;
  }
  div#avail_slot .alCustomHomeServiceRadio.items .item label span.customCheckbox{
    padding: 10px;
    border-radius: 10px;
    color: #000;
    margin: 2px 5px;
    display: inline-block;
    background-color: #f3f3f3;
  }
#avail_slot  .grid-item.main .items .item input[type="radio"]{
    display: none;
}
#avail_slot  .grid-item.main .items .item label {
    color: #000;
    font-size: 13px;
    margin: 0px;
}
div#avail_slot .alCustomHomeServiceRadio input[type="radio"]:checked + label span{background: var(--theme-deafult)!important;color: #fff !important;}

</style>
<h4 class="mt-4 mb-2"><b>What time would you like us to start?</b></h4>
<div class="booking-time  radio-btns long-radio">   
   
    @foreach ($dispatch_agents as $key => $data)
    {{-- @php
    pr($data);
    @endphp --}}
    <div class="agent_slot">
        <div>
            <a class="agentInfo d-block dispatch_agent black-box" data-agent_id="{{ $data['id'] }}" href="javascript:void(0)">
                <div class="brand-ing">
                       <img class="agentImg" src="{{ $data['image_url'] }}" alt="" title="">
                   </div>
                <h6>{{ $data['name'] }}</h6>
               </a>
        </div>
    </div>    
    {{-- <div class="agent_slot_{{ $data['id'] }} d-none">
        @foreach ($data['slotings'] as $key => $slot)

        <div>
            <div class="radios agent_{{ $data['id'] }}">
                <input type="radio" value='{{$slot['value']}} - {{@$time_slots[$key+1]}}' name='booking_time' id='time{{$cart_product_id}}{{$key+1}}'/>          
                <label for='time{{$cart_product_id}}{{$key+1}}'><span class="customCheckbox selected-time" aria-hidden="true"  data-value='{{$slot['value']}}' data-cart_product_id='{{$cart_product_id}}'>{{$slot['name']}} - {{@$time_slots[$key+1]}}</span></label>
                
            </div>
        </div>
        @endforeach
    </div> --}}
    @endforeach
</div>
<div class="agent_slots" id="avail_slot">
</div>
<P id="message_of_time{{$cart_product_id}}"></P>
<script>
    var dispatch_agents = {
        agent: {!!json_encode($dispatch_agents)!!}
    } 
    var cart_product_id = "{{ $cart_product_id }}"
</script>
<script src="{{ asset('js/onDemand/AgentSlot.js') }}"></script>

