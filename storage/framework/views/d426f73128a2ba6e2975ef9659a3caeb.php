<div>
    <div class="login-box bg-white box-shadow border-radius-10">
        <div class="login-title">
            <h2 class="text-center text-primary">Đăng nhập hệ thống</h2>
        </div>
        <form wire:submit.prevent="login()">
            <?php if (isset($component)) { $__componentOriginal99af1ca30191b253736b5478bea41af2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99af1ca30191b253736b5478bea41af2 = $attributes; } ?>
<?php $component = App\View\Components\FormAlerts::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('form-alerts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\FormAlerts::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal99af1ca30191b253736b5478bea41af2)): ?>
<?php $attributes = $__attributesOriginal99af1ca30191b253736b5478bea41af2; ?>
<?php unset($__attributesOriginal99af1ca30191b253736b5478bea41af2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal99af1ca30191b253736b5478bea41af2)): ?>
<?php $component = $__componentOriginal99af1ca30191b253736b5478bea41af2; ?>
<?php unset($__componentOriginal99af1ca30191b253736b5478bea41af2); ?>
<?php endif; ?>
            <?php echo csrf_field(); ?>

            <div class="input-group custom mb-1">
                <input type="text" class="form-control form-control-lg" placeholder="Mã tài khoản / Email"
                    wire:model="login_id" value="<?php echo e(old('login_id', Cookie::get('login_id'))); ?>">

                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                </div>
            </div>
            <?php $__errorArgs = ['login_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-danger ml-1"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <div class="input-group custom mb-1 mt-2">
                <input type="password" class="form-control form-control-lg" placeholder="**********"
                    wire:model="password" id="password" autocomplete="off">

                <div class="input-group-append custom">
                    <span class="input-group-text mt-1" id="password-eye" style="cursor: pointer;">
                        <i class="dw dw-padlock1"></i>
                    </span>
                </div>
            </div>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-danger ml-1"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <div class="row pb-30 mt-3">
                <div class="col-6">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1" wire:model="remember">

                        <label class="custom-control-label" for="customCheck1">Ghi nhớ đăng nhập</label>
                    </div>
                </div>
                <div class="col-6">
                    <div class="forgot-password">
                        <a href="<?php echo e(route('admin.forgot')); ?>">Quên mật khẩu</a>
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
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/auth/login.blade.php ENDPATH**/ ?>