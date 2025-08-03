
<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle'); ?>
<?php $__env->startSection('meta_tags'); ?>
    <?php echo SEO::generate(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Danh sách</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Danh sách Huynh - Dự - Đội Trưởng
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('personnel.scouters');

$__html = app('livewire')->mount($__name, $__params, 'lw-648817679-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('/extra-assets/html2canvas/html2canvas.min.js')); ?>"></script>
    <script>

        $('#sector').selectpicker().on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedRoles = Array.from($('#role')[0].selectedOptions).map(o => o.value);
            const selectedSectors = Array.from($('#sector')[0].selectedOptions).map(o => o.value);
            Livewire.dispatch('chooseDataSort', [selectedRoles, selectedSectors]);
        });

        $('#role').selectpicker().on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            const selectedRoles = Array.from($('#role')[0].selectedOptions).map(o => o.value);
            const selectedSectors = Array.from($('#sector')[0].selectedOptions).map(o => o.value);
            Livewire.dispatch('chooseDataSort', [selectedRoles, selectedSectors]);
        });


        window.addEventListener('showScouterModal', function() {
            $('#scouter_modal').modal('show');

            $('#scouter_modal').on('shown.bs.modal', function() {

                $('.selectpicker').selectpicker('refresh');

                const isMember = isSpecialRoleSelected();
                showSector.classList.toggle('d-none', !isMember);

                if (isMember) {
                    const sectorSelected = [...sectorModalEl.selectedOptions].length > 0;
                    msgSectorModal.classList.toggle('d-none', sectorSelected);
                } else {
                    msgSectorModal.classList.add('d-none');
                }
            });

            const roleModalEl = document.getElementById('roleModal');
            const sectorModalEl = document.getElementById('sectorModal');

            const msgSectorModal = document.getElementById('msgSectorModal');
            const showSector = document.getElementById('showSector');

            function isSpecialRoleSelected() {
                return [...roleModalEl.selectedOptions].some(option => ['huynh trưởng', 'dự trưởng', 'đội trưởng']
                    .includes(option.text.toLowerCase())
                );
            }

            let isMember = isSpecialRoleSelected();
            showSector.classList.toggle('d-none', !isMember);

            if (isMember) {
                const sectorSelected = [...sectorModalEl.selectedOptions].length > 0;
                msgSectorModal.classList.toggle('d-none', sectorSelected);
            } else {
                msgSectorModal.classList.add('d-none');
            }
            roleModalEl.addEventListener('change', function() {
                const isMember = isSpecialRoleSelected();

                showSector.classList.toggle('d-none', !isMember);

                if (isMember) {
                    const sectorSelected = [...sectorModalEl.selectedOptions].length > 0;
                    msgSectorModal.classList.toggle('d-none', sectorSelected);
                } else {
                    [...sectorModalEl.options].forEach(opt => opt.selected = false);
                    $('.selectpicker').selectpicker('refresh');
                    msgSectorModal.classList.add('d-none');
                }
            });

            sectorModalEl.addEventListener('change', function() {
                const selected = [...this.selectedOptions].length > 0;
                msgSectorModal.classList.toggle('d-none', selected);
            });


            $('#scouter_modal form').on('submit', function(e) {
                e.preventDefault();

                const isMember = isSpecialRoleSelected();
                const selectedSectors = [...sectorModalEl.selectedOptions].map(opt => opt.value);

                if (isMember && selectedSectors.length === 0) {
                    msgSectorModal.classList.remove('d-none');
                    return;
                }
                msgSectorModal.classList.add('d-none');
                //ngành
                var sectorModal = Array.from(document.querySelector('#sectorModal').selectedOptions).map(
                    option => option.value
                );
                //chức vụ
                var roleModal = Array.from(document.querySelector('#roleModal').selectedOptions).map(
                    option => option.value
                );
                //trạng thái tôn giáo
                var trang_thai_ton_giao = Array.from(document.querySelector('#trang_thai_ton_giao')
                    .selectedOptions).map(
                    option => option.value
                );

                roleModal = parseInt(roleModal[0]);
                sectorModal = parseInt(sectorModal[0]);
                trang_thai_ton_giao = trang_thai_ton_giao.join(',');;

                Livewire.dispatch('submitScouterFormModal', [roleModal, sectorModal, trang_thai_ton_giao]);

            });

        });

        window.addEventListener('hideScouterModal', function() {
            $('#scouter_modal').modal('hide');
        });

        //Update Avatar
        window.addEventListener('showScouterAvatar', function() {
            $('#scouter_avatar').modal('show');
            var id = event.detail[0].id;

            const avatar = document.getElementById('profilePicturePreview');
            const fileInput = document.getElementById('profilePictureFile');


            if (!id || id <= 0) {
                alert('Đã có lỗi vui lòng thử lại');
                $('#scouter_avatar').modal('hide');
                location.reload();
                return;
            }

            $('input[type="file"][id="profilePictureFile"]').kropify({
                preview: 'image#profilePicturePreview',
                viewMode: 1,
                aspectRatio: 1,
                cancelButtonText: 'Hủy',
                resetButtonText: 'Đặt lại',
                cropButtonText: 'Cắt & cập nhật',
                processURL: `/admin/update-profile-picture/` + id,
                maxSize: 3145728, // 3MB
                showLoader: true,
                animationClass: 'pulse',
                success: function(data) {
                    if (data.status == 1) {
                        Livewire.dispatch('updateNavbar', []);
                        Livewire.dispatch('updateProfile', []);
                        $().notifa({
                            vers: 1,
                            cssClass: 'success',
                            html: data.message,
                            delay: 2500
                        });
                        $('#scouter_avatar').modal('hide');
                        Livewire.dispatch('refreshScouterList');
                    } else {
                        $().notifa({
                            vers: 1,
                            cssClass: 'error',
                            html: data.message,
                            delay: 2500
                        });
                    }
                },
                errors: function(error, text) {
                    $().notifa({
                        vers: 1,
                        cssClass: 'error',
                        html: text,
                        delay: 2500
                    });
                },
            });

            avatar.addEventListener('dragover', function(e) {
                e.preventDefault();
                avatar.style.border = '2px dashed #007bff';
                avatar.style.opacity = '0.8';
            });

            avatar.addEventListener('dragleave', function(e) {
                e.preventDefault();
                avatar.style.border = 'none';
                avatar.style.opacity = '1';
            });

            avatar.addEventListener('drop', function(e) {
                e.preventDefault();
                avatar.style.border = 'none';
                avatar.style.opacity = '1';

                const file = e.dataTransfer.files[0];


                if (file && file.type.startsWith('image/') && file.size > 0) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;

                    $(fileInput).trigger('change');
                } else {
                    alert('File không hợp lệ hoặc rỗng. Hãy kéo ảnh từ thư mục máy tính.');
                }
            });

        });
        window.addEventListener('hideScouterAvatar', function() {
            $('#scouter_avatar').modal('hide');
        });
        //Update Avatar

        window.addEventListener('deleteScouter', function(event) {
            var id = event.detail[0].id;
            var name = event.detail[0].name;
            var position = event.detail[0].position;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có chắc không muốn xóa ' + position + ' ' + name + ' này?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Có, xóa',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('deleteScouterAction', [id]);
                }
            });
        });

        window.addEventListener('confirmResetPasswordScouter', function(event) {
            var id = event.detail[0].id;
            var name = event.detail[0].name;
            var position = event.detail[0].position;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có chắc đặt lại mật khẩu cho ' + position + ' ' + name + ' này không?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Có, đặt lại mật khẩu',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('confirmResetPasswordScouterAction', [id]);
                }
            });
        });

        window.addEventListener('showScouterCard', function(event) {
            const cardToCapture = document.querySelector('#scouter_card .card-preview');

            $('#scouter_card').modal('show');

            $('#scouter_card').on('shown.bs.modal', function() {
                var name = event.detail[0].name;
                var times = event.detail[0].times;
                html2canvas(cardToCapture, {
                    allowTaint: true,
                    useCORS: true,
                    scale: 2
                }).then(canvas => {
                    const downloadLink = document.createElement('a');
                    downloadLink.href = canvas.toDataURL('image/png');
                    downloadLink.download = name + '-L' + times + '.png';
                    downloadLink.click();
                });

                $('#scouter_card').off('shown.bs.modal');
                $('#scouter_card').modal('hide');

            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.pages-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/tnttgxmyvan.org/resources/views/back/personnel/scouter.blade.php ENDPATH**/ ?>