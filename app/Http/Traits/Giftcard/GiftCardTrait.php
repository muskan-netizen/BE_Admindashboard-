<?php

namespace App\Http\Traits\Giftcard;

use App\Models\{UserGiftCard};
use Carbon\Carbon;
use Session, DB , Auth;
use Illuminate\Support\Str;


trait GiftCardTrait
{


    public function getUserActiveGiftCard(){
        $active_giftcard = [];
        try {
            $user = Auth::user();
           
            if( $user ){
                $now = Carbon::now()->toDateTimeString();
                $now = convertDateTimeInClientTimeZone($now);
               
                $active_giftcard = UserGiftCard::select('*',DB::raw('count(*) as total'))->with('giftCard')->whereHas('giftCard',function ($query) use ($now){
                    return  $query->whereDate('expiry_date', '>=', $now);
                })->where(['is_used'=>'0','user_id'=>$user->id])->groupBy('gift_card_id')->get();
            }
            return $active_giftcard;
        } catch (Exception $e) {
            return $active_giftcard;
        }
    }

    
}
