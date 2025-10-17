@extends('layouts.frontend')

@section('title', 'Nghệ sĩ - MusicWeb')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl md:text-4xl font-bold mb-2 text-yellow-400">🎤 Danh sách nghệ sĩ</h2>
        <p class="text-gray-400">Khám phá các nghệ sĩ tài năng</p>
    </div>

    @if($artists->isEmpty())
        <div class="text-center py-16">
            <i class="fas fa-microphone text-6xl text-gray-700 mb-4"></i>
            <p class="text-gray-400 text-xl">Không có nghệ sĩ nào được tìm thấy.</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            @foreach($artists as $artist)
                <div class="bg-gray-800 p-6 rounded-xl text-center shadow-lg hover:shadow-2xl transform hover:-translate-y-2 transition duration-300 group">
                    
                    {{-- Avatar --}}
                    @if($artist->avatar)
                        <div class="relative mb-4">
                            <img src="{{ asset('storage/' . $artist->avatar) }}"
                                 alt="{{ $artist->name }}"
                                 class="w-24 h-24 mx-auto rounded-full object-cover border-4 border-purple-500 shadow-lg group-hover:border-yellow-400 transition">
                            <div class="absolute inset-0 w-24 h-24 mx-auto rounded-full bg-black bg-opacity-0 group-hover:bg-opacity-30 transition flex items-center justify-center">
                                <i class="fas fa-play text-white text-2xl opacity-0 group-hover:opacity-100 transition"></i>
                            </div>
                        </div>
                    @else
                        <div class="relative mb-4">
                            <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-purple-600 to-yellow-500 text-white flex items-center justify-center text-3xl font-bold shadow-lg group-hover:scale-110 transition">
                                {{ strtoupper(substr($artist->name, 0, 1)) }}
                            </div>
                        </div>
                    @endif

                    {{-- Tên nghệ sĩ --}}
                    <h3 class="text-base font-semibold text-white mb-1 truncate px-2" title="{{ $artist->name }}">
                        {{ $artist->name }}
                    </h3>

                    {{-- Quốc gia --}}
                    <p class="text-xs text-gray-400 mb-3 truncate">
                        <i class="fas fa-globe mr-1"></i> {{ $artist->country ?? 'Không rõ' }}
                    </p>

                    {{-- Số bài hát --}}
                    @if(isset($artist->songs_count))
                        <p class="text-xs text-purple-400 mb-3">
                            <i class="fas fa-music mr-1"></i> {{ $artist->songs_count }} bài hát
                        </p>
                    @endif

                    {{-- Nút chi tiết --}}
                    <a href="{{ route('artist.show', $artist->id) }}"
                       class="inline-block w-full px-4 py-2 bg-yellow-500 text-black rounded-lg hover:bg-yellow-600 text-sm transition font-semibold">
                        <i class="fas fa-eye mr-1"></i> Chi tiết
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($artists->hasPages())
            <div class="mt-8">
                {{ $artists->links() }}
            </div>
        @endif
    @endif
@endsection
