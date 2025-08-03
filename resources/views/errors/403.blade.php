@extends('errors.layout.errors')

@section('title', 'Truy cập bị từ chối')
@section('code', '403')
@section('message', 'Oops! Bạn không có quyền truy cập vào trang này.')
@section('description', 'Bạn không có quyền truy cập vào nội dung này hoặc tài khoản của bạn bị hạn chế.')
@section('image', '/images/site/403.jpg')

@section('button_secondary')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Quay lại trang chủ</a>
@endsection
