<?php
namespace Database\Seeders;
use DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MainPermissionSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $permissions_array = [];
        // $permissions = [
        //  'DASHBOARD','ORDERS','VENDORS','CUSTOMERS','Profile', 'CUSTOMIZE', 'CONFIGURATIONS', 'BANNER','CATALOG', 'TAX', 'PAYMENT','PROMOCODE', 'LOYALTY CARDS', 'CELEBRITY', 'WEB STYLING', 'APP STYLING', 'Accounting Orders', 'Accounting Loyality', 'Accounting Promo Codes', 'Accounting Taxes', 'Accounting Vendors','Subscriptions Customers', 'Subscriptions Vendors', 'CMS Pages', 'CMS Emails', 'Inquiries','Tools'];

        $permissions = array(
            //Dashboard Page
            array('name' => 'dashboard-view','controller'=>'DashBoardController'),
            array('name' => 'dashboard-weekRevenue','controller'=>'DashBoardController'),
            array('name' => 'dashboard-locationRevenue','controller'=>'DashBoardController'),
            array('name' => 'dashboard-monthRevenue','controller'=>'DashBoardController'),
            array('name' => 'dashboard-totalRevenue','controller'=>'DashBoardController'),
            
            //Order Page
            array('name' => 'order-view','controller'=>'OrderController'),
            array('name' => 'order-accept','controller'=>'OrderController'),

            //Vendor Page
            array('name' => 'vendor-view','controller'=>'VendorController'),
            array('name' => 'vendor-setting','controller'=>'VendorController'),
            array('name' => 'vendor-catalog','controller'=>'VendorController'),
            array('name' => 'vendor-config','controller'=>'VendorController'),
            array('name' => 'vendor-categories','controller'=>'VendorController'),
            array('name' => 'vendor-payout','controller'=>'VendorController'),
            array('name' => 'vendor-add-users','controller'=>'VendorController'),
            
            //Account Page
            array('name' => 'accounting-view','controller'=>'AccountController'),
            array('name' => 'accounting-orders','controller'=>'AccountController'),
            array('name' => 'accounting-loyalty-cards','controller'=>'AccountController'),
            array('name' => 'accounting-promo-codes','controller'=>'AccountController'),
            array('name' => 'accounting-taxes','controller'=>'AccountController'),
            array('name' => 'accounting-vendors','controller'=>'AccountController'),
            array('name' => 'accounting-payout-request','controller'=>'AccountController'),
            array('name' => 'accounting-order-refund','controller'=>'AccountController'),
            array('name' => 'accounting-subscription-discount','controller'=>'AccountController'),


            //Subscription Page
            array('name' => 'subscription-customer-view','controller'=>'SubscriptionPlansUserController'),
            array('name' => 'subscription-customer-add','controller'=>'SubscriptionPlansUserController'),
            array('name' => 'subscription-vendor-view','controller'=>'SubscriptionPlansVendorController'),
            array('name' => 'subscription-vendor-add','controller'=>'SubscriptionPlansVendorController'),
            
            //Customers Page
            array('name' => 'customers-view','controller'=>'UserController'),
            array('name' => 'customers-add','controller'=>'UserController'),
            

            //Review Page
            array('name' => 'review-view','controller'=>'ReviewController'),
            array('name' => 'review-product-performance','controller'=>'ReportController'),



        );


        foreach ($permissions as $key=> $permission) {
           $permissions_array[]=array(
            'name' => $permission['name'],
            'controller' => $permission['controller'],
            'guard_name' => 'web',
           );
        }

        $option_count = DB::table('main_permissions')->count();
        if($option_count == 0)
        {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('main_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('main_permissions')->insert($permissions_array);
        }
        else{

            foreach ($permissions_array as $key=> $permission) {
                $payop = Permission::where('name', $permission['name'])->first();
  
                if ($payop !== null) {
                    $payop->update(['name' => $permission['name']]);
                } else {
                    // dd('ddd');
                    $payop = Permission::create([
                        'name' => $permission['name'],
                        'controller' => $permission['controller'],
                        'guard_name' => 'web',
                    ]);
                }
            }

        }
    }
}
