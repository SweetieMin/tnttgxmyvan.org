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
                    <h4>Lịch điểm danh</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tạo lịch điểm danh
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    @livewire('management.schedules')
@endsection
@push('scripts')
    <Script>
        window.addEventListener('showScheduleModal', function() {
            $('#schedule_modal').modal('show');

            $('#schedule_modal').on('shown.bs.modal', function() {
                $('.selectpicker').selectpicker('refresh');
            });

            $('#schedule_modal form').on('submit', function(e) {
                e.preventDefault();

                const scheduleType = document.querySelector('#schedule_type').value;

                Livewire.dispatch('submitScheduleFormModal', [scheduleType]);
            });
        });
        window.addEventListener('hideScheduleModal', function() {
            $('#schedule_modal').modal('hide');
        });
    </Script>
@endpush
