<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponses
{
    protected function success($data, $message = null, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
    protected function error($data, $message = null, $code = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function notFound($message = null, $code = 404): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'success' => false,
            'message' => $message ?? 'Item not found',
            'data' => null,
        ], $code);
    }
}
