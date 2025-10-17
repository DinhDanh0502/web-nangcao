@extends('layouts.frontend')

@section('title', 'Danh sách Playlist - MusicWeb')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2">🎵 Playlist</h1>
        <p class="text-gray-400">Khám phá các bộ sưu tập nhạc đặc sắc</p>
    </div>

    <!-- Playlists Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($playlists as $playlist)
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition transform hover:scale-105 duration-300">
                <div class="h-40 bg-gradient-to-r from-purple-600 to-indigo-700 flex items-center justify-center">
                    <i class="fas fa-compact-disc text-6xl text-white text-opacity-70"></i>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-2">{{ $playlist->title }}</h3>
                    
                    @if($playlist->description)
                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $playlist->description }}</p>
                    @else
                        <p class="text-gray-500 text-sm mb-4 italic">Không có mô tả</p>
                    @endif
                    
                    <div class="flex justify-between items-center text-sm text-gray-400 mb-4">
                        <span><i class="fas fa-music mr-1"></i> {{ $playlist->songs_count }} bài hát</span>
                        @if($playlist->created_at)
                            <span><i class="fas fa-calendar mr-1"></i> {{ $playlist->created_at->format('d/m/Y') }}</span>
                        @endif
                    </div>
                    
                    @if($playlist->title)
                        <a href="{{ route('music.playlist', ['name' => urlencode($playlist->title)]) }}" 
                           class="block w-full py-2 bg-purple-600 text-white text-center rounded hover:bg-purple-700 transition">
                            <i class="fas fa-headphones mr-2"></i> Nghe ngay
                        </a>
                    @else
                        <div class="block w-full py-2 bg-gray-600 text-white text-center rounded cursor-not-allowed">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Không có tên playlist
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16">
                <i class="fas fa-compact-disc text-6xl text-gray-700 mb-4"></i>
                <p class="text-gray-400 text-xl">Chưa có playlist nào.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $playlists->links() }}
    </div>
@endsection