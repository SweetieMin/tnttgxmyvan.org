
<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="card-box pd-20 height-100-p mb-30">
        <div class="row align-items-center">
            <div class="col-md-2 col-sm-4 col-12 d-flex justify-content-center align-items-center mt-2 mb-2">
                <img src="<?php echo e($user->picture); ?>" alt="" class="img-fluid rounded-circle" style="max-width: 150px">
            </div>
            <div class="col-md-10 col-sm-8 col-12 mt-2 mb-2">
                <h4 class="font-20 weight-500 mb-10 text-capitalize">
                    Welcome back
                    <div class="weight-600 font-30 text-blue mt-2"><?php echo e($user->full_name); ?></div>
                </h4>
                <p class="font-18">
                    <?php echo e($bible); ?>

                </p>
            </div>
        </div>
    </div>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('dashboard.children');

$__html = app('livewire')->mount($__name, $__params, 'lw-1833178084-0', $__slots ?? [], get_defined_vars());

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
        document.addEventListener('viewNoticeModal', function() {
            $('#viewNoticeModal').modal('show');
        });

        document.addEventListener('openAllRankingModal', function() {
            $('#openAllRankingModal').modal('show');
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.pages-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/back/dashboard/child-Dashboard.blade.php ENDPATH**/ ?>