@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')
    @livewire('support.complaints')
@endsection

@push('stylesheets')
    <link rel="stylesheet" href="/extra-assets/dropzone/dropzone.css">
@endpush

@push('scripts')
    <script src="/extra-assets/dropzone/dropzone.js"></script>
    <script>
        let uploadedFiles = [];
        let fileName = [];
        let errorFiles = [];
        let alertShown = false;
        Dropzone.autoDiscover = false;

        window.addEventListener('showComplaintModal', function() {
            $('#complaint_modal').modal('show');
            alertShown = false;
            fileName = [];
            uploadedFiles = [];
            $('#messageDropzone').text('');
            if (!Dropzone.instances.length) {
                const dz = new Dropzone("#complaint_picture", {
                    url: "{{ route('admin.support.complaint.upload_image') }}",
                    paramName: "file",
                    maxFilesize: 2,
                    maxFiles: 4,
                    acceptedFiles: "image/*",
                    addRemoveLinks: true,
                    dictDefaultMessage: "Nhấn hoặc kéo thả hình vào đây",
                    dictFallbackMessage: "Trình duyệt của bạn không hỗ trợ kéo thả hình.",
                    dictFileTooBig: "Hình có dung lượng quá lớn. Dung lượng tối đa là 2MB.",
                    dictInvalidFileType: "Định dạng hình không hợp lệ. File phải là hình ảnh.",
                    dictMaxFilesExceeded: "Bạn chỉ được phép upload tối đa 4 hình.",
                    dictRemoveFile: "×",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },

                    success: function(file, response) {
                        file.upload.filename = response.fileName;
                        uploadedFiles.push(response.fileName);
                        Livewire.dispatch('uploadedFilesComplaint', [uploadedFiles]);
                    },

                    maxfilesexceeded: function(file) {
                        if (!alertShown) {
                            alert('Bạn chỉ được phép upload tối đa 4 hình.');
                            alertShown = true;
                        }
                        this.removeFile(file);
                    },

                    removedfile: function(file) {
                        file.previewElement.remove();
                        const index = errorFiles.indexOf(file);
                        if (index !== -1) {
                            errorFiles.splice(index, 1);
                        }
                        if (errorFiles.length === 0) {
                            $('#messageDropzone').text('');
                        }
                        let fileName = file.upload?.filename || file.name;
                        fetch("{{ route('admin.support.complaint.delete_image') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    fileName: fileName
                                })
                            })
                            .then(res => res.json())
                            .then(result => {
                                if (result.success) {
                                    uploadedFiles = uploadedFiles.filter(f => f !== fileName);
                                    Livewire.dispatch('uploadedFilesComplaint', [uploadedFiles]);
                                } else {
                                    console.error("Xoá file thất bại:", result.message);
                                }
                            });
                    },
                    error: function(file, response) {
                        $('#messageDropzone').text(response);
                        errorFiles.push(file);

                        if (file.previewElement) {
                            const removeButton = file.previewElement.querySelector('.dz-remove');
                            if (removeButton) {
                                removeButton.classList.add('text-danger');
                            }
                        }

                        return false;
                    },

                });
            }

        });

        $('#complaint_modal').on('hidden.bs.modal', function() {
            if (Dropzone.instances.length > 0) {
                Dropzone.instances.forEach(dz => {
                    dz.files.forEach(file => {
                        if (file.previewElement) {
                            file.previewElement.remove();
                        }
                    });
                    dz.destroy();
                });
            }
            uploadedFiles = [];
            Livewire.dispatch('uploadedFilesComplaint', [uploadedFiles]);
        });

        window.addEventListener('hideComplaintModal', function() {
            $('#complaint_modal').modal('hide');
            if (Dropzone.instances.length > 0) {
                Dropzone.instances.forEach(dz => {
                    dz.files.forEach(file => {
                        if (file.previewElement) {
                            file.previewElement.remove();
                        }
                    });
                    dz.files = [];
                });
            }
            uploadedFiles = [];
            Livewire.dispatch('uploadedFilesComplaint', [uploadedFiles]);
        });


        window.addEventListener('deleteComplaint', function(event) {
            var id = event.detail[0].id;
            var title = event.detail[0].title;
            $().konfirma({
                title: 'Cảnh báo',
                html: 'Bạn có chắc không muốn xóa ' + title + ' này?',
                cancelButtonText: 'Quay lại',
                confirmButtonText: 'Có, xóa',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: 450,
                allowOutsideClick: false,
                fontSide: '0.87rem',
                done: function() {
                    Livewire.dispatch('deleteComplaintAction', [id]);
                }
            });
        });
    </script>
@endpush
