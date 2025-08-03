<div>

    <div class="row pb-10">
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark"><?php echo e($totalHuynhTruong); ?></div>
                        <div class="font-14 text-secondary weight-500">
                            Huynh-Dự Trưởng
                        </div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#FF3300">
                            <i class="icon-copy fa fa-user-md"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark"><?php echo e($totalDoiTruong); ?></div>
                        <div class="font-14 text-secondary weight-500">
                            Đội Trưởng
                        </div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#FFFF00">
                            <span class="icon-copy fa fa-user"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark"><?php echo e($totalThieuNhi); ?></div>
                        <div class="font-14 text-secondary weight-500">
                            Thiếu Nhi
                        </div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon">
                            <i class="icon-copy fa fa-child" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <a href="javascript:;" wire:click='showTransactionDetails()'>
                            <div class="weight-700 font-24 text-dark"><?php echo e($currentBalance); ?></div>
                            <div class="d-flex align-items-center">
                                <div class="font-14 text-secondary weight-500">Tiền Quỹ</div>
                                <div wire:loading wire:target="showTransactionDetails" class="ml-2">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status"
                                        style="width: 1rem; height: 1rem;">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="widget-icon">
                        <a href="javascript:;" wire:click='showTransactionDetails()'>
                            <div class="icon" data-color="#09cc06">
                                <i class="icon-copy dw dw-money-2" aria-hidden="true"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        <div class="row">
            
            <div class="col-md-6">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h4 text-danger">
                            Bảng xếp hạng Huynh Trưởng
                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="showAllScouter" class="btn btn-primary btn-sm mr-2"
                            wire:loading.attr="disabled">

                            <span wire:loading.remove wire:target="showAllScouter">Xem tất cả</span>
                            <span wire:loading wire:target="showAllScouter">
                                <span class="spinner-border spinner-border-sm mr-1" role="status"
                                    aria-hidden="true"></span>
                                Đang tải...
                            </span>
                        </a>
                    </div>
                </div>
                <div wire:ignore class="table-responsive mt-2">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Hạng</th>
                                <th class="text-center">Họ và tên</th>
                                <th class="text-center">Điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $topHuynhTruong; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td class="text-center"><?php echo e($user->SimpleName); ?></td>
                                    <td class="text-center"><?php echo e($user->total_score); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Không có danh sách</td>
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
                            Bảng xếp hạng Thiếu Nhi
                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="showAllChildren" class="btn btn-primary btn-sm mr-2"
                            wire:loading.attr="disabled">

                            <span wire:loading.remove wire:target="showAllChildren">Xem tất cả</span>
                            <span wire:loading wire:target="showAllChildren">
                                <span class="spinner-border spinner-border-sm mr-1" role="status"
                                    aria-hidden="true"></span>
                                Đang tải...
                            </span>
                        </a>
                    </div>
                </div>
                <div wire:ignore class="table-responsive mt-2">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Hạng</th>
                                <th class="text-center">Họ và tên</th>
                                <th class="text-center">Điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $topThieuNhi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center"><?php echo e($index + 1); ?></td>
                                    <td class="text-center"><?php echo e($user->SimpleName); ?></td>
                                    <td class="text-center"><?php echo e($user->total_score); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Không có danh sách</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
                                <?php $__empty_1 = true; $__currentLoopData = $bangDiemTatCa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $userModal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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

    <div wire:ignore.self class="modal fade" id="showTransactionDetails" tabindex="-1" role="dialog"
        aria-labelledby="rankingModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rankingModalLabel">Bảng 100 thu chi gần nhất</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">

                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="text-success"> Tổng thu: <?php echo e($totalIncome); ?> </h5>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-danger"> Tổng Chi: <?php echo e($totalExpense); ?> </h5>
                            </div>
                        </div>

                        <table class="table table-bordered table-hover mt-2">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center">Ngày</th>
                                    <th class="text-center d-none d-md-table-cell">Hạng mục</th>
                                    <th class="text-center">Chi tiết</th>
                                    <th class="text-center">Số tiền</th>
                                    <th class="text-center">Phụ trách</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($transaction->formatted_date); ?></td>
                                        <td class="text-center d-none d-md-table-cell"><?php echo e($transaction->title); ?></td>
                                        <td class="text-center"><?php echo e($transaction->description); ?></td>
                                        <td
                                            class="text-center <?php echo e($transaction->type === 'income' ? 'text-success' : 'text-danger'); ?>">
                                            <?php echo e($transaction->formatted_amount); ?></td>
                                        <td class="text-center"><?php echo e($transaction->in_charge); ?></td>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/dashboard/leaders.blade.php ENDPATH**/ ?>