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
}
