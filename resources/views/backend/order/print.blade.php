<div class="al_print_area" style="background-color: #fff;padding: 10px" id="al_print_area">
    <table class="table table-borderless" cellspacing="0" border="0" width="100%" cellspacing="0">
        <tr>
            <td>
                <div class="al_print_header" style="width: 100%;">
                    <h5 style="color: #000;margin: 0px;font-family: Lato,sans-serif;">{{ $vendor_data->name }}</h5>
                        <ul style="padding: 0; margin: 0;">
                            @if($order->luxury_option_name != '')
                                <li style="display: inline-block;vertical-align: middle;margin-right: 5px"><span style="font-family: Lato,sans-serif; background-color: #05C3DF;color: #fff;border-radius: 5px;font-size: 12px;padding: 2px 7px">Delivery</span></li>
                            @endif
                            <li style="display: inline-block;vertical-align: middle;"><span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("Items from Order") }} #{{$order->order_number}}</span></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr>
                <td height="15"></td>
            </tr>

            <tr>
                <td>
                    <table  width="100%" cellpadding="0" border="0" cellspacing="0" class="table table-border table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th align="left">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{ __("Product Name") }}</p>
                                </th>
                                <th align="left">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{ __("Product") }}</p>
                                </th>
                                <th align="left">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{ __("Quantity") }}</p>
                                </th>
                                <th align="left">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{ __("Price") }}</p>
                                </th>
                                <th align="left">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{ __("Total") }}</p>
                                </th>
                            </tr>
                        </thead>
                        @foreach($order->vendors as $vendor)
                        <tbody>
                            @php
                                $sub_total = 0;
                                $taxable_amount = 0;
                            @endphp
                            @foreach($vendor->products as $product)
                            @if($product->order_id == $order->id)
                                @php
                                    $taxable_amount = $vendor->taxable_amount;
                                    $vendor_service_fee = $vendor->service_fee_percentage_amount;
                                    $sub_total += $product->total_amount;
                                @endphp
                            <tr>
                                <td scope="row">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px"><b style="font-size: 14px;font-family: Lato,sans-serif;">{{$product->product_name}}</b></p>
                                    <p class="p-0 m-0">
                                        @if(isset($product->scheduled_date_time)) {{dateTimeInUserTimeZone($product->scheduled_date_time, $timezone)}} @endif
                                    </p>
                                    @foreach($product->prescription as $pres)
                                        <br><a target="_blank" href="{{ ($pres) ? @$pres->prescription['proxy_url'].'74/100'.@$pres->prescription['image_path'] : ''}}">{{($product->prescription) ? 'Prescription' : ''}}</a>
                                    @endforeach
                                    <p class="p-0 m-0">{{ substr($product->product_variant_sets, 0, -2) }}</p>
                                    @if($product->addon && count($product->addon))
                                        <hr class="my-2">
                                        <h6 class="m-0 pl-0"><b>{{__('Add Ons')}}</b></h6>
                                        @foreach($product->addon as $addon)
                                            <p class="p-0 m-0">{{ $addon->option->translation_title }}</p>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">
                                    @if($product->image_path)
                                        <img style="height: 30px;" src="{{@$product->image_path['proxy_url'].'32/32'.@$product->image_path['image_path']}}" >
                                    @else 
                                        @php $image_path = getDefaultImagePath(); @endphp
                                        <img style="height: 30px;" src="{{$image_path['proxy_url'].'32/32'.$image_path['image_path']}}" >
                                    @endif
                                    </p>
                                </td>
                                <td>
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{ $product->quantity }}</p>
                                </td>
                                <td>
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">
                                        {{$clientCurrency->currency->symbol}}@money($product->price)
                                        @if($product->addon->isNotEmpty())
                                        <hr class="my-2">
                                        @foreach($product->addon as $addon)
                                            <p class="p-0 m-0">{{$clientCurrency->currency->symbol}}{{ $addon->option->price_in_cart }}</p>
                                        @endforeach
                                        @endif
                                    </p>
                                </td>
                                <td>
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{$clientCurrency->currency->symbol}}@money($product->total_amount)</p>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            <tr>
                                <td scope="row" colspan="4">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px"><b style="font-size: 14px;font-family: Lato,sans-serif;">{{__('Delivery Fee')}} :</b></p>
                                </td>
                                <td><p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{$clientCurrency->currency->symbol}}@money($vendor->delivery_fee)</p></td>
                            </tr>
                            <tr>
                                <td scope="row" colspan="4">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px"><b style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("Sub Total") }} :</b></p>
                                </td>
                                <td><p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{$clientCurrency->currency->symbol}}@money($sub_total)</p></td>
                            </tr>
                            <tr>
                                <td scope="row" colspan="4">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px"><b style="font-size: 14px;font-family: Lato,sans-serif;">{{__('Total Discount')}} :</b></p>
                                </td>
                                <td><p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{$clientCurrency->currency->symbol}}@money($vendor->discount_amount)</p></td>
                            </tr>
                            <tr>
                                <td scope="row" colspan="4">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px"><b style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("Estimated Tax") }} :</b></p>
                                </td>
                                <td><p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{$clientCurrency->currency->symbol}}@money($taxable_amount)</p></td>
                            </tr>
                            @if($vendor_service_fee > 0)
                            <tr>
                                <td scope="row" colspan="4">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px"><b style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("Service Fee") }} :</b></p>
                                </td>
                                <td><p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px;">{{$clientCurrency->currency->symbol}}@money($vendor_service_fee)</p></td>
                            </tr>
                            @endif
                            <!-- <tr>
                                <td scope="row" colspan="4">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px"><b style="font-size: 14px;font-family: Lato,sans-serif; width:200px;">{{$client_head->name}} {{ __("Revenue") }} :</b></p>
                                </td>
                                <td><p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{$clientCurrency->currency->symbol}}@money($revenue)</p></td>
                            </tr>
                            <tr>
                                <td scope="row" colspan="4">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px"><b style="font-size: 14px;font-family: Lato,sans-serif; width:200px;">{{ __("Store Earning") }} :</b></p>
                                </td>
                                <td><p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{$clientCurrency->currency->symbol}}@money($vendor->payable_amount * $clientCurrency->doller_compare - $revenue)</p></td>
                            </tr> -->
                            <tr>
                                <td scope="row" colspan="4">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px"><b style="font-size: 14px;font-family: Lato,sans-serif; width:200px;">{{ __("Reject Reason") }} :</b></p>
                                </td>
                                <td><p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{$vendor->reject_reason}}</p></td>
                            </tr>
                            <tr>
                                <td scope="row" colspan="4">
                                    <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px"><b style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("Total") }} :</b></p>
                                </td>
                                <td><p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 10px">{{$clientCurrency->currency->symbol}}@money($vendor->payable_amount * $clientCurrency->doller_compare)</p></td>
                            </tr>
                        </tbody>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr><td height="25"></td></tr>
            <tr>
                <td>
                    <table width="100%">
                        <td align="left" valign="top">
                            @if($order->address)
                            <table>
                                <thead>
                                    <tr>
                                        <th align="left"><p style="font-size: 18px;font-family: Lato,sans-serif;margin: 0;padding: 5px"> {{ __("Delivery Information") }}</p> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                                <b style="font-size: 14px;font-family: Lato,sans-serif;">{{$order->user->name}}</b>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                                <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("Email") }} :</span> {{ $order->user->email ? $order->user->email : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __('Phone')}} :</span> {{'+'.$order->user->dial_code.$order->user->phone_number}}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("Address") }} :</span> {{ $order->address->house_number ? $order->address->house_number."," : ''}} {{ $order->address ? $order->address->address : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    @if(isset($order->address) && !empty($order->address->street))
                                     <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{__('Street')}} :</span> {{ $order->address ? $order->address->street : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{__('City')}} :</span> {{ $order->address ? $order->address->city : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("State") }} :</span> {{ $order->address ? $order->address->state : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("Zip Code") }} :</span> {{ $order->address ? $order->address->pincode : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            @elseif( ($order->luxury_option_id == 2) || ($order->luxury_option_id == 3) )
                            <table>
                                <thead>
                                    <tr>
                                        <th align="left"><p style="font-size: 18px;font-family: Lato,sans-serif;margin: 0;padding: 5px"> {{ __("User Information") }}</p> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                                <b style="font-size: 14px;font-family: Lato,sans-serif;">{{$order->user->name}}</b>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                                <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("Address") }} :</span> {{ $order->user->address->first() ? $order->user->address->first()->address : __('Not Available')}}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("Mobile") }} :</span> {{$order->user->phone_number ? $order->user->phone_number : __('Not Available')}}
                                            </p>
                                        </td>
                                    </tr>
                                    @if(isset($order->address) && !empty($order->address->street))
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{__('Street')}} :</span> {{ $order->address ? $order->address->street : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{__('City')}} :</span> {{ $order->address ? $order->address->city : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("State") }} :</span> {{ $order->address ? $order->address->state : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __("Zip Code") }} :</span> {{ $order->address ? $order->address->pincode : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            @endif
                            
                        </td>
                        <td align="left" valign="top">
                            <table>
                                <thead>
                                    <tr>
                                        <th align="left"><p style="font-size: 18px;font-family: Lato,sans-serif;margin: 0;padding: 5px"> {{ __('Payment Information') }}</p> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __('Payment By') }} :</span> {{ $order->paymentOption  ? $order->paymentOption->title : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    @if($order->payment)
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __('Transaction Id') }} :</span> {{ $order->payment  ? $order->payment->transaction_id : ''}}
                                            </p>
                                        </td>
                                    </tr>
                                     @endif
                                    <tr>
                                        <td height="20"></td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                            <table>
                                <thead>
                                    <tr>
                                        <th align="left"><p style="font-size: 18px;font-family: Lato,sans-serif;margin: 0;padding: 5px"> {{ __('Comment/Schedule Information') }}</p> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($order->comment_for_pickup_driver)
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __('Comment for Pickup Driver') }} :</span> {{ $order->comment_for_pickup_driver ?? ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($order->comment_for_dropoff_driver)
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __('Comment for Dropoff Driver') }} :</span> {{ $order->comment_for_dropoff_driver ?? ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($order->comment_for_vendor)
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __('Comment for Vendor') }} :</span> {{ $order->comment_for_vendor ?? ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($order->schedule_pickup)
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __('Schedule Pickup') }} :</span> {{dateTimeInUserTimeZone($order->schedule_pickup, $timezone)}}
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($order->schedule_dropoff)
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __('Schedule Dropoff') }} :</span> {{dateTimeInUserTimeZone($order->schedule_dropoff, $timezone)}}
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($order->specific_instructions)
                                    <tr>
                                        <td align="left">
                                            <p style="font-size: 14px;font-family: Lato,sans-serif;margin: 0;padding: 5px">
                                            <span style="font-size: 14px;font-family: Lato,sans-serif;">{{ __('Specific instructions') }} :</span> {{ $order->specific_instructions ?? ''}}
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td height="20"></td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </td>
                    </table>
                </td>
                
            </tr>
        </table>
    </div>