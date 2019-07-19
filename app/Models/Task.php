<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Task extends Model
{
    protected $fillable = [
        'title',
        'card_id',
        'completed',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected function card()
    {
        return  $this->belongsTo(Card::class);
    }

    public function getTasksByCardId($cardId)
    {
        return $this->where('card_id', $cardId)->get()->toArray();
    }

    public function getTaskById($id)
    {
        return $this->where('id', $id)->first();
    }
}
