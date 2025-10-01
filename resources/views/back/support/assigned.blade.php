@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')
    @livewire('support.assigned')
@endsection

@push('scripts')
    <script>
        window.addEventListener('showSupportModal', function() {
            $('#support_modal').modal('show');
            $('#support_modal').on('shown.bs.modal', function() {
                $('.selectpicker').selectpicker('refresh');
            });

            $('#support_modal form').on('submit', function(e) {
                e.preventDefault();
                const receiverID = document.querySelector('#receiver').value;
                Livewire.dispatch('submitSupportFormModal', [receiverID]);

            });

        });
        window.addEventListener('hideSupportModal', function() {
            $('#support_modal').modal('hide');
        });
    </script>
@endpush
