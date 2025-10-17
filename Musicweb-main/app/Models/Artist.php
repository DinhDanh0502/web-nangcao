<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'avatar',
    ];

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function collaboratedSongs()
    {
        return $this->belongsToMany(Song::class, 'song_artist');
    }
}

