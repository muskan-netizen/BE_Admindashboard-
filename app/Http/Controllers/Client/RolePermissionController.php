<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    
    public function indexRole(Request $request)
    {
        $roles = Role::with('permissions');
        if(auth()->user()->is_superadmin != 1){
            $roles =$roles->where('id','>','4');
        }
        $roles =$roles->orderBy('id','ASC')->get();
        $permissions = Permission::get();
        return view('backend/role_permission/index',compact('roles','permissions'));
    }

    public function getRole(Request $request,$id)
    {
        $roles = Role::with('permissions')->findOrFail($request->id);
        $permissions = Permission::get();
        $select ='';
        $selected = "";
        foreach($permissions as $perm)
        {
            if(in_array($perm->id,$roles->permissions->pluck('id')->toArray()))
            {
                $selected = 'Selected';
            }else{
                $selected = '';
            }
            $select .="<option value='{$perm->id}' {$selected}>$perm->name</option>";
        }

        return response()->json(['select'=>$select,'role'=>$roles]);
    }

    public function saveRole(Request $request)
    {    
        $this->validate($request, [
            'role_name' => 'required|unique:main_roles,name,'.$request->id
        ]);

            if(empty($request->id))
            {
                $role = Role::create(['name' => $request->input('role_name')]);
                if(@$request->permission && count($request->permission)>0){
                    //Assign all selected permisson to role
                    $role->syncPermissions($request->input('permission'));
                }else{
                    //Revoke all permisson from role
                    $role->syncPermissions();
                }

            }else{

                $role = Role::findOrFail($request->id);
                
                if(@$request->permission &&  count($request->permission)>0){
                    //Assign all selected permisson to role
                    $role->syncPermissions($request->input('permission'));
                }else{
                    //Revoke all permisson from role
                    $role->syncPermissions();
                }
                
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
