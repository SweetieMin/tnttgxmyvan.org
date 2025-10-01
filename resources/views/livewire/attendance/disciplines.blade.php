<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Kỷ luật</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Ghi nhận các lỗi kỷ luật trong đoàn
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">

                <div class="row mt-4">
                    <div class="col-md-4 form-group">
                        <input type="text" class="form-control search-input outline-primary" placeholder="Tìm tên"
                            id="search" wire:model.live="search">
                    </div>

                    <div wire:ignore class="col-md-4 form-group">
                        <select class="selectpicker form-control" id="role" data-size="5"
                            data-style="btn-outline-danger" wire:model="role"
                            data-actions-box="true" data-select-all-text="Chọn tất cả"
                            data-deselect-all-text="Bỏ tất cả" data-none-selected-text="Chọn chức vụ" multiple>
                            @forelse ($listRoles as $listRole)
                                <option value="{{ $listRole->id }}">{{ $listRole->name }}</option>
                            @empty
                                <option value="">Không có chức vụ</option>
                            @endforelse
                        </select>
                    </div>


                    <!-- Dropdown for selecting Sector -->
                    <div wire:ignore class="col-md-4 form-group">
                        <select class="selectpicker form-control" id="sector" data-size="5"
                            data-style="btn-outline-warning" wire:model="sector"
                            data-actions-box="true" data-select-all-text="Chọn tất cả"
                            data-deselect-all-text="Bỏ tất cả" data-none-selected-text="Chọn cấp" multiple>
                            @forelse ($listSectors as $listSector)
                                <option value="{{ $listSector->id }}">{{ $listSector->name }}</option>
                            @empty
                                <option value="">Không có ngành</option>
                            @endforelse
                        </select>
                    </div>

                </div>

                <div class="table-responsive mt-1">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center ">Hình đại diện</th>
                                <th class="text-center">Họ và tên</th>
                                <th class="text-center d-none d-md-table-cell">Ngành</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($listChildren as $user)
                                <tr>
                                    <td class="text-center">
                                        {{ ($listChildren->currentPage() - 1) * $listChildren->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="text-center ">
                                        <img src="{{ $user->picture }}" alt="Ảnh đại diện của {{ $user->SimpleName }}"
                                            class="img-fluid rounded-circle"
                                            style="max-width: 50px; max-height: 50px; object-fit: cover;">
                                    </td>
                                    <td class="text-center">{{ $user->SimpleName }}</td>
                                    <td class="text-center d-none d-md-table-cell">
                                        @php
                                            $roleName = $user->roles->first()?->name;
                                            $sectorName = $user->sectors->first()?->name;
                                        @endphp

                                        @if ($sectorName)
                                            {{ $roleName }} - {{ $sectorName }}
                                        @else
                                            {{ $roleName ?? 'Không rõ' }}
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                href="#" role="button" data-toggle="dropdown">
                                                <i class="dw dw-more"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                <a class="dropdown-item text-danger" href="javascript:;"
                                                    wire:click="recordUser({{ $user->id }})">
                                                    <i class="bi bi-clipboard-x"></i> Ghi nhận
                                                </a>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-block mt-1 text-center">
                    {{ $listChildren->links('livewire::bootstrap') }}
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="record_modal" tabindex="-1" role="dialog"
        aria-labelledby="locationModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="locationModalLabel">
                        Xác nhận kỷ luật
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        ×
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" wire:model="user_id">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_holy">Tên Thánh</label>
                                <input type="user_holy" wire:model="user_holy" id="user_holy" class="form-control"
                                    autocomplete="off" value="{{ $user_holy }}" readonly>

                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="user_name">Họ và tên</label>
                                <input type="user_name" wire:model="user_name" id="user_name" class="form-control"
                                    autocomplete="off" value="{{ $user_name }}" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_sector">Chức vụ</label>
                                <input type="user_sector" wire:model="user_sector" id="user_sector"
                                    class="form-control" autocomplete="off" value="{{ $user_sector }}" readonly>
                            </div>
                        </div>

                        {{-- Dropdown cho Huynh Trưởng / Scouter --}}
                        @if ($showDisciplinesScouter)
                            <div class="col-md-8">
                                <div wire:ignore class="form-group">
                                    <label for="user_discipline_scouter"><b>Phạm nội quy (Huynh Trưởng)</b></label>
                                    <select class="selectpicker form-control" id="user_discipline_scouter"
                                        wire:model="selected_permissions" data-size="5"
                                        data-style="btn-outline-warning" data-live-search="true"
                                        data-none-selected-text="Chọn lỗi vi phạm">
                                        @forelse ($disciplinesModal as $item)
                                            <option value="{{ $item->id }}" data-toggle="tooltip"
                                                data-placement="top" title="{{ $item->description }}">
                                                {{ \Illuminate\Support\Str::limit($item->description, 40) }}
                                            </option>
                                        @empty
                                            <option value="">Không tìm thấy</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        @endif

                        {{-- Dropdown cho Thiếu Nhi --}}
                        @if ($showDisciplinesChildren)
                            <div class="col-md-8">
                                <div wire:ignore class="form-group">
                                    <label for="user_discipline_children"><b>Phạm nội quy (Thiếu Nhi)</b></label>
                                    <select class="selectpicker form-control" id="user_discipline_children"
                                        wire:model="selected_permissions" data-size="5"
                                        data-style="btn-outline-warning" data-live-search="true"
                                        data-none-selected-text="Chọn lỗi vi phạm">
                                        @forelse ($disciplinesModalChildren as $item)
                                            <option value="{{ $item->id }}">
                                                {{ \Illuminate\Support\Str::limit($item->description, 40) }}
                                            </option>
                                        @empty
                                            <option value="">Không tìm thấy</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        @endif



                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_discipline_name">Lỗi vi phạm</label>
                                <input type="user_discipline_name" wire:model="user_discipline_name"
                                    id="user_discipline_name" class="form-control" autocomplete="off">
                                @error('user_discipline_name')
                                    <span class="text-danger ml-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="user_note">Ghi chú</label>
                                <input type="user_note" wire:model="user_note" id="user_note" class="form-control"
                                    autocomplete="off">
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Đóng
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Xác nhận
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
