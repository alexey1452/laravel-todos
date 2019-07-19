<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successApiResponse($data = [], $message = 'Success', $code = 201)
    {

        $responseData = [
            'requestStatus' => 'Success',
            'message' => $message
        ];

        if($data) {
            $responseData['data'] = $data;
        }

        return response()->json($responseData, $code);
    }

    public function unauthorizedApiResponse($errors = [], $message = 'Unauthorized user', $code = 401)
    {

        $responseData = [
            'requestStatus' => 'unauthorized',
            'message' => $message,
            'errors' => $errors
        ];

        return response()->json($responseData, $code);
    }

    public function errorApiResponse($message = 'Error', $code = null)
    {
        if(!$code){
            $code = 500;
        }

        $responseData = [
            'code' => $code,
            'message' => $message,
        ];

        return response()->json($responseData, $code);
    }

    public function resourceNotFound($message = 'Resource not found', $code = 404)
    {

        $responseData = [
            'requestStatus' => 'not found',
            'message' => $message
        ];

        return response()->json($responseData, $code);
    }

    public function invalidData($message = 'Bad request', $code = 422)
    {

        $responseData = [
            'requestStatus' => 'invalid data',
            'message' => $message
        ];

        return response()->json($responseData, $code);
    }
}
