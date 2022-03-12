<?php

namespace Router\Zones\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Router\Drivers\Domain\Models\Driver;

class Zone extends Model
{
    protected $guarded = ['id'];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
