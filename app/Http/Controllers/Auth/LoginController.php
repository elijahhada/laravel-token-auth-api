<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function attemptLogin(Request $request)
    {
        $token = $this->guard()->attempt($this->credentials($request));

        if( ! $token){

            return false;
        }
        $user = $this->guard()->user();

        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            
            return false;
        }
        $this->guard()->setToken($token);

        return true;
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        $token = (string)$this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ]);
    }


    protected function sendFailedLoginResponse()
    {
        $user = $this->guard()->user();

        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "message" => "You need to verify your email account"
            ]], 422);
        }

        throw ValidationException::withMessages([
            $this->username() => "Invalid credentials"
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['message' => 'Logged out successfully!']);
    }
}
