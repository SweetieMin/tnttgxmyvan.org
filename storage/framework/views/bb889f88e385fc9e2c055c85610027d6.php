
<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Tiền quỹ</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Danh sách thu chi của Đoàn
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('management.transactions');

$__html = app('livewire')->mount($__name, $__params, 'lw-300067391-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.pages-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/back/management/transaction.blade.php ENDPATH**/ ?>