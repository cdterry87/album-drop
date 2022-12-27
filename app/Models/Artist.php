<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $table = 'artists';
    protected $guarded = [];

    public function albums()
    {
        return $this->hasMany(ArtistAlbum::class);
    }

    public function userArtists()
    {
        return $this->hasMany(UserArtist::class);
    }

    public function relatedArtists()
    {
        return $this->hasMany(ArtistRelatedArtist::class);
    }
}
