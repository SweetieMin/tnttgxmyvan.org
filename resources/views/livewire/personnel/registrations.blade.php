<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mt-2 mb-2">
                    <div class="pull-left">
                        <div class="h4 text-blue">Đơn đăng ký / cấp lại thẻ</div>
                        <small class="text-muted">
                            Trang cho phụ huynh:
                            <a href="{{ route('registration') }}" target="_blank">{{ route('registration') }}</a>
                        </small>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-8">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <a href="javascript:;" wire:click="selectTab('pending')"
                                    class="nav-link text-blue {{ $tab === 'pending' ? 'active text-white' : '' }}">
                                    Chờ duyệt
                                    @if ($counts['pending'] ?? 0)
                                        <span class="badge badge-danger ml-1">{{ $counts['pending'] }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" wire:click="selectTab('approved')"
                                    class="nav-link text-blue {{ $tab === 'approved' ? 'active text-white' : '' }}">Đã duyệt</a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" wire:click="selectTab('rejected')"
                                    class="nav-link text-blue {{ $tab === 'rejected' ? 'active text-white' : '' }}">Từ chối</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 d-flex mt-2 mt-md-0">
                        <select class="form-control mr-2" wire:model.live="type" style="max-width: 150px;">
                            <option value="">Tất cả</option>
                            <option value="new">Đăng ký mới</option>
                            <option value="reissue">Cấp lại thẻ</option>
                        </select>
                        <input type="text" class="form-control" wire:model.live.debounce.400ms="search"
                            placeholder="Mã hồ sơ, tên, SĐT...">
                    </div>
                </div>

                <div class="table-responsive mt-2">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">Mã hồ sơ</th>
                                <th class="text-center">Loại</th>
                                <th>Thiếu nhi</th>
                                <th class="text-center">SĐT liên hệ</th>
                                <th class="text-center">Ngày gửi</th>
                                @if ($tab !== 'pending')
                                    <th class="text-center">Người duyệt</th>
                                    <th>Ghi chú</th>
                                @endif
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($listRequests as $item)
                                <tr>
                                    <td class="text-center"><strong>{{ $item->code }}</strong></td>
                                    <td class="text-center">
                                        @if ($item->type === 'new')
                                            <span class="badge badge-primary">Đăng ký mới</span>
                                        @else
                                            <span class="badge badge-warning">{{ $item->reason_label ?? 'Cấp lại thẻ' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->child_name }}
                                        @if ($item->user)
                                            <div class="small text-muted">{{ $item->user->account_code }}</div>
                                        @endif
                                        @if ($item->duplicate_user_id && $item->status === 'pending')
                                            <div class="small text-danger"><i class="fa fa-exclamation-triangle"></i> Nghi trùng</div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->contact_phone }}</td>
                                    <td class="text-center">{{ $item->created_at->format('H:i d/m/Y') }}</td>
                                    @if ($tab !== 'pending')
                                        <td class="text-center">
                                            {{ $item->reviewer?->SimpleName }}
                                            <div class="small text-muted">{{ $item->reviewed_at?->format('H:i d/m/Y') }}</div>
                                        </td>
                                        <td>{{ $item->admin_note }}</td>
                                    @endif
                                    <td class="text-center">
                                        <a href="javascript:;" wire:click="viewRequest({{ $item->id }})"
                                            class="btn btn-primary btn-sm" wire:loading.attr="disabled"
                                            wire:target="viewRequest({{ $item->id }})">
                                            <i class="fa fa-eye"></i> {{ $item->status === 'pending' ? 'Duyệt' : 'Xem' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $tab === 'pending' ? 6 : 8 }}" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $listRequests->links('livewire::bootstrap') }}
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div wire:ignore.self class="modal fade" id="registration_modal" tabindex="-1" role="dialog"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                @if ($current)
                    @php $isPending = $current->status === 'pending'; @endphp
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{ $current->type_label }} - {{ $current->code }}
                            <span class="badge badge-{{ ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$current->status] }} ml-2">
                                {{ $current->status_label }}
                            </span>
                        </h4>
                        <button type="button" class="close" wire:click="hideRegistrationModal">×</button>
                    </div>
                    <div class="modal-body">
                        @if ($current->type === 'new')
                            @if ($current->duplicateUser && $isPending)
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    Trùng tên và ngày sinh với <strong>{{ $current->duplicateUser->FullName }}</strong>
                                    (Mã {{ $current->duplicateUser->account_code }}). Kiểm tra lại trước khi duyệt.
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-md-3 text-center mb-3">
                                    @if ($current->picture_url)
                                        <a href="{{ $current->picture_url }}" target="_blank">
                                            <img src="{{ $current->picture_url }}" alt="Ảnh thẻ"
                                                style="width: 150px; height: 200px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                                        </a>
                                    @elseif ($current->user)
                                        <img src="{{ $current->user->picture }}" alt="Ảnh thẻ"
                                            style="width: 150px; height: 200px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                                    @endif
                                    @if ($current->user)
                                        <div class="h5 mt-2 text-primary">{{ $current->user->account_code }}</div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label><strong>Tên Thánh</strong></label>
                                            <input type="text" class="form-control @error('holyName') is-invalid @enderror"
                                                wire:model="holyName" @disabled(!$isPending)>
                                            @error('holyName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <label><strong>Họ và tên</strong></label>
                                            <input type="text" class="form-control @error('fullName') is-invalid @enderror"
                                                wire:model="fullName" @disabled(!$isPending)>
                                            @error('fullName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label><strong>Ngày sinh</strong></label>
                                            <input type="date" class="form-control @error('birthday') is-invalid @enderror"
                                                wire:model="birthday" @disabled(!$isPending)>
                                            @error('birthday') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <label><strong>Địa chỉ</strong></label>
                                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                                wire:model="address" @disabled(!$isPending)>
                                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label><strong>Ngành</strong> <span class="text-danger">*</span></label>
                                            <select class="form-control @error('sector_id') is-invalid @enderror"
                                                wire:model="sector_id" @disabled(!$isPending)>
                                                <option value="">-- Chọn ngành --</option>
                                                @foreach ($listSectors as $sector)
                                                    <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('sector_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label><strong>Lớp giáo lý</strong></label>
                                            <select class="form-control @error('course_id') is-invalid @enderror"
                                                wire:model="course_id" @disabled(!$isPending)>
                                                <option value="">-- Chưa xếp lớp --</option>
                                                @foreach ($listCourses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered mb-3">
                                <tr>
                                    <th style="width: 25%">Cha</th>
                                    <td>{{ $current->nameFather ?: '—' }}</td>
                                    <td style="width: 20%">{{ $current->phoneFather }}</td>
                                </tr>
                                <tr>
                                    <th>Mẹ</th>
                                    <td>{{ $current->nameMother ?: '—' }}</td>
                                    <td>{{ $current->phoneMother }}</td>
                                </tr>
                                <tr>
                                    <th>Người đỡ đầu</th>
                                    <td colspan="2">{{ $current->godParent ?: '—' }}</td>
                                </tr>
                                <tr>
                                    <th>SĐT của em</th>
                                    <td colspan="2">{{ $current->phone ?: '—' }}</td>
                                </tr>
                            </table>
                        @else
                            @php
                                $child = $current->user;
                                $knownPhones = array_filter([$child?->phone, $child?->studentParent?->phoneFather, $child?->studentParent?->phoneMother]);
                            @endphp
                            @if (!$child)
                                <div class="alert alert-danger">Tài khoản thiếu nhi của đơn này không còn tồn tại.</div>
                            @else
                                <div class="row">
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="small text-muted mb-1">Ảnh hiện tại</div>
                                        <img src="{{ $child->picture }}" alt="Ảnh hiện tại"
                                            style="width: 150px; height: 200px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                                    </div>
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="small text-muted mb-1">Ảnh mới</div>
                                        @if ($current->picture_url)
                                            <a href="{{ $current->picture_url }}" target="_blank">
                                                <img src="{{ $current->picture_url }}" alt="Ảnh mới"
                                                    style="width: 150px; height: 200px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                                            </a>
                                        @else
                                            <div class="text-muted pt-5">Dùng lại ảnh cũ</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <h4 class="h4 mb-1">{{ $child->FullName }}</h4>
                                        <div class="h5 text-primary mb-3">{{ $child->account_code }}</div>
                                        <div>Ngày sinh: <strong>{{ $child->birthday }}</strong></div>
                                        <div>Ngành: <strong>{{ $child->sectors->pluck('name')->implode(', ') ?: '—' }}</strong></div>
                                        <div>Lớp: <strong>{{ $child->courses->pluck('name')->implode(', ') ?: '—' }}</strong></div>
                                        <div>Số lần đã cấp thẻ: <strong>{{ $child->reissue_count }}</strong></div>
                                        <div class="mt-2">Lý do: <span class="badge badge-warning">{{ $current->reason_label }}</span></div>
                                        <div class="mt-2">
                                            SĐT liên hệ: <strong>{{ $current->contact_phone }}</strong>
                                            @if (in_array($current->contact_phone, $knownPhones))
                                                <span class="badge badge-success">Khớp SĐT trong hồ sơ</span>
                                            @else
                                                <span class="badge badge-danger">Khác SĐT trong hồ sơ</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if ($isPending)
                                    <div class="alert alert-info small mb-2">
                                        Khi duyệt: em chuyển về trạng thái <strong>chưa làm thẻ</strong>. Sau đó xuất thẻ mới ở trang Thiếu Nhi như bình thường.
                                    </div>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="invalidate_old_card"
                                            wire:model.live="invalidate_old_card">
                                        <label class="custom-control-label" for="invalidate_old_card">
                                            <strong>Vô hiệu thẻ cũ</strong>: mã QR trên thẻ cũ sẽ không còn quét điểm danh được
                                            <div class="small text-muted">Nên chọn khi em bị mất thẻ, để người nhặt được không điểm danh hộ.</div>
                                        </label>
                                    </div>
                                @endif
                            @endif
                        @endif

                        @if ($current->note)
                            <div class="mb-3"><strong>Ghi chú của phụ huynh:</strong> {{ $current->note }}</div>
                        @endif

                        <div class="form-group mb-0">
                            <label><strong>Ghi chú của Xứ Đoàn</strong> <small class="text-muted">(bắt buộc khi từ chối, phụ huynh sẽ thấy khi tra cứu)</small></label>
                            <input type="text" class="form-control @error('admin_note') is-invalid @enderror"
                                wire:model="admin_note" @disabled(!$isPending)
                                placeholder="VD: Nhận thẻ tại phòng giáo lý sau lễ Chúa Nhật">
                            @error('admin_note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="hideRegistrationModal">Đóng</button>
                        @if ($isPending)
                            <button type="button" class="btn btn-danger" wire:click="reject"
                                wire:confirm="Từ chối đơn {{ $current->code }}?" wire:loading.attr="disabled" wire:target="reject, approve">
                                <i class="fa fa-times"></i> Từ chối
                            </button>
                            @if ($current->type === 'new' || $current->user)
                                <button type="button" class="btn btn-success" wire:click="approve"
                                    wire:confirm="{{ $current->type === 'new' ? 'Tạo tài khoản thiếu nhi từ đơn này?' : ($invalidate_old_card ? 'Duyệt cấp lại thẻ? Thẻ cũ sẽ không dùng được nữa.' : 'Duyệt cấp lại thẻ? Thẻ cũ vẫn dùng được.') }}"
                                    wire:loading.attr="disabled" wire:target="reject, approve">
                                    <span wire:loading.remove wire:target="approve"><i class="fa fa-check"></i> Duyệt</span>
                                    <span wire:loading wire:target="approve"><span class="spinner-border spinner-border-sm"></span> Đang xử lý...</span>
                                </button>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- End Modal --}}
</div>
