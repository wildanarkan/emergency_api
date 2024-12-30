<?php

namespace App\Helper;

/**
 * Format response.
 */
class ResponseFormatter
{
    /**
     * Format success response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($message = '', $data = [], $code = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Format error response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message = '', $data = [], $code = 400)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
