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
class CheckRole
{
    /**
     * Guard used for admin user
     *
     * @var string
     */
    protected $guard;

    /**
     * CanAdmin constructor.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;

        $this->guard = $this->config->get('zekini-admin.defaults.guard', 'web');
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {

        foreach($roles as $role) {
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        return redirect($this->config->get('zekini-admin.auth_routes.logout_redirect', '/login'));
       
    }
}
