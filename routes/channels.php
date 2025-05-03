<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('driver.{id}', function ($driver, $id) {
    return $driver->id == $id;
}, ['guards' => ['driver']]);