<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistRelatedArtist extends Model
{
    use HasFactory;

    protected $table = 'artists_related_artists';
    protected $guarded = [];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
