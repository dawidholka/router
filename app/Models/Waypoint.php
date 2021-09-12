<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waypoint extends Model
{
    protected $guarded = ['id'];

    public function point(): BelongsTo
    {
        return $this->belongsTo(Point::class);
    }

}
