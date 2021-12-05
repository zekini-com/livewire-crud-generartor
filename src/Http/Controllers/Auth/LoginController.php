<?php

namespace Zekini\CrudGenerator\Http\Controllers\Auth;

use Zekini\CrudGenerator\Http\Controllers\Controller;
use Zekini\CrudGenerator\Traits\AuthenticatesUsers;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Where to redirect users after logout.
     *
     * @var string
     */
    protected $redirectToAfterLogout = '/admin/login';

    /**
     * Guard used for admin user
     *
     * @var string
     */
    protected $guard = 'zekini-admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->guard = config('zekini-admin.defaults.guard');
        $this->redirectTo = config('zekini-admin.auth_routes.login_redirect');
        $this->redirectToAfterLogout = config('zekini-admin.auth_routes.logout_redirect');
    }

    /**
     * Show the application's login form.
     *
     * @return Response
     */
    public function showLoginForm()
    {
        return view('zekini/livewire-crud-generator::admin.auth.login');
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect($this->redirectToAfterLogout);
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectAfterLogoutPath(): string
    {
        if (method_exists($this, 'redirectToAfterLogout')) {
            return $this->redirectToAfterLogout();
        }

        return property_exists($this, 'redirectToAfterLogout') ? $this->redirectToAfterLogout : '/';
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard($this->guard);
    }
}
