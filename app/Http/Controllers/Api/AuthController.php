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
        $data = $request->only('email', 'first_name', 'last_name');
        $data['password'] = Hash::make($request->get('password'));
        $data['confirmation_token'] = Str::uuid();
        $user = new User();
        $user->fill($data);
        if($user->save()){
            dispatch(new SendUserRegisterEmail($user));
            return $this->successApiResponse();
        }

        return $this->errorApiResponse();
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

            if(!$user->confirmed_at){
                return $this->unauthorizedApiResponse('Not verified email');
            }

            $token = $user->createToken(config('app.name'))->accessToken;

            $success['token'] = $token;
            $success['user'] = $user;

            return $this->successApiResponse($success, 'Success');
        } else {
            return $this->unauthorizedApiResponse('Wrong data');
        }
    }

    public function verifyUser($code)
    {
        /** @var User $user */
        if($user = User::confirmationUser($code)){
            return $this->successApiResponse();
        }

        return $this->resourceNotFound();
    }
}
