<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistAlbumTrack extends Model
{
    use HasFactory;

    protected $table = 'artists_albums_tracks';
    protected $guarded = [];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function album()
    {
        return $this->belongsTo(ArtistAlbum::class);
    }
}
