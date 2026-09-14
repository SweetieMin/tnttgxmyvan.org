<div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p">
                <div class="profile-photo mt-3">
                    <img src="{{ $user->picture }}" alt="Ảnh đại diện của {{ $user->full_name }}" class="avatar-photo"
                        id="profilePicturePreview">
                    <input type="file" name="profilePictureFile" id="profilePictureFile" class="d-none"
                        style="opacity: 0">
                </div>
                <h5 class="text-center h5 mb-0">{{ $user->holyName }}</h5>
                <h5 class="text-center h3 mb-3">{{ $user->SimpleName }}</h5>
                <div class="profile-info d-flex justify-content-center ">
                    <div class="mt-2">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate(url('/profile/' . $user->token)) !!}
                    </div>
                </div>

            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="card-box height-100-p overflow-hidden">
                <div class="profile-tab height-100-p">
                    <div class="tab height-100-p">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a wire:click="selectTab('personal_details')"
                                    class="nav-link {{ $tab == 'personal_details' ? 'active' : '' }}" data-toggle="tab"
                                    href="#personal_details" role="tab">Thông tin cá nhân</a>
                            </li>

                            @if ($isShowParent)
                                <li class="nav-item">
                                    <a wire:click="selectTab('personal_parents')"
                                        class="nav-link {{ $tab == 'personal_parents' ? 'active' : '' }}"
                                        data-toggle="tab" href="#personal_parents" role="tab">Thông tin phụ
                                        huynh</a>
                                </li>
                            @endif


                            @if ($isShowReligiousProfile)
                                <li class="nav-item">
                                    <a wire:click="selectTab('personal_religious')"
                                        class="nav-link {{ $tab == 'personal_religious' ? 'active' : '' }}"
                                        data-toggle="tab" href="#personal_religious" role="tab">Thông tin công
                                        giáo</a>
                                </li>
                            @endif

                            @if ($isShowJourneyOfVocation)
                                <li class="nav-item">
                                    <a wire:click="selectTab('personal_journeyOfVocation')"
                                        class="nav-link {{ $tab == 'personal_journeyOfVocation' ? 'active' : '' }}"
                                        data-toggle="tab" href="#personal_journeyOfVocation" role="tab">Hành trình
                                        dấn
                                        thân</a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a wire:click="selectTab('update_password')"
                                    class="nav-link {{ $tab == 'update_password' ? 'active' : '' }}" data-toggle="tab"
                                    href="#update_password" role="tab">Thay đổi mật khẩu</a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <!-- Timeline Tab start -->
                            <div class="tab-pane fade {{ $tab == 'personal_details' ? 'show active' : '' }}"
                                id="personal_details" role="tabpanel">
                                <div class="pd-20">
                                    <form wire:submit="updatePersonalDetails()">
                                        <div class="row justify-content-center">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <div class="card border-secondary bg-light">
                                                        <div class="card-body d-flex justify-content-center align-items-center"
                                                            style="height: 50px;">
                                                            <div class="h3 text-center mb-0">{{ $account_code }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="holyName"><strong>Tên Thánh</strong></label>
                                                    <input type="text" class="form-control" wire:model="holyName"
                                                        placeholder="Tên Thánh" id="holyName" autocomplete="off"
                                                        disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="form-group ">
                                                    <label for="name"><strong>Họ và tên</strong></label>
                                                    <input type="text" class="form-control" wire:model="fullName"
                                                        placeholder="Họ và tên" id="name" autocomplete="off"
                                                        disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="birthday"><strong>Ngày sinh <small
                                                                class="text-muted">(dd/mm/yyyy)</small></strong></label>
                                                    <input id="birthday" class="form-control " {{-- date-picker --}}
                                                        wire:model="birthday" placeholder="Chọn ngày" type="text"
                                                        autocomplete="off" value="" disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="address"><strong>Địa chỉ</strong></label>
                                                    <input id="address" class="form-control " wire:model="address"
                                                        type="text"disabled autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group ">
                                                    <label for="phone"><strong>Số điện thoại</strong></label>
                                                    <input type="tel" class="form-control" wire:model="phone"
                                                        placeholder="Số điện thoại" id="phone"
                                                        autocomplete="off">
                                                    @error('phone')
                                                        <span class="text-danger mt-3 ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <!-- Label -->
                                                    @if (!$user->email)
                                                        <label for="email"><strong>Email</strong></label>
                                                    @else
                                                        <label for="email"
                                                            class="{{ $user->email_verified_at ? 'text-success' : 'text-warning' }}">
                                                            <strong>
                                                                {{ $user->email_verified_at
                                                                    ? 'Email đã được xác minh và có thể dùng đăng nhập.'
                                                                    : 'Email chưa được xác minh sẽ không thể dùng để đăng nhập.' }}
                                                            </strong>
                                                        </label>
                                                    @endif

                                                    <!-- Input + Button ngang hàng bằng flex -->
                                                    <div
                                                        class="d-flex {{ !$user->email_verified_at && $user->email ? 'gap-2' : '' }}">
                                                        @if (!$user->email)
                                                            <input type="email" class="form-control"
                                                                wire:model="email" placeholder="Email" id="email"
                                                                autocomplete="off">
                                                        @else
                                                            <input id="email" type="text"
                                                                class="form-control {{ $user->email_verified_at ? 'form-control-success' : 'form-control-warning' }}"
                                                                wire:model="email" placeholder="Email"
                                                                autocomplete="off">

                                                            @if (!$user->email_verified_at)
                                                                <div
                                                                    class="d-flex flex-column justify-content-center ms-2 ml-2">
                                                                    <button type="button"
                                                                        class="btn btn-warning text-nowrap mb-1"
                                                                        wire:click="sendVerificationEmail"
                                                                        wire:loading.remove>
                                                                        Xác minh
                                                                    </button>
                                                                    <button class="btn btn-warning btn-sm" wire:loading
                                                                        wire:target="sendVerificationEmail"
                                                                        type="button" disabled>
                                                                        <span class="spinner-border spinner-border-sm"
                                                                            role="status" aria-hidden="true"></span>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>

                                                    <!-- Error -->
                                                    @error('email')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row">
                                            @if ($isShowRole)
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="role"><strong>Chức vụ</strong></label>
                                                        <input id="role" class="form-control " wire:model="role"
                                                            type="text"disabled>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($isShowCourse)
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="course"><strong>Lớp Giáo Lý</strong></label>
                                                        <input id="course" class="form-control "
                                                            wire:model="course" type="text"disabled>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($isShowSector)
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="sector"><strong>Ngành</strong></label>
                                                        <input id="sector" class="form-control "
                                                            wire:model="sector" type="text"disabled>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="bio"><strong>Giới thiệu bản thân</strong></label>
                                                    <textarea wire:model="bio" id="bio" cols="4" rows="4" class="form-control"
                                                        placeholder="Hãy viết thật hay để bạn bè biết thêm về bản thân nhé!"></textarea>
                                                    @error('bio')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-center mt-2">
                                                    <button type="submit" class="btn btn-primary" wire:loading.remove
                                                        wire:target="updatePersonalDetails">
                                                        Lưu thay đổi
                                                    </button>
                                                    <button class="btn btn-primary btn-lg"
                                                        wire:loading wire:target="updatePersonalDetails"
                                                        type="button" disabled>
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span>
                                                        Đang xử lý...
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- Timeline Tab End -->
                            @if ($isShowParent)
                                <!-- Parents Tab start -->
                                <div class="tab-pane fade {{ $tab == 'personal_parents' ? 'show active' : '' }}"
                                    id="personal_parents" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row">
                                            <div class="col-md-8 form-group">
                                                <label for="nameFather"><strong>Tên Thánh - Họ và tên
                                                        Cha</strong></label>
                                                <input type="text" class="form-control" wire:model="nameFather"
                                                    id="nameFather" disabled>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="phoneFather"><strong>Số điện thoại</strong></label>
                                                <input type="tel" class="form-control" wire:model="phoneFather"
                                                    id="phoneFather" disabled>
                                            </div>

                                            <div class="col-md-8 form-group">
                                                <label for="nameMother"><strong>Tên Thánh - Họ và tên
                                                        Mẹ</strong></label>
                                                <input type="text" class="form-control" wire:model="nameMother"
                                                    id="nameMother" disabled>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="phoneMother"><strong>Số điện thoại</strong></label>
                                                <input type="tel" class="form-control" wire:model="phoneMother"
                                                    id="phoneMother" disabled>
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label for="godParents"><strong>Tên Thánh - Họ và tên người đỡ
                                                        đầu</strong></label>
                                                <input type="text" class="form-control" wire:model="godParents"
                                                    id="godParents" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Parents Tab end -->
                            @endif

                            @if ($isShowReligiousProfile)
                                <!-- Religious Tab start -->
                                <div class="tab-pane fade {{ $tab == 'personal_religious' ? 'show active' : '' }}"
                                    id="personal_religious" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label for="ngay_rua_toi"><strong>Ngày Rửa Tội</strong></label>
                                                <input type="text" class="form-control" wire:model="ngay_rua_toi"
                                                    id="ngay_rua_toi" disabled>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <label for="linh_muc_rua_toi"><strong>Linh mục Rửa Tội</strong></label>
                                                <input type="text" class="form-control"
                                                    wire:model="linh_muc_rua_toi" id="linh_muc_rua_toi" disabled>
                                            </div>

                                            @if ($ngay_xung_toi)
                                                <div class="col-md-4 form-group">
                                                    <label for="ngay_xung_toi"><strong>Ngày Xưng Tội</strong></label>
                                                    <input type="text" class="form-control"
                                                        wire:model="ngay_xung_toi" id="ngay_xung_toi" disabled>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="row">
                                            @if ($ngay_them_suc)
                                                <div class="col-md-4 form-group">
                                                    <label for="ngay_them_suc"><strong>Ngày Thêm Sức</strong></label>
                                                    <input type="text" class="form-control"
                                                        wire:model="ngay_them_suc" id="ngay_them_suc" disabled>
                                                </div>
                                                <div class="col-md-8 form-group">
                                                    <label for="giam_muc_them_suc"><strong>Do ĐGM</strong></label>
                                                    <input type="text" class="form-control"
                                                        wire:model="giam_muc_them_suc" id="giam_muc_them_suc"
                                                        disabled>
                                                </div>
                                            @endif

                                            @if ($ngay_bao_dong)
                                                <div class="col-md-4 form-group">
                                                    <label for="ngay_bao_dong"><strong>Ngày Bao Đồng</strong></label>
                                                    <input type="text" class="form-control"
                                                        wire:model="ngay_bao_dong" id="ngay_bao_dong" disabled>
                                                </div>
                                            @endif

                                            <div class="col-md-8 form-group">
                                                <label for="trang_thai_ton_giao"><strong>Tình trạng học
                                                        tập</strong></label>
                                                <input type="text" class="form-control"
                                                    wire:model="trang_thai_ton_giao" id="trang_thai_ton_giao"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Religious Tab start -->
                                </div>
                                <!-- Tasks Tab start -->
                            @endif
                            @if ($isShowJourneyOfVocation)
                                <!-- journeyOfVocation Tab start -->
                                <div class="tab-pane fade {{ $tab == 'personal_journeyOfVocation' ? 'show active' : '' }}"
                                    id="personal_journeyOfVocation" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ngay_doi_truong"><strong>Ngày Tham gia Đội
                                                            Trưởng</strong></label>
                                                    <input type="text" class="form-control" id="ngay_doi_truong"
                                                        wire:model="ngay_doi_truong" disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ngay_du_truong"><strong>Ngày Tham gia Dự
                                                            Trưởng</strong></label>
                                                    <input type="text" class="form-control" id="ngay_du_truong"
                                                        wire:model="ngay_du_truong" disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ngay_huynh_truong"><strong>Ngày Tham gia Huynh
                                                            Trưởng</strong></label>
                                                    <input type="text" class="form-control" id="ngay_huynh_truong"
                                                        wire:model="ngay_huynh_truong" disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ngay_huynh_truong2"><strong>Ngày Tham gia Huynh Trưởng
                                                            <span class="text-danger">2*</span></strong></label>
                                                    <input type="text" class="form-control"
                                                        id="ngay_huynh_truong2" wire:model="ngay_huynh_truong2"
                                                        disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="ngay_huynh_truong3"><strong>Ngày Tham gia Huynh Trưởng
                                                            <span class="text-danger">3*</span></strong></label>
                                                    <input type="text" class="form-control"
                                                        id="ngay_huynh_truong3" wire:model="ngay_huynh_truong3"
                                                        disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- journeyOfVocation Tab end -->
                            @endif
                            <!-- Tasks Tab start -->
                            <div class="tab-pane fade {{ $tab == 'update_password' ? 'show active' : '' }}"
                                id="update_password" role="tabpanel">
                                <div class="pd-20 profile-task-wrap">

                                    <form wire:submit="updatePassword()">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="current_password"><strong>Mật khẩu hiện
                                                            tại</strong></label>
                                                    <input type="password" class="form-control"
                                                        wire:model="current_password" id="current_password"
                                                        placeholder="Mật khẩu hiện tại">
                                                    @error('current_password')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="new_password"><strong>Mật khẩu mới</strong></label>
                                                    <input type="password" class="form-control"
                                                        wire:model="new_password" id="new_password"
                                                        placeholder="Mật khẩu mới">
                                                    @error('new_password')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="new_password_confirmation"><strong>Xác nhận mật
                                                            khẩu</strong></label>
                                                    <input type="password" class="form-control"
                                                        wire:model="new_password_confirmation"
                                                        placeholder="Xác nhận mật khẩu mới"
                                                        id="new_password_confirmation">
                                                    @error('new_password_confirmation')
                                                        <span class="text-danger ml-1">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-center mt-2">
                                                    <div class="d-flex justify-content-center mt-2">
                                                        <button type="button" class="btn btn-primary btn-lg"
                                                            wire:click="updatePassword" wire:loading.attr="disabled"
                                                            wire:target="updatePassword">

                                                            <span wire:loading.remove wire:target="updatePassword">Đổi
                                                                mật khẩu</span>

                                                            <span wire:loading wire:target="updatePassword">
                                                                <span class="spinner-border spinner-border-sm"
                                                                    role="status" aria-hidden="true"></span>
                                                                Đang xử lý...
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Tasks Tab End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
