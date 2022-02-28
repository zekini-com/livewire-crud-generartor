<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller; /** @phpstan-ignore-line */

/**
 * @psalm-suppress UndefinedClass
 */
class LoginController extends Controller /** @phpstan-ignore-line */
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



    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Contracts\View\Factory | \Illuminate\Contracts\View\View
     */
    public function showLoginForm()
    {
        return view('zekini/livewire-crud-generator::admin.auth.login');
    }

}
