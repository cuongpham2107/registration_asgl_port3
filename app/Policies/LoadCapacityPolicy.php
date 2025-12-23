<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LoadCapacity;
use Illuminate\Auth\Access\HandlesAuthorization;

class LoadCapacityPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LoadCapacity');
    }

    public function view(AuthUser $authUser, LoadCapacity $loadCapacity): bool
    {
        return $authUser->can('View:LoadCapacity');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LoadCapacity');
    }

    public function update(AuthUser $authUser, LoadCapacity $loadCapacity): bool
    {
        return $authUser->can('Update:LoadCapacity');
    }

    public function delete(AuthUser $authUser, LoadCapacity $loadCapacity): bool
    {
        return $authUser->can('Delete:LoadCapacity');
    }

    public function restore(AuthUser $authUser, LoadCapacity $loadCapacity): bool
    {
        return $authUser->can('Restore:LoadCapacity');
    }

    public function forceDelete(AuthUser $authUser, LoadCapacity $loadCapacity): bool
    {
        return $authUser->can('ForceDelete:LoadCapacity');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LoadCapacity');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LoadCapacity');
    }

    public function replicate(AuthUser $authUser, LoadCapacity $loadCapacity): bool
    {
        return $authUser->can('Replicate:LoadCapacity');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LoadCapacity');
    }

}