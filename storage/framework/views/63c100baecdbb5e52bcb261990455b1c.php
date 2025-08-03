<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h4 text-blue">
                            Xử lý khiếu nại
                        </div>

                    </div>
                </div>

                <div class="tab">
                    <ul class="nav nav-pills" role="tablist">

                        <li class="nav-item">
                            <a wire:click="selectTab('tabMain')"
                                class="nav-link text-blue <?php echo e($tab === 'tabMain' ? 'active' : ''); ?>" data-toggle="tab"
                                href="#tabMain" role="tab" aria-selected="true">Bảng chính</a>
                        </li>

                        <li class="nav-item">
                            <a wire:click="selectTab('tabResolved')"
                                class="nav-link text-blue <?php echo e($tab === 'tabResolved' ? 'active' : ''); ?>"
                                data-toggle="tab" href="#tabResolved" role="tab" aria-selected="true">Đã xử lý</a>
                        </li>

                        <li class="nav-item">
                            <a wire:click="selectTab('tabCancel')"
                                class="nav-link text-blue <?php echo e($tab === 'tabCancel' ? 'active' : ''); ?>" data-toggle="tab"
                                href="#tabCancel" role="tab" aria-selected="true">Đã hủy</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade <?php echo e($tab === 'tabMain' ? 'active show' : ''); ?>" id="tabMain"
                            role="tabpanel">

                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Người gửi</th>
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Thời gian</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $listPendingAndInProgress; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complaint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                                <td class="text-center">
                                                    <?php echo e($complaint->isHideUser ? 'Ẩn danh' : $complaint->user->SimpleName); ?>

                                                </td>
                                                <td class="text-center"><?php echo e($complaint->title); ?></td>
                                                <td class="text-center"><?php echo e($complaint->formatted_created_at); ?></td>
                                                <td class="text-center">
                                                    <?php if($complaint->status === 'pending'): ?>
                                                        <span class="badge badge-danger">Chờ xử lý</span>
                                                    <?php elseif($complaint->status === 'in_progress'): ?>
                                                        <span class="badge badge-warning">Đã tiếp nhận</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:;"
                                                        wire:click="viewSupport(<?php echo e($complaint->id); ?>)"
                                                        class="btn btn-primary btn-sm mr-2" wire:loading.attr="disabled"
                                                        wire:target="viewSupport(<?php echo e($complaint->id); ?>)">

                                                        <span wire:loading.remove
                                                            wire:target="viewSupport(<?php echo e($complaint->id); ?>)">
                                                            <i class="fa fa-edit"></i> Xem chi tiết
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="viewSupport(<?php echo e($complaint->id); ?>)">
                                                            <span class="spinner-border spinner-border-sm mr-1"
                                                                role="status" aria-hidden="true"></span>
                                                            Đang tải...
                                                        </span>
                                                    </a>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                                            </tr>
                                        <?php endif; ?>

                                    </tbody>
                                </table>

                            </div>

                        </div>
                        <div class="tab-pane fade <?php echo e($tab === 'tabResolved' ? 'active show' : ''); ?>" id="tabResolved"
                            role="tabpanel">

                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Hoàn thành lúc</th>
                                            <th class="text-center">Người xử lý</th>
                                            <th class="text-center">Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $listResolved; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td class="text-center text-primary"><?php echo e($loop->iteration); ?></td>
                                                <td class="text-center text-primary"><?php echo e($feedback->title); ?></td>
                                                <td class="text-center text-primary">
                                                    <?php echo e($feedback->formatted_resolved_at); ?></td>
                                                <td class="text-center text-primary">
                                                    <?php echo e($feedback->handler->SimpleName); ?></td>
                                                <td class="text-center text-primary"><?php echo e($feedback->note); ?></td>
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
                        <div class="tab-pane fade <?php echo e($tab === 'tabCancel' ? 'active show' : ''); ?>" id="tabCancel"
                            role="tabpanel">

                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Đã hủy lúc</th>
                                            <th class="text-center">Người hủy</th>
                                            <th class="text-center">Lý do hủy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $listCanceled; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td class="text-center text-danger"><?php echo e($loop->iteration); ?></td>
                                                <td class="text-center text-danger"><?php echo e($feedback->title); ?></td>
                                                <td class="text-center text-danger">
                                                    <?php echo e($feedback->formatted_resolved_at); ?></td>
                                                <td class="text-center text-danger">
                                                    <?php echo e($feedback->handler->SimpleName); ?></td>
                                                <td class="text-center text-danger"><?php echo e($feedback->note); ?></td>
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

            </div>
        </div>
    </div>

    
    <div wire:ignore.self class="modal fade" id="support_modal" tabindex="-1" schedule="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form wire:submit.prevent='supportModalSubmit()' class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Xem hộp thư
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" wire:model="support_id">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="support_sender"><strong>Người gửi</strong></label>
                                <input type="text" class="form-control" id="support_sender"
                                    wire:model="support_sender" readonly>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="support_title"><strong>Tiêu đề</strong></label>
                                <input type="text" class="form-control" id="support_title"
                                    wire:model="support_title" readonly>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="support_content" class="font-weight-bold">
                                    Mô tả
                                </label>
                                <textarea wire:model="support_content" id="support_content" class="form-control" rows="5"
                                    placeholder="Nhập nội dung..." readonly></textarea>
                            </div>
                        </div>

                        <?php if(!empty($support_picture)): ?>

                            <?php $__currentLoopData = $support_picture; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-3 col-sm-4 col-6 mb-2 d-flex justify-content-center">
                                    <a href="<?php echo e(asset($support_picture_path . $img)); ?>"
                                        data-lightbox="support-images" data-title="Ảnh hỗ trợ" target="_blank">
                                        <img src="<?php echo e(asset($support_picture_path . $img)); ?>" alt="Ảnh hỗ trợ"
                                            style="width: 140px; height: 100px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="col-md-12">
                                <p class="text-muted">Không có hình</p>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-12 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="support_note"><strong>Ghi chú cách xử lý</strong></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['support_note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="support_note" wire:model="support_note" autocomplete="off">
                                <?php $__errorArgs = ['support_note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit"
                        class="btn btn-<?php echo e($support_status === 'in_progress' ? 'primary' : 'danger'); ?>"
                        <?php echo e($support_status === 'in_progress' ? '' : 'disabled'); ?> wire:loading.attr="disabled"
                        wire:target="supportModalSubmit()">
                        <span wire:loading.remove wire:target="supportModalSubmit()">
                            <?php echo e($support_status === 'in_progress' ? 'Xử lý xong' : 'Chưa phân công'); ?>

                        </span>
                        <span wire:loading wire:target="supportModalSubmit()">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Đang xử lý...
                        </span>
                    </button>

                </div>

            </form>
        </div>
    </div>
    
</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/support/resolves.blade.php ENDPATH**/ ?>