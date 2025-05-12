<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Requests\Users\Profile\CreateNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use HttpResponses;
    protected FirebaseService $firebaseService;
    protected $repository;


    public function __construct(NotificationRepositoryInterface $repository, FirebaseService $firebaseService)
    {
        $this->repository = $repository;
        $this->firebaseService = $firebaseService;
    }

    public function store(CreateNotificationRequest $request)
    {
        try {
            $notification = $this->repository->create($request->validated());

            // Send notification to Firebase
            $d = $this->firebaseService->sendNotificationModel($notification);

            return $this->success(new NotificationResource($notification), 'Notification created successfully', 201);
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function getMyNotifications()
    {
        try {
            $result = $this->repository->getNotifications();

            return $this->success($result, 'Notifications retrieved successfully');
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }
}
