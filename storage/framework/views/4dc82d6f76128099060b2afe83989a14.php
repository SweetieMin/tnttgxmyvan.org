<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('front.registration');

$__html = app('livewire')->mount($__name, $__params, 'lw-2216619208-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.profile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/now/resources/views/front/registration.blade.php ENDPATH**/ ?>