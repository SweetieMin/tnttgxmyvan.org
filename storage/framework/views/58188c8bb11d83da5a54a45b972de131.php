<div>
    <div class="mb-3">
        <?php if(Session::get('info')): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo Session::get('info'); ?>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if(Session::get('fail')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo Session::get('fail'); ?>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if(Session::get('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo Session::get('success'); ?>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/components/form-alerts.blade.php ENDPATH**/ ?>