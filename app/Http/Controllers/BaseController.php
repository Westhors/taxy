<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    protected mixed $crudRepository;

    public function toggle($id, $column): JsonResponse
    {
        $item = $this->crudRepository->find($id);

        if ($item) {
            $item->$column = !$item->$column;
            $item->save();

            return response()->json(['message' => 'Status Changed successfully']);
        }

        return response()->json(['Error' => 'Item does not exist'], 404);
    }
}
