<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MusicWeb - Nghe nhạc trực tuyến')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2d9d5d66a.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans">
    
    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ asset('images/logo.png') }}" alt="Loading..." style="width: 80px;">
    </div>

    <!-- Navigation -->
    <nav class="bg-gray-800 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('music.index') }}" class="text-2xl font-bold text-purple-400 hover:text-purple-300 transition">
                    🎵 MusicWeb
                </a>
                
                <!-- Desktop Menu -->
                <ul class="hidden md:flex gap-6 items-center">
                    <li>
                        <a href="{{ route('music.index') }}" 
                           class="hover:text-purple-300 transition {{ request()->routeIs('music.index') ? 'text-purple-400 font-semibold' : '' }}">
                            <i class="fas fa-home mr-1"></i> Trang chủ
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('artists.index') }}" 
                           class="hover:text-purple-300 transition {{ request()->routeIs('artists.index') || request()->routeIs('artist.show') ? 'text-purple-400 font-semibold' : '' }}">
                            <i class="fas fa-microphone mr-1"></i> Nghệ sĩ
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('genres.index') }}" 
                           class="hover:text-purple-300 transition {{ request()->routeIs('genres.index') || request()->routeIs('genre.show') ? 'text-purple-400 font-semibold' : '' }}">
                            <i class="fas fa-list mr-1"></i> Thể loại
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('playlists.index') }}" 
                           class="hover:text-purple-300 transition {{ request()->routeIs('playlists.index') ? 'text-purple-400 font-semibold' : '' }}">
                            <i class="fas fa-compact-disc mr-1"></i> Playlist
                        </a>
                    </li>
                    @auth
                        <li>
                            <a href="{{ route('profile.show') }}" 
                               class="hover:text-purple-300 transition {{ request()->routeIs('profile.show') ? 'text-purple-400 font-semibold' : '' }}">
                                <i class="fas fa-user mr-1"></i> Cá nhân
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="hover:text-red-400 transition">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}" 
                               class="px-4 py-2 bg-purple-600 rounded hover:bg-purple-700 transition">
                                <i class="fas fa-sign-in-alt mr-1"></i> Đăng nhập
                            </a>
                        </li>
                    @endauth
                </ul>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <ul id="mobile-menu" class="hidden md:hidden mt-4 space-y-2">
                <li>
                    <a href="{{ route('music.index') }}" 
                       class="block py-2 hover:text-purple-300 transition">
                        <i class="fas fa-home mr-2"></i> Trang chủ
                    </a>
                </li>
                <li>
                    <a href="{{ route('artists.index') }}" 
                       class="block py-2 hover:text-purple-300 transition">
                        <i class="fas fa-microphone mr-2"></i> Nghệ sĩ
                    </a>
                </li>
                <li>
                    <a href="{{ route('genres.index') }}" 
                       class="block py-2 hover:text-purple-300 transition">
                        <i class="fas fa-list mr-2"></i> Thể loại
                    </a>
                </li>
                <li>
                    <a href="{{ route('playlists.index') }}" 
                       class="block py-2 hover:text-purple-300 transition">
                        <i class="fas fa-compact-disc mr-2"></i> Playlist
                    </a>
                </li>
                @auth
                    <li>
                        <a href="{{ route('profile.show') }}" class="block py-2 hover:text-purple-300 transition">
                            <i class="fas fa-user mr-2"></i> Cá nhân
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block py-2 w-full text-left hover:text-red-400 transition">
                                <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                            </button>
                        </form>
                    </li>
                @else
                    <li>
                        <a href="{{ route('login') }}" 
                           class="block py-2 hover:text-purple-300 transition">
                            <i class="fas fa-sign-in-alt mr-2"></i> Đăng nhập
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-10 px-4 md:px-6 lg:px-16 max-w-7xl mx-auto">
        @if(session('success'))
            <div class="bg-green-500 text-white px-4 py-3 rounded mb-6 flex items-center justify-between">
                <span><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 text-white px-4 py-3 rounded mb-6 flex items-center justify-between">
                <span><i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-center py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-400">© 2025 MusicWeb. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-purple-400 transition">
                        <i class="fab fa-facebook fa-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-purple-400 transition">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-purple-400 transition">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-purple-400 transition">
                        <i class="fab fa-youtube fa-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script -->
    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const btn = document.getElementById('mobile-menu-btn');
            if (!menu.contains(event.target) && !btn.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>