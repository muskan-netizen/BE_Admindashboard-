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

                $checkPermissionEnable = @getAdditionalPreference(['is_role_and_permission_enable'])['is_role_and_permission_enable'];
                if($checkPermissionEnable)
                {
                        $guard = $guard ?? config('auth.defaults.guard');
                        $user = auth()->user();
                        \Log::info(['guard' => $guard]);
                        $authGuard = app('auth')->guard($guard);
                        $permissionArray = $this->permissionUser($user);
                        \Log::info(['permissions' => $permissionArray]);
                        $page = $request->route()->action['controller'];
                        $check = explode('\\',$page);
                        $cnt = count($check);
                        $pageUrl = $check[$cnt-1];
                        $check = explode('@',$pageUrl);
                        $page = $check[0];
                        \Log::info($page);
                        $permissions = [];
                        if(isset($permissionArray[$page]) && count($permissionArray[$page])>0)
                        {
                            $permissions =  $permissionArray[$check[0]];
                        }else{
                            if(@$user->is_superadmin || @$user->is_admin){
                                return $next($request);
                            }

                            throw UnauthorizedException::forPermissions($permissions);
                        }
                        \Log::info(['permissions-new' => $permissions]);

                        foreach ($permissions as $permission) {
                            \Log::info($authGuard->user()->can($permission));
                            if ($authGuard->user()->checkPermissionTo($permission, $guard)) {
                                return $next($request);
                            }
                        }

                        throw UnauthorizedException::forPermissions($permissions);
                }else{
                return $next($request);
            }
    }


    public function permissionUser($user)
    {
        $permissionArray = array();
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $key=> $perm) {
                $permissionArray[$perm->controller][] = $perm->name;
            }
        }
        return $permissionArray;
    }

}
