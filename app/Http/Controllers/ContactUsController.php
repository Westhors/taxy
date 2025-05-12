<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\Driver\StoreContactRequest;
use App\Models\ContactUs;
use App\Traits\HttpResponses;
use Exception;

class ContactUsController extends Controller
{
    use HttpResponses;

    public function store(StoreContactRequest $request)
    {
        try {
            $data = $request->validated();
            $contact = ContactUs::create($data);
            return $this->success($contact, 'Massage send successfully.');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
