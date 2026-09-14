<?php

namespace App\Livewire\Personnel;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\Sector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\ActivityLogService;
use Livewire\WithPagination;

class Scouters extends Component
{
    use WithPagination;
    public $isUpdateScouter = false;
    public $roleModal = [];
    public $sectorModal = [];
    public $search;
    public $role, $sector;
    public $scouter_id_avatarModal, $scouter_id, $scouter_email, $scouter_picture, $scouter_id_avatar, $scouter_full_name, $scouter_last_name, $scouter_name, $scouter_holy_name, $scouter_token, $scouter_birthday, $scouter_account_code, $scouter_phone, $scouter_address;
    public $scouter_father_name, $scouter_father_phone, $scouter_mother_name, $scouter_mother_phone, $scouter_godParent_name;
    public $ngay_rua_toi, $linh_muc_rua_toi, $ngay_xung_toi, $ngay_them_suc, $giam_muc_them_suc, $ngay_bao_dong, $trang_thai_ton_giao;
    public $ngay_doi_truong, $ngay_du_truong, $ngay_huynh_truong, $ngay_huynh_truong2, $ngay_huynh_truong3;

    public $scouter_picture_card, $scouter_full_name_card, $scouter_holy_name_card, $scouter_token_card, $scouter_position_card;

    protected $listeners = [
        'submitScouterFormModal',
        'deleteScouterAction',
        'confirmResetPasswordScouterAction',
        'refreshScouterList' => '$refresh',
        'chooseDataSort'
    ];

    public function chooseDataSort($selectedRoles, $selectedSectors)
    {
        $this->resetPage();
        $this->sector = $selectedSectors;
        $this->role = $selectedRoles;
    }

    public function submitScouterFormModal($roleModal, $sectorModal, $trang_thai_ton_giao)
    {
        $formattedFullName = trim(preg_replace('/\s+/', ' ', ucwords(strtolower($this->scouter_full_name))));
        $formattedHollyName = trim(preg_replace('/\s+/', ' ', ucwords(strtolower($this->scouter_holy_name))));
        $nameParts = explode(" ", $formattedFullName);
        $this->scouter_last_name = implode(" ", array_slice($nameParts, 0, -1));
        $this->scouter_name = end($nameParts);

        $existingUser = User::where('holyName', $this->scouter_holy_name)
            ->where('lastName', $this->scouter_last_name)
            ->where('name', $this->scouter_name)
            ->where('birthday', $this->scouter_birthday)
            ->first();

        if ($existingUser && $this->isUpdateScouter === false) {
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Người dùng này đã tồn tại. ( Mã tài khoản ' . $existingUser->account_code . ' )']);
            return;
        }

        $this->roleModal = $roleModal;
        $roleName = Role::where('id', $roleModal)->pluck('name')->first();

        if (in_array($roleName, ['Huynh Trưởng', 'Dự Trưởng', 'Đội Trưởng'])) {
            $this->sectorModal = $sectorModal;
        } else {
            $this->sectorModal = [];
        }

        $this->trang_thai_ton_giao = $trang_thai_ton_giao;

        $this->validate(
            [
                'scouter_birthday' => 'required|date',
                'scouter_holy_name' => 'required|string|max:100',
                'scouter_full_name' => 'required|string|max:255',
                'scouter_phone' => ['nullable', 'regex:/^(0[3,5,7,8,9])[0-9]{8}$/'],
                'scouter_address' => 'required|string|max:255',
                //Phụ Huynh
                'scouter_father_name' => 'nullable|string|max:255',
                'scouter_father_phone' => ['nullable', 'regex:/^(0[3,5,7,8,9])[0-9]{8}$/'],
                'scouter_mother_name' => 'nullable|string|max:255',
                'scouter_mother_phone' => ['nullable', 'regex:/^(0[3,5,7,8,9])[0-9]{8}$/'],
                'scouter_godParent_name' => 'nullable|string|max:255',
                //Thông tin khác
                'ngay_rua_toi' => 'required|date',
                'linh_muc_rua_toi' => 'required|string|max:255',
                'ngay_xung_toi' => 'nullable|date',
                'ngay_them_suc' => 'nullable|date',
                'giam_muc_them_suc' => 'nullable|string|max:255',
                'ngay_bao_dong' => 'nullable|date',
                //Hành trình dấn thân
                'ngay_doi_truong' => 'nullable|date',
                'ngay_du_truong' => 'nullable|date',
                'ngay_huynh_truong' => 'nullable|date',
                'ngay_huynh_truong2' => 'nullable|date',
                'ngay_huynh_truong3' => 'nullable|date',
            ],
            [
                'scouter_birthday.required' => 'Ngày sinh không được để trống',
                'scouter_birthday.date' => 'Ngày sinh không đúng định dạng',
                'scouter_holy_name.required' => 'Tên Thánh không được để trống',
                'scouter_holy_name.string' => 'Tên Thánh không đúng định dạng',
                'scouter_holy_name.max' => 'Tên Thánh không được quá 100 ký tự',
                'scouter_full_name.required' => 'Họ và tên không được để trống',
                'scouter_full_name.string' => 'Họ và tên không đúng định dạng',
                'scouter_full_name.max' => 'Họ và tên không được quá 255 ký tự',
                'scouter_phone.regex' => 'Số điện thoại không đúng định dạng',
                'scouter_address.required' => 'Địa chỉ không được để trống',
                'scouter_address.string' => 'Địa chỉ không đúng định dạng',
                'scouter_address.max' => 'Địa chỉ không được quá 255 ký tự',
                //Phụ Huynh
                'scouter_father_name.string' => 'Tên phụ huynh không đúng định dạng',
                'scouter_father_name.max' => 'Tên phụ huynh không được quá 255 ký tự',
                'scouter_father_phone.regex' => 'Số điện thoại phụ huynh không đúng định dạng',
                'scouter_mother_name.string' => 'Tên phụ huynh không đúng định dạng',
                'scouter_mother_name.max' => 'Tên phụ huynh không được quá 255 ký tự',
                'scouter_mother_phone.regex' => 'Số điện thoại phụ huynh không đúng định dạng',
                'scouter_godParent_name.string' => 'Tên người đỡ đầu không đúng định dạng',
                'scouter_godParent_name.max' => 'Tên người đỡ đầu không được quá 255 ký tự',
                //Thông tin khác
                'ngay_rua_toi.required' => 'Ngày rửa tội không được để trống',
                'ngay_rua_toi.date' => 'Ngày rửa tội không đúng định dạng',
                'linh_muc_rua_toi.required' => 'Tên linh mục rửa tội không được để trống',
                'linh_muc_rua_toi.string' => 'Tên linh mục rửa tội không đúng định dạng',
                'linh_muc_rua_toi.max' => 'Tên linh mục rửa tội không được quá 255 ký tự',
                'ngay_xung_toi.date' => 'Ngày xưng tội không đúng định dạng',
                'ngay_them_suc.date' => 'Ngày thêm sức không đúng định dạng',
                'giam_muc_them_suc.string' => 'Tên giám mục thêm sức không đúng định dạng',
                'giam_muc_them_suc.max' => 'Tên giám mục thêm sức không được quá 255 ký tự',
                'ngay_bao_dong.date' => 'Ngày báo danh không đúng định dạng',
                //Hành trình dấn thân
                'ngay_doi_truong.date' => 'Ngày đội trưởng không đúng định dạng',
                'ngay_du_truong.date' => 'Ngày dự trưởng không đúng định dạng',
                'ngay_huynh_truong.date' => 'Ngày huynh trưởng không đúng định dạng',
                'ngay_huynh_truong2.date' => 'Ngày huynh trưởng không đúng định dạng',
                'ngay_huynh_truong3.date' => 'Ngày huynh trưởng không đúng định dạng',
            ]
        );
        if ($this->isUpdateScouter) {
            $this->updateScouter();
        } else {
            $this->createScouter();
        }
    }

    public function addScouter()
    {
        $this->isUpdateScouter = false;
        $this->showScouterModal();
        $this->resetModal();
    }

    public function createScouter()
    {
        DB::beginTransaction();
        try {
            $scouter = new User();
            $scouter->birthday = $this->scouter_birthday; //
            $scouter->account_code = $this->scouter_account_code; //
            $scouter->holyName = $this->scouter_holy_name; //
            $scouter->lastName = $this->scouter_last_name; //
            $scouter->name = $this->scouter_name; //
            $scouter->phone = $this->scouter_phone; //
            $scouter->address = $this->scouter_address; //
            $scouter->token = Str::random(64); //
            $scouter->password = Hash::make($scouter->account_code); //
            $scouter->save();
            $scouter->roles()->sync($this->roleModal);
            $scouter->sectors()->sync($this->sectorModal);

            //Phụ Huynh

            if (!empty($this->scouter_father_name) || !empty($this->scouter_father_phone) || !empty($this->scouter_mother_name) || !empty($this->scouter_mother_phone)) {
                $scouter->studentParent()->updateOrCreate(
                    ['user_id' => $scouter->id],
                    [
                        'nameFather'   => $this->scouter_father_name,
                        'phoneFather'  => $this->scouter_father_phone,
                        'nameMother'   => $this->scouter_mother_name,
                        'phoneMother'  => $this->scouter_mother_phone,
                        'godParent'    => $this->scouter_godParent_name,
                    ]
                );
            }

            //Thông tin khác

            $scouter->religiousProfile()->updateOrCreate(
                ['user_id' => $scouter->id],
                [
                    'ngay_rua_toi'        => $this->ngay_rua_toi,
                    'linh_muc_rua_toi'    => $this->linh_muc_rua_toi,
                    'ngay_xung_toi'       => $this->ngay_xung_toi,
                    'ngay_them_suc'       => $this->ngay_them_suc,
                    'giam_muc_them_suc'   => $this->giam_muc_them_suc,
                    'ngay_bao_dong'       => $this->ngay_bao_dong,
                    'trang_thai_ton_giao' => $this->trang_thai_ton_giao,
                ]
            );

            //Hành trình dấn thân
            if (!empty($this->ngay_doi_truong) || !empty($this->ngay_du_truong) || !empty($this->ngay_huynh_truong) || !empty($this->ngay_huynh_truong2) || !empty($this->ngay_huynh_truong3)) {
                $scouter->journeysOfVocation()->updateOrCreate(
                    ['user_id' => $scouter->id],
                    [
                        'ngay_doi_truong'     => $this->ngay_doi_truong,
                        'ngay_du_truong'      => $this->ngay_du_truong,
                        'ngay_huynh_truong'   => $this->ngay_huynh_truong,
                        'ngay_huynh_truong2'  => $this->ngay_huynh_truong2,
                        'ngay_huynh_truong3'  => $this->ngay_huynh_truong3,
                    ]
                );
            }

            $roleOfUser = Role::where('id', $this->roleModal)->first();

            if (!$roleOfUser) {
                $positionOfUser = '';
            } else {
                $positionOfUser = $roleOfUser->name;
            }

            DB::commit();

            ActivityLogService::log(
                'Tạo tài khoản',
                'Thêm Huynh Trưởng',
                $scouter->id,
                $scouter->SimpleName,
            );

            $this->hideScouterModal();
            $this->updateAvatar($scouter->id);
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Thêm mới ' . $positionOfUser . ' ' . $scouter->Simplename . ' thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.' . $e->getMessage()]);
        }
    }

    public function editScouter($id)
    {
        $this->resetErrorBag();
        $this->isUpdateScouter = true;

        $scouter = User::with(['roles', 'sectors', 'studentParent', 'religiousProfile', 'journeysOfVocation'])->find($id);
        $this->scouter_id = $scouter->id;
        $this->scouter_holy_name = $scouter->holyName;
        $this->scouter_full_name = $scouter->Simplename;
        $this->scouter_account_code = $scouter->account_code;
        $this->scouter_phone = $scouter->phone;
        $this->scouter_birthday = Carbon::createFromFormat('d/m/Y', $scouter->birthday)->format('Y-m-d');
        $this->scouter_address = $scouter->address;

        $this->sectorModal = $scouter->sectors->pluck('id')->toArray();

        $this->roleModal = $scouter->roles->pluck('id')->toArray();

        //Phụ Huynh
        $this->scouter_father_name = $scouter->studentParent->nameFather ?? '';
        $this->scouter_father_phone = $scouter->studentParent->phoneFather ?? '';
        $this->scouter_mother_name = $scouter->studentParent->nameMother ?? '';
        $this->scouter_mother_phone = $scouter->studentParent->phoneMother ?? '';
        $this->scouter_godParent_name = $scouter->studentParent->godParent ?? '';
        //Thông tin khác
        $this->ngay_rua_toi = optional($scouter->religiousProfile)->ngay_rua_toi
            ? Carbon::parse($scouter->religiousProfile->ngay_rua_toi)->format('Y-m-d')
            : null;

        $this->linh_muc_rua_toi = optional($scouter->religiousProfile)->linh_muc_rua_toi ?? '';

        $this->ngay_xung_toi = optional($scouter->religiousProfile)->ngay_xung_toi
            ? Carbon::parse($scouter->religiousProfile->ngay_xung_toi)->format('Y-m-d')
            : null;

        $this->ngay_them_suc = optional($scouter->religiousProfile)->ngay_them_suc
            ? Carbon::parse($scouter->religiousProfile->ngay_them_suc)->format('Y-m-d')
            : null;

        $this->giam_muc_them_suc = optional($scouter->religiousProfile)->giam_muc_them_suc ?? '';

        $this->ngay_bao_dong = optional($scouter->religiousProfile)->ngay_bao_dong
            ? Carbon::parse($scouter->religiousProfile->ngay_bao_dong)->format('Y-m-d')
            : null;

        $this->trang_thai_ton_giao = optional($scouter->religiousProfile)->trang_thai_ton_giao ?? '';
        //Hành trình dấn thân
        $this->ngay_doi_truong = optional($scouter->journeysOfVocation)->ngay_doi_truong
            ? Carbon::parse($scouter->journeysOfVocation->ngay_doi_truong)->format('Y-m-d')
            : null;
        $this->ngay_du_truong = optional($scouter->journeysOfVocation)->ngay_du_truong
            ? Carbon::parse($scouter->journeysOfVocation->ngay_du_truong)->format('Y-m-d')
            : null;
        $this->ngay_huynh_truong = optional($scouter->journeysOfVocation)->ngay_huynh_truong
            ? Carbon::parse($scouter->journeysOfVocation->ngay_huynh_truong)->format('Y-m-d')
            : null;
        $this->ngay_huynh_truong2 = optional($scouter->journeysOfVocation)->ngay_huynh_truong2
            ? Carbon::parse($scouter->journeysOfVocation->ngay_huynh_truong2)->format('Y-m-d')
            : null;
        $this->ngay_huynh_truong3 = optional($scouter->journeysOfVocation)->ngay_huynh_truong3
            ? Carbon::parse($scouter->journeysOfVocation->ngay_huynh_truong3)->format('Y-m-d')
            : null;

        $this->dispatch('showScouterModal');
    }

    public function updateScouter()
    {
        DB::beginTransaction();
        try {
            $scouter = User::findOrFail($this->scouter_id);
            $scouter->holyName = $this->scouter_holy_name; //
            $scouter->lastName = $this->scouter_last_name; //
            $scouter->name = $this->scouter_name; //
            $scouter->phone = $this->scouter_phone; //
            $scouter->address = $this->scouter_address; //
            $scouter->save();

            $scouter->roles()->sync($this->roleModal);
            $scouter->sectors()->sync($this->sectorModal);
            //Phụ Huynh
            if (!empty($this->scouter_father_name) || ($this->scouter_father_phone) || !empty($this->scouter_mother_name) || !empty($this->scouter_mother_phone)) {
                $scouter->studentParent()->updateOrCreate(
                    ['user_id' => $scouter->id],
                    [
                        'nameFather'   => $this->scouter_father_name,
                        'phoneFather'  => $this->scouter_father_phone,
                        'nameMother'   => $this->scouter_mother_name,
                        'phoneMother'  => $this->scouter_mother_phone,
                        'godParent'    => $this->scouter_godParent_name,
                    ]
                );
            }
            //Thông tin khác

            $religiousProfileData = [
                'ngay_rua_toi'        => $this->ngay_rua_toi,
                'linh_muc_rua_toi'    => $this->linh_muc_rua_toi,
                'ngay_xung_toi'       => $this->ngay_xung_toi,
                'ngay_them_suc'       => $this->ngay_them_suc,
                'giam_muc_them_suc'   => $this->giam_muc_them_suc,
                'ngay_bao_dong'       => $this->ngay_bao_dong,
                'trang_thai_ton_giao' => $this->trang_thai_ton_giao,
            ];
            //Hành trình dấn thân
            if (!empty($this->ngay_doi_truong) || !empty($this->ngay_du_truong) || !empty($this->ngay_huynh_truong) || !empty($this->ngay_huynh_truong2) || !empty($this->ngay_huynh_truong3)) {
                $scouter->journeysOfVocation()->updateOrCreate(
                    ['user_id' => $scouter->id],
                    [
                        'ngay_doi_truong'     => $this->ngay_doi_truong,
                        'ngay_du_truong'      => $this->ngay_du_truong,
                        'ngay_huynh_truong'   => $this->ngay_huynh_truong,
                        'ngay_huynh_truong2'  => $this->ngay_huynh_truong2,
                        'ngay_huynh_truong3'  => $this->ngay_huynh_truong3,
                    ]
                );
            }

            if ($this->trang_thai_ton_giao === 'Đã nghỉ') {
                $religiousProfileData['ngay_bo_hoc'] = now();
            } else {
                $religiousProfileData['ngay_bo_hoc'] = null;
            }

            $scouter->religiousProfile()->updateOrCreate(['user_id' => $scouter->id], $religiousProfileData);

            $roleOfUser = Role::where('id', $this->roleModal)->first();

            if (!$roleOfUser) {
                $positionOfUser = '';
            } else {
                $positionOfUser = $roleOfUser->name;
            }

            DB::commit();

            ActivityLogService::log(
                'Cập nhật tài khoản',
                'Cập nhật Huynh Trưởng',
                $scouter->id,
                $scouter->SimpleName,
            );

            $this->hideScouterModal();
            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Cập nhật ' . $positionOfUser . ' ' . $scouter->Simplename . ' thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.' . $e->getMessage()]);
        }
    }

    public function deleteScouter($id)
    {
        $scouter = User::findOrFail($id);

        $roleOfUser = $scouter->roles()->first();

        if (!$roleOfUser) {
            $positionOfUser = '';
        } else {
            $positionOfUser = $roleOfUser->name;
        }

        $this->dispatch('deleteScouter', ['id' => $id, 'name' => $scouter->Simplename, 'position' => $positionOfUser]);
    }

    public function deleteScouterAction($id)
    {
        DB::beginTransaction();
        try {
            $scouter = User::findOrFail($id);
            $roleOfUser = $scouter->roles()->first();
            if (!$roleOfUser) {
                $positionOfUser = '';
            } else {
                $positionOfUser = $roleOfUser->name;
            }
            $scouter->roles()->detach();
            $scouter->sectors()->detach();
            $scouter->studentParent()->delete();
            $scouter->religiousProfile()->delete();
            $scouter->journeysOfVocation()->delete();
            $scouter->delete();
            DB::commit();

            ActivityLogService::log(
                'Xóa tài khoản',
                'Xóa Huynh Trưởng',
                $scouter->id,
                $scouter->SimpleName,
            );

            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Xóa ' . $positionOfUser . ' ' . $scouter->Simplename . ' thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.' . $e->getMessage()]);
        }
    }

    public function showScouterModal()
    {
        $this->resetErrorBag();
        $this->dispatch('showScouterModal');
    }

    public function hideScouterModal()
    {
        $this->dispatch('hideScouterModal');
        $this->resetModal();
    }

    public function hideScouterAvatar()
    {
        $this->dispatch('hideScouterAvatar');
        $this->isUpdateScouter = false;
    }

    public function resetModal()
    {
        $this->scouter_id = null;
        $this->scouter_holy_name = null;
        $this->scouter_full_name = null;
        $this->scouter_last_name = null;
        $this->scouter_name = null;
        $this->scouter_account_code = null;
        $this->scouter_phone = null;
        $this->scouter_picture = null;
        $this->scouter_birthday = null;
        $this->scouter_address = null;

        $this->roleModal = [];
        $this->sectorModal = [];

        //Phụ Huynh
        $this->scouter_father_name = null;
        $this->scouter_father_phone = null;
        $this->scouter_mother_name = null;
        $this->scouter_mother_phone = null;
        $this->scouter_godParent_name = null;

        //Thông tin khác
        $this->ngay_rua_toi = null;
        $this->linh_muc_rua_toi = null;
        $this->ngay_xung_toi = null;
        $this->ngay_them_suc = null;
        $this->giam_muc_them_suc = null;
        $this->ngay_bao_dong = null;

        //Hành trình dấn thân
        $this->ngay_doi_truong = null;
        $this->ngay_du_truong = null;
        $this->ngay_huynh_truong = null;
        $this->ngay_huynh_truong2 = null;
        $this->ngay_huynh_truong3 = null;
    }

    public function generateAccountCode()
    {
        if (!$this->isUpdateScouter) {
            $date = \Carbon\Carbon::parse($this->scouter_birthday);
            $formattedDate = $date->format('dmy');
            $randomNumbers = rand(10, 99);

            $accountCode = 'MV' . $formattedDate . $randomNumbers;
            while (User::where('account_code', $accountCode)->exists()) {
                $randomNumbers = rand(01, 99);
                $accountCode = 'MV' . $formattedDate . $randomNumbers;
            }

            $this->scouter_account_code = $accountCode;

            $formattedFullName = trim(preg_replace('/\s+/', ' ', ucwords(strtolower($this->scouter_full_name))));
            $formattedHollyName = trim(preg_replace('/\s+/', ' ', ucwords(strtolower($this->scouter_holy_name))));
            $nameParts = explode(" ", $formattedFullName);
            $this->scouter_last_name = implode(" ", array_slice($nameParts, 0, -1));
            $this->scouter_name = end($nameParts);

            $existingUser = User::where('holyName', $this->scouter_holy_name)
                ->where('lastName', $this->scouter_last_name)
                ->where('name', $this->scouter_name)
                ->where('birthday', $this->scouter_birthday)
                ->first();

            if ($existingUser && $this->isUpdateScouter === false) {
                $this->addError('scouter_full_name', 'Người dùng này đã tồn tại. ( Mã tài khoản ' . $existingUser->account_code . ' )');
            } else {
                $this->resetErrorBag('scouter_full_name');
            }
        }
    }

    public function updateAvatar($id)
    {
        $Scouter = User::find($id);
        $this->scouter_picture = $Scouter->picture;
        $this->scouter_holy_name = $Scouter->holyName;
        $this->scouter_full_name = $Scouter->Simplename;
        $this->scouter_token = $Scouter->token;
        $this->scouter_id_avatarModal = $Scouter->id;
        $this->dispatch('showScouterAvatar', ['id' => $Scouter->id]);
    }

    public function resetPasswordScouter($id)
    {
        $scouter = User::findOrFail($id);
        $roleOfUser = $scouter->roles()->first();
        if (!$roleOfUser) {
            $positionOfUser = '';
        } else {
            $positionOfUser = $roleOfUser->name;
        }
        $this->dispatch('confirmResetPasswordScouter', [
            'id' => $scouter->id,
            'name' => $scouter->Simplename,
            'position' => $positionOfUser,
        ]);
    }

    public function confirmResetPasswordScouterAction($id)
    {
        DB::beginTransaction();
        try {
            $scouter = User::findOrFail($id);
            $scouter->status = 'active';
            $scouter->password = Hash::make($scouter->account_code);
            $scouter->save();
            DB::commit();

            ActivityLogService::log(
                'Đặt lại mật khẩu',
                'Đặt lại mật khẩu Huynh Trưởng',
                $scouter->id,
                $scouter->SimpleName,
            );

            $roleOfUser = $scouter->roles()->first();

            if (!$roleOfUser) {
                $positionOfUser = '';
            } else {
                $positionOfUser = $roleOfUser->name;
            }

            $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Đặt lại mật khẩu cho ' . $positionOfUser . ' ' . $scouter->Simplename . ' thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showToastr', ['type' => 'error', 'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.' . $e->getMessage()]);
        }
    }

    public function getScouters()
    {
        $listScouters = User::with(['roles', 'sectors'])
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->leftJoin('sector_user', 'users.id', '=', 'sector_user.user_id')
            ->leftJoin('sectors', 'sector_user.sector_id', '=', 'sectors.id')
            ->whereNotIn('roles.name', ['Thiếu Nhi', 'admin'])
            ->select('users.*', 'roles.ordering as role_ordering', 'sectors.ordering as sector_ordering')
            ->when(!empty($this->search), function ($query) {
                $query->where(function ($q) {
                    $q->whereRaw("CONCAT(users.name, ' ', users.lastName) LIKE ?", ['%' . $this->search . '%'])
                        ->orWhereRaw("CONCAT(users.lastName, ' ', users.name) LIKE ?", ['%' . $this->search . '%']);
                });
            })
            ->when(!empty($this->sector), fn($query) => $query->whereIn('sector_user.sector_id', $this->sector))
            ->when(!empty($this->role), fn($query) => $query->whereIn('roles.id', $this->role))
            ->orderBy('role_ordering')
            ->orderBy('sector_ordering')
            ->orderBy('users.name')
            ->distinct()
            ->paginate(15);


        return $listScouters;
    }

    public function updatedSearch()
    {
        $this->search = $this->search;
        $this->resetPage();
    }

    public function screenShot($id)
    {
        $scouter = User::findOrFail($id);
        $this->scouter_picture_card = $scouter->picture;
        $this->scouter_full_name_card = $scouter->Simplename;
        $this->scouter_holy_name_card = $scouter->holyName;
        $this->scouter_token_card = $scouter->token;

        $this->scouter_position_card = mb_strtoupper($scouter->roles->first()->name ?? '', 'UTF-8');


        $scouter->update([
            'reissue_count' => $scouter->reissue_count + 1,
        ]);


        ActivityLogService::log(
            'Xuất ảnh thẻ',
            'Xuất ảnh thẻ Huynh Trưởng',
            $scouter->id,
            $scouter->SimpleName,
        );

        $name = pathinfo($scouter->getRawOriginal('picture'), PATHINFO_FILENAME) . ' - ' . Str::upper($scouter->FullName);

        $this->dispatch('showScouterCard', ['name' => $name, 'times' => $scouter->reissue_count]);
    }
    public function render()
    {
        return view('livewire.personnel.scouters', [
            'listScouters' => $this->getScouters(),
            'listSectors' => Sector::all(),
            'listRoles' => Role::whereNotIn('name', ['Thiếu Nhi', 'admin'])->get(),
        ]);
    }
}
