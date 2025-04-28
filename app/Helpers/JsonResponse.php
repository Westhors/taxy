<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class JsonResponse
{
    const MSG_ADDED_SUCCESSFULLY = 'Item has been added successfully';
    const MSG_UPDATED_SUCCESSFULLY = "Item has been updated successfully";
    const MSG_DELETED_SUCCESSFULLY = "Item has been deleted successfully";
    const MSG_FORCE_DELETED_SUCCESSFULLY = "Item has been deleted successfully from database";
    const MSG_NOT_FOUND = "Item cannot be found";
    const MSG_EMAIL_SENDING = "Email has Been Sent Successfully";
    const MSG_SUCCESS = "success";
    const MSG_FAILED = "error";
    const MSG_RESTORED_SUCCESSFULLY = "Item has been successfully restored";
    const MSG_CANNOT_DELETED = "This item cannot be deleted due to relationship with other resources";

    public static function respondSuccess($message, $content = null, $status = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'result' => trans(self::MSG_SUCCESS),
            'data' => $content,
            'message' => $message,
            'status' => $status
        ]);
    }

    public static function respondError($message, int $status = 500): \Illuminate\Http\JsonResponse
    {
        try {
            throw new Exception($message, $status);
        } catch (Throwable $e) {
            $errorLog = collect([
                'code' => $e->getCode(),
            ])->concat((array)collect($e->getTrace())->take(1))->toArray()[0];
            Log::channel('slack')->error($e->getMessage(), $errorLog);
        }

        return response()->json([
            'data' => null,
            'result' => trans(self::MSG_FAILED),
            'message' => $message,
            'status' => $status,
        ]);
    }

    public static function respondValidationError(ValidationException $e)
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors(),
            'status' => 422
        ], 422);
    }

    public static function downloadFile($url): BinaryFileResponse
    {
        return response()->download(public_path('storage/' . $url));
    }

    public static function success(): array
    {
        return ['result' => trans(self::MSG_SUCCESS), 'message' => trans(self::MSG_SUCCESS), 'status' => 200];
    }

    public static function savedSuccessfully(): array
    {
        return ['result' => trans(self::MSG_SUCCESS), 'message' => trans(self::MSG_ADDED_SUCCESSFULLY), 'status' => 200];
    }

    public static function updatedSuccessfully(): array
    {
        return ['result' => trans(self::MSG_SUCCESS), 'message' => trans(self::MSG_UPDATED_SUCCESSFULLY), 'status' => 200];
    }
}
