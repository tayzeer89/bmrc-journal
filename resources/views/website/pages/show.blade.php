<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>
        {{ $page->title }} | BMRC Journal
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    <style>

        body {
            background: #f5f8fa;
            color: #18313f;
        }


        .page-header {
            background: #073b4c;
            color: white;
            padding: 55px 0;
        }


        .page-content {
            background: #fff;
            border-radius: 12px;
            padding: 40px;
            margin-top: -25px;
            margin-bottom: 60px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }


        .page-content img {
            max-width: 100%;
            height: auto;
        }


        .page-content table {
            width: 100%;
        }

    </style>

</head>


<body>


<section class="page-header">

    <div class="container">

        <a
            href="{{ url('/') }}"
            class="text-white text-decoration-none"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Journal
        </a>


        <h1 class="mt-3 mb-2">

            {{ $page->title }}

        </h1>


        @if($page->short_description)

            <div class="opacity-75">

                {!! $page->short_description !!}

            </div>

        @endif

    </div>

</section>



<div class="container">

    <div class="page-content">

        @if($page->featured_image)

            <img
                src="{{ asset(
                    'storage/' . $page->featured_image
                ) }}"
                alt="{{ $page->title }}"
                class="img-fluid rounded mb-4"
            >

        @endif


        <div class="journal-page-content">

            {!! $page->content !!}

        </div>


</div>


</body>

</html>