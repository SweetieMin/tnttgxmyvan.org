<?php $__env->startSection('title', 'Trang không tồn tại'); ?>
<?php $__env->startSection('code', '404'); ?>
<?php $__env->startSection('message', 'Oops! Trang bạn tìm kiếm không tồn tại.'); ?>
<?php $__env->startSection('description', 'Đường dẫn trang của bạn không đúng hoặc đã bị xóa.'); ?>
<?php $__env->startSection('image', '/images/site/404.jpg'); ?>

<?php $__env->startSection('button_secondary'); ?>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-primary">Quay lại trang chủ</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/errors/404.blade.php ENDPATH**/ ?>