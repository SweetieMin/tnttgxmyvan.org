<?php

namespace App\Livewire\Support;

use App\Models\Feedback;
use App\Models\FeedbackImage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class Assigned extends Component
{
    public $tab = null;
    public $tabname = 'tabMain';
    protected $queryString = ['tab' => ['keep' => true]];
    public $receiver_list = [];
    public $allowedRoles = [
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
    ];

    protected $listeners = [
        'submitSupportFormModal',
    ];

    public $support_id, $support_title, $support_content, $support_picture = [], $support_status, $support_sender, $support_receiver, $support_note, $support_picture_path;

    public function resolveSupport()
    {
        $this->validate([
            'support_note' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $support = Feedback::findOrFail($this->support_id);
            $support->status = 'resolved';
            $support->note = $this->support_note;
            $support->handled_by = Auth::user()->id;
            $support->resolved_at = now();
          
            $images = FeedbackImage::where('feedback_id', $this->support_id)->get();

            foreach ($images as $image) {
                $path = public_path('images/' . $support->type .'s/' . $image->file_name);

                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            
            FeedbackImage::where('feedback_id', $this->support_id)->delete();

            $support->save();
            DB::commit();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Xử lý thành công.']);
            $this->hideSupportModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra. Vui lòng thử lại.']);
        }
    }

    public function submitSupportFormModal($receiverID)
    {
        $this->validate([
            'support_note' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $support = Feedback::findOrFail($this->support_id);
            $support->status = 'in_progress';
            $support->handled_by = $receiverID;
            $support->note = $this->support_note;
            $support->resolved_at = null;
            $support->save();

            DB::commit();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Chuyển giao thành công.']);
            $this->hideSupportModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra. Vui lòng thử lại.']);
        }
    }

    public function rejectSupport()
    {
        $this->validate([
            'support_note' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $support = Feedback::findOrFail($this->support_id);
            $support->status = 'canceled';
            $support->note = $this->support_note;
            $support->handled_by = Auth::user()->id;
            $support->resolved_at = now();
            
            $images = FeedbackImage::where('feedback_id', $this->support_id)->get();

            foreach ($images as $image) {
                $path = public_path('images/' . $support->type .'s/' . $image->file_name);

                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            
            FeedbackImage::where('feedback_id', $this->support_id)->delete();

            $support->save();

            DB::commit();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Từ chối thành công.']);
            $this->hideSupportModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra. Vui lòng thử lại.']);
        }
    }

    public function viewSupport($support_id)
    {
        $support = Feedback::findOrFail($support_id);
        $this->support_id = $support->id;
        $this->support_title = $support->title;
        $this->support_content = $support->content;
        $this->support_picture_path = '/images/' . $support->type . 's/';
        $this->support_picture = $support->images->pluck('file_name')->toArray();
        $this->support_status = $support->status;
        $this->support_sender = $support->user->SimpleName;
        $this->support_receiver = $support->handled_by;
        $this->support_note = $support->note;
        $this->showSupportModal();
    }

    public function showSupportModal()
    {
        $this->resetErrorBag();
        $this->dispatch('showSupportModal');
    }

    public function hideSupportModal()
    {
        $this->resetErrorBag();
        $this->resetModal();
        $this->dispatch('hideSupportModal');
    }

    public function resetModal()
    {
        $this->support_id = null;
        $this->support_title = null;
        $this->support_content = null;
        $this->support_picture = null;
        $this->support_status = null;
        $this->support_sender = null;
        $this->support_receiver = null;
        $this->support_note = null;
    }

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = request('tab') ? request('tab') : $this->tabname;
    }

    public function render()
    {

        $listPendingAndInProgress = Feedback::whereIn('status', ['pending', 'in_progress'])
            ->orderByRaw("FIELD(status, 'pending', 'in_progress')")
            ->orderBy('created_at', 'desc')
            ->get();

        $listResolved = Feedback::where('status', 'resolved')
            ->orderBy('created_at', 'desc')
            ->get();

        $listCanceled = Feedback::where('status', 'canceled')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->receiver_list = User::whereHas('roles', function ($q) {
            $q->whereIn('name', $this->allowedRoles);
        })
            ->with(['roles' => function ($q) {
                $q->whereIn('name', $this->allowedRoles)->orderBy('ordering');
            }])
            ->get()
            ->sortBy(function ($user) {
                return $user->roles->min('ordering');
            })
            ->values();

        return view('livewire.support.assigned', [
            'listPendingAndInProgress' => $listPendingAndInProgress,
            'listResolved' => $listResolved,
            'listCanceled' => $listCanceled,
        ]);
    }
}
