<?php

namespace App\Http\Controllers\Front;
use DB;
use Auth;
use Session;
use Password;
use Carbon\Carbon;
use Omnipay\Omnipay;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Http\Traits\ApiResponser;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Front\FrontController;
use App\Models\{AppStyling, AppStylingOption, Currency, Client, Category, Brand, Cart, ReferAndEarn, ClientPreference, Vendor, ClientCurrency, User, Country, UserRefferal, Wallet, WalletHistory, CartProduct, PaymentOption, UserVendor,Permissions, UserPermissions, VendorDocs, VendorRegistrationDocument, EmailTemplate};

class CustomerAuthController extends FrontController
{

    public function getTestHtmlPage()
    {
        return view('test');
    }

    public function loginForm($domain = '')
    {
        $curId = Session::get('customerCurrency');
        $langId = Session::get('customerLanguage');
        $navCategories = $this->categoryNav($langId);
        return view('frontend.account.loginnew')->with(['navCategories' => $navCategories]);
    }

    public function registerForm($domain = '', Request $request)
    {
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        if (!Session::get('referrer')) {
            return view('frontend.account.registernew')->with(['navCategories' => $navCategories]);
        } else {
            return view('frontend.account.registernew')->with(['navCategories' => $navCategories, 'code' => Session::get('referrer')]);
        }
    }

    /**     * check if cookie already exist     */
    public function checkCookies($userid){
        if (\Cookie::has('uuid')) {
            $existCookie = \Cookie::get('uuid');
            $userFind = User::where('system_id', $existCookie)->first();
            if ($userFind) {
                $cart = Cart::where('user_id', $userFind->id)->first();
                if ($cart) {
                    $cart->user_id = $userid;
                    $cart->save();
                }
                $userFind->delete();
            }
            \Cookie::queue(\Cookie::forget('uuid'));
            return redirect()->route('user.checkout');
        }
    }

    /**     * Display login Form     */
    public function login(LoginRequest $req, $domain = ''){
        if (Auth::attempt(['email' => $req->email, 'password' => $req->password, 'status' => 1])) {
            $userid = Auth::id();
            $this->checkCookies($userid);
            $user_cart = Cart::where('user_id', $userid)->first();
            if ($user_cart) {
                $unique_identifier_cart = Cart::where('unique_identifier', session()->get('_token'))->first();
                if ($unique_identifier_cart) {
                    $unique_identifier_cart_products = CartProduct::where('cart_id', $unique_identifier_cart->id)->get();
                    foreach ($unique_identifier_cart_products as $unique_identifier_cart_product) {
                        $user_cart_product_detail = CartProduct::where('cart_id', $user_cart->id)->where('product_id', $unique_identifier_cart_product->product_id)->first();
                        if ($user_cart_product_detail) {
                            $user_cart_product_detail->quantity = ($unique_identifier_cart_product->quantity + $user_cart_product_detail->quantity);
                            $user_cart_product_detail->save();
                            $unique_identifier_cart_product->delete();
                        } else {
                            $unique_identifier_cart_product->cart_id = $user_cart->id;
                            $unique_identifier_cart_product->save();
                        }
                    }
                    $unique_identifier_cart->delete();
                }
            } else {
                Cart::where('unique_identifier', session()->get('_token'))->update(['user_id' => $userid, 'created_by' => $userid, 'unique_identifier' => '']);
            }
            return redirect()->route('user.verify');
        }
        $checkEmail = User::where('email', $req->email)->first();
        if ($checkEmail) {
            return redirect()->back()->with('err_password', __('Password not matched. Please enter correct password.'));
        }
        return redirect()->back()->with('err_email', __('Email not exist. Please enter correct email.'));
    }


    /**     * Display register Form     */
    public function register(SignupRequest $req, $domain = ''){
        try {
            $user = new User();
            $county = Country::where('code', strtoupper($req->countryData))->first();
            $phoneCode = mt_rand(100000, 999999);
            $emailCode = mt_rand(100000, 999999);
            $sendTime = \Carbon\Carbon::now()->addMinutes(10)->toDateTimeString();
            $user->type = 1;
            $user->status = 1;
            $user->role_id = 1;
            $user->name = $req->name;
            $user->email = $req->email;
            $user->is_email_verified = 0;
            $user->is_phone_verified = 0;
            $user->country_id = $county->id;
            $user->phone_token = $phoneCode;
            $user->dial_code = $req->dialCode;
            $user->email_token = $emailCode;
            $user->phone_number = $req->phone_number;
            $user->phone_token_valid_till = $sendTime;
            $user->email_token_valid_till = $sendTime;
            $user->password = Hash::make($req->password);
            $user->save();
            $wallet = $user->wallet;
            $userRefferal = new UserRefferal();
            $userRefferal->refferal_code = $this->randomData("user_refferals", 8, 'refferal_code');
            if ($req->refferal_code != null) {
                $userRefferal->reffered_by = $req->refferal_code;
            }
            $userRefferal->user_id = $user->id;
            $userRefferal->save();
            if ($user->id > 0) {
                if ($req->refferal_code != null) {
                    $refferal_amounts = ClientPreference::first();
                    if ($refferal_amounts) {
                        if ($refferal_amounts->reffered_by_amount != null && $refferal_amounts->reffered_to_amount != null) {
                            $reffered_by = UserRefferal::where('refferal_code', $req->refferal_code)->first();
                            $user_refferd_by = $reffered_by->user_id;
                            $user_refferd_by = User::where('id', $reffered_by->user_id)->first();
                            if ($user_refferd_by) {
                                //user reffered by amount
                                $wallet_user_reffered_by = $user_refferd_by->wallet;
                                $wallet_user_reffered_by->depositFloat($refferal_amounts->reffered_by_amount, ['Referral code used by <b>' . $req->name . '</b>']);
                                $wallet_user_reffered_by->balance;
                                //user reffered to amount
                                $wallet->depositFloat($refferal_amounts->reffered_to_amount, ['You used referral code of <b>' . $user_refferd_by->name . '</b>']);
                                $wallet->balance;
                            }
                        }
                    }
                }
                Auth::login($user);
                $this->checkCookies($user->id);
                Session::forget('referrer');
                $this->newUserSendToken();
                return redirect()->route('user.verify');
            }
        } catch (Exception $e) {
        }
    }
    public function postVendorregister(Request $request, $domain = ''){
        try {
            DB::beginTransaction();
            $vendor_registration_documents = VendorRegistrationDocument::with('primary')->get();
            if (empty($request->input('user_id'))){
                if($vendor_registration_documents->count() > 0){
                    $request->validate([
                        'address' => 'required',
                        'full_name' => 'required',
                        'email' => 'required|email|unique:users',
                        'vendor_registration_document.*.did_visit' => 'required',
                        'password' => 'required|string|min:6|max:50',
                        'confirm_password' => 'required|same:password',
                        'name' => 'required|string|max:150|unique:vendors',
                        'phone_number' => 'required|string|min:6|max:15|unique:users',
                    ]);
                }else{
                    $request->validate([
                        'address' => 'required',
                        'full_name' => 'required',
                        'email' => 'required|email|unique:users',
                        'password' => 'required|string|min:6|max:50',
                        'confirm_password' => 'required|same:password',
                        'name' => 'required|string|max:150|unique:vendors',
                        'phone_number' => 'required|string|min:6|max:15|unique:users',
                    ]);
                }
            }else {
                $rules_array = [
                        'address' => 'required',
                        'name' => 'required|string|max:150|unique:vendors',
                ];
                foreach ($vendor_registration_documents as $vendor_registration_document) {
                    $rules_array[$vendor_registration_document->primary->slug] = 'required';
                }
                $request->validate($rules_array);
            }
            $client_detail = Client::first();
            $client_preference = ClientPreference::first();
            if(!$request->user_id){
                $user = new User();
                $county = Country::where('code', strtoupper($request->countryData))->first();
                $sendTime = Carbon::now()->addMinutes(10)->toDateTimeString();
                $user->type = 1;
                $user->status = 1;
                $user->role_id = 1;
                $user->is_admin = 1;
                $user->is_email_verified = 0;
                $user->is_phone_verified = 0;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->title = $request->title;
                $user->country_id = $county->id;
                $user->dial_code = $request->dialCode;
                $user->phone_token_valid_till = $sendTime;
                $user->email_token_valid_till = $sendTime;
                $user->email_token = mt_rand(100000, 999999);
                $user->phone_token = mt_rand(100000, 999999);
                $user->phone_number = $request->phone_number;
                $user->password = Hash::make($request->password);
                $user->save();
                $wallet = $user->wallet;
            }else{
                $user = User::where('id', $request->user_id)->first();
                $user->title = $request->title;
                $user->save();
            }
            $vendor = new Vendor();
            $vendor->dine_in = ($request->has('dine_in') && $request->dine_in == 'on') ? 1 : 0;
            $vendor->takeaway = ($request->has('takeaway') && $request->takeaway == 'on') ? 1 : 0;
            $vendor->delivery = ($request->has('delivery') && $request->delivery == 'on') ? 1 : 0;
            $vendor->logo = 'default/default_logo.png';
            $vendor->banner = 'default/default_image.png';
            if ($request->hasFile('upload_logo')) {
                $file = $request->file('upload_logo');
                $vendor->logo = Storage::disk('s3')->put('/vendor', $file, 'public');
            }
            if ($request->hasFile('upload_banner')) {
                $file = $request->file('upload_banner');
                $vendor->banner = Storage::disk('s3')->put('/vendor', $file, 'public');
            }
            $vendor->status = 0;
            $vendor->name = $request->name;
            $vendor->email = $request->email;
            $vendor->phone_no = $user->phone_no;
            $vendor->address = $request->address;
            $vendor->website = $request->website;
            $vendor->latitude = $request->latitude;
            $vendor->longitude = $request->longitude;
            $vendor->desc = $request->vendor_description;
            $vendor->slug = Str::slug($request->name, "-");
            $vendor->save();
            $permission_details = Permissions::whereIn('id', [1,2,3,12,17,18,19,20,21])->get();
            if($vendor_registration_documents->count() > 0){
                foreach ($vendor_registration_documents as $vendor_registration_document) {
                    $vendor_registration_document_id = $vendor_registration_document->id;
                    $name = $vendor_registration_document->primary->slug;
                    $vendor_registration_document = $request->$name;
                    $vendor_docs =  new VendorDocs();
                    $vendor_docs->vendor_id = $vendor->id;
                    $vendor_docs->vendor_registration_document_id = $vendor_registration_document_id;
                    $vendor_docs->file_name = Storage::disk('s3')->put('/vendor', $vendor_registration_document, 'public');
                    $vendor_docs->save();
                }
            }
            UserVendor::create(['user_id' => $user->id, 'vendor_id' => $vendor->id]);
            foreach ($permission_details as $permission_detail) {
                UserPermissions::create(['user_id' => $user->id, 'permission_id' => $permission_detail->id]);
            }
            $content = '';
            $email_template = EmailTemplate::where('id', 1)->first();
            if($email_template){
                $content = $email_template->content;
                $content = str_ireplace("{title}", $user->title, $content);
                $content = str_ireplace("{email}", $user->email, $content);
                $content = str_ireplace("{address}", $vendor->address, $content);
                $content = str_ireplace("{website}", $vendor->website, $content);
                $content = str_ireplace("{description}", $vendor->desc, $content);
                $content = str_ireplace("{vendor_name}", $vendor->name, $content);
                $content = str_ireplace("{phone_no}", $user->phone_number, $content);
            }
            $email_data = [
                'title' => $user->title,
                'email' => $user->email,
                'powered_by' => url('/'),
                'banner' => $vendor->banner,
                'website' => $vendor->website,
                'address' => $vendor->address,
                'vendor_logo' => $vendor->logo,
                'vendor_name' => $vendor->name,
                'description' => $vendor->desc,
                'phone_no' => $user->phone_number,
                'email_template_content' => $content,
                'subject' => $email_template->subject,
                'client_name' => $client_detail->name,
                'customer_name' => ucwords($user->name),
                'logo' => $client_detail->logo['original'],
                'mail_from' => $client_preference->mail_from,
            ];
            $admin_email_data = [
                'title' => $user->title,
                'email' => $user->email,
                'powered_by' => url('/'),
                'banner' => $vendor->banner,
                'website' => $vendor->website,
                'address' => $vendor->address,
                'vendor_logo' => $vendor->logo,
                'vendor_name' => $vendor->name,
                'description' => $vendor->desc,
                'phone_no' => $user->phone_number,
                'email_template_content' => $content,
                'client_name' => $client_detail->name,
                'subject' => 'New Vendor Registration',
                'customer_name' => ucwords($user->name),
                'logo' => $client_detail->logo['original'],
                'mail_from' => $client_preference->mail_from,
            ];
            dispatch(new \App\Jobs\sendVendorRegistrationEmail($email_data))->onQueue('verify_email');
            dispatch(new \App\Jobs\sendVendorRegistrationEmail($admin_email_data))->onQueue('verify_email');
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Vendor Registration Created Successfully!',
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('customer.login');
    }
}
