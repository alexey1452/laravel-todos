<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Jobs\SendUserRegisterEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(RegisterRequest $request)
    {
        $data = $request;
        $data['password'] = Hash::make($request['password']);
        $data['confirmed_token'] = Str::random(20);
        $user = User::create($data->toArray());
        if($user){
            dispatch(new SendUserRegisterEmail($user));
        }
        return $this->successApiResponse();
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(LoginRequest $request)
    {
        $data = $request->only('email', 'password');

        if(Auth::attempt($data)){
            /** @var User $user */
            $user = Auth::user();

            if($user->confirmed_token){
                return $this->unauthorizedApiResponse('not verify email');
            }

            $tokenResult = $user->createToken(config('app.name'));
            $token = $tokenResult->token;
            $token->expires_at = now()->addDays(config('token_lifetime'));
            $token->save();

            $success['token'] = $tokenResult->accessToken;
            $success['expires_at'] = $token->expires_at->toDateString();
            $success['user'] = $user;

            return $this->successApiResponse($success, 'Success');
        } else {
            return $this->unauthorizedApiResponse('Wrong data');
        }
    }

    public function verifyUser($code)
    {
        /** @var User $user */
        $user = User::where('confirmed_token', $code)->firstOrFail();
        $user->update(['confirmed_token' => null]);

        return $this->successApiResponse();

    }
}
