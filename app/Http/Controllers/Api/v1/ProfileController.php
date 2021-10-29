<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Country;
use DB;
use Config;
use Validation;
use Carbon\Carbon;
use ConvertCurrency;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\v1\BaseController;
use App\Http\Requests\{SendReferralRequest};
use App\Models\{User,UserRefferal,ClientPreference,Client,UserWishlist,ClientCurrency, EmailTemplate, Product};

class ProfileController extends BaseController{

    use ApiResponser;
    private $curLang = 0; 
    private $field_status = 2;

     public function postSendReffralCode(SendReferralRequest $SendReferralRequest){
        try {
            $user = Auth::user();
            $client = Client::first();
            $client_preference_detail = ClientPreference::first();
            $user_refferal_detail = UserRefferal::where('user_id', $user->id)->first();
            if($user_refferal_detail){
                $refferal_code = $user_refferal_detail->refferal_code;
                if($client_preference_detail){
                    if ($client_preference_detail->mail_driver && $client_preference_detail->mail_host && $client_preference_detail->mail_port && $client_preference_detail->mail_port && $client_preference_detail->mail_password && $client_preference_detail->mail_encryption) {
                        $confirured = $this->setMailDetail($client_preference_detail->mail_driver, $client_preference_detail->mail_host, $client_preference_detail->mail_port, $client_preference_detail->mail_username, $client_preference_detail->mail_password, $client_preference_detail->mail_encryption);
                        $client_name = $client->name;
                        $sendto = $SendReferralRequest->email;
                        $mail_from = $client_preference_detail->mail_from;
                        try {
                            Mail::send(
                                'email.verify',
                                [
                                    'code' => $refferal_code,
                                    'logo' => $client->logo['original'],
                                    'customer_name' => "Link from ".$user->name,
                                    'code_text' => 'Register yourself using this referral code below to get bonus offer',
                                    'link' => "http://local.myorder.com/user/register?refferal_code=".$refferal_code,
                                ],
                                function ($message) use ($sendto, $client_name, $mail_from) {
                                    $message->from($mail_from, $client_name);
                                    $message->to($sendto)->subject('OTP to verify account');
                                }
                            );
                        } catch (\Exception $e) {
                        }
                    }
                    return response()->json(array('success' => true, 'message' => __('Send Successfully')));
                }
            }
        } catch (Exception $e) {
            
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wishlists(Request $request){
        $user = Auth::user();
        $language_id = $user->language;
        $paginate = $request->has('limit') ? $request->limit : 12;
		$clientCurrency = ClientCurrency::where('currency_id', $user->currency)->first();
        $user_wish_details = UserWishlist::with(['product.category.categoryDetail',
            'product.category.categoryDetail.translation' => function($q) use($language_id){
                $q->select('category_translations.name', 'category_translations.meta_title', 'category_translations.meta_description', 'category_translations.meta_keywords', 'category_translations.category_id')
                ->where('category_translations.language_id', $language_id);
            },
            'product.media.image', 'product.translation' => function($q) use($language_id){
                $q->select('product_id', 'title', 'body_html', 'meta_title', 'meta_keyword', 'meta_description')->where('language_id', $language_id);
            },'product.variant' => function($q) use($language_id){
                $q->select('sku', 'product_id', 'quantity', 'price', 'barcode');
                $q->groupBy('product_id');
            },
        ])->select( "id", "user_id", "product_id")->where('user_id', $user->id)->paginate($paginate);
    	if($user_wish_details){
    		foreach ($user_wish_details as $user_wish_detail) {
                $user_wish_detail->product->is_wishlist = $user_wish_detail->product->category->categoryDetail->show_wishlist ?? null;
    			if($user_wish_detail->product->variant){
		    		foreach ($user_wish_detail->product->variant as $variant) {
			            $variant->multiplier = $clientCurrency->doller_compare;
			        }
		    	}
	        }
    	}
    	return response()->json(['data' => $user_wish_details]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWishlist(Request $request, $pid = 0){
        $product = Product::where('id', $pid)->first();
        if(!$product){
            return response()->json(['error' => __('No record found.')], 404);
        }
        $exist = UserWishlist::where('user_id', Auth::user()->id)->where('product_id', $product->id)->first();
        if($exist){
            $exist->delete();
            return response()->json([
            	'data' => $product->id,
	            'message' => __('Product has been removed from wishlist.'),
	        ]);
        }
        $wishlist = new UserWishlist();
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $product->id;
        $wishlist->added_on = Carbon::now();
        $wishlist->save();
        return response()->json([
        	'data' => $product->id,
            'message' => __('Product has been added in wishlist.'),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function newsLetter(Request $request, $domain = '')
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $user = User::with('country', 'address')->select('name', 'email', 'phone_number', 'type', 'country_id')->where('id', Auth::user()->id)->first();
        if(!$user){
            return response()->json(['error' => __('No record found.')], 404);
        }
        return response()->json(['data' => $user]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request, $domain = ''){
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|max:50',
            'confirm_password' => 'required|same:new_password',
        ]);
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = __($error_value[0]);
                return response()->json($errors, 422);
            }
        }

        $current_password = Auth::User()->password;           
        if(!Hash::check($request->current_password, $current_password))
        {
            return response()->json(['error' => __('Password did not matched.')], 404);
        }
        $user_id = Auth::User()->id;                       
        $obj_user = User::find(Auth::User()->id);
        $obj_user->password = Hash::make($request->new_password);
        $obj_user->save(); 
        return response()->json([
            'message' => __('Password updated successfully.'),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(Request $request){
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|string'
        ]); 
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = __($error_value[0]);
                return response()->json($errors, 422);
            }
        }
        $img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->avatar));
        $user = User::where('id', Auth::user()->id)->first();
        if(!empty($user->image)){
            Storage::disk('s3')->delete($user->image); 
        }
        $imgType = ($request->has('type')) ? $request->type : 'jpg';
        $code = Client::orderBy('id','asc')->value('code');
        $imageName = '/'.$code.'/profile/'.$user->id.substr(md5(microtime()), 0, 15).'.'.$imgType;
        $save = Storage::disk('s3')->put($imageName, $img, 'public');
        $user->image = $imageName;
        $user->save();
        return response()->json([
            'message' => __('Profile image updated successfully.'),
            'data' => $user->image,
            'save' => $save
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request){
        $usr = Auth::user()->id; 
        $validator = Validator::make($request->all(), [
            'country_code'  => 'required|string',
            'name'          => 'required|string|min:3|max:50',
            'email'         => 'required|email|max:50||unique:users,email,'.$usr,
            'phone_number'  => 'required|string|min:8|max:15|unique:users,phone_number,'.$usr,
        ]);
        if($validator->fails()){
            foreach($validator->errors()->toArray() as $error_key => $error_value){
                $errors['error'] = __($error_value[0]);
                return response()->json($errors, 422);
            }
        }
        $country_detail = Country::where('code', $request->country_code)->first();
        if(!$country_detail){
            return response()->json(['error' => __('Invalid country code.')], 404);
        }
        $prefer = ClientPreference::select('mail_type', 'mail_driver', 'mail_host', 'mail_port', 'mail_username','mail_password', 'mail_encryption', 'mail_from', 'sms_provider', 'sms_key', 'sms_secret', 'sms_from', 'theme_admin', 'distance_unit', 'map_provider', 'date_format', 'time_format', 'map_key', 'sms_provider', 'verify_email', 'verify_phone', 'app_template_id', 'web_template_id')->first();
        $user = User::where('id', $usr)->first();
        $user->name = $request->name;
        $user->country_id = $country_detail->id;
        $sendTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
        if($user->phone_number != trim($request->phone_number)){
            $phoneCode = mt_rand(100000, 999999);
            $user->is_phone_verified = 0;
            $user->phone_token = $phoneCode;
            $user->phone_token_valid_till = $sendTime;
            $user->phone_number = $request->phone_number;
            if(!empty($prefer->sms_key) && !empty($prefer->sms_secret) && !empty($prefer->sms_from)){
                $to = $request->phone_number;
                $provider = $prefer->sms_provider;
                $body = "Dear ".ucwords($request->phone_number).", Please enter OTP ".$phoneCode." to verify your account.";
                $send = $this->sendSms($provider, $prefer->sms_key, $prefer->sms_secret, $prefer->sms_from, $to, $body);
                $response['send_otp'] = 1;
            }
        }
        if($user->email != trim($request->email)){
            $emailCode = mt_rand(100000, 999999);
            $user->email = $request->email;
            $user->is_email_verified = 0;
            $user->email_token = $emailCode;
            $user->email_token_valid_till = $sendTime;
            if(!empty($prefer->mail_driver) && !empty($prefer->mail_host) && !empty($prefer->mail_port) && !empty($prefer->mail_port) && !empty($prefer->mail_password) && !empty($prefer->mail_encryption)){
                $client = Client::select('id', 'name', 'email', 'phone_number', 'logo')->where('id', '>', 0)->first();
                $confirured = $this->setMailDetail($prefer->mail_driver, $prefer->mail_host, $prefer->mail_port, $prefer->mail_username, $prefer->mail_password, $prefer->mail_encryption);
                $client_name = $client->name;
                $mail_from = $prefer->mail_from;
                $sendto = $user->email;
                try {
                    $email_template_content = '';
                    $email_template = EmailTemplate::where('id', 2)->first();
                    if($email_template){
                        $email_template_content = $email_template->content;
                        $email_template_content = str_ireplace("{code}", $emailCode, $email_template_content);
                        $email_template_content = str_ireplace("{customer_name}", ucwords($user->name), $email_template_content);
                    }
                    $data = [
                        'code' => $emailCode,
                        'link' => "link",
                        'email' => $sendto,
                        'mail_from' => $mail_from,
                        'client_name' => $client_name,
                        'logo' => $client->logo['original'],
                        'subject' => $email_template->subject,
                        'customer_name' => ucwords($user->name),
                        'email_template_content' => $email_template_content,
                    ];
                    $user->is_email_verified = 0;
                    dispatch(new \App\Jobs\SendVerifyEmailJob($data))->onQueue('verify_email');
                    $notified = 1;
                } catch (\Exception $e) {
                    $user->save();
                }
            }
        }
        $user->save();
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['cca2'] = $request->country_code;
        $data['phone_number'] = $user->phone_number;
        $data['is_phone_verified'] = $user->is_phone_verified;
        $data['is_email_verified'] = $user->is_email_verified;
        return response()->json([
            'data' => $data,
            'message' => __('Profile updated successfully.')
        ]);
    }
    
}