<div>

    <form wire:submit.prevent="sendPasswordResetLink">

        <x-form-alerts></x-form-alerts>
        @csrf
        <div class="input-group custom mb-1">
            <input type="text" class="form-control form-control-lg" placeholder="Email" id="email" autocomplete="off"
                wire:model="email" value="{{ old('email') }}">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
            </div>
        </div>
        @error('email')
            <span class="text-danger ml-1">{{ $message }}</span>
        @enderror
        <div class="row align-items-center mt-2">
            <div class="col-5">

                <div class="d-flex justify-content-center mt-2">
                    <button type="button" class="btn btn-primary btn-lg" wire:click="sendPasswordResetLink"
                        wire:loading.remove>
                        Gửi mã
                    </button>
                    <button class="btn btn-primary btn-lg" wire:loading wire:target="sendPasswordResetLink"
                        type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </button>
                </div>

            </div>
            <div class="col-2">
                <div class="font-16 weight-600 text-center" data-color="#707373" style="color: rgb(112, 115, 115);">
                    Hoặc
                </div>
            </div>
            <div class="col-5">
                <div class="input-group mb-0">
                    <a class="btn btn-outline-primary btn-lg btn-block" href="{{ route('admin.login') }}">Đăng nhập</a>
                </div>
            </div>
        </div>
    </form>


</div>
