<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\Users\Addresses\CreateUserAddressRequest;
use App\Http\Requests\Users\Addresses\UpdateUserAddressRequest;
use App\Http\Resources\AddressResource;
use App\Repositories\AddressRepository;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    use HttpResponses;

    protected $repository;

    public function __construct(AddressRepository $repository)
    {
        $this->repository = $repository;
    }

    public function myAddresses()
    {
        $addresses = Auth::user()->addresses;

        return $this->success(AddressResource::collection($addresses));
    }

    public function store(CreateUserAddressRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();

            $address = $this->repository->create($data);

            return $this->success(new AddressResource($address));
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function update(UpdateUserAddressRequest $request, $id)
    {
        try {
            $address = $this->repository->findOrError($id);

            if (!$address || $address->user_id !== Auth::id()) {
                return $this->error(null, 'Unauthorized', 403);
            }

            $address->update($request->validated());

            return $this->success(new AddressResource($address), 'Address updated successfully.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $address = $this->repository->findOrError($id);

            if (!$address || $address->user_id !== Auth::id()) {
                return $this->error(null, 'Unauthorized', 403);
            }

            $address->delete();

            return $this->success(null, 'Address deleted successfully.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage(), $e->getCode());
        }
    }
}