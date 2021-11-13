<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zone extends Model
{
    protected $guarded = ['id'];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
