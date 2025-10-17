<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function categorizedSongs()
    {
        return $this->belongsToMany(Song::class, 'song_genre');
    }
}
