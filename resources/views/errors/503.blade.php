@extends('errors.layout.errors')

@section('title', 'Bảo trì hệ thống')
@section('code', '503')
@section('message', 'Chúng mình đang tiến hành bảo trì.')
@section('description', 'Bạn hãy quay lại sau nhé. Hoặc liên hệ với chúng mình qua Facebook.')
@section('image', '/images/site/MAINTENANCE.jpg')

@section('button_secondary')
    <a href="https://www.facebook.com/profile.php?id=100069752143507" class="btn btn-primary" target="_blank">Liên hệ chúng mình</a>
@endsection
