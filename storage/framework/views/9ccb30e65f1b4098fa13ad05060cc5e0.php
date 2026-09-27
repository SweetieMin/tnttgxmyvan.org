<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row pd-20 w-100 mx-0">
        <div class="col-12">
            <?php if(session('card_success')): ?>
                <div class="alert alert-success text-center mb-30">
                    <i class="fa fa-check-circle"></i> <?php echo e(session('card_success')); ?>

                </div>
            <?php endif; ?>
            <?php if(session('card_info')): ?>
                <div class="alert alert-info text-center mb-30">
                    <?php echo e(session('card_info')); ?>

                </div>
            <?php endif; ?>
        </div>

        
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p d-flex flex-column">
                <h5 class="text-center text-danger h4 mb-3"><?php echo e($user->roles->first()->name ?? ''); ?></h5>

                <div class="profile-photo">
                    <img src="<?php echo e($user->picture); ?>" alt="Ảnh đại diện của <?php echo e($user->full_name); ?>" class="avatar-photo">
                </div>
                <h5 class="text-center h5 mb-0"><?php echo e($user->holyName); ?></h5>
                <h5 class="text-center h3 mb-3"><?php echo e($user->lastName . ' ' . $user->name); ?></h5>

                <div class="d-flex flex-column justify-content-center align-items-center flex-grow-1">
                    <?php if($user->has_card): ?>
                        <span class="badge badge-pill bg-success text-white px-4 py-2" style="font-size: 16px;">
                            <i class="fa fa-check"></i> Đã làm thẻ
                        </span>
                    <?php else: ?>
                        <span class="badge badge-pill bg-warning text-dark px-4 py-2 mb-3" style="font-size: 16px;">
                            Chưa làm thẻ
                        </span>

                        <form method="POST" action="<?php echo e(route('card_confirm', ['token' => $user->token_v2])); ?>"
                            class="w-100 text-center"
                            onsubmit="return confirm('Xác nhận đã làm thẻ cho <?php echo e(addslashes($user->SimpleName)); ?>?') && (this.querySelector('button').disabled = true, true);">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fa fa-id-card"></i> Xác nhận đã làm thẻ
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
            <?php if($user->has_card): ?>
                <div class="card-box height-100-p pd-20 d-flex flex-column justify-content-center align-items-center text-center">
                    <i class="fa fa-id-card text-success" style="font-size: 64px;"></i>
                    <h4 class="h4 mt-3 mb-2"><?php echo e($user->holyName); ?> <?php echo e($user->SimpleName); ?> đã được làm thẻ</h4>
                    <div class="text-muted">Mã tài khoản: <?php echo e($user->account_code); ?></div>
                </div>
            <?php else: ?>
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
                                        <a class="nav-link" data-toggle="tab" href="#personal_parents" role="tab">Thông tin
                                            phụ huynh</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                            <div class="tab-content">
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
                                                    <input type="text" id="holyName" class="form-control bg-light"
                                                        value="<?php echo e($user->holyName); ?>" disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="name">Họ và tên</label>
                                                    <input type="text" id="name" class="form-control bg-light"
                                                        value="<?php echo e($user->SimpleName); ?>" disabled>
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

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Số điện thoại</label>
                                                    <input type="text" id="phone" class="form-control bg-light"
                                                        value="<?php echo e($user->phone ? Str::mask($user->phone, '*', 3, strlen($user->phone) - 6) : 'Chưa cập nhật'); ?>"
                                                        disabled>
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
                                        </div>
                                    </div>
                                </div>

                                <?php if(isset($user->studentParent)): ?>
                                    <div class="tab-pane fade" id="personal_parents" role="tabpanel">
                                        <div class="pd-20">
                                            <div class="row">
                                                <div class="col-md-8 form-group">
                                                    <label for="nameFather"><strong>Tên Thánh - Họ và tên
                                                            Cha</strong></label>
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
                                                    <label for="nameMother"><strong>Tên Thánh - Họ và tên
                                                            Mẹ</strong></label>
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
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.profile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/now/resources/views/front/card.blade.php ENDPATH**/ ?>