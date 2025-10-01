@extends('errors.layout.errors')

@section('title', 'Phiên làm việc đã hết hạn')
@section('code', '419')
@section('message', 'Oops! Phiên làm việc của bạn đã hết hạn.')
@section('description', 'Vui lòng làm mới trang hoặc quay lại trang trước đó.')
@section('image', '/images/site/419.png')

@section('button_secondary')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Quay lại trang chủ</a>
@endsection

@push('scripts')
    <script>
        setTimeout(function() {
            window.location.href = '/admin/dashboard';
        }, 5000);
    </script>
@endpush
