<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\AlertSystem\UserRepository;
use function Couchbase\defaultDecoder;

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

        $this->userRepository->confirmedUser($user);

        return redirect('/')->with('notification', 'Has confirmado correctamente tu correo!');
    }
}