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
                <div class="table-responsive mt-4">
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
                            @forelse ($listData as $submitter => $attendances)
                                @php
                                    [$submitterId, $violationName] = explode('|', $submitter);
                                @endphp

                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $violationName }}</td>
                                    <td>
                                        <strong>
                                            <a href="#"
                                                wire:click="viewData({{ $submitterId }}, @js($violationName))"
                                                class="text-primary text-decoration-underline">
                                                {{ $attendances[0]['submittedBy']['SimpleName'] ?? 'Không rõ' }}
                                            </a>
                                        </strong>
                                    </td>
                                    <td class="text-center">{{ count($attendances) }}</td>
                                    <td class="text-center">
                                        <a class="btn btn-primary" href="#"
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
                                        </a>
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
            </div>
        </div>
    </div>

    @if ($isShowTableListUserSubmit)

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <a href="#" wire:click="closeTableListUserSubmit()"
                                class="btn btn-danger mr-5">Đóng</a>
                        </div>
                        <div class="pull-right">
                            <div class="h4 text-blue mr-3">
                                {{ $nameUserSubmit }}
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
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
                                    <tr>
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
                </div>
            </div>
        </div>
    @endif
</div>
