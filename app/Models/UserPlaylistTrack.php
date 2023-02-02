<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlaylistTrack extends Model
{
    use HasFactory;

    protected $table = 'users_playlist_tracks';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
