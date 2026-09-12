<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Journal Archive | BMRC Journal
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">

    <section
        class="py-5 text-white"
        style="background:#073b4c;"
    >

        <div class="container">

            <a
                href="{{ url('/') }}"
                class="text-white text-decoration-none"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Back to Journal

            </a>


            <h1 class="mt-3 mb-2">

                Journal Archive

            </h1>


            <p class="mb-0 opacity-75">

                Browse previous issues of the BMRC Journal.

            </p>

        </div>

    </section>


    <div class="container py-5">

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4 p-lg-5">

                <h4 class="mb-3">

                    Journal Archive

                </h4>


                <p class="text-muted mb-0">

                    Previous journal issues will be displayed here.

                </p>

            </div>

        </div>

    </div>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>