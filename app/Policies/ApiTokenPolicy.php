<?php
namespace App\Policies;

use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApiTokenPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('api_tokens.view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ApiToken  $apiToken
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ApiToken $apiToken)
    {
        return $user->can('api_tokens.view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('api_tokens.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ApiToken  $apiToken
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ApiToken $apiToken)
    {
        return $user->can('api_tokens.edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ApiToken  $apiToken
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ApiToken $apiToken)
    {
        return $user->can('api_tokens.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ApiToken  $apiToken
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, ApiToken $apiToken)
    {
        return $user->can('api_tokens.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ApiToken  $apiToken
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, ApiToken $apiToken)
    {
        return $user->can('api_tokens.delete');
    }
}
