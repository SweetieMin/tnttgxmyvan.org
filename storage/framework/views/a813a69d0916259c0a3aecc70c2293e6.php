<div>
    <div class="tab">
        <ul class="nav nav-tabs customtab" role="tablist">
            <li class="nav-item">
                <a wire:click="selectTab('general_settings')"
                    class="nav-link <?php echo e($tab === 'general_settings' ? 'active' : ''); ?>" data-toggle="tab"
                    href="#general_settings" role="tab" aria-selected="true">Cài đặt chung</a>
            </li>
            <li class="nav-item">
                <a wire:click="selectTab('website_social')"
                    class="nav-link <?php echo e($tab === 'website_social' ? 'active' : ''); ?>" data-toggle="tab"
                    href="#website_social" role="tab" aria-selected="false">Trang mạng xã hội</a>
            </li>
            <li class="nav-item">
                <a wire:click="selectTab('logo_favicon')" class="nav-link <?php echo e($tab === 'logo_favicon' ? 'active' : ''); ?>"
                    data-toggle="tab" href="#logo_favicon" role="tab" aria-selected="false">Logo & Favicon</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade <?php echo e($tab === 'general_settings' ? 'active show' : ''); ?>" id="general_settings"
                role="tabpanel">
                <div class="pd-20">
                    <form wire:submit='updateSiteInfo()'>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_title"><b>Tiêu đề</b></label>
                                    <input type="text" class="form-control" id="site_title" wire:model="site_title"
                                        placeholder="Nhập tiêu đề cho trang web">
                                    <?php $__errorArgs = ['site_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger ml-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_email"><b>Email</b></label>
                                    <input type="text" class="form-control" id="site_email" wire:model="site_email"
                                        placeholder="Nhập địa chỉ email của trang">
                                    <?php $__errorArgs = ['site_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger ml-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_phone"><b>Số điện thoại</b></label>
                                    <input type="text" class="form-control" id="site_phone" wire:model="site_phone"
                                        placeholder="Nhập số điện thoại của trang">
                                    <?php $__errorArgs = ['site_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger ml-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="site_meta_keywords"><b>Từ khóa Meta</b> <small> ̣(Tùy
                                            chọn)</small></label>
                                    <input type="text" class="form-control" id="site_meta_keywords"
                                        wire:model="site_meta_keywords" placeholder="Nhập từ khóa Meta">
                                    <?php $__errorArgs = ['site_meta_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger ml-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="site_meta_description"><b>Mô tả từ khóa Meta</b> <small> (Tùy
                                    chọn)</small></label>
                            <textarea class="form-control" cols="4" rows="4" id="site_meta_description"
                                wire:model="site_meta_description" placeholder="Viết mô tả Meta của trang..."></textarea>
                            <?php $__errorArgs = ['site_meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger ml-1"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group text-center mt-2">
                            <button type="submit" class="btn btn-lg btn-primary" wire:loading.remove>Cập nhật</button>
                            <button class="btn btn-primary btn-lg" style="width: 101px" wire:loading
                                wire:target="updateSiteInfo" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="tab-pane fade <?php echo e($tab === 'website_social' ? 'active show' : ''); ?>" id="website_social"
                role="tabpanel">
                <div class="pd-20 profile-task-wrap">
                    <form method="POST" wire:submit="updateSiteSocialLinks()">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="facebook_url"><b>Facebook</b></label>
                                    <input type="text" class="form-control" id="facebook_url"
                                        wire:model='facebook_url' placeholder="Facebook URL">
                                    <?php $__errorArgs = ['facebook_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger ml-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tikTok_url"><b>TikTok</b></label>
                                    <input type="text" class="form-control" id="tikTok_url"
                                        wire:model='tikTok_url' placeholder="TikTok URL">
                                    <?php $__errorArgs = ['tikTok_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger ml-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="youtube_url"><b>Youtube</b></label>
                                    <input type="text" class="form-control" id="youtube_url"
                                        wire:model='youtube_url' placeholder="Youtube URL">
                                    <?php $__errorArgs = ['youtube_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger ml-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="instagram_url"><b>Instagram</b></label>
                                    <input type="text" class="form-control" id="instagram_url"
                                        wire:model='instagram_url' placeholder="Instagram URL">
                                    <?php $__errorArgs = ['instagram_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger ml-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center mt-2">
                            <button type="submit" class="btn btn-lg btn-primary" wire:loading.remove>Cập nhật</button>
                            <button class="btn btn-primary btn-lg" style="width: 137px" wire:loading
                                wire:target="updateSiteSocialLinks" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="tab-pane fade <?php echo e($tab === 'logo_favicon' ? 'active show' : ''); ?>" id="logo_favicon"
                role="tabpanel">
                <div class="pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Site Logo</h6>
                            <div class="mb-2 mt-1" style="max-width: 200px">
                                <img wire:ignore src="" alt="" class="img-thumbnail"
                                    data-ijabo-default-img="/images/site/<?php echo e(isset(settings()->site_logo) ? settings()->site_logo : ''); ?>"
                                    id="preview_side_logo">
                            </div>
                            <form action="<?php echo e(route('admin.update_logo')); ?>" method="POST"
                                enctype="multipart/form-data" id="updateLogoForm">
                                <?php echo csrf_field(); ?>

                                <div class=" mb-2">
                                    <input type="file" name="site_logo" id="" class="form-control">

                                    <span class="text-danger ml-1"></span>
                                </div>

                                <div class="form-group text-center mt-2">
                                    <button type="submit" class="btn btn-primary">Cập nhật Logo</button>
                                </div>

                            </form>
                        </div>
                        <div class="col-md-6">
                            <h6>Favicon</h6>
                            <div class="mb-2 mt-1" style="max-width: 100px">
                                <img wire:ignore src="" alt="" class="img-thumbnail"
                                    data-ijabo-default-img="/images/site/<?php echo e(isset(settings()->site_favicon) ? settings()->site_favicon : ''); ?>"
                                    id="preview_side_favicon">
                            </div>
                            <form action="<?php echo e(route('admin.update_favicon')); ?>" method="POST"
                                enctype="multipart/form-data" id="updateFaviconForm">
                                <?php echo csrf_field(); ?>
                                <div class=" mb-2">
                                    <input type="file" name="site_favicon" id="" class="form-control">

                                    <span class="text-danger ml-1"></span>
                                </div>

                                <div class="form-group text-center mt-2">
                                    <button type="submit" class="btn btn-primary">Cập nhật Favicon</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/livewire/settings/general.blade.php ENDPATH**/ ?>