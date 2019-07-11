<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * @property File avatar
 * @property integer id
 * @property string first_name
 * @property string last_name
 * @property string email
 * @property integer avatar_id
 * @property string confirmed_token
 */

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar_id',
        'confirmed_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'confirmed_token',
        'updated_at',
        'created_at',
        'confirmed_email',
        'avatar_id'
    ];

    protected $appends = [
        'full_name'
    ];

    protected $with = [
        'avatar'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar->url;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function avatar()
    {
        return $this->hasOne( File::class, 'id', 'avatar_id');
    }

    public function removeOldAvatar()
    {
        $this->avatar->delete();
    }

    public function verify()
    {
        return $this->update(['confirmed_token' => null]);
    }

}
