<?php

namespace App\Http\Controllers\Api;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File as Storage;
use Illuminate\Support\Str;
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
            return $this->invalidData();
        }

        /** @var User $user */
        $user = Auth::user();
        $file = $request->file('file');

        if(!empty($user->avatar)) {
            $user->removeAvatar();
        }

        $image = Image::make($file)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            });

        $mimeType = $this->convertMimeType($image->mime());
        $filename = Str::random() . $mimeType;

        $path = public_path('files');

        if(!Storage::isDirectory($path)) {
            Storage::makeDirectory($path, 0777, true, true);
        }

        if($image->save(public_path("/files/$filename"))) {
            /** @var File $avatar */
            $avatar = new File();
            $avatar->fill([
                'user_id' => $user->id,
                'mime_type' => $image->mime(),
                'filename' => $filename
            ]);
            $avatar->save();

            return $this->successApiResponse($avatar);
        }

        return $this->errorApiResponse();
    }


    /**
     * @param $fileId
     * @param File $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFile($fileId, File $file){
        /** @var File $file */
        $removableFile = $file->getFileById($fileId);

        if($removableFile){
            $removableFile->delete();
            return $this->successApiResponse();
        }

        return $this->resourceNotFound();

    }

    public function convertMimeType ($mimeType) {
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

