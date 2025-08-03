<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h3 text-danger">
                            Số tiền hiện tại: <?php echo e($currentBalance); ?>

                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addTransaction" class="btn btn-primary btn-sm mr-2"
                            wire:loading.attr="disabled" wire:target="addTransaction">

                            <span wire:loading.remove wire:target="addTransaction">Thêm</span>

                            <span wire:loading wire:target="addTransaction">
                                <span class="spinner-border spinner-border-sm mr-1" role="status"
                                    aria-hidden="true"></span>
                                Đang tải...
                            </span>
                        </a>
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Ngày</th>
                                <th class="text-center">Hạng mục</th>
                                <th class="text-center">Chi tiết</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Thu</th>
                                <th class="text-center">Chi</th>
                                <th class="text-center">Phụ trách</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-light">
                                <td colspan="5" class="text-right">Tổng cộng:</td>
                                <td class="text-center text-success"><?php echo e($totalIncome); ?></td>
                                <td class="text-center text-danger"><?php echo e($totalExpense); ?></td>
                                <td colspan="3"></td>
                            </tr>
                            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo e(($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration); ?>

                                    </td>
                                    <td class="text-center"><?php echo e($transaction->formatted_date); ?></td>
                                    <td class="text-center"><?php echo e($transaction->title); ?></td>
                                    <td class="text-center"><?php echo e($transaction->description); ?></td>
                                    <td class="text-center"><?php echo e($transaction->quantity); ?></td>

                                    <?php if($transaction->type === 'income'): ?>
                                        <td class="text-center text-success"><?php echo e($transaction->formatted_amount); ?></td>
                                    <?php else: ?>
                                        <td class="text-center">-</td>
                                    <?php endif; ?>

                                    <?php if($transaction->type === 'expense'): ?>
                                        <td class="text-center text-danger"><?php echo e($transaction->formatted_amount); ?></td>
                                    <?php else: ?>
                                        <td class="text-center">-</td>
                                    <?php endif; ?>

                                    <td class="text-center"><?php echo e($transaction->in_charge); ?></td>

                                    <?php if($transaction->status === 'paid'): ?>
                                        <td class="text-center">Đã chi</td>
                                    <?php else: ?>
                                        <td class="text-center text-danger">Chưa chi</td>
                                    <?php endif; ?>

                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editTransaction(<?php echo e($transaction->id); ?>)"><i
                                                        class="dw dw-edit2"></i>
                                                    Sửa</a>
                                                <a class="dropdown-item text-danger" href="javascript:;"
                                                    wire:click="deleteTransaction(<?php echo e($transaction->id); ?>)"><i
                                                        class="dw dw-delete-3 "></i>
                                                    Xóa</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                <tr>
                                    <td colspan="9" class="text-center">Không có dữ liệu</td>
                                </tr>
                            <?php endif; ?>
                            <tr class="bg-light">
                                <td colspan="5" class="text-right">Tổng cộng:</td>
                                <td class="text-center text-success"><?php echo e($totalIncome); ?></td>
                                <td class="text-center text-danger"><?php echo e($totalExpense); ?></td>
                                <td colspan="3"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-block mt-1 text-center">
                    <?php echo e($transactions->links('livewire::bootstrap')); ?>

                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="transaction_modal" tabindex="-1" schedule="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        <?php echo e($isUpdateTransactionMode ? 'Cập nhật quỹ' : 'Thêm Thu/Chi'); ?>

                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <?php if($isUpdateTransactionMode): ?>
                        <input type="hidden" wire:model="transaction_id">
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-4 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="schedule_date"><strong>Ngày<span
                                            class="text-danger">*</span></strong></label>
                                <input type="date" class="form-control" id="transaction_date"
                                    wire:model="transaction_date" max="<?php echo e(now()->format('Y-m-d')); ?>">
                                <?php $__errorArgs = ['transaction_date'];
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

                        <div class="col-md-8 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="transaction_item"><strong>Hạng mục<span
                                            class="text-danger">*</span></strong></label>
                                <input type="text" class="form-control" id="transaction_item"
                                    wire:model="transaction_item">
                                <?php $__errorArgs = ['transaction_item'];
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

                        <div class="col-md-12 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="transaction_description"><strong>Chi tiết<span
                                            class="text-danger">*</span></strong></label>
                                <input type="text" class="form-control" id="transaction_description"
                                    wire:model="transaction_description">
                                <?php $__errorArgs = ['transaction_description'];
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

                        <div class="col-md-3 mt-2">
                            <div class="form-group">
                                <label for="transaction_quantity"><strong>Số lượng<span
                                            class="text-danger">*</span></strong></label>
                                <input type="text" class="form-control" id="transaction_quantity"
                                    wire:model="transaction_quantity">
                                <?php $__errorArgs = ['transaction_quantity'];
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

                        <div class="col-md-3 mt-2">
                            <div wire:ignore class="form-group">
                                <label for="transaction_type"><strong>Thu/Chi<span
                                            class="text-danger">*</span></strong></label>
                                <select id="transaction_type" class="form-control selectpicker"
                                    data-style="btn-outline-primary" wire:model="transaction_type">
                                    <option value="income">Thu</option>
                                    <option value="expense">Chi</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="transaction_amount"><strong>Số tiền<span
                                            class="text-danger">*</span></strong></label>
                                <input type="number" class="form-control" id="transaction_amount"
                                    wire:model="transaction_amount" min="0" step="1">
                                <?php $__errorArgs = ['transaction_amount'];
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

                        <div class="col-md-4 mt-2">
                            <div wire:ignore class="form-group">
                                <label for="transaction_status"><strong>Trạng thái<span
                                            class="text-danger">*</span></strong></label>
                                <select id="transaction_status" class="form-control selectpicker"
                                    data-style="btn-outline-primary" wire:model="transaction_status">
                                    <option value="paid">Đã chi</option>
                                    <option value="pending">Chưa chi</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="transaction_in_charge"><strong>Phụ trách<span
                                            class="text-danger">*</span></strong></label>
                                <input type="text" class="form-control" id="transaction_in_charge"
                                    wire:model="transaction_in_charge">
                                <?php $__errorArgs = ['transaction_in_charge'];
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


                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <?php echo e($isUpdateTransactionMode ? 'Lưu thay đổi' : 'Thêm Thu/Chi'); ?>

                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/management/transactions.blade.php ENDPATH**/ ?>