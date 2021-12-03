 <tr>
     <td>
        <table class="order-detail" border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px; margin-top: 10px">
            <tr class="pad-left-right-space">
                <th align="left">PRODUCT</th>
                <th align="left">DESCRIPTION</th>
                <th align="center">QUANTITY</th>
                <th align="right">PRICE </th>
            </tr>
            @foreach($cartData->products as $product)
            <tr class="pad-left-right-space">
                <td colspan="4"><h4 class="main-bg-light" style="padding: 5px 10px; margin-top: 5px; margin-bottom: 5px">{{$product['vendor']['name']}}</h4></td>
            </tr>
            @php
                $total_products = 0;
            @endphp
                @foreach($product['vendor_products'] as $vendor_product)
                    <tr class="pad-left-right-space">
                        <td align="left">
                            <div style="padding:0px 5px"><img src="{{$vendor_product['product']['media'][0]['image']['path']['image_fit']}}100/100{{$vendor_product['product']['media'][0]['image']['path']['image_path']}}" alt="" width="80"></div>
                        </td>
                        <td valign="top" align="left">
                            <h4 style="margin-top: 10px;">{{$vendor_product['product']['translation_one']['title']}}</h4>
                        </td>
                        <td valign="top" align="center">
                            <h4 style="margin-top: 10px;"><span>{{$vendor_product['quantity']}}</span></h4>
                        </td>
                        <td valign="top" align="right">
                            <h4 style="margin-top:10px"><b>{{ $currencySymbol . number_format($vendor_product['pvariant']['price'], 2, '.', '')}}</b></h4>
                            @php
                            $total_products += $vendor_product['pvariant']['price'];
                            @endphp
                        </td>
                    </tr>
                @endforeach
            @endforeach
            <tr class="pad-left-right-space ">
                <td class="m-t-5" colspan="2" align="left">
                    <p style="font-size: 14px;"><b>Subtotal</b></p>
                </td>
                <td class="m-t-5" colspan="2" align="right">
                    <b style>{{$currencySymbol . number_format($order->total_amount, 2, '.', '')}}</b>
                </td>
            </tr>
            <tr class="pad-left-right-space">
                <td colspan="2" align="left">
                    <p style="font-size: 14px;"><b>Tax</b></p>
                </td>
                <td colspan="2" align="right">
                    <b>{{$currencySymbol . number_format($order->taxable_amount, 2, '.', '')}}</b>
                </td>
            </tr>
            @if($order->total_delivery_fee > 0)
                <tr class="pad-left-right-space">
                    <td colspan="2" align="left">
                        <p style="font-size: 14px;"><b>Delivery Charge</b></p>
                    </td>
                    <td colspan="2" align="right">
                        <b>{{$currencySymbol . number_format($order->total_delivery_fee, 2, '.', '')}}</b>
                    </td>
                </tr>
            @endif
            @if($order->tip_amount > 0)
                <tr class="pad-left-right-space">
                    <td colspan="2" align="left">
                        <p style="font-size: 14px;"><b>{{__('Tip')}}</b></p>
                    </td>
                    <td colspan="2" align="right">
                        <b>{{$currencySymbol . number_format($order->tip_amount, 2, '.', '')}}</b>
                    </td>
                </tr>
            @endif
            @if($order->subscription_discount > 0)
                <tr class="pad-left-right-space">
                    <td colspan="2" align="left">
                        <p style="font-size: 14px;"><b>{{__('Subscription Discount')}}</b></p>
                    </td>
                    <td colspan="2" align="right">
                        <b>{{$currencySymbol . number_format($order->subscription_discount, 2, '.', '')}}</b>
                    </td>
                </tr>
            @endif
            @if($order->loyalty_amount_saved > 0)
            <tr class="pad-left-right-space">
                <td colspan="2" align="left">
                    <p style="font-size: 14px;"><b>{{__('Loyalty Amount Used')}}</b></p>
                </td>
                <td colspan="2" align="right">
                    <b>{{$currencySymbol . number_format($order->loyalty_amount_saved, 2, '.', '')}}</b>
                </td>
            </tr>
            @endif
            @if($order->wallet_amount_used > 0)
            <tr class="pad-left-right-space">
                <td colspan="2" align="left">
                    <p style="font-size: 14px;"><b>{{__('Wallet Amount Used')}}</b></p>
                </td>
                <td colspan="2" align="right">
                    <b>{{$currencySymbol . number_format($order->wallet_amount_used, 2, '.', '')}}</b>
                </td>
            </tr>
            @endif
            @if($order->total_discount > 0)
            <tr class="pad-left-right-space">
                <td colspan="2" align="left">
                    <p style="font-size: 14px;"><b>{{__('Total Discount')}}</b></p>
                </td>
                <td colspan="2" align="right">
                    <b>{{$currencySymbol . number_format($order->total_discount, 2, '.', '')}}</b>
                </td>
            </tr>
            @endif
            <tr class="pad-left-right-space main-bg-light">
                <td class="m-b-5" colspan="2" align="left">
                    <p style="font-size: 14px;"><b>Total</b></p>
                </td>
                <td class="m-b-5" colspan="2" align="right">
                    <b>{{$currencySymbol . number_format($order->payable_amount, 2, '.', '')}}</b>
                </td>
            </tr>
        </table>
     </td>
 </tr>
