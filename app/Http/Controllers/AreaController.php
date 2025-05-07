<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\Users\Orders\CalculatePriceRequest;
use App\Interfaces\AreaRepositoryInterface;
use App\Models\Area;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    use HttpResponses;

    protected $areaRepository;

    public function __construct(AreaRepositoryInterface $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    public function calculatePrice(CalculatePriceRequest $request)
    {
        try {
            $priceData = $this->areaRepository->calculatePrice(
                $request->pick_lat,
                $request->pick_lng,
                $request->drop_lat,
                $request->drop_lng
            );

            if (!$priceData) {
                return $this->error(null);
            }

            return $this->success($priceData);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}