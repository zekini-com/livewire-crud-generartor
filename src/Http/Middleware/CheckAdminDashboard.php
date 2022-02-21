<?php

namespace Zekini\CrudGenerator\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Config\Repository as Config;

/**
 * Class CanAdmin
 *
 * @package Zekini\CrudGenerator\Http\Middleware
 */
class CheckAdminDashboard
{

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $roles = collect(config('zekini-admin.admin_roles'))->pluck('name')->toArray();
    
        if($request->is('dashboard')) {
            return redirect('/admin');
        }

        return $next($request);
       
    }
}
