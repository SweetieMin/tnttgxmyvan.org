<?php

namespace App\Livewire\Dashboard;

use App\Models\Attendance;
use App\Models\Regulation;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Component;

class Leaders extends Component
{
    public $totalHuynhTruong, $totalDoiTruong, $totalThieuNhi;
    public $bangDiemTatCa = [];

    public $topHuynhTruong, $topThieuNhi;

    public function showAllScouter()
    {
        $huynhTruong = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['Thiếu Nhi', 'Admin', 'Cha Tuyên Uy']);
        })->with('roles')->get();

        $thieuNhi = User::whereHas('roles', function ($q) {
            $q->where('name', 'Thiếu Nhi');
        })->with('roles')->get();

        $rankHuynhTruong = $huynhTruong->map(function ($u) {
            $u->setTotalScore($this->calculateScore($u));
            return $u;
        })->sortByDesc('total_score')
            ->values();

        $rankThieuNhi = $thieuNhi->map(function ($u) {
            $u->setTotalScore($this->calculateScore($u));
            return $u;
        })->sortByDesc('total_score')
            ->values();

        $this->bangDiemTatCa = $rankHuynhTruong;
        $this->topHuynhTruong = $rankHuynhTruong->take(10);
        $this->topThieuNhi = $rankThieuNhi->take(10);

        $this->dispatch('openAllRankingModal');
    }

    public function showAllChildren()
    {
        $huynhTruong = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['Thiếu Nhi', 'Admin', 'Cha Tuyên Uy']);
        })->with('roles')->get();

        $thieuNhi = User::whereHas('roles', function ($q) {
            $q->where('name', 'Thiếu Nhi');
        })->with('roles')->get();

        $rankHuynhTruong = $huynhTruong->map(function ($u) {
            $u->setTotalScore($this->calculateScore($u));
            return $u;
        })->sortByDesc('total_score')
            ->values();

        $rankThieuNhi = $thieuNhi->map(function ($u) {
            $u->setTotalScore($this->calculateScore($u));
            return $u;
        })->sortByDesc('total_score')
            ->values();

        $this->bangDiemTatCa = $rankThieuNhi;
        $this->topHuynhTruong = $rankHuynhTruong->take(10);
        $this->topThieuNhi = $rankThieuNhi->take(10);

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

        $this->totalDoiTruong = User::whereHas('roles', function ($q) {
            $q->where('name', 'Đội Trưởng');
        })->count();

        $this->totalThieuNhi = User::whereHas('roles', function ($q) {
            $q->where('name', 'Thiếu Nhi');
        })->count();


        $huynhTruong = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['Thiếu Nhi', 'Admin', 'Cha Tuyên Uy']);
        })->with('roles')->get();

        $thieuNhi = User::whereHas('roles', function ($q) {
            $q->where('name', 'Thiếu Nhi');
        })->with('roles')->get();

        $rankHuynhTruong = $huynhTruong->map(function ($u) {
            $u->setTotalScore($this->calculateScore($u));
            return $u;
        })->sortByDesc('total_score')
            ->values();

        $rankThieuNhi = $thieuNhi->map(function ($u) {
            $u->setTotalScore($this->calculateScore($u));
            return $u;
        })->sortByDesc('total_score')
            ->values();

        $this->topHuynhTruong = $rankHuynhTruong->take(10);
        $this->topThieuNhi = $rankThieuNhi->take(10);
    }

    public function calculateScore(User $user): int
    {
        $userRoles = $user->roles->pluck('name')->toArray();

        $regulations = Regulation::where(function ($query) use ($userRoles) {
            foreach ($userRoles as $role) {
                $query->orWhereJsonContains('applicable_object', $role);
            }
        })->get();

        $reward = 0;
        $discipline = 0;

        foreach ($regulations as $regulation) {
            $count = Attendance::where('user_id', $user->id)
                ->where('isConfirm', true)
                ->where('status', 1)
                ->where('regulation_id', $regulation->id)
                ->count();

            $points = $regulation->points * $count;

            if ($regulation->type === 'plus') {
                $reward += $points;
            } else {
                $discipline += $points;
            }
        }

        return $reward - $discipline;
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
