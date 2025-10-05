

<?php $__env->startSection('title', 'Truy cập bị từ chối'); ?>
<?php $__env->startSection('code', '403'); ?>
<?php $__env->startSection('message', 'Oops! Bạn không có quyền truy cập vào trang này.'); ?>
<?php $__env->startSection('description', 'Bạn không có quyền truy cập vào nội dung này hoặc tài khoản của bạn bị hạn chế.'); ?>
<?php $__env->startSection('image', '/images/site/403.jpg'); ?>

<?php $__env->startSection('button_secondary'); ?>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-primary">Quay lại trang chủ</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/errors/403.blade.php ENDPATH**/ ?>