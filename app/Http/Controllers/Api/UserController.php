<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserUpdateRequest as UserUpdateRequestAlias;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

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

        if(!$user){
            return $this->resourceNotFound();
        }

        return $this->successApiResponse(['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     * @param UserUpdateRequestAlias $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequestAlias $request, $id)
    {
        /** @var User $user */
        $user = Auth::user();
        $updatedData = $request->all();

        if(!$user) {
            return $this->resourceNotFound();
        }

        if(!empty($updatedData['password'])){
            $updatedData['password'] = Hash::make($updatedData['password']);
        }

        $user->fill($updatedData);
        $user->save();
        
        return $this->successApiResponse([ 'user' => $user]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
