<div>
    {{-- Bảng hiện danh sách thiếu nhi --}}
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-2">
                    <div class="mb-2 mb-md-0">
                        <div class="h4 text-blue mb-0">
                            Thiếu Nhi - Quản lý Thiếu Nhi trong xứ Đoàn
                        </div>
                        <!-- spell-check: enable -->
                    </div>
                    <div class="d-flex flex-wrap justify-content-md-end">
                        <button type="button" wire:click="exportChildrenInfo" wire:loading.attr="disabled"
                            wire:target="exportChildrenInfo" class="btn btn-success btn-sm mb-2 mr-2">
                            <span wire:loading.remove wire:target="exportChildrenInfo">Xuất thông tin</span>
                            <span wire:loading wire:target="exportChildrenInfo">Đang xuất...</span>
                        </button>
                        <button type="button" wire:click="exportChildrenScores" wire:loading.attr="disabled"
                            wire:target="exportChildrenScores" class="btn btn-info btn-sm mb-2 mr-2">
                            <span wire:loading.remove wire:target="exportChildrenScores">Xuất điểm</span>
                            <span wire:loading wire:target="exportChildrenScores">Đang xuất...</span>
                        </button>
                        @can('create', App\Models\User::class)
                            <a href="javascript:;" wire:click="addChild()" class="btn btn-primary btn-sm mb-2">
                                Thêm Thiếu Nhi
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4 form-group">
                        <input type="text" class="form-control search-input outline-primary"
                            placeholder="Tìm tên/ Mã tài khoản" id="search" wire:model.live="search">
                    </div>

                    <div wire:ignore class="col-md-4 form-group">
                        <select class="selectpicker form-control" id="course" data-size="5"
                            data-style="btn-outline-secondary" wire:model="course" data-actions-box="true"
                            data-live-search-placeholder="Tìm kiếm lớp GL..."
                            data-none-results-text="Không tìm thấy lớp GL" data-none-selected-text="Chọn cấp"
                            data-select-all-text="Chọn tất cả" data-deselect-all-text="Bỏ tất cả" multiple>
                            @forelse ($listCourses as $listCourse)
                                <option value="{{ $listCourse->id }}">{{ $listCourse->name }}</option>
                            @empty
                                <option value="">Không có lớp giáo lý</option>
                            @endforelse
                        </select>
                    </div>

                    <!-- Dropdown for selecting Sector -->
                    <div wire:ignore class="col-md-4 form-group">
                        <select class="selectpicker form-control" id="sector" data-size="5"
                            data-style="btn-outline-secondary" wire:model="sector" data-actions-box="true"
                            data-live-search-placeholder="Tìm kiếm cấp..." data-none-results-text="Không tìm thấy cấp"
                            data-none-selected-text="Chọn cấp" data-select-all-text="Chọn tất cả"
                            data-deselect-all-text="Bỏ tất cả" multiple>
                            @forelse ($listSectors as $listSector)
                                <option value="{{ $listSector->id }}">{{ $listSector->name }}</option>
                            @empty
                                <option value="">Không có cấp</option>
                            @endforelse
                        </select>
                    </div>
                </div>

                {{-- Dạng bảng: chỉ hiện từ màn hình lớn trở lên --}}
                <div class="table-responsive mt-1 d-none d-lg-block" style="min-height: 180px">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Mã tài khoản</th>
                                <th class="text-center">Hình đại diện</th>
                                <th class="text-center">Tên thánh</th>
                                <th class="text-center">Họ và tên</th>
                                <th class="text-center">Lớp giáo lý</th>
                                <th class="text-center">Ngành</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($listChildren as $child)
                                <tr wire:key="child-{{ $child->id }}">
                                    <td class="text-center align-middle">
                                        {{ ($listChildren->currentPage() - 1) * $listChildren->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="text-center align-middle">{{ $child->account_code }}</td>
                                    <td class="text-center align-middle">
                                        <img src="{{ $child->picture }}" alt="Ảnh đại diện của {{ $child->SimpleName }}"
                                            class="img-fluid rounded-circle"
                                            style="max-width: 50px; max-height: 50px; object-fit: cover;">
                                    </td>
                                    <td class="text-center align-middle">{{ $child->holyName }}</td>
                                    <td class="text-center align-middle">{{ $child->SimpleName }}</td>
                                    <td class="text-center align-middle">
                                        {{ $child->courses->pluck('name')->implode(', ') }}</td>
                                    <td class="text-center align-middle">
                                        {{ $child->sectors->pluck('name')->implode(', ') }}</td>

                                    <td class="text-center align-middle">
                                        @can('update', $child)
                                            <div class="dropdown">
                                                <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                    href="#" role="button" data-toggle="dropdown">
                                                    <i class="dw dw-more"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                    <a class="dropdown-item" href="javascript:;"
                                                        wire:click="editChild({{ $child->id }})">
                                                        <i class="dw dw-edit2"></i> Chỉnh sửa
                                                    </a>
                                                    <a class="dropdown-item" href="javascript:;"
                                                        wire:click="resetPasswordChild({{ $child->id }})">
                                                        <i class="fa fa-repeat"></i> Đặt lại Password
                                                    </a>
                                                    <a class="dropdown-item" href="javascript:;"
                                                        wire:click="updateAvatar({{ $child->id }})">
                                                        <i class="bi bi-file-image"></i> Cài đặt Avatar
                                                    </a>

                                                    @if ($child->hasCustomPicture())
                                                        @can('exportQr', $child)
                                                            <a class="dropdown-item" href="javascript:;"
                                                                wire:click="screenShot({{ $child->id }})">
                                                                <i class="bi bi-person-badge"></i> Xuất QR
                                                            </a>
                                                        @endcan
                                                    @endif
                                                    @can('delete', $child)
                                                        <a class="dropdown-item text-danger" href="javascript:;"
                                                            wire:click="deleteChild({{ $child->id }})">
                                                            <i class="dw dw-delete-3"></i> Xóa
                                                        </a>
                                                    @endCan
                                                </div>
                                            </div>
                                        @endCan
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Dạng thẻ: hiện trên điện thoại / máy tính bảng --}}
                <div class="d-lg-none mt-1">
                    @forelse ($listChildren as $child)
                        <div class="card mb-2" wire:key="child-card-{{ $child->id }}">
                            <div class="card-body p-3">
                                <div class="d-flex">
                                    <img src="{{ $child->picture }}" alt="Ảnh đại diện của {{ $child->SimpleName }}"
                                        class="rounded-circle mr-3"
                                        style="width: 48px; height: 48px; object-fit: cover;">

                                    <div class="flex-grow-1 text-break">
                                        <div class="font-weight-bold">
                                            <span class="text-muted">
                                                {{ ($listChildren->currentPage() - 1) * $listChildren->perPage() + $loop->iteration }}.
                                            </span>
                                            {{ trim(($child->holyName ?? '') . ' ' . $child->SimpleName) }}
                                        </div>
                                        <div class="small text-muted">{{ $child->account_code }}</div>
                                    </div>

                                    @can('update', $child)
                                        <div class="dropdown ml-2">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editChild({{ $child->id }})">
                                                    <i class="dw dw-edit2"></i> Chỉnh sửa
                                                </a>
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="resetPasswordChild({{ $child->id }})">
                                                    <i class="fa fa-repeat"></i> Đặt lại Password
                                                </a>
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="updateAvatar({{ $child->id }})">
                                                    <i class="bi bi-file-image"></i> Cài đặt Avatar
                                                </a>

                                                @if ($child->hasCustomPicture())
                                                    @can('exportQr', $child)
                                                        <a class="dropdown-item" href="javascript:;"
                                                            wire:click="screenShot({{ $child->id }})">
                                                            <i class="bi bi-person-badge"></i> Xuất QR
                                                        </a>
                                                    @endcan
                                                @endif
                                                @can('delete', $child)
                                                    <a class="dropdown-item text-danger" href="javascript:;"
                                                        wire:click="deleteChild({{ $child->id }})">
                                                        <i class="dw dw-delete-3"></i> Xóa
                                                    </a>
                                                @endCan
                                            </div>
                                        </div>
                                    @endCan
                                </div>

                                <div class="mt-2 small text-muted">
                                    <div>Lớp giáo lý: {{ $child->courses->pluck('name')->implode(', ') ?: '---' }}</div>
                                    <div>Ngành: {{ $child->sectors->pluck('name')->implode(', ') ?: '---' }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Không có dữ liệu</div>
                    @endforelse
                </div>

                <div class="d-block mt-1 text-center">
                    {{ $listChildren->links('livewire::bootstrap') }}
                </div>
            </div>
        </div>
    </div>
    {{-- Kết thúc Bảng hiện danh sách thiếu nhi --}}

    <!-- spell-check: disable -->
    <div wire:ignore.self class="modal fade " id="children_modal" tabindex="-1" aria-labelledby="myLargeModalLabel1"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateChild ? 'Cập nhật Thiếu Nhi' : 'Thêm Thiếu Nhi' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateChild)
                        <input type="hidden" wire:model="child_id">
                    @endif
                    <h5 class="h5"> 1. Thông tin cá nhân</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="child_birthday"><strong>Ngày sinh <span class="text-danger"> *</span>
                                        <small>(dd/mm/yyyy)</small></strong>
                                </label>
                                <input type="date" class="form-control" id="child_birthday"
                                    wire:model="child_birthday" wire:change="generateAccountCode" autocomplete="off"
                                    {{ $isUpdateChild ? 'disabled' : '' }}>
                                @error('child_birthday')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="child_account_code" class="d-block"><strong>Mã tài
                                        khoản</strong></label>
                                <input type="text" class="form-control text-center font-weight-bold"
                                    id="child_account_code" wire:model="child_account_code" autocomplete="off" disabled
                                    style="font-size: 19px;">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="child_holy_name"><strong>Tên Thánh <span class="text-danger">
                                            *</span></strong>
                                </label>
                                <input type="text" class="form-control" id="child_holy_name"
                                    wire:model="child_holy_name" wire:change="generateAccountCode"
                                    oninput="this.value = this.value.replace(/(^|\s)\S/g, char => char.toLocaleUpperCase())">
                                @error('child_holy_name')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="child_full_name"><strong>Họ và tên <span class="text-danger">
                                            *</span></strong>
                                </label>
                                <input type="text" class="form-control" id="child_full_name"
                                    wire:model="child_full_name" wire:change="generateAccountCode"
                                    oninput="this.value = this.value.replace(/(^|\s)\S/g, char => char.toLocaleUpperCase())">
                                @error('child_full_name')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="child_phone"><strong>Số điện thoại</strong></label>
                                <input type="tel" class="form-control" id="child_phone" wire:model="child_phone">
                                @error('child_phone')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="child_address"><strong>Địa chỉ <span class="text-danger">
                                            *</span></strong><small> (Giáo khu)</small>
                                </label>
                                <input type="text" class="form-control" id="child_address" wire:model="child_address"
                                    oninput="this.value = this.value.replace(/(^|\s)\S/g, char => char.toLocaleUpperCase())">
                                @error('child_address')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div wire:ignore class="form-group">
                                <label for="courseModal"><strong>Lớp giáo lý<span class="text-danger">
                                            *</span></strong>
                                </label>
                                <select class="selectpicker form-control" id="courseModal" data-size="5"
                                    data-style="btn-outline-primary" wire:model="courseModal" data-live-search="true">
                                    @forelse ($listCourses as $Course)
                                        <option value="{{ $Course->id }}">
                                            {{ $Course->name }}
                                        </option>
                                    @empty
                                        <option value="">Không có lớp giáo lý</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div wire:ignore class="form-group">
                                <label for="sectorModal"><strong>Ngành<span class="text-danger"> *</span></strong>
                                </label>
                                <select class="selectpicker form-control" id="sectorModal" data-size="5"
                                    data-style="btn-outline-primary" wire:model="sectorModal" data-live-search="true">
                                    @forelse ($listSectors as $Sector)
                                        <option value="{{ $Sector->id }}">
                                            {{ $Sector->name }}
                                        </option>
                                    @empty
                                        <option value="">Không có ngành</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                    </div>

                    <hr>
                    <h5 class="h5"> 2. Thông tin phụ huynh</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="child_father_name"><strong>Tên Thánh - Họ và tên cha </strong>
                                </label>
                                <input type="text" class="form-control" id="child_father_name"
                                    wire:model="child_father_name"
                                    oninput="this.value = this.value.replace(/(^|\s)\S/g, char => char.toLocaleUpperCase())">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="child_father_phone"><strong>Số điện thoại</strong>
                                </label>
                                <input type="tel" class="form-control" id="child_father_phone"
                                    wire:model="child_father_phone">
                                @error('child_father_phone')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="child_mother_name"><strong>Tên Thánh - Họ và tên mẹ </strong>
                                </label>
                                <input type="text" class="form-control" id="child_mother_name"
                                    wire:model="child_mother_name"
                                    oninput="this.value = this.value.replace(/(^|\s)\S/g, char => char.toLocaleUpperCase())">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="child_mother_phone"><strong>Số điện thoại</strong>
                                </label>
                                <input type="tel" class="form-control" id="child_mother_phone"
                                    wire:model="child_mother_phone">
                                @error('child_mother_phone')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="child_godParent_name"><strong>Tên Thánh - Họ và tên cha mẹ đỡ
                                        đầu </strong>
                                </label>
                                <input type="text" class="form-control" id="child_godParent_name"
                                    wire:model="child_godParent_name"
                                    oninput="this.value = this.value.replace(/(^|\s)\S/g, char => char.toLocaleUpperCase())">
                            </div>
                        </div>

                    </div>


                    <hr>
                    <h5 class="h5"> 3. Thông tin công giáo</h5>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ngay_rua_toi"><strong>Ngày Rửa Tội<span class="text-danger">
                                            *</span></strong>
                                </label>
                                <input type="date" class="form-control" id="ngay_rua_toi" wire:model="ngay_rua_toi">
                                @error('ngay_rua_toi')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="linh_muc_rua_toi"><strong>Linh mục Rửa Tội<span class="text-danger">
                                            *</span></strong>
                                </label>
                                <input type="text" class="form-control" id="linh_muc_rua_toi"
                                    wire:model="linh_muc_rua_toi"
                                    oninput="this.value = this.value.replace(/(^|\s)\S/g, char => char.toLocaleUpperCase())">
                                @error('linh_muc_rua_toi')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ngay_xung_toi"><strong>Ngày Xưng Tội<span class="text-danger">
                                            *</span></strong>
                                </label>
                                <input type="date" class="form-control" id="ngay_xung_toi" wire:model="ngay_xung_toi">
                                @error('ngay_xung_toi')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ngay_them_suc"><strong>Ngày Thêm Sức</strong>
                                </label>
                                <input type="date" class="form-control" id="ngay_them_suc"
                                    wire:model="ngay_them_suc">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="giam_muc_them_suc"><strong>Do ĐGM</strong>
                                </label>
                                <input type="text" class="form-control" id="giam_muc_them_suc"
                                    wire:model="giam_muc_them_suc"
                                    oninput="this.value = this.value.replace(/(^|\s)\S/g, char => char.toLocaleUpperCase())">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ngay_bao_dong"><strong>Ngày Bao Đồng</strong>
                                </label>
                                <input type="date" class="form-control" id="ngay_bao_dong"
                                    wire:model="ngay_bao_dong">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div wire:ignore class="form-group">
                                <label for="trang_thai"><strong>Trạng thái</strong>
                                </label>
                                <select class="selectpicker form-control" id="trang_thai" data-size="5"
                                    data-style="btn-outline-primary" wire:model="trang_thai" data-live-search="true">
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Ngưng hoạt động</option>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit"
                        class="btn btn-primary">{{ $isUpdateChild ? 'Lưu thay đổi' : 'Thêm mới' }}</button>
                </div>

            </form>
        </div>
    </div>
    {{-- Kết thúc Thêm Thiếu Nhi Modal --}}

    {{-- Hiện modal update Avatar --}}
    <div class="modal fade " id="children_avatar" tabindex="-1" aria-labelledby="myLargeModalLabel1"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Cập nhật hình đại diện
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="{{ $child_id_avatarModal }}" id="child_id">
                    <div class="profile-photo">
                        <a href="javascript:;"
                            onclick="event.preventDefault();document.getElementById('profilePictureFile').click();"
                            class="edit-avatar"><i class="fa fa-pencil"></i></a>
                        <img src="{{ $child_picture }}" alt="Avatar {{ $child_full_name }}"
                            class="avatar-photo img-fluid rounded-circle" id="profilePicturePreview">
                        <input type="file" name="profilePictureFile" id="profilePictureFile" class="d-none"
                            style="opacity: 0">
                    </div>
                    <h5 class="text-center h5 mb-0">{{ $child_holy_name }}</h5>
                    <h5 class="text-center h5 mb-0">{{ $child_full_name }}</h5>
                    <div class="profile-info d-flex justify-content-center flex-grow-1">
                        <div class="mt-2 mw-100">
                            {{-- Kèm ?user= để biết ai là người mở mã QR này trên web khi được quét điểm danh --}}
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate(url('/profile/' . $child_token) . '?user=' . auth()->id()) !!}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- Kết thúc modal update Avatar --}}

    <div class="modal fade" id="child_card" tabindex="-1" aria-labelledby="myLargeModalLabel1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="card-preview">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate(url('/profile/' . $child_token_card)) !!}
            </div>
        </div>
    </div>
</div>