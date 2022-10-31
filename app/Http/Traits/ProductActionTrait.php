<?php
namespace App\Http\Traits;
use App\Models\{ProductRecentlyViewed,WebStylingOption};
use Auth;
use Carbon\Carbon;

trait ProductActionTrait{

      
    /**
     * RecentView
     *
     * @param  mixed $p_id
     * @return void
     */
    public function RecentView($p_id)
    {
        $token_id = session()->get('_token');
        $user_id = 0;
        $update_by['product_id'] = $p_id;
         if(Auth::check()){
            $user_id = Auth::user()->id;
            $update_by['user_id'] = $user_id;
        } else{
            $update_by['token_id'] = $token_id;
        }
        $RecentlyViewed = [
            'product_id' => $p_id,
            'token_id' => $token_id,
            'user_id' => $user_id,
            'updated_at' => Carbon::now()
        ];
         ProductRecentlyViewed::updateOrCreate(
            $update_by
        ,$RecentlyViewed);
    }
    
    /**
     * LoginActionRecentView
     *
     * @param  mixed $user_id
     * @return void
     */
    public function LoginActionRecentView($user_id)
    {
        ProductRecentlyViewed::where('token_id', session()->get('_token'))->update(['user_id' => $user_id, 'token_id' => '']);
    }


    /**
     * LoginActionRecentView
     *
     * @param  mixed $user_id
     * @return void
     */
    public function checkIfTemplateEightEnable()
    {
        $set_template = WebStylingOption::where('is_selected', 1)->first();
        $val = 0;
        if(isset($set_template)  && $set_template->template_id == 8){
            $val = 1;
        }
        return $val;
    }
    
   
}
