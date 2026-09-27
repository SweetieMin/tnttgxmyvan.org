@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')
    @livewire('personnel.registrations')
@endsection
@push('scripts')
    <script>
        window.addEventListener('showRegistrationModal', function() {
            $('#registration_modal').modal('show');
        });
        window.addEventListener('hideRegistrationModal', function() {
            $('#registration_modal').modal('hide');
        });
    </script>
@endpush
