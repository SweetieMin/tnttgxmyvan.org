<?php

namespace App\Livewire\Dashboard;

use App\Models\Transaction;
use App\Models\User;
use App\Services\ScoreService;
use Livewire\Component;

class Leaders extends Component
{
    public $totalHuynhTruong, $totalDoiTruong, $totalThieuNhi;
    public $bangDiemTatCa = [];

    public $topHuynhTruong, $topThieuNhi;
    public $scoreUpdatedAtHuynhTruong, $scoreUpdatedAtThieuNhi, $scoreUpdatedAtModal;

    public function showAllScouter()
    {
        $rankHuynhTruong = app(ScoreService::class)->scouterRanking();
        $rankThieuNhi = app(ScoreService::class)->childrenRanking(10);

        $this->bangDiemTatCa = $rankHuynhTruong['users'];
        $this->topHuynhTruong = $rankHuynhTruong['users']->take(10);
        $this->topThieuNhi = $rankThieuNhi['users'];
        $this->scoreUpdatedAtHuynhTruong = $rankHuynhTruong['updated_at'];
        $this->scoreUpdatedAtThieuNhi = $rankThieuNhi['updated_at'];
        $this->scoreUpdatedAtModal = $rankHuynhTruong['updated_at'];

        $this->dispatch('openAllRankingModal');
    }

    public function showAllChildren()
    {
        $rankHuynhTruong = app(ScoreService::class)->scouterRanking(10);
        $rankThieuNhi = app(ScoreService::class)->childrenRanking();

        $this->bangDiemTatCa = $rankThieuNhi['users'];
        $this->topHuynhTruong = $rankHuynhTruong['users'];
        $this->topThieuNhi = $rankThieuNhi['users']->take(10);
        $this->scoreUpdatedAtHuynhTruong = $rankHuynhTruong['updated_at'];
        $this->scoreUpdatedAtThieuNhi = $rankThieuNhi['updated_at'];
        $this->scoreUpdatedAtModal = $rankThieuNhi['updated_at'];

        $this->dispatch('openAllRankingModal');
    }

    public function mount()
    {
        $this->totalHuynhTruong = User::whereHas('roles', function ($q) {
            $q->whereIn('name', [
                'Xứ Đoàn Trưởng',
                'Xứ Đoàn Phó',
                'Trưởng Ngành Nghĩa',
                'Phó Ngành Nghĩa',
                'Trưởng Ngành Thiếu',
                'Phó Ngành Thiếu',
                'Trưởng Ngành Ấu',
                'Phó Ngành Ấu',
                'Trưởng Ngành Tiền Ấu',
                'Phó Ngành Tiền Ấu',
                'Huynh Trưởng',
                'Dự Trưởng',
            ]);
        })->count();

        $this->totalDoiTruong = User::where('is_attendance', 1)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Đội Trưởng');
            })->count();

        $this->totalThieuNhi = User::where('is_attendance', 1)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Thiếu Nhi');
            })->count();

        $rankHuynhTruong = app(ScoreService::class)->scouterRanking(10);
        $rankThieuNhi = app(ScoreService::class)->childrenRanking(10);

        $this->topHuynhTruong = $rankHuynhTruong['users'];
        $this->topThieuNhi = $rankThieuNhi['users'];
        $this->scoreUpdatedAtHuynhTruong = $rankHuynhTruong['updated_at'];
        $this->scoreUpdatedAtThieuNhi = $rankThieuNhi['updated_at'];
    }

    public function showTransactionDetails()
    {
        $this->dispatch('showTransactionDetails');
    }

    public function render()
    {
        $transaction = Transaction::whereYear('transaction_date', now()->year)
            ->orderByDesc('transaction_date')
            ->take(100)
            ->get()
            ->values();

        return view('livewire.dashboard.leaders', [
            'topHuynhTruong' => $this->topHuynhTruong,
            'topThieuNhi' => $this->topThieuNhi,
            'currentBalance'  => Transaction::getCurrentBalance(),
            'transactions' => $transaction,
            'totalIncome'     => number_format(Transaction::where('type', 'income')->sum('amount'), 0, ',', '.') . ' ₫',
            'totalExpense'    => number_format(Transaction::where('type', 'expense')->sum('amount'), 0, ',', '.') . ' ₫',
        ]);
    }
}
