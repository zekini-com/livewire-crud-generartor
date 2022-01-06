<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zekini\CrudGenerator\Traits\AuthenticatesUsers;
/**
 * @psalm-suppress UndefinedClass
 */
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
     * @return \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\View\View
     */
    public function showLoginForm()
    {
        return view('zekini/livewire-crud-generator::admin.auth.login');
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
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
