<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h4 text-blue">
                            Quản Lý Thông Báo
                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addNotice()" class="btn btn-primary btn-sm mr-2">
                            Thêm Thông Báo
                        </a>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Tiêu đề</th>
                                <th class="text-center">Nhóm đối tượng</th>
                                <th class="text-center">Ngành</th>
                                <th class="text-center">Công khai</th>
                                <th class="text-center">Popup</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($listNotices as $notice)
                                <tr wire:key="notice-{{ $notice->id }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $notice->title }}</td>
                                    <td class="text-start">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            @foreach ($notice->applicable_roles as $role)
                                                <span class="badge">{{ $role }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            @foreach ($notice->applicable_sectors as $sector)
                                                <span class="badge">{{ $sector }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                id="notice_active_{{ $notice->id }}"
                                                wire:click="toggleNoticeActive({{ $notice->id }})"
                                                @checked($notice->is_active)>
                                            <label class="custom-control-label"
                                                for="notice_active_{{ $notice->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                id="notice_popup_{{ $notice->id }}"
                                                wire:click="toggleNoticePopup({{ $notice->id }})"
                                                @checked($notice->is_popup)>
                                            <label class="custom-control-label"
                                                for="notice_popup_{{ $notice->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editNotice({{ $notice->id }})">
                                                    <i class="dw dw-edit2"></i> Chỉnh sửa
                                                </a>
                                                <a class="dropdown-item text-danger" href="javascript:;"
                                                    wire:click="deleteNotice({{ $notice->id }})">
                                                    <i class="dw dw-delete-3"></i> Xóa
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có thông báo nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="notice_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateNoticeModal ? 'Cập nhật thông báo' : 'Thêm thông báo' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateNoticeModal)
                        <input type="hidden" wire:model="notice_id">
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <label for="notice_type"><b>Loại thông báo <span class="text-danger">*</span></b></label>
                            <select id="notice_type" class="custom-select" wire:model="notice_type">
                                <option value="" selected>Chọn loại...</option>
                                <option class="bg-info text-white" value="info">Thông tin</option>
                                <option class="bg-warning text-white" value="warning">Cảnh báo</option>
                                <option class="bg-danger text-white" value="danger">Khẩn cấp</option>
                            </select>
                            @error('notice_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="notice_title"><b>Tiêu đề <span class="text-danger">*</span></b></label>
                                <input type="text" id="notice_title" class="form-control" wire:model="notice_title"
                                    placeholder="Tiêu đề thông báo" autocomplete="on">
                                @error('notice_title')
                                    <span class="text-danger ml-1">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notice_content"><b>Nội dung thông báo <span
                                            class="text-danger">*</span></b></label>
                                <textarea wire:model="notice_content" id="notice_content" class="form-control" autocomplete="on"
                                    placeholder="Nôi dung thông báo" rows="4">
                            </textarea>
                                @error('notice_content')
                                    <span class="text-danger ml-1">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div wire:ignore class="form-group">
                                <label for="applicable_roles"><b>Nhóm đối tượng</b></label>
                                <select class="selectpicker form-control" id="applicable_roles"
                                    wire:model="applicable_roles" data-size="5" data-style="btn-outline-secondary"
                                    multiple data-actions-box="true" data-selected-text-format="count"
                                    data-live-search="true" data-live-search-placeholder="Tìm kiếm nhóm đối tượng..."
                                    data-none-results-text="Không tìm thấy nhóm đối tượng"
                                    data-none-selected-text="Chọn nhóm đối tượng" data-select-all-text="Chọn tất cả"
                                    data-deselect-all-text="Bỏ tất cả">
                                    @forelse ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @empty
                                        <option value="">Không có nhóm đối tượng</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div wire:ignore class="form-group">
                                <label for="applicable_sectors"><b>Chọn ngành</b></label>
                                <select class="selectpicker form-control" id="applicable_sectors"
                                    wire:model="applicable_sectors" data-size="5" data-style="btn-outline-secondary"
                                    multiple data-actions-box="true" data-selected-text-format="count"
                                    data-live-search="true" data-live-search-placeholder="Tìm kiếm ngành..."
                                    data-none-results-text="Không tìm thấy ngành" data-none-selected-text="Chọn ngành"
                                    data-select-all-text="Chọn tất cả" data-deselect-all-text="Bỏ tất cả">
                                    @forelse ($sectors as $sector)
                                        <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                    @empty
                                        <option value="">Không có ngành</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="notice_start_at"><strong>Bắt đầu <span
                                            class="text-danger">*</span></strong></label>
                                <input type="date" class="form-control" id="notice_start_at"
                                    wire:model="notice_start_at">
                                @error('notice_start_at')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="notice_end_at"><strong>Kết thúc<span
                                            class="text-danger">*</span></strong></label>
                                <input type="date" class="form-control" id="notice_end_at"
                                    wire:model="notice_end_at">
                                @error('notice_end_at')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="notice_is_active"
                                        wire:model="notice_is_active">
                                    <label class="custom-control-label" for="notice_is_active"><b>Công
                                            khai</b></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="notice_is_popup"
                                        wire:model="notice_is_popup">
                                    <label class="custom-control-label" for="notice_is_popup"><b>Thông báo
                                            đẩy</b></label>
                                </div>
                            </div>
                        </div>

                    </div>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdateNoticeModal ? 'Lưu thay đổi' : 'Thêm thông báo' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
