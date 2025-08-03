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
                    <h4>Tiền quỹ</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Danh sách thu chi của Đoàn
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @livewire('management.transactions')

@endsection

@push('scripts')
    <Script>
        window.addEventListener('showTransactionModal', function() {
            $('#transaction_modal').modal('show');

            $('#transaction_modal').on('shown.bs.modal', function() {
                $('.selectpicker').selectpicker('refresh');
            });

            $('#transaction_modal form').on('submit', function(e) {
                e.preventDefault();

                const transactionType = document.querySelector('#transaction_type').value;
                const transactionStatus = document.querySelector('#transaction_status').value;

                Livewire.dispatch('submitTransactionFormModal', [transactionType, transactionStatus]);
            });

        });
        window.addEventListener('hideTransactionModal', function() {
            $('#transaction_modal').modal('hide');
        });

        window.addEventListener('deleteTransaction', function(event) {
            var id = event.detail[0].id;
            var description = event.detail[0].description;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có chắc không muốn xóa ' + description + ' này?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Có, xóa',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('deleteTransactionAction', [id]);
                }
            });
        });
    </Script>
@endpush
