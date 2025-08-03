
<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Danh sách</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Các Quyền Truy Cập
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
[$__name, $__params] = $__split('management.permissions');

$__html = app('livewire')->mount($__name, $__params, 'lw-756242468-0', $__slots ?? [], get_defined_vars());

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

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".switch-btn").forEach((checkbox) => {
                checkbox.addEventListener("change", function() {
                    let permission_id = this.getAttribute("data-id");
                    Livewire.dispatch("togglePermission", [permission_id]);
                });
            });
        });
        document.addEventListener('showPermissionModal', function() {
            $('#permission_modal').modal('show');
        });
        document.addEventListener('hidePermissionModal', function() {
            $('#permission_modal').modal('hide');
        });

        $('table tbody#sortable_permissions').sortable({
            cursor: "move",
            update: function(event, ui) {
                $(this).children().each(function(index) {
                    if ($(this).attr('data-ordering') != (index + 1)) {
                        $(this).attr('data-ordering', (index + 1)).addClass('updated');
                    }
                });
                var positions = [];
                $('.updated').each(function() {
                    positions.push([$(this).attr('data-index'), $(this).attr('data-ordering')]);
                    $(this).removeClass('updated');
                });
                Livewire.dispatch('updatePermissionsOrdering', [positions]);
            }
        });

        window.addEventListener('deletePermission', function(event) {
            var id = event.detail[0].id;
            var name = event.detail[0].name;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có muốn xóa quyền ' + name + ' không?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Xác nhận',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('deletePermissionAction', [id]);
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.pages-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/back/management/permission.blade.php ENDPATH**/ ?>