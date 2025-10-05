

<?php $__env->startSection('title', 'Phiên làm việc đã hết hạn'); ?>
<?php $__env->startSection('code', '419'); ?>
<?php $__env->startSection('message', 'Oops! Phiên làm việc của bạn đã hết hạn.'); ?>
<?php $__env->startSection('description', 'Vui lòng làm mới trang hoặc quay lại trang trước đó.'); ?>
<?php $__env->startSection('image', '/images/site/419.png'); ?>

<?php $__env->startSection('button_secondary'); ?>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-primary">Quay lại trang chủ</a>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        setTimeout(function() {
            window.location.href = '/admin/dashboard';
        }, 5000);
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('errors.layout.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/errors/419.blade.php ENDPATH**/ ?>