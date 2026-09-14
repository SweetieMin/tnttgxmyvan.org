<?php

namespace App\Livewire\Management;

use Livewire\Component;
use App\Models\Bible;

class Bibles extends Component
{

    public $isUpdateBibleMode = false;
    public $isMountCallMode = false;
    public $bible_id, $bible;

    protected $listeners = [
        'updateBibleOrdering',
        'deleteBibleAction'
    ];

    public function addBible()
    {
        $this->bible = null;
        $this->isUpdateBibleMode = false;
        $this->showBibleModalForm();
    }

    public function showBibleModalForm()
    {
        $this->resetErrorBag();
        $this->dispatch('showBibleModalForm');
    }

    public function hideBibleModalForm()
    {
        $this->dispatch('hideBibleModalForm');
        $this->isUpdateBibleMode = false;
        $this->bible = null;
    }

    public function createBible()
    {
        $this->validate([
            'bible' => 'required|unique:bibles,bible',
        ], [
            'bible.required' => 'Điền câu Kinh Thánh.',
            'bible.unique' => 'Câu Kinh Thánh đã tồn tại.',
        ]);

        $bible = new Bible();
        $bible->bible = $this->bible;
        $save = $bible->save();

        if ($save) {
            $this->hideBibleModalForm();
            $this->updateBibleOrdering();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã thêm câu Kinh Thánh thành công.']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
        }
    }

    public function editBible($id)
    {
        $bible = Bible::query()->findOrFail($id);
        $this->bible_id = $bible->id;
        $this->bible = $bible->bible;
        $this->isUpdateBibleMode = true;
        $this->showBibleModalForm();
    }

    public function updateBible()
    {
        $bible = Bible::query()->findOrFail($this->bible_id);
        $this->validate([
            'bible' => 'required|unique:bibles,bible,' . $this->bible_id,
        ], [
            'bible.required' => 'Điền câu Kinh Thánh.',
            'bible.unique' => 'Câu Kinh Thánh đã tồn tại.',
        ]);


        $bible->bible = $this->bible;
        $update = $bible->save();

        if ($update) {
            $this->hideBibleModalForm();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã cập nhật câu Kinh Thánh thành công.']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
        }
    }

    public function updateBibleOrdering($positions = null)
    {

        if (!$positions) {
            $bible = Bible::orderBy('ordering')->get();
            $positions = $bible->map(function ($bible, $index) {
                return [$bible->id, $index + 1];
            });
        }

        foreach ($positions as $position) {
            $index = $position[0];
            $new_position = $position[1];

            Bible::query()->where('id', $index)->update([
                'ordering' => $new_position
            ]);
        }

        if (!$this->isMountCallMode) {
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã cập nhật danh sách lớp Giáo Lý thành công.']);
        }
    }

    public function deleteBible($id)
    {
        $this->dispatch('deleteBible', ['id' => $id]);
    }

    public function deleteBibleAction($id)
    {
        $bible = Bible::query()->findOrFail($id);
        $delete = $bible->delete();

        $this->updateBibleOrdering();

        if ($delete) {
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã xóa câu Kinh Thánh thành công.']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
        }
    }

    public function mount()
    {
        $this->isMountCallMode = true;
        $this->updateBibleOrdering();
        $this->isMountCallMode = false;
    }

    public function render()
    {
        return view('livewire.management.bibles',[
            'bibles' => Bible::orderBy('ordering', 'asc')->get()
        ]);
    }
}
