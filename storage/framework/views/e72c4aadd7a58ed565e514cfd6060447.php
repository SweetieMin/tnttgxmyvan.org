
<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row pd-20">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p d-flex flex-column">
                <h5 class="text-center text-danger h4 mb-3"><?php echo e($user->roles->first()->name ?? ''); ?></h5>

                <div class="profile-photo">
                    <img src="<?php echo e($user->picture); ?>" alt="Ảnh đại diện của <?php echo e($user->full_name); ?>" class="avatar-photo"
                        id="profilePicturePreview">
                </div>
                <h5 class="text-center h5 mb-0"><?php echo e($user->holyName); ?></h5>
                <h5 class="text-center h3 mb-3"><?php echo e($user->lastName . ' ' . $user->name); ?></h5>
                <div class="profile-info d-flex justify-content-center flex-grow-1">
                    <div class="mt-2">
                        <?php echo \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate(url('/profile/' . $user->token)); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="card-box height-100-p overflow-hidden">
                <div class="profile-tab height-100-p">
                    <div class="tab height-100-p">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#personal_details" role="tab">Thông
                                    tin cá nhân</a>
                            </li>
                            <?php if(isset($user->studentParent)): ?>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#personal_parents" role="tab">Thông tin phụ
                                        huynh</a>
                                </li>
                            <?php endif; ?>

                        </ul>
                        <div class="tab-content">
                            <!-- Timeline Tab start -->
                            <div class="tab-pane fade show active" id="personal_details" role="tabpanel">
                                <div class="pd-20">
                                    <div class="row justify-content-center">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="card border-secondary bg-light">
                                                    <div class="card-body d-flex justify-content-center align-items-center"
                                                        style="height: 50px;">
                                                        <div class="h3 text-center mb-0"><?php echo e($user->account_code); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="holyName">Tên Thánh</label>
                                                <input type="text" id="holyName" name="holyName"
                                                    class="form-control bg-light" value="<?php echo e($user->holyName); ?>" disabled>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="name">Họ và tên</label>
                                                <input type="text" id="name" name="name"
                                                    class="form-control bg-light" value="<?php echo e($user->SimpleName); ?>" disabled>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">Số điện thoại</label>
                                                <input type="text" id="phone" class="form-control bg-light"
                                                    value="<?php echo e($user->phone ? Str::mask($user->phone, '*', 3, strlen($user->phone) - 6) : 'Chưa cập nhật'); ?>"
                                                    disabled>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="birthday">Ngày sinh <small
                                                        class="text-muted">(dd/mm/yyyy)</small></label>
                                                <input type="text" id="birthday" class="form-control bg-light"
                                                    value="<?php echo e($user->birthday ?? 'Chưa có'); ?>" disabled>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" id="email" class="form-control bg-light"
                                                    value="<?php echo e($user->email ? Str::mask($user->email, '*', 3, strpos($user->email, '@') - 3) : 'Chưa có'); ?>"
                                                    disabled>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="bio">Giới thiệu bản thân</label>
                                                <textarea id="bio" class="form-control bg-light" rows="3" disabled><?php echo e($user->bio ?? 'Chưa có'); ?></textarea>
                                            </div>
                                        </div>

                                        <?php if($user->courses->isNotEmpty()): ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="course">Lớp giáo lý</label>
                                                    <input type="text" id="course" class="form-control bg-light"
                                                        value="<?php echo e($user->courses->first()->name); ?>" disabled>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($user->sectors->isNotEmpty()): ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sector">Ngành sinh hoạt</label>
                                                    <input type="text" id="sector" class="form-control bg-light"
                                                        value="<?php echo e($user->sectors->first()->name); ?>" disabled>
                                                </div>
                                            </div>
                                        <?php endif; ?>


                                        
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="score" class="font-weight-bold mb-2">Số điểm hiện
                                                    tại</label>
                                                <div id="score"
                                                    class="d-flex justify-content-between align-items-center border rounded px-3 py-2 bg-white shadow-sm">
                                                    <span class="h5 mb-0 text-primary"><?php echo e($totalScore); ?></span>
                                                    <a href="<?php echo e(route('admin.score')); ?>"
                                                        class="text-decoration-none text-primary small">
                                                        <u>Xem chi tiết</u>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- Timeline Tab end -->

                            <?php if(isset($user->studentParent)): ?>
                                <!-- Timeline Tab start -->

                                <div class="tab-pane fade" id="personal_parents" role="tabpanel">
                                    <div class="pd-20">
                                        <div class="row">
                                            
                                            <div class="col-md-8 form-group">
                                                <label for="nameFather"><strong>Tên Thánh - Họ và tên Cha</strong></label>
                                                <input type="text" id="nameFather" class="form-control bg-light"
                                                    value="<?php echo e(optional($user->studentParent)->nameFather ?? 'Chưa cập nhật'); ?>"
                                                    disabled>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="phoneFather"><strong>Số điện thoại Cha</strong></label>
                                                <input type="text" id="phoneFather" class="form-control bg-light"
                                                    value="<?php echo e(optional($user->studentParent)->phoneFather
                                                        ? Str::mask($user->studentParent->phoneFather, '*', 3, strlen($user->studentParent->phoneFather) - 6)
                                                        : 'Chưa cập nhật'); ?>"
                                                    disabled>
                                            </div>

                                            
                                            <div class="col-md-8 form-group">
                                                <label for="nameMother"><strong>Tên Thánh - Họ và tên Mẹ</strong></label>
                                                <input type="text" id="nameMother" class="form-control bg-light"
                                                    value="<?php echo e(optional($user->studentParent)->nameMother ?? 'Chưa cập nhật'); ?>"
                                                    disabled>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label for="phoneMother"><strong>Số điện thoại Mẹ</strong></label>
                                                <input type="text" id="phoneMother" class="form-control bg-light"
                                                    value="<?php echo e(optional($user->studentParent)->phoneMother
                                                        ? Str::mask($user->studentParent->phoneMother, '*', 3, strlen($user->studentParent->phoneMother) - 6)
                                                        : 'Chưa cập nhật'); ?>"
                                                    disabled>
                                            </div>

                                            
                                            <div class="col-md-12 form-group">
                                                <label for="godParent"><strong>Tên Thánh - Họ và tên người đỡ
                                                        đầu</strong></label>
                                                <input type="text" id="godParent" class="form-control bg-light"
                                                    value="<?php echo e(optional($user->studentParent)->godParent ?? 'Chưa cập nhật'); ?>"
                                                    disabled>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                    <!-- Timeline Tab start -->
                </div>
            </div>
        </div>
    </div>

    <?php if($noticePopUp): ?>
        <div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content bg-<?php echo e($noticePopUp->type); ?> text-white">
                    <div class="modal-body text-center">
                        <h2 class="text-white mb-3">
                            <i class="fa fa-exclamation-triangle"></i> <?php echo e($noticePopUp->title); ?>

                        </h2>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <img src="/images/site/Notice.png" alt="<?php echo e($noticePopUp->title); ?>" class="img-fluid">
                            </div>
                            <div class="col-md-8 d-flex align-items-center">
                                <div class="card-box w-100 d-flex align-items-center justify-content-center"
                                    style="min-height: 200px; background: white; border-radius: 8px;">
                                    <h4 class="text-<?php echo e($noticePopUp->type); ?> mb-0">
                                        <?php echo e($noticePopUp->content); ?>

                                    </h4>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <?php if($noticePopUp): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#alert-modal').modal('show');
            });
        </script>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.profile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/front/profile.blade.php ENDPATH**/ ?>