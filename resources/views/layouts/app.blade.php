<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>
        @yield('title', config('app.name', 'BMRC Journal'))
    </title>

    <link rel="icon"
      type="image/png"
      href="{{ asset('favicon.png') }}">


    <link rel="apple-touch-icon"
        href="{{ asset('favicon.png') }}">

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Font --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fb;
        }

        /*
        |--------------------------------------------------------------------------
        | Main Content
        |--------------------------------------------------------------------------
        */

        main {
            margin: 0 !important;
            padding: 0 !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Content Wrapper
        |--------------------------------------------------------------------------
        */

        .page-content {
            margin: 0;
            padding: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Authentication Page
        |--------------------------------------------------------------------------
        */

        .auth-page {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0;
            padding: 30px 15px;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 480px;
        }

        /*
        |--------------------------------------------------------------------------
        | Brand
        |--------------------------------------------------------------------------
        */

        .auth-brand {
            text-align: center;
            margin-bottom: 25px;
        }

        .auth-brand-logo {
            width: 65px;
            height: 65px;

            border-radius: 14px;

            background: linear-gradient(
                135deg,
                #0d6efd,
                #084298
            );

            color: #fff;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            font-size: 28px;

            box-shadow:
                0 8px 25px rgba(13, 110, 253, .20);
        }

        .auth-brand h1 {
            font-size: 22px;
            font-weight: 700;

            margin-top: 14px;
            margin-bottom: 4px;
        }

        .auth-brand p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | Authentication Card
        |--------------------------------------------------------------------------
        */

        .auth-card {
            border: 0;
            border-radius: 14px;
            overflow: hidden;

            background: #fff;

            box-shadow:
                0 10px 35px rgba(0, 0, 0, .08);
        }

        .auth-card-header {
            background: #fff;
            text-align: center;

            padding: 28px 25px 20px;

            border-bottom: 1px solid #edf0f5;
        }

        .auth-icon {
            width: 58px;
            height: 58px;

            margin: 0 auto 12px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eaf2ff;
            color: #0d6efd;

            font-size: 25px;
        }

        .auth-card-header h2 {
            font-size: 21px;
            font-weight: 700;

            margin-bottom: 5px;
        }

        .auth-card-header p {
            margin: 0;

            font-size: 13px;
            color: #6c757d;
        }

        .auth-card-body {
            padding: 30px;
        }

        /*
        |--------------------------------------------------------------------------
        | Forms
        |--------------------------------------------------------------------------
        */

        .form-label {
            font-size: 14px;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            min-height: 45px;

            border-radius: 8px;

            border-color: #dce1e7;

            font-size: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #86b7fe;

            box-shadow:
                0 0 0 .2rem rgba(13, 110, 253, .10);
        }

        .btn {
            border-radius: 8px;
            font-weight: 600;
            min-height: 45px;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        @media (max-width: 575.98px) {

            .auth-page {
                min-height: 100vh;

                padding: 20px 12px;
                margin: 0;

                align-items: flex-start;
            }

            .auth-wrapper {
                margin-top: 15px;
            }

            .auth-card-body {
                padding: 22px 18px;
            }

            .auth-card-header {
                padding: 23px 18px 18px;
            }

            .auth-brand {
                margin-bottom: 18px;
            }

            .auth-brand-logo {
                width: 55px;
                height: 55px;
                font-size: 23px;
            }

            .auth-brand h1 {
                font-size: 19px;
            }

            .auth-card-header h2 {
                font-size: 19px;
            }

        }

    </style>

    @stack('styles')

</head>


<body>

    {{-- Main Page Content --}}

    <main class="page-content">
        @yield('content')
    </main>


    {{-- Bootstrap JS --}}

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    @stack('scripts')

</body>

</html>

