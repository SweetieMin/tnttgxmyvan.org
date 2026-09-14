<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>@yield('title', 'Error')</title>

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

    @stack('stylesheets')
</head>

<body>
    <div class="error-page d-flex align-items-center flex-wrap justify-content-center">
        <div class="error-page-wrap text-center">
            <img src="@yield('image', '/images/site/default_error.png')" alt="">
            <h1>@yield('code', 'Error')</h1>
            <h3>@yield('message', 'Something went wrong')</h3>
            <p>@yield('description', 'Please try again later.')</p>
            <div class="mt-3">
                @yield('button_secondary')
            </div>
        </div>
    </div>
</body>

@stack('scripts')

</html>
