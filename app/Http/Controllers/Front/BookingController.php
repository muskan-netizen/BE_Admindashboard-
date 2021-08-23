<?php

namespace App\Http\Controllers\Front;

use App\Models\{VendorOrderDispatcherStatus,OrderVendor};
use Illuminate\Http\Request;
use App\Http\Requests\DispatchOrderStatusUpdateRequest;
use App\Http\Controllers\Front\FrontController;
use Carbon\Carbon;
use Auth;
use Session;
use DB;
use App\Http\Traits\ApiResponser;
use App\Models\{Currency, Banner, Category, Brand, Product, Celebrity, ClientLanguage, Vendor, VendorCategory, ClientCurrency, ProductVariantSet, ServiceArea, UserAddress,Country,Cart,CartProduct,SubscriptionInvoicesUser,ClientPreference,LoyaltyCard,Order};

class BookingController extends FrontController
{
    use ApiResponser;
   
   
    /******************    ---- Booking index page-----   ******************/
    public function index()
    {
        return view('frontend.booking.index');
    }


    /******************    ---- Booking details page-----   ******************/
    public function bookingDetails(Request $request, $domain = '',$order_id = 0)
    {   
        $langId = Session::get('customerLanguage');
        $curId = Session::get('customerCurrency');
        $navCategories = $this->categoryNav($langId);
        $user_addresses = UserAddress::get();
        $order = Order::where('order_number',$order_id)->where('user_id',Auth::id())->first();
        $route = route('front.booking.orderplacedetails',$order->id);
           
        return view('frontend.booking.details')->with(['user_addresses' => $user_addresses, 'navCategories' => $navCategories,'order' => $order,'route' => $route]);
       
    }


    public function orderPlaceDetails(Request $request, $domain = '',$order_id = 0){

            $order = Order::where('id',$order_id)->where('user_id',Auth::id())->first();
          
            $order['product_image'] = $order->products->first()->image['image_fit'].'300/300'.$order->products->first()->image['image_path'];
            $order['dispatch_traking_url'] = $order->vendors->first()->dispatch_traking_url ?? null;
            $data = [];
            $data['status'] = 200;
            $data['message'] =  'Order Details';
            $data['data'] = $order;
            return $data;
    }
    
}
