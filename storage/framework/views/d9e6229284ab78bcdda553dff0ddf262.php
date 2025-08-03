
<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Thông Báo</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Quản lý thông báo
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
[$__name, $__params] = $__split('management.notices');

$__html = app('livewire')->mount($__name, $__params, 'lw-2159176835-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.pages-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/back/management/notice.blade.php ENDPATH**/ ?>