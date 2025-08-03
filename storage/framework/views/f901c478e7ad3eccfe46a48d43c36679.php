<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Điểm danh</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php echo e($attendance_name); ?>

                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30 text-center">
                <!-- Nút quét -->
                <?php if($isAttendanceOpen): ?>
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-2 mb-3">
                        <button id="start-scan-btn" class="btn btn-primary">Bắt đầu quét</button>
                        <button id="stop-scan-btn" class="btn btn-danger" style="display:none;">Dừng quét</button>
                    </div>

                    <!-- Vùng quét QR -->
                    <div id="reader" class="mx-auto" style="width: 100%; max-width: 400px; height: auto;"></div>
                <?php else: ?>
                    <div class="text-danger"><?php echo e($msgAttendance); ?></div>
                <?php endif; ?>


            </div>
        </div>
        <?php if($isAttendanceOpen): ?>
            <div class="col-md-12">
                <div class="pd-20 card-box mb-30">
                    <h4 class="h4">Danh sách điểm danh hôm nay <small><span class="text-danger" id="attendance-msg"
                                style="font-size: smaller;"><?php echo e($msgAttendance); ?></span></small></h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Họ và tên</th>
                                    <th class="text-center">Ngành</th>
                                    <th class="text-center">Điểm danh bởi</th>
                                    <th class="text-center">Hạng mục</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $listUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($item->user->SimpleName); ?></td>
                                        <td class="text-center">
                                            <?php
                                                $roleName = $item->user?->roles->first()?->name;
                                                $sectorName = $item->user?->sectors->first()?->name;
                                            ?>

                                            <?php if($sectorName): ?>
                                                <?php echo e($roleName); ?> - <?php echo e($sectorName); ?>

                                            <?php else: ?>
                                                <?php echo e($roleName ?? 'Không rõ'); ?>

                                            <?php endif; ?>
                                        </td>


                                        <td class="text-center">
                                            <?php echo e($item->submittedBy->SimpleName ?? 'Không xác định'); ?>

                                        </td>

                                        <td class="text-center">
                                            <?php echo e($schedule_name); ?>

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
        <?php endif; ?>
    </div>


    <div wire:ignore.self class="modal fade" id="attendance_modal" tabindex="-1" role="dialog"
        aria-labelledby="locationModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form class="modal-content" wire:submit.prevent="attendanceUserConfirm">
                <div class="modal-header">
                    <h4 class="modal-title" id="locationModalLabel">
                        Xác nhận điểm danh
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" wire:model="user_id">

                    <div class="row text-center">
                        <div class="profile-photo">
                            <img src="" alt="" class="avatar-photo" id="profilePicturePreview">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-4">
                            <div class="form-group">
                                <label for="user_holy">Tên Thánh</label>
                                <div id="user_holy" class="border p-2 rounded bg-light">
                                    <?php echo e($user_holy); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-8">
                            <div class="form-group">
                                <label for="user_name">Họ và tên</label>
                                <div id="user_name" class="border p-2 rounded bg-light">
                                    <?php echo e($user_name); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="user_sector">Ngành</label>
                                <div id="user_sector" class="border p-2 rounded bg-light">
                                    <?php echo e($user_sector); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="user_note">Ghi chú</label>
                                <input type="user_note" wire:model="user_note" id="user_note" class="form-control"
                                    autocomplete="off">
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                        wire:target="attendanceUserConfirm">
                        <span wire:loading.remove wire:target="attendanceUserConfirm">Xác nhận</span>
                        <span wire:loading wire:target="attendanceUserConfirm">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Đang xử lý...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/attendance/reward.blade.php ENDPATH**/ ?>