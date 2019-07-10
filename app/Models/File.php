<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer owner_id
 * @property integer id
 * @property string url
 * @property string mime_type
 * @property string name_file
 */

class File extends Model
{
   protected $fillable = [
       'owner_id',
       'url',
       'mime_type',
       'name_file'
   ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];


    public function delete()
    {
        @unlink(public_path("/files/$this->name_file"));
        return parent::delete();
    }
}
