@extends('layouts.frontend')

@section('title', $genre->name . ' - Thể loại')

@section('content')
    <div class="mb-8">
        <a href="{{ route('genres.index') }}" class="text-sm text-gray-400 hover:text-gray-200">← Quay lại danh sách thể loại</a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-white">{{ $genre->name }}</h1>
        <p class="text-gray-400 mt-2"><i class="fas fa-music mr-2"></i> {{ $genre->songs_count ?? $genre->songs()->count() }} bài hát</p>
    </div>

    @if($songs->isEmpty())
        <div class="text-gray-400">Chưa có bài hát nào thuộc thể loại này.</div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($songs as $song)
                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                    <div class="relative h-40 bg-gradient-to-br from-purple-600 to-indigo-700">
                        @if($song->cover_url)
                            <img src="{{ asset('storage/' . $song->cover_url) }}" alt="{{ $song->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-music text-5xl text-white opacity-50"></i>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                            {{ gmdate('i:s', $song->duration ?? 0) }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-white font-semibold mb-1 truncate" title="{{ $song->title }}">{{ $song->title }}</h3>
                        <p class="text-xs text-gray-400 mb-1 truncate">{{ $song->artist->name ?? 'Không rõ nghệ sĩ' }}</p>
                        @if($song->file_url)
                            <audio controls class="w-full" style="height: 32px;">
                                <source src="{{ asset('storage/' . $song->file_url) }}" type="audio/mpeg">
                            </audio>
                        @endif
                        <a href="{{ route('music.show', $song->id) }}" class="block mt-3 text-center py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition text-sm">
                            <i class="fas fa-play mr-1"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $songs->links() }}
        </div>
    @endif
@endsection
