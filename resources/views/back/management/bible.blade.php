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
                            Các câu Kinh Thánh
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @livewire('management.bibles')

@endsection

@push('scripts')
    <script>
        window.addEventListener('showBibleModalForm', function() {
            $('#bible_modal').modal('show');
        });
        window.addEventListener('hideBibleModalForm', function() {
            $('#bible_modal').modal('hide');
        });

        $('table tbody#sortable_bible').sortable({
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
                Livewire.dispatch('updateBibleOrdering', [positions]);
            }
        });

        window.addEventListener('deleteBible', function(event) {
            var id = event.detail[0].id;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có chắc không muốn xóa câu này?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Có, xóa câu này',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('deleteBibleAction', [id]);
                }
            });
        });
    </script>
@endpush

