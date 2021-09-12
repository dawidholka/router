<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    const STATUS_NO_WAYPOINTS = 0;
    const STATUS_NO_GEO = 1;
    const STATUS_OK = 2;
    const STATUS_END = 3;
    const OPTIMIZE_NONE = 0;
    const OPTIMIZE_ROUTEXL = 1;

    protected $guarded = ['id'];

    protected $casts = [
        'delivery_time' => 'date',
        'created_at' => 'date',
        'updated_at' => 'date'
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function waypoints(): HasMany
    {
        return $this->hasMany(Waypoint::class);
    }
}
