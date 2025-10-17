@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>Thêm Bài Hát</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.songs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.songs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="title">Tên bài hát</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                
                <!-- Nghệ sĩ chính -->
                <div class="form-group">
                    <label for="artist_id">Nghệ sĩ chính <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-user-music"></i>
                            </span>
                        </div>
                        <select name="artist_id" class="form-control" required>
                            <option value="">-- Chọn nghệ sĩ chính --</option>
                            @foreach($artists as $artist)
                            <option value="{{ $artist->id }}">{{ $artist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Nghệ sĩ cộng tác (chọn nhiều) -->
                <div class="form-group">
                    <label for="collaborating_artist_ids">Nghệ sĩ cộng tác (tùy chọn)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                        <select name="collaborating_artist_ids[]" class="form-control" multiple>
                            @foreach($artists as $artist)
                                <option value="{{ $artist->id }}">{{ $artist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <small class="form-text text-muted">Giữ Ctrl (Windows) hoặc Cmd (Mac) để chọn nhiều nghệ sĩ cộng tác</small>
                </div>

                <!-- (Tùy chọn) Nhập nhanh nghệ sĩ mới bằng tên, phân cách dấu phẩy -->
                <div class="form-group">
                    <label for="collaborating_artists">Thêm nhanh nghệ sĩ cộng tác mới (tùy chọn)</label>
                    <input type="text" name="collaborating_artists" class="form-control" 
                           placeholder="Nhập tên nghệ sĩ mới, phân cách bằng dấu phẩy (VD: Ca sĩ A, Ca sĩ B)">
                    <small class="form-text text-muted">Nếu tên chưa tồn tại, hệ thống sẽ tạo mới tự động</small>
                </div>
                
                <!-- Thể loại (multiple selection) -->
                <div class="form-group">
                    <label for="genre_ids">Thể loại <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-tags"></i>
                            </span>
                        </div>
                        <select name="genre_ids[]" class="form-control" multiple required>
                            @foreach($genres as $genre)
                            <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <small class="form-text text-muted">Giữ Ctrl hoặc Cmd để chọn nhiều thể loại</small>
                </div>
                
                <!-- Upload file nhạc -->
                <div class="form-group">
                    <label for="audio_file">File Bài Hát <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-music"></i>
                            </span>
                        </div>
                        <input type="file" name="audio_file" class="form-control-file" accept="audio/*" onchange="checkFileSize(this)">
                    </div>
                    <small class="form-text text-muted">Chỉ chấp nhận file âm thanh (mp3, wav, ogg, m4a, aac) - Tối đa 5MB</small>
                    <div id="file-info" class="mt-2" style="display: none;">
                        <small class="text-info" id="file-details"></small>
                    </div>
                </div>
                
                <!-- Upload ảnh bìa -->
                <div class="form-group">
                    <label for="cover_image">Ảnh Bìa Bài Hát</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-image"></i>
                            </span>
                        </div>
                        <input type="file" name="cover_image" class="form-control-file" accept="image/*">
                    </div>
                    <small class="form-text text-muted">Tùy chọn: Upload ảnh bìa cho bài hát (jpg, png, gif, webp) - Tối đa 2MB</small>
                </div>
                
                <div class="form-group">
                    <label for="duration">Thời lượng (giây) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-clock"></i>
                            </span>
                        </div>
                        <input type="number" name="duration" class="form-control" required min="1">
                    </div>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> Lưu Bài Hát
                    </button>
                    <a href="{{ route('admin.songs.index') }}" class="btn btn-secondary btn-lg ml-2">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.input-group:focus-within .input-group-text {
    border-color: #80bdff;
    background-color: #e3f2fd;
}

select[multiple] {
    min-height: 120px;
}

select[multiple] option {
    padding: 8px 12px;
    border-bottom: 1px solid #f0f0f0;
}

select[multiple] option:hover {
    background-color: #f8f9fa;
}

select[multiple] option:checked {
    background-color: #007bff;
    color: white;
}
</style>

<script>
function checkFileSize(input) {
    const file = input.files[0];
    const fileInfo = document.getElementById('file-info');
    const fileDetails = document.getElementById('file-details');
    
    if (file) {
        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        const maxSizeMB = 5;
        
        fileInfo.style.display = 'block';
        fileDetails.innerHTML = `File: ${file.name} (${fileSizeMB}MB)`;
        
        if (file.size > maxSizeMB * 1024 * 1024) {
            fileDetails.className = 'text-danger';
            fileDetails.innerHTML += ` - <strong>File quá lớn! Tối đa ${maxSizeMB}MB</strong>`;
        } else {
            fileDetails.className = 'text-success';
            fileDetails.innerHTML += ' - OK';
        }
    } else {
        fileInfo.style.display = 'none';
    }
}
</script>
@endsection
