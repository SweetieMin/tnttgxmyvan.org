@extends('errors.layout.errors')

@section('title', 'Trang không tồn tại')
@section('code', '404')
@section('message', 'Oops! Trang bạn tìm kiếm không tồn tại.')
@section('description', 'Đường dẫn trang của bạn không đúng hoặc đã bị xóa.')
@section('image', '/images/site/404.jpg')

@section('button_secondary')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Quay lại trang chủ</a>
@endsection
