
<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Lịch điểm danh</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tạo lịch điểm danh
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
[$__name, $__params] = $__split('management.schedules');

$__html = app('livewire')->mount($__name, $__params, 'lw-3167717668-0', $__slots ?? [], get_defined_vars());

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
        window.addEventListener('showScheduleModal', function() {
            $('#schedule_modal').modal('show');

            $('#schedule_modal').on('shown.bs.modal', function() {
                $('.selectpicker').selectpicker('refresh');
            });

            $('#schedule_modal form').on('submit', function(e) {
                e.preventDefault();

                const scheduleType = document.querySelector('#schedule_type').value;

                Livewire.dispatch('submitScheduleFormModal', [scheduleType]);
            });
        });
        window.addEventListener('hideScheduleModal', function() {
            $('#schedule_modal').modal('hide');
        });
    </Script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.pages-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/back/management/schedule.blade.php ENDPATH**/ ?>