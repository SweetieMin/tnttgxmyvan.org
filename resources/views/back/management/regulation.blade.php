@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Nội Quy</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Quản lý nội quy
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    @livewire('management.regulations')
@endsection

@push('scripts')
    <script>
        window.addEventListener('closeModal', function() {
            $('#regulation_modal').modal('hide');
        });
    </script>
@endpush
