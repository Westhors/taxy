<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
/**
 *
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel filter($filters = null, $filterOperator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BaseModel withoutTrashed()
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    use SoftDeletes , LogsActivity;

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
}
