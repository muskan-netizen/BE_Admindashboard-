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
class BookingController extends FrontController
{
    use ApiResponser;
   
   
    /******************    ---- Booking index page-----   ******************/
    public function index()
    {
        return view('frontend.booking.index');
    }
}
