@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')

    @livewire('management.activity-logs')

@endsection

@push('scripts')
    <script>
        $('#action').selectpicker().on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedActions = Array.from($('#action')[0].selectedOptions).map(o => o.value);
            const selectedUsers = Array.from($('#user')[0].selectedOptions).map(o => o.value);
            console.log(selectedActions, selectedUsers);

            Livewire.dispatch('chooseDataSort', [selectedActions, selectedUsers]);
        });

        $('#user').selectpicker().on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedActions = Array.from($('#action')[0].selectedOptions).map(o => o.value);
            const selectedUsers = Array.from($('#user')[0].selectedOptions).map(o => o.value);
            console.log(selectedActions, selectedUsers);

            Livewire.dispatch('chooseDataSort', [selectedActions, selectedUsers]);
        });

        window.addEventListener('showDetailLog', function() {
            $('#activity_modal').modal('show');
        });
    </script>
@endpush
