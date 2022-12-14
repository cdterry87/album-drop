<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAlbum extends Model
{
    use HasFactory;

    protected $table = 'users_albums';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function album()
    {
        return $this->belongsTo(ArtistAlbum::class);
    }
}
