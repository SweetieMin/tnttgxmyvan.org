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
                            Danh sách Thiếu Nhi
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
[$__name, $__params] = $__split('personnel.children');

$__html = app('livewire')->mount($__name, $__params, 'lw-3553934783-0', $__slots ?? [], get_defined_vars());

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
        const childrenSelectpickerConfigs = {
            '#course': {
                noneSelectedText: 'Chọn lớp giáo lý',
                selectAllText: 'Chọn tất cả',
                deselectAllText: 'Bỏ chọn tất cả',
                noneResultsText: 'Không tìm thấy lớp giáo lý nào',
            },
            '#sector': {
                noneSelectedText: 'Chọn cấp',
                selectAllText: 'Chọn tất cả',
                deselectAllText: 'Bỏ chọn tất cả',
                noneResultsText: 'Không tìm thấy cấp nào',
            },
            '#compare_first_child_id': {
                noneSelectedText: 'Chọn thiếu nhi',
                noneResultsText: 'Không tìm thấy thiếu nhi',
                container: 'body',
            },
            '#compare_second_child_id': {
                noneSelectedText: 'Chọn thiếu nhi',
                noneResultsText: 'Không tìm thấy thiếu nhi',
                container: 'body',
            },
            '#courseModal': {
                noneSelectedText: 'Chọn lớp giáo lý',
                noneResultsText: 'Không tìm thấy lớp giáo lý',
                container: 'body',
            },
            '#sectorModal': {
                noneSelectedText: 'Chọn ngành',
                noneResultsText: 'Không tìm thấy ngành',
                container: 'body',
            },
            '#trang_thai': {
                noneSelectedText: 'Chọn trạng thái',
                noneResultsText: 'Không tìm thấy trạng thái',
                container: 'body',
            },
        };

        function rebuildChildrenSelectpicker(selector, options = {}) {
            const $elements = $(selector);

            if (!$elements.length) {
                return;
            }

            $elements.each(function() {
                const $element = $(this);
                const currentValue = $element.val();

                if ($element.data('selectpicker')) {
                    $element.selectpicker('destroy');
                }

                $element.selectpicker(options);

                if (currentValue !== undefined && currentValue !== null) {
                    $element.selectpicker('val', currentValue);
                }

                $element.selectpicker('refresh');
            });
        }

        function syncChildrenSelectpickers() {
            Object.entries(childrenSelectpickerConfigs).forEach(([selector, options]) => {
                rebuildChildrenSelectpicker(selector, options);
            });
        }

        function getSelectedValues(selector) {
            const element = document.querySelector(selector);
            if (!element) {
                return [];
            }

            return Array.from(element.selectedOptions).map(option => option.value);
        }

        syncChildrenSelectpickers();

        $('#sector').off('changed.bs.select.children').on('changed.bs.select.children', function() {
            const selectedSectors = getSelectedValues('#sector');
            const selectedCourses = getSelectedValues('#course');

            Livewire.dispatch('chooseDataSort', [selectedSectors, selectedCourses]);
        });

        $('#course').off('changed.bs.select.children').on('changed.bs.select.children', function() {
            const selectedSectors = getSelectedValues('#sector');
            const selectedCourses = getSelectedValues('#course');

            Livewire.dispatch('chooseDataSort', [selectedSectors, selectedCourses]);
        });

        window.addEventListener('refreshChildrenSelectpicker', syncChildrenSelectpickers);

        window.addEventListener('showCompareChildrenExportModal', function() {
            $('#children_compare_export_modal').modal('show');
        });

        $('#children_compare_export_modal')
            .off('shown.bs.modal.children')
            .on('shown.bs.modal.children', function() {
                rebuildChildrenSelectpicker('#compare_first_child_id', childrenSelectpickerConfigs[
                    '#compare_first_child_id']);
                rebuildChildrenSelectpicker('#compare_second_child_id', childrenSelectpickerConfigs[
                    '#compare_second_child_id']);
            });

        $(document).off('submit.childrenCompare', '#children_compare_export_form').on('submit.childrenCompare',
            '#children_compare_export_form',
            function(e) {
            e.preventDefault();

            const firstChildId = $('#compare_first_child_id').val();
            const secondChildId = $('#compare_second_child_id').val();

            if (!firstChildId || !secondChildId || firstChildId === secondChildId) {
                $().notifa({
                    vers: 1,
                    cssClass: 'error',
                    html: 'Vui lòng chọn 2 thiếu nhi khác nhau.',
                    delay: 2500
                });
                return;
            }

            $('#children_compare_export_modal').modal('hide');
            Livewire.dispatch('submitCompareChildrenExport', [firstChildId, secondChildId]);
        });

        window.addEventListener('showChildModal', function() {
            $('#children_modal').modal('show');
        });

        $('#children_modal').off('shown.bs.modal.children').on('shown.bs.modal.children', function() {
            rebuildChildrenSelectpicker('#courseModal', childrenSelectpickerConfigs['#courseModal']);
            rebuildChildrenSelectpicker('#sectorModal', childrenSelectpickerConfigs['#sectorModal']);
            rebuildChildrenSelectpicker('#trang_thai', childrenSelectpickerConfigs['#trang_thai']);
        });

        $('#children_modal form').off('submit.children').on('submit.children', function(e) {
            e.preventDefault();

            let courseModal = getSelectedValues('#courseModal');
            let sectorModal = getSelectedValues('#sectorModal');
            let trang_thai = getSelectedValues('#trang_thai');

            courseModal = parseInt(courseModal[0], 10);
            sectorModal = parseInt(sectorModal[0], 10);
            trang_thai = trang_thai.join(',');

            Livewire.dispatch('submitChildFormModal', [courseModal, sectorModal, trang_thai]);
        });

        window.addEventListener('hideChildModal', function() {
            $('#children_modal').modal('hide');
        });

        window.addEventListener('showChildrenAvatar', function() {
            $('#children_avatar').modal('show');
            var id = event.detail[0].id;

            const avatar = document.getElementById('profilePicturePreview');
            const fileInput = document.getElementById('profilePictureFile');


            if (!id) {
                alert('Vui lòng thử lại');
                return;
            }

            $('input[type="file"][id="profilePictureFile"]').kropify({
                preview: 'image#profilePicturePreview',
                viewMode: 1,
                aspectRatio: 1,
                cancelButtonText: 'Hủy',
                resetButtonText: 'Đặt lại',
                cropButtonText: 'Cắt & cập nhật',
                processURL: `/admin/update-profile-picture/`+ id,
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
                        $('#children_avatar').modal('hide');
                        Livewire.dispatch('refreshChildren');
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

        window.addEventListener('hideChildrenAvatar', function() {
            $('#children_avatar').modal('hide');
        });


        window.addEventListener('deleteChildren', function(event) {
            var id = event.detail[0].id;
            var name = event.detail[0].name;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có chắc không muốn xóa Thiếu Nhi ' + name + ' này?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Có, xóa',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('deleteChildrenAction', [id]);
                }
            });
        });

        window.addEventListener('resetPasswordChildren', function(event) {
            var id = event.detail[0].id;
            var name = event.detail[0].name;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có muốn đặt lại mật khẩu cho ' + name + ' này không?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Có, đặt lại mật khẩu',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('resetPasswordChildrenAction', [id]);
                }
            });
        });

        // Nhấn ảnh đại diện: QR theo token_v2 - hiện lên, tải ảnh rồi tắt (giống Xuất QR)
        window.addEventListener('showChildQr', function(event) {
            const cardToCapture = document.querySelector('#child_qr_v2 .card-preview');

            $('#child_qr_v2').modal('show');

            $('#child_qr_v2').on('shown.bs.modal', function() {
                var name = event.detail[0].name;
                var times = event.detail[0].times;

                html2canvas(cardToCapture, {
                    allowTaint: true,
                    useCORS: true,
                    scale: 2
                }).then(canvas => {
                    const downloadLink = document.createElement('a');
                    downloadLink.href = canvas.toDataURL('image/png');
                    downloadLink.download =  name + '-L' + times + '.png';
                    downloadLink.click();
                });

                $('#child_qr_v2').off('shown.bs.modal');
                $('#child_qr_v2').modal('hide');

            });
        });

        window.addEventListener('showChildCard', function(event) {
            const cardToCapture = document.querySelector('#child_card .card-preview');

            $('#child_card').modal('show');

            $('#child_card').on('shown.bs.modal', function() {
                var name = event.detail[0].name;
                var times = event.detail[0].times;

                html2canvas(cardToCapture, {
                    allowTaint: true,
                    useCORS: true,
                    scale: 2
                }).then(canvas => {
                    const downloadLink = document.createElement('a');
                    downloadLink.href = canvas.toDataURL('image/png');
                    downloadLink.download =  name + '-L' + times + '.png';
                    downloadLink.click();
                });

                $('#child_card').off('shown.bs.modal');
                $('#child_card').modal('hide');

            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.pages-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/smyth/Herd/now/resources/views/back/personnel/children.blade.php ENDPATH**/ ?>