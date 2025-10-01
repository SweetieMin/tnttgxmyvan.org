<?php

namespace App\Livewire\Support;

use App\Models\Feedback;
use App\Models\FeedbackImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class Complaints extends Component
{
    public $tab = null;
    public $tabname = 'tabMain';
    public $isUpdateComplaintMode = false;
    public $complaint_id, $complaint_title, $complaint_content, $isHideUser = false;
    protected $queryString = ['tab' => ['keep' => true]];

    public $uploadedFiles = [];

    protected $listeners = [
        'uploadedFilesComplaint',
        'deleteComplaintAction',
    ];

    public function createComplaint()
    {
        $this->validate([
            'complaint_title' => 'required|string|max:100',
            'complaint_content' => 'required|string|max:1000',
        ], [
            'complaint_title.required' => 'Vui lòng nhập tiêu đề.',
            'complaint_title.string' => 'Tiêu đề không hợp lệ.',
            'complaint_title.max' => 'Tiêu đề không được vượt quá 100 ký tự.',
            'complaint_content.required' => 'Vui lòng nhập nội dung.',
            'complaint_content.string' => 'Nội dung không hợp lệ.',
            'complaint_content.max' => 'Nội dung không được vượt quá 1000 ký tự.',
        ]);

        DB::beginTransaction();
        try {
            $complaint = Feedback::create([
                'user_id' => Auth::id(),
                'isHideUser' => $this->isHideUser ? 1 : 0,
                'type' => 'complaint',
                'title' => $this->complaint_title,
                'content' => $this->complaint_content,
                'status' => 'pending',
            ]);

            if (!empty($this->uploadedFiles)) {
                foreach ($this->uploadedFiles as $file) {
                    FeedbackImage::create([
                        'feedback_id' => $complaint->id,
                        'file_name' => $file,
                    ]);
                }
            }

            DB::commit();
            $this->hideComplaintModal();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Thêm khiếu nại thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra khi thêm góp ý. Vui lòng thử lại.' . $e->getMessage()]);
        }
    }

    public function deleteComplaintAction($id)
    {
        DB::beginTransaction();

        try {
            $complaint = Feedback::findOrFail($id);

            // Lấy danh sách ảnh liên quan
            $images = FeedbackImage::where('feedback_id', $id)->get();

            foreach ($images as $image) {
                $path = public_path('images/complaints/' . $image->file_name);

                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            // Xoá bản ghi trong bảng
            FeedbackImage::where('feedback_id', $id)->delete();
            $complaint->delete();

            DB::commit();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Xóa khiếu nại thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra khi xóa góp ý. Vui lòng thử lại.']);
        }
    }

    public function uploadedFilesComplaint($files)
    {
        $this->uploadedFiles = $files;
    }

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function addComplaint()
    {
        $this->resetModal();
        $this->isUpdateComplaintMode = false;
        $this->uploadedFiles = [];
        $this->showComplaintModal();
    }

    public function deleteComplaint($id)
    {
        $complaint = Feedback::findOrFail($id);
        $this->dispatch('deleteComplaint', ['id' => $complaint->id, 'title' => $complaint->title]);
    }

    public function mount()
    {
        $this->tab = request('tab') ? request('tab') : $this->tabname;
    }

    public function showComplaintModal()
    {
        $this->resetErrorBag();
        $this->dispatch('showComplaintModal');
    }

    public function hideComplaintModal()
    {
        $this->dispatch('hideComplaintModal');
        $this->isUpdateComplaintMode = false;
        $this->uploadedFiles = [];
        $this->resetModal();
    }

    public function resetModal()
    {
        $this->isUpdateComplaintMode = false;
        $this->complaint_id = $this->complaint_title = $this->complaint_content = null;
    }

    public function render()
    {
        $listComplaintsPendingAndInProgress = Feedback::where('user_id', Auth::id())
            ->where('type', 'complaint')
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('created_at', 'desc')
            ->get();

        $listComplaintsResolved = Feedback::where('user_id', Auth::id())
            ->where('type', 'complaint')
            ->where('status', 'resolved')
            ->orderBy('created_at', 'desc')
            ->get();

        $listComplaintsCanceled = Feedback::where('user_id', Auth::id())
            ->where('type', 'complaint')
            ->where('status', 'canceled')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.support.complaints', [
            'listComplaintsPendingAndInProgress' => $listComplaintsPendingAndInProgress,
            'listComplaintsResolved' => $listComplaintsResolved,
            'listComplaintsCanceled' => $listComplaintsCanceled,
        ]);
    }
}
