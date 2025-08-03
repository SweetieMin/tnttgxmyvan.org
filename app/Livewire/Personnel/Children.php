<?php

namespace App\Livewire\Personnel;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\Course;
use App\Models\Sector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use  Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogService;
use Livewire\WithPagination;
//use App\Exports\PersonalInfo\ListTotalThieuNhiExport;
use App\Exports\Scores\ScoreScouterExport;
use Maatwebsite\Excel\Facades\Excel;

class Children extends Component
{
    use WithPagination;
    public $course, $sector;
    public $courseModal, $sectorModal;
    public $child_id_avatarModal;
    public $isUpdateChild = false;
    public $search;
    public $managerSector;

    public $child_id, $child_holy_name, $child_full_name, $child_last_name, $child_name, $child_account_code, $child_phone, $child_email, $child_picture, $child_birthday, $child_token, $child_address;
    public  $child_father_name, $child_father_phone, $child_mother_name, $child_mother_phone, $child_godParent_name;

    public $ngay_rua_toi, $linh_muc_rua_toi, $ngay_xung_toi, $ngay_them_suc, $giam_muc_them_suc, $ngay_bao_dong, $trang_thai_ton_giao, $ngay_bo_hoc;

    public $child_picture_card, $child_full_name_card, $child_holy_name_card, $child_token_card, $child_position_card;

    protected $listeners = [
        'chooseDataSort',
        'submitChildFormModal',
        'deleteChildrenAction',
        'resetPasswordChildrenAction',
        'refreshChildren' => '$refresh',
    ];

    public function exportChildren()
    {
        return Excel::download(new ScoreScouterExport(), 'Danh_sach_thieu_nhi.xlsx');
    }

    public function generateAccountCode()
    {
        if (!$this->isUpdateChild) {
            $date = \Carbon\Carbon::parse($this->child_birthday);
            $formattedDate = $date->format('dmy');
            $randomNumbers = rand(10, 99);

            $accountCode = 'MV' . $formattedDate . $randomNumbers;
            while (User::where('account_code', $accountCode)->exists()) {
                $randomNumbers = rand(01, 99);
                $accountCode = 'MV' . $formattedDate . $randomNumbers;
            }
            $this->child_account_code = $accountCode;

            $formattedFullName = trim(preg_replace('/\s+/', ' ', ucwords(strtolower($this->child_full_name))));
            $formattedHollyName = trim(preg_replace('/\s+/', ' ', ucwords(strtolower($this->child_holy_name))));
            $nameParts = explode(" ", $formattedFullName);
            $this->child_last_name = implode(" ", array_slice($nameParts, 0, -1));
            $this->child_name = end($nameParts);

            $existingUser = User::where('holyName', $this->child_holy_name)
                ->where('lastName', $this->child_last_name)
                ->where('name', $this->child_name)
                ->where('birthday', $this->child_birthday)
                ->first();

            if ($existingUser && $this->isUpdateChild === false) {
                $this->addError('child_full_name', 'Người dùng này đã tồn tại. ( Mã tài khoản ' . $existingUser->account_code . ' )');
            } else {
                $this->resetErrorBag('child_full_name');
            }
        }
    }

    public function submitChildFormModal($courseModal, $sectorModal, $trang_thai_ton_giao)
    {
        $formattedFullName = trim(preg_replace('/\s+/', ' ', ucwords(strtolower($this->child_full_name))));
        $formattedHollyName = trim(preg_replace('/\s+/', ' ', ucwords(strtolower($this->child_holy_name))));
        $nameParts = explode(" ", $formattedFullName);
        $this->child_last_name = implode(" ", array_slice($nameParts, 0, -1));
        $this->child_name = end($nameParts);

        $existingUser = User::where('holyName', $this->child_holy_name)
            ->where('lastName', $this->child_last_name)
            ->where('name', $this->child_name)
            ->where('birthday', $this->child_birthday)
            ->first();

        if ($existingUser && $this->isUpdateChild === false) {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Người dùng này đã tồn tại. ( Mã tài khoản ' . $existingUser->account_code . ' )']);
            return;
        }

        $this->courseModal = $courseModal;
        $this->sectorModal = $sectorModal;
        $this->trang_thai_ton_giao = $trang_thai_ton_giao;

        $this->validate(
            [
                'child_birthday' => 'required|date',
                'child_holy_name' => 'required|string|max:100',
                'child_full_name' => 'required|string|max:255',
                'child_phone' => ['nullable', 'regex:/^(0[3,5,7,8,9])[0-9]{8}$/'],
                'child_address' => 'required|string|max:255',
                //Phụ Huynh
                'child_father_name' => 'nullable|string|max:255',
                'child_father_phone' => ['nullable', 'regex:/^(0[3,5,7,8,9])[0-9]{8}$/'],
                'child_mother_name' => 'nullable|string|max:255',
                'child_mother_phone' => ['nullable', 'regex:/^(0[3,5,7,8,9])[0-9]{8}$/'],
                'child_godParent_name' => 'nullable|string|max:255',
                //Thông tin khác
                'ngay_rua_toi' => 'nullable|date',
                'linh_muc_rua_toi' => 'nullable|string|max:255',
                'ngay_xung_toi' => 'nullable|date',
                'ngay_them_suc' => 'nullable|date',
                'giam_muc_them_suc' => 'nullable|string|max:255',
                'ngay_bao_dong' => 'nullable|date',

            ],
            [
                'child_birthday.required' => 'Ngày sinh không được để trống',
                'child_birthday.date' => 'Ngày sinh không đúng định dạng',
                'child_holy_name.required' => 'Tên Thánh không được để trống',
                'child_holy_name.string' => 'Tên Thánh không đúng định dạng',
                'child_holy_name.max' => 'Tên Thánh không được quá 100 ký tự',
                'child_full_name.required' => 'Họ và tên không được để trống',
                'child_full_name.string' => 'Họ và tên không đúng định dạng',
                'child_full_name.max' => 'Họ và tên không được quá 255 ký tự',
                'child_phone.regex' => 'Số điện thoại không đúng định dạng',
                'child_address.required' => 'Địa chỉ không được để trống',
                'child_address.string' => 'Địa chỉ không đúng định dạng',
                'child_address.max' => 'Địa chỉ không được quá 255 ký tự',
                //Phụ Huynh
                'child_father_name.string' => 'Tên phụ huynh không đúng định dạng',
                'child_father_name.max' => 'Tên phụ huynh không được quá 255 ký tự',
                'child_father_phone.regex' => 'Số điện thoại phụ huynh không đúng định dạng',
                'child_mother_name.string' => 'Tên phụ huynh không đúng định dạng',
                'child_mother_name.max' => 'Tên phụ huynh không được quá 255 ký tự',
                'child_mother_phone.regex' => 'Số điện thoại phụ huynh không đúng định dạng',
                'child_godParent_name.string' => 'Tên người đỡ đầu không đúng định dạng',
                'child_godParent_name.max' => 'Tên người đỡ đầu không được quá 255 ký tự',
                //Thông tin khác
                'linh_muc_rua_toi.max' => 'Tên linh mục rửa tội không được quá 255 ký tự',
                'ngay_xung_toi.date' => 'Ngày xưng tội không đúng định dạng',
                'ngay_them_suc.date' => 'Ngày thêm sức không đúng định dạng',
                'giam_muc_them_suc.string' => 'Tên giám mục thêm sức không đúng định dạng',
                'giam_muc_them_suc.max' => 'Tên giám mục thêm sức không được quá 255 ký tự',
                'ngay_bao_dong.date' => 'Ngày báo danh không đúng định dạng',
            ]
        );
        if ($this->isUpdateChild) {
            $this->updateChild();
        } else {
            $this->createChild();
        }
    }

    public function addChild()
    {
        $this->isUpdateChild = false;
        $this->dispatch('showChildModal');
        $this->resetModal();
    }

    public function createChild()
    {
        DB::beginTransaction();
        try {
            $child = new User();
            $child->birthday = $this->child_birthday; //
            $child->account_code = $this->child_account_code; //
            $child->holyName = $this->child_holy_name; //
            $child->lastName = $this->child_last_name; //
            $child->name = $this->child_name; //
            $child->phone = $this->child_phone; //
            $child->address = $this->child_address; //
            $child->token = Str::random(64); //
            $child->password = Hash::make($child->account_code); //
            $child->save();
            $child->courses()->sync($this->courseModal);
            $child->sectors()->sync($this->sectorModal);
            $child->roles()->sync(Role::where('name', 'Thiếu Nhi')->first()->id);
            //Parent

            if (!empty($this->child_father_name) || !empty($this->child_father_phone) || !empty($this->child_mother_name) || !empty($this->child_mother_phone)) {
                $child->studentParent()->updateOrCreate(
                    ['user_id' => $child->id],
                    [
                        'nameFather'   => $this->child_father_name,
                        'phoneFather'  => $this->child_father_phone,
                        'nameMother'   => $this->child_mother_name,
                        'phoneMother'  => $this->child_mother_phone,
                        'godParent'    => $this->child_godParent_name,
                    ]
                );
            }

            //Other

            $child->religiousProfile()->updateOrCreate(
                ['user_id' => $child->id],
                [
                    'ngay_rua_toi'      => $this->ngay_rua_toi,
                    'linh_muc_rua_toi'  => $this->linh_muc_rua_toi,
                    'ngay_xung_toi'     => $this->ngay_xung_toi,
                    'ngay_them_suc'     => $this->ngay_them_suc,
                    'giam_muc_them_suc' => $this->giam_muc_them_suc,
                    'ngay_bao_dong'     => $this->ngay_bao_dong,
                    'trang_thai_ton_giao' => $this->trang_thai_ton_giao,
                ]
            );


            DB::commit();

            ActivityLogService::log(
                'Tạo tài khoản',
                'Thêm thiếu nhi',
                $child->id,
                $child->SimpleName,
            );

            $this->dispatch('hideChildModal');
            $this->updateAvatar($child->id);
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Thêm thiếu nhi ' . $child->child_full_name . ' thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Tạo tài khoản thất bại.' . $e->getMessage()]);
            return;
        }
    }

    public function updateAvatar($id)
    {
        $child = User::find($id);
        $this->child_picture = $child->picture;
        $this->child_holy_name = $child->holyName;
        $this->child_full_name = $child->SimpleName;
        $this->child_token = $child->token;
        $this->dispatch('showChildrenAvatar', ['id' => $child->id]);
    }

    public function updateChild()
    {
        DB::beginTransaction();
        try {
            $child = User::findOrFail($this->child_id);
            $child->holyName = $this->child_holy_name; //
            $child->lastName = $this->child_last_name; //
            $child->name = $this->child_name; //
            $child->phone = $this->child_phone; //
            $child->address = $this->child_address; //
            $child->save();


            $child->courses()->sync($this->courseModal);
            $child->sectors()->sync($this->sectorModal);

            //Parent

            if (!empty($this->child_father_name) || !empty($this->child_father_phone) || !empty($this->child_mother_name) || !empty($this->child_mother_phone)) {
                $child->studentParent()->updateOrCreate(
                    ['user_id' => $child->id],
                    [
                        'nameFather'  => $this->child_father_name,
                        'phoneFather' => $this->child_father_phone,
                        'nameMother'  => $this->child_mother_name,
                        'phoneMother' => $this->child_mother_phone,
                        'godParent'   => $this->child_godParent_name,
                    ]
                );
            }

            // Other

            $religiousProfileData = [
                'ngay_rua_toi'      => $this->ngay_rua_toi,
                'linh_muc_rua_toi'  => $this->linh_muc_rua_toi,
                'ngay_xung_toi'     => $this->ngay_xung_toi,
                'ngay_them_suc'     => $this->ngay_them_suc,
                'giam_muc_them_suc' => $this->giam_muc_them_suc,
                'ngay_bao_dong'     => $this->ngay_bao_dong,
                'trang_thai_ton_giao' => $this->trang_thai_ton_giao,
            ];

            $religiousProfileData['ngay_bo_hoc'] = ($this->trang_thai_ton_giao === 'Đã nghỉ') ? now() : null;

            $child->religiousProfile()->updateOrCreate(
                ['user_id' => $child->id],
                $religiousProfileData
            );


            DB::commit();

            ActivityLogService::log(
                'Cập nhật tài khoản',
                'Cập nhật thiếu nhi',
                $child->id,
                $child->SimpleName,
            );

            $this->hideChildModal();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Cập nhật thiếu nhi ' . $child->child_full_name . ' thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Cập nhật khoản thất bại.' . $e->getMessage()]);
            return;
        }
    }

    public function editChild($id)
    {
        $this->resetModal();
        $child = User::with(['courses', 'sectors', 'studentParent', 'religiousProfile'])->find($id);

        $this->child_id = $child->id;
        $this->isUpdateChild = true;
        $this->child_holy_name = $child->holyName;
        $this->child_full_name = $child->SimpleName;
        $this->child_birthday = Carbon::createFromFormat('d/m/Y', $child->birthday)->format('Y-m-d');
        $this->child_phone = $child->phone;
        $this->child_address = $child->address;
        $this->child_account_code = $child->account_code;
        $this->courseModal = $child->courses->pluck('id')->toArray();
        $this->sectorModal = $child->sectors->pluck('id')->toArray();
        //Phụ Huynh
        $this->child_father_name = $child->studentParent->nameFather ?? '';
        $this->child_father_phone = $child->studentParent->phoneFather ?? '';
        $this->child_mother_name = $child->studentParent->nameMother ?? '';
        $this->child_mother_phone = $child->studentParent->phoneMother ?? '';
        $this->child_godParent_name = $child->studentParent->godParent ?? '';
        //Thông tin khác
        $this->ngay_rua_toi = optional($child->religiousProfile)->ngay_rua_toi
            ? Carbon::parse($child->religiousProfile->ngay_rua_toi)->format('Y-m-d')
            : null;

        $this->linh_muc_rua_toi = optional($child->religiousProfile)->linh_muc_rua_toi ?? '';

        $this->ngay_xung_toi = optional($child->religiousProfile)->ngay_xung_toi
            ? Carbon::parse($child->religiousProfile->ngay_xung_toi)->format('Y-m-d')
            : null;

        $this->ngay_them_suc = optional($child->religiousProfile)->ngay_them_suc
            ? Carbon::parse($child->religiousProfile->ngay_them_suc)->format('Y-m-d')
            : null;

        $this->giam_muc_them_suc = optional($child->religiousProfile)->giam_muc_them_suc ?? '';

        $this->ngay_bao_dong = optional($child->religiousProfile)->ngay_bao_dong
            ? Carbon::parse($child->religiousProfile->ngay_bao_dong)->format('Y-m-d')
            : null;

        $this->trang_thai_ton_giao = optional($child->religiousProfile)->trang_thai_ton_giao ?? '';

        $this->dispatch('showChildModal');
    }

    public function deleteChild($id)
    {
        $child = User::find($id);
        $this->dispatch('deleteChildren', ['id' => $child->id, 'name' => $child->SimpleName]);
    }

    public function deleteChildrenAction($id)
    {
        DB::beginTransaction();
        try {
            $child = User::find($id);
            $child->courses()->detach();
            $child->sectors()->detach();
            $child->studentParent()->delete();
            $child->religiousProfile()->delete();
            $child->delete();

            DB::commit();

            ActivityLogService::log(
                'Xóa tài khoản',
                'Xóa thiếu nhi',
                $child->id,
                $child->SimpleName,
            );

            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Xóa thiếu nhi ' . $child->SimpleName . ' thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Xóa tài khoản thất bại.' . $e->getMessage()]);
            return;
        }
    }

    public function showChildModal()
    {
        $this->resetErrorBag();
        $this->dispatch('showChildModal');
    }

    public function hideChildModal()
    {
        $this->dispatch('hideChildModal');
        $this->isUpdateChild = false;
        $this->resetModal();
    }

    public function resetModal()
    {
        $this->child_id = null;
        $this->child_holy_name = null;
        $this->child_full_name = null;
        $this->child_last_name = null;
        $this->child_name = null;
        $this->child_account_code = null;
        $this->child_phone = null;
        $this->child_email = null;
        $this->child_picture = null;
        $this->child_birthday = null;
        $this->child_token = null;
        $this->child_address = null;

        //Phụ Huynh
        $this->child_father_name = null;
        $this->child_father_phone = null;
        $this->child_mother_name = null;
        $this->child_mother_phone = null;
        $this->child_godParent_name = null;

        //Thông tin khác
        $this->ngay_rua_toi = null;
        $this->linh_muc_rua_toi = null;
        $this->ngay_xung_toi = null;
        $this->ngay_them_suc = null;
        $this->giam_muc_them_suc = null;
        $this->ngay_bao_dong = null;
    }

    public function mount()
    {
        $user = Auth::user();
        $user_role = $user->roles->first()?->name;
        $managerData = $this->getManagerData($user);
    }

    public function chooseDataSort($selectedSectors, $selectedCourses)
    {
        $this->resetPage();
        $this->sector = $selectedSectors;
        $this->course = $selectedCourses;
    }

    public function resetPasswordChild($id)
    {
        $child = User::find($id);
        $this->dispatch('resetPasswordChildren', ['id' => $child->id, 'name' => $child->SimpleName]);
    }

    public function resetPasswordChildrenAction($id)
    {
        DB::beginTransaction();
        try {
            $child = User::find($id);
            $child->status = 'active';
            $child->password = Hash::make($child->account_code);
            $child->save();
            DB::commit();

            ActivityLogService::log(
                'Đặt lại mật khẩu',
                'Đặt lại mật khẩu thiếu nhi',
                $child->id,
                $child->SimpleName,
            );

            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đặt lại mật khẩu cho thiếu nhi ' . $child->SimpleName . ' thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Đặt lại mật khẩu thất bại.' . $e->getMessage()]);
            return;
        }
    }

    public function getListChildren()
    {
        $user = Auth::user();
        $managerData = $this->getManagerData($user);

        $this->managerSector = $managerData['managerSector']; // collection ngành mà user này quản lý

        $listChildren = User::query()
            ->select('users.*', 'roles.ordering as role_ordering', 'sectors.ordering as sector_ordering', 'courses.ordering as course_ordering')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftJoin('sector_user', 'users.id', '=', 'sector_user.user_id')
            ->leftJoin('sectors', 'sector_user.sector_id', '=', 'sectors.id')
            ->leftJoin('course_user', 'users.id', '=', 'course_user.user_id')
            ->leftJoin('courses', 'course_user.course_id', '=', 'courses.id')
            ->where('roles.name', 'Thiếu Nhi') // chỉ lấy user có role là "Thiếu Nhi"
            ->whereIn('sectors.id', $this->managerSector->pluck('id')->toArray()) // chỉ lấy user thuộc các sector mà người này quản lý
            ->when(
                !empty($this->sector),
                fn($query) => $query->whereIn('sector_user.sector_id', $this->sector)
            )
            ->when(
                !empty($this->course),
                fn($query) => $query->whereIn('course_user.course_id', $this->course)
            )
            ->when(
                !empty($this->search),
                fn($query) => $query->where(function ($q) {
                    $q->whereRaw("CONCAT(users.name, ' ', users.lastName) LIKE ?", ['%' . $this->search . '%'])
                        ->orWhereRaw("CONCAT(users.lastName, ' ', users.name) LIKE ?", ['%' . $this->search . '%'])
                        ->orWhereRaw("CONCAT(users.account_code, ' ', users.account_code) LIKE ?", ['%' . $this->search . '%']);
                })
            )
            ->orderBy('courses.ordering', 'asc')
            ->orderBy('roles.ordering', 'asc')
            ->orderBy('sectors.ordering', 'asc')
            ->orderBy('users.name', 'asc')
            ->distinct()
            ->paginate(15);

        return $listChildren;
    }

    public function getManagerData($user)
    {
        $roleName = $user->roles->first()?->name ?? '';

        $sectorMap = [
            'Trưởng Ngành Thiếu' => 'Thiếu%',
            'Phó Ngành Thiếu' => 'Thiếu%',
            'Trưởng Ngành Tiền Ấu' => 'Tiền%',
            'Phó Ngành Tiền Ấu' => 'Tiền%',
            'Trưởng Ngành Ấu' => 'Ấu%',
            'Phó Ngành Ấu' => 'Ấu%',
            'Trưởng Ngành Nghĩa' => 'Nghĩa%',
            'Phó Ngành Nghĩa' => 'Nghĩa%',
        ];

        $this->managerSector = collect();

        if (in_array($roleName, ['Xứ Đoàn Trưởng', 'Xứ Đoàn Phó', 'Admin', 'Cha Tuyên Úy'])) {
            $this->managerSector = Sector::orderBy('ordering')->get();
        } elseif (isset($sectorMap[$roleName])) {
            $this->managerSector = Sector::where('name', 'LIKE', $sectorMap[$roleName])
                ->orderBy('ordering')
                ->get();
        }

        return [
            'managerSector' => $this->managerSector,
            'roleName' => $roleName,
        ];
    }

    public function screenShot($id)
    {
        $child = User::findOrFail($id);
        $this->child_picture_card = $child->picture;
        $this->child_full_name_card = $child->SimpleName;
        $this->child_holy_name_card = $child->holyName;
        $this->child_token_card = $child->token;

        $this->child_position_card = mb_strtoupper($child->roles->first()->name ?? '', 'UTF-8');


        $child->update([
            'reissue_count' => $child->reissue_count + 1,
        ]);

        ActivityLogService::log(
            'Xuất ảnh thẻ',
            'Xuất ảnh thẻ thiếu nhi',
            $child->id,
            $child->SimpleName,
        );

        $name = pathinfo($child->getRawOriginal('picture'), PATHINFO_FILENAME) . ' - ' . Str::upper($child->FullName);

        $this->dispatch('showChildCard', ['name' => $name, 'times' => $child->reissue_count]);
    }

    public function updatedSearch()
    {
        $this->search = $this->search;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.personnel.children', [
            'listChildren' => $this->getListChildren(),
            'listCourses' => Course::all(),
            'listSectors' => $this->managerSector,
        ]);
    }
}
