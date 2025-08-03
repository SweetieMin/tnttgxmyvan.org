<?php

namespace App\Livewire\Management;

use Livewire\Component;
use App\Models\Course;

class Courses extends Component
{
    public $isUpdateCourseMode = false;
    public $isMountCallMode = false;
    public $course_id, $course_name, $course_description;

    protected $listeners = [
        'updateCourseOrdering',
        'deleteCourseAction'
    ];

    public function addCourse()
    {
        $this->course_name = null;
        $this->course_description = null;
        $this->isUpdateCourseMode = false;
        $this->showCourseModalForm();
    }

    public function showCourseModalForm()
    {
        $this->resetErrorBag();
        $this->dispatch('showCourseModalForm');
    }

    public function hideCourseModalForm()
    {
        $this->dispatch('hideCourseModalForm');
        $this->isUpdateCourseMode = false;
        $this->course_name = $this->course_description = null;
    }

    public function createCourse()
    {
        $this->validate([
            'course_name' => 'required|unique:courses,course_name',
            'course_description' => 'required|min:2'
        ], [
            'course_name.required' => 'Tên lớp Giáo Lý là bắt buộc.',
            'course_name.unique' => 'Tên lớp Giáo Lý đã tồn tại.',
            'course_description.required' => 'Mô tả lớp Giáo Lý là bắt buộc.',
            'course_description.min' => 'Mô tả ít nhất 2 ký tự.',
        ]);

        $course = new Course();
        $course->name = ucwords(strtolower($this->course_name));
        $course->description = ucfirst($this->course_description);
        $save = $course->save();

        if ($save) {
            $this->hideCourseModalForm();
            $this->updateCourseOrdering();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã thêm lớp Giáo Lý ' . $this->course_name . ' thành công.']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
        }
    }

    public function editCourse($id)
    {
        $course = Course::query()->findOrFail($id);
        $this->course_id = $course->id;
        $this->course_name = ucwords($course->name);
        $this->course_description = ucfirst($course->description);
        $this->isUpdateCourseMode = true;
        $this->showCourseModalForm();
    }

    public function updateCourse()
    {
        $course = Course::query()->findOrFail($this->course_id);
        $this->validate([
            'course_name' => 'required|unique:courses,name,' . $course->id,
            'course_description' => 'required|min:2'
        ], [
            'course_name.required' => 'Tên lớp Giáo Lý là bắt buộc.',
            'course_name.unique' => 'Tên lớp Giáo Lý đã tồn tại.',
            'course_description.required' => 'Mô tả lớp Giáo Lý là bắt buộc.',
            'course_description.min' => 'Mô tả ít nhất 2 ký tự.',
        ]);

        $course->name = ucwords(strtolower($this->course_name));
        $course->description = ucfirst($this->course_description);
        $update = $course->save();

        if ($update) {
            $this->hideCourseModalForm();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã cập nhật lớp Giáo Lý ' . $this->course_name . ' thành công.']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
        }
    }

    public function updateCourseOrdering($positions = null)
    {

        if (!$positions) {
            $courses = Course::orderBy('ordering')->get();
            $positions = $courses->map(function ($course, $index) {
                return [$course->id, $index + 1];
            });
        }

        foreach ($positions as $position) {
            $index = $position[0];
            $new_position = $position[1];

            Course::query()->where('id', $index)->update([
                'ordering' => $new_position
            ]);
        }

        if (!$this->isMountCallMode) {
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã cập nhật danh sách lớp Giáo Lý thành công.']);
        }
    }

    public function deleteCourse($id)
    {
        $this->dispatch('deleteCourse', ['id' => $id]);
    }

    public function deleteCourseAction($id)
    {
        $course = Course::query()->findOrFail($id);
        $course_name = ucwords($course->name);
        $delete = $course->delete();

        $this->updateCourseOrdering();

        if ($delete) {
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã xóa lớp Giáo Lý ' . $course_name . ' thành công.']);
        } else {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đã có lỗi xảy ra. Bạn hãy thử lại sau nhé.']);
        }
    }

    public function mount()
    {
        $this->isMountCallMode = true;
        $this->updateCourseOrdering();
        $this->isMountCallMode = false;
    }
    public function render()
    {
        return view('livewire.management.courses',[
            'courses' => Course::orderBy('ordering', 'asc')->get()
        ]);
    }
}