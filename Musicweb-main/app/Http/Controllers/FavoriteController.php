<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Song;

class FavoriteController extends Controller
{
    public function toggle(Song $song, Request $request)
    {
        $user = Auth::user();
        $exists = $user->favorites()->where('song_id', $song->id)->exists();
        if ($exists) {
            $user->favorites()->detach($song->id);
            $message = 'Đã gỡ khỏi yêu thích';
        } else {
            $user->favorites()->attach($song->id);
            $message = 'Đã thêm vào yêu thích';
        }

        if ($request->expectsJson()) {
            return response()->json(['favorited' => !$exists, 'message' => $message]);
        }
        return back()->with('success', $message);
    }
}
