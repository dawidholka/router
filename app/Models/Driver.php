<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Model
{
    use HasApiTokens;

    const COLORS = [
        ['name' => 'aqua', 'value' => 'aqua'],
        ['name' => 'blueviolet', 'value' => 'blueviolet'],
        ['name' => 'cornflowerblue', 'value' => 'cornflowerblue'],
        ['name' => 'chartreuse', 'value' => 'chartreuse'],
        ['name' => 'coral', 'value' => 'coral'],
        ['name' => 'darkgray', 'value' => 'darkgray'],
        ['name' => 'darkorange', 'value' => 'darkorange'],
        ['name' => 'darksalmon', 'value' => 'darksalmon'],
        ['name' => 'hotpink', 'value' => 'hotpink'],
        ['name' => 'lightgreen', 'value' => 'lightgreen'],
        ['name' => 'yellowgreen', 'value' => 'yellowgreen'],
    ];

    protected $guarded = ['id'];

    protected $hidden = ['password'];

    protected $casts = [
        'id' => 'int',
        'route_id' => 'int'
    ];

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }
}
