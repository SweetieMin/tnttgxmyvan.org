<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h4 text-blue">
                            Quản Lý Thông Báo
                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addNotice()" class="btn btn-primary btn-sm mr-2">
                            Thêm Thông Báo
                        </a>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Tiêu đề</th>
                                <th class="text-center">Nhóm đối tượng</th>
                                <th class="text-center">Ngành</th>
                                <th class="text-center">Công khai</th>
                                <th class="text-center">Popup</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $listNotices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr wire:key="notice-<?php echo e($notice->id); ?>">
                                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                    <td class="text-center"><?php echo e($notice->title); ?></td>
                                    <td class="text-start">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <?php $__currentLoopData = $notice->applicable_roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge"><?php echo e($role); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <?php $__currentLoopData = $notice->applicable_sectors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sector): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge"><?php echo e($sector); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                id="notice_active_<?php echo e($notice->id); ?>"
                                                wire:click="toggleNoticeActive(<?php echo e($notice->id); ?>)"
                                                <?php if($notice->is_active): echo 'checked'; endif; ?>>
                                            <label class="custom-control-label"
                                                for="notice_active_<?php echo e($notice->id); ?>"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                id="notice_popup_<?php echo e($notice->id); ?>"
                                                wire:click="toggleNoticePopup(<?php echo e($notice->id); ?>)"
                                                <?php if($notice->is_popup): echo 'checked'; endif; ?>>
                                            <label class="custom-control-label"
                                                for="notice_popup_<?php echo e($notice->id); ?>"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editNotice(<?php echo e($notice->id); ?>)">
                                                    <i class="dw dw-edit2"></i> Chỉnh sửa
                                                </a>
                                                <a class="dropdown-item text-danger" href="javascript:;"
                                                    wire:click="deleteNotice(<?php echo e($notice->id); ?>)">
                                                    <i class="dw dw-delete-3"></i> Xóa
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Không có thông báo nào</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="notice_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        <?php echo e($isUpdateNoticeModal ? 'Cập nhật thông báo' : 'Thêm thông báo'); ?>

                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <?php if($isUpdateNoticeModal): ?>
                        <input type="hidden" wire:model="notice_id">
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="notice_type"><b>Loại thông báo <span class="text-danger">*</span></b></label>
                            <select id="notice_type" class="custom-select" wire:model="notice_type">
                                <option value="" selected>Chọn loại...</option>
                                <option class="bg-info text-white" value="info">Thông tin</option>
                                <option class="bg-warning text-white" value="warning">Cảnh báo</option>
                                <option class="bg-danger text-white" value="danger">Khẩn cấp</option>
                            </select>
                            <?php $__errorArgs = ['notice_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="notice_title"><b>Tiêu đề <span class="text-danger">*</span></b></label>
                                <input type="text" id="notice_title" class="form-control" wire:model="notice_title"
                                    placeholder="Tiêu đề thông báo" autocomplete="on">
                                <?php $__errorArgs = ['notice_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger ml-1">
                                        <?php echo e($message); ?>

                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notice_content"><b>Nội dung thông báo <span
                                            class="text-danger">*</span></b></label>
                                <textarea wire:model="notice_content" id="notice_content" class="form-control" autocomplete="on"
                                    placeholder="Nôi dung thông báo" rows="4">
                            </textarea>
                                <?php $__errorArgs = ['notice_content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger ml-1">
                                        <?php echo e($message); ?>

                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div wire:ignore class="form-group">
                                <label for="applicable_roles"><b>Nhóm đối tượng</b></label>
                                <select class="selectpicker form-control" id="applicable_roles"
                                    wire:model="applicable_roles" data-size="5" data-style="btn-outline-secondary"
                                    multiple data-actions-box="true" data-selected-text-format="count"
                                    data-live-search="true" data-live-search-placeholder="Tìm kiếm nhóm đối tượng..."
                                    data-none-results-text="Không tìm thấy nhóm đối tượng"
                                    data-none-selected-text="Chọn nhóm đối tượng" data-select-all-text="Chọn tất cả"
                                    data-deselect-all-text="Bỏ tất cả">
                                    <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <option value="">Không có nhóm đối tượng</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div wire:ignore class="form-group">
                                <label for="applicable_sectors"><b>Chọn ngành</b></label>
                                <select class="selectpicker form-control" id="applicable_sectors"
                                    wire:model="applicable_sectors" data-size="5" data-style="btn-outline-secondary"
                                    multiple data-actions-box="true" data-selected-text-format="count"
                                    data-live-search="true" data-live-search-placeholder="Tìm kiếm ngành..."
                                    data-none-results-text="Không tìm thấy ngành" data-none-selected-text="Chọn ngành"
                                    data-select-all-text="Chọn tất cả" data-deselect-all-text="Bỏ tất cả">
                                    <?php $__empty_1 = true; $__currentLoopData = $sectors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sector): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option value="<?php echo e($sector->id); ?>"><?php echo e($sector->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <option value="">Không có ngành</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="notice_start_at"><strong>Bắt đầu <span
                                            class="text-danger">*</span></strong></label>
                                <input type="date" class="form-control" id="notice_start_at"
                                    wire:model="notice_start_at">
                                <?php $__errorArgs = ['notice_start_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="notice_end_at"><strong>Kết thúc<span
                                            class="text-danger">*</span></strong></label>
                                <input type="date" class="form-control" id="notice_end_at"
                                    wire:model="notice_end_at">
                                <?php $__errorArgs = ['notice_end_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="notice_is_active"
                                        wire:model="notice_is_active">
                                    <label class="custom-control-label" for="notice_is_active"><b>Công
                                            khai</b></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="notice_is_popup"
                                        wire:model="notice_is_popup">
                                    <label class="custom-control-label" for="notice_is_popup"><b>Thông báo
                                            đẩy</b></label>
                                </div>
                            </div>
                        </div>

                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <?php echo e($isUpdateNoticeModal ? 'Lưu thay đổi' : 'Thêm thông báo'); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/management/notices.blade.php ENDPATH**/ ?>