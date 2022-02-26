<?php

namespace App\Http\Controllers\Client;

use DB;
use Log;
use Auth;
use Session;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Client as CP;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AhoyController;
use App\Http\Controllers\Client\BaseController;
use App\Http\Controllers\Front\LalaMovesController;
use App\Http\Controllers\ShiprocketController;
use App\Http\Controllers\DunzoController;
use App\Models\{AutoRejectOrderCron, Order, OrderStatusOption, OrderCancelRequest, DispatcherStatusOption, VendorOrderStatus, ClientPreference, NotificationTemplate, OrderProduct, OrderVendor, UserAddress, Vendor, OrderReturnRequest, UserDevice, UserVendor, LuxuryOption, ClientCurrency, VendorOrderDispatcherStatus, Tax, Transaction, User};

class OrderCancelRequestsController extends BaseController
{

    use ApiResponser;
    use \App\Http\Traits\OrderTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $order_requests = OrderCancelRequest::orderBy('id','desc');

        $pending_requests_count = OrderCancelRequest::where('status', 0);
        if ($user->is_superadmin == 0) {
            $pending_requests_count = $pending_requests_count->whereHas('order.vendors.vendor.permissionToUser', function ($query) use($user) {
                $query->where('user_id', $user->id);
            });
        }
        $pending_requests_count = $pending_requests_count->count();

        $approved_requests_count = OrderCancelRequest::where('status', 1);
        if ($user->is_superadmin == 0) {
            $approved_requests_count = $approved_requests_count->whereHas('order.vendors.vendor.permissionToUser', function ($query) use($user) {
                $query->where('user_id', $user->id);
            });
        }
        $approved_requests_count = $approved_requests_count->count();

        $rejected_requests_count = OrderCancelRequest::where('status', 2);
        if ($user->is_superadmin == 0) {
            $rejected_requests_count = $rejected_requests_count->whereHas('order.vendors.vendor.permissionToUser', function ($query) use($user) {
                $query->where('user_id', $user->id);
            });
        }
        $rejected_requests_count = $rejected_requests_count->count();
        
        $clientCurrency = ClientCurrency::where('is_primary', 1)->first();
        return view('order-cancel-requests.index', compact('pending_requests_count', 'approved_requests_count', 'rejected_requests_count', 'clientCurrency'));
    }

    public function postOrderFilter(Request $request, $domain = '')
    {
        $user = Auth::user();
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        
    }
   
    
}
