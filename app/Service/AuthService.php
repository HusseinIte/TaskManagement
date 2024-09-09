<?php

namespace App\Service;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = Auth::attempt($credentials)) {
          throw new AuthenticationException();
        }
        $success = $this->respondWithToken($token);
        return $success;
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $success = Auth::user();
        return $success;
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();

    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        $success = $this->respondWithToken(Auth::refresh());
        return $success;
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ];
    }
}
