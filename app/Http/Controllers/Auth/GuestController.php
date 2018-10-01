<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmationEmailUser;
use App\Repositories\AlertSystem\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class GuestController extends Controller
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * GuestController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $code
     * @return mixed
     */
    public function verify(string $code)
    {
        $user = $this->userRepository->getUserFromConfirmationCode($code);

        if (is_null($user)){return redirect('/register'); }

        $user = $this->userRepository->confirmedUser($user);

        if (!$user->accepted){ return redirect('/login')->with('status', 'Actualmente se encuentra en lista para ser aceptado'); }

        Auth::login($user);

        return redirect('/');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reConfirmationIndex()
    {
        return view('auth.passwords.reConfirmationEmail');
    }

    public function reConfirmationSendEmail(Request $request)
    {
        $email = $request->get('email');

        if (!$this->userRepository->validateEmailExistence($email)){
            return redirect('reConfirmation/index')->with('warning', 'correo electrónico no encontrado');
        }

        $code = sha1(time());
        $result = $this->userRepository->updateFromEmail($email,['confirmed_code' => $code, 'confirmed' => false ]);

        $user = $this->userRepository->getUser($result);

        Mail::to($email)->send(new ConfirmationEmailUser($code,$user->name));

        return redirect('/login')->with('status', 'Le enviamos un código de activación. Verifique su correo electrónico y haga clic en el enlace para verificar.');
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
    }

}