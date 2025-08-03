<?php

namespace App\Livewire\Attendance;

use App\Models\Regulation;
use App\Models\User;
use App\Models\Attendance;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Scores extends Component
{
    public $stt;
    public $user_holy_name, $user_full_name;
    public $score;
    public $regulationRecords = [];
    public $rewards = [];
    public $disciplines = [];
    public $listRecord = [];
    public $totalScore = 0;

    public function getData()
    {
        $user = User::with('roles')->findOrFail(Auth::id());
        $this->user_holy_name = $user->holyName;
        $this->user_full_name = $user->SimpleName;
        $userRoles = $user->roles->pluck('name')->toArray();

        $regulations = Regulation::where(function ($query) use ($userRoles) {
            foreach ($userRoles as $role) {
                $query->orWhereJsonContains('applicable_object', $role);
            }
        })->get();

        $rewards = [];
        $disciplines = [];

        $totalRewardPoints = 0;  
        $totalDisciplinePoints = 0; 

        foreach ($regulations as $index => $regulation) {
            $soLan = 0;
            $this->score = 0;
            $attendances = Attendance::where('user_id', $user->id)
                ->where('isConfirm', true)
                ->where('status', 1)
                ->where('regulation_id', $regulation->id)
                ->get();

            $soLan = $attendances->count();
            if ($soLan > 0) {
                $this->score += $regulation->points * $soLan;
                if ($regulation->type === 'plus') {
                    $totalRewardPoints += $this->score;
                } elseif ($regulation->type === 'minus') {
                    $totalDisciplinePoints += $this->score;
                }
            }
            $record = [
                'stt' => $index + 1,
                'noi_dung' => $regulation->description,
                'so_lan' => $soLan > 0 ? $soLan : '',
                'so_diem' => $soLan > 0 ? $this->score : '',
                'ghi_chu' => $soLan > 0 ? $regulation->id : '',
            ];

            if ($regulation->type === 'plus') {
                $rewards[] = $record;
            } elseif ($regulation->type === 'minus') {
                $disciplines[] = $record;
            }

        }

        $this->rewards = $rewards;
        $this->disciplines = $disciplines;

        $this->totalScore = ($totalRewardPoints - $totalDisciplinePoints);
    }

    public function viewDetail($id)
    {
        $user = User::with('roles','sectors')->findOrFail(Auth::id());
        $this->listRecord = Attendance::where('user_id', $user->id)
            ->where('isConfirm', true)
            ->where('status', 1)
            ->where('regulation_id', $id)
            ->get();
        $this->dispatch('showRecordModal');
    }

    public function render()
    {

        $this->getData();
        return view('livewire.attendance.scores', [
            'rewards' => $this->rewards,
            'disciplines' => $this->disciplines,
        ]);
    }
}
