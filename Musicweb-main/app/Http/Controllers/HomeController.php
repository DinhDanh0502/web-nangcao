<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Playlist;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $songs = Song::with(['artist', 'genre'])
            ->when($keyword, function ($query, $keyword) {
                $query->where('title', 'like', "%$keyword%")
                      ->orWhereHas('artist', fn($q) => $q->where('name', 'like', "%$keyword%"))
                      ->orWhereHas('genre', fn($q) => $q->where('name', 'like', "%$keyword%"));
            })
            ->paginate(16);

        return view('frontend.home', compact('songs', 'keyword'));
    }
    
    public function show($id)
    {
        $song = Song::with(['artist', 'genre'])->findOrFail($id);
        
        $relatedSongs = Song::with(['artist', 'genre'])
            ->where('id', '!=', $song->id)
            ->where(function($query) use ($song) {
                $query->where('artist_id', $song->artist_id)
                      ->orWhere('genre_id', $song->genre_id);
            })
            ->limit(8)
            ->get();
        
        return view('frontend.song_detail', compact('song', 'relatedSongs'));
    }
    

    public function genres()
    {
        $genres = Genre::withCount('songs')
                       ->orderBy('name', 'asc')
                       ->paginate(12);
        
        return view('frontend.genres', compact('genres'));
    }
    
  
    public function genreShow($id)
    {
        $genre = Genre::with('songs.artist')->findOrFail($id);
        $genre->loadCount('songs');
        
        $songs = $genre->songs()->with('artist')->paginate(12);
        
        return view('frontend.genre_detail', compact('genre', 'songs'));
    }
    

    public function artists()
    {
        $artists = Artist::withCount('songs')
                        ->orderBy('name', 'asc')
                        ->paginate(18);
        
        return view('frontend.artists', compact('artists'));
    }
    

    public function artistShow($id)
    {
        $artist = Artist::findOrFail($id);
        $artist->loadCount('songs');
        
        $songs = \App\Models\Song::with('genre')
            ->where('artist_id', $artist->id)
            ->orWhereHas('artists', function ($q) use ($artist) {
                $q->where('artists.id', $artist->id);
            })
            ->orderByDesc('id')
            ->distinct()
            ->paginate(12);
        
        return view('frontend.artist_detail', compact('artist', 'songs'));
    }
    

    public function playlists()
    {
        $playlists = Playlist::with('songs')
                            ->withCount('songs')
                            ->orderBy('created_at', 'desc')
                            ->paginate(12);
        
        return view('frontend.playlists', compact('playlists'));
    }
    

    public function playlist($name)
    {
        $decodedName = urldecode($name);
        
        $playlist = Playlist::with(['songs.artist', 'songs.genre'])
                           ->where('title', $decodedName)
                           ->firstOrFail();
        
        return view('frontend.playlist_detail', compact('playlist'));
    }
}