@extends('layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')

    <div class="login-box bg-white box-shadow border-radius-10">
        <div class="login-title">
            <h2 class="text-center text-primary">Đổi mật khẩu</h2>
        </div>
        <h6 class="mb-20">Bạn hãy nhập mật khẩu và nhấn xác nhận nhé</h6>
        <form action="{{route('admin.reset_password',['token'=>$token])}}" method="POST">
            <x-form-alerts></x-form-alerts>
            @csrf
            <div class="input-group custom mb-1">
                <input type="password" class="form-control form-control-lg" placeholder="Mật khẩu mới" name="new_password" />
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                </div>
            </div>

            @error('new_password')
                <span class="text-danger ml-1">{{ $message }}</span>
            @enderror

            <div class="input-group custom mb-1 mt-3">
                <input type="password" class="form-control form-control-lg" placeholder="Xác nhận mật khẩu mới"
                    name="new_password_confirmation" />
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                </div>
            </div>

            @error('new_password_confirmation')
                <span class="text-danger ml-1">{{ $message }}</span>
            @enderror

            <div class="row justify-content-center align-items-center mt-4">
                <div class="col-5">
                    <div class="d-flex justify-content-center">
                        <input class="btn btn-primary btn-lg btn-block" type="submit" value="Thay đổi">
                    </div>
                </div>
            </div>

        </form>
    </div>

@endsection
