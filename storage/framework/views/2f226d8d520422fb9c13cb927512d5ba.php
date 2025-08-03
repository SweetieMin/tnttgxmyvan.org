<div>
    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        <div class="row">
            
            <div class="col-md-6">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h4 text-danger">
                            Bảng thông báo
                        </div>
                    </div>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Ngày</th>
                                <th class="text-center">Tiêu đề</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $notices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo e($notice->created_at->format('d/m/Y')); ?>

                                    </td>
                                    <td class="text-center">
                                        <?php echo e($notice->title); ?>

                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:;" wire:click='viewNotice(<?php echo e($notice->id); ?>)'
                                            title="Xem chi tiết"><span class="ti-eye"></span></a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Chưa có thông báo nào</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            
            <div class="col-md-6">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h4 text-danger">
                            Bảng xếp hạng
                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="showAll" class="btn btn-primary btn-sm mr-2"
                            wire:loading.attr="disabled">
                            Xem tất cả
                            <span wire:loading wire:target="showAll" class="spinner-border spinner-border-sm ml-2"
                                role="status" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
                <div class="table-responsive mt-2">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Hạng</th>
                                <th class="text-center">Họ và tên</th>
                                <th class="text-center">Điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td class="text-center"><?php echo e($user->SimpleName); ?></td>
                                    <td class="text-center"><?php echo e($user->total_score); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    

    <div wire:ignore.self class="modal fade" id="viewNoticeModal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-<?php echo e($notice_type); ?> text-white">
                <div class="modal-body text-center">
                    <h2 class="text-white mb-3">
                        <i class="fa fa-exclamation-triangle"></i> <?php echo e($notice_title); ?>

                    </h2>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <img src="/images/site/Notice.png" alt="<?php echo e($notice_title); ?>" class="img-fluid">
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="card-box w-100 d-flex align-items-center justify-content-center"
                                style="min-height: 200px; background: white; border-radius: 8px;">
                                <h4 class="text-<?php echo e($notice_type); ?> mb-0">
                                    <?php echo e($notice_content); ?>

                                </h4>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        Đã hiểu
                    </button>
                </div>
            </div>
        </div>
    </div>

    

    <div wire:ignore.self class="modal fade" id="openAllRankingModal" tabindex="-1" role="dialog"
        aria-labelledby="rankingModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rankingModalLabel">Bảng xếp hạng tổng điểm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">Hạng</th>
                                    <th class="text-center">Họ và tên</th>
                                    <th class="text-center">Điểm</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $userModal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-center font-weight-bold"><?php echo e($index + 1); ?></td>
                                        <td class="text-center"><?php echo e($userModal->SimpleName); ?></td>
                                        <td class="text-center"><?php echo e($userModal->total_score); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>


</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/dashboard/scouters.blade.php ENDPATH**/ ?>