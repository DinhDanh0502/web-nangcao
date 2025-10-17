@extends('layouts.frontend')

@section('title', 'Trang chủ - MusicWeb')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl md:text-4xl font-bold mb-2 text-purple-400">🎵 Danh sách bài hát</h2>
        <p class="text-gray-400">Khám phá hàng ngàn bài hát yêu thích của bạn</p>
    </div>

    <!-- Form tìm kiếm -->
    <form action="{{ route('music.index') }}" method="GET" class="mb-8">
        <div class="flex flex-col md:flex-row gap-3">
            <input type="text" 
                   name="keyword" 
                   placeholder="🔍 Tìm kiếm bài hát, nghệ sĩ..." 
                   value="{{ request('keyword') }}"
                   class="flex-1 px-4 py-3 rounded-lg text-black bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            <button type="submit" 
                    class="px-6 py-3 bg-purple-600 rounded-lg text-white hover:bg-purple-700 transition duration-300 font-semibold">
                <i class="fas fa-search mr-2"></i> Tìm kiếm
            </button>
            @if(request('keyword'))
                <a href="{{ route('music.index') }}" 
                   class="px-6 py-3 bg-gray-700 rounded-lg text-white hover:bg-gray-600 transition duration-300 text-center">
                    <i class="fas fa-times mr-2"></i> Xóa
                </a>
            @endif
        </div>
    </form>

    @if(request('keyword'))
        <div class="mb-6">
            <p class="text-gray-400">
                Kết quả tìm kiếm cho: <span class="text-purple-400 font-semibold">"{{ request('keyword') }}"</span>
                ({{ $songs->total() }} bài hát)
            </p>
        </div>
    @endif

    @if($songs->isEmpty())
        <div class="text-center py-16">
            <i class="fas fa-music text-6xl text-gray-700 mb-4"></i>
            <p class="text-gray-400 text-xl">Không có bài hát nào được tìm thấy.</p>
            @if(request('keyword'))
                <a href="{{ route('music.index') }}" 
                   class="inline-block mt-4 px-6 py-3 bg-purple-600 rounded-lg text-white hover:bg-purple-700 transition">
                    Xem tất cả bài hát
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($songs as $song)
                <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden transition transform hover:scale-105 hover:shadow-2xl duration-300">
                    <!-- Album Cover -->
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
                        <div class="absolute top-2 right-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                            {{ gmdate("i:s", $song->duration ?? 0) }}
                        </div>
                    </div>

                    <!-- Song Info -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-white mb-1 truncate" title="{{ $song->title }}">
                            {{ $song->title }}
                        </h3>
                        <p class="text-sm text-gray-400 mb-1 truncate" title="{{ $song->artist->name ?? 'Không rõ nghệ sĩ' }}">
                            <i class="fas fa-user-music mr-1"></i> {{ $song->artist->name ?? 'Không rõ nghệ sĩ' }}
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

                        <!-- Detail Button -->
                        <a href="{{ route('music.show', $song->id) }}"
                           class="block text-center py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition duration-300">
                            <i class="fas fa-play-circle mr-1"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $songs->links() }}
        </div>
    @endif

    <!-- Playlist nổi bật -->
    <div class="mt-16">
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold mb-2 text-purple-400">🔥 Playlist nổi bật</h2>
            <p class="text-gray-400">Các playlist được tuyển chọn đặc biệt cho bạn</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('music.playlist', ['name' => 'Chill Vibes']) }}"
               class="group bg-gradient-to-br from-purple-600 to-purple-800 p-8 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 block">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-headphones text-3xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white">Chill Vibes</h3>
                        <p class="text-purple-200">Thư giãn</p>
                    </div>
                </div>
                <p class="text-sm text-purple-100 mb-4">Âm nhạc thư giãn mỗi ngày, giúp bạn tìm lại sự bình yên.</p>
                <div class="flex items-center text-purple-200 text-sm group-hover:text-white transition">
                    <span>Khám phá ngay</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <a href="{{ route('music.playlist', ['name' => 'Trending']) }}"
               class="group bg-gradient-to-br from-blue-600 to-blue-800 p-8 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 block">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-fire text-3xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white">Trending</h3>
                        <p class="text-blue-200">Xu hướng</p>
                    </div>
                </div>
                <p class="text-sm text-blue-100 mb-4">Top hit đang hot nhất hiện nay, cập nhật liên tục.</p>
                <div class="flex items-center text-blue-200 text-sm group-hover:text-white transition">
                    <span>Khám phá ngay</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>

            <a href="{{ route('music.playlist', ['name' => 'Buồn Lặng']) }}"
               class="group bg-gradient-to-br from-pink-600 to-pink-800 p-8 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 block">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-heart-broken text-3xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-white">Buồn Lặng</h3>
                        <p class="text-pink-200">Tâm trạng</p>
                    </div>
                </div>
                <p class="text-sm text-pink-100 mb-4">Những giai điệu sâu lắng cho tâm trạng của bạn.</p>
                <div class="flex items-center text-pink-200 text-sm group-hover:text-white transition">
                    <span>Khám phá ngay</span>
                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </a>
        </div>
    </div>
@endsection