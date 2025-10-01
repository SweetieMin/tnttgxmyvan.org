@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')
    @livewire('attendance.disciplines')
@endsection
@push('scripts')
    <script>
        $('#sector').selectpicker().on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedRoles = Array.from($('#role')[0].selectedOptions).map(o => o.value);
            const selectedSectors = Array.from($('#sector')[0].selectedOptions).map(o => o.value);
            Livewire.dispatch('chooseDataSort', [selectedSectors, selectedRoles]);
        });
        $('#role').selectpicker().on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedRoles = Array.from($('#role')[0].selectedOptions).map(o => o.value);
            const selectedSectors = Array.from($('#sector')[0].selectedOptions).map(o => o.value);
            Livewire.dispatch('chooseDataSort', [selectedSectors, selectedRoles]);
        });

        window.addEventListener('showRecordModal', function() {
            $('#record_modal').modal('show');

            $('#record_modal').on('shown.bs.modal', function() {
                $('.selectpicker').selectpicker('refresh');
            });

            $(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('#record_modal form').off('submit').on('submit', function(e) {
                e.preventDefault();

                let recordDisciplineScouter = null;
                let recordDisciplineChildren = null;

                const scouterSelect = document.querySelector('#user_discipline_scouter');
                const childrenSelect = document.querySelector('#user_discipline_children');

                if (scouterSelect) {
                    recordDisciplineScouter = scouterSelect.value;
                }

                if (childrenSelect) {
                    recordDisciplineChildren = childrenSelect.value;
                }

                Livewire.dispatch('submitRecordFormModal', [recordDisciplineScouter,
                    recordDisciplineChildren
                ]);
            });

        });

        window.addEventListener('hideRecordModal', function() {
            $('#record_modal').modal('hide');
        });
    </script>
@endpush
