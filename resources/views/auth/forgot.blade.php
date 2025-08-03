@extends('layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')

    <div class="login-box bg-white box-shadow border-radius-10">
        <div class="login-title">
            <h2 class="text-center text-primary">{{ isset($pageTitle) ? $pageTitle : 'Quên mật khẩu' }}</h2>
        </div>
        <h6 class="mb-20">
            Bạn hãy điền Email để chúng mình gửi mã xác minh về tài khoản của bạn nhé.
        </h6>
        @livewire('auth.forgot-password')
    </div>

@endsection
