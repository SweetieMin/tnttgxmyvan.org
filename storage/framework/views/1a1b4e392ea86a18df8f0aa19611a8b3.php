<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="login-box bg-white box-shadow border-radius-10">
        <div class="login-title">
            <h2 class="text-center text-primary">Đổi mật khẩu</h2>
        </div>
        <h6 class="mb-20">Bạn hãy nhập mật khẩu và nhấn xác nhận nhé</h6>
        <form action="<?php echo e(route('admin.reset_password',['token'=>$token])); ?>" method="POST">
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
                <input type="password" class="form-control form-control-lg" placeholder="Mật khẩu mới" name="new_password" />
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                </div>
            </div>

            <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-danger ml-1"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <div class="input-group custom mb-1 mt-3">
                <input type="password" class="form-control form-control-lg" placeholder="Xác nhận mật khẩu mới"
                    name="new_password_confirmation" />
                <div class="input-group-append custom">
                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                </div>
            </div>

            <?php $__errorArgs = ['new_password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-danger ml-1"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <div class="row justify-content-center align-items-center mt-4">
                <div class="col-5">
                    <div class="d-flex justify-content-center">
                        <input class="btn btn-primary btn-lg btn-block" type="submit" value="Thay đổi">
                    </div>
                </div>
            </div>

        </form>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.auth-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/auth/reset.blade.php ENDPATH**/ ?>