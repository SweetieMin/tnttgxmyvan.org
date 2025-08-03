@extends('layout.profile')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')

    <div class="row pd-20">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p d-flex flex-column">
                <h5 class="text-center text-danger h4 mb-3">{{ $user->roles->first()->name ?? '' }}</h5>

                <div class="profile-photo">
                    <img src="{{ $user->picture }}" alt="Ảnh đại diện của {{ $user->full_name }}" class="avatar-photo"
                        id="profilePicturePreview">
                </div>
                <h5 class="text-center h5 mb-0">{{ $user->holyName }}</h5>
                <h5 class="text-center h3 mb-3">{{ $user->lastName . ' ' . $user->name }}</h5>
                <div class="profile-info d-flex justify-content-center flex-grow-1">
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
                                <a class="nav-link active" data-toggle="tab" href="#personal_details" role="tab">Thông
                                    tin cá nhân</a>
                            </li>
                            @isset($user->studentParent)
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#personal_parents" role="tab">Thông tin phụ
                                        huynh</a>
                                </li>
                            @endisset

                        </ul>
                        <div class="tab-content">
                            <!-- Timeline Tab start -->
                            <div class="tab-pane fade show active" id="personal_details" role="tabpanel">
                                <div class="pd-20">
                                    <div class="row justify-content-center">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="card border-secondary bg-light">
                                                    <div class="card-body d-flex justify-content-center align-items-center"
                                                        style="height: 50px;">
                                                        <div class="h3 text-center mb-0">{{ $user->account_code }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        {{-- Tên Thánh --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="holyName">Tên Thánh</label>
                                                <input type="text" id="holyName" name="holyName"
                                                    class="form-control bg-light" value="{{ $user->holyName }}" disabled>
                                            </div>
                                        </div>

                                        {{-- Họ và tên --}}
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="name">Họ và tên</label>
                                                <input type="text" id="name" name="name"
                                                    class="form-control bg-light" value="{{ $user->SimpleName }}" disabled>
                                            </div>
                                        </div>

                                        {{-- Số điện thoại --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">Số điện thoại</label>
                                                <input type="text" id="phone" class="form-control bg-light"
                                                    value="{{ $user->phone ? Str::mask($user->phone, '*', 3, strlen($user->phone) - 6) : 'Chưa cập nhật' }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        {{-- Ngày sinh --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="birthday">Ngày sinh <small
                                                        class="text-muted">(dd/mm/yyyy)</small></label>
                                                <input type="text" id="birthday" class="form-control bg-light"
                                                    value="{{ $user->birthday ?? 'Chưa có' }}" disabled>
                                            </div>
                                        </div>

                                        {{-- Email --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" id="email" class="form-control bg-light"
                                                    value="{{ $user->email ? Str::mask($user->email, '*', 3, strpos($user->email, '@') - 3) : 'Chưa có' }}"
                                                    disabled>
                                            </div>
                                        </div>

                                        {{-- Giới thiệu bản thân --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="bio">Giới thiệu bản thân</label>
                                                <textarea id="bio" class="form-control bg-light" rows="3" disabled>{{ $user->bio ?? 'Chưa có' }}</textarea>
                                            </div>
                                        </div>

                                        @if ($user->courses->isNotEmpty())
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="course">Lớp giáo lý</label>
                                                    <input type="text" id="course" class="form-control bg-light"
                                                        value="{{ $user->courses->first()->name }}" disabled>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($user->sectors->isNotEmpty())
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sector">Ngành sinh hoạt</label>
                                                    <input type="text" id="sector" class="form-control bg-light"
                                                        value="{{ $user->sectors->first()->name }}" disabled>
                                                </div>
                                            </div>
                                        @endif


                                        {{-- Điểm số --}}
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="score" class="font-weight-bold mb-2">Số điểm hiện
                                                    tại</label>
                                                <div id="score"
                                                    class="d-flex justify-content-between align-items-center border rounded px-3 py-2 bg-white shadow-sm">
                                                    <span class="h5 mb-0 text-primary">{{ $totalScore }}</span>
                                                    <a href="{{ route('admin.score') }}"
                                                        class="text-decoration-none text-primary small">
                                                        <u>Xem chi tiết</u>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- Timeline Tab end -->

                            @if (isset($user->studentParent))
                                <!-- Timeline Tab start -->

                                <div class="tab-pane fade" id="personal_parents" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row">
                                            {{-- Cha --}}
                                            <div class="col-md-8 form-group">
                                                <label for="nameFather"><strong>Tên Thánh - Họ và tên Cha</strong></label>
                                                <input type="text" id="nameFather" class="form-control bg-light"
                                                    value="{{ optional($user->studentParent)->nameFather ?? 'Chưa cập nhật' }}"
                                                    disabled>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="phoneFather"><strong>Số điện thoại Cha</strong></label>
                                                <input type="text" id="phoneFather" class="form-control bg-light"
                                                    value="{{ optional($user->studentParent)->phoneFather
                                                        ? Str::mask($user->studentParent->phoneFather, '*', 3, strlen($user->studentParent->phoneFather) - 6)
                                                        : 'Chưa cập nhật' }}"
                                                    disabled>
                                            </div>

                                            {{-- Mẹ --}}
                                            <div class="col-md-8 form-group">
                                                <label for="nameMother"><strong>Tên Thánh - Họ và tên Mẹ</strong></label>
                                                <input type="text" id="nameMother" class="form-control bg-light"
                                                    value="{{ optional($user->studentParent)->nameMother ?? 'Chưa cập nhật' }}"
                                                    disabled>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="phoneMother"><strong>Số điện thoại Mẹ</strong></label>
                                                <input type="text" id="phoneMother" class="form-control bg-light"
                                                    value="{{ optional($user->studentParent)->phoneMother
                                                        ? Str::mask($user->studentParent->phoneMother, '*', 3, strlen($user->studentParent->phoneMother) - 6)
                                                        : 'Chưa cập nhật' }}"
                                                    disabled>
                                            </div>

                                            {{-- Người đỡ đầu --}}
                                            <div class="col-md-12 form-group">
                                                <label for="godParent"><strong>Tên Thánh - Họ và tên người đỡ
                                                        đầu</strong></label>
                                                <input type="text" id="godParent" class="form-control bg-light"
                                                    value="{{ optional($user->studentParent)->godParent ?? 'Chưa cập nhật' }}"
                                                    disabled>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                    <!-- Timeline Tab start -->
                </div>
            </div>
        </div>
    </div>

    @if ($noticePopUp)
        <div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content bg-{{ $noticePopUp->type }} text-white">
                    <div class="modal-body text-center">
                        <h2 class="text-white mb-3">
                            <i class="fa fa-exclamation-triangle"></i> {{ $noticePopUp->title }}
                        </h2>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <img src="/images/site/Notice.png" alt="{{ $noticePopUp->title }}" class="img-fluid">
                            </div>
                            <div class="col-md-8 d-flex align-items-center">
                                <div class="card-box w-100 d-flex align-items-center justify-content-center"
                                    style="min-height: 200px; background: white; border-radius: 8px;">
                                    <h4 class="text-{{ $noticePopUp->type }} mb-0">
                                        {{ $noticePopUp->content }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    @if ($noticePopUp)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#alert-modal').modal('show');
            });
        </script>
    @endif
@endpush
