<div>

    <form wire:submit.prevent="sendPasswordResetLink">

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
            <input type="text" class="form-control form-control-lg" placeholder="Email" id="email" autocomplete="off"
                wire:model="email" value="<?php echo e(old('email')); ?>">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
            </div>
        </div>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="text-danger ml-1"><?php echo e($message); ?></span>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    <a class="btn btn-outline-primary btn-lg btn-block" href="<?php echo e(route('admin.login')); ?>">Đăng nhập</a>
                </div>
            </div>
        </div>
    </form>


</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/auth/forgot-password.blade.php ENDPATH**/ ?>