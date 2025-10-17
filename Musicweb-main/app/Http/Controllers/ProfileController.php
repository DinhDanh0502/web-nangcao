<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Song;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with('artist', 'genre')->orderByDesc('favorites.created_at')->paginate(12);
        return view('frontend.profile', compact('user', 'favorites'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable','string'],
            'favorite_genres' => ['nullable','string','max:255'],
            'favorite_artists' => ['nullable','string','max:255'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->bio = $validated['bio'] ?? $user->bio;
        $user->favorite_genres = $validated['favorite_genres'] ?? $user->favorite_genres;
        $user->favorite_artists = $validated['favorite_artists'] ?? $user->favorite_artists;

        $user->save();

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }
}
