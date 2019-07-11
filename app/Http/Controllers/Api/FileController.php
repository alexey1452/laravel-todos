<?php

namespace App\Http\Controllers\Api;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class FileController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(Request $request)
    {
        if(!$request->hasFile('file')) {
            return $this->resourceNotFound();
        }

        /** @var User $user */
        $user = Auth::user();
        $file = $request->file('file');

        if($user->avatar_id) {
            $user->removeOldAvatar();
        }

        $image = Image::make($file)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            });

        $mimeType = $this->checkMimeType($image->mime());
        $fileName = str_random(20) . $mimeType;

        if($image->save(public_path("/files/$fileName"))) {
            /** @var File $avatar */
            $avatar = File::create([
                    'owner_id' => $user->id,
                    'url' => "/files/$fileName",
                    'mime_type' => $image->mime(),
                    'name_file' => $fileName
                ]);
            $user->avatar_id = $avatar->id;
            $user->save();
            return $this->successApiResponse();
        }

        return $this->errorApiResponse();
    }


    /**
     * @param $fileId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deleteFile($fileId){
        /** @var User $user */
        $user = Auth::user();
        /** @var File $file */
        $file = File::find($fileId);

        if(!$file){
            return $this->resourceNotFound();
        }

        if($file->delete()){
            $user->avatar_id = null;
            $user->save();

            return $this->successApiResponse();
        }

        return $this->errorApiResponse();

    }

    public function checkMimeType ($mimeType) {
        switch ($mimeType) {
            case 'image/jpeg':
                return '.jpg';
            case 'image/png':
                return '.png';
            default:
                return '';
        }
    }
}

