<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist_id',
        'genre_id',
        'file_url',
        'cover_url',
        'duration',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'song_artist');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'song_genre');
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class);
    }
}
