<?php

namespace Router\Routes\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Router\Drivers\Domain\Models\Driver;

class Route extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'delivery_time' => 'date',
        'created_at' => 'date',
        'updated_at' => 'date',
        'id' => 'int',
        'driver_id' => 'int'
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function waypoints(): HasMany
    {
        return $this->hasMany(Waypoint::class);
    }

    public function driver_locations(): HasMany
    {
        return $this->hasMany(DriverLocation::class);
    }

}
