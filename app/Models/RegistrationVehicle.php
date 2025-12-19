<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class RegistrationVehicle extends Model
{
    protected $guarded = [];

    public function registrationDirectly(): HasOne
    {
        return $this->hasOne(RegistrationDirectly::class, 'id_registration_vehicle');
    }
}
