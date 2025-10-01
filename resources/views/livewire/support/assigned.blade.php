<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Quản Lý</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Quản lý góp ý và khiếu nại
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h4 text-blue">
                            Quản lý phân công giải quyết
                        </div>

                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addComplaint()" class="btn btn-primary btn-sm mr-2">
                            Thêm khiếu nại
                        </a>
                    </div>
                </div>

                <div class="tab">
                    <ul class="nav nav-pills" role="tablist">

                        <li class="nav-item">
                            <a wire:click="selectTab('tabMain')"
                                class="nav-link text-blue {{ $tab === 'tabMain' ? 'active' : '' }}" data-toggle="tab"
                                href="#tabMain" role="tab" aria-selected="true">Bảng chính</a>
                        </li>

                        <li class="nav-item">
                            <a wire:click="selectTab('tabResolved')"
                                class="nav-link text-blue {{ $tab === 'tabResolved' ? 'active' : '' }}"
                                data-toggle="tab" href="#tabResolved" role="tab" aria-selected="true">Đã xử lý</a>
                        </li>

                        <li class="nav-item">
                            <a wire:click="selectTab('tabCancel')"
                                class="nav-link text-blue {{ $tab === 'tabCancel' ? 'active' : '' }}" data-toggle="tab"
                                href="#tabCancel" role="tab" aria-selected="true">Đã hủy</a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade {{ $tab === 'tabMain' ? 'active show' : '' }}" id="tabMain"
                            role="tabpanel">

                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Người gửi</th>
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Thời gian</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($listPendingAndInProgress as $complaint)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $complaint->user->SimpleName }}</td>
                                                <td class="text-center">{{ $complaint->title }}</td>
                                                <td class="text-center">{{ $complaint->formatted_created_at }}</td>
                                                <td class="text-center">
                                                    @if ($complaint->status === 'pending')
                                                        <span class="badge badge-danger">Chờ xử lý</span>
                                                    @elseif ($complaint->status === 'in_progress')
                                                        <span class="badge badge-warning">Đã tiếp nhận</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:;"
                                                        wire:click="viewSupport({{ $complaint->id }})"
                                                        class="btn btn-primary btn-sm mr-2" wire:loading.attr="disabled"
                                                        wire:target="viewSupport({{ $complaint->id }})">

                                                        <span wire:loading.remove
                                                            wire:target="viewSupport({{ $complaint->id }})">
                                                            <i class="fa fa-edit"></i> Xem chi tiết
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="viewSupport({{ $complaint->id }})">
                                                            <span class="spinner-border spinner-border-sm mr-1"
                                                                role="status" aria-hidden="true"></span>
                                                            Đang tải...
                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>

                            </div>

                        </div>
                        <div class="tab-pane fade {{ $tab === 'tabResolved' ? 'active show' : '' }}" id="tabResolved"
                            role="tabpanel">

                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Hoàn thành lúc</th>
                                            <th class="text-center">Người xử lý</th>
                                            <th class="text-center">Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($listResolved as $feedback)
                                            <tr>
                                                <td class="text-center text-primary">{{ $loop->iteration }}</td>
                                                <td class="text-center text-primary">{{ $feedback->title }}</td>
                                                <td class="text-center text-primary">
                                                    {{ $feedback->formatted_resolved_at }}</td>
                                                <td class="text-center text-primary">
                                                    {{ $feedback->handler->SimpleName }}</td>
                                                <td class="text-center text-primary">{{ $feedback->note }}</td>
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
                        <div class="tab-pane fade {{ $tab === 'tabCancel' ? 'active show' : '' }}" id="tabCancel"
                            role="tabpanel">

                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Đã hủy lúc</th>
                                            <th class="text-center">Người hủy</th>
                                            <th class="text-center">Lý do hủy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($listCanceled as $feedback)
                                            <tr>
                                                <td class="text-center text-danger">{{ $loop->iteration }}</td>
                                                <td class="text-center text-danger">{{ $feedback->title }}</td>
                                                <td class="text-center text-danger">
                                                    {{ $feedback->formatted_resolved_at }}</td>
                                                <td class="text-center text-danger">
                                                    {{ $feedback->handler->SimpleName }}</td>
                                                <td class="text-center text-danger">{{ $feedback->note }}</td>
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

            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div wire:ignore.self class="modal fade" id="support_modal" tabindex="-1" schedule="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Xem hộp thư
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" wire:model="support_id">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="support_sender"><strong>Người gửi</strong></label>
                                <input type="text" class="form-control" id="support_sender"
                                    wire:model="support_sender" readonly>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="support_title"><strong>Tiêu đề</strong></label>
                                <input type="text" class="form-control" id="support_title"
                                    wire:model="support_title" readonly>
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="support_content" class="font-weight-bold">
                                    Mô tả
                                </label>
                                <textarea wire:model="support_content" id="support_content" class="form-control" rows="5"
                                    placeholder="Nhập nội dung..." readonly></textarea>
                            </div>
                        </div>

                        @if (!empty($support_picture))

                            @foreach ($support_picture as $img)
                                <div class="col-md-3 col-sm-4 col-6 mb-2 d-flex justify-content-center">
                                    <a href="{{ asset($support_picture_path . $img) }}"
                                        data-lightbox="support-images" data-title="Ảnh hỗ trợ" target="_blank">
                                        <img src="{{ asset($support_picture_path . $img) }}" alt="Ảnh hỗ trợ"
                                            style="width: 140px; height: 100px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="col-md-12">
                                <p class="text-muted">Không có hình</p>
                            </div>
                        @endif

                        <div wire:ignore class="col-md-12 col-sm-12 mt-2 form-group">
                            <label for="receiver"><strong>Người xử lý<span class="text-danger">
                                        *</span></strong></label>
                            <select class="selectpicker form-control" id="receiver" data-size="6"
                                data-style="btn-outline-primary" data-live-search-placeholder="Tìm kiếm..."
                                data-live-search="true" data-none-results-text="Không tìm thấy"
                                data-none-selected-text="Chọn người nhận" wire:model="support_receiver">
                                @forelse ($receiver_list as $user)
                                    <option value="{{ $user->id }}">{{ $user->lastName }} {{ $user->name }}
                                    </option>
                                @empty
                                    <option value="">Không có dữ liệu</option>
                                @endforelse

                            </select>
                        </div>

                        <div class="col-md-12 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="support_note"><strong>Ghi chú</strong></label>
                                <input type="text" class="form-control @error('support_note') is-invalid @enderror"
                                    id="support_note" wire:model="support_note">
                                @error('support_note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="rejectSupport"
                        wire:loading.attr="disabled" wire:target="rejectSupport">
                        <span wire:loading.remove wire:target="rejectSupport">Từ chối</span>
                        <span wire:loading wire:target="rejectSupport">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Đang tải...
                        </span>
                    </button>

                    @if ($support_status === 'pending')
                        <button type="submit" class="btn btn-primary">
                            Tiếp nhận
                        </button>
                    @endif

                    <button type="button" class="btn btn-success" wire:click="resolveSupport"
                        wire:loading.attr="disabled" wire:target="resolveSupport">
                        <span wire:loading.remove wire:target="resolveSupport">Đã xử lý</span>
                        <span wire:loading wire:target="resolveSupport">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Đang tải...
                        </span>
                    </button>

                </div>

            </form>
        </div>
    </div>
    {{-- End Modal --}}

</div>
