<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Point extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'lock_geo' => 'bool',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];

    public function getGeocodedAttribute(): bool
    {
        return ($this->lat && $this->long);
    }

    public function getFullAddressAttribute(): string
    {
        if ($this->apartament) {
            return $this->street . ' ' . $this->building_number . '/' . $this->apartament . ' ' . $this->city . ' '  .$this->postcode;
        }

        return $this->street . ' ' . $this->building_number . ' ' . $this->city;
    }

    public function getAddressAttribute(): string
    {
        if ($this->apartament) {
            return $this->street . ' ' . $this->building_number . '/' . $this->apartament . ' ' . $this->city;
        }

        return $this->street . ' ' . $this->building_number . ' ' . $this->city;
    }

    public function waypoints(): HasMany
    {
        return $this->hasMany(Waypoint::class);
    }
}
