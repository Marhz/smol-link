<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Url[] $urls
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Visit
 *
 * @property int $id
 * @property string|null $country
 * @property string|null $referrer
 * @property int $url_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visit whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visit whereReferrer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visit whereUrlId($value)
 */
	class Visit extends \Eloquent {}
}

namespace App{
/**
 * App\Url
 *
 * @property int $id
 * @property string $slug
 * @property string $url
 * @property string|null $label
 * @property string|null $title
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read mixed $path
 * @property-read mixed $visits_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Visit[] $visits
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Url whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Url whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Url whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Url whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Url whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Url whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Url whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Url whereUserId($value)
 */
	class Url extends \Eloquent {}
}

