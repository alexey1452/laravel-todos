<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File as Files;

/**
 * @property integer owner_id
 * @property integer id
 * @property string url
 * @property string mime_type
 * @property string filename
 */

class File extends Model
{

   public $primarykey = 'id';

   protected $fillable = [
       'user_id',
       'mime_type',
       'filename'
   ];

   protected $hidden = [
       'created_at',
       'updated_at'
   ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


    public function delete()
    {
        Files::delete(public_path("/files/$this->filename"));
        parent::delete();
        return true;
    }

    public function getFileById($id)
    {
        return File::find($id);
    }
}
