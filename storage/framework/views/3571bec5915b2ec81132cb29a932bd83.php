
<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('attendance.disciplines');

$__html = app('livewire')->mount($__name, $__params, 'lw-2126829211-0', $__slots ?? [], get_defined_vars());

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
        $('#sector').selectpicker().on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedRoles = Array.from($('#role')[0].selectedOptions).map(o => o.value);
            const selectedSectors = Array.from($('#sector')[0].selectedOptions).map(o => o.value);
            Livewire.dispatch('chooseDataSort', [selectedSectors, selectedRoles]);
        });
        $('#role').selectpicker().on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedRoles = Array.from($('#role')[0].selectedOptions).map(o => o.value);
            const selectedSectors = Array.from($('#sector')[0].selectedOptions).map(o => o.value);
            Livewire.dispatch('chooseDataSort', [selectedSectors, selectedRoles]);
        });

        window.addEventListener('showRecordModal', function() {
            $('#record_modal').modal('show');

            $('#record_modal').on('shown.bs.modal', function() {
                $('.selectpicker').selectpicker('refresh');
            });

            $(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('#record_modal form').off('submit').on('submit', function(e) {
                e.preventDefault();

                let recordDisciplineScouter = null;
                let recordDisciplineChildren = null;

                const scouterSelect = document.querySelector('#user_discipline_scouter');
                const childrenSelect = document.querySelector('#user_discipline_children');

                if (scouterSelect) {
                    recordDisciplineScouter = scouterSelect.value;
                }

                if (childrenSelect) {
                    recordDisciplineChildren = childrenSelect.value;
                }

                Livewire.dispatch('submitRecordFormModal', [recordDisciplineScouter,
                    recordDisciplineChildren
                ]);
            });

        });

        window.addEventListener('hideRecordModal', function() {
            $('#record_modal').modal('hide');
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.pages-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/back/attendance/discipline.blade.php ENDPATH**/ ?>