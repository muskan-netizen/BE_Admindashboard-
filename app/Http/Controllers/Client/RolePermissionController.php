<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    

    function __construct()
    {
        //  $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        //  $this->middleware('permission:role-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    


    public function indexRole(Request $request)
    {
        $roles = Role::where('id','>','2')->orderBy('id','ASC')->get();
        // $users = User::where('status', 1)->get();
        return view('backend/role_permission/index',compact('roles'));
    }

    public function saveRole(Request $request)
    {    
        $this->validate($request, [
            'role_name' => 'required|unique:main_roles,name,'.$request->id
        ]);

            if(empty($request->id))
            {
                $role = Role::create(['name' => $request->input('role_name')]);

            }else{

                $role = Role::findOrFail($request->id);
                // dd($role);
                $role->update(['name'=>$request->input('role_name')]);
                return redirect()->back()->withSuccess('Role Updated.');
            }
            return redirect()->back()->withSuccess('Role Created.');
    }


    public function indexPermission(Request $request)
    {
         // $role = Role::create(['name' => 'Super Admin']);
        // $permission = Permission::create(['name' => 'All Pages articles']);
        // $role = Role::first();
        // $permission = Permission::first();
        // $role->givePermissionTo($permission);
        // $permission->assignRole($role);

        // $user = auth()->user();
        // dd($user);

        $permissions = Permission::orderBy('id','ASC')->get();
        return view('backend/role_permission/permission',compact('permissions'));
    }
    
    public function savePermission(Request $request)
    {    
            $val = $this->validate($request, [
                'permission_name' => 'required|unique:main_permissions,name'
            ]);

        
            $role = Permission::create(['name' => $request->input('permission_name')]);
            return redirect()->back()->withSuccess('Permission Created.');
    }

}
