<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, LogsActivity, HasRoles, MustVerifyEmail;

    protected $guarded = ['id'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*'])->logOnlyDirty();
    }
    public function scopeFilter($builder, $filters = null, $filterOperator = "=")
    {
        if (isset($filters) && is_array($filters)) {
            foreach ($filters as $field => $value) {
                if ($value == Constants::NULL)
                    $builder->whereNull($field);
                elseif ($value == Constants::NOT_NULL)
                    $builder->whereNotNull($field);
                elseif (is_array($value))
                    $builder->whereIn($field, $value);
                elseif ($filterOperator == "like")
                    $builder->where($field, $filterOperator, '%' . $value . '%');
                else
                    $builder->where($field, $value);
            }
        }
        return $builder;
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function scopeNearby($query, $lat, $lng, $distance = 10)
    {
        return $query->selectRaw("*, (
            6371 * acos(
                cos(radians(?)) * cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(latitude))
            )
        ) AS distance", [$lat, $lng, $lat])
            ->having("distance", "<", $distance)
            ->orderBy("distance");
    }
}
