@extends('layouts.frontend')

@section('title', 'Thể loại - MusicWeb')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl md:text-4xl font-bold mb-2 text-purple-400">🎼 Danh sách thể loại</h2>
        <p class="text-gray-400">Khám phá âm nhạc theo thể loại yêu thích</p>
    </div>

    @if($genres->isEmpty())
        <div class="text-center py-16">
            <i class="fas fa-list-music text-6xl text-gray-700 mb-4"></i>
            <p class="text-gray-400 text-xl">Không có thể loại nào được tìm thấy.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($genres as $genre)
                <a href="{{ route('genre.show', $genre->id) }}"
                   class="group bg-gradient-to-br from-purple-600 to-indigo-700 text-white rounded-xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 block relative overflow-hidden">
                    
                    {{-- Background Pattern --}}
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute transform rotate-45 -right-10 -top-10 w-32 h-32 bg-white rounded-full"></div>
                        <div class="absolute transform -rotate-12 -left-10 -bottom-10 w-40 h-40 bg-white rounded-full"></div>
                    </div>

                    {{-- Content --}}
                    <div class="relative z-10">
                        {{-- Icon --}}
                        <div class="mb-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-music text-3xl text-white"></i>
                            </div>
                        </div>

                        {{-- Tên thể loại --}}
                        <h3 class="text-2xl font-bold mb-3 group-hover:text-yellow-300 transition">
                            {{ $genre->name }}
                        </h3>

                        {{-- Mô tả --}}
                        <p class="text-sm text-purple-100 mb-4 line-clamp-2">
                            {{ $genre->description ?? 'Khám phá các bài hát thuộc thể loại ' . $genre->name }}
                        </p>

                        {{-- Số bài hát --}}
                        @if(isset($genre->songs_count))
                            <div class="flex items-center text-purple-200 text-sm mb-4">
                                <i class="fas fa-compact-disc mr-2"></i>
                                <span>{{ $genre->songs_count }} bài hát</span>
                            </div>
                        @endif

                        {{-- Call to action --}}
                        <div class="flex items-center text-white text-sm font-semibold group-hover:translate-x-2 transition-transform">
                            <span>Khám phá ngay</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($genres->hasPages())
            <div class="mt-8">
                {{ $genres->links() }}
            </div>
        @endif
    @endif

    {{-- Thống kê thể loại --}}
    @if(!$genres->isEmpty() && isset($genres[0]->songs_count))
        <div class="mt-16 bg-gray-800 rounded-xl p-8">
            <h3 class="text-2xl font-bold mb-6 text-purple-400">
                <i class="fas fa-chart-bar mr-2"></i> Thống kê thể loại
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-400 mb-2">{{ $genres->count() }}</div>
                    <div class="text-sm text-gray-400">Tổng thể loại</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-400 mb-2">{{ $genres->sum('songs_count') }}</div>
                    <div class="text-sm text-gray-400">Tổng bài hát</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-400 mb-2">{{ $genres->max('songs_count') }}</div>
                    <div class="text-sm text-gray-400">Nhiều nhất</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-400 mb-2">{{ round($genres->avg('songs_count')) }}</div>
                    <div class="text-sm text-gray-400">Trung bình</div>
                </div>
            </div>
        </div>
    @endif
@endsection