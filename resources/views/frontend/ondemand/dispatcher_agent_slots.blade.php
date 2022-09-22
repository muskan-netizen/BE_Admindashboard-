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
    a.agentInfo.d-block.selected_agent .brand-ing {
    border-color: var(--theme-deafult);
    border-width: 3px;
}
.alCustomHomeServiceRadio::-webkit-scrollbar {height: 4px;}
.alCustomHomeServiceRadio{flex-wrap: nowrap;white-space: nowrap;overflow: auto;}
.alCustomHomeServiceRadio .item{display: inline-block;margin-bottom: 6px;}
a.agentInfo.d-block.selected_agent h6 {
    color: var(--theme-deafult);
}
.agentInfo .brand-ing{transition: 0.5s}
.alCustomHomeServiceAgentRadio{flex-wrap: nowrap;white-space: nowrap;overflow: auto;}
.alCustomHomeServiceAgentRadio .agent_slot{display: inline-block;margin: 0px 10px;}
</style>
<h4 class="mt-4 mb-2"><b>{{ __('What time would you like us to start?') }}</b></h4>
<div class="booking-tim  radio-btns long-radio">   
    <div class="grid-item main radios agent_{{ $cart_product_id }}">
        <div class="alCustomHomeServiceRadio  items">
         @if((isset($dispatch_agents)) && (isset($dispatch_agents['slots'])) && (count($dispatch_agents['slots']) > 0) )
            @foreach ($dispatch_agents['slots'] as $key => $slot)
                <div class="item  {{ ($slot['value'] ==@$schedule_slot) ? 'checked_item': ''  }}">
                    <input type="radio" value='{{ $slot['value'] }}' name='booking_time' {{ ($slot['value'] ==@$schedule_slot) ? 'checked': ''  }} id='time{{$cart_product_id}}{{$slot['value']}}'/>  
                    <label for='time{{$cart_product_id}}{{$slot['value']}}'>
                        <span class="customCheckbox selected-time" aria-hidden="true"  data-show_agent="{{ $show_dispatcher_agent }}" data-selected_agnet_id="{{ @$selected_agent_id  }}" data-agent_ids="{{ $slot['agent_id'] }}" data-value='{{$slot['value']}}' data-cart_product_id='{{$cart_product_id}}' >{{$slot['name']}}</span>
                    </label>
                </div>
            @endforeach
        @else
         <h5>{{ __('No Slot Available!') }}</h5>
        @endif
        </div>
    </div>
           
</div>
<div class="agent_slots{{ $cart_product_id }}" id="avail_slot">
</div>
<P id="message_of_time{{$cart_product_id}}"></P>
@section('js-script')
<script>
    var dispatch_agents = {
        agent: {!!json_encode(($dispatch_agents['agents'] ?? ''))!!}
    } 
   
</script>
<script src="{{ asset('js/onDemand/AgentSlot.js') }}"></script>

@endsection