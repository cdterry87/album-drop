<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $table = 'albums';
    protected $guarded = [];
    protected $dates = ['release_date'];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'spotify_artist_id', 'spotify_artist_id');
    }

    public function userAlbums()
    {
        return $this->hasMany(UserAlbum::class);
    }
}
