<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'lock_geo' => 'bool'
    ];

    public function getGeocodedAttribute(): bool
    {
        return ($this->lat && $this->long);
    }

    public function getAddressAttribute(): string
    {
        if ($this->apartament) {
            return $this->street . ' ' . $this->building_number . '/' . $this->apartament . ' ' . $this->city;
        }

        return $this->street . ' ' . $this->building_number . ' ' . $this->city;
    }
}
