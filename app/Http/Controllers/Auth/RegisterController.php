<?php

namespace App\Http\Controllers\Auth;

use App\Entities\AlertSystem\User;
use App\Http\Controllers\Controller;
use App\Mail\ConfirmationEmailUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;

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

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
            'name'              => 'required|string|max:255',
            'institution'       => '',
            'email'             => 'required|string|email|max:255|unique:users',
            'password'          => 'required|string|min:6|confirmed',
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
        $data['confirmed_code'] = str_random(25);

        $user  = User::create([
            'role_id'           => 3, # hace referencia al rol consultor
            'name'              => $data['name'],
            'institution'       => $data['institution'],
            'email'             => $data['email'],
            'confirmed_code'    => $data['confirmed_code'],
            'password'          => bcrypt($data['password']),
        ]);

        Mail::to($data['email'])->send(new ConfirmationEmailUser($data['confirmed_code'],$data['name']));

        return $user;

    }
}
