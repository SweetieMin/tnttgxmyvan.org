<div>
    {{-- CSS riêng cho danh sách lịch điểm danh (tối ưu cho điện thoại) --}}
    <style>
        .schedule-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .schedule-toolbar .h4 {
            margin-bottom: 0;
        }

        /* Bảng (từ màn hình lớn trở lên) */
        .schedule-table th,
        .schedule-table td {
            vertical-align: middle;
            white-space: nowrap;
        }

        .schedule-table .schedule-col-name {
            white-space: normal;
            text-align: left;
            min-width: 260px;
        }

        /* Danh sách dạng thẻ (điện thoại / máy tính bảng) */
        .schedule-card {
            border: 1px solid #e6e6e6;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 12px;
            background: #fff;
        }

        .schedule-card+.schedule-card {
            margin-top: 0;
        }

        .schedule-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .schedule-card-date {
            font-size: 15px;
            font-weight: 600;
            color: #1b00ff;
        }

        .schedule-card-index {
            display: inline-block;
            min-width: 24px;
            height: 24px;
            line-height: 24px;
            text-align: center;
            border-radius: 50%;
            background: #eef0ff;
            color: #1b00ff;
            font-size: 12px;
            font-weight: 600;
            margin-right: 8px;
        }

        .schedule-card-name {
            margin-top: 8px;
            font-size: 14px;
            line-height: 1.5;
            color: #212529;
            word-break: break-word;
        }

        .schedule-card-meta {
            margin-top: 8px;
            font-size: 13px;
            color: #6c757d;
        }

        .schedule-card-meta span+span::before {
            content: "•";
            margin: 0 6px;
            color: #ced4da;
        }

        .schedule-card-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .schedule-card-actions .btn {
            flex: 0 0 auto;
        }

        .schedule-empty {
            padding: 24px 12px;
            text-align: center;
            color: #6c757d;
        }

        @media (max-width: 575.98px) {
            .schedule-toolbar .btn {
                width: 100%;
            }
        }
    </style>

    {{-- Bảng hiện danh sách lịch điểm danh --}}
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="schedule-toolbar mt-2">
                    <div class="h4 text-blue">
                        Lịch điểm danh - Danh sách
                    </div>
                    <!-- spell-check: enable -->
                    <a href="javascript:;" wire:click="addSchedule()" class="btn btn-primary btn-sm">
                        Thêm Lịch
                    </a>
                </div>

                {{-- Dạng bảng: chỉ hiện trên màn hình lớn --}}
                <div class="table-responsive mt-3 d-none d-lg-block">
                    <table class="table table-bordered table-hover schedule-table">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Ngày</th>
                                <th class="text-center schedule-col-name">Hạng mục</th>
                                <th class="text-center">Bắt đầu</th>
                                <th class="text-center">Kết thúc</th>
                                <th class="text-center">Người tạo</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($listSchedule as $schedule)
                                @php
                                    $badge = match ($schedule->status_text) {
                                        'pending' => 'warning',
                                        'open' => 'success',
                                        'closed' => 'dark text-white',
                                    };
                                    $label = match ($schedule->status_text) {
                                        'pending' => 'Chưa đến giờ',
                                        'open' => 'Đang mở',
                                        'closed' => 'Đã kết thúc',
                                    };
                                    $canExport =
                                        !empty($exportableScheduleIds) &&
                                        in_array($schedule->id, $exportableScheduleIds);
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        {{ ($listSchedule->currentPage() - 1) * $listSchedule->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($schedule->date)->format('d-m-Y') }}
                                    </td>
                                    <td class="schedule-col-name">
                                        {{ $schedule->regulation->description ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $schedule->createdBy->SimpleName ?? $schedule->created_by }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" schedule="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editSchedule({{ $schedule->id }})">
                                                    <i class="dw dw-edit2"></i> Chỉnh sửa
                                                </a>
                                                @if ($canExport)
                                                    <a class="dropdown-item" href="javascript:;"
                                                        wire:click="exportAbsentChildren({{ $schedule->id }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="exportAbsentChildren({{ $schedule->id }})">
                                                        <i class="dw dw-download"></i>
                                                        <span wire:loading.remove
                                                            wire:target="exportAbsentChildren({{ $schedule->id }})">Xuất DS vắng lễ</span>
                                                        <span wire:loading
                                                            wire:target="exportAbsentChildren({{ $schedule->id }})">Đang xuất...</span>
                                                    </a>
                                                @endif
                                                <a class="dropdown-item text-danger" href="javascript:;"
                                                    wire:click="deleteSchedule({{ $schedule->id }})">
                                                    <i class="dw dw-delete-3"></i> Xóa
                                                </a>
                                            </div>
                                        </div>
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
                <div class="d-lg-none mt-3">
                    @forelse ($listSchedule as $schedule)
                        @php
                            $badge = match ($schedule->status_text) {
                                'pending' => 'warning',
                                'open' => 'success',
                                'closed' => 'dark text-white',
                            };
                            $label = match ($schedule->status_text) {
                                'pending' => 'Chưa đến giờ',
                                'open' => 'Đang mở',
                                'closed' => 'Đã kết thúc',
                            };
                            $canExport =
                                !empty($exportableScheduleIds) && in_array($schedule->id, $exportableScheduleIds);
                        @endphp
                        <div class="schedule-card">
                            <div class="schedule-card-head">
                                <div class="schedule-card-date">
                                    <span class="schedule-card-index">
                                        {{ ($listSchedule->currentPage() - 1) * $listSchedule->perPage() + $loop->iteration }}
                                    </span>
                                    {{ \Carbon\Carbon::parse($schedule->date)->format('d-m-Y') }}
                                </div>
                                <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                            </div>

                            <div class="schedule-card-name">
                                {{ $schedule->name ?? $schedule->regulation->description }}
                            </div>

                            <div class="schedule-card-meta">
                                <span>
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </span>
                                <span>{{ $schedule->createdBy->SimpleName ?? $schedule->created_by }}</span>
                            </div>

                            <div class="schedule-card-actions">
                                <a href="javascript:;" class="btn btn-outline-primary btn-sm"
                                    wire:click="editSchedule({{ $schedule->id }})">
                                    <i class="dw dw-edit2"></i> Chỉnh sửa
                                </a>
                                @if ($canExport)
                                    <a href="javascript:;" class="btn btn-outline-success btn-sm"
                                        wire:click="exportAbsentChildren({{ $schedule->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="exportAbsentChildren({{ $schedule->id }})">
                                        <i class="dw dw-download"></i>
                                        <span wire:loading.remove
                                            wire:target="exportAbsentChildren({{ $schedule->id }})">Xuất DS vắng</span>
                                        <span wire:loading
                                            wire:target="exportAbsentChildren({{ $schedule->id }})">Đang xuất...</span>
                                    </a>
                                @endif
                                <a href="javascript:;" class="btn btn-outline-danger btn-sm"
                                    wire:click="deleteSchedule({{ $schedule->id }})">
                                    <i class="dw dw-delete-3"></i> Xóa
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="schedule-empty">Không có dữ liệu</div>
                    @endforelse
                </div>

                <div class="d-block mt-1 text-center">
                    {{ $listSchedule->links('livewire::bootstrap') }}
                </div>
            </div>
        </div>
    </div>
    {{-- Kết thúc Bảng hiện danh sách lịch điểm danh --}}

    {{-- Modal thêm lịch điểm danh --}}
    <div wire:ignore.self class="modal fade" id="schedule_modal" tabindex="-1" schedule="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateScheduleMode ? 'Cập nhật lịch' : 'Thêm lịch' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateScheduleMode)
                        <input type="hidden" wire:model="schedule_id">
                    @endif

                    <div class="row">
                        <!-- Tên lịch điểm danh -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="schedule_name"><strong>Tên lịch điểm danh <span
                                            class="text-danger">*</span></strong></label>
                                <input type="text" class="form-control" id="schedule_name" wire:model="schedule_name"
                                    placeholder="VD: Thánh lễ Chúa Nhật" autocomplete="on">
                                @error('schedule_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Loại lịch điểm danh -->
                        <div class="col-md-6">
                            <div wire:ignore class="form-group">
                                <label for="schedule_type"><strong>Loại lịch điểm danh <span
                                            class="text-danger">*</span></strong></label>
                                <select id="schedule_type" class="form-control selectpicker"
                                    data-style="btn-outline-primary" wire:model="schedule_type" data-live-search="true"
                                    data-actions-box="true">
                                    @foreach ($listRegulation as $item)
                                        <option value="{{ $item->id }}">
                                            {{ \Illuminate\Support\Str::limit($item->description, 40) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Ngày điểm danh -->
                        <div class="col-md-4 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="schedule_date"><strong>Ngày điểm danh <span
                                            class="text-danger">*</span></strong></label>
                                <input type="date" class="form-control" id="schedule_date"
                                    wire:model="schedule_date">
                                @error('schedule_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Giờ bắt đầu -->
                        <div class="col-md-4 col-sm-6 mt-2">
                            <div class="form-group">
                                <label for="schedule_start_time"><strong>Giờ bắt đầu <span
                                            class="text-danger">*</span></strong></label>
                                <input type="time" class="form-control" id="schedule_start_time"
                                    wire:model="schedule_start_time" placeholder="hh:mm">
                                @error('schedule_start_time')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Giờ kết thúc -->
                        <div class="col-md-4 col-sm-6 mt-2">
                            <div class="form-group">
                                <label for="schedule_end_time"><strong>Giờ kết thúc <span
                                            class="text-danger">*</span></strong></label>
                                <input type="time" class="form-control" id="schedule_end_time"
                                    wire:model="schedule_end_time" placeholder="hh:mm">
                                @error('schedule_end_time')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <!-- Người tạo -->
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="schedule_created_by"><strong>Người tạo</strong></label>
                                <input type="text" class="form-control" id="schedule_created_by"
                                    wire:model="schedule_created_by" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <div class="text-danger mt-2 small">
                        #Lưu ý: chỉ nên tạo lịch 1 lần trong ngày. Nếu cần chỉnh sửa, hãy làm trước khi đến giờ điểm
                        danh.
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdateScheduleMode ? 'Lưu thay đổi' : 'Thêm lịch' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>