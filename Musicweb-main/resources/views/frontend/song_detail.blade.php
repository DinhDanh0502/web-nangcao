@extends('layouts.frontend')

@section('title', $song->title . ' - MusicWeb')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('music.index') }}" 
           class="inline-flex items-center text-purple-400 hover:text-purple-300 transition">
            <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
        </a>
    </div>

    <!-- Song Detail Card -->
    <div class="bg-gray-800 rounded-xl shadow-2xl overflow-hidden">
        <div class="md:flex">
            <!-- Album Cover -->
            <div class="md:w-1/3 bg-gradient-to-br from-purple-600 to-indigo-700">
                @if($song->cover_url)
                    <img src="{{ asset('storage/' . $song->cover_url) }}" 
                         alt="{{ $song->title }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center p-16">
                        <i class="fas fa-music text-9xl text-white opacity-50"></i>
                    </div>
                @endif
            </div>

            <!-- Song Info -->
            <div class="md:w-2/3 p-8">
                <!-- Title -->
                <h1 class="text-4xl font-bold text-white mb-6">{{ $song->title }}</h1>

                <!-- Meta Info -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-center text-gray-300">
                        <i class="fas fa-user-music w-8 text-purple-400"></i>
                        <div>
                            <span class="text-sm text-gray-500">Ca sĩ:</span>
                            <a href="{{ route('artist.show', $song->artist->id) }}" 
                               class="ml-2 text-lg font-semibold hover:text-purple-400 transition">
                                {{ $song->artist->name ?? 'Không rõ' }}
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center text-gray-300">
                        <i class="fas fa-tag w-8 text-purple-400"></i>
                        <div>
                            <span class="text-sm text-gray-500">Thể loại:</span>
                            <a href="{{ route('genre.show', $song->genre->id) }}" 
                               class="ml-2 text-lg font-semibold hover:text-purple-400 transition">
                                {{ $song->genre->name ?? 'Không rõ' }}
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center text-gray-300">
                        <i class="fas fa-clock w-8 text-purple-400"></i>
                        <div>
                            <span class="text-sm text-gray-500">Thời lượng:</span>
                            <span class="ml-2 text-lg font-semibold">{{ gmdate("i:s", $song->duration ?? 0) }}</span>
                        </div>
                    </div>

                    @if($song->release_date)
                        <div class="flex items-center text-gray-300">
                            <i class="fas fa-calendar w-8 text-purple-400"></i>
                            <div>
                                <span class="text-sm text-gray-500">Phát hành:</span>
                                <span class="ml-2 text-lg font-semibold">{{ \Carbon\Carbon::parse($song->release_date)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Audio Player -->
                <div class="bg-gray-900 rounded-lg p-6 mb-6">
                    <h5 class="text-lg font-semibold text-purple-400 mb-4 flex items-center">
                        <i class="fas fa-headphones mr-2"></i> Nghe bài hát
                    </h5>
                    @if ($song->file_url)
                        <audio id="audio-player" controls class="w-full" controlsList="nodownload">
                            <source src="{{ asset('storage/' . $song->file_url) }}" type="audio/mpeg">
                            Trình duyệt của bạn không hỗ trợ audio HTML5.
                        </audio>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-3"></i>
                            <p class="text-red-400">Không có file nhạc.</p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-3">
                    @auth
                    <form action="{{ route('favorites.toggle', $song->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-semibold">
                            <i class="fas fa-heart mr-2"></i> Yêu thích
                        </button>
                    </form>
                    @endauth
                    <button id="loop-toggle" class="flex-1 px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition font-semibold">
                        <i class="fas fa-redo mr-2"></i> Lặp lại: Tắt
                    </button>
                    <button class="flex-1 px-6 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition font-semibold">
                        <i class="fas fa-share mr-2"></i> Chia sẻ
                    </button>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const loopBtn = document.getElementById('loop-toggle');
                        const audio = document.getElementById('audio-player');
                        if (loopBtn && audio) {
                            loopBtn.addEventListener('click', function() {
                                audio.loop = !audio.loop;
                                loopBtn.textContent = 'Lặp lại: ' + (audio.loop ? 'Bật' : 'Tắt');
                            });
                        }
                    });
                </script>
            </div>
        </div>
    </div>

    <!-- Lyrics Section (Optional) -->
    @if(isset($song->lyrics) && $song->lyrics)
        <div class="mt-8 bg-gray-800 rounded-xl p-8">
            <h3 class="text-2xl font-bold text-purple-400 mb-6 flex items-center">
                <i class="fas fa-align-left mr-3"></i> Lời bài hát
            </h3>
            <div class="text-gray-300 whitespace-pre-line leading-relaxed">
                {{ $song->lyrics }}
            </div>
        </div>
    @endif

    <!-- Related Songs -->
    @if(isset($relatedSongs) && $relatedSongs->count() > 0)
        <div class="mt-8">
            <h3 class="text-2xl font-bold text-purple-400 mb-6 flex items-center">
                <i class="fas fa-compact-disc mr-3"></i> Bài hát liên quan
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($relatedSongs as $relatedSong)
                    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden transition transform hover:scale-105 duration-300">
                        <div class="relative h-40 bg-gradient-to-br from-purple-600 to-indigo-700">
                            @if($relatedSong->cover_url)
                                <img src="{{ asset('storage/' . $relatedSong->cover_url) }}" 
                                     alt="{{ $relatedSong->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-music text-5xl text-white opacity-50"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h4 class="text-white font-semibold mb-1 truncate">{{ $relatedSong->title }}</h4>
                            <p class="text-sm text-gray-400 truncate">{{ $relatedSong->artist->name ?? 'Unknown' }}</p>
                            <a href="{{ route('music.show', $relatedSong->id) }}"
                               class="block mt-3 text-center py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition text-sm">
                                <i class="fas fa-play mr-1"></i> Nghe
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection