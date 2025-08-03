<div>
    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
        <div class="row">
            {{-- thông báo --}}
            <div class="col-md-6">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h4 text-danger">
                            Bảng thông báo
                        </div>
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Ngày</th>
                                <th class="text-center">Tiêu đề</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($notices as $notice)
                                <tr>
                                    <td class="text-center">
                                        {{ $notice->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $notice->title }}
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:;" wire:click='viewNotice({{ $notice->id }})'
                                            title="Xem chi tiết"><span class="ti-eye"></span></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Chưa có thông báo nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- kết thúc thông báo --}}
            {{-- Bảng xếp hạng --}}
            <div class="col-md-6">
                <div class="clearfix mt-2">
                    <div class="pull-left">
                        <div class="h4 text-danger">
                            Bảng xếp hạng
                        </div>
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">Hạng</th>
                                <th class="text-center">Họ và tên</th>
                                <th class="text-center">Điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topUsers as $index => $user)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $user->SimpleName }}</td>
                                    <td class="text-center">{{ $user->total_score }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Kết thúc Bảng xếp hạng --}}
        </div>
    </div>

    {{-- Modal --}}

    <div wire:ignore.self class="modal fade" id="viewNoticeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-{{ $notice_type }} text-white">
                <div class="modal-body text-center">
                    <h2 class="text-white mb-3">
                        <i class="fa fa-exclamation-triangle"></i> {{ $notice_title }}
                    </h2>
                    <div class="row mb-2">
                        <div class="col-md-4">
                            <img src="/images/site/Notice.png" alt="{{ $notice_title }}" class="img-fluid">
                        </div>
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="card-box w-100 d-flex align-items-center justify-content-center"
                                style="min-height: 200px; background: white; border-radius: 8px;">
                                <h4 class="text-{{ $notice_type }} mb-0">
                                    {{ $notice_content }}
                                </h4>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        Đã hiểu
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- End Modal openAllRankingModal --}}

</div>
