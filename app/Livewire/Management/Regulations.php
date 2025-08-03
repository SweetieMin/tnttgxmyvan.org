<?php

namespace App\Livewire\Management;

use Livewire\Component;
use App\Models\Regulation;
use Illuminate\Support\Facades\DB;

class Regulations extends Component
{
    public $isEditMode = false;
    public $regulation_id;
    public $regulationForm = [
        'description' => '',
        'type' => '',
        'points' => 0,
        'applicable_object_text' => '',
        'is_active' => true,
    ];

    protected function rules()
    {
        return [
            'regulationForm.description' => 'required|string|min:5',
            'regulationForm.type' => 'required|in:plus,minus',
            'regulationForm.points' => 'required|integer|min:1|max:100',
            'regulationForm.applicable_object_text' => 'required|string',
            'regulationForm.is_active' => 'boolean',
            'regulationForm.is_attendance' => 'boolean',
        ];
    }

    protected function messages()
    {
        return [
            'regulationForm.description.required' => 'Vui lòng nhập nội dung nội quy.',
            'regulationForm.description.min' => 'Nội dung nội quy phải có ít nhất 5 ký tự.',
            'regulationForm.type.required' => 'Vui lòng chọn loại nội quy.',
            'regulationForm.points.required' => 'Vui lòng nhập số điểm.',
            'regulationForm.points.integer' => 'Số điểm phải là số nguyên.',
            'regulationForm.points.min' => 'Số điểm phải lớn hơn hoặc bằng 1.',
            'regulationForm.points.max' => 'Số điểm không được lớn hơn 100.',
            'regulationForm.applicable_object_text.required' => 'Vui lòng nhập đối tượng áp dụng.',
        ];
    }

    public function updateOrdering($positions = null)
    {   
        DB::beginTransaction();

        try {

            if ($positions == null) {
                $regulations = Regulation::orderBy('ordering')->get();
                $positions = $regulations->map(function ($regulation, $index) {
                    return [$regulation->id, $index + 1];
                });
            }

            foreach ($positions as $position) {
                $regulationId = $position[0];
                $newPosition = $position[1];
                Regulation::where('id', $regulationId)->update(['ordering' => $newPosition]);
            }
            DB::commit();
            $this->dispatch('showToastr', ['message' => 'Đã thay đổi vị trí nội quy!', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['message' => 'Đã xảy ra lỗi khi thay đổi vị trí nội quy.', 'type' => 'error']);
        }
    }


    public function addRegulation()
    {
        $this->reset('regulationForm', 'isEditMode', 'regulation_id');
        $this->regulationForm['is_active'] = true;
    }

    public function editRegulation($id)
    {
        $this->isEditMode = true;
        $this->regulation_id = $id;
        $reg = Regulation::findOrFail($id);
        $this->regulationForm = [
            'description' => $reg->description,
            'type' => $reg->type,
            'points' => $reg->points,
            'applicable_object_text' => implode(', ', $reg->applicable_object),
            'is_active' => $reg->is_active,
        ];
    }


    public function storeRegulation()
    {
        $this->validate();
        DB::beginTransaction();

        try {

            Regulation::create([
                'description' => $this->regulationForm['description'],
                'type' => $this->regulationForm['type'],
                'points' => $this->regulationForm['points'],
                'applicable_object' => array_map('trim', explode(',', $this->regulationForm['applicable_object_text'])),
                'is_active' => $this->regulationForm['is_active'],
                'ordering' => Regulation::max('ordering') + 1,
            ]);

            DB::commit();
            $this->updateOrdering();
            $this->reset('regulationForm');
            $this->dispatch('showToastr', ['message' => 'Thêm nội quy thành công.', 'type' => 'success']);
            $this->dispatch('closeModal');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['message' => 'Đã xảy ra lỗi khi thêm nội quy.', 'type' => 'error']);
        }
    }

    public function updateRegulation()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $reg = Regulation::findOrFail($this->regulation_id);
            $reg->update([
                'description' => $this->regulationForm['description'],
                'type' => $this->regulationForm['type'],
                'points' => $this->regulationForm['points'],
                'applicable_object' => array_map('trim', explode(',', $this->regulationForm['applicable_object_text'])),
                'is_active' => $this->regulationForm['is_active'],
            ]);

            DB::commit();
            $this->updateOrdering();
            $this->dispatch('showToastr', ['message' => 'Cập nhật nội quy thành công.', 'type' => 'success']);
            $this->dispatch('closeModal');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['message' => 'Đã xảy ra lỗi khi cập nhật nội quy.', 'type' => 'error']);
        }
    }

    public function deleteRegulation($id)
    {
        DB::beginTransaction();

        try {
            $reg = Regulation::findOrFail($id);
            $reg->delete();

            DB::commit();
            $this->updateOrdering();
            $this->dispatch('showToastr', ['message' => 'Xóa nội quy thành công.', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['message' => 'Đã xảy ra lỗi khi xóa nội quy.', 'type' => 'error']);
        }
    }

    public function toggleActive($id)
    {
        $regulation = Regulation::findOrFail($id);
        $regulation->is_active = !$regulation->is_active;
        $regulation->save();

        $this->dispatch('showToastr', [
            'message' => 'Trạng thái đã được cập nhật.',
            'type' => 'success'
        ]);
    }

    public function render()
    {

        $plusRegulations = Regulation::where('type', 'plus')->orderBy('ordering')->get();
        $minusRegulations = Regulation::where('type', 'minus')->orderBy('ordering')->get();
        
        return view('livewire.management.regulations', [
            'plusRegulations' => $plusRegulations,
            'minusRegulations' => $minusRegulations,
        ]);
    }
}
