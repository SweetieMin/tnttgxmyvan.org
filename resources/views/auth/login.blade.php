@extends('layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')
    @livewire('auth.login')
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('password-eye');
            const lockIcon = eyeIcon.querySelector('i');
            passwordInput.addEventListener('input', function() {
                if (passwordInput.value) {
                    eyeIcon.style.pointerEvents = 'auto';
                    lockIcon.classList.remove('dw-padlock1');
                    lockIcon.classList.add('dw-eye');
                } else {
                    eyeIcon.style.pointerEvents = 'none';
                    lockIcon.classList.remove('dw-eye');
                    lockIcon.classList.add('dw-padlock1');
                    if (lockIcon.classList.contains('bi-eye-slash')) {
                        lockIcon.classList.remove('bi-eye-slash');
                        lockIcon.classList.add('dw-padlock1');
                    }
                }
            });
            if (passwordInput.value) {
                eyeIcon.style.pointerEvents = 'auto';
                lockIcon.classList.remove('dw-padlock1');
                lockIcon.classList.add('dw-eye');
            } else {
                eyeIcon.style.pointerEvents = 'none';
                lockIcon.classList.remove('dw-eye');
                lockIcon.classList.add('dw-padlock1');
            }
            eyeIcon.addEventListener('click', function() {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    lockIcon.classList.remove('dw-eye');
                    lockIcon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = "password";
                    lockIcon.classList.remove('bi-eye-slash');
                    lockIcon.classList.add('dw-eye');
                }
            });
        });
    </script>
@endpush
