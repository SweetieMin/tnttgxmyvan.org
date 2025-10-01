<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('auth.login');

$__html = app('livewire')->mount($__name, $__params, 'lw-2128310820-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('password-eye');
            const lockIcon = eyeIcon.querySelector('i');
            passwordInput.addEventListener('input', function() {
                if (passwordInput.value) {
                    eyeIcon.style.pointerEvents = 'auto';
                    lockIcon.classList.remove('dw-padlock1');
                    lockIcon.classList.add('dw-eye');
                } else {
                    eyeIcon.style.pointerEvents = 'none';
                    lockIcon.classList.remove('dw-eye');
                    lockIcon.classList.add('dw-padlock1');
                    if (lockIcon.classList.contains('bi-eye-slash')) {
                        lockIcon.classList.remove('bi-eye-slash');
                        lockIcon.classList.add('dw-padlock1');
                    }
                }
            });
            if (passwordInput.value) {
                eyeIcon.style.pointerEvents = 'auto';
                lockIcon.classList.remove('dw-padlock1');
                lockIcon.classList.add('dw-eye');
            } else {
                eyeIcon.style.pointerEvents = 'none';
                lockIcon.classList.remove('dw-eye');
                lockIcon.classList.add('dw-padlock1');
            }
            eyeIcon.addEventListener('click', function() {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    lockIcon.classList.remove('dw-eye');
                    lockIcon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = "password";
                    lockIcon.classList.remove('bi-eye-slash');
                    lockIcon.classList.add('dw-eye');
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.auth-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/auth/login.blade.php ENDPATH**/ ?>