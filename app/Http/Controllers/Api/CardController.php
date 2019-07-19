<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\{CardCreateRequest, CardUpdateRequest};
use App\Models\Card;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * @param Request $request
     * @param Card $card
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Card $card )
    {
        $cards = $card->getCardsByUserId($request->only('user_id'));

        return $this->successApiResponse($cards);
    }

    /**
     * @param CardCreateRequest $request
     * @param Card $card
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CardCreateRequest $request, Card $card)
    {
        $card->fill($request->only('user_id', 'title', 'description'));

        if ($card->save()) {
            return $this->successApiResponse($card, 'Card successful created');
        }

        return $this->errorApiResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Card $card
     * @return \Illuminate\Http\Response
     */
    public function show($id, Card $card)
    {
        $currentCard = $card->getCardById($id);

        if(!$currentCard) {
            return $this->resourceNotFound();
        }

        return $this->successApiResponse($currentCard);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CardUpdateRequest $request
     * @param int $id
     * @param Card $card
     * @return \Illuminate\Http\Response
     */
    public function update(CardUpdateRequest $request, $id, Card $card)
    {
        $card = $card->getCardById($id);

        if(!$card) {
            return $this->resourceNotFound();
        }

        $card->fill($request->only('title', 'description'))->save();

        return $this->successApiResponse($card, 'Success update');

    }


    /**
     * @param $id
     * @param Card $card
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Card $card)
    {
        $removableCard = $card->getCardById($id);

        if(!$removableCard){
            return $this->resourceNotFound();
        }
        $removableCard->delete();

        return $this->successApiResponse();
    }
}
