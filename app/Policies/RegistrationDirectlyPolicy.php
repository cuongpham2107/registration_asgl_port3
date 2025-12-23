<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RegistrationDirectly;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegistrationDirectlyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RegistrationDirectly');
    }

    public function view(AuthUser $authUser, RegistrationDirectly $registrationDirectly): bool
    {
        return $authUser->can('View:RegistrationDirectly');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RegistrationDirectly');
    }

    public function update(AuthUser $authUser, RegistrationDirectly $registrationDirectly): bool
    {
        return $authUser->can('Update:RegistrationDirectly');
    }

    public function delete(AuthUser $authUser, RegistrationDirectly $registrationDirectly): bool
    {
        return $authUser->can('Delete:RegistrationDirectly');
    }

    public function restore(AuthUser $authUser, RegistrationDirectly $registrationDirectly): bool
    {
        return $authUser->can('Restore:RegistrationDirectly');
    }

    public function forceDelete(AuthUser $authUser, RegistrationDirectly $registrationDirectly): bool
    {
        return $authUser->can('ForceDelete:RegistrationDirectly');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RegistrationDirectly');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RegistrationDirectly');
    }

    public function replicate(AuthUser $authUser, RegistrationDirectly $registrationDirectly): bool
    {
        return $authUser->can('Replicate:RegistrationDirectly');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RegistrationDirectly');
    }

}