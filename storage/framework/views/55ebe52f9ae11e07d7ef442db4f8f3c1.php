<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Xác nhận</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Xác nhận điểm danh
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <h6 class="h6 text-danger"><i>#Lưu ý: Đảm bảo quá trình điểm danh kết thúc. Sẽ khóa điểm danh lại khi
                        xác nhận!</i></h6>
                <div class="table-responsive mt-4">
                    <table id="" class="table table-borderless table-striped table-hover ">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Hạng mục</th>
                                <th>Người điểm danh</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $listData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submitter => $attendances): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    [$submitterId, $violationName] = explode('|', $submitter);
                                ?>

                                <tr>
                                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                    <td class="text-center"><?php echo e($violationName); ?></td>
                                    <td>
                                        <strong>
                                            <a href="#"
                                                wire:click="viewData(<?php echo e($submitterId); ?>, <?php echo \Illuminate\Support\Js::from($violationName)->toHtml() ?>)"
                                                class="text-primary text-decoration-underline">
                                                <?php echo e($attendances[0]['submittedBy']['SimpleName'] ?? 'Không rõ'); ?>

                                            </a>
                                        </strong>
                                    </td>
                                    <td class="text-center"><?php echo e(count($attendances)); ?></td>
                                    <td class="text-center">
                                        <a class="btn btn-primary" href="#"
                                            wire:click="confirmAttendance(<?php echo e($submitterId); ?>, <?php echo \Illuminate\Support\Js::from($violationName)->toHtml() ?>)"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmAttendance(<?php echo e($submitterId); ?>, <?php echo \Illuminate\Support\Js::from($violationName)->toHtml() ?>)">

                                            <span wire:loading.remove
                                                wire:target="confirmAttendance(<?php echo e($submitterId); ?>, <?php echo \Illuminate\Support\Js::from($violationName)->toHtml() ?>)">
                                                <i class="bi bi-person-check"></i> Xác nhận
                                            </span>

                                            <span wire:loading
                                                wire:target="confirmAttendance(<?php echo e($submitterId); ?>, <?php echo \Illuminate\Support\Js::from($violationName)->toHtml() ?>)">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span> Đang xử lý...
                                            </span>
                                        </a>
                                    </td>
                                </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if($isShowTableListUserSubmit): ?>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <a href="#" wire:click="closeTableListUserSubmit()"
                                class="btn btn-danger mr-5">Đóng</a>
                        </div>
                        <div class="pull-right">
                            <div class="h4 text-blue mr-3">
                                <?php echo e($nameUserSubmit); ?>

                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table id="" class="table table-borderless table-striped table-hover ">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Họ và tên</th>
                                    <th class="text-center">Ngành</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $listUserOfSubmit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                        <td class="text-center"><?php echo e($attendance->user->SimpleName); ?></td>
                                        <td class="text-center"><?php echo e($attendance->sector_name); ?></td>
                                        <td class="text-center">
                                            <?php if($attendance->status == 1): ?>
                                                <button class="btn btn-danger"
                                                    wire:click="deleteAttendance('<?php echo e($attendance->id); ?>')"
                                                    wire:loading.attr="disabled"
                                                    wire:target="deleteAttendance('<?php echo e($attendance->id); ?>')">
                                                    <span wire:loading.remove
                                                        wire:target="deleteAttendance('<?php echo e($attendance->id); ?>')">Xóa</span>
                                                    <span wire:loading
                                                        wire:target="deleteAttendance('<?php echo e($attendance->id); ?>')">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span> Đang xóa...
                                                    </span>
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-success"
                                                    wire:click="undoAttendance('<?php echo e($attendance->id); ?>')"
                                                    wire:loading.attr="disabled"
                                                    wire:target="undoAttendance('<?php echo e($attendance->id); ?>')">
                                                    <span wire:loading.remove
                                                        wire:target="undoAttendance('<?php echo e($attendance->id); ?>')">Hoàn
                                                        tác</span>
                                                    <span wire:loading
                                                        wire:target="undoAttendance('<?php echo e($attendance->id); ?>')">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span> Đang hoàn tác...
                                                    </span>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/attendance/confirm.blade.php ENDPATH**/ ?>