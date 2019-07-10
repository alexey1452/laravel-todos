<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Tasks tasks
 * @property integer id
 */


class Card extends Model
{

    protected $fillable = [
        'name',
        'user_id',
        'id',
        'description',
        'image_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $with = [
        'tasks'
    ];


    public function tasks()
    {
        return  $this->hasMany(Tasks::class, 'card_id');
    }
}
