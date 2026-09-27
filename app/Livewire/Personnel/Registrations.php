<?php

namespace App\Livewire\Personnel;

use App\Models\Course;
use App\Models\RegistrationRequest;
use App\Models\Sector;
use App\Services\RegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Registrations extends Component
{
    use WithPagination;

    public $tab = 'pending';
    public $type = '';
    public $search;
    protected $queryString = ['tab' => ['except' => 'pending'], 'type' => ['except' => '']];

    // Modal
    public $request_id;
    public $holyName, $fullName, $birthday, $address, $sector_id, $course_id;
    public $admin_note;
    public $invalidate_old_card = false; // chỉ trưởng quyết định, phụ huynh không chọn được

    public function selectTab($tab)
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function viewRequest($id)
    {
        $this->resetErrorBag();
        $request = RegistrationRequest::findOrFail($id);

        $this->request_id = $request->id;
        $this->holyName = $request->holyName;
        $this->fullName = $request->fullName;
        $this->birthday = $request->birthday?->format('Y-m-d');
        $this->address = $request->address;
        $this->sector_id = $request->sector_id;
        $this->course_id = $request->course_id;
        $this->admin_note = $request->admin_note;
        $this->invalidate_old_card = $request->reason === 'lost';

        $this->dispatch('showRegistrationModal');
    }

    public function approve()
    {
        $this->ensurePermission();
        $request = RegistrationRequest::where('status', 'pending')->findOrFail($this->request_id);

        if ($request->type === 'new') {
            $this->validate([
                'holyName' => 'required|string|max:100',
                'fullName' => 'required|string|max:255',
                'birthday' => 'required|date',
                'address' => 'required|string|max:255',
                'sector_id' => 'required|exists:sectors,id',
                'course_id' => 'nullable|exists:courses,id',
                'admin_note' => 'nullable|string|max:255',
            ], [
                'holyName.required' => 'Tên Thánh không được để trống',
                'fullName.required' => 'Họ và tên không được để trống',
                'birthday.required' => 'Ngày sinh không được để trống',
                'address.required' => 'Địa chỉ không được để trống',
                'sector_id.required' => 'Vui lòng xếp ngành cho em',
            ]);

            $existing = RegistrationService::findExistingChild($this->holyName, $this->fullName, $this->birthday);
            if ($existing) {
                $this->addError('fullName', 'Thiếu nhi này đã tồn tại (Mã ' . $existing->account_code . '). Vui lòng từ chối đơn nếu bị trùng.');
                return;
            }

            // Cho phép trưởng sửa lại thông tin phụ huynh nhập sai trước khi tạo tài khoản
            $request->update([
                'holyName' => RegistrationService::formatName($this->holyName),
                'fullName' => RegistrationService::formatName($this->fullName),
                'birthday' => $this->birthday,
                'address' => trim($this->address),
                'sector_id' => $this->sector_id,
                'course_id' => $this->course_id ?: null,
            ]);
        } elseif (!$request->user) {
            $this->addError('admin_note', 'Tài khoản thiếu nhi của đơn này không còn tồn tại. Vui lòng từ chối đơn.');
            return;
        }

        try {
            $user = RegistrationService::approve($request, $this->admin_note ?: null, (bool) $this->invalidate_old_card);
        } catch (\Exception $e) {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Duyệt đơn thất bại. ' . $e->getMessage()]);
            return;
        }

        $this->hideRegistrationModal();
        $this->dispatch('showToastr', [
            'type' => 'success',
            'message' => ($request->type === 'new' ? 'Đã tạo tài khoản ' : 'Đã duyệt cấp lại thẻ cho ') . $user->SimpleName . ' (' . $user->account_code . ').',
        ]);
    }

    public function reject()
    {
        $this->ensurePermission();
        $this->validate(
            ['admin_note' => 'required|string|max:255'],
            ['admin_note.required' => 'Vui lòng ghi lý do từ chối (phụ huynh sẽ thấy khi tra cứu)']
        );

        $request = RegistrationRequest::where('status', 'pending')->findOrFail($this->request_id);
        RegistrationService::reject($request, $this->admin_note);

        $this->hideRegistrationModal();
        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đã từ chối đơn ' . $request->code . '.']);
    }

    public function hideRegistrationModal()
    {
        $this->dispatch('hideRegistrationModal');
        $this->reset(['request_id', 'holyName', 'fullName', 'birthday', 'address', 'sector_id', 'course_id', 'admin_note', 'invalidate_old_card']);
        $this->resetErrorBag();
    }

    // Route middleware không chạy lại cho request Livewire, nên kiểm tra quyền trước khi ghi
    private function ensurePermission(): void
    {
        abort_unless(Auth::user()->hasPermission('admin.personnel.registration'), 403);
    }

    public function render()
    {
        $counts = RegistrationRequest::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $listRequests = RegistrationRequest::with(['user.courses', 'user.sectors', 'sector', 'course', 'reviewer'])
            ->where('status', $this->tab)
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->search, function ($q) {
                $search = '%' . trim($this->search) . '%';
                $q->where(function ($q) use ($search) {
                    $q->where('code', 'like', $search)
                        ->orWhere('fullName', 'like', $search)
                        ->orWhere('contact_phone', 'like', $search)
                        ->orWhereHas('user', fn($u) => $u
                            ->whereRaw("CONCAT(lastName, ' ', name) LIKE ?", [$search])
                            ->orWhere('account_code', 'like', $search));
                });
            })
            ->orderBy($this->tab === 'pending' ? 'created_at' : 'reviewed_at', $this->tab === 'pending' ? 'asc' : 'desc')
            ->paginate(15);

        return view('livewire.personnel.registrations', [
            'counts' => $counts,
            'listRequests' => $listRequests,
            'current' => $this->request_id
                ? RegistrationRequest::with(['user.courses', 'user.sectors', 'user.studentParent', 'duplicateUser', 'sector', 'course'])->find($this->request_id)
                : null,
            'listSectors' => Sector::orderBy('ordering')->get(),
            'listCourses' => Course::orderBy('ordering')->get(),
        ]);
    }
}
