<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Bảng điểm</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Bảng thành tích cá nhân
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="col-md-6 col-sm-12 text-right">
                <div class="dropdown">
                    <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                        Khóa 24-25
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Khóa 25-26</a>

                    </div>
                </div>
            </div>


        </div>
    </div>
    {{-- Bảng hiện danh sách thiếu nhi --}}
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h4 text-blue">
                            {{ $user_holy_name }} - {{ $user_full_name }}
                        </div>
                        <!-- spell-check: enable -->
                    </div>
                    <div class="pull-right mr-2">
                        <div class="d-flex flex-column align-items-center me-3">
                            <div class="h5 text-primary mb-2">Tổng điểm</div>
                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center shadow"
                                style="width: 90px; height: 90px; font-size: 2rem; font-weight: bold;">
                                {{ $totalScore }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-info text-white">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Nội Dung</th>
                                <th class="text-center">Số lần</th>
                                <th class="text-center">Số điểm</th>
                                <th class="text-center">Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center bg-warning"><strong>HẠNG MỤC KHEN THƯỞNG</strong></td>
                            </tr>



                            @forelse ($rewards as $record)
                                <tr>
                                    <td class="text-center">{{ $record['stt'] }}</td>
                                    <td>{{ $record['noi_dung'] }}</td>
                                    <td class="text-center">{{ $record['so_lan'] }}</td>
                                    <td class="text-center">{{ $record['so_diem'] }}</td>
                                    <td class="text-center">
                                        @if (!empty($record['so_lan']))
                                            <a class="text-primary fst-italic" href="#"
                                                wire:click="viewDetail({{ $record['ghi_chu'] }})"><u><i>Xem chi
                                                    tiết</i></u></a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Chưa có dữ liệu</td>
                                </tr>
                            @endforelse


                            <tr>
                                <td colspan="5" class="text-center bg-warning"><strong>HẠNG MỤC KỶ LUẬT</strong></td>
                            </tr>

                            @forelse ($disciplines as $record)
                                <tr>
                                    <td class="text-center">{{ $record['stt'] }}</td>
                                    <td>{{ $record['noi_dung'] }}</td>
                                    <td class="text-center">{{ $record['so_lan'] }}</td>
                                    <td class="text-center">{{ $record['so_diem'] }}</td>
                                    <td class="text-center">
                                        @if (!empty($record['so_lan']))
                                            <a class="text-primary" href="#"
                                                wire:click="viewDetail({{ $record['ghi_chu'] }})"><u><i>Xem chi
                                                    tiết</i></u></a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Chưa có dữ liệu</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- Kết thúc Bảng hiện danh sách thiếu nhi --}}
    <div class="modal fade" id="viewDetailModal" tabindex="-1" role="dialogs aria-labelledby="viewDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailModalLabel">Chi tiết</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Hạng mục</th>
                                    <th class="text-center">Ngày</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listRecord as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-center">{{ $item->created_at->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
