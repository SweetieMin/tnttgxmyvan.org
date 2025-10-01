@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')

    <div class="card-box pd-20 height-100-p mb-30">
        <div class="row align-items-center">
            <div class="col-md-2 col-sm-4 col-12 d-flex justify-content-center align-items-center mt-2 mb-2">
                <img src="{{ $user->picture }}" alt="" class="img-fluid rounded-circle" style="max-width: 150px">
            </div>
            <div class="col-md-10 col-sm-8 col-12 mt-2 mb-2">
                <h4 class="font-20 weight-500 mb-10 text-capitalize">
                    Welcome back
                    <div class="weight-600 font-30 text-blue mt-2">{{ $user->full_name }}</div>
                </h4>
                <p class="font-18">
                    {{ $bible }}
                </p>
            </div>
        </div>
    </div>

    @livewire('dashboard.scouters')

@endsection

@push('scripts')
    <script>
        document.addEventListener('viewNoticeModal', function() {
            $('#viewNoticeModal').modal('show');
        });

        document.addEventListener('openAllRankingModal', function() {
            $('#openAllRankingModal').modal('show');
        });
    </script>
@endpush
