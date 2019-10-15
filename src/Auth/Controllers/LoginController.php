<?php

namespace Compredict\User\Auth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }


    protected function sendFailedLoginResponse(Request $request)
    {
        $response = \CP_User::getLastError();

        if (isset($response->errors)) {
            $message = explode(':', $response->errors[0]);
            $message = $message[sizeof($message) - 1];
        } elseif (isset($response->error)) {
            $message = $response->error;
        } elseif (is_string($response)) {
            $message = $response;
        } else {
            $message = 'Something went wrong please try again!';
        }

        throw ValidationException::withMessages([
            $this->username() => [$message],
        ]);
    }

}
