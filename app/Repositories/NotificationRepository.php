<?php

namespace App\Repositories;

use App\Http\Resources\NotificationResource;
use App\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationRepository extends CrudRepository implements NotificationRepositoryInterface
{
    protected Model $model;

    public function __construct(Notification $model)
    {
        $this->model = $model;
    }

    public function getNotifications(): array
    {
        $userId = Auth::guard('user')->check() ? Auth::guard('user')->id() : null;
        $driverId = Auth::guard('driver')->check() ? Auth::guard('driver')->id() : null;

        if (!$userId && !$driverId) {
            throw new Exception('You must be authenticated as either a user or a driver.');
        }

        $query = Notification::query()
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when($driverId, fn($q) => $q->orWhere('driver_id', $driverId))
            ->latest();

        $perPage = request('per_page', 15);
        $paginated = $query->paginate($perPage);

        // Grouping by day
        $grouped = $paginated->getCollection()->groupBy(function ($notification) {
            $date = Carbon::parse($notification->created_at);

            if ($date->isToday()) {
                return 'today';
            } elseif ($date->isYesterday()) {
                return 'yesterday';
            } else {
                return $date->format('F j, Y');
            }
        });

        return [
            'data' => $grouped->map(fn($group) => NotificationResource::collection($group))->toArray(),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        ];
    }
}
