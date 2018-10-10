<?php

namespace App\Http\Controllers\AlertSystem;

use App\Http\Controllers\Controller;
use App\Repositories\AlertSystem\UserRepository;
use Illuminate\Support\Facades\Auth;

class AlertSystemController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * AlertSystemController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return $this
     */
    public function loadVueLayout()
    {
         return view('test')->with('user', (!is_null( Auth::user())) ? $this->userRepository->getCompleteUser(Auth::user()->id)->toArray() : null);
    }
}