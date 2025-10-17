@extends('layouts.frontend')

@section('title', 'Trang cá nhân')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Profile card -->
    <div class="md:col-span-1 bg-gray-800 rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-semibold mb-4 text-yellow-400">Hồ sơ</h2>
        <!-- Avatar hiển thị (không cho đổi) -->
        <div class="flex items-center gap-4 mb-4">
            <div class="w-24 h-24">
                @if($user->avatar)
                    <img src="{{ '/storage/' . $user->avatar }}" class="w-24 h-24 object-cover rounded-full border-4 border-purple-500" alt="avatar">
                @else
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-600 to-yellow-500 text-white flex items-center justify-center text-3xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div>
                <p class="text-lg font-semibold">{{ $user->name }}</p>
                <p class="text-sm text-gray-400">{{ $user->email }}</p>
            </div>
        </div>
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm text-gray-400 mb-1">Tên hiển thị</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 rounded bg-gray-900 border border-gray-700" required />
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Gmail</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 rounded bg-gray-900 border border-gray-700" required />
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Thể loại nhạc yêu thích</label>
                <input type="text" name="favorite_genres" value="{{ old('favorite_genres', $user->favorite_genres) }}" class="w-full px-3 py-2 rounded bg-gray-900 border border-gray-700" />
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Nghệ sĩ yêu thích</label>
                <input type="text" name="favorite_artists" value="{{ old('favorite_artists', $user->favorite_artists) }}" class="w-full px-3 py-2 rounded bg-gray-900 border border-gray-700" />
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Giới thiệu</label>
                <textarea name="bio" rows="4" class="w-full px-3 py-2 rounded bg-gray-900 border border-gray-700">{{ old('bio', $user->bio) }}</textarea>
            </div>
            <button class="w-full px-4 py-2 bg-purple-600 rounded hover:bg-purple-700 transition font-semibold">Lưu</button>
        </form>
    </div>

    <!-- Favorites -->
    <div class="md:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-yellow-400">Bài hát yêu thích</h2>
        </div>

        @if($favorites->isEmpty())
            <p class="text-gray-400">Bạn chưa yêu thích bài hát nào.</p>
        @else
            <!-- Favorites Player Controls -->
            <div class="bg-gray-800 rounded-lg p-4 mb-6 flex items-center gap-3 shadow">
                <button id="prevBtn" class="px-3 py-2 bg-gray-700 rounded hover:bg-gray-600" aria-label="Bài trước" title="Bài trước">‹</button>
                <button id="playPauseBtn" class="px-4 py-2 bg-purple-600 rounded hover:bg-purple-700 font-semibold" aria-label="Phát/Tạm dừng" title="Phát/Tạm dừng">▶︎/⏸</button>
                <button id="nextBtn" class="px-3 py-2 bg-gray-700 rounded hover:bg-gray-600" aria-label="Bài tiếp" title="Bài tiếp">›</button>
                <button id="shuffleBtn" class="px-3 py-2 bg-gray-700 rounded hover:bg-gray-600" aria-label="Ngẫu nhiên" title="Ngẫu nhiên">🔀 Ngẫu nhiên: Tắt</button>
                <button id="repeatAllBtn" class="px-3 py-2 bg-gray-700 rounded hover:bg-gray-600" aria-label="Lặp danh sách" title="Lặp danh sách">🔁 Lặp danh sách: Tắt</button>
                <div class="flex-1 text-right text-sm text-gray-400"><span id="nowPlaying">Chưa phát</span></div>
            </div>
            <audio id="favPlayer" class="hidden"></audio>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($favorites as $idx => $song)
                    <div class="bg-gray-800 rounded-lg shadow overflow-hidden" data-index="{{ $idx }}" data-src="{{ $song->file_url ? asset('storage/' . $song->file_url) : '' }}" data-title="{{ $song->title }}" data-artist="{{ $song->artist->name ?? 'Không rõ nghệ sĩ' }}">
                        <div class="relative h-40 bg-gradient-to-br from-purple-600 to-indigo-700">
                            @if($song->cover_url)
                                <img src="{{ asset('storage/' . $song->cover_url) }}" class="w-full h-full object-cover" alt="{{ $song->title }}" />
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-music text-5xl text-white opacity-50"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-white font-semibold truncate" title="{{ $song->title }}">{{ $song->title }}</h3>
                            <p class="text-xs text-gray-400 truncate">{{ $song->artist->name ?? 'Không rõ nghệ sĩ' }} • {{ $song->genre->name ?? 'Thể loại khác' }}</p>
                            <div class="mt-3 flex gap-2">
                                <!-- Phát -->
                                <button class="playFavBtn px-3 py-2 bg-purple-600 rounded hover:bg-purple-700 text-white" title="Phát" aria-label="Phát">▶︎</button>
                                <!-- Chuyển bài (tiếp theo) -->
                                <button class="nextFavBtn px-3 py-2 bg-gray-700 rounded hover:bg-gray-600 text-white" title="Bài tiếp" aria-label="Bài tiếp">›</button>
                                <!-- Xóa khỏi yêu thích -->
                                <form action="{{ route('favorites.toggle', $song->id) }}" method="POST">
                                    @csrf
                                    <button class="px-3 py-2 bg-red-700 rounded hover:bg-red-600 text-white" title="Xóa khỏi yêu thích" aria-label="Xóa khỏi yêu thích">✖︎</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">{{ $favorites->links() }}</div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const player = document.getElementById('favPlayer');
                    const tiles = Array.from(document.querySelectorAll('[data-index]'));
                    const nowPlaying = document.getElementById('nowPlaying');
                    const playPauseBtn = document.getElementById('playPauseBtn');
                    const prevBtn = document.getElementById('prevBtn');
                    const nextBtn = document.getElementById('nextBtn');
                    const shuffleBtn = document.getElementById('shuffleBtn');
                    const repeatAllBtn = document.getElementById('repeatAllBtn');

                    let order = tiles.map((t, i) => i); // default order 0..n-1
                    let idx = 0;
                    let isPlaying = false;
                    let shuffle = false;
                    let repeatAll = false;

                    function load(i) {
                        idx = i;
                        const tile = tiles[order[idx]];
                        const src = tile.getAttribute('data-src');
                        if (!src) { return; }
                        player.src = src;
                        nowPlaying.textContent = tile.getAttribute('data-title') + ' — ' + tile.getAttribute('data-artist');
                    }

                    function play() {
                        player.play();
                        isPlaying = true;
                        playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                    }

                    function pause() {
                        player.pause();
                        isPlaying = false;
                        playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
                    }

                    playPauseBtn.addEventListener('click', function() {
                        if (!player.src) { load(0); }
                        isPlaying ? pause() : play();
                    });

                    prevBtn.addEventListener('click', function() {
                        if (order.length === 0) return;
                        idx = (idx - 1 + order.length) % order.length;
                        load(idx); play();
                    });

                    nextBtn.addEventListener('click', function() {
                        next();
                    });

                    function next() {
                        if (order.length === 0) return;
                        if (idx + 1 < order.length) {
                            idx += 1;
                            load(idx); play();
                        } else if (repeatAll) {
                            idx = 0; load(idx); play();
                        } else {
                            pause();
                        }
                    }

                    shuffleBtn.addEventListener('click', function() {
                        shuffle = !shuffle;
                        shuffleBtn.textContent = 'Ngẫu nhiên: ' + (shuffle ? 'Bật' : 'Tắt');
                        if (shuffle) {
                            // Fisher–Yates shuffle of order
                            order = tiles.map((_, i) => i);
                            for (let i = order.length - 1; i > 0; i--) {
                                const j = Math.floor(Math.random() * (i + 1));
                                [order[i], order[j]] = [order[j], order[i]];
                            }
                        } else {
                            order = tiles.map((_, i) => i);
                        }
                        load(0); if (isPlaying) play();
                    });

                    repeatAllBtn.addEventListener('click', function() {
                        repeatAll = !repeatAll;
                        repeatAllBtn.textContent = 'Lặp danh sách: ' + (repeatAll ? 'Bật' : 'Tắt');
                    });

                    player.addEventListener('ended', next);

                    document.querySelectorAll('.playFavBtn').forEach(btn => {
                        btn.addEventListener('click', function(e) {
                            const tile = e.target.closest('[data-index]');
                            const i = parseInt(tile.getAttribute('data-index')) || 0;
                            // map to order index
                            load(order.indexOf(i));
                            play();
                        });
                    });

                    document.querySelectorAll('.nextFavBtn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            if (!player.src) { load(0); }
                            next();
                        });
                    });
                });
            </script>
        @endif
    </div>
</div>
@endsection
