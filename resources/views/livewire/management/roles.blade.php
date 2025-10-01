<div>

    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div class="pull-left">
                        <div class="h4 text-blue ml-3">
                            Chức vụ chính
                        </div>
                    </div>
                    <div class="pull-right">
                        <a href="javascript:;" wire:click="addRole()" class="btn btn-primary btn-sm mr-2">Thêm chức vụ</a>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table id="" class="table table-borderless table-striped table-hover ">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>STT</th>
                                <th>Chức vụ</th>
                                <th class="text-center">Mô tả</th>
                                <th class="text-nowrap text-center">Cấu hình</th>
                                <th class="text-nowrap text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="sortable_role">
                            @forelse ($roles as $role)
                                <tr data-index="{{ $role->id }}" data-ordering="{{ $role->ordering }}">
                                    <td>{{ $role->ordering }}</td>
                                    <td class="text-nowrap">{{ $role->name }}</td>
                                    <td>{{ $role->permissions->pluck('display_name')->implode(', ') }}</td>
                                    <td class="text-center">
                                        @if ($role->type == 'System')
                                            <span class="badge badge-success">Hệ thống</span>
                                        @else
                                            <span class="badge badge-danger">Tùy chỉnh</span>
                                        @endif
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="editRole({{ $role->id }})"><i
                                                        class="dw dw-edit2"></i>
                                                    Sửa</a>
                                                <a class="dropdown-item" href="javascript:;"
                                                    wire:click="deleteRole({{ $role->id }})"><i
                                                        class="dw dw-delete-3"></i>
                                                    Xóa</a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="4">
                                        <span class="text-danger ">Không có danh sách chức vụ!</span>
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

    <div wire:ignore.self class="modal fade" id="role_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        {{ $isUpdateRoleMode ? 'Cập nhật chức vụ' : 'Thêm chức vụ' }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    @if ($isUpdateRoleMode)
                        <input type="hidden" wire:model="role_id">
                    @endif

                    <div class="form-group">
                        <label for="role_name"><b>Tên chức vụ</b></label>
                        <input type="text" id="role_name" class="form-control" wire:model="role_name"
                            placeholder="Điền tên chức vụ" autocomplete="off" {{ $isSystem }}>
                        @error('role_name')
                            <span class="text-danger ml-1">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    <div class="form-group">
                        <label for="role_description"><b>Mô tả chức vụ</b></label>
                        <textarea wire:model="role_description" id="role_description" class="form-control" placeholder="Mô tả về chức vụ trên"
                            rows="4">
                        </textarea>
                        @error('role_description')
                            <span class="text-danger ml-1">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div wire:ignore class="row">
                        <div class="col mt-2">
                            <label for="Permissions"><b>Permissions</b></label>
                            <select class="selectpicker form-control" id="Permissions" wire:model="selected_permissions"
                                data-size="5" data-style="btn-outline-warning" multiple data-actions-box="true"
                                data-selected-text-format="count">
                                @forelse ($permissions as $permission)
                                    <option value="{{ $permission->id }}">{{ $permission->display_name }}</option>
                                @empty
                                    <option value="">No Permissions</option>
                                @endforelse
                            </select>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    @if ($hasChanges)
                        <button type="submit" class="btn btn-primary">
                            {{ $isUpdateRoleMode ? 'Lưu thay đổi' : 'Thêm chức vụ' }}
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

</div>
