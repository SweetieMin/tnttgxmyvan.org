<div>
    <div class="login-box bg-white box-shadow border-radius-10">
        <div class="login-title">
            <h2 class="text-center text-primary">Đăng nhập hệ thống</h2>
        </div>
        <form wire:submit.prevent="login()">
            <x-form-alerts></x-form-alerts>
            @csrf

            <div class="input-group custom mb-1">
                <input type="text" class="form-control form-control-lg" placeholder="Mã tài khoản / Email"
                    wire:model="login_id" value="{{ old('login_id', Cookie::get('login_id')) }}">

                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                </div>
            </div>
            @error('login_id')
                <span class="text-danger ml-1">{{ $message }}</span>
            @enderror

            <div class="input-group custom mb-1 mt-2">
                <input type="password" class="form-control form-control-lg" placeholder="**********"
                    wire:model="password" id="password" autocomplete="off">

                <div class="input-group-append custom">
                    <span class="input-group-text mt-1" id="password-eye" style="cursor: pointer;">
                        <i class="dw dw-padlock1"></i>
                    </span>
                </div>
            </div>
            @error('password')
                <span class="text-danger ml-1">{{ $message }}</span>
            @enderror

            <div class="row pb-30 mt-3">
                <div class="col-6">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1" wire:model="remember">

                        <label class="custom-control-label" for="customCheck1">Ghi nhớ đăng nhập</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="forgot-password">
                        <a href="{{ route('admin.forgot') }}">Quên mật khẩu</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="input-group mb-0">
                        <button class="btn btn-primary btn-lg btn-block" type="submit" wire:loading.attr="disabled"
                            wire:target="login">
                            <span wire:loading.remove wire:target="login">Đăng nhập</span>
                            <span wire:loading wire:target="login">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Đang xử lý...
                            </span>
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
