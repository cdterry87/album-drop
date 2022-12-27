<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAlbumRelease extends Model
{
    use HasFactory;

    protected $table = 'users_albums_releases';
    protected $guarded = [];

    public function album()
    {
        return $this->belongsTo(ArtistAlbum::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
