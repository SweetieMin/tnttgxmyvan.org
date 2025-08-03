<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Hộp thư</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Hộp thư góp ý
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
                    <div class="pull-left">
                        <div class="h4 text-blue">
                            <span class="text-danger"><small><i>Góp ý của bạn là động lực để
                                        chúng mình ngày càng tốt hơn</i></small></span>
                        </div>

                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addFeedback" class="btn btn-success btn-sm mr-2"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="addFeedback">Thêm góp ý</span>
                            <span wire:loading wire:target="addFeedback">
                                <span class="spinner-border spinner-border-sm mr-1" role="status"
                                    aria-hidden="true"></span>
                                Đang tải...
                            </span>
                        </a>
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
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Thời gian</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $listFeedbacksPendingAndInProgress; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                                <td class="text-center"><?php echo e($feedback->title); ?></td>
                                                <td class="text-center"><?php echo e($feedback->formatted_created_at); ?></td>
                                                <td class="text-center">
                                                    <?php if($feedback->status === 'pending'): ?>
                                                        <span class="badge badge-warning">Chờ xử lý</span>
                                                    <?php elseif($feedback->status === 'in_progress'): ?>
                                                        <span class="badge badge-success">Đang xử lý</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if($feedback->status === 'pending'): ?>
                                                        <div class="dropdown">
                                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                                href="#" role="button" data-toggle="dropdown">
                                                                <i class="dw dw-more"></i>
                                                            </a>
                                                            <div
                                                                class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                                <a class="dropdown-item text-danger" href="javascript:;"
                                                                    wire:click="deleteFeedback(<?php echo e($feedback->id); ?>)"><i
                                                                        class="dw dw-delete-3 "></i>
                                                                    Xóa</a>
                                                            </div>
                                                        </div>
                                                    <?php elseif($feedback->status === 'in_progress'): ?>
                                                        <?php echo e($feedback->note); ?>

                                                    <?php endif; ?>
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
                                        <?php $__empty_1 = true; $__currentLoopData = $listFeedbacksResolved; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                                        <?php $__empty_1 = true; $__currentLoopData = $listFeedbacksCanceled; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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

    
    <div wire:ignore.self class="modal fade" id="feedback_modal" tabindex="-1" schedule="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form wire:submit.prevent="<?php echo e($isUpdateFeedbackMode ? 'updateFeedback()' : 'createFeedback()'); ?>"
                class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        <?php echo e($isUpdateFeedbackMode ? 'Cập nhật' : 'Thêm góp ý'); ?>

                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <?php if($isUpdateFeedbackMode): ?>
                        <input type="hidden" wire:model="feedback_id">
                    <?php endif; ?>

                    <div class="row">

                        <div class="col-md-12 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="feedback_title"><strong>Tiêu đề<span
                                            class="text-danger">*</span></strong></label>
                                <input type="text"
                                    class="form-control <?php $__errorArgs = ['feedback_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="feedback_title" wire:model="feedback_title">
                                <?php $__errorArgs = ['feedback_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="feedback_content" class="font-weight-bold">
                                    Mô tả <span class="text-danger">*</span>
                                </label>
                                <textarea wire:model="feedback_content" id="feedback_content"
                                    class="form-control <?php $__errorArgs = ['feedback_content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5"
                                    placeholder="Nhập nội dung..."></textarea>
                                <?php $__errorArgs = ['feedback_content'];
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

                        <div class="col-md-12 mt-2">
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="customCheck2"
                                    wire:model='isHideUser'>
                                <label class="custom-control-label" for="customCheck2">Ẩn thông tin cá
                                    nhân</label>
                            </div>
                        </div>

                        <div wire:ignore class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="" class="font-weight-bold">
                                    Hình ảnh (tuỳ chọn) <span class="text-danger">* tối đa 4 ảnh/lần</span>
                                </label>
                                <input type="hidden" id="uploaded_files" wire:model="uploadedFiles">
                                <div class="dropzone dz-clickable" id="feedback_picture">
                                    <div class="dz-default dz-message">
                                        <span>Nhấn hoặc kéo thả hình vào đây</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                        wire:target="<?php echo e($isUpdateFeedbackMode ? 'updateFeedback()' : 'createFeedback()'); ?>">
                        <span wire:loading.remove wire:target="<?php echo e($isUpdateFeedbackMode ? 'updateFeedback()' : 'createFeedback()'); ?>">
                            <?php echo e($isUpdateFeedbackMode ? 'Lưu thay đổi' : 'Thêm góp ý'); ?>

                        </span>
                        <span wire:loading wire:target="<?php echo e($isUpdateFeedbackMode ? 'updateFeedback()' : 'createFeedback()'); ?>">
                            <span class="spinner-border spinner-border-sm mr-1" role="status"
                                aria-hidden="true"></span>
                            Đang tải...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    

</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/support/feedbacks.blade.php ENDPATH**/ ?>