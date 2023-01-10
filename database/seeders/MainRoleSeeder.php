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
                    'name' => 'Buyer',
                ),
                array(
                    'id' => 2,
                    'name' => 'Seller',
                ),
            ); 
            \DB::table('main_roles')->insert($maps);
        }
    }
}
