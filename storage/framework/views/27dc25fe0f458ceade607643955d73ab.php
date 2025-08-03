<!DOCTYPE html>
<html lang="vi">

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <?php echo $__env->yieldContent('meta_tags'); ?>
    <!-- Site favicon -->
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/png" sizes="16x16"
        href="/images/site/<?php echo e(isset(settings()->site_favicon) ? settings()->site_favicon : 'FAVICON_default.png'); ?>" />

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Đoàn TNTT Giáo Xứ Mỹ Vân">

    <link rel="apple-touch-icon" href="/images/site/<?php echo e(isset(settings()->site_favicon) ? settings()->site_favicon : 'FAVICON_default.png'); ?>">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/style.css" />

    <?php echo $__env->yieldPushContent('stylesheets'); ?>

</head>

<body class="login-page">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="/">
                    <img src="/images/site/<?php echo e(isset(settings()->site_logo) ? settings()->site_logo : 'LOGO_default.png'); ?>"
                        alt="<?php echo e(isset(settings()->site_title) ? settings()->site_title : 'Đoàn TNTT Giáo Xứ Mỹ Vân'); ?>" />
                </a>
            </div>
        </div>
    </div>
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="/images/site/LOGIN_default.webp" loading="lazy" alt="" />
                </div>
                <div class="col-lg-6">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- js -->
    <script src="/back/vendors/scripts/core.js"></script>
    <script src="/back/vendors/scripts/script.min.js"></script>
    <script src="/back/vendors/scripts/layout-settings.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/layout/auth-layout.blade.php ENDPATH**/ ?>