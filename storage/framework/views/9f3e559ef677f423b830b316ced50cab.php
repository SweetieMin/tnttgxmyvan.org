<div>

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Nhật Ký</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Nhật ký hoạt động trên website
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mt-2">

                </div>
                <div class="row">

                    <div wire:ignore class="col-md-4 form-group">
                        <select class="selectpicker form-control" id="action" data-size="5"
                            data-style="btn-outline-secondary" data-live-search="true" data-actions-box="true"
                            data-live-search-placeholder="Tìm kiếm hạng mục..."
                            data-none-results-text="Không tìm hạng mục" data-none-selected-text="Chọn hạng mục"
                            data-select-all-text="Chọn tất cả" data-deselect-all-text="Bỏ tất cả" multiple>
                            <?php $__empty_1 = true; $__currentLoopData = $activityLogs_action; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($item->action); ?>"><?php echo e($item->action); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option value="">Không có hạng mục</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div wire:ignore class="col-md-4 form-group">
                        <select class="selectpicker form-control" id="user" data-size="5"
                            data-style="btn-outline-secondary" data-live-search="true" data-actions-box="true"
                            data-live-search-placeholder="Tìm kiếm người thực hiện..."
                            data-none-results-text="Không tìm người thực hiện"
                            data-none-selected-text="Chọn người thực hiện" data-select-all-text="Chọn tất cả"
                            data-deselect-all-text="Bỏ tất cả" multiple>
                            <?php $__empty_1 = true; $__currentLoopData = $activityLogs_user; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($item->user->id); ?>"><?php echo e($item->user->SimpleName); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option value="">Không có người thực hiện</option>
                            <?php endif; ?>
                        </select>
                    </div>

                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Thời gian</th>
                                <th class="text-center">Hạng mục</th>
                                <th class="text-center">Người thực hiện</th>
                                <th class="text-center">Mô tả hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $activeLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo e(($activeLogs->currentPage() - 1) * $activeLogs->perPage() + $loop->iteration); ?>

                                    </td>
                                    <td class="text-center"><?php echo e($item->created_at->diffForHumans()); ?></td>
                                    <td class="text-center"><?php echo e($item->action); ?></td>
                                    <td class="text-center"><?php echo e($item->user->SimpleName); ?></td>
                                    <td class="text-center">
                                        <a href="#" wire:click='viewDetailLog(<?php echo e($item->id); ?>)'
                                            class="text-primary"><i><u>Xem chi tiết</u></i></a>
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

                <div class="d-block mt-1 text-center">
                    <?php echo e($activeLogs->links('livewire::bootstrap')); ?>

                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="activity_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Xem chi tiết nhật ký hoạt động: <span class="text-primary"><?php echo e($activeLog_action); ?></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="activeLog_description" class="font-weight-bold">
                                    Mô tả
                                </label>
                                <textarea wire:model="activeLog_description" id="activeLog_description" class="form-control" rows="5" readonly></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/management/activity-logs.blade.php ENDPATH**/ ?>