<?php

namespace App\Policies;

use App\User;
use App\Url;
use Illuminate\Auth\Access\HandlesAuthorization;

class UrlPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the url.
     *
     * @param  \App\User  $user
     * @param  \App\Url  $url
     * @return mixed
     */
    public function update(User $user, Url $url)
    {
        return $user->id === $url->user_id;
    }

    /**
     * Determine whether the user can delete the url.
     *
     * @param  \App\User  $user
     * @param  \App\Url  $url
     * @return mixed
     */
    public function delete(User $user, Url $url)
    {
        //
    }
}
