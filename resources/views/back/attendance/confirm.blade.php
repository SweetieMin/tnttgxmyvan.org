@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')

    @livewire('attendance.confirm')

@endsection

@push('scripts')
    <script>
        window.addEventListener('deleteAttendance', function(event) {
            var id = event.detail[0].id;
            var name = event.detail[0].name;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có chắc không muốn xóa ' + name + ' khỏi danh sách điểm danh không?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Có, xóa',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('deleteAttendanceAction', [id]);
                }
            });
        });
    </script>
@endpush
