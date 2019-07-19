<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /** @var User $user */
        $user = $id === 'me' ? Auth::user() : User::find($id);

        if(!$user) {
            return $this->resourceNotFound();
        }

        return $this->successApiResponse($user);
    }

    /**
     * Update the specified resource in storage.
     * @param UserUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, $id)
    {
        /** @var User $user */
        $user = $id === 'me' ? Auth::user() : User::find($id);
        $updatedUser = $request->only('first_name', 'last_name', 'email');

        if(!empty($request->input('password'))){
            $updatedUser['password'] = Hash::make($request->input('password'));
        }

        $user->fill($updatedUser);
        if($user->save()) {
            return $this->successApiResponse($user);
        }
        
        return $this->errorApiResponse($user);
    }

}
