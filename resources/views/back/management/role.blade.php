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
                            Danh sách Chức vụ
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @livewire('management.roles')


@endsection

@push('scripts')
    <script>
        window.addEventListener('showRoleModalForm', function() {
            $('#role_modal').modal('show');

            $('#role_modal').on('shown.bs.modal', function() {
                $('.selectpicker').selectpicker('refresh');
            });

            $('#role_modal').on('shown.bs.modal', function() {
                $('#role_name').focus();
            });

            $('#Permissions').on('change', function() {
                var selectedPermissions = Array.from(this.selectedOptions).map(option => option.value);  
                
                if (selectedPermissions.length > 0) {
                    Livewire.dispatch('updated', selectedPermissions);
                }
            });

            $('#role_modal form').on('submit', function(e) {
                e.preventDefault();
                var selectedPermissions = Array.from(document.getElementById('Permissions').selectedOptions)
                    .map(option => option.value);
                Livewire.dispatch('submitRoleForm', [selectedPermissions]);
            });

        });
        window.addEventListener('hideRoleModalForm', function() {
            $('#role_modal').modal('hide');
        });

        $('table tbody#sortable_role').sortable({
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
                Livewire.dispatch('updateRoleOrdering', [positions]);
            }
        });

        window.addEventListener('deleteRole', function(event) {
            var id = event.detail[0].id;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có chắc không muốn xóa chức vụ này?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Có, xóa chức vụ này',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('deleteRoleAction', [id]);
                }
            });
        });
    </script>
@endpush
