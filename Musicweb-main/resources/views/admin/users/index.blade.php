@extends('adminlte::page')

@section('title', 'Quản lý Người dùng')

@section('content_header')
    <h1>Quản lý Người dùng</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Danh sách Người dùng</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm Người dùng Mới
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tìm kiếm và Lọc</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" id="searchForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Từ khóa</label>
                            <input type="text" 
                                   name="keyword" 
                                   class="form-control"
                                   placeholder="Tìm kiếm..." 
                                   value="{{ request('keyword') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Tìm theo</label>
                            <select name="search_by" class="form-control">
                                <option value="all" {{ request('search_by') == 'all' ? 'selected' : '' }}>Tất cả</option>
                                <option value="name" {{ request('search_by') == 'name' ? 'selected' : '' }}>Tên</option>
                                <option value="email" {{ request('search_by') == 'email' ? 'selected' : '' }}>Email</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Sắp xếp</label>
                            <select name="sort_by" class="form-control">
                                <option value="id_desc" {{ request('sort_by') == 'id_desc' ? 'selected' : '' }}>ID (Mới nhất)</option>
                                <option value="id_asc" {{ request('sort_by') == 'id_asc' ? 'selected' : '' }}>ID (Cũ nhất)</option>
                                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên (A-Z)</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên (Z-A)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Số mục</label>
                            <select name="per_page" class="form-control">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 mục</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 mục</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 mục</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 mục</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Tìm kiếm
                                </button>
                                <button type="button" class="btn btn-secondary" id="resetSearch">
                                    <i class="fas fa-redo"></i> Làm mới
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Danh sách 
                @if(isset($users) && method_exists($users, 'total'))
                    ({{ $users->total() }} người dùng)
                @endif
            </h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">ID</th>
                        <th width="25%">Tên</th>
                        <th width="25%">Email</th>
                        <th width="15%">Vai trò</th>
                        <th width="15%">Ngày tạo</th>
                        <th width="15%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-danger">Admin</span>
                            @else
                                <span class="badge badge-secondary">User</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" 
                               class="btn btn-sm btn-info" 
                               title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(auth()->id() === $user->id)
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="btn btn-sm btn-warning" 
                                   title="Chỉnh sửa hồ sơ của tôi">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @else
                                <button class="btn btn-sm btn-warning" disabled title="Chỉ được sửa hồ sơ của chính mình">
                                    <i class="fas fa-edit"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Không có người dùng nào được tìm thấy.</p>
                            @if(request('keyword'))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-list"></i> Xem tất cả
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($users) && method_exists($users, 'links'))
        <div class="card-footer clearfix">
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@stop

@section('css')
<style>
    .table td {
        vertical-align: middle;
    }
    .card-title {
        font-weight: 600;
    }
</style>
@stop

@section('js')
<script>
    // Reset form tìm kiếm
    document.getElementById('resetSearch').addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = "{{ route('admin.users.index') }}";
    });

    // Auto submit khi thay đổi dropdown
    document.querySelectorAll('select[name="per_page"], select[name="sort_by"]')
        .forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('searchForm').submit();
            });
        });

    // Auto dismiss alerts sau 5 giây
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@stop