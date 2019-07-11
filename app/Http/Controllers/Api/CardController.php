<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CardRequestCreate;
use App\Models\Card;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();
        $cards = Card::query()
                    ->where('user_id',  $userId)
                    ->get()
                    ->toArray();

        return $this->successApiResponse($cards);
    }


    /**
     * @param CardRequestCreate $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CardRequestCreate $request)
    {
            $userId = Auth::id();
            $newCardData = $request->all();
            $newCardData['user_id'] = $userId;
            $card = Card::create($newCardData);

            return $this->successApiResponse($card, 'Card success created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = Card::where('id', $id)->firstOrFail();

        return $this->successApiResponse($card);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CardRequestCreate  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CardRequestCreate $request, $id)
    {
        $card = Card::where('id', $id)->firstOrFail();
        $card->update($request->all());

        return $this->successApiResponse($card, 'Success update');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $card->delete();

        return $this->successApiResponse();
    }
}
