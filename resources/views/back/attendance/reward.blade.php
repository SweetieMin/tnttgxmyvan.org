@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page pageTitle')
@section('meta_tags')
    {!! SEO::generate() !!}
@endsection
@section('content')
    @livewire('attendance.reward')
@endsection

@push('scripts')
    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const qrConfig = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        };

        function startScanner() {

            $("#reader").show();
            $("#start-scan-btn").hide();
            $("#stop-scan-btn").show();
            html5QrCode.start({
                    facingMode: "environment"
                }, qrConfig, onScanSuccess)
                .catch(err => {
                    console.error("Không thể mở camera:", err);
                    alert("Không thể mở camera! Hãy kiểm tra quyền truy cập.");
                });
        }

        function stopScanner() {

            html5QrCode.stop().then(() => {
                //console.log("⏸️ Đã dừng quét");
                $("#stop-scan-btn").hide();
                $("#start-scan-btn").show();

                $("body").css("pointer-events", "auto");
            }).catch(err => console.error("Lỗi khi dừng scanner:", err));
            $("button").prop("disabled", false);
        }


        function onScanSuccess(decodedText, decodedResult) {
            let profileUrl = decodedText;
            let token = profileUrl.split('/profile/')[1];
            if (token) {
                stopScanner();

                Livewire.dispatch('attendanceUser', [token]);


            } else {
                alert("Mã QR của bạn không hợp lệ!");
                stopScanner();
            }
        }

        function isValidUrl(url) {
            const pattern = /^(https?:\/\/)?([a-z0-9]+[.])+[a-z0-9]{2,6}(\/[^\s]*)?$/i;
            return pattern.test(url);
        }

        $("#start-scan-btn").on("click", function() {
            startScanner();
        });

        // Khi nhấn nút dừng quét
        $("#stop-scan-btn").on("click", function() {
            stopScanner();
        });

        //continueScan
        document.addEventListener('continueScan', function(e) {
            startScanner();
        });

        document.addEventListener('showProfileModal', function(e) {
            $('#attendance_modal').modal('show');
            let pictureUrl = event.detail[0].pictureUrl;


            $('#attendance_modal').on('shown.bs.modal', function() {

                var picturePath = pictureUrl ? '/images/users/' + pictureUrl :
                    '/images/users/default-avatar.png';

                var profilePicture = document.getElementById('profilePicturePreview');
                if (profilePicture) {
                    profilePicture.src = picturePath;
                }
            });

            $('#attendance_modal').on('hidden.bs.modal', function() {
                $('input[type="checkbox"]:not(:disabled)').prop('checked', false);
            });
        });

        document.addEventListener('hideProfileModal', function() {
            $('#attendance_modal').modal('hide');
        });
    </script>
@endpush
