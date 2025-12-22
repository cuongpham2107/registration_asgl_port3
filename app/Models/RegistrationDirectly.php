<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationDirectly extends Model
{
    protected $guarded = [];
    public function registrationVehicle(): BelongsTo
    {
        return $this->belongsTo(RegistrationVehicle::class, 'id_registration_vehicle');
    }

    public function loadCapacity(): BelongsTo
    {
        return $this->belongsTo(LoadCapacity::class, 'id_load_capacity');
    }

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class, 'id_gateway');
    }   
}
