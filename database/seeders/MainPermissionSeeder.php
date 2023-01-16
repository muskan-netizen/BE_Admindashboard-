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


            //UserController Client Profile Page
            array('name' => 'setting-profile-view','controller'=>'UserController'),
            array('name' => 'setting-profile-add','controller'=>'UserController'),

            //ClientPreferenceController Page
            array('name' => 'setting-customize-view','controller'=>'ClientPreferenceController'),
            array('name' => 'setting-customize-add','controller'=>'ClientPreferenceController'),


             //WebStylingController Page
             array('name' => 'setting-webstyle-view','controller'=>'WebStylingController'),

             
             //AppStylingController Page
             array('name' => 'setting-appstyle-view','controller'=>'AppStylingController'),


              //PageController Page
              array('name' => 'cms-pages-view','controller'=>'PageController'),
              
              //EmailController Page
              array('name' => 'cms-email-view','controller'=>'EmailController'),



              //NotificationController Page
              array('name' => 'cms-notification-view','controller'=>'NotificationController'),

              //SmsController Page
              array('name' => 'cms-sms-view','controller'=>'SmsController'),

              //ReasonController Page
              array('name' => 'cms-reason-view','controller'=>'ReasonController'),

              //CategoryController Page
              array('name' => 'category-view','controller'=>'CategoryController'),
              array('name' => 'category-add','controller'=>'CategoryController'),
              array('name' => 'variant-view','controller'=>'CategoryController'),
              array('name' => 'variant-add','controller'=>'CategoryController'),
              array('name' => 'brand-view','controller'=>'CategoryController'),
              array('name' => 'brand-add','controller'=>'CategoryController'),
              array('name' => 'tags-view','controller'=>'CategoryController'),
              array('name' => 'tags-add','controller'=>'CategoryController'),


              //ClientPreferenceController Page
              array('name' => 'configuration-view','controller'=>'ClientPreferenceController'),
              array('name' => 'configuration-add','controller'=>'ClientPreferenceController'),


              //TaxController Page
              array('name' => 'tax-view','controller'=>'TaxController'),
              array('name' => 'tax-add','controller'=>'TaxController'),


              //PaymentOption Page
              array('name' => 'payment-option-view','controller'=>'PaymentOptionController'),
              array('name' => 'payment-option-add','controller'=>'PaymentOptionController'),


              //DeliveryOption Page
              array('name' => 'delivery-option-view','controller'=>'DeliveryOptionController'),
              array('name' => 'delivery-option-add','controller'=>'DeliveryOptionController'),

               //Banners Page
               array('name' => 'banner-option-view','controller'=>'BannerController'),
               array('name' => 'banner-option-add','controller'=>'BannerController'),


                //PromocodeController Page
                array('name' => 'promo-code-view','controller'=>'PromocodeController'),
                array('name' => 'promo-code-add','controller'=>'PromocodeController'),


                //LoyaltyController Page
                array('name' => 'loyalty-code-view','controller'=>'LoyaltyController'),
                array('name' => 'loyalty-code-add','controller'=>'LoyaltyController'),

                //campaign Page
                array('name' => 'campaign-code-view','controller'=>'CampaignController'),
                array('name' => 'campaign-code-add','controller'=>'CampaignController'),


                //inquiry Page
                array('name' => 'inquiry-code-view','controller'=>'CampaignController'),


                //ToolsController Page
                array('name' => 'tool-view','controller'=>'ToolsController'),

                //database-logs Page
                array('name' => 'database-log-view','controller'=>'ToolsController'),


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
