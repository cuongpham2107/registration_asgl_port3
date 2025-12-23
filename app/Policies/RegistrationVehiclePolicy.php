<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RegistrationVehicle;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegistrationVehiclePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RegistrationVehicle');
    }

    public function view(AuthUser $authUser, RegistrationVehicle $registrationVehicle): bool
    {
        return $authUser->can('View:RegistrationVehicle');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RegistrationVehicle');
    }

    public function update(AuthUser $authUser, RegistrationVehicle $registrationVehicle): bool
    {
        return $authUser->can('Update:RegistrationVehicle');
    }

    public function delete(AuthUser $authUser, RegistrationVehicle $registrationVehicle): bool
    {
        return $authUser->can('Delete:RegistrationVehicle');
    }

    public function restore(AuthUser $authUser, RegistrationVehicle $registrationVehicle): bool
    {
        return $authUser->can('Restore:RegistrationVehicle');
    }

    public function forceDelete(AuthUser $authUser, RegistrationVehicle $registrationVehicle): bool
    {
        return $authUser->can('ForceDelete:RegistrationVehicle');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RegistrationVehicle');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RegistrationVehicle');
    }

    public function replicate(AuthUser $authUser, RegistrationVehicle $registrationVehicle): bool
    {
        return $authUser->can('Replicate:RegistrationVehicle');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RegistrationVehicle');
    }

}