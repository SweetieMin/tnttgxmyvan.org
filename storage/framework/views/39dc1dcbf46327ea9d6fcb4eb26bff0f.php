<div>

    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <div class="h4 text-blue ml-3">
                            Chức vụ chính
                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addRole()" class="btn btn-primary btn-sm mr-2">Thêm chức vụ</a>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table id="" class="table table-borderless table-striped table-hover ">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>STT</th>
                                <th>Chức vụ</th>
                                <th class="text-center">Mô tả</th>
                                <th class="text-nowrap text-center">Cấu hình</th>
                                <th class="text-nowrap text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="sortable_role">
                            <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr data-index="<?php echo e($role->id); ?>" data-ordering="<?php echo e($role->ordering); ?>">
                                    <td><?php echo e($role->ordering); ?></td>
                                    <td class="text-nowrap"><?php echo e($role->name); ?></td>
                                    <td><?php echo e($role->permissions->pluck('display_name')->implode(', ')); ?></td>
                                    <td class="text-center">
                                        <?php if($role->type == 'System'): ?>
                                            <span class="badge badge-success">Hệ thống</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Tùy chỉnh</span>
                                        <?php endif; ?>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editRole(<?php echo e($role->id); ?>)"><i
                                                        class="dw dw-edit2"></i>
                                                    Sửa</a>
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="deleteRole(<?php echo e($role->id); ?>)"><i
                                                        class="dw dw-delete-3"></i>
                                                    Xóa</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr class="text-center">
                                    <td colspan="4">
                                        <span class="text-danger ">Không có danh sách chức vụ!</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    

    <div wire:ignore.self class="modal fade" id="role_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        <?php echo e($isUpdateRoleMode ? 'Cập nhật chức vụ' : 'Thêm chức vụ'); ?>

                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <?php if($isUpdateRoleMode): ?>
                        <input type="hidden" wire:model="role_id">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="role_name"><b>Tên chức vụ</b></label>
                        <input type="text" id="role_name" class="form-control" wire:model="role_name"
                            placeholder="Điền tên chức vụ" autocomplete="off" <?php echo e($isSystem); ?>>
                        <?php $__errorArgs = ['role_name'];
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

                    <div class="form-group">
                        <label for="role_description"><b>Mô tả chức vụ</b></label>
                        <textarea wire:model="role_description" id="role_description" class="form-control" placeholder="Mô tả về chức vụ trên"
                            rows="4">
                        </textarea>
                        <?php $__errorArgs = ['role_description'];
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

                    <div wire:ignore class="row">
                        <div class="col mt-2">
                            <label for="Permissions"><b>Permissions</b></label>
                            <select class="selectpicker form-control" id="Permissions" wire:model="selected_permissions"
                                data-size="5" data-style="btn-outline-warning" multiple data-actions-box="true"
                                data-selected-text-format="count">
                                <?php $__empty_1 = true; $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($permission->id); ?>"><?php echo e($permission->display_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <option value="">No Permissions</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <?php if($hasChanges): ?>
                        <button type="submit" class="btn btn-primary">
                            <?php echo e($isUpdateRoleMode ? 'Lưu thay đổi' : 'Thêm chức vụ'); ?>

                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/management/roles.blade.php ENDPATH**/ ?>