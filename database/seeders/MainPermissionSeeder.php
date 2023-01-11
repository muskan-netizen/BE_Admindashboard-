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
            array('id' => '1','name' => 'dashboard-weekRevenue','controller'=>'DashboardController'),
            array('id' => '2','name' => 'dashboard-locationRevenue','controller'=>'DashboardController'),
            array('id' => '3','name' => 'dashboard-monthRevenue','controller'=>'DashboardController'),
            array('id' => '4','name' => 'dashboard-totalRevenue','controller'=>'DashboardController'),
            array('id' => '5','name' => 'order-list','controller'=>'OrderController'),
            array('id' => '6','name' => 'order-update','controller'=>'OrderController')
        );

        // // $permissions = [
        //     'dashboard-weekRevenue','dashboard-locationRevenue','dashboard-monthRevenue','dashboard-totalRevenue','order-list'];

        foreach ($permissions as $key=> $permission) {
           $permissions_array[]=array(
            'id'    => $permission['id'],
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
                $payop = Permission::where('id', $permission['id'])->first();
  
                if ($payop !== null) {
                    $payop->update(['name' => $permission['name']]);
                } else {
                    // dd('ddd');
                    $payop = Permission::create([
                        'id'    => $permission['id'],
                        'name' => $permission['name'],
                        'controller' => $permission['controller'],
                        'guard_name' => 'web',
                    ]);
                }
            }

        }
    }
}
