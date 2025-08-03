<div>

    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <div class="h4 text-blue ml-3">
                            Lớp Giáo Lý
                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addCourse()" class="btn btn-primary btn-sm mr-2">Thêm Lớp</a>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table id="" class="table table-borderless table-striped table-hover ">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>STT</th>
                                <th>Tên lớp</th>
                                <th class="text-center">Mô tả</th>
                                <th class="text-nowrap text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="sortable_course">
                            @forelse ($courses as $course)
                                <tr data-index="{{ $course->id }}" data-ordering="{{ $course->ordering }}">
                                    <td>{{ $course->ordering }}</td>
                                    <td class="text-nowrap">{{ $course->name }}</td>
                                    <td>{{ $course->description }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editCourse({{ $course->id }})"><i
                                                        class="dw dw-edit2"></i>
                                                    Sửa</a>
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="deleteCourse({{ $course->id }})"><i
                                                        class="dw dw-delete-3"></i>
                                                    Xóa</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="4">
                                        <span class="text-danger ">Không có danh sách lớp Giáo Lý!</span>
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

    <div wire:ignore.self class="modal fade" id="course_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" wire:submit="{{ $isUpdateCourseMode ? 'updateCourse()' : 'createCourse()' }}">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateCourseMode ? 'Cập nhật lớp Giáo Lý' : 'Thêm lớp Giáo Lý' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateCourseMode)
                        <input type="hidden" wire:model="course_id">
                    @endif

                    <div class="form-group">
                        <label for="course_name"><b>Tên lớp Giáo Lý</b></label>
                        <input type="text" id="course_name" class="form-control" wire:model="course_name"
                            placeholder="Điền tên lớp Giáo Lý" autocomplete="off">
                        @error('course_name')
                            <span class="text-danger ml-1">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    <div class="form-group">
                        <label for="course_description"><b>Mô tả lớp Giáo Lý</b></label>
                        <textarea wire:model="course_description" id="course_description" class="form-control" placeholder="Mô tả về lớp Giáo Lý trên"
                            rows="4">
                     </textarea>
                        @error('course_description')
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
                        {{ $isUpdateCourseMode ? 'Lưu thay đổi' : 'Thêm lớp Giáo Lý' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
