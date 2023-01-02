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
                $active_giftcard = UserGiftCard::select('*',DB::raw('count(*) as total'))->with('giftCard')->whereHas('giftCard',function ($query) use ($now){
                    return  $query->whereDate('expiry_date', '>=', $now);
                })->where(['is_used'=>'0','user_id'=>$user->id])->groupBy('gift_card_id')->get();
            }
            return $active_giftcard;
        } catch (Exception $e) {
            return $active_giftcard;
        }
    }

    public function getGiftCardCode($str = 'ROYO',$length = 4){
        
            $code =strtoupper( mb_substr($str, 0, 4) );
   
            do {
                for ($i = $length; $i--; $i > 0) {
                    $code .= mt_rand(0, 9);
                }
            } while (!empty(UserGiftCard::where('gift_card_code', $code)->first(['gift_card_code'])));
            return $code;
       
    }

    
}
