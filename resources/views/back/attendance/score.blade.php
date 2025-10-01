@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')

    @livewire('attendance.scores')

@endsection

@push('scripts')
    <script>
        window.addEventListener('showRecordModal', function() {
            $('#viewDetailModal').modal('show');
        });
    </script>
    
@endpush