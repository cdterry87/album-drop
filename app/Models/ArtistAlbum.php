<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtistAlbum extends Model
{
    use HasFactory;

    protected $table = 'artists_albums';
    protected $guarded = [];
    protected $dates = ['release_date'];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
