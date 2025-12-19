<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function registrationVehicles()
    {
        return $this->hasMany(RegistrationVehicle::class);
    }
}
