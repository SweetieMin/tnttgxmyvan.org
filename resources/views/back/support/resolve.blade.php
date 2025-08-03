@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')
    @livewire('support.resolves')
@endsection
@push('scripts')
    <script>
        window.addEventListener('showSupportModal', function() {
            $('#support_modal').modal('show');
       });
        window.addEventListener('hideSupportModal', function() {
            $('#support_modal').modal('hide');
        });
    </script>
@endpush
