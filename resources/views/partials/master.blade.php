<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ env('APP_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

	@include('partials.head')
</head>
<body class="loading" data-sidebar-user="true">
    <div id="wrapper">
        @include('partials.topbar')
        @include('partials.sidebar')

        <div class="content-page">
            <div class="content">
                <!-- Start content -->
                <div class="container-fluid">
                    @include('partials.page-title', [
                        'title' => $title ?? env('APP_NAME'),
                    ])

                    @yield('content')
                </div>
            </div>
            @include('partials.footer')
        </div>
    </div>

    @include('partials.footer-script')
</body>
</html>
