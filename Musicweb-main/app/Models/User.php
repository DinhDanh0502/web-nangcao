<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role',
'bio',
        'favorite_genres',
        'favorite_artists',
    ];

    public function favorites()
    {
        return $this->belongsToMany(Song::class, 'favorites')->withTimestamps();
    }
}
