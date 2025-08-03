<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Cài đặt</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Cài đặt trang web
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="pd-20 card-box mb-4">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.general');

$__html = app('livewire')->mount($__name, $__params, 'lw-3088051973-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $('input[type="file"][name="site_logo"]').ijaboViewer({
            preview:'#preview_side_logo',
            imageShape:'rectangular',
            allowedExtensions:['png'],
            onErrorShape: function(message, element){
                alert(message);
            },
            onInvalidType: function(message, element){
                alert(message);
            },
            onSuccess: function(message, element){
                
            }
        });

        $('#updateLogoForm').submit(function(e){
            e.preventDefault();
            var form = this;
            var inputVal = $(form).find('input[type="file"]').val();
            var errorElement = $(form).find('span.text-danger');
            errorElement.text('');

            if(inputVal.length > 0){
                $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:new FormData(form),
                    processData:false,
                    dataType: 'json',
                    contentType:false,
                    beforeSend:function(){},
                    success: function(data){
                        if(data.status == 1){
                            $(form)[0].reset();
                            $().notifa({
                                vers:1,
                                cssClass: 'success',
                                html:data.message,
                                delay:2500,
                            });
                            $('img.site_logo').each(function(){
                                $(this).attr('src','/'+data.image_path);
                            });
                        }else{
                            $().notifa({
                                vers:1,
                                cssClass: 'error',
                                html:data.message,
                                delay:2500,
                            });
                        }

                    }
                });
            }else{
                errorElement.text('Hãy chọn file hình ảnh.');
            }
        });

        $('input[type="file"][name="site_favicon"]').ijaboViewer({
            preview:'#preview_side_favicon',
            imageShape:'square',
            allowedExtensions:['png','ico'],
            onErrorShape: function(message, element){
                alert(message);
            },
            onInvalidType: function(message, element){
                alert(message);
            },
            onSuccess: function(message, element){
                
            }
        });

        $('#updateFaviconForm').submit(function(e){
            e.preventDefault();
            var form = this;
            var inputVal = $(form).find('input[type="file"]').val();
            var errorElement = $(form).find('span.text-danger');
            errorElement.text('');

            if(inputVal.length > 0){
                $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:new FormData(form),
                    processData:false,
                    dataType: 'json',
                    contentType:false,
                    beforeSend:function(){},
                    success: function(data){
                        if(data.status == 1){
                            $(form)[0].reset();
                            var linkElement = document.querySelector('link[rel="icon"]');
                            linkElement.href='/'+data.image_path;
                            
                            $().notifa({
                                vers:1,
                                cssClass: 'success',
                                html:data.message,
                                delay:2500,
                            });
                            $('img_site_favicon').each(function(){
                                $(this).attr('src','/'+data.image_path);
                            });
                        }else{
                            $().notifa({
                                vers:1,
                                cssClass: 'error',
                                html:data.message,
                                delay:2500,
                            });
                        }

                    }
                });
            }else{
                errorElement.text('Hãy chọn file hình ảnh.');
            }
        });

    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layout.pages-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/back/settings/general_setting.blade.php ENDPATH**/ ?>