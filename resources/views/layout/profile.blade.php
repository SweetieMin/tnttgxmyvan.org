<!DOCTYPE html>
<html lang="vi">

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    @yield('meta_tags')
    <!-- Site favicon -->
    <link rel="canonical" href="https://tnttgxmyvan.org/" />
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "{{ isset(settings()->site_title) ? settings()->site_title : 'Đoàn Thiếu Nhi Thánh Thể Giáo Xứ Mỹ Vân' }}",
            "url": "https://tnttgxmyvan.org",
            "logo": "https://tnttgxmyvan.org/images/site/FAVICON_default.png",
            "sameAs": [
                "https://www.facebook.com/profile.php?id=100069752143507"
            ]
        }
    </script>


    <link rel="icon" type="image/png" sizes="16x16"
        href="/images/site/{{ isset(settings()->site_favicon) ? settings()->site_favicon : 'FAVICON_default.png' }}" />

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Đoàn TNTT Giáo Xứ Mỹ Vân">

    <link rel="apple-touch-icon"
        href="/images/site/{{ isset(settings()->site_favicon) ? settings()->site_favicon : 'FAVICON_default.png' }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/back/vendors/styles/style.css" />

    @stack('stylesheets')

</head>

<body class="login-page">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a href="/">
                    <img src="/images/site/{{ isset(settings()->site_logo) ? settings()->site_logo : 'LOGO_default.png' }}"
                        alt="Logo Đoàn TNTT giáo xứ Mỹ Vân" />
                </a>
            </div>
            <div class="login-menu">
                <ul>
                    @if (auth()->check())
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('admin.login') }}">Đăng nhập</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">

                @yield('content')

            </div>
        </div>
    </div>
    <!-- js -->
    <script src="/back/vendors/scripts/core.js"></script>
    <script src="/back/vendors/scripts/script.min.js"></script>
    <script src="/back/vendors/scripts/layout-settings.js"></script>
    @stack('scripts')
</body>

</html>