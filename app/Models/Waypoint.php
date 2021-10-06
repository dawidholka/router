<?php

namespace App\Models;

use App\Support\ColorDictionary;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Waypoint extends Model implements HasMedia
{
    use InteractsWithMedia;

    const STATUS_DELIVERED = 'delivered';
    const STATUS_PROBLEM = 'problem';
    const STATUS_UNDELIVERED = 'undelivered';

    protected $guarded = ['id'];

    protected $casts = [
        'delivered_time' => 'date',
        'quantity' => 'int',
        'lat' => 'double',
        'long' => 'double',
        'id' => 'int',
        'point_id' => 'int',
        'stop_number' => 'int'
    ];

    public function point(): BelongsTo
    {
        return $this->belongsTo(Point::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status){
            self::STATUS_DELIVERED => 'green',
            self::STATUS_UNDELIVERED => 'yellow',
            self::STATUS_PROBLEM => 'red'
        };
    }

    public function getPhotoUploadedAttribute(): bool
    {
        return (bool)$this->getFirstMedia('image');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }
}
