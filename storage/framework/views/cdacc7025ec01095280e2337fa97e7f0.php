
<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('management.activity-logs');

$__html = app('livewire')->mount($__name, $__params, 'lw-3646960000-0', $__slots ?? [], get_defined_vars());

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
        $('#action').selectpicker().on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedActions = Array.from($('#action')[0].selectedOptions).map(o => o.value);
            const selectedUsers = Array.from($('#user')[0].selectedOptions).map(o => o.value);
            console.log(selectedActions, selectedUsers);

            Livewire.dispatch('chooseDataSort', [selectedActions, selectedUsers]);
        });

        $('#user').selectpicker().on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedActions = Array.from($('#action')[0].selectedOptions).map(o => o.value);
            const selectedUsers = Array.from($('#user')[0].selectedOptions).map(o => o.value);
            console.log(selectedActions, selectedUsers);

            Livewire.dispatch('chooseDataSort', [selectedActions, selectedUsers]);
        });

        window.addEventListener('showDetailLog', function() {
            $('#activity_modal').modal('show');
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.pages-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/back/management/activity-logs.blade.php ENDPATH**/ ?>