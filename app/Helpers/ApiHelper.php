<?php

namespace App\Helpers;

class ApiHelper
{
    public static function responseWithSuccess($message, $data=null, $token=null)
    {
        if ($token) {
            return response()->json([
                'success'=>true,
                'status_code' => 200,
                'message' => $message,
                'token' => $token,
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'success'=>true,
                'status_code' => 200,
                'message' => $message,
                'data' => $data
            ], 200);
        }
    }

    public static function responseWithUnauthorized($message="Unauthorized")
    {
        return response()->json([
            'success'=>false,
            'status_code' => 401,
            'message' => $message,
            'data' => null
        ], 401);
    }

    public static function responseWithNotFound($message="not found")
    {
        return response()->json([
            'success'=>false,
            'status_code' => 404,
            'message' => $message,
            'data' => null
        ], 404);
    }

    public static function responseWithBadRequest($message)
    {
        return response()->json([
            'success'=>false,
            'status_code' => 400,
            'message' => $message,
            'data' => null
        ], 400);
    }

    public static function serverError($message)
    {
        return response()->json([
            'success'=>false,
            'status_code' => 500,
            'message' => $message,

        ], 500);
    }

}
