<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'spotify_id',
        'spotify_token',
        'spotify_refresh_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function artists()
    {
        return $this->hasManyThrough(
            Artist::class,
            UserArtist::class,
            'user_id',
            'id',
            'id',
            'artist_id'
        );
    }

    public function albums()
    {
        return $this->hasManyThrough(
            ArtistAlbum::class,
            UserAlbum::class,
            'user_id',
            'id',
            'id',
            'album_id'
        );
    }

    public function albumDrops()
    {
        return $this->hasMany(UserAlbumDrop::class);
    }

    public function playlistTracks()
    {
        return $this->hasMany(UserPlaylistTrack::class);
    }
}
