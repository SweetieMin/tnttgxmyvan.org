<?php

namespace App\Livewire\Management;

use Livewire\Component;
use App\Models\Sector;

class Sectors extends Component
{
    public $isUpdateSectorMode = false;
    public $isMountCallMode = false;
    public $sector_id, $sector_name, $sector_description;

    protected $listeners = [
        'updateSectorOrdering',
        'deleteSectorAction'
    ];

    public function addSector()
    {
        $this->sector_name = null;
        $this->sector_description = null;
        $this->isUpdateSectorMode = false;
        $this->showSectorModalForm();
    }

    public function showSectorModalForm()
    {
        $this->resetErrorBag();
        $this->dispatch('showSectorModalForm');
    }

    public function hideSectorModalForm()
    {
        $this->dispatch('hideSectorModalForm');
        $this->isUpdateSectorMode = false;
        $this->sector_name = $this->sector_description = null;
    }

    public function createSector()
    {
        $this->validate([
            'sector_name' => 'required|unique:sectors,name',
            'sector_description' => 'required|min:2'
        ], [
            'sector_name.required' => 'Tên ngành là bắt buộc.',
            'sector_name.unique' => 'Tên ngành đã tồn tại.',
            'sector_description.required' => 'Mô tả ngành là bắt buộc.',
            'sector_description.min' => 'Mô tả ít nhất 2 ký tự.',
        ]);

        $sector = new Sector();
        $sector->name = ucwords($this->sector_name);
        $sector->description = ucfirst($this->sector_description);
        $save = $sector->save();

        if ($save) {
            $this->hideSectorModalForm();
            $this->updateSectorOrdering();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã thêm ngành ' . $this->sector_name . ' thành công.']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
        }
    }

    public function editSector($id)
    {
        $sector = Sector::query()->findOrFail($id);
        $this->sector_id = $sector->id;
        $this->sector_name = ucwords(strtolower($sector->name));
        $this->sector_description = ucfirst(strtolower($sector->description));
        $this->isUpdateSectorMode = true;
        $this->showSectorModalForm();
    }

    public function updateSector()
    {
        $sector = Sector::query()->findOrFail($this->sector_id);
        $this->validate([
            'sector_name' => 'required|unique:sectors,name,' . $sector->id,
            'sector_description' => 'required|min:2'
        ], [
            'sector_name.required' => 'Tên ngành là bắt buộc.',
            'sector_name.unique' => 'Tên ngành đã tồn tại.',
            'sector_description.required' => 'Mô tả ngành là bắt buộc.',
            'sector_description.min' => 'Mô tả ít nhất 2 ký tự.',
        ]);

        $sector->name = ucwords($this->sector_name);
        $sector->description = ucfirst($this->sector_description);
        $update = $sector->save();

        if ($update) {
            $this->hideSectorModalForm();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã cập nhật ngành ' . $this->sector_name . ' thành công.']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
        }
    }

    public function updateSectorOrdering($positions = null)
    {

        if (!$positions) {
            $sectors = Sector::orderBy('ordering')->get();
            $positions = $sectors->map(function ($sector, $index) {
                return [$sector->id, $index + 1];
            });
        }

        foreach ($positions as $position) {
            $index = $position[0];
            $new_position = $position[1];

            Sector::query()->where('id', $index)->update([
                'ordering' => $new_position
            ]);
        }

        if (!$this->isMountCallMode) {
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã cập nhật danh sách ngành thành công.']);
        }
    }

    public function deleteSector($id)
    {
        $this->dispatch('deleteSector', ['id' => $id]);
    }

    public function deleteSectorAction($id)
    {
        $sector = Sector::query()->findOrFail($id);
        $sector_name = ucwords(strtolower($sector->name));
        $delete = $sector->delete();

        $this->updateSectorOrdering();

        if ($delete) {
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã xóa ngành ' . $sector_name . ' thành công.']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
        }
    }

    public function mount()
    {
        $this->isMountCallMode = true;
        $this->updateSectorOrdering();
        $this->isMountCallMode = false;
    }
    public function render()
    {
        return view('livewire.management.sectors',[
            'sectors' => Sector::orderBy('ordering', 'asc')->get()
        ]);
    }
}
