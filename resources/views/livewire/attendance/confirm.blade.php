<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Xác nhận</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Xác nhận điểm danh
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <h6 class="h6 text-danger"><i>#Lưu ý: Đảm bảo quá trình điểm danh kết thúc. Sẽ khóa điểm danh lại khi
                        xác nhận!</i></h6>

                {{-- Dạng bảng: chỉ hiện từ màn hình lớn trở lên --}}
                <div class="table-responsive mt-4 d-none d-lg-block">
                    <table id="" class="table table-borderless table-striped table-hover ">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Hạng mục</th>
                                <th>Người điểm danh</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($listData as $groupKey => $record)
                                @php
                                    $submitterId = $record['submitter_id'];
                                    $violationName = $record['name'];
                                @endphp

                                {{-- wire:key giữ đúng dòng khi danh sách thay đổi sau mỗi lần xác nhận --}}
                                <tr wire:key="submit-{{ md5($groupKey) }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $violationName }}</td>
                                    <td>
                                        <strong>
                                            <a href="#"
                                                wire:click="viewData({{ $submitterId }}, @js($violationName))"
                                                class="text-primary text-decoration-underline">
                                                {{ $record['submitter_name'] }}
                                            </a>
                                        </strong>
                                    </td>
                                    <td class="text-center">{{ $record['total'] }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary"
                                            wire:click="confirmAttendance({{ $submitterId }}, @js($violationName))"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmAttendance({{ $submitterId }}, @js($violationName))">

                                            <span wire:loading.remove
                                                wire:target="confirmAttendance({{ $submitterId }}, @js($violationName))">
                                                <i class="bi bi-person-check"></i> Xác nhận
                                            </span>

                                            <span wire:loading
                                                wire:target="confirmAttendance({{ $submitterId }}, @js($violationName))">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span> Đang xử lý...
                                            </span>
                                        </button>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

                {{-- Dạng thẻ: hiện trên điện thoại / máy tính bảng --}}
                <div class="d-lg-none mt-4">
                    @forelse ($listData as $groupKey => $record)
                        @php
                            $submitterId = $record['submitter_id'];
                            $violationName = $record['name'];
                        @endphp

                        <div class="card mb-2" wire:key="submit-card-{{ md5($groupKey) }}">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1 text-break">
                                        <div class="font-weight-bold">
                                            <span class="text-muted">{{ $loop->iteration }}.</span>
                                            {{ $record['submitter_name'] }}
                                        </div>
                                        <div class="small text-muted mt-1">{{ $violationName }}</div>
                                    </div>
                                    <span class="badge badge-pill bg-secondary text-white ml-2">
                                        {{ $record['total'] }} em
                                    </span>
                                </div>

                                <div class="d-flex flex-wrap mt-3">
                                    <button type="button" class="btn btn-primary btn-sm mr-2 mb-2"
                                        wire:click="confirmAttendance({{ $submitterId }}, @js($violationName))"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmAttendance({{ $submitterId }}, @js($violationName))">
                                        <span wire:loading.remove
                                            wire:target="confirmAttendance({{ $submitterId }}, @js($violationName))">
                                            <i class="bi bi-person-check"></i> Xác nhận
                                        </span>
                                        <span wire:loading
                                            wire:target="confirmAttendance({{ $submitterId }}, @js($violationName))">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span> Đang xử lý...
                                        </span>
                                    </button>

                                    <button type="button" class="btn btn-outline-secondary btn-sm mb-2"
                                        wire:click="viewData({{ $submitterId }}, @js($violationName))"
                                        wire:loading.attr="disabled"
                                        wire:target="viewData({{ $submitterId }}, @js($violationName))">
                                        <i class="bi bi-list-ul"></i> Xem danh sách
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">Không có dữ liệu</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if ($isShowTableListUserSubmit)

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="pd-20 card-box mb-30">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-md-center flex-md-row-reverse">
                        <div class="h4 text-blue mb-2 mb-md-0 mr-md-3 text-break">
                            {{ $nameUserSubmit }}
                        </div>
                        <a href="#" wire:click="closeTableListUserSubmit()" class="btn btn-danger btn-sm mb-2 mb-md-0">
                            Đóng
                        </a>
                    </div>

                    {{-- Dạng bảng: chỉ hiện từ màn hình lớn trở lên --}}
                    <div class="table-responsive mt-4 d-none d-lg-block">
                        <table id="" class="table table-borderless table-striped table-hover ">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Họ và tên</th>
                                    <th class="text-center">Ngành</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listUserOfSubmit as $attendance)
                                    <tr wire:key="attendance-{{ $attendance->id }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $attendance->user->SimpleName }}</td>
                                        <td class="text-center">{{ $attendance->sector_name }}</td>
                                        <td class="text-center">
                                            @if ($attendance->status == 1)
                                                <button class="btn btn-danger"
                                                    wire:click="deleteAttendance('{{ $attendance->id }}')"
                                                    wire:loading.attr="disabled"
                                                    wire:target="deleteAttendance('{{ $attendance->id }}')">
                                                    <span wire:loading.remove
                                                        wire:target="deleteAttendance('{{ $attendance->id }}')">Xóa</span>
                                                    <span wire:loading
                                                        wire:target="deleteAttendance('{{ $attendance->id }}')">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span> Đang xóa...
                                                    </span>
                                                </button>
                                            @else
                                                <button class="btn btn-success"
                                                    wire:click="undoAttendance('{{ $attendance->id }}')"
                                                    wire:loading.attr="disabled"
                                                    wire:target="undoAttendance('{{ $attendance->id }}')">
                                                    <span wire:loading.remove
                                                        wire:target="undoAttendance('{{ $attendance->id }}')">Hoàn
                                                        tác</span>
                                                    <span wire:loading
                                                        wire:target="undoAttendance('{{ $attendance->id }}')">
                                                        <span class="spinner-border spinner-border-sm" role="status"
                                                            aria-hidden="true"></span> Đang hoàn tác...
                                                    </span>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Dạng thẻ: hiện trên điện thoại / máy tính bảng --}}
                    <div class="d-lg-none mt-4">
                        @forelse ($listUserOfSubmit as $attendance)
                            <div class="card mb-2" wire:key="attendance-card-{{ $attendance->id }}">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 text-break">
                                            <div class="font-weight-bold">
                                                <span class="text-muted">{{ $loop->iteration }}.</span>
                                                {{ $attendance->user->SimpleName }}
                                            </div>
                                            <div class="small text-muted mt-1">
                                                {{ $attendance->sector_name ?: '---' }}
                                            </div>
                                        </div>
                                        @if ($attendance->status != 1)
                                            <span class="badge badge-pill bg-danger text-white ml-2">Đã xóa</span>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        @if ($attendance->status == 1)
                                            <button class="btn btn-danger btn-sm"
                                                wire:click="deleteAttendance('{{ $attendance->id }}')"
                                                wire:loading.attr="disabled"
                                                wire:target="deleteAttendance('{{ $attendance->id }}')">
                                                <span wire:loading.remove
                                                    wire:target="deleteAttendance('{{ $attendance->id }}')">Xóa</span>
                                                <span wire:loading
                                                    wire:target="deleteAttendance('{{ $attendance->id }}')">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span> Đang xóa...
                                                </span>
                                            </button>
                                        @else
                                            <button class="btn btn-success btn-sm"
                                                wire:click="undoAttendance('{{ $attendance->id }}')"
                                                wire:loading.attr="disabled"
                                                wire:target="undoAttendance('{{ $attendance->id }}')">
                                                <span wire:loading.remove
                                                    wire:target="undoAttendance('{{ $attendance->id }}')">Hoàn
                                                    tác</span>
                                                <span wire:loading
                                                    wire:target="undoAttendance('{{ $attendance->id }}')">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span> Đang hoàn tác...
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">Không có dữ liệu</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
