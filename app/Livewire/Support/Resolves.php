<?php

namespace App\Livewire\Support;

use App\Models\Feedback;
use App\Models\FeedbackImage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class Resolves extends Component
{
    public $tab = null;
    public $tabname = 'tabMain';
    protected $queryString = ['tab' => ['keep' => true]];

    public $support_id, $support_title, $support_content, $support_picture = [], $support_status, $support_sender, $support_receiver, $support_note, $support_picture_path;

    public function viewSupport($support_id)
    {
        $this->resetErrorBag();
        $support = Feedback::findOrFail($support_id);
        $this->support_id = $support->id;
        $this->support_title = $support->title;
        $this->support_content = $support->content;
        $this->support_picture_path = '/images/' . $support->type . 's/';
        $this->support_picture = $support->images->pluck('file_name')->toArray();

        if ($support->isHideUser) {
            $this->support_sender = 'Ẩn danh';
        } else {
            $this->support_sender = $support->user->SimpleName;
        }
        $this->support_status = $support->status;
        $this->support_receiver = $support->handled_by;
        $this->support_note = '';
        $this->showSupportModal();
    }

    public function supportModalSubmit() 
    {
        $this->validate([
            'support_note' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $support = Feedback::findOrFail($this->support_id);
            $support->status = 'resolved';
            $support->note = $this->support_note;
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
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã hoàn tất.']);
            $this->hideSupportModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra. Vui lòng thử lại.']);
        }
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
        $admin = User::findOrFail(Auth::id());

        $baseQuery = Feedback::query();

        if (!$admin->roles->contains('name', 'Admin')) {
            $baseQuery->where('handled_by', Auth::id());
        }

        $listPendingAndInProgress = (clone $baseQuery)
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderByRaw("FIELD(status, 'in_progress', 'pending')")
            ->orderBy('created_at', 'desc')
            ->get();

        $listResolved = (clone $baseQuery)
            ->where('status', 'resolved')
            ->orderBy('created_at', 'desc')
            ->get();

        $listCanceled = (clone $baseQuery)
            ->where('status', 'canceled')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.support.resolves', [
            'listPendingAndInProgress' => $listPendingAndInProgress,
            'listResolved' => $listResolved,
            'listCanceled' => $listCanceled,
        ]);
    }
}
