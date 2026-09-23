<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BookBorrower;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookBorrowerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BookBorrower');
    }

    public function view(AuthUser $authUser, BookBorrower $bookBorrower): bool
    {
        return $authUser->can('View:BookBorrower');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BookBorrower');
    }

    public function update(AuthUser $authUser, BookBorrower $bookBorrower): bool
    {
        return $authUser->can('Update:BookBorrower');
    }

    public function delete(AuthUser $authUser, BookBorrower $bookBorrower): bool
    {
        return $authUser->can('Delete:BookBorrower');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BookBorrower');
    }

    public function restore(AuthUser $authUser, BookBorrower $bookBorrower): bool
    {
        return $authUser->can('Restore:BookBorrower');
    }

    public function forceDelete(AuthUser $authUser, BookBorrower $bookBorrower): bool
    {
        return $authUser->can('ForceDelete:BookBorrower');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BookBorrower');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BookBorrower');
    }

    public function replicate(AuthUser $authUser, BookBorrower $bookBorrower): bool
    {
        return $authUser->can('Replicate:BookBorrower');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BookBorrower');
    }

}