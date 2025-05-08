<?php

namespace App\Http\Controllers;

use App\Http\Resources\DistrictResource;
use App\Interfaces\DistrictRepositoryInterface;
use App\Traits\HttpResponses;
use App\Helpers\JsonResponse;
use App\Models\District;
use Exception;

class DistrictController extends Controller
{
    use HttpResponses;

    protected $districtRepository;

    public function __construct(DistrictRepositoryInterface $districtRepository)
    {
        $this->districtRepository = $districtRepository;
    }

    public function index()
    {
        $districts = $this->districtRepository->all();
        return $this->success(DistrictResource::collection($districts));
    }
}