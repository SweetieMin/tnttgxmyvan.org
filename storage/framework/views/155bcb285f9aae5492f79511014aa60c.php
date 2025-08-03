
<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('support.assigned');

$__html = app('livewire')->mount($__name, $__params, 'lw-3237531952-0', $__slots ?? [], get_defined_vars());

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
        window.addEventListener('showSupportModal', function() {
            $('#support_modal').modal('show');
            $('#support_modal').on('shown.bs.modal', function() {
                $('.selectpicker').selectpicker('refresh');
            });

            $('#support_modal form').on('submit', function(e) {
                e.preventDefault();
                const receiverID = document.querySelector('#receiver').value;
                Livewire.dispatch('submitSupportFormModal', [receiverID]);

            });

        });
        window.addEventListener('hideSupportModal', function() {
            $('#support_modal').modal('hide');
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.pages-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/back/support/assigned.blade.php ENDPATH**/ ?>