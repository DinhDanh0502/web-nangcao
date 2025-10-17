@extends('layouts.frontend')

@section('title', $artist->name . ' - Nghệ sĩ')

@section('content')
    <div class="mb-8">
        <a href="{{ route('artists.index') }}" class="text-sm text-gray-400 hover:text-gray-200">← Quay lại danh sách nghệ sĩ</a>
    </div>

    <div class="flex flex-col md:flex-row gap-6 mb-10">
        <div class="w-32 h-32 md:w-40 md:h-40">
            @if($artist->avatar)
                <img src="{{ asset('storage/' . $artist->avatar) }}" alt="{{ $artist->name }}" class="w-full h-full object-cover rounded-full border-4 border-purple-500">
            @else
                <div class="w-full h-full rounded-full bg-gradient-to-br from-purple-600 to-yellow-500 text-white flex items-center justify-center text-4xl font-bold">
                    {{ strtoupper(substr($artist->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $artist->name }}</h1>
            <p class="text-gray-400 mb-2">
                <i class="fas fa-music mr-2"></i> {{ $artist->songs_count ?? $artist->songs()->count() }} bài hát
            </p>
            @if($artist->bio)
                <div class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $artist->bio }}</div>
            @endif
        </div>
    </div>

    <h2 class="text-2xl font-semibold text-yellow-400 mb-4">Bài hát của {{ $artist->name }}</h2>

    @if($songs->isEmpty())
        <div class="text-gray-400">Chưa có bài hát nào.</div>
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
                        <p class="text-xs text-gray-400 mb-3 truncate">{{ $song->genre->name ?? 'Thể loại khác' }}</p>
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
