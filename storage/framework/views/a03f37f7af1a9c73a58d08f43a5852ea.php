<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title><?php echo $__env->yieldContent('title', 'Error'); ?></title>

    <!-- Site favicon -->
    <link rel="icon" type="image/png" sizes="16x16"
        href="/images/site/<?php echo e(isset(settings()->site_favicon) ? settings()->site_favicon : 'FAVICON_default.png'); ?>" />

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

<body>
    <div class="error-page d-flex align-items-center flex-wrap justify-content-center">
        <div class="error-page-wrap text-center">
            <img src="<?php echo $__env->yieldContent('image', '/images/site/default_error.png'); ?>" alt="">
            <h1><?php echo $__env->yieldContent('code', 'Error'); ?></h1>
            <h3><?php echo $__env->yieldContent('message', 'Something went wrong'); ?></h3>
            <p><?php echo $__env->yieldContent('description', 'Please try again later.'); ?></p>
            <div class="mt-3">
                <?php echo $__env->yieldContent('button_secondary'); ?>
            </div>
        </div>
    </div>
</body>

<?php echo $__env->yieldPushContent('scripts'); ?>

</html>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/errors/layout/errors.blade.php ENDPATH**/ ?>