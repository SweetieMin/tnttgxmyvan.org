<?php $__env->startSection('title', 'Bảo trì hệ thống'); ?>
<?php $__env->startSection('code', '503'); ?>
<?php $__env->startSection('message', 'Chúng mình đang tiến hành bảo trì.'); ?>
<?php $__env->startSection('description', 'Bạn hãy quay lại sau nhé. Hoặc liên hệ với chúng mình qua Facebook.'); ?>
<?php $__env->startSection('image', '/images/site/MAINTENANCE.jpg'); ?>

<?php $__env->startSection('button_secondary'); ?>
    <a href="https://www.facebook.com/profile.php?id=100069752143507" class="btn btn-primary" target="_blank">Liên hệ chúng mình</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout.errors', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/errors/503.blade.php ENDPATH**/ ?>