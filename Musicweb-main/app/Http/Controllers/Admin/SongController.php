<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\Artist;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    public function index(Request $request)
    {
        $query = Song::with(['artist', 'genre', 'artists', 'genres']);

        $keyword = $request->get('keyword');
        $searchBy = $request->get('search_by', 'all');
        $sortBy = $request->get('sort_by', 'id_desc');
        $perPage = $request->get('per_page', 10);

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($keyword, $searchBy) {
                if ($searchBy === 'all') {
                    $q->where('title', 'like', "%{$keyword}%")
                        ->orWhereHas('artist', function ($q) use ($keyword) {
                            $q->where('name', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('genre', function ($q) use ($keyword) {
                            $q->where('name', 'like', "%{$keyword}%");
                        });
                } elseif ($searchBy === 'id') {
                    if (is_numeric($keyword)) {
                        $q->where('id', intval($keyword));
                    }
                } elseif ($searchBy === 'artist_id') {
                    $q->whereHas('artist', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                } elseif ($searchBy === 'genre_id') {
                    $q->whereHas('genre', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                } else {
                    $q->where($searchBy, 'like', "%{$keyword}%");
                }
            });
        }

        switch ($sortBy) {
            case 'id_asc':
                $query->orderBy('id', 'asc');
                break;
            case 'id_desc':
                $query->orderBy('id', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc'); 
                break;
        }

        if ($perPage === 'all') {
            $songs = $query->get(); 
        } else {
            $songs = $query->paginate((int) $perPage); 
        }

        return view('admin.songs.index', compact('songs'));
    }

    public function create()
    {
        $artists = Artist::all();
        $genres = Genre::all();
        return view('admin.songs.create', compact('artists', 'genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
'artist_id' => ['required', 'exists:artists,id'],
            'collaborating_artist_ids' => ['nullable', 'array'],
            'collaborating_artist_ids.*' => ['exists:artists,id'],
            'collaborating_artists' => ['nullable', 'string', 'max:500'],
            'genre_ids' => ['required', 'array', 'min:1'],
            'genre_ids.*' => ['exists:genres,id'],
'audio_file' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac', 'max:51200'], // 50MB max
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'], // 2MB max
            'duration' => ['required', 'numeric', 'min:1'],
        ]);

        try {
           
            $audioPath = null;
            if ($request->hasFile('audio_file')) {
                $audioFile = $request->file('audio_file');
                if (!$audioFile->isValid()) {
                    $error = $audioFile->getError();
                    $errorMessages = [
                        UPLOAD_ERR_INI_SIZE => 'File quá lớn (vượt quá upload_max_filesize)',
                        UPLOAD_ERR_FORM_SIZE => 'File quá lớn (vượt quá MAX_FILE_SIZE)',
                        UPLOAD_ERR_PARTIAL => 'File chỉ được upload một phần',
                        UPLOAD_ERR_NO_FILE => 'Không có file nào được upload',
                        UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm',
                        UPLOAD_ERR_CANT_WRITE => 'Không thể ghi file',
                        UPLOAD_ERR_EXTENSION => 'Upload bị dừng bởi extension'
                    ];
                    $errorMessage = $errorMessages[$error] ?? 'File nhạc không hợp lệ!';
                    return back()->with('error', $errorMessage)->withInput();
                }
                $audioPath = $audioFile->store('songs', 'public');
            }
            
            $coverPath = null;
            if ($request->hasFile('cover_image')) {
                $coverFile = $request->file('cover_image');
                if ($coverFile->isValid()) {
                    $coverPath = $coverFile->store('covers', 'public');
                }
            }

            $song = Song::create([
                'title' => $validated['title'],
                'artist_id' => $validated['artist_id'],
                'genre_id' => $validated['genre_ids'][0], 
'file_url' => $audioPath ?? '',
                'cover_url' => $coverPath,
                'duration' => $validated['duration'],
            ]);

            $song->genres()->attach($validated['genre_ids']);

            $collabIds = array_map('intval', $request->input('collaborating_artist_ids', []));

            if (!empty($validated['collaborating_artists'])) {
                $artistNames = array_map('trim', explode(',', $validated['collaborating_artists']));
                foreach ($artistNames as $artistName) {
                    if ($artistName !== '') {
                        $artist = Artist::firstOrCreate(['name' => $artistName]);
                        $collabIds[] = (int) $artist->id;
                    }
                }
            }

            $collabIds = array_values(array_unique(array_filter($collabIds)));
            $collabIds = array_diff($collabIds, [(int) $validated['artist_id']]);
            if (!empty($collabIds)) {
                $song->artists()->attach($collabIds);
            }

            return redirect()->route('admin.songs.index')->with('success', 'Bài hát đã được tạo thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi tạo bài hát: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Song $song)
    {
        $artists = Artist::all();
        $genres = Genre::all();
        return view('admin.songs.edit', compact('song', 'artists', 'genres'));
    }

    public function update(Request $request, Song $song)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'artist_id' => ['required', 'exists:artists,id'],
            'genre_id' => ['required', 'exists:genres,id'],
            'audio_file' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac', 'max:51200'], // 50MB max
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'duration' => ['required', 'numeric'],
        ]);

        try {
            if ($request->hasFile('audio_file')) {
                if ($song->file_url) {
                    Storage::disk('public')->delete($song->file_url);
                }
                $song->file_url = $request->file('audio_file')->store('songs', 'public');
            }

            if ($request->hasFile('cover_image')) {
                if ($song->cover_url) {
                    Storage::disk('public')->delete($song->cover_url);
                }
                $song->cover_url = $request->file('cover_image')->store('covers', 'public');
            }

            $song->title = $request->input('title');
            $song->artist_id = $request->input('artist_id');
            $song->genre_id = $request->input('genre_id');
            $song->duration = $request->input('duration');
            $song->save();

            return redirect()->route('admin.songs.index')->with('success', 'Bài hát đã được cập nhật thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật bài hát!')->withInput();
        }
    }

    public function destroy(Song $song)
    {
        try {
            if ($song->file_url) {
                Storage::disk('public')->delete($song->file_url);
            }
            if ($song->cover_url) {
                Storage::disk('public')->delete($song->cover_url);
            }

            $song->delete();
            return redirect()->route('admin.songs.index')->with('success', 'Bài hát đã được xóa thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xóa bài hát!');
        }
    }
    public function search(Request $request)
    {
        $search = $request->get('search');

        $songs = Song::where('title', 'like', "%{$search}%")
            ->orWhere('artist_id', 'like', "%{$search}%")
            ->paginate(10);

        return view('admin.song.index', compact('songs'));
    }
    public function show(Song $song)
    {
        return view('admin.songs.show', compact('song'));
    }
}
