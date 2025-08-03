<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <div class="h4 text-blue ml-3">
                            Quyền - Quản lý quyền hệ thống
                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addPermission()" class="btn btn-primary btn-sm mr-2">
                            Thêm quyền
                        </a>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-borderless table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>STT</th>
                                <th>Tên hiển thị</th>
                                <th>Tên route</th>
                                <th class="text-center">Công khai</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="sortable_permissions">
                            <?php $__empty_1 = true; $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr data-index="<?php echo e($permission->id); ?>" data-ordering="<?php echo e($permission->ordering); ?>">
                                    <td><strong><?php echo e($permission->ordering); ?></strong></td>
                                    <td><?php echo e($permission->display_name); ?></td>
                                    <td><?php echo e($permission->name); ?></td>
                                    <td wire:ignore class="text-center">
                                        <input type="checkbox" data-id="<?php echo e($permission->id); ?>"
                                            <?php echo e($permission->isShow ? 'checked' : ''); ?> class="switch-btn"
                                            data-color="#0099ff" />
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editPermission(<?php echo e($permission->id); ?>)">
                                                    <i class="dw dw-edit2"></i> Chỉnh sửa
                                                </a>
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="deletePermission(<?php echo e($permission->id); ?>)">
                                                    <i class="dw dw-delete-3"></i> Xóa
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center">Không tìm thấy quyền nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

        <!-- Modal -->
        <div wire:ignore.self class="modal fade" id="permission_modal" tabindex="-1" role="dialog"
        aria-labelledby="locationModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content" wire:submit.prevent="<?php echo e($isUpdate ? 'updatePermission' : 'createPermission'); ?>">
                <div class="modal-header">
                    <h4 class="modal-title" id="locationModalLabel">
                        <?php echo e($isUpdate ? 'Cập nhật quyền' : 'Thêm quyền'); ?>

                    </h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <?php if($isUpdate): ?>
                        <input type="hidden" wire:model="permission_id">
                    <?php endif; ?>
                    <div class="row">
                        <?php if($isUpdate): ?>
                            <div class="col-md-12 mb-3">
                                <label for="permission_name" class="form-label">Tên quyền</label>
                                <input type="text" id="permission_name" class="form-control"
                                    wire:model="permission_name" autocomplete="off" disabled />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="display_name" class="form-label">Tên hiển thị</label>
                                <input type="text" id="display_name" class="form-control"
                                    wire:model="permission_display_name" placeholder="Nhập tên hiển thị"
                                    autocomplete="off" />
                            </div>
                            <?php $__errorArgs = ['permission_display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger ml-1"> <?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php else: ?>
                            <div class="col-md-12 mb-3">
                                <?php $__empty_1 = true; $__currentLoopData = $routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $route): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="<?php echo e($route); ?>"
                                            id="<?php echo e($route); ?>" wire:model="selectedRoutes">
                                        <label class="form-check-label" for="<?php echo e($route); ?>">
                                            <?php echo e($route); ?>

                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-muted">Không có route nào khả dụng.</p>
                                <?php endif; ?>
                            </div>
                            <?php $__errorArgs = ['selectedRoutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger ml-1"> <?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <?php echo e($isUpdate ? 'Lưu thay đổi' : 'Tạo mới'); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/management/permissions.blade.php ENDPATH**/ ?>