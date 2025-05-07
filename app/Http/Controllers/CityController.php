<?php

namespace App\Http\Controllers;

use App\Events\NewCityRequest;
use App\Http\Requests\Users\Citys\CreateCityRequest;
use App\Http\Resources\CityResource;
use App\Interfaces\CityRepositoryInterface;
use App\Traits\HttpResponses;
use App\Helpers\JsonResponse;
use App\Models\City;
use Exception;

class CityController extends Controller
{
    use HttpResponses;

    protected $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function index()
    {
        $cities = $this->cityRepository->all();
        return $this->success(CityResource::collection($cities));
    }
}
