<?php
namespace App\Policies;

use App\Models\Attendee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendeePolicy
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
        return $user->can('attendees.view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendee  $attendee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Attendee $attendee)
    {
        if ($user->client_id !== null) {
            return $user->client_id === $attendee->client_id;
        }

        return $user->can('attendees.view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('attendees.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendee  $attendee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Attendee $attendee)
    {
        return $user->can('attendees.edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendee  $attendee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Attendee $attendee)
    {
        return $user->can('attendees.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendee  $attendee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Attendee $attendee)
    {
        return $user->can('attendees.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Attendee  $attendee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Attendee $attendee)
    {
        return $user->can('attendees.delete');
    }
}
