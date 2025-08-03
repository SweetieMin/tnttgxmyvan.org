<div>

    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <div class="h4 text-blue ml-3">
                            Ngành sinh hoạt
                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addSector()" class="btn btn-primary btn-sm mr-2">Thêm ngành</a>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table id="" class="table table-borderless table-striped table-hover ">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>STT</th>
                                <th>Tên ngành</th>
                                <th class="text-center">Mô tả</th>
                                <th class="text-nowrap text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="sortable_sector">
                            @forelse ($sectors as $sector)
                                <tr data-index="{{ $sector->id }}" data-ordering="{{ $sector->ordering }}">
                                    <td>{{ $sector->ordering }}</td>
                                    <td class="text-nowrap">{{ $sector->name }}</td>
                                    <td>{{ $sector->description }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editSector({{ $sector->id }})"><i
                                                        class="dw dw-edit2"></i>
                                                    Sửa</a>
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="deleteSector({{ $sector->id }})"><i
                                                        class="dw dw-delete-3"></i>
                                                    Xóa</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="4">
                                        <span class="text-danger ">Không có danh sách ngành!</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}

    <div wire:ignore.self class="modal fade" id="sector_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" wire:submit="{{ $isUpdateSectorMode ? 'updateSector()' : 'createSector()' }}">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateSectorMode ? 'Cập nhật ngành' : 'Thêm ngành' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateSectorMode)
                        <input type="hidden" wire:model="sector_id">
                    @endif

                    <div class="form-group">
                        <label for="sector_name"><b>Tên chức vụ</b></label>
                        <input type="text" id="sector_name" class="form-control" wire:model="sector_name"
                            placeholder="Điền tên ngành" autocomplete="off">
                        @error('sector_name')
                            <span class="text-danger ml-1">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    <div class="form-group">
                        <label for="sector_description"><b>Mô tả ngành sinh hoạt</b></label>
                        <textarea wire:model="sector_description" id="sector_description" class="form-control" placeholder="Mô tả về ngành trên"
                            rows="4">
                     </textarea>
                        @error('sector_description')
                            <span class="text-danger ml-1">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdateSectorMode ? 'Lưu thay đổi' : 'Thêm ngành' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
