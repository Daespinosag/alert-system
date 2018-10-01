<?php

namespace App\Http\Controllers\Auth;

use App\Entities\AlertSystem\User;
use App\Http\Controllers\Controller;
use App\Mail\ConfirmationEmailUser;
use App\Repositories\AlertSystem\PermissionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Repositories\AlertSystem\UserPermissionRepository;
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
     * @var UserPermissionRepository
     */
    private $userPermissionRepository;
    /**
     * @var PermissionRepository
     */
    private $permissionRepository;

    /**
     * Create a new controller instance.
     *
     * @param UserPermissionRepository $userPermissionRepository
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(
        UserPermissionRepository $userPermissionRepository,
        PermissionRepository $permissionRepository
    )
    {
        $this->middleware('guest');

        $this->userPermissionRepository = $userPermissionRepository;
        $this->permissionRepository = $permissionRepository;
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
            'flood'             => '',
            'landslide'         => '',
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
        $data['confirmed_code'] = sha1(time());

        $user  = User::create([
            'role_id'           => 3, # hace referencia al rol consultor
            'name'              => $data['name'],
            'institution'       => $data['institution'],
            'email'             => $data['email'],
            'confirmed_code'    => $data['confirmed_code'],
            'password'          => bcrypt($data['password']),
        ]);


        $this->userPermissionRepository->assignPermissionUser(
            ($this->permissionRepository->getPermissionFromCode('permission-a10'))->id,
            $user->id,
            array_key_exists('flood',$data),
            array_key_exists('flood',$data)
        );

        $this->userPermissionRepository->assignPermissionUser(
            ($this->permissionRepository->getPermissionFromCode('permission-a25'))->id,
            $user->id,
            array_key_exists('landslide',$data),
            array_key_exists('landslide',$data)
        );

        Mail::to($data['email'])->send(new ConfirmationEmailUser($data['confirmed_code'],$data['name']));

        return $user;
    }

    /**
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();

        return redirect('/login')->with('status', 'Le enviamos un código de activación. Verifique su correo electrónico y haga clic en el enlace para verificar.');
    }
}
