<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Task tasks
 * @property integer id
 * @property string title
 * @property string description
 */

class Card extends Model
{

    protected $fillable = [
        'title',
        'user_id',
        'description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public function tasks()
    {
        return  $this->hasMany(Task::class);
    }

    public function getCardsByUserId($userId)
    {
        return $this->whereUserId($userId)->get()->toArray();
    }

    public function getCardById($id)
    {
        return $this->where('id', $id)->with('tasks')->first();
    }

}
