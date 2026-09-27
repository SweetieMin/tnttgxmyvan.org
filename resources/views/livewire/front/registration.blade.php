<div class="col-lg-9 col-md-11 mx-auto py-4">
    <div class="card-box pd-20 mb-30">
        <div class="text-center mb-20">
            <h3 class="h3 text-blue mb-1">Đăng ký thiếu nhi</h3>
            <div class="text-muted">Dành cho phụ huynh: đăng ký em mới vào Xứ Đoàn hoặc xin cấp lại thẻ khi em mất thẻ.</div>
        </div>

        <ul class="nav nav-pills nav-fill mb-20" role="tablist">
            <li class="nav-item">
                <a href="javascript:;" wire:click="selectTab('new')" class="nav-link {{ $tab === 'new' ? 'active' : 'text-blue' }}">
                    <i class="fa fa-user-plus"></i> Đăng ký mới
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" wire:click="selectTab('reissue')" class="nav-link {{ $tab === 'reissue' ? 'active' : 'text-blue' }}">
                    <i class="fa fa-id-card"></i> Cấp lại thẻ
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" wire:click="selectTab('status')" class="nav-link {{ $tab === 'status' ? 'active' : 'text-blue' }}">
                    <i class="fa fa-search"></i> Tra cứu hồ sơ
                </a>
            </li>
        </ul>

        @error('form')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        {{-- Ô ẩn chống spam --}}
        <div style="position:absolute; left:-9999px;" aria-hidden="true">
            <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
        </div>

        @if ($submittedCode)
            <div class="text-center py-4">
                <i class="fa fa-check-circle text-success" style="font-size: 64px;"></i>
                <h4 class="h4 mt-3">Đã gửi hồ sơ thành công</h4>
                <p class="mb-2">Mã hồ sơ của bạn:</p>
                <div class="d-inline-block border border-primary rounded px-4 py-2 mb-3">
                    <span class="h3 text-primary mb-0" style="letter-spacing: 2px;">{{ $submittedCode }}</span>
                </div>
                <p class="text-muted mb-4">
                    Vui lòng <strong>chụp màn hình</strong> hoặc ghi lại mã này.<br>
                    Dùng mã hồ sơ và số điện thoại phụ huynh để tra cứu kết quả ở mục <strong>Tra cứu hồ sơ</strong>.
                </p>
                <button type="button" class="btn btn-outline-primary" wire:click="selectTab('status')">
                    <i class="fa fa-search"></i> Tra cứu hồ sơ
                </button>
                <button type="button" class="btn btn-primary" wire:click="selectTab('{{ $tab }}')">
                    Gửi đơn khác
                </button>
            </div>
        @elseif ($tab === 'new')
            {{-- ================= ĐĂNG KÝ MỚI ================= --}}
            <form wire:submit.prevent="submitNew">
                <h5 class="h5 text-blue mb-15"><i class="fa fa-child"></i> Thông tin thiếu nhi</h5>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="holyName">Tên Thánh <span class="text-danger">*</span></label>
                        <input type="text" id="holyName" class="form-control @error('holyName') is-invalid @enderror"
                            wire:model="holyName" placeholder="VD: Maria">
                        @error('holyName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-8 form-group">
                        <label for="fullName">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" id="fullName" class="form-control @error('fullName') is-invalid @enderror"
                            wire:model="fullName" placeholder="VD: Nguyễn Thị Hương">
                        @error('fullName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="birthday">Ngày sinh <span class="text-danger">*</span></label>
                        <input type="date" id="birthday" class="form-control @error('birthday') is-invalid @enderror"
                            wire:model="birthday">
                        @error('birthday') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="phone">Số điện thoại của em <small class="text-muted">(nếu có)</small></label>
                        <input type="tel" id="phone" class="form-control @error('phone') is-invalid @enderror"
                            wire:model="phone" inputmode="numeric">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="address">Địa chỉ <span class="text-danger">*</span></label>
                        <input type="text" id="address" class="form-control @error('address') is-invalid @enderror"
                            wire:model="address" placeholder="Số nhà, đường, giáo họ...">
                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="sector_id">Ngành <small class="text-muted">(nếu biết)</small></label>
                        <select id="sector_id" class="form-control @error('sector_id') is-invalid @enderror" wire:model="sector_id">
                            <option value="">-- Để Xứ Đoàn xếp --</option>
                            @foreach ($listSectors as $sector)
                                <option value="{{ $sector->id }}">{{ $sector->name }}{{ $sector->description ? ' - ' . $sector->description : '' }}</option>
                            @endforeach
                        </select>
                        @error('sector_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="course_id">Lớp giáo lý <small class="text-muted">(nếu biết)</small></label>
                        <select id="course_id" class="form-control @error('course_id') is-invalid @enderror" wire:model="course_id">
                            <option value="">-- Để Xứ Đoàn xếp --</option>
                            @foreach ($listCourses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h5 class="h5 text-blue mb-15 mt-10"><i class="fa fa-users"></i> Thông tin phụ huynh</h5>
                <p class="text-muted small">Cần ít nhất một số điện thoại của cha hoặc mẹ để Xứ Đoàn liên lạc và để tra cứu hồ sơ.</p>
                <div class="row">
                    <div class="col-md-8 form-group">
                        <label for="nameFather">Tên Thánh - Họ và tên Cha</label>
                        <input type="text" id="nameFather" class="form-control @error('nameFather') is-invalid @enderror" wire:model="nameFather">
                        @error('nameFather') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="phoneFather">Số điện thoại Cha</label>
                        <input type="tel" id="phoneFather" class="form-control @error('phoneFather') is-invalid @enderror"
                            wire:model="phoneFather" inputmode="numeric">
                        @error('phoneFather') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-8 form-group">
                        <label for="nameMother">Tên Thánh - Họ và tên Mẹ</label>
                        <input type="text" id="nameMother" class="form-control @error('nameMother') is-invalid @enderror" wire:model="nameMother">
                        @error('nameMother') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="phoneMother">Số điện thoại Mẹ</label>
                        <input type="tel" id="phoneMother" class="form-control @error('phoneMother') is-invalid @enderror"
                            wire:model="phoneMother" inputmode="numeric">
                        @error('phoneMother') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="godParent">Tên Thánh - Họ và tên người đỡ đầu</label>
                        <input type="text" id="godParent" class="form-control @error('godParent') is-invalid @enderror" wire:model="godParent">
                        @error('godParent') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h5 class="h5 text-blue mb-15 mt-10"><i class="fa fa-camera"></i> Ảnh làm thẻ <span class="text-danger">*</span></h5>
                <div class="row">
                    <div class="col-md-8 form-group">
                        <input type="file" id="picture" accept="image/*" class="form-control-file @error('picture') is-invalid @enderror"
                            wire:model="picture">
                        <small class="form-text text-muted">Ảnh chân dung rõ mặt, nền trơn, chụp thẳng. Ảnh sẽ được cắt theo khung 3x4.</small>
                        <div wire:loading wire:target="picture" class="text-primary small mt-1">
                            <span class="spinner-border spinner-border-sm"></span> Đang tải ảnh...
                        </div>
                        @error('picture') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4 text-center">
                        @if ($picture && !$errors->has('picture'))
                            <img src="{{ $picture->temporaryUrl() }}" alt="Ảnh thẻ"
                                style="width: 120px; height: 160px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                        @endif
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="note">Ghi chú <small class="text-muted">(nếu có)</small></label>
                        <textarea id="note" rows="2" class="form-control @error('note') is-invalid @enderror" wire:model="note"
                            placeholder="VD: em đã học giáo lý ở giáo xứ khác..."></textarea>
                        @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="custom-control custom-checkbox mb-20">
                    <input type="checkbox" class="custom-control-input @error('agree') is-invalid @enderror" id="agree" wire:model="agree">
                    <label class="custom-control-label" for="agree">
                        Tôi là phụ huynh / người giám hộ của em và đồng ý để Xứ Đoàn lưu thông tin, ảnh của em để quản lý sinh hoạt và làm thẻ.
                    </label>
                    @error('agree') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block" wire:loading.attr="disabled" wire:target="submitNew, picture">
                    <span wire:loading.remove wire:target="submitNew">Gửi đăng ký</span>
                    <span wire:loading wire:target="submitNew"><span class="spinner-border spinner-border-sm"></span> Đang gửi...</span>
                </button>
            </form>
        @elseif ($tab === 'reissue')
            {{-- ================= CẤP LẠI THẺ ================= --}}
            @if (empty($foundChildren))
                <form wire:submit.prevent="lookupChild">
                    <p class="text-muted">Nhập <strong>mã thiếu nhi</strong> (in trên thẻ, dạng MV...) hoặc <strong>số điện thoại phụ huynh</strong> đã đăng ký với Xứ Đoàn, kèm ngày sinh của em.</p>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="lookup_key">Mã thiếu nhi hoặc SĐT phụ huynh <span class="text-danger">*</span></label>
                            <input type="text" id="lookup_key" class="form-control @error('lookup_key') is-invalid @enderror"
                                wire:model="lookup_key" placeholder="VD: MV01021512 hoặc 0912345678">
                            @error('lookup_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="lookup_birthday">Ngày sinh của em <span class="text-danger">*</span></label>
                            <input type="date" id="lookup_birthday" class="form-control @error('lookup_birthday') is-invalid @enderror"
                                wire:model="lookup_birthday">
                            @error('lookup_birthday') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" wire:loading.attr="disabled" wire:target="lookupChild">
                        <span wire:loading.remove wire:target="lookupChild"><i class="fa fa-search"></i> Tìm thiếu nhi</span>
                        <span wire:loading wire:target="lookupChild"><span class="spinner-border spinner-border-sm"></span> Đang tìm...</span>
                    </button>
                </form>
            @else
                <form wire:submit.prevent="submitReissue">
                    <div class="form-group">
                        <label><strong>Thiếu nhi</strong></label>
                        @foreach ($foundChildren as $id => $maskedName)
                            <div class="custom-control custom-radio">
                                <input type="radio" id="child_{{ $id }}" value="{{ $id }}" class="custom-control-input"
                                    wire:model="selected_user_id">
                                <label class="custom-control-label h5 mb-0" for="child_{{ $id }}">{{ $maskedName }}</label>
                            </div>
                        @endforeach
                        @error('selected_user_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <a href="javascript:;" wire:click="resetLookup" class="small">Không phải em này? Tìm lại</a>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="reason">Lý do <span class="text-danger">*</span></label>
                            <select id="reason" class="form-control @error('reason') is-invalid @enderror" wire:model.live="reason">
                                @foreach ($reasons as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="contact_phone">SĐT phụ huynh để liên hệ <span class="text-danger">*</span></label>
                            <input type="tel" id="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror"
                                wire:model="contact_phone" inputmode="numeric">
                            @error('contact_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-8 form-group">
                            <label for="reissue_picture">
                                Ảnh mới
                                @if ($reason === 'photo')
                                    <span class="text-danger">*</span>
                                @else
                                    <small class="text-muted">(bỏ trống nếu dùng lại ảnh cũ)</small>
                                @endif
                            </label>
                            <input type="file" id="reissue_picture" accept="image/*" class="form-control-file @error('reissue_picture') is-invalid @enderror"
                                wire:model="reissue_picture">
                            <div wire:loading wire:target="reissue_picture" class="text-primary small mt-1">
                                <span class="spinner-border spinner-border-sm"></span> Đang tải ảnh...
                            </div>
                            @error('reissue_picture') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 text-center">
                            @if ($reissue_picture && !$errors->has('reissue_picture'))
                                <img src="{{ $reissue_picture->temporaryUrl() }}" alt="Ảnh thẻ mới"
                                    style="width: 120px; height: 160px; object-fit: cover; border-radius: 6px; border: 1px solid #ccc;">
                            @endif
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="reissue_note">Ghi chú <small class="text-muted">(nếu có)</small></label>
                            <textarea id="reissue_note" rows="2" class="form-control" wire:model="reissue_note"></textarea>
                        </div>
                    </div>

                    <div class="alert alert-warning small">
                        Làm thẻ mới có thu phí, Xứ Đoàn sẽ báo khi nhận thẻ. Trường hợp mất thẻ, Xứ Đoàn có thể khóa thẻ cũ để tránh người khác dùng.
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block" wire:loading.attr="disabled" wire:target="submitReissue, reissue_picture">
                        <span wire:loading.remove wire:target="submitReissue">Gửi yêu cầu cấp lại thẻ</span>
                        <span wire:loading wire:target="submitReissue"><span class="spinner-border spinner-border-sm"></span> Đang gửi...</span>
                    </button>
                </form>
            @endif
        @else
            {{-- ================= TRA CỨU HỒ SƠ ================= --}}
            <form wire:submit.prevent="checkStatus">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="status_code">Mã hồ sơ <span class="text-danger">*</span></label>
                        <input type="text" id="status_code" class="form-control text-uppercase @error('status_code') is-invalid @enderror"
                            wire:model="status_code" placeholder="VD: DK260927-AB12">
                        @error('status_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="status_phone">SĐT phụ huynh đã đăng ký <span class="text-danger">*</span></label>
                        <input type="tel" id="status_phone" class="form-control @error('status_phone') is-invalid @enderror"
                            wire:model="status_phone" inputmode="numeric">
                        @error('status_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block" wire:loading.attr="disabled" wire:target="checkStatus">
                    <span wire:loading.remove wire:target="checkStatus"><i class="fa fa-search"></i> Tra cứu</span>
                    <span wire:loading wire:target="checkStatus"><span class="spinner-border spinner-border-sm"></span> Đang tra cứu...</span>
                </button>
            </form>

            @if ($statusResult)
                @php
                    $badge = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$statusResult['status']] ?? 'secondary';
                @endphp
                <div class="card border-{{ $badge }} mt-20">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>{{ $statusResult['code'] }}</strong>
                            <span class="badge badge-{{ $badge }} px-3 py-2">{{ $statusResult['status_label'] }}</span>
                        </div>
                        <div>Loại đơn: <strong>{{ $statusResult['type'] }}</strong></div>
                        <div>Thiếu nhi: <strong>{{ $statusResult['child'] }}</strong></div>
                        <div>Ngày gửi: {{ $statusResult['created_at'] }}</div>
                        @if ($statusResult['account_code'])
                            <div>Mã thiếu nhi: <strong class="text-primary">{{ $statusResult['account_code'] }}</strong></div>
                        @endif
                        @if ($statusResult['status'] === 'approved')
                            <div class="alert alert-success mt-2 mb-0 small">Hồ sơ đã được duyệt. Xứ Đoàn sẽ báo khi thẻ được in xong.</div>
                        @endif
                        @if ($statusResult['admin_note'])
                            <div class="mt-2">Ghi chú của Xứ Đoàn: {{ $statusResult['admin_note'] }}</div>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
