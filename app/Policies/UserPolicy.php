<?php

namespace KSUGMap\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use KSUGMap\User;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can upload to S3 via vapor-core.
     *
     * @param  \KSUGMap\User  $user
     * @return mixed
     */
    public function uploadFiles(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \KSUGMap\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \KSUGMap\User  $user
     * @param  \KSUGMap\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \KSUGMap\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \KSUGMap\User  $user
     * @param  \KSUGMap\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \KSUGMap\User  $user
     * @param  \KSUGMap\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \KSUGMap\User  $user
     * @param  \KSUGMap\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \KSUGMap\User  $user
     * @param  \KSUGMap\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
