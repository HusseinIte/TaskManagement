<?php

namespace App\Http\Controllers;

use App\Service\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService=$authService;
    }

    public function login(Request $request){
        try{
            $success= $this->authService->login($request);
            return $this->sendResponse($success, 'User login successfully.');

        }catch(AuthenticationException $e){
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
    public function profile(){
        $success=$this->authService->profile();
        return $this->sendResponse($success, 'Refresh token return successfully.');
    }

    public function logout(){
        $this->authService->logout();
        return $this->sendResponse([], 'Successfully logged out.');
    }

    public function refresh(){
        $success=$this->authService->refresh();
        return $this->sendResponse($success, 'Refresh token return successfully.');

    }

}
