@extends('adminlte::page')

@section('title', 'Bảng Điều Khiển - Admin')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-tachometer-alt mr-2"></i>Bảng Điều Khiển</h1>
        <small class="text-muted">Tổng quan hệ thống</small>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Card Tổng số Người dùng -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $userCount }}</h3>
                <p>Người Dùng</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ url('admin/users') }}" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Tổng số Bài hát -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $songCount }}</h3>
                <p>Bài Hát</p>
            </div>
            <div class="icon">
                <i class="fas fa-music"></i>
            </div>
            <a href="{{ url('admin/songs') }}" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Tổng số Nghệ sĩ -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $artistCount }}</h3>
                <p>Nghệ Sĩ</p>
            </div>
            <div class="icon">
                <i class="fas fa-microphone"></i>
            </div>
            <a href="{{ url('admin/artists') }}" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Tổng số Thể loại -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $genreCount }}</h3>
                <p>Thể Loại</p>
            </div>
            <div class="icon">
                <i class="fas fa-tags"></i>
            </div>
            <a href="{{ url('admin/genres') }}" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Row 2: Charts and Statistics -->
<div class="row">
    <!-- Bài hát mới nhất -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="card-title">
                        <i class="fas fa-music mr-2"></i>Bài Hát Mới Nhất
                    </h3>
                    <a href="{{ url('admin/songs') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye mr-1"></i>Xem tất cả
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu Đề</th>
                            <th>Nghệ Sĩ</th>
                            <th>Thể Loại</th>
                            <th>Thời Lượng</th>
                            <th>Ngày Tạo</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Song::with(['artist', 'genre'])->latest()->take(5)->get() as $song)
                        <tr>
                            <td>{{ $song->id }}</td>
                            <td>
                                <strong>{{ $song->title }}</strong>
                            </td>
                            <td>
                                <i class="fas fa-user-music mr-1 text-muted"></i>
                                {{ $song->artist->name ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $song->genre->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <i class="fas fa-clock mr-1 text-muted"></i>
                                {{ gmdate("i:s", $song->duration ?? 0) }}
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $song->created_at->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('music.show', $song->id) }}" 
                                   class="btn btn-xs btn-success" 
                                   target="_blank"
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ url('admin/songs/' . $song->id . '/edit') }}" 
                                   class="btn btn-xs btn-primary" 
                                   title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-music fa-2x mb-2"></i>
                                <p>Chưa có bài hát nào</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-2"></i>Thao Tác Nhanh
                </h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ url('admin/songs/create') }}" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-plus-circle mr-2"></i>Thêm Bài Hát Mới
                    </a>
                    <a href="{{ url('admin/artists/create') }}" class="btn btn-warning btn-block mb-2">
                        <i class="fas fa-user-plus mr-2"></i>Thêm Nghệ Sĩ Mới
                    </a>
                    <a href="{{ url('admin/genres/create') }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-tag mr-2"></i>Thêm Thể Loại Mới
                    </a>
                    <a href="{{ url('admin/playlists/create') }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-list-ul mr-2"></i>Tạo Playlist Mới
                    </a>
                    <hr>
                    <a href="{{ route('music.index') }}" class="btn btn-outline-secondary btn-block" target="_blank">
                        <i class="fas fa-external-link-alt mr-2"></i>Xem Trang Frontend
                    </a>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card mt-3">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>Thông Tin Hệ Thống
                </h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-server text-primary mr-2"></i>
                        <strong>Phiên bản:</strong> 1.0.0
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-code text-success mr-2"></i>
                        <strong>Laravel:</strong> {{ app()->version() }}
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-database text-info mr-2"></i>
                        <strong>Database:</strong> MySQL
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar text-warning mr-2"></i>
                        <strong>Hôm nay:</strong> {{ now()->format('d/m/Y') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Statistics -->
<div class="row mt-3">
    <!-- Top Genres -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-2"></i>Thể Loại Phổ Biến
                </h3>
            </div>
            <div class="card-body">
                @php
                    $topGenres = \App\Models\Genre::withCount('songs')
                        ->orderBy('songs_count', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                
                @if($topGenres->count() > 0 && $songCount > 0)
                    @foreach($topGenres as $genre)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ $genre->name }}</span>
                                <span class="badge badge-primary">{{ $genre->songs_count }} bài hát</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" 
                                     role="progressbar" 
                                     style="width: {{ ($genre->songs_count / $songCount) * 100 }}%" 
                                     aria-valuenow="{{ $genre->songs_count }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $songCount }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">Chưa có dữ liệu</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Top Artists -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-star mr-2"></i>Nghệ Sĩ Nổi Bật
                </h3>
            </div>
            <div class="card-body">
                @php
                    $topArtists = \App\Models\Artist::withCount('songs')
                        ->orderBy('songs_count', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                
                @if($topArtists->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($topArtists as $artist)
                            <div class="list-group-item px-0">
                                <div class="d-flex align-items-center">
                                    @if($artist->avatar_url)
                                        <img src="{{ asset('storage/' . $artist->avatar_url) }}" 
                                             alt="{{ $artist->name }}" 
                                             class="rounded-circle mr-3" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-warning text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-weight: bold;">
                                            {{ strtoupper(substr($artist->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <strong>{{ $artist->name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-music mr-1"></i>{{ $artist->songs_count }} bài hát
                                        </small>
                                    </div>
                                   
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center">Chưa có dữ liệu</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .small-box {
        border-radius: 0.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }
    .progress-bar {
        border-radius: 10px;
    }
    .d-grid {
        display: grid;
    }
    .gap-2 {
        gap: 0.5rem;
    }
</style>
@endsection

@section('js')
<script>
    console.log('Dashboard loaded successfully!');
    
    // Add animation to small boxes
    document.addEventListener('DOMContentLoaded', function() {
        const boxes = document.querySelectorAll('.small-box');
        boxes.forEach((box, index) => {
            setTimeout(() => {
                box.style.opacity = '0';
                box.style.transform = 'translateY(20px)';
                box.style.transition = 'all 0.5s ease';
                
                setTimeout(() => {
                    box.style.opacity = '1';
                    box.style.transform = 'translateY(0)';
                }, 100);
            }, index * 100);
        });
    });
</script>
@endsection