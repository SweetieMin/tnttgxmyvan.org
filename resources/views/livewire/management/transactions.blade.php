<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h3 text-danger">
                            Số tiền hiện tại: {{ $currentBalance }}
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
                                <td class="text-center text-success">{{ $totalIncome }}</td>
                                <td class="text-center text-danger">{{ $totalExpense }}</td>
                                <td colspan="3"></td>
                            </tr>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="text-center">
                                        {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="text-center">{{ $transaction->formatted_date }}</td>
                                    <td class="text-center">{{ $transaction->title }}</td>
                                    <td class="text-center">{{ $transaction->description }}</td>
                                    <td class="text-center">{{ $transaction->quantity }}</td>

                                    @if ($transaction->type === 'income')
                                        <td class="text-center text-success">{{ $transaction->formatted_amount }}</td>
                                    @else
                                        <td class="text-center">-</td>
                                    @endif

                                    @if ($transaction->type === 'expense')
                                        <td class="text-center text-danger">{{ $transaction->formatted_amount }}</td>
                                    @else
                                        <td class="text-center">-</td>
                                    @endif

                                    <td class="text-center">{{ $transaction->in_charge }}</td>

                                    @if ($transaction->status === 'paid')
                                        <td class="text-center">Đã chi</td>
                                    @else
                                        <td class="text-center text-danger">Chưa chi</td>
                                    @endif

                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editTransaction({{ $transaction->id }})"><i
                                                        class="dw dw-edit2"></i>
                                                    Sửa</a>
                                                <a class="dropdown-item text-danger" href="javascript:;"
                                                    wire:click="deleteTransaction({{ $transaction->id }})"><i
                                                        class="dw dw-delete-3 "></i>
                                                    Xóa</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @empty

                                <tr>
                                    <td colspan="9" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                            <tr class="bg-light">
                                <td colspan="5" class="text-right">Tổng cộng:</td>
                                <td class="text-center text-success">{{ $totalIncome }}</td>
                                <td class="text-center text-danger">{{ $totalExpense }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-block mt-1 text-center">
                    {{ $transactions->links('livewire::bootstrap') }}
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
                        {{ $isUpdateTransactionMode ? 'Cập nhật quỹ' : 'Thêm Thu/Chi' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateTransactionMode)
                        <input type="hidden" wire:model="transaction_id">
                    @endif

                    <div class="row">
                        <div class="col-md-4 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="schedule_date"><strong>Ngày<span
                                            class="text-danger">*</span></strong></label>
                                <input type="date" class="form-control" id="transaction_date"
                                    wire:model="transaction_date" max="{{ now()->format('Y-m-d') }}">
                                @error('transaction_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="transaction_item"><strong>Hạng mục<span
                                            class="text-danger">*</span></strong></label>
                                <input type="text" class="form-control" id="transaction_item"
                                    wire:model="transaction_item">
                                @error('transaction_item')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="transaction_description"><strong>Chi tiết<span
                                            class="text-danger">*</span></strong></label>
                                <input type="text" class="form-control" id="transaction_description"
                                    wire:model="transaction_description">
                                @error('transaction_description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3 mt-2">
                            <div class="form-group">
                                <label for="transaction_quantity"><strong>Số lượng<span
                                            class="text-danger">*</span></strong></label>
                                <input type="text" class="form-control" id="transaction_quantity"
                                    wire:model="transaction_quantity">
                                @error('transaction_quantity')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
                                @error('transaction_amount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
                                @error('transaction_in_charge')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdateTransactionMode ? 'Lưu thay đổi' : 'Thêm Thu/Chi' }}
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
