<div>
    {{-- Chọn lịch cần điểm danh bù --}}
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="h4 text-blue mb-3">Điểm danh bù - Chọn buổi</div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="schedule_id"><strong>Lịch điểm danh <span
                                    class="text-danger">*</span></strong></label>
                        <select id="schedule_id" class="form-control" wire:model.live="schedule_id">
                            <option value="">-- Chọn buổi cần bù --</option>
                            @foreach ($listSchedule as $item)
                                <option value="{{ $item->id }}">
                                    {{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }} - {{ $item->name }}
                                    @if ($item->regulation)
                                        ({{ \Illuminate\Support\Str::limit($item->regulation->description, 40) }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="note"><strong>Ghi chú (không bắt buộc)</strong></label>
                        <input type="text" id="note" class="form-control" wire:model="note"
                            placeholder="VD: Bù do quên thẻ">
                    </div>
                </div>

                @if ($schedule)
                <div class="text-danger small">
                    #Lưu ý: bản ghi sẽ được lưu theo ngày
                    <strong>{{ \Carbon\Carbon::parse($schedule->date)->format('d-m-Y') }}</strong>
                    của buổi này và vẫn phải qua bước Xét Duyệt.
                </div>
                @else
                <div class="text-danger small">#Vui lòng chọn buổi cần điểm danh bù trước khi thêm thiếu nhi.</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Danh sách thiếu nhi --}}
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div class="h4 text-blue mb-0 mr-2">
                        Thiếu nhi vắng buổi này
                        @if ($schedule)
                        <small><span class="badge badge-danger align-middle">{{ $listChildren->total() }}</span></small>
                        @endif
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4 form-group">
                        <input type="text" class="form-control search-input outline-primary"
                            placeholder="Tìm tên/ Mã tài khoản" id="search" wire:model.live="search">
                    </div>
                </div>

                @php
                $childrenEmptyMessage = !$schedule
                ? 'Vui lòng chọn buổi cần điểm danh bù ở trên.'
                : (filled($search)
                ? 'Không tìm thấy em nào vắng buổi này khớp với "' . $search . '".'
                : 'Tất cả thiếu nhi đều đã điểm danh buổi này.');
                @endphp

                {{-- Dạng bảng: chỉ hiện từ màn hình lớn trở lên --}}
                <div class="table-responsive mt-2 d-none d-lg-block">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Tên thánh</th>
                                <th>Họ và tên</th>
                                <th class="text-center">Lớp</th>
                                <th class="text-center">Ngành</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($listChildren as $child)
                            <tr wire:key="child-{{ $child->id }}">
                                <td class="text-center align-middle">
                                    {{ ($listChildren->currentPage() - 1) * $listChildren->perPage() + $loop->iteration
                                    }}
                                </td>
                                <td class="text-center align-middle">{{ $child->holyName }}</td>
                                <td class="align-middle">{{ $child->SimpleName }}</td>
                                <td class="text-center align-middle">
                                    {{ optional($child->courses->first())->name ?? '' }}
                                </td>
                                <td class="text-center align-middle">
                                    {{ optional($child->sectors->first())->name ?? '' }}
                                </td>
                                <td class="text-center align-middle">
                                    @if (!$schedule)
                                    <span class="text-muted">Chưa chọn buổi</span>
                                    @else
                                    <button type="button" class="btn btn-primary btn-sm"
                                                wire:click="addMakeup({{ $child->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="addMakeup({{ $child->id }})">
                                                <span wire:loading.remove
                                                    wire:target="addMakeup({{ $child->id }})">Thêm</span>
                                                <span wire:loading wire:target="addMakeup({{ $child->id }})">
                                                    <span class="spinner-border spinner-border-sm" role="status"
                                                        aria-hidden="true"></span> Đang thêm...
                                                </span>
                                            </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ $childrenEmptyMessage }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Dạng thẻ: hiện trên điện thoại / máy tính bảng --}}
                <div class="d-lg-none mt-2">
                    @forelse ($listChildren as $child)
                    <div class="card mb-2" wire:key="child-card-{{ $child->id }}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="font-weight-bold text-break mr-2">
                                    <span class="text-muted">
                                            {{ ($listChildren->currentPage() - 1) * $listChildren->perPage() + $loop->iteration }}.
                                        </span>
                                    {{ trim(($child->holyName ?? '') . ' ' . $child->SimpleName) }}
                                </div>
                            </div>

                            <div class="mt-2 small text-muted">
                                <div>Lớp: {{ optional($child->courses->first())->name ?? '---' }}</div>
                                <div>Ngành: {{ optional($child->sectors->first())->name ?? '---' }}</div>
                            </div>

                            <div class="mt-3">
                                @if (!$schedule)
                                <span class="text-muted small">Chưa chọn buổi</span>
                                @else
                                <button type="button" class="btn btn-primary btn-sm btn-block"
                                            wire:click="addMakeup({{ $child->id }})" wire:loading.attr="disabled"
                                            wire:target="addMakeup({{ $child->id }})">
                                            <span wire:loading.remove
                                                wire:target="addMakeup({{ $child->id }})">Thêm</span>
                                            <span wire:loading wire:target="addMakeup({{ $child->id }})">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span> Đang thêm...
                                            </span>
                                        </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">{{ $childrenEmptyMessage }}</div>
                    @endforelse
                </div>

                <div class="d-block mt-1 text-center">
                    {{ $listChildren->links('livewire::bootstrap') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách đã thêm cho buổi này --}}
    @if ($schedule)
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="h4 text-blue mb-3">
                    Bạn đã điểm danh cho buổi này ({{ $listAdded->count() }})
                </div>

                {{-- Dạng bảng: chỉ hiện từ màn hình lớn trở lên --}}
                <div class="table-responsive d-none d-lg-block">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Họ và tên</th>
                                <th class="text-center">Ngành</th>
                                <th class="text-center">Ghi chú</th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($listAdded as $item)
                            <tr wire:key="added-{{ $item->id }}">
                                <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                <td class="align-middle">{{ $item->user->SimpleName ?? 'Không rõ' }}</td>
                                <td class="text-center align-middle">{{ $item->sector_name }}</td>
                                <td class="text-center align-middle text-break">{{ $item->note }}</td>
                                <td class="text-center align-middle">
                                    @if ($item->isConfirm)
                                    <span class="badge bg-success">Đã xét duyệt</span>
                                    @else
                                    <span class="badge bg-warning">Chờ xét duyệt</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Chưa thêm em nào cho buổi này</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Dạng thẻ: hiện trên điện thoại / máy tính bảng --}}
                <div class="d-lg-none">
                    @forelse ($listAdded as $item)
                    <div class="card mb-2" wire:key="added-card-{{ $item->id }}">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="font-weight-bold text-break mr-2">
                                    <span class="text-muted">{{ $loop->iteration }}.</span>
                                    {{ $item->user->SimpleName ?? 'Không rõ' }}
                                </div>
                                @if ($item->isConfirm)
                                <span class="badge bg-success">Đã xét duyệt</span>
                                @else
                                <span class="badge bg-warning">Chờ xét duyệt</span>
                                @endif
                            </div>

                            <div class="mt-2 small text-muted">
                                <div>Ngành: {{ $item->sector_name ?: '---' }}</div>
                                <div class="text-break">Ghi chú: {{ $item->note ?: '---' }}</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">Chưa thêm em nào cho buổi này</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif
</div>