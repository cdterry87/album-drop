<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRecommendation extends Model
{
    use HasFactory;

    protected $table = 'users_recommendations';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
