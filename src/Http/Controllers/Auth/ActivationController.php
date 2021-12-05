<?php

namespace Zekini\CrudGenerator\Http\Controllers\Auth;

use Zekini\CrudGenerator\Activation\Contracts\ActivationBroker as ActivationBrokerContract;
use Zekini\CrudGenerator\Activation\Contracts\CanActivate as CanActivateContract;
use Zekini\CrudGenerator\Activation\Facades\Activation;
use Zekini\CrudGenerator\Http\Controllers\Controller;
use Zekini\CrudGenerator\Traits\RedirectsUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ActivationController extends Controller
{
    //use RedirectsUsers;

    /*
    |--------------------------------------------------------------------------
    | Activation Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling activation requests.
    |
    */

    /**
     * Guard used for admin user
     *
     * @var string
     */
    protected $guard = 'admin';

    /**
     * Where to redirect users after activating their accounts.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Activation broker used for admin user
     *
     * @var string
     */
    protected $activationBroker = 'admin_users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->guard = config('zekini-admin.defaults.guard');
        $this->activationBroker = config('zekini-admin.defaults.activations');
        $this->redirectTo = config('zekini-admin.activation_redirect');
        $this->middleware('guest.admin:' . $this->guard);
    }

    /**
     * Activate user from token
     *
     * @param Request $request
     * @param mixed $token
     * @throws ValidationException
     * @return RedirectResponse
     */
    public function activate(Request $request, $token)
    {
        if (!config('zekini-admin.activation_enabled')) {
            return $this->sendActivationFailedResponse($request, Activation::ACTIVATION_DISABLED);
        }

        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        // Here we will attempt to activate the user's account. If it is successful we
        // will update the activation flag on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->activate(
            $this->credentials($request, $token),
            function ($user) {
                $this->activateUser($user);
            }
        );

        // If the activation was successful, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response === Activation::ACTIVATED
            ? $this->sendActivationResponse($request, $response)
            : $this->sendActivationFailedResponse($request, $response);
    }

    /**
     * Get the activation validation rules.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * Get the activation validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages(): array
    {
        return [];
    }

    /**
     * Get the activation credentials from the request.
     *
     * @param Request $request
     * @param $token
     * @return array
     */
    protected function credentials(Request $request, $token): array
    {
        return ['token' => $token];
    }

    /**
     * Activate the given user account.
     *
     * @param CanActivateContract $user
     * @return void
     */
    protected function activateUser(CanActivateContract $user): void
    {
        $user->forceFill([
            'activated' => true,
        ])->save();
    }

    /**
     * Get the response for a successful activation.
     *
     * @param Request $request
     * @param string $response
     * @return RedirectResponse
     */
    protected function sendActivationResponse(Request $request, $response)
    {
        $message = trans($response);
        if ($response === Activation::ACTIVATED) {
            $message = trans('brackets/zekini-admin::admin.activations.activated');
        }
        return redirect($this->redirectPath())
            ->with('status', $message);
    }

    /**
     * Get the response for a failed activation.
     *
     * @param Request
     * @param string $response
     * @param Request $request
     * @return RedirectResponse
     */
    protected function sendActivationFailedResponse(Request $request, string $response)
    {
        $message = trans($response);
        if ($response === Activation::INVALID_USER || $response === Activation::INVALID_TOKEN) {
            $message = trans('brackets/zekini-admin::admin.activations.invalid_request');
        } else {
            if (Activation::ACTIVATION_DISABLED) {
                $message = trans('brackets/zekini-admin::admin.activations.disabled');
            }
        }
        if (config('zekini-admin.self_activation_form_enabled')) {
            return redirect(route('brackets/zekini-admin::admin/activation'))
                ->withInput($request->only('email'))
                ->withErrors(['token' => $message]);
        } else {
            return view('brackets/zekini-admin::admin.auth.activation.error')->withErrors(
                ['token' => $message]
            );
        }
    }

    /**
     * Get the broker to be used during activation.
     *
     * @return ActivationBrokerContract
     */
    public function broker(): ?ActivationBrokerContract
    {
        return Activation::broker($this->activationBroker);
    }
}
