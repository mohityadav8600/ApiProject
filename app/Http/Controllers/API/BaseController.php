<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function sendResponse($result, $message): JsonResponse
    {
        $responce=[
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($responce, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}

