<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $permission = null, $guard = null)
    {

        $user = auth()->user();
        $authGuard = app('auth')->guard($guard);
        $permissionArray = $this->permissionUser($user);

            $page = $request->route()->action['controller'];
            $check = explode('\\',$page);
            $cnt = count($check);
            $pageUrl = $check[$cnt-1];
            $check = explode('@',$pageUrl);
            $page = $check[0];

            $permissions = [];
            if(isset($permissionArray[$page]) && count($permissionArray[$page])>0)
            {
                $permissions =  $permissionArray[$check[0]];
            }else{
                if(@$user->is_superadmin || @$user->is_admin){
                    return $next($request);
                }
            // dd($page);

                throw UnauthorizedException::forPermissions($permissions);
            }

        foreach ($permissions as $permission) {
            if ($authGuard->user()->can($permission)) {
                return $next($request);
            }
        }

        throw UnauthorizedException::forPermissions($permissions);
    }


    public function permissionUser($user)
    {
        
        // if(@$user->is_superadmin){
        //     //Assign all selected permisson to role
        //     $role = Role::first();
        //     $permissions = Permission::all();
        //     $role->syncPermissions('');
        // }

        $permissionArray = array();
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $key=> $perm) {
                $permissionArray[$perm->controller][] = $perm->name;
            }
        }
        return $permissionArray;
    }

}
