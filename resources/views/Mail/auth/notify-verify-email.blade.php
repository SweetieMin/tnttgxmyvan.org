<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>{{ $pageTitle }}</title>

    <!-- Site favicon -->
    <link rel="icon" type="image/png" sizes="16x16"
        href="/images/site/{{ isset(settings()->site_favicon) ? settings()->site_favicon : 'FAVICON_default.png' }}" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/style.css" />

</head>

<body class="login-page d-flex align-items-center justify-content-center" style="background: #f4f7fc;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9 col-sm-11">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <a href="/" class="navbar-brand">
                            <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'LOGO_default.png' }}"
                                alt="Logo" class="img-fluid" style="max-width: 250px;">
                        </a>
                    </div>
                    <div class="card-body mt-2">
                        @if ($status === 'error')
                            <div class="alert alert-danger d-flex justify-content-center align-items-center">
                                <img src="/back/vendors/images/error.png" alt="Error"
                                    style="width: 30px; height: 30px; margin-right: 10px;">
                                <span>{{ $notify }}</span>
                            </div>
                        @endif
                        @if ($status === 'info')
                            <div class="alert alert-info d-flex justify-content-center align-items-center">
                                <!-- Hình ảnh thông tin -->
                                <img src="/back/vendors/images/info.png" alt="Info"
                                    style="width: 30px; height: 30px; margin-right: 10px;">
                                <span>{{ $notify }}</span>
                            </div>
                        @endif
                        @if ($status === 'success')
                            <div class="alert alert-success d-flex justify-content-center align-items-center">
                                <!-- Hình ảnh thành công -->
                                <img src="/back/vendors/images/success.png" alt="Success"
                                    style="width: 30px; height: 30px; margin-right: 10px;">
                                <span>{{ $notify }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('admin.login') }}" class="btn btn-primary btn-lg">Đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
