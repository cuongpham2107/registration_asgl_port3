<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class RegistrationVehicle extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expected_arrival_time' => 'datetime',
    ];

    public function registrationDirectly(): HasOne
    {
        return $this->hasOne(RegistrationDirectly::class, 'id_registration_vehicle');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function loadCapacity()
    {
        return $this->belongsTo(LoadCapacity::class, 'id_load_capacity');
    }
    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'id_gateway');
    }
}
