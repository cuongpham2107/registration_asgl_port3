<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $guarded = [];

    public function registrationDirectly(): BelongsTo
    {
        return $this->belongsTo(RegistrationDirectly::class, 'registration_directly_id');
    }
}
