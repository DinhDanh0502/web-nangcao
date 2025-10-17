@extends('layouts.frontend')

@section('title', 'Playlist: ' . $playlist->title . ' - MusicWeb')

@section('content')
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('music.index') }}" 
           class="inline-flex items-center text-purple-400 hover:text-purple-300 transition">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại trang chủ
        </a>
    </div>

    <!-- Playlist Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-xl p-8 md:p-12 mb-8 shadow-2xl">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <!-- Playlist Icon -->
            <div class="w-32 h-32 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center shadow-xl">
                <i class="fas fa-compact-disc text-6xl text-white"></i>
            </div>
            
            <!-- Playlist Info -->
            <div class="flex-1 text-center md:text-left">
                <p class="text-purple-200 text-sm font-semibold mb-2 uppercase tracking-wider">Playlist</p>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">🎵 {{ $playlist->title }}</h1>
                
                @if($playlist->description)
                    <p class="text-purple-100 text-lg mb-4">{{ $playlist->description }}</p>
                @endif
                
                <div class="flex flex-wrap gap-4 justify-center md:justify-start text-sm text-purple-200">
                    <span><i class="fas fa-music mr-2"></i>{{ $playlist->songs->count() }} bài hát</span>
                    @if($playlist->songs->count() > 0)
                        <span><i class="fas fa-clock mr-2"></i>{{ gmdate("H:i:s", $playlist->songs->sum('duration')) }}</span>
                    @endif
                    @if($playlist->created_at)
                        <span><i class="fas fa-calendar mr-2"></i>{{ $playlist->created_at->format('d/m/Y') }}</span>
                    @endif
                </div>
            </div>

            <!-- Play All Button -->
            @if($playlist->songs->count() > 0)
                <div>
                    <button class="px-8 py-4 bg-white text-purple-700 rounded-full font-bold text-lg hover:scale-105 transition shadow-xl">
                        <i class="fas fa-play mr-2"></i> Phát tất cả
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if($playlist->songs->isEmpty())
        <div class="text-center py-16 bg-gray-800 rounded-xl">
            <i class="fas fa-music text-6xl text-gray-700 mb-4"></i>
            <p class="text-gray-400 text-xl mb-4">Playlist chưa có bài hát nào.</p>
            <a href="{{ route('music.index') }}" 
               class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-search mr-2"></i> Khám phá bài hát
            </a>
        </div>
    @else
        <!-- Songs Grid -->
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-purple-400 mb-4">
                <i class="fas fa-list mr-2"></i> Danh sách bài hát
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($playlist->songs as $index => $song)
                <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden transition transform hover:scale-105 hover:shadow-2xl duration-300">
                    <!-- Album Cover with Index -->
                    <div class="relative h-48 bg-gradient-to-br from-purple-600 to-indigo-700">
                        @if($song->cover_url)
                            <img src="{{ asset('storage/' . $song->cover_url) }}" 
                                 alt="{{ $song->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-music text-6xl text-white opacity-50"></i>
                            </div>
                        @endif
                        
                        <!-- Track Number -->
                        <div class="absolute top-3 left-3 bg-black bg-opacity-70 text-white font-bold w-10 h-10 rounded-full flex items-center justify-center">
                            {{ $index + 1 }}
                        </div>
                        
                        <!-- Duration -->
                        <div class="absolute top-3 right-3 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                            {{ gmdate("i:s", $song->duration ?? 0) }}
                        </div>
                    </div>

                    <!-- Song Info -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-white mb-1 truncate" title="{{ $song->title }}">
                            {{ $song->title }}
                        </h3>
                        <p class="text-sm text-gray-400 mb-1 truncate">
                            <i class="fas fa-user-music mr-1"></i>
                            <a href="{{ route('artist.show', $song->artist->id) }}" 
                               class="hover:text-purple-400 transition">
                                {{ $song->artist->name ?? 'Không rõ nghệ sĩ' }}
                            </a>
                        </p>
                        <p class="text-xs text-gray-500 mb-3 truncate">
                            <i class="fas fa-tag mr-1"></i> {{ $song->genre->name ?? 'Thể loại khác' }}
                        </p>

                        <!-- Audio Player -->
                        @if($song->file_url)
                            <audio controls class="w-full mb-3" style="height: 32px;">
                                <source src="{{ asset('storage/' . $song->file_url) }}" type="audio/mpeg">
                                Trình duyệt của bạn không hỗ trợ trình phát nhạc.
                            </audio>
                        @else
                            <p class="text-xs text-red-400 mb-3">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Không có file nhạc
                            </p>
                        @endif

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('music.show', $song->id) }}"
                               class="flex-1 text-center py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition text-sm">
                                <i class="fas fa-info-circle mr-1"></i> Chi tiết
                            </a>
                            <button class="px-3 py-2 bg-gray-700 text-white rounded hover:bg-gray-600 transition text-sm">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Playlist Stats -->
        <div class="mt-12 bg-gray-800 rounded-xl p-8">
            <h3 class="text-2xl font-bold mb-6 text-purple-400">
                <i class="fas fa-chart-bar mr-2"></i> Thống kê playlist
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-400 mb-2">{{ $playlist->songs->count() }}</div>
                    <div class="text-sm text-gray-400">Bài hát</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-400 mb-2">{{ $playlist->songs->unique('artist_id')->count() }}</div>
                    <div class="text-sm text-gray-400">Nghệ sĩ</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-yellow-400 mb-2">{{ $playlist->songs->unique('genre_id')->count() }}</div>
                    <div class="text-sm text-gray-400">Thể loại</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-400 mb-2">{{ gmdate("H:i", $playlist->songs->sum('duration')) }}</div>
                    <div class="text-sm text-gray-400">Tổng thời lượng</div>
                </div>
            </div>
        </div>
    @endif
@endsection