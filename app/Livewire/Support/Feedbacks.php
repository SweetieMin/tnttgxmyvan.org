<?php

namespace App\Livewire\Support;

use App\Models\Feedback;
use App\Models\FeedbackImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class Feedbacks extends Component
{
    public $tab = null;
    public $tabname = 'tabMain';
    public $isUpdateFeedbackMode = false;
    public $feedback_id, $feedback_title, $feedback_content, $feedback_picture, $isHideUser = false;
    protected $queryString = ['tab' => ['keep' => true]];

    public $uploadedFiles = [];

    protected $listeners = [
        'uploadedFilesFeedback',
        'deleteFeedbackAction',
    ];

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function uploadedFilesFeedback($files)
    {
        $this->uploadedFiles = $files;
    }

    public function createFeedback()
    {

        $this->validate([
            'feedback_title' => 'required|string|max:100',
            'feedback_content' => 'required|string|max:1000',
        ], [
            'feedback_title.required' => 'Vui lòng nhập tiêu đề.',
            'feedback_title.string' => 'Tiêu đề không hợp lệ.',
            'feedback_title.max' => 'Tiêu đề không được vượt quá 100 ký tự.',
            'feedback_content.required' => 'Vui lòng nhập nội dung.',
            'feedback_content.string' => 'Nội dung không hợp lệ.',
            'feedback_content.max' => 'Nội dung không được vượt quá 1000 ký tự.',
        ]);

        DB::beginTransaction();

        try {
            $feedback = Feedback::create([
                'user_id' => Auth::id(),
                'isHideUser' => $this->isHideUser ? 1 : 0,
                'type' => 'feedback',
                'title' => $this->feedback_title,
                'content' => $this->feedback_content,
                'status' => 'pending',
            ]);

            if (!empty($this->uploadedFiles)) {
                foreach ($this->uploadedFiles as $fileName) {
                    FeedbackImage::create([
                        'feedback_id' => $feedback->id,
                        'file_name' => $fileName,
                    ]);
                }
            }

            DB::commit();
            $this->hideFeedbackModal();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Thêm góp ý thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra khi thêm góp ý. Vui lòng thử lại.']);
        }
    }

    public function addFeedback()
    {
        $this->resetModal();
        $this->isUpdateFeedbackMode = false;
        $this->uploadedFiles = [];
        $this->showFeedbackModal();
    }

    public function deleteFeedback($feedbackId)
    {
        $feedback = Feedback::findOrFail($feedbackId);
        $this->dispatch('deleteFeedback', ['id' => $feedback->id, 'title' => $feedback->title]);
    }

    public function deleteFeedbackAction($feedbackId)
    {
        DB::beginTransaction();

        try {
            $feedback = Feedback::findOrFail($feedbackId);

            // Lấy danh sách ảnh liên quan
            $images = FeedbackImage::where('feedback_id', $feedbackId)->get();

            foreach ($images as $image) {
                $path = public_path('images/feedbacks/' . $image->file_name);

                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            // Xoá bản ghi trong bảng
            FeedbackImage::where('feedback_id', $feedbackId)->delete();
            $feedback->delete();

            DB::commit();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Xóa góp ý thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra khi xóa góp ý. Vui lòng thử lại.']);
        }
    }

    public function mount()
    {
        $this->tab = request('tab') ? request('tab') : $this->tabname;
    }

    public function showFeedbackModal()
    {
        $this->resetErrorBag();
        $this->dispatch('showFeedbackModal');
    }

    public function hideFeedbackModal()
    {
        $this->dispatch('hideFeedbackModal');
        $this->isUpdateFeedbackMode = false;
        $this->uploadedFiles = [];
        $this->resetModal();
    }


    public function resetModal()
    {
        $this->isUpdateFeedbackMode = false;
        $this->feedback_id = $this->feedback_title = $this->feedback_content = $this->feedback_picture = null;
    }

    public function render()
    {
        $listFeedbacksPendingAndInProgress = Feedback::where('user_id', Auth::id())
            ->where('type', 'feedback')
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('created_at', 'desc')
            ->get();

        $listFeedbacksResolved = Feedback::where('user_id', Auth::id())
            ->where('type', 'feedback')
            ->where('status', 'resolved')
            ->orderBy('created_at', 'desc')
            ->get();

        $listFeedbacksCanceled = Feedback::where('user_id', Auth::id())
            ->where('type', 'feedback')
            ->where('status', 'canceled')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.support.feedbacks', [
            'listFeedbacksPendingAndInProgress' => $listFeedbacksPendingAndInProgress,
            'listFeedbacksResolved' => $listFeedbacksResolved,
            'listFeedbacksCanceled' => $listFeedbacksCanceled,
        ]);
    }
}
