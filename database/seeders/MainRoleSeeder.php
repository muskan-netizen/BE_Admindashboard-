<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use DB;
class MainRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $countRoles = Role::count();
        if($countRoles==0)
        {
            \DB::table('main_roles')->delete();
            $maps = array(
                array(
                    'id' => 1,
                    'name' => 'Super Admin',
                    'guard_name'=>'web'
                ),
                array(
                    'id' => 2,
                    'name' => 'Admin',
                    'guard_name'=>'web'

                ),
                array(
                    'id' => 3,
                    'name' => 'Buyer',
                    'guard_name'=>'web'
                ),
                array(
                    'id' => 4,
                    'name' => 'Seller',
                    'guard_name'=>'web'

                )
            ); 
            \DB::table('main_roles')->insert($maps);
        }
    }
}
