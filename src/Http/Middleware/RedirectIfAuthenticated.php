<?php

namespace Zekini\CrudGenerator\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class RedirectIfAuthenticated
 *
 * @package Zekini\CrudGenerator\Http\Middleware
 */
class RedirectIfAuthenticated
{
    /**
     * Guard used for admin user
     *
     * @var string
     */
    protected $guard = 'admin';

    /**
     * RedirectIfAuthenticated constructor.
     */
    public function __construct()
    {
        $this->guard = config('zekini-admin.defaults.guard');
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
       
        if (! Auth::guard($this->guard)->check()) {
            return redirect(config('zekini-admin.auth_routes.logout_redirect'));
        }

        return $next($request);
    }
}
