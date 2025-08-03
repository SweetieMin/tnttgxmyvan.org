<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Hộp thư</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Hộp thư khiếu nại
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
                            <span class="text-danger">
                                <small><i>Khiếu nại của bạn giúp chúng mình hoàn thiện hơn mỗi ngày. Đừng ngần ngại chia
                                        sẻ – thông tin của bạn luôn được bảo mật tuyệt đối.</i></small>
                            </span>
                        </div>

                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addComplaint" class="btn btn-success btn-sm mr-2"
                            wire:loading.attr="disabled">

                            <span wire:loading.remove wire:target="addComplaint">Thêm khiếu nại</span>
                            <span wire:loading wire:target="addComplaint">
                                <span class="spinner-border spinner-border-sm mr-1" role="status"
                                    aria-hidden="true"></span>
                                Đang tải...
                            </span>
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
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Thời gian</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($listComplaintsPendingAndInProgress as $complaint)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $complaint->title }}</td>
                                                <td class="text-center">{{ $complaint->formatted_created_at }}</td>
                                                <td class="text-center">
                                                    @if ($complaint->status === 'pending')
                                                        <span class="badge badge-warning">Chờ xử lý</span>
                                                    @elseif ($complaint->status === 'in_progress')
                                                        <span class="badge badge-success">Đang xử lý</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($complaint->status === 'pending')
                                                        <div class="dropdown">
                                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                                href="#" role="button" data-toggle="dropdown">
                                                                <i class="dw dw-more"></i>
                                                            </a>
                                                            <div
                                                                class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                                <a class="dropdown-item text-danger" href="javascript:;"
                                                                    wire:click="deleteComplaint({{ $complaint->id }})"><i
                                                                        class="dw dw-delete-3 "></i>
                                                                    Xóa</a>
                                                            </div>
                                                        </div>
                                                    @elseif ($complaint->status === 'in_progress')
                                                        {{ $complaint->note }}
                                                    @endif
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
                                        @forelse ($listComplaintsResolved as $feedback)
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
                                        @forelse ($listComplaintsCanceled as $feedback)
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
    <div wire:ignore.self class="modal fade" id="complaint_modal" tabindex="-1" schedule="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form wire:submit.prevent="{{ $isUpdateComplaintMode ? 'updateComplaint()' : 'createComplaint()' }}"
                class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateComplaintMode ? 'Cập nhật' : 'Thêm khiếu nại' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateComplaintMode)
                        <input type="hidden" wire:model="complaint_id">
                    @endif

                    <div class="row">

                        <div class="col-md-12 col-sm-12 mt-2">
                            <div class="form-group">
                                <label for="complaint_title"><strong>Tiêu đề<span
                                            class="text-danger">*</span></strong></label>
                                <input type="text"
                                    class="form-control @error('complaint_title') is-invalid @enderror"
                                    id="complaint_title" wire:model="complaint_title">
                                @error('complaint_title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="complaint_content" class="font-weight-bold">
                                    Mô tả <span class="text-danger">*</span>
                                </label>
                                <textarea wire:model="complaint_content" id="complaint_content"
                                    class="form-control @error('complaint_content') is-invalid @enderror" rows="5"
                                    placeholder="Nhập nội dung..."></textarea>
                                @error('complaint_content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mt-2">
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="customCheck2"
                                    wire:model='isHideUser'>
                                <label class="custom-control-label" for="customCheck2">Ẩn thông tin cá
                                    nhân</label>
                            </div>
                        </div>

                        <div wire:ignore class="col-md-12 mt-2">
                            <div class="form-group">
                                <label for="" class="font-weight-bold">
                                    Hình ảnh (tuỳ chọn) <span class="text-danger">* tối đa 4 ảnh/lần</span>
                                </label>
                                <input type="hidden" id="uploaded_files" wire:model="uploadedFiles">
                                <div class="dropzone dz-clickable" id="complaint_picture">
                                    <div class="dz-default dz-message">
                                        <span>Nhấn hoặc kéo thả hình vào đây</span>
                                    </div>
                                </div>
                                <span id="messageDropzone" class="text-danger"></span>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                        wire:target="{{ $isUpdateComplaintMode ? 'updateComplaint()' : 'createComplaint()' }}">
                        <span wire:loading.remove wire:target="{{ $isUpdateComplaintMode ? 'updateComplaint()' : 'createComplaint()' }}">
                            {{ $isUpdateComplaintMode ? 'Lưu thay đổi' : 'Thêm khiếu nại' }}
                        </span>
                        <span wire:loading wire:target="{{ $isUpdateComplaintMode ? 'updateComplaint()' : 'createComplaint()' }}">
                            <span class="spinner-border spinner-border-sm mr-1" role="status"
                                aria-hidden="true"></span>
                            Đang tải...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>
    {{-- End Modal --}}
</div>
