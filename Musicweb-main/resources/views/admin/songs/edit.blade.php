@extends('adminlte::page')

@section('title', 'Chỉnh sửa Bài Hát')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Chỉnh sửa Bài Hát</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.songs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.songs.update', $song->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Tên bài hát</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $song->title) }}" required>
                </div>

                <div class="form-group">
                    <label for="artist_id">Nghệ sĩ</label>
                    <select name="artist_id" class="form-control" required>
                        @foreach ($artists as $artist)
                            <option value="{{ $artist->id }}" {{ $artist->id == $song->artist_id ? 'selected' : '' }}>
                                {{ $artist->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="genre_id">Thể loại</label>
                    <select name="genre_id" class="form-control" required>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->id }}" {{ $genre->id == $song->genre_id ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="audio_file">Upload file nhạc </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-music"></i>
                            </span>
                        </div>
                        <input type="file" name="audio_file" class="form-control-file" accept="audio/*">
                    </div>
                    <small class="form-text text-muted">Chỉ chấp nhận file mp3, wav, ogg, m4a, aac</small>
                    @if($song->file_url)
                        <div class="mt-2">
                            <audio controls class="w-100" style="height: 32px;">
                                <source src="{{ asset('storage/' . $song->file_url) }}" type="audio/mpeg">
                            </audio>
                            <small class="text-muted">File hiện tại: {{ basename($song->file_url) }}</small>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="duration">Thời lượng (giây)</label>
                    <input type="number" name="duration" class="form-control" value="{{ old('duration', $song->duration) }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection

