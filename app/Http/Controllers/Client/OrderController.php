<?php

namespace App\Http\Controllers\Client;

use Auth;
use Session;
use App\Models\Tax;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Client\BaseController;
use App\Models\VendorOrderDispatcherStatus;
use App\Models\{OrderStatusOption, DispatcherStatusOption, VendorOrderStatus, ClientPreference, NotificationTemplate, OrderProduct, OrderVendor, UserAddress, Vendor, OrderReturnRequest, UserDevice, UserVendor, LuxuryOption, ClientCurrency};
use DB;
use GuzzleHttp\Client;
use App\Models\Client as CP;
use App\Models\Transaction;
use App\Models\AutoRejectOrderCron;
use App\Http\Traits\ApiResponser;
use Log;

class OrderController extends BaseController
{

    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user();
        // $orders = Order::with(['vendors.products','orderStatusVendor', 'address','user'])->orderBy('id', 'DESC');
        // if (Auth::user()->is_superadmin == 0) {
        //     $orders = $orders->whereHas('vendors.vendor.permissionToUser', function ($query) {
        //         $query->where('user_id', Auth::user()->id);
        //     });
        // }
        // $orders = $orders->get();
        // foreach ($orders as $order) {
        //     $order->address = $order->address ? $order->address['address'] : '';
        //     $order->created_date = convertDateTimeInTimeZone($order->created_at, $user->timezone, 'd-m-Y, H:i A');
        //     foreach ($order->vendors as $vendor) {
        //         $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
        //         $vendor->order_status = $vendor_order_status ? $vendor_order_status->OrderStatusOption->title : '';
        //         foreach ($vendor->products as $product) {
        //             $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
        //         }
        //     }
        // }
        $return_requests = OrderReturnRequest::where('status', 'Pending');
        if (Auth::user()->is_superadmin == 0) {
            $return_requests = $return_requests->whereHas('order.vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $return_requests = $return_requests->count();
        // Pending counts
        $pending_order_count = Order::with('vendors')->whereHas('vendors', function ($query) {
            $query->where('order_status_option_id', 1);
        });
        if (Auth::user()->is_superadmin == 0) {
            $pending_order_count = $pending_order_count->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $pending_order_count = $pending_order_count->where(function ($q1) {
            $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
            $q1->orWhere(function ($q2) {
                $q2->where('payment_option_id', 1);
            });
        })->count();

        // past orders count
        $past_order_count = Order::with('vendors')->whereHas('vendors', function ($query) {
            $query->whereIn('order_status_option_id', [6, 3]);
        });
        if (Auth::user()->is_superadmin == 0) {
            $past_order_count = $past_order_count->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $past_order_count = $past_order_count->where(function ($q1) {
            $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
            $q1->orWhere(function ($q2) {
                $q2->where('payment_option_id', 1);
            });
        })->count();

        // active orders count
        $active_order_count = Order::with('vendors')->whereHas('vendors', function ($query) {
            $query->whereIn('order_status_option_id', [2, 4, 5]);
        });
        if (Auth::user()->is_superadmin == 0) {
            $active_order_count = $active_order_count->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $active_order_count = $active_order_count->where(function ($q1) {
            $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
            $q1->orWhere(function ($q2) {
                $q2->where('payment_option_id', 1);
            });
        })->count();

        // all vendors
        $vendors = Vendor::where('status', '!=', '2')->orderBy('id', 'desc');
        if (Auth::user()->is_superadmin == 0) {
            $vendors = $vendors->whereHas('permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        $vendors = $vendors->get();
        $clientCurrency = ClientCurrency::where('is_primary', 1)->first();

        return view('backend.order.index', compact('return_requests', 'pending_order_count', 'active_order_count', 'past_order_count', 'clientCurrency', 'vendors'));
    }

    public function postOrderFilter(Request $request, $domain = '')
    {
        $user = Auth::user();
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $filter_order_status = $request->filter_order_status;
        $orders = Order::with(['vendors.products', 'vendors.status', 'orderStatusVendor', 'address', 'user'])->orderBy('id', 'DESC');
        if (Auth::user()->is_superadmin == 0) {
            $orders = $orders->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        if (!empty($request->search_keyword)) {
            $orders = $orders->where('order_number', 'like', '%' . $request->search_keyword . '%');
        }


        $order_count = Order::with('vendors')->where(function ($q1) {
            $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
            $q1->orWhere(function ($q2) {
                $q2->where('payment_option_id', 1);
            });
        })->orderBy('id', 'asc');
        if (Auth::user()->is_superadmin == 0) {
            $order_count = $order_count->whereHas('vendors.vendor.permissionToUser', function ($query) {
                $query->where('user_id', Auth::user()->id);
            });
        }
        //filer bitween date
        if (!empty($request->get('date_filter'))) {
            $date_date_filter = explode(' to ', $request->get('date_filter'));
            $to_date = (!empty($date_date_filter[1])) ? $date_date_filter[1] : $date_date_filter[0];
            $from_date = $date_date_filter[0];

            $orders->between($from_date . " 00:00:00", $to_date . " 23:59:59");

            //order_count
            $order_count->between($from_date . " 00:00:00", $to_date . " 23:59:59");
        }
        //get by vendor
        if (!empty($request->get('vendor_id'))) {
            $order_count->whereHas('vendors', function ($query)  use ($request) {
                $query->where('vendor_id', $request->get('vendor_id'));
            });
        }
        $pending_orders = clone $order_count;
        $active_orders = clone $order_count;
        $orders_history = clone $order_count;

        if ($filter_order_status) {
            switch ($filter_order_status) {
                case 'pending_orders':
                    $orders = $orders->with('vendors', function ($query) {
                        $query->where('order_status_option_id', 1);
                    })->whereHas('vendors', function ($query) use ($request) {
                        $query->where('order_status_option_id', 1);
                        if (!empty($request->get('vendor_id'))) {
                            $query->where('vendor_id', $request->get('vendor_id'));
                        }
                    });

                    break;
                case 'active_orders':
                    $order_status_options = [2, 4, 5];
                    $orders = $orders->with('vendors', function ($query) use ($order_status_options) {
                        $query->whereIn('order_status_option_id', $order_status_options);
                    })->whereHas('vendors', function ($query) use ($order_status_options, $request) {
                        $query->whereIn('order_status_option_id', $order_status_options);
                        if (!empty($request->get('vendor_id'))) {
                            $query->where('vendor_id', $request->get('vendor_id'));
                        }
                    });

                    break;
                case 'orders_history':
                    $order_status_options = [6, 3];
                    $orders = $orders->with('vendors', function ($query) use ($order_status_options) {
                        $query->whereIn('order_status_option_id', $order_status_options);
                    })->whereHas('vendors', function ($query) use ($order_status_options, $request) {
                        $query->whereIn('order_status_option_id', $order_status_options);
                        if (!empty($request->get('vendor_id'))) {
                            $query->where('vendor_id', $request->get('vendor_id'));
                        }
                    });

                    break;
            }
        }
        $orders = $orders->whereHas('vendors')->where(function ($q1) {
            $q1->where('payment_status', 1)->whereNotIn('payment_option_id', [1]);
            $q1->orWhere(function ($q2) {
                $q2->where('payment_option_id', 1);
            });
        })->select('*', 'id as total_discount_calculate')->paginate(30);


        $pending_orders = $pending_orders->with('vendors', function ($query) {
            $query->where('order_status_option_id', 1);
        })->whereHas('vendors', function ($query) {
            $query->where('order_status_option_id', 1);
        })->count();

        $order_status_optionsa = [2, 4, 5];
        $active_orders = $active_orders->with('vendors', function ($query) use ($order_status_optionsa) {
            $query->whereIn('order_status_option_id', $order_status_optionsa);
        })->whereHas('vendors', function ($query) use ($order_status_optionsa) {
            $query->whereIn('order_status_option_id', $order_status_optionsa);
        })->count();

        $order_status_optionsd = [6, 3];
        $orders_history = $orders_history->with('vendors', function ($query) use ($order_status_optionsd) {
            $query->whereIn('order_status_option_id', $order_status_optionsd);
        })->whereHas('vendors', function ($query) use ($order_status_optionsd) {
            $query->whereIn('order_status_option_id', $order_status_optionsd);
        })->count();


        foreach ($orders as $key => $order) {
            // $order->created_date = convertDateTimeInTimeZone($order->created_at, $user->timezone, 'd-m-Y, h:i A');
            $order->created_date = dateTimeInUserTimeZone($order->created_at, $user->timezone);
            $order->scheduled_date_time = !empty($order->scheduled_date_time) ? dateTimeInUserTimeZone($order->scheduled_date_time, $user->timezone) : '';
            foreach ($order->vendors as $vendor) {
                $vendor->vendor_detail_url = route('order.show.detail', [$order->id, $vendor->vendor_id]);
                $vendor_order_status = VendorOrderStatus::with('OrderStatusOption')->where('order_id', $order->id)->where('vendor_id', $vendor->vendor_id)->orderBy('id', 'DESC')->first();
                $vendor->order_status = $vendor_order_status ? $vendor_order_status->OrderStatusOption->title : '';
                $vendor->order_vendor_id = $vendor_order_status ? $vendor_order_status->order_vendor_id : '';
                $vendor->vendor_name = $vendor ? $vendor->vendor->name : '';
                $product_total_count = 0;
                foreach ($vendor->products as $product) {
                    $product_total_count += $product->quantity * $product->price;
                    $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
                }
                $vendor->product_total_count = $product_total_count;
                $vendor->final_amount = $vendor->taxable_amount + $product_total_count;
            }
            $luxury_option_name = '';
            if ($order->luxury_option_id > 0) {
                $luxury_option = LuxuryOption::where('id', $order->luxury_option_id)->first();
                if ($luxury_option->title == 'takeaway') {
                    $luxury_option_name = $this->getNomenclatureName('Takeaway', $langId, false);
                } elseif ($luxury_option->title == 'dine_in') {
                    $luxury_option_name = 'Dine-In';
                } else {
                    $luxury_option_name = 'Delivery';
                }
            }
            $order->luxury_option_name = $luxury_option_name;
            if ($order->vendors->count() == 0) {
                $orders->forget($key);
            }
        }


        return $this->successResponse(['orders' => $orders, 'pending_orders' => $pending_orders, 'active_orders' => $active_orders, 'orders_history' => $orders_history], '', 201);
    }
    /**
     * Display the order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */

    public function getOrderDetail($domain = '', $order_id, $vendor_id)
    {
        $langId = Session::has('adminLanguage') ? Session::get('adminLanguage') : 1;
        $clientCurrency = ClientCurrency::where('is_primary', 1)->first();
        $vendor_order_status_option_ids = [];
        $vendor_order_status_created_dates = [];
        $order = Order::with(array(
            'vendors' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            },
            'vendors.products.prescription' => function ($query) use ($vendor_id, $order_id) {
                $query->where('vendor_id', $vendor_id)->where('order_id', $order_id);
            },
            'vendors.products' => function ($query) use ($vendor_id) {
                $query->where('vendor_id', $vendor_id);
            },
            'vendors.products.addon',
            'vendors.products.addon.set',
            'vendors.products.addon.option',
            'vendors.products.addon.option.translation_one' => function ($q) use ($langId) {
                $q->select('id', 'addon_opt_id', 'title');
                $q->where('language_id', $langId);
            },
            'vendors.dineInTable.translations' => function ($qry) use ($langId) {
                $qry->where('language_id', $langId);
            },
            'vendors.dineInTable.category'
        ))->findOrFail($order_id);
        foreach ($order->vendors as $key => $vendor) {
            foreach ($vendor->products as $key => $product) {
                $product->image_path  = $product->media->first() ? $product->media->first()->image->path : '';
                $divider = (empty($product->doller_compare) || $product->doller_compare < 0) ? 1 : $product->doller_compare;
                $total_amount = $product->quantity * $product->price;
                foreach ($product->addon as $ck => $addons) {
                    $opt_price_in_currency = $addons->option->price;
                    $opt_price_in_doller_compare = $addons->option->price;
                    if ($clientCurrency) {
                        $opt_price_in_currency = $addons->option->price / $divider;
                        $opt_price_in_doller_compare = $opt_price_in_currency * $clientCurrency->doller_compare;
                    }
                    $opt_quantity_price = number_format($opt_price_in_doller_compare * $product->quantity, 2, '.', '');
                    $addons->option->price_in_cart = $addons->option->price;
                    $addons->option->price = number_format($opt_price_in_currency, 2, '.', '');
                    $addons->option->multiplier = ($clientCurrency) ? $clientCurrency->doller_compare : 1;
                    $addons->option->quantity_price = $opt_quantity_price;
                    $total_amount = $total_amount + $opt_quantity_price;
                }
                $product->total_amount = $total_amount;
            }
            if ($vendor->dineInTable) {
                $vendor->dineInTableName = $vendor->dineInTable->translations->first() ? $vendor->dineInTable->translations->first()->name : '';
                $vendor->dineInTableCapacity = $vendor->dineInTable->seating_number;
                $vendor->dineInTableCategory = $vendor->dineInTable->category->title; //$vendor->dineInTable->category->first() ? $vendor->dineInTable->category->first()->title : '';
            }
        }
        // dd($order->toArray());
        $luxury_option_name = '';
        if ($order->luxury_option_id > 0) {
            $luxury_option = LuxuryOption::where('id', $order->luxury_option_id)->first();
            if ($luxury_option->title == 'takeaway') {
                $luxury_option_name = $this->getNomenclatureName('Takeaway', $langId, false);
            } elseif ($luxury_option->title == 'dine_in') {
                $luxury_option_name = 'Dine-In';
            } else {
                $luxury_option_name = 'Delivery';
            }
        }
        $order->luxury_option_name = $luxury_option_name;
        $order_status_options = OrderStatusOption::where('type', 1)->get();
        $dispatcher_status_options = DispatcherStatusOption::with(['vendorOrderDispatcherStatus' => function ($q) use ($order_id, $vendor_id) {
            $q->where(['order_id' => $order_id, 'vendor_id' => $vendor_id]);
        }])->get();
        $vendor_order_statuses = VendorOrderStatus::where('order_id', $order_id)->where('vendor_id', $vendor_id)->get();
        foreach ($vendor_order_statuses as $vendor_order_status) {
            $vendor_order_status_created_dates[$vendor_order_status->order_status_option_id] = $vendor_order_status->created_at;
            $vendor_order_status_option_ids[] = $vendor_order_status->order_status_option_id;
        }
        return view('backend.order.view')->with([
            'vendor_id' => $vendor_id, 'order' => $order,
            'vendor_order_statuses' => $vendor_order_statuses,
            'vendor_order_status_option_ids' => $vendor_order_status_option_ids,
            'order_status_options' => $order_status_options,
            'dispatcher_status_options' => $dispatcher_status_options,
            'vendor_order_status_created_dates' => $vendor_order_status_created_dates, 'clientCurrency' => $clientCurrency
        ]);
    }

    /**
     * Change the status of order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $domain = '')
    {

        DB::beginTransaction();
        $client_preferences = ClientPreference::first();
        try {
            $timezone = Auth::user()->timezone;
            $vendor_order_status_check = VendorOrderStatus::where('order_id', $request->order_id)->where('vendor_id', $request->vendor_id)->where('order_status_option_id', $request->status_option_id)->first();
            $currentOrderStatus = OrderVendor::where(['vendor_id' => $request->vendor_id, 'order_id' => $request->order_id])->first();
            if ($currentOrderStatus->order_status_option_id == 2 && $request->status_option_id == 2) { //$request->status_option_id == 3){
                return response()->json(['status' => 'error', 'message' => __('Order has already been accepted!!!')]);
            }
            if ($currentOrderStatus->order_status_option_id == 3 && $request->status_option_id == 3) { //$request->status_option_id == 2){
                return response()->json(['status' => 'error', 'message' => __('Order has already been rejected!!!')]);
            }
            if (!$vendor_order_status_check) {
                $vendor_order_status = new VendorOrderStatus();
                $vendor_order_status->order_id = $request->order_id;
                $vendor_order_status->vendor_id = $request->vendor_id;
                $vendor_order_status->order_vendor_id = $request->order_vendor_id;
                $vendor_order_status->order_status_option_id = $request->status_option_id;
                $vendor_order_status->save();
                if ($request->status_option_id == 2 || $request->status_option_id == 3) {
                    $clientDetail = CP::on('mysql')->where(['code' => $client_preferences->client_code])->first();
                    AutoRejectOrderCron::on('mysql')->where(['database_name' => $clientDetail->database_name, 'order_vendor_id' => $currentOrderStatus->id])->delete();
                }
                if ($request->status_option_id == 2) {
                    $order_dispatch = $this->checkIfanyProductLastMileon($request);
                    if ($order_dispatch && $order_dispatch == 1)
                        $stats = $this->insertInVendorOrderDispatchStatus($request);
                }
                OrderVendor::where('vendor_id', $request->vendor_id)->where('order_id', $request->order_id)->update(['order_status_option_id' => $request->status_option_id, 'reject_reason' => $request->reject_reason]);
                $orderData = Order::find($request->order_id);

                if (!empty($currentOrderStatus->dispatch_traking_url) && ($request->status_option_id == 3)) {
                    $dispatch_traking_url = str_replace('/order/', '/order-cancel/', $currentOrderStatus->dispatch_traking_url);
                    $response = Http::get($dispatch_traking_url);
                }
                DB::commit();
                // $this->sendSuccessNotification(Auth::user()->id, $request->vendor_id);
                $this->sendStatusChangePushNotificationCustomer([$currentOrderStatus->user_id], $orderData, $request->status_option_id);
                return response()->json([
                    'status' => 'success',
                    'created_date' => convertDateTimeInTimeZone($vendor_order_status->created_at, $timezone, 'l, F d, Y, H:i A'),
                    'message' => 'Order Status Updated Successfully.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    /// ******************   insert In Vendor Order Dispatch Status   ************************ ///////////////
    public function insertInVendorOrderDispatchStatus($request)
    {
        $update = VendorOrderDispatcherStatus::updateOrCreate([
            'dispatcher_id' => null,
            'order_id' =>  $request->order_id,
            'dispatcher_status_option_id' => 1,
            'vendor_id' =>  $request->vendor_id
        ]);
    }

    public function sendSuccessNotification($id, $vendorId)
    {
        $super_admin = User::where('is_superadmin', 1)->pluck('id');
        $user_vendors = UserVendor::where('vendor_id', $vendorId)->pluck('user_id');
        $devices = UserDevice::whereNotNull('device_token')->where('user_id', $id)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_vendors)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $super_admin)->pluck('device_token');
        foreach ($devices as $device) {
            $token[] = $device;
        }
        $token[] = "d4SQZU1QTMyMaENeZXL3r6:APA91bHoHsQ-rnxsFaidTq5fPse0k78qOTo7ZiPTASiH69eodqxGoMnRu2x5xnX44WfRhrVJSQg2FIjdfhwCyfpnZKL2bHb5doCiIxxpaduAUp4MUVIj8Q43SB3dvvvBkM1Qc1ThGtEM";
        // dd($token);

        $from = env('FIREBASE_SERVER_KEY');

        $notification_content = NotificationTemplate::where('id', 2)->first();
        if ($notification_content) {
            $headers = [
                'Authorization: key=' . $from,
                'Content-Type: application/json',
            ];
            $data = [
                "registration_ids" => $token,
                "notification" => [
                    'title' => $notification_content->label,
                    'body'  => $notification_content->content,
                ]
            ];
            $dataString = $data;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
            $result = curl_exec($ch);
            // dd($result);
            curl_close($ch);
        }
    }
    /// ******************  check If any Product Last Mile on   ************************ ///////////////
    public function checkIfanyProductLastMileon($request)
    {
        $order_dispatchs = 2;
        $checkdeliveryFeeAdded = OrderVendor::where(['order_id' => $request->order_id, 'vendor_id' => $request->vendor_id])->first();
        $dispatch_domain = $this->getDispatchDomain();
        if ($dispatch_domain && $dispatch_domain != false) {
            if ($checkdeliveryFeeAdded && $checkdeliveryFeeAdded->delivery_fee > 0.00)
                $order_dispatchs = $this->placeRequestToDispatch($request->order_id, $request->vendor_id, $dispatch_domain);


            if ($order_dispatchs && $order_dispatchs == 1)
                return 1;
        }


        $dispatch_domain_ondemand = $this->getDispatchOnDemandDomain();
        if ($dispatch_domain_ondemand && $dispatch_domain_ondemand != false) {
            $ondemand = 0;

            foreach ($checkdeliveryFeeAdded->products as $key => $prod) {
                if (isset($prod->product_dispatcher_tag) && !empty($prod->product_dispatcher_tag) && $prod->product->category->categoryDetail->type_id == 8) {
                    $dispatch_domain_ondemand = $this->getDispatchOnDemandDomain();
                    if ($dispatch_domain_ondemand && $dispatch_domain_ondemand != false && $ondemand == 0  && $checkdeliveryFeeAdded->delivery_fee <= 0.00) {
                        $order_dispatchs = $this->placeRequestToDispatchOnDemand($request->order_id, $request->vendor_id, $dispatch_domain_ondemand);
                        if ($order_dispatchs && $order_dispatchs == 1) {
                            $ondemand = 1;
                            return 1;
                        }
                    }
                }
            }
        }

        /////////////// **************** for laundry accept order *************** ////////////////
        $dispatch_domain_laundry = $this->getDispatchLaundryDomain();

        if ($dispatch_domain_laundry && $dispatch_domain_laundry != false) {
            $laundry = 0;

            foreach ($checkdeliveryFeeAdded->products as $key => $prod) {
                if ($prod->product->category->categoryDetail->type_id == 9) {     ///////// if product from laundry
                    $dispatch_domain_laundry = $this->getDispatchLaundryDomain();
                    if ($dispatch_domain_laundry && $dispatch_domain_laundry != false && $laundry == 0) {

                        for ($x = 1; $x <= 2; $x++) {

                            if ($x == 1) {
                                $team_tag = $dispatch_domain_laundry->laundry_pickup_team ?? null;
                                $colm = $x;
                            }

                            if ($x == 2) {
                                $team_tag = $dispatch_domain_laundry->laundry_dropoff_team ?? null;
                                $colm = $x;
                            }



                            $order_dispatchs = $this->placeRequestToDispatchLaundry($request->order_id, $request->vendor_id, $dispatch_domain_laundry, $team_tag, $colm);
                        }

                        if ($order_dispatchs && $order_dispatchs == 1) {
                            $laundry = 1;
                            return 1;
                        }
                    }
                }
            }
        }



        return 2;
    }
    // place Request To Dispatch
    public function placeRequestToDispatch($order, $vendor, $dispatch_domain)
    {
        try {

            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected = 'Yes';
                $payable_amount = $order->payable_amount;
            } else {
                $cash_to_be_collected = 'No';
                $payable_amount = 0.00;
            }
            $dynamic = uniqid($order->id . $vendor);
            $call_back_url = route('dispatch-order-update', $dynamic);
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'latitude', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';

            $team_tag = null;
            if (!empty($dispatch_domain->last_mile_team))
                $team_tag = $dispatch_domain->last_mile_team;


            $tasks[] = array(
                'task_type_id' => 1,
                'latitude' => $vendor_details->latitude ?? '',
                'longitude' => $vendor_details->longitude ?? '',
                'short_name' => '',
                'address' => $vendor_details->address ?? '',
                'post_code' => '',
                'barcode' => '',
            );

            $tasks[] = array(
                'task_type_id' => 2,
                'latitude' => $cus_address->latitude ?? '',
                'longitude' => $cus_address->longitude ?? '',
                'short_name' => '',
                'address' => $cus_address->address ?? '',
                'post_code' => $cus_address->pincode ?? '',
                'barcode' => '',
            );

            $postdata =  [
                'customer_name' => $customer->name ?? 'Dummy Customer',
                'customer_phone_number' => $customer->phone_number ?? rand(111111, 11111),
                'customer_email' => $customer->email ?? null,
                'recipient_phone' => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email' => $customer->email ?? null,
                'task_description' => "Order From :" . $vendor_details->name,
                'allocation_type' => 'a',
                'task_type' => 'now',
                'cash_to_be_collected' => $payable_amount ?? 0.00,
                'barcode' => '',
                'order_team_tag' => $team_tag,
                'call_back_url' => $call_back_url ?? null,
                'task' => $tasks
            ];


            $client = new Client([
                'headers' => [
                    'personaltoken' => $dispatch_domain->delivery_service_key,
                    'shortcode' => $dispatch_domain->delivery_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);

            $url = $dispatch_domain->delivery_service_key_url;
            $res = $client->post(
                $url . '/api/task/create',
                ['form_params' => ($postdata)]
            );
            $response = json_decode($res->getBody(), true);
            if ($response && $response['task_id'] > 0) {
                $dispatch_traking_url = $response['dispatch_traking_url'] ?? '';
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic, 'dispatch_traking_url' => $dispatch_traking_url]);

                return 1;
            }
            return 2;
        } catch (\Exception $e) {
            return 2;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }



    // place Request To Dispatch for On Demand
    public function placeRequestToDispatchOnDemand($order, $vendor, $dispatch_domain)
    {
        try {

            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected = 'Yes';
                $payable_amount = $order->payable_amount;
            } else {
                $cash_to_be_collected = 'No';
                $payable_amount = 0.00;
            }
            $dynamic = uniqid($order->id . $vendor);
            $call_back_url = route('dispatch-order-update', $dynamic);
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'latitude', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';

            $unique = Auth::user()->code;
            $team_tag = $unique . "_" . $vendor;


            $tasks[] = array(
                'task_type_id' => 1,
                'latitude' => $vendor_details->latitude ?? '',
                'longitude' => $vendor_details->longitude ?? '',
                'short_name' => '',
                'address' => $vendor_details->address ?? '',
                'post_code' => '',
                'barcode' => '',
            );

            $tasks[] = array(
                'task_type_id' => 2,
                'latitude' => $cus_address->latitude ?? '',
                'longitude' => $cus_address->longitude ?? '',
                'short_name' => '',
                'address' => $cus_address->address ?? '',
                'post_code' => $cus_address->pincode ?? '',
                'barcode' => '',
            );

            $postdata =  [
                'customer_name' => $customer->name ?? 'Dummy Customer',
                'customer_phone_number' => $customer->phone_number ?? rand(111111, 11111),
                'customer_email' => $customer->email ?? null,
                'recipient_phone' => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email' => $customer->email ?? null,
                'task_description' => "Order From :" . $vendor_details->name,
                'allocation_type' => 'a',
                'task_type' => 'now',
                'cash_to_be_collected' => $payable_amount ?? 0.00,
                'barcode' => '',
                'order_team_tag' => $team_tag,
                'call_back_url' => $call_back_url ?? null,
                'task' => $tasks
            ];


            $client = new Client([
                'headers' => [
                    'personaltoken' => $dispatch_domain->dispacher_home_other_service_key,
                    'shortcode' => $dispatch_domain->dispacher_home_other_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);

            $url = $dispatch_domain->dispacher_home_other_service_key_url;
            $res = $client->post(
                $url . '/api/task/create',
                ['form_params' => ($postdata)]
            );
            $response = json_decode($res->getBody(), true);
            if ($response && $response['task_id'] > 0) {
                $dispatch_traking_url = $response['dispatch_traking_url'] ?? '';
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic, 'dispatch_traking_url' => $dispatch_traking_url]);

                return 1;
            }
            return 2;
        } catch (\Exception $e) {
            return 2;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


    // place Request To Dispatch for Laundry
    public function placeRequestToDispatchLaundry($order, $vendor, $dispatch_domain, $team_tag, $colm)
    {
        try {

            $order = Order::find($order);
            $customer = User::find($order->user_id);
            $cus_address = UserAddress::find($order->address_id);
            $tasks = array();
            if ($order->payment_method == 1) {
                $cash_to_be_collected = 'Yes';
                $payable_amount = $order->payable_amount;
            } else {
                $cash_to_be_collected = 'No';
                $payable_amount = 0.00;
            }


            $dynamic = uniqid($order->id . $vendor);
            $call_back_url = route('dispatch-order-update', $dynamic);
            $vendor_details = Vendor::where('id', $vendor)->select('id', 'name', 'latitude', 'longitude', 'address')->first();
            $tasks = array();
            $meta_data = '';

            $unique = Auth::user()->code;
            if ($colm == 1) {     # 1 for pickup from customer drop to vendor
                $desc = $order->comment_for_pickup_driver ?? null;
                $tasks[] = array(
                    'task_type_id' => 1,
                    'latitude' => $cus_address->latitude ?? '',
                    'longitude' => $cus_address->longitude ?? '',
                    'short_name' => '',
                    'address' => $cus_address->address ?? '',
                    'post_code' => $cus_address->pincode ?? '',
                    'barcode' => '',
                );
                $tasks[] = array(
                    'task_type_id' => 2,
                    'latitude' => $vendor_details->latitude ?? '',
                    'longitude' => $vendor_details->longitude ?? '',
                    'short_name' => '',
                    'address' => $vendor_details->address ?? '',
                    'post_code' => '',
                    'barcode' => '',
                );

                if (isset($order->schedule_pickup) && !empty($order->schedule_pickup)) {
                    $task_type = 'schedule';
                    $schedule_time = $order->schedule_pickup ?? null;
                } else {
                    $task_type = 'now';
                }
            }


            if ($colm == 2) { # 1 for pickup from vendor drop to customer
                $desc = $order->comment_for_dropoff_driver ?? null;
                $tasks[] = array(
                    'task_type_id' => 1,
                    'latitude' => $vendor_details->latitude ?? '',
                    'longitude' => $vendor_details->longitude ?? '',
                    'short_name' => '',
                    'address' => $vendor_details->address ?? '',
                    'post_code' => '',
                    'barcode' => '',
                );

                $tasks[] = array(
                    'task_type_id' => 2,
                    'latitude' => $cus_address->latitude ?? '',
                    'longitude' => $cus_address->longitude ?? '',
                    'short_name' => '',
                    'address' => $cus_address->address ?? '',
                    'post_code' => $cus_address->pincode ?? '',
                    'barcode' => '',
                );


                if (isset($order->schedule_dropoff) && !empty($order->schedule_dropoff)) {
                    $task_type = 'schedule';
                    $schedule_time = $order->schedule_dropoff ?? null;
                } else {
                    $task_type = 'now';
                }
            }




            $postdata =  [
                'customer_name' => $customer->name ?? 'Dummy Customer',
                'customer_phone_number' => $customer->phone_number ?? rand(111111, 11111),
                'customer_email' => $customer->email ?? null,
                'recipient_phone' => $customer->phone_number ?? rand(111111, 11111),
                'recipient_email' => $customer->email ?? null,
                'task_description' => $desc ?? null,
                'allocation_type' => 'a',
                'task_type' => $task_type,
                'cash_to_be_collected' => $payable_amount ?? 0.00,
                'schedule_time' => $schedule_time ?? null,
                'barcode' => '',
                'order_team_tag' => $team_tag,
                'call_back_url' => $call_back_url ?? null,
                'task' => $tasks
            ];


            $client = new Client([
                'headers' => [
                    'personaltoken' => $dispatch_domain->laundry_service_key,
                    'shortcode' => $dispatch_domain->laundry_service_key_code,
                    'content-type' => 'application/json'
                ]
            ]);
            Log::info($postdata);
            $url = $dispatch_domain->laundry_service_key_url;
            $res = $client->post(
                $url . '/api/task/create',
                ['form_params' => ($postdata)]
            );
            $response = json_decode($res->getBody(), true);

            if ($response && $response['task_id'] > 0) {
                $dispatch_traking_url = $response['dispatch_traking_url'] ?? '';
                $up_web_hook_code = OrderVendor::where(['order_id' => $order->id, 'vendor_id' => $vendor])
                    ->update(['web_hook_code' => $dynamic, 'dispatch_traking_url' => $dispatch_traking_url]);

                return 1;
            }
            return 2;
        } catch (\Exception $e) {

            return 2;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    # get prefereance if last mile on or off and all details updated in config
    public function getDispatchDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_delivery_service == 1 && !empty($preference->delivery_service_key) && !empty($preference->delivery_service_key_code) && !empty($preference->delivery_service_key_url))
            return $preference;
        else
            return false;
    }


    # get prefereance if on demand on in config
    public function getDispatchOnDemandDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_dispacher_home_other_service == 1 && !empty($preference->dispacher_home_other_service_key) && !empty($preference->dispacher_home_other_service_key_code) && !empty($preference->dispacher_home_other_service_key_url))
            return $preference;
        else
            return false;
    }

    # get prefereance if laundry in config
    public function getDispatchLaundryDomain()
    {
        $preference = ClientPreference::first();
        if ($preference->need_laundry_service == 1 && !empty($preference->laundry_service_key) && !empty($preference->laundry_service_key_code) && !empty($preference->laundry_service_key_url))
            return $preference;
        else
            return false;
    }



    /**
     * Display a listing of the order return request.
     *
     * @return \Illuminate\Http\Response
     */
    public function returnOrders(Request $request, $domain = '', $status)
    {
        try {

            $orders_list = OrderReturnRequest::where('status', $status)->with('product')->orderBy('updated_at', 'DESC');
            if (Auth::user()->is_superadmin == 0) {
                $orders_list = $orders_list->whereHas('order.vendors.vendor.permissionToUser', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                });
            }
            $orders[$status] = $orders_list->paginate(20);
            return view(
                'backend.order.return',
                [
                    'orders' => $orders,
                    'status' => $status
                ]
            );
        } catch (\Throwable $th) {
            return redirect()->back();
        }
    }


    /**
     * return orders details
     */
    public function getReturnProductModal(Request $request, $domain = '')
    {
        try {
            $return_details = OrderReturnRequest::where('id', $request->id)->with('returnFiles')->first();
            if (isset($return_details)) {

                if ($request->ajax()) {
                    return \Response::json(\View::make('frontend.modals.update-return-product-client', array('return_details' => $return_details))->render());
                }
            }
            return $this->errorResponse('Invalid order', 404);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * return  order product
     */
    public function updateProductReturn(Request $request)
    {
        DB::beginTransaction();
        try {
            $return = OrderReturnRequest::find($request->id);
            $returns = OrderReturnRequest::where('id', $request->id)->update(['status' => $request->status ?? null, 'reason_by_vendor' => $request->reason_by_vendor ?? null]);
            if (isset($returns)) {
                if ($request->status == 'Accepted' && $return->status != 'Accepted') {
                    $user = User::find($return->return_by);
                    $wallet = $user->wallet;
                    $order_product = OrderProduct::find($return->order_vendor_product_id);
                    $credit_amount = $order_product->price + $order_product->taxable_amount;
                    $wallet->depositFloat($credit_amount, ['Wallet has been <b>Credited</b> for return ' . $order_product->product_name]);
                    DB::commit();
                }
                return $this->successResponse($returns, 'Updated.');
            }
            return $this->errorResponse('Invalid order', 200);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function sendStatusChangePushNotificationCustomer($user_ids, $orderData, $order_status_id)
    {
        $devices = UserDevice::whereNotNull('device_token')->whereIn('user_id', $user_ids)->pluck('device_token')->toArray();

        $client_preferences = ClientPreference::select('fcm_server_key', 'favicon')->first();
        if (!empty($devices) && !empty($client_preferences->fcm_server_key)) {
            $from = $client_preferences->fcm_server_key;
            if ($order_status_id == 2) {
                $notification_content = NotificationTemplate::where('id', 5)->first();
            } elseif ($order_status_id == 3) {
                $notification_content = NotificationTemplate::where('id', 6)->first();
            } elseif ($order_status_id == 4) {
                $notification_content = NotificationTemplate::where('id', 7)->first();
            } elseif ($order_status_id == 5) {
                $notification_content = NotificationTemplate::where('id', 8)->first();
            } elseif ($order_status_id == 6) {
                $notification_content = NotificationTemplate::where('id', 9)->first();
            }
            if ($notification_content) {
                $headers = [
                    'Authorization: key=' . $from,
                    'Content-Type: application/json',
                ];
                $body_content = str_ireplace("{order_id}", "#" . $orderData->order_number, $notification_content->content);
                $data = [
                    "registration_ids" => $devices,
                    "notification" => [
                        'title' => $notification_content->subject,
                        'body'  => $body_content,
                        'sound' => "default",
                        "icon" => (!empty($client_preferences->favicon)) ? $client_preferences->favicon['proxy_url'] . '200/200' . $client_preferences->favicon['image_path'] : '',
                        'click_action' => route('user.orders'),
                        "android_channel_id" => "default-channel-id"
                    ],
                    "data" => [
                        'title' => $notification_content->subject,
                        'body'  => $body_content,
                        "type" => "order_status_change"
                    ],
                    "priority" => "high"
                ];
                $dataString = $data;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataString));
                $result = curl_exec($ch);

                curl_close($ch);
            }
        }
    }
}
