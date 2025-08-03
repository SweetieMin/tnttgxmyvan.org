<?php

namespace App\Livewire\Management;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Transactions extends Component
{
    use WithPagination;
    public $totalTransaction;
    public $transaction_id, $transaction_date, $transaction_item, $transaction_description, $transaction_quantity, $transaction_type, $transaction_amount, $transaction_in_charge, $transaction_status;
    public $isUpdateTransactionMode = false;

    protected $listeners = [
        'submitTransactionFormModal',
        'deleteTransactionAction'
    ];

    public function submitTransactionFormModal($transactionType, $transactionStatus)
    {
        $this->validate([
            'transaction_date' => 'required|date|before_or_equal:today',
            'transaction_item' => 'required|min:1|string',
            'transaction_description' => 'required|min:1|string',
            'transaction_quantity' => 'required|integer|min:1',
            'transaction_amount' => 'required|numeric|min:0',
            'transaction_in_charge' => 'required|string',
        ], [
            'transaction_date.before_or_equal' => 'Ngày giao dịch không được ở tương lai.',
            'transaction_item.required' => 'Vui lòng nhập tên hạng mục.',
            'transaction_item.min' => 'Tên hạng mục phải có ít nhất 1 ký tự.',
            'transaction_description.required' => 'Vui lòng nhập mô tả.',
            'transaction_description.min' => 'Mô tả phải có ít nhất 1 ký tự.',
            'transaction_quantity.required' => 'Vui lòng nhập số lượng.',
            'transaction_quantity.integer' => 'Số lượng phải là số nguyên.',
            'transaction_quantity.min' => 'Số lượng phải lớn hơn 0.',
            'transaction_amount.required' => 'Vui lòng nhập số tiền.',
            'transaction_amount.numeric' => 'Số tiền phải là số.',
            'transaction_amount.min' => 'Số tiền không được nhỏ hơn 0.',
            'transaction_in_charge.required' => 'Vui lòng nhập tên người phụ trách.',
        ]);

        $this->transaction_type = $transactionType;
        $this->transaction_status = $transactionStatus;

        if ($this->isUpdateTransactionMode) {
            $this->updateTransaction();
        } else {
            $this->createTransaction();
        }
    }

    public function createTransaction()
    {
        DB::beginTransaction();
        try {

            $transaction = new Transaction();
            $transaction->transaction_date = $this->transaction_date;
            $transaction->title = $this->transaction_item;
            $transaction->description = $this->transaction_description;
            $transaction->quantity = $this->transaction_quantity;
            $transaction->type = $this->transaction_type;
            $transaction->amount = $this->transaction_amount;
            $transaction->in_charge = $this->transaction_in_charge;
            $transaction->status = $this->transaction_status;

            $transaction->save();

            DB::commit();
            $this->hideTransaction();
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Thêm ' . $transaction->description . ' thành công.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ]);
        }
    }

    public function updateTransaction()
    {
        $transaction = Transaction::findOrFail($this->transaction_id);
        DB::beginTransaction();
        try {

            $transaction->transaction_date = $this->transaction_date;
            $transaction->title = $this->transaction_item;
            $transaction->description = $this->transaction_description;
            $transaction->quantity = $this->transaction_quantity;
            $transaction->type = $this->transaction_type;
            $transaction->amount = $this->transaction_amount;
            $transaction->in_charge = $this->transaction_in_charge;
            $transaction->status = $this->transaction_status;

            $transaction->save();

            DB::commit();
            $this->hideTransaction();
            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Cập nhật ' . $transaction->description . ' thành công.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ]);
        }
    }

    public function deleteTransaction($transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);
        $this->dispatch('deleteTransaction', ['id' => $transaction->id, 'description' => $transaction->description]);
    }

    public function deleteTransactionAction($id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->delete();

            DB::commit();

            $this->dispatch('showToastr', [
                'type' => 'success',
                'message' => 'Xóa thành công.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', [
                'type' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ]);
        }
    }

    public function addTransaction()
    {
        $this->resetModal();
        $this->isUpdateTransactionMode = false;
        $this->showTransaction();
    }

    public function editTransaction($transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);
        $this->transaction_id          = $transaction->id;
        $this->transaction_date        = $transaction->transaction_date->format('Y-m-d');
        $this->transaction_item        = $transaction->title;
        $this->transaction_description = $transaction->description;
        $this->transaction_quantity    = $transaction->quantity;
        $this->transaction_type        = $transaction->type;
        $this->transaction_amount      = (int) $transaction->amount;
        $this->transaction_in_charge   = $transaction->in_charge;
        $this->transaction_status      = $transaction->status;

        $this->isUpdateTransactionMode = true;
        $this->showTransaction();
    }

    public function resetModal()
    {
        $this->isUpdateTransactionMode = false;
        $this->transaction_id = null;
        $this->transaction_date = null;
        $this->transaction_quantity = null;
        $this->transaction_item = null;
        $this->transaction_description = null;
        $this->transaction_amount = null;
        $this->transaction_in_charge = null;
    }

    public function showTransaction()
    {
        $this->resetErrorBag();
        $this->dispatch('showTransactionModal');
    }

    public function hideTransaction()
    {
        $this->dispatch('hideTransactionModal');
        $this->isUpdateTransactionMode = false;
        $this->resetModal();
    }

    public function getListDataTransaction()
    {
        return Transaction::whereYear('transaction_date', now()->year)
            ->orderBy('transaction_date', 'desc')
            ->paginate(20);
    }

    public function render()
    {
        return view('livewire.management.transactions', [
            'transactions'    => $this->getListDataTransaction(),
            'currentBalance'  => Transaction::getCurrentBalance(),
            'totalIncome'     => number_format(Transaction::where('type', 'income')->sum('amount'), 0, ',', '.') . ' ₫',
            'totalExpense'    => number_format(Transaction::where('type', 'expense')->sum('amount'), 0, ',', '.') . ' ₫',
        ]);
    }
}
