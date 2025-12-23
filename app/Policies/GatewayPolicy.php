<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Gateway;
use Illuminate\Auth\Access\HandlesAuthorization;

class GatewayPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Gateway');
    }

    public function view(AuthUser $authUser, Gateway $gateway): bool
    {
        return $authUser->can('View:Gateway');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Gateway');
    }

    public function update(AuthUser $authUser, Gateway $gateway): bool
    {
        return $authUser->can('Update:Gateway');
    }

    public function delete(AuthUser $authUser, Gateway $gateway): bool
    {
        return $authUser->can('Delete:Gateway');
    }

    public function restore(AuthUser $authUser, Gateway $gateway): bool
    {
        return $authUser->can('Restore:Gateway');
    }

    public function forceDelete(AuthUser $authUser, Gateway $gateway): bool
    {
        return $authUser->can('ForceDelete:Gateway');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Gateway');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Gateway');
    }

    public function replicate(AuthUser $authUser, Gateway $gateway): bool
    {
        return $authUser->can('Replicate:Gateway');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Gateway');
    }

}