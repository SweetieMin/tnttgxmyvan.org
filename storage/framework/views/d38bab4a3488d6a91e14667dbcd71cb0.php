<div>

    
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h4 text-blue">
                            Nội Quy - Áp dụng cho đoàn Thiếu Nhi
                        </div>
                        <!-- spell-check: enable -->
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addRegulation()" data-toggle="modal"
                            data-target="#regulation_modal" class="btn btn-primary btn-sm">
                            Thêm Nội Quy
                        </a>

                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Nội dung</th>
                                <th class="text-center d-none d-md-table-cell">Loại</th>
                                <th class="text-center">Điểm</th>
                                <th class="text-center d-none d-lg-table-cell">Áp dụng</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td colspan="7" class="text-center"><strong>HẠNG MỤC KHEN THƯỞNG</strong></td>
                            </tr>

                            <?php $__empty_1 = true; $__currentLoopData = $plusRegulations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center"><?php echo e($reg->ordering); ?></td>
                                    <td><?php echo e($reg->description); ?></td>
                                    <td class="text-center d-none d-md-table-cell">
                                        <span class="badge bg-<?php echo e($reg->type === 'plus' ? 'success' : 'danger'); ?>">
                                            <?php echo e($reg->type === 'plus' ? 'Cộng điểm' : 'Trừ điểm'); ?>

                                        </span>
                                    </td>
                                    <td class="text-center"><?php echo e($reg->points); ?></td>
                                    <td class="text-start d-none d-lg-table-cell">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <?php $__currentLoopData = $reg->applicable_object; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge"><?php echo e($role); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                wire:click="toggleActive(<?php echo e($reg->id); ?>)"
                                                <?php echo e($reg->is_active ? 'checked' : ''); ?>>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" schedule="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editRegulation(<?php echo e($reg->id); ?>)" data-toggle="modal"
                                                    data-target="#regulation_modal">
                                                    <i class="dw dw-edit2"></i> Chỉnh sửa
                                                </a>

                                                <a class="dropdown-item text-danger" href="javascript:;"
                                                    wire:click="deleteRegulation(<?php echo e($reg->id); ?>)">
                                                    <i class="dw dw-delete-3"></i> Xóa
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                <tr>
                                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                                </tr>

                            <?php endif; ?>

                            <tr>
                                <td colspan="7" class="text-center"><strong>HẠNG MỤC KỶ LUẬT</strong></td>
                            </tr>

                            <?php $__empty_1 = true; $__currentLoopData = $minusRegulations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center"><?php echo e($reg->ordering); ?></td>
                                    <td><?php echo e($reg->description); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?php echo e($reg->type === 'plus' ? 'success' : 'danger'); ?>">
                                            <?php echo e($reg->type === 'plus' ? 'Cộng điểm' : 'Trừ điểm'); ?>

                                        </span>
                                    </td>
                                    <td class="text-center"><?php echo e($reg->points); ?></td>
                                    <td class="text-start">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <?php $__currentLoopData = $reg->applicable_object; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge"><?php echo e($role); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                wire:click="toggleActive(<?php echo e($reg->id); ?>)"
                                                <?php echo e($reg->is_active ? 'checked' : ''); ?>>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" schedule="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editRegulation(<?php echo e($reg->id); ?>)"
                                                    data-toggle="modal" data-target="#regulation_modal">
                                                    <i class="dw dw-edit2"></i> Chỉnh sửa
                                                </a>

                                                <a class="dropdown-item text-danger" href="javascript:;"
                                                    wire:click="deleteRegulation(<?php echo e($reg->id); ?>)">
                                                    <i class="dw dw-delete-3"></i> Xóa
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

    
    <div wire:ignore.self class="modal fade" id="regulation_modal" tabindex="-1" role="dialog"
        aria-labelledby="regulationModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form wire:submit.prevent="<?php echo e($isEditMode ? 'updateRegulation' : 'storeRegulation'); ?>"
                class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="regulationModalLabel">
                        <?php echo e($isEditMode ? 'Cập nhật nội quy' : 'Thêm nội quy'); ?>

                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                </div>

                <div class="modal-body">
                    <?php if($isEditMode): ?>
                        <input type="hidden" wire:model="regulation_id">
                    <?php endif; ?>

                    <div class="form-group mb-3">
                        <label for="description">Nội dung</label>
                        <textarea class="form-control" rows="3" id="description" wire:model.defer="regulationForm.description"></textarea>
                        <?php $__errorArgs = ['regulationForm.description'];
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

                    <div class="form-row row mb-3">
                        <div class="col">
                            <label for="type">Loại</label>
                            <select class="form-control" id="type" wire:model.defer="regulationForm.type">
                                <option value="">-- Chọn --</option>
                                <option value="plus">Cộng điểm</option>
                                <option value="minus">Trừ điểm</option>
                            </select>
                            <?php $__errorArgs = ['regulationForm.type'];
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
                        <div class="col">
                            <label for="points">Điểm</label>
                            <input type="number" id="points" class="form-control"
                                wire:model.defer="regulationForm.points">
                            <?php $__errorArgs = ['regulationForm.points'];
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

                    <div class="form-group mb-3">
                        <label for="applicable_object">Áp dụng cho (phân cách bởi dấu phẩy)</label>
                        <input type="text" id="applicable_object" class="form-control"
                            placeholder="VD: Thiếu Nhi, Huynh Trưởng, Dự Trưởng, Dự Trưởng"
                            wire:model.defer="regulationForm.applicable_object_text">
                        <?php $__errorArgs = ['regulationForm.applicable_object_text'];
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox"
                                    wire:model.defer="regulationForm.is_active">
                                <label class="form-check-label">Kích hoạt nội quy</label>
                            </div>
                        </div>
                    </div>




                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">
                        <?php echo e($isEditMode ? 'Lưu thay đổi' : 'Thêm mới'); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>



</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/management/regulations.blade.php ENDPATH**/ ?>