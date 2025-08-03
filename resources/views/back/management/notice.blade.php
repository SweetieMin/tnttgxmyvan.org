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
                    <h4>Thông Báo</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Quản lý thông báo
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    @livewire('management.notices')
@endsection
@push('scripts')
    <script>
        window.addEventListener('showNoticeModal', function() {
            $('#notice_modal').modal('show');

            $('#notice_modal').on('shown.bs.modal', function() {
                $('.selectpicker').selectpicker('refresh');
            });

            $('#notice_modal form').on('submit', function(e) {
                e.preventDefault();

                var selectedRoles = Array.from(document.getElementById('applicable_roles')
                        .selectedOptions)
                    .map(option => option.value);

                var selectedSectors = Array.from(document.getElementById('applicable_sectors')
                        .selectedOptions)
                    .map(option => option.value);

                Livewire.dispatch('submitNoticeFormModal', [selectedRoles, selectedSectors]);
            });

        });
        window.addEventListener('hideNoticeModal', function() {
            $('#notice_modal').modal('hide');
        });

        window.addEventListener('deleteNotice', function(event) {
            var id = event.detail[0].id;
            var title = event.detail[0].title;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có chắc không muốn xóa thông báo '+ title +' này?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Có, xóa',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('deleteNoticeAction', [id]);
                }
            });
        });
    </script>
@endpush
