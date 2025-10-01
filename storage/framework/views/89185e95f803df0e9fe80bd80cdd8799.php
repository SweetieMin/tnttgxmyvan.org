<!DOCTYPE html>
<html lang="vi">

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php echo $__env->yieldContent('meta_tags'); ?>

    <!-- Site favicon -->
    <link rel="icon" type="image/png" sizes="16x16"
        href="/images/site/<?php echo e(settings()->site_favicon ?? 'FAVICON_default.png'); ?>" />

    <!-- Android Chrome -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#0d6efd"> <!-- Màu thanh trạng thái -->

    <!-- Apple Safari -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Đoàn TNTT Giáo Xứ Mỹ Vân">

    <!-- Biểu tượng cho cả iOS và Android -->
    <link rel="apple-touch-icon" href="/images/site/FAVICON_default.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/site/FAVICON_default.png">


    <link rel="apple-touch-icon"
        href="/images/site/<?php echo e(isset(settings()->site_favicon) ? settings()->site_favicon : 'FAVICON_default.png'); ?>">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" href="/extra-assets/ijabo/css/ijabo.min.css">
    <link rel="stylesheet" href="/extra-assets/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="/extra-assets/jquery-ui/jquery-ui.structure.min.css">
    <link rel="stylesheet" href="/extra-assets/jquery-ui/jquery-ui.theme.css">
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/style.css" />
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="<?php echo e(asset('extra-assets/jquery-3.7.1/jquery-3.7.1.min.js')); ?>"></script>
    <link href="/vendors/sawastacks/kropify/css/kropify.min.css" rel="stylesheet">
    <?php echo $__env->yieldPushContent('stylesheets'); ?>
</head>


<body>

    <div class="header">
        <div class="header-left">
            <div class="menu-icon bi bi-list"></div>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.searches');

$__html = app('livewire')->mount($__name, $__params, 'lw-3117440220-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>
        <div class="header-right">
            <div class="dashboard-setting user-notification">
                <div class="dropdown">
                    <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                        <i class="dw dw-settings2"></i>
                    </a>
                </div>
            </div>
            <div class="user-notification">
                <div class="dropdown">
                    <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                        <i class="icon-copy dw dw-notification"></i>
                        
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="notification-list mx-h-350 customscroll">

                        </div>
                    </div>
                </div>
            </div>

            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.top-user-info');

$__html = app('livewire')->mount($__name, $__params, 'lw-3117440220-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

            <div class="square d-none d-md-block"></div>
        </div>
    </div>

    <div class="right-sidebar">
        <div class="sidebar-title">
            <h3 class="weight-600 font-16 text-blue">
                Cài Đặt Bố Cục
                <span class="btn-block font-weight-400 font-12">Cài Đặt Giao Diện Người Dùng</span>
            </h3>
            <div class="close-sidebar" data-toggle="right-sidebar-close">
                <i class="icon-copy ion-close-round"></i>
            </div>
        </div>
        <div class="right-sidebar-body customscroll">
            <div class="right-sidebar-body-content">
                <h4 class="weight-600 font-18 pb-10">Màu Nền Tiêu Đề</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a href="javascript:void(0);" class="btn btn-outline-primary header-white active">Sáng</a>
                    <a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Tối</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Màu Nền Sidebar</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light">Sáng</a>
                    <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Tối</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Biểu Tượng Thả Xuống Menu</h4>
                <div class="sidebar-radio-group pb-10 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-1" name="menu-dropdown-icon"
                            class="custom-control-input" value="icon-style-1" checked="" />
                        <label class="custom-control-label" for="sidebaricon-1"><i
                                class="fa fa-angle-down"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-2" name="menu-dropdown-icon"
                            class="custom-control-input" value="icon-style-2" />
                        <label class="custom-control-label" for="sidebaricon-2"><i
                                class="ion-plus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-3" name="menu-dropdown-icon"
                            class="custom-control-input" value="icon-style-3" />
                        <label class="custom-control-label" for="sidebaricon-3"><i
                                class="fa fa-angle-double-right"></i></label>
                    </div>
                </div>

                <h4 class="weight-600 font-18 pb-10">Biểu Tượng Danh Sách Menu</h4>
                <div class="sidebar-radio-group pb-30 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-1" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-1" checked="" />
                        <label class="custom-control-label" for="sidebariconlist-1"><i
                                class="ion-minus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-2" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-2" />
                        <label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o"
                                aria-hidden="true"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-3" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-3" />
                        <label class="custom-control-label" for="sidebariconlist-3"><i
                                class="dw dw-check"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-4" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-4" checked="" />
                        <label class="custom-control-label" for="sidebariconlist-4"><i
                                class="icon-copy dw dw-next-2"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-5" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-5" />
                        <label class="custom-control-label" for="sidebariconlist-5"><i
                                class="dw dw-fast-forward-1"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-6" name="menu-list-icon"
                            class="custom-control-input" value="icon-list-style-6" />
                        <label class="custom-control-label" for="sidebariconlist-6"><i
                                class="dw dw-next"></i></label>
                    </div>
                </div>

                <div class="reset-options pt-30 text-center">
                    <button class="btn btn-danger" id="reset-settings">
                        Đặt Lại Cài Đặt
                    </button>
                </div>
            </div>
        </div>
    </div>


    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('layout.sidebars');

$__html = app('livewire')->mount($__name, $__params, 'lw-3117440220-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>


    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
            <div class="footer-wrap pd-20 mb-20 card-box">
                Đoàn TNTT Giáo xứ Mỹ Vân - Thiết kế bởi
                <a href="https://www.facebook.com/TVSweetieMin" target="_blank">Smyth Nguyen</a>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="/back/vendors/scripts/process.js"></script>
    <script src="/back/vendors/scripts/core.js"></script>
    <script src="/back/vendors/scripts/script.min.js"></script>
    <script src="/back/vendors/scripts/layout-settings.js"></script>
    <script src="/extra-assets/ijabo/js/ijabo.min.js"></script>
    <script src="/extra-assets/jquery-ui/jquery-ui.min.js"></script>
    <script src="/vendors/sawastacks/kropify/js/kropify.min.js"></script>
    <script>
        window.addEventListener('showToastr', function(event) {
            $().notifa({
                vers: 1,
                cssClass: event.detail[0].type,
                html: event.detail[0].message,
                delay: 2500
            });
        });

        window.addEventListener('offline', function() {
            $().notifa({
                vers: 1,
                cssClass: 'error',
                html: 'Mất kết nối mạng. Vui lòng kiểm tra lại Internet!',
                delay: 86400000,
            });
        });

        window.addEventListener('online', function() {
            $().notifa({
                vers: 1,
                cssClass: 'success',
                html: 'Đã kết nối lại Internet!',
                delay: 2500
            });
        });
    </script>



    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/layout/pages-layout.blade.php ENDPATH**/ ?>