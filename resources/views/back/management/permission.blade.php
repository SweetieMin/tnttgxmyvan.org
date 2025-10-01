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
                    <h4>Danh sách</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Các Quyền Truy Cập
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @livewire('management.permissions')

@endsection

@push('scripts')
    <script>

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".switch-btn").forEach((checkbox) => {
                checkbox.addEventListener("change", function() {
                    let permission_id = this.getAttribute("data-id");
                    Livewire.dispatch("togglePermission", [permission_id]);
                });
            });
        });
        document.addEventListener('showPermissionModal', function() {
            $('#permission_modal').modal('show');
        });
        document.addEventListener('hidePermissionModal', function() {
            $('#permission_modal').modal('hide');
        });

        $('table tbody#sortable_permissions').sortable({
            cursor: "move",
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-ordering') != (index + 1)) {
                        $(this).attr('data-ordering', (index + 1)).addClass('updated');
                    }
                });
                var positions = [];
                $('.updated').each(function() {
                    positions.push([$(this).attr('data-index'), $(this).attr('data-ordering')]);
                    $(this).removeClass('updated');
                });
                Livewire.dispatch('updatePermissionsOrdering', [positions]);
            }
        });

        window.addEventListener('deletePermission', function(event) {
            var id = event.detail[0].id;
            var name = event.detail[0].name;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có muốn xóa quyền ' + name + ' không?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Xác nhận',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('deletePermissionAction', [id]);
                }
            });
        });
    </script>
@endpush
