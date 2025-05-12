<?php

namespace App\Interfaces;

use App\Models\Notification;
use App\Repositories\ICrudRepository;

interface NotificationRepositoryInterface extends ICrudRepository
{
    public function getNotifications();
}
