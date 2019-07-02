<?php

namespace Compredict\User\Auth\Controllers;

use App\Http\Controllers\Controller;
use Compredict\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RedirectsUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:63'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password1' => ['required', 'string', 'min:8', 'same:password2'],
            'password2' => ['required', 'string', 'min:8'],
            'first_name' => ['nullable', 'string'],
            'last_name' => ['nullable', 'string'],
            'organization' => ['required', 'string'],
            'phone_number' => ['nullable', ' numeric'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        try {
            return User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password1' => $data['password1'],
                'password2' => $data['password2'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'organization' => $data['organization'],
                'phone_number' => $data['phone_number'],
            ]);
        } catch (\Exception $exception) {
            $errors = explode("|||", $exception->getMessage());
            $messages = [];
            foreach ($errors as $error) {
                $error_split = explode(" : ", $error);
                $messages[$error_split[0]] = $error_split[1];
            }
            return \Redirect::back()->withErrors($messages)->withInput();
        }
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());

        if ($user instanceof \Illuminate\Http\RedirectResponse) {
            return $user;
        }

        event(new Registered($user));

        $this->guard()->login($user);

        return $this->registered($request, $user)
        ?: redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}
