<?php $__env->startSection('meta_tags'); ?>
    <title><?php echo e(isset(settings()->site_title) ? settings()->site_title : 'Đoàn Thiếu Nhi Thánh Thể Giáo Xứ Mỹ Vân'); ?></title>
    <meta name="description"
        content="<?php echo e(isset(settings()->site_meta_description) ? settings()->site_meta_description : 'Đoàn Thiếu Nhi Thánh Thể Giáo Xứ Mỹ Vân'); ?>">
    <meta name="keywords" content="<?php echo e(isset(settings()->site_meta_keywords) ? settings()->site_meta_keywords : 'Đoàn TNTT, Giáo xứ Mỹ Vân, Thiếu Nhi Thánh Thể, Huynh Trưởng, sinh hoạt Công Giáo'); ?>">
    <meta name="author" content="Toma Nguyễn Khắc Huấn">

    <meta property="og:title"
        content="<?php echo e(isset(settings()->site_title) ? settings()->site_title : 'Đoàn Thiếu Nhi Thánh Thể Giáo Xứ Mỹ Vân'); ?>">
    <meta property="og:description"
        content="<?php echo e(isset(settings()->site_meta_keywords) ? settings()->site_meta_keywords : 'Đoàn TNTT, Giáo xứ Mỹ Vân, Thiếu Nhi Thánh Thể, Huynh Trưởng, sinh hoạt Công Giáo'); ?>">
    <meta property="og:image" content="https://tnttgxmyvan.org/images/site/LOGO_SHARE.png">
     <meta property="og:image:alt" content="Logo Đoàn Thiếu Nhi Thánh Thể Giáo Xứ Mỹ Vân">
    <meta property="og:url" content="https://tnttgxmyvan.org/">
    <meta property="og:type" content="website">
    <meta property="fb:app_id" content="546590444997966" />


    <meta name="twitter:title"
        content="<?php echo e(isset(settings()->site_title) ? settings()->site_title : 'Đoàn Thiếu Nhi Thánh Thể Giáo Xứ Mỹ Vân'); ?>">
    <meta name="twitter:description"
        content="<?php echo e(isset(settings()->site_meta_keywords) ? settings()->site_meta_keywords : 'Đoàn TNTT, Giáo xứ Mỹ Vân, Thiếu Nhi Thánh Thể, Huynh Trưởng, sinh hoạt Công Giáo'); ?>">
    <meta name="twitter:image" content="https://tnttgxmyvan.org/images/site/FAVICON_default.png">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <section class=" about-section py-5">
        <div class="container ">
            <div class="text-center mb-5">
                <h1 class="fw-bold text-primary">Giới thiệu về Đoàn Thiếu Nhi Thánh Thể</h1>
                <p>Giáo xứ Mỹ Vân – Nơi yêu thương, phục vụ và đồng hành cùng thiếu nhi</p>
                <hr class="w-25 mx-auto">
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                        <p class="lead">
                            <strong>Đoàn Thiếu Nhi Thánh Thể Giáo xứ Mỹ Vân</strong> là tổ chức giáo dục đức tin và nhân bản
                            dành cho các em thiếu nhi trong giáo xứ. Với mục tiêu <em>“Yêu mến Chúa Giêsu Thánh Thể – Phục
                                vụ thiếu nhi – Làm chứng cho Tin Mừng”</em>, Đoàn luôn là nơi ươm mầm đức tin cho thế hệ trẻ
                            tại Mỹ Vân.
                        </p>

                        <h2 class="mt-4 text-secondary">🌱 Sứ mạng</h2>
                        <p>
                            Chúng tôi tổ chức các hoạt động như: học giáo lý, sinh hoạt đội nhóm, tham dự Thánh Lễ, trại hè,
                            tĩnh tâm... Tất cả nhằm giúp các em thiếu nhi sống gắn bó với Chúa và trưởng thành trong đức
                            tin.
                        </p>

                        <h2 class="mt-4 text-secondary">💡 Giá trị cốt lõi</h2>
                        <ul>
                            <li>Trung thành với giáo huấn Công Giáo</li>
                            <li>Yêu thương – Phục vụ – Hi sinh</li>
                            <li>Đào tạo Huynh Trưởng có trách nhiệm và đời sống đạo đức</li>
                        </ul>

                        <h2 class="mt-4 text-secondary">🌐 Trang web chính thức</h2>
                        <p>
                            Website <strong>tnttgxmyvan.org</strong> là nơi kết nối giữa Ban Điều Hành, Huynh Trưởng, thiếu
                            nhi
                            và phụ huynh. Đây là công cụ cập nhật thông báo, quản lý điểm danh, chia sẻ hình ảnh và hoạt
                            động đoàn.
                        </p>

                        <div class="text-end mt-4">
                            <em>“Tất cả vì thiếu nhi – tất cả để yêu mến Chúa Giêsu Thánh Thể.”</em>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.profile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/now/resources/views/welcome.blade.php ENDPATH**/ ?>