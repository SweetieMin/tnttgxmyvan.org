<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <div class="h4 text-blue ml-3">
                            Câu Kinh Thánh
                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addBible()" class="btn btn-primary btn-sm mr-2">Thêm câu Kinh
                            Thánh</a>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table id="" class="table table-borderless table-striped table-hover ">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>STT</th>
                                <th class="text-center">Nội dung</th>
                                <th class="text-nowrap text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="sortable_bible">
                            @forelse ($bibles as $bible)
                                <tr data-index="{{ $bible->id }}" data-ordering="{{ $bible->ordering }}">
                                    <td class="text-center">{{ $bible->ordering }}</td>
                                    <td >{{ $bible->bible }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editBible({{ $bible->id }})"><i
                                                        class="dw dw-edit2"></i>
                                                    Sửa</a>
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="deleteBible({{ $bible->id }})"><i
                                                        class="dw dw-delete-3"></i>
                                                    Xóa</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="4">
                                        <span class="text-danger ">Không có danh sách Kinh Thánh!</span>
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

    <div wire:ignore.self class="modal fade" id="bible_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" wire:submit="{{ $isUpdateBibleMode ? 'updateBible()' : 'createBible()' }}">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateBibleMode ? 'Cập nhật câu Kinh Thánh' : 'Thêm câu Kinh Thánh' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateBibleMode)
                        <input type="hidden" wire:model="bible_id">
                    @endif

                    <div class="form-group">
                        <label for="bible"><b>Nội dung câu Kinh Thánh</b></label>

                        <textarea wire:model="bible" id="bible" cols="4" rows="4" class="form-control"
                            placeholder="Hãy viết các câu Kinh Thánh hay ở đây và đừng quên tác giả nhé..."></textarea>
                        @error('bible')
                            <span class="text-danger ml-1">{{ $message }}</span>
                        @enderror

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{ $isUpdateBibleMode ? 'Lưu thay đổi' : 'Thêm câu Kinh Thánh' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
