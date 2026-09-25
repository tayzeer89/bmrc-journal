<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >


    <title>
        BMRC Journal Online System
    </title>


    {{-- =========================================================
         BOOTSTRAP 5
    ========================================================== --}}

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- =========================================================
         BOOTSTRAP ICONS
    ========================================================== --}}

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >


    {{-- =========================================================
         GOOGLE FONT
    ========================================================== --}}

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <style>

        /* =====================================================
           ROOT
        ====================================================== */

        :root {

            --bmrc-navy: #123B5D;
            --bmrc-navy-dark: #0B2D46;

            --bmrc-teal: #008C95;
            --bmrc-teal-dark: #006F77;

            --bmrc-gold: #C99A2E;

            --bmrc-light: #F4F8FA;

            --bmrc-text: #18313F;

            --bmrc-muted: #647783;

            --bmrc-border: #DDE7EC;

            --white: #FFFFFF;

        }


        * {

            box-sizing: border-box;

        }


        html {

            scroll-behavior: smooth;

        }


        body {

            margin: 0;

            font-family: "Inter", sans-serif;

            color: var(--bmrc-text);

            background: var(--bmrc-light);

        }



        /* =====================================================
           NAVBAR
        ====================================================== */

        .main-navbar {

            background: #073b4c;

            padding: 9px 0;

            box-shadow:
                0 4px 18px
                rgba(0, 0, 0, .12);

            position: relative;

            z-index: 1050;

        }


        .main-navbar .navbar-brand {

            padding-top: 0;

            padding-bottom: 0;

            margin-right: 20px;

            text-decoration: none;

        }


        .brand-logo {

            width: 46px;

            height: 46px;

            min-width: 46px;

            border-radius: 10px;

            background: #ffffff;

            display: flex;

            align-items: center;

            justify-content: center;

            margin-right: 11px;

            box-shadow:
                0 3px 10px
                rgba(0, 0, 0, .12);

        }


        .brand-logo i {

            font-size: 24px;

            color: #073b4c;

        }


        .brand-name {

            color: #ffffff;

            font-weight: 800;

            font-size: 17px;

            line-height: 1.2;

            display: block;

        }


        .brand-subtitle {

            color:
                rgba(
                    255,
                    255,
                    255,
                    .68
                );

            font-size: 9px;

            display: block;

            line-height: 1.4;

            margin-top: 2px;

        }



        /* =====================================================
           NAV LINKS
        ====================================================== */

        .main-navbar .navbar-nav {

            gap: 1px;

        }


        .main-navbar .nav-link {

            color:
                rgba(
                    255,
                    255,
                    255,
                    .90
                );

            font-size: 13px;

            font-weight: 500;

            padding:
                10px 8px !important;

            border-radius: 6px;

            white-space: nowrap;

            transition:
                all .2s ease;

        }


        .main-navbar .nav-link:hover,
        .main-navbar .nav-link:focus,
        .main-navbar .nav-link.show {

            color: #ffffff;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .08
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Dropdown button
        |--------------------------------------------------------------------------
        */

        .main-navbar button.nav-link {

            font-family: inherit;

            cursor: pointer;

            outline: none;

            appearance: none;

        }


        .main-navbar button.nav-link:focus {

            outline: none;

            box-shadow: none;

        }



        /* =====================================================
           DROPDOWN
        ====================================================== */

        .main-navbar .nav-item.dropdown {

            position: relative;

        }


        .main-navbar .dropdown-menu {

            border:
                1px solid
                rgba(
                    0,
                    0,
                    0,
                    .05
                );

            border-radius: 10px;

            padding: 8px;

            margin-top: 8px;

            min-width: 245px;

            background: #ffffff;

            box-shadow:
                0 14px 35px
                rgba(
                    0,
                    0,
                    0,
                    .16
                );

            z-index: 2000;

        }


        .main-navbar .dropdown-item {

            padding:
                10px 12px;

            border-radius: 7px;

            font-size: 13px;

            font-weight: 500;

            color: #343a40;

            display: flex;

            align-items: center;

            white-space: normal;

        }


        .main-navbar .dropdown-item:hover,
        .main-navbar .dropdown-item:focus {

            background: #e8f4f7;

            color: #073b4c;

        }


        .main-navbar .dropdown-item i {

            width: 19px;

            min-width: 19px;

            color: #0b7285;

        }


        .main-navbar .dropdown-divider {

            margin:
                7px 4px;

        }



        /* =====================================================
           LOGIN BUTTON
        ====================================================== */

        .btn-login {

            background: transparent;

            color:
                #ffffff !important;

            border:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    .55
                );

            border-radius: 7px;

            padding:
                8px 14px !important;

            font-size: 13px;

            font-weight: 600;

            transition:
                all .2s ease;

            white-space: nowrap;

        }


        .btn-login:hover {

            background: #ffffff;

            color:
                #073b4c !important;

            border-color:
                #ffffff;

        }



        /* =====================================================
           MOBILE TOGGLER
        ====================================================== */

        .main-navbar .navbar-toggler {

            border:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    .35
                );

            border-radius: 7px;

            padding:
                4px 9px;

            box-shadow: none;

        }


        .main-navbar .navbar-toggler:focus {

            box-shadow: none;

            border-color:
                rgba(
                    255,
                    255,
                    255,
                    .65
                );

        }



        /* =====================================================
           HERO
        ====================================================== */

        .hero {

            min-height: 650px;

            display: flex;

            align-items: center;

            position: relative;

            overflow: hidden;

            background:

                linear-gradient(
                    135deg,
                    #F8FCFD 0%,
                    #E9F4F6 55%,
                    #DCECEF 100%
                );

        }


        .hero::before {

            content: "";

            position: absolute;

            width: 520px;

            height: 520px;

            border-radius: 50%;

            background:
                rgba(
                    0,
                    140,
                    149,
                    .06
                );

            right: -180px;

            top: -180px;

        }


        .hero::after {

            content: "";

            position: absolute;

            width: 380px;

            height: 380px;

            border-radius: 50%;

            background:
                rgba(
                    201,
                    154,
                    46,
                    .07
                );

            left: -180px;

            bottom: -180px;

        }


        .hero-content {

            position: relative;

            z-index: 2;

        }


        .hero-badge {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .85
                );

            border:
                1px solid
                var(--bmrc-border);

            color:
                var(--bmrc-teal-dark);

            padding:
                9px 16px;

            border-radius: 30px;

            font-size: 12px;

            font-weight: 700;

            margin-bottom: 22px;

            box-shadow:
                0 5px 20px
                rgba(
                    18,
                    59,
                    93,
                    .06
                );

        }


        .hero-badge i {

            color:
                var(--bmrc-gold);

        }


        .hero h1 {

            font-size:
                clamp(
                    38px,
                    5vw,
                    62px
                );

            font-weight: 800;

            line-height: 1.08;

            letter-spacing: -1.5px;

            margin-bottom: 22px;

            color:
                var(--bmrc-navy);

        }


        .hero h1 span {

            display: block;

            color:
                var(--bmrc-teal);

        }


        .hero-description {

            max-width: 650px;

            font-size: 17px;

            line-height: 1.8;

            color:
                var(--bmrc-muted);

            margin-bottom: 32px;

        }


        .hero-buttons {

            display: flex;

            flex-wrap: wrap;

            gap: 12px;

        }


        .btn-hero-primary {

            background:
                var(--bmrc-teal);

            border-color:
                var(--bmrc-teal);

            color:
                var(--white);

            padding:
                13px 24px;

            border-radius: 8px;

            font-weight: 600;

        }


        .btn-hero-primary:hover {

            background:
                var(--bmrc-teal-dark);

            border-color:
                var(--bmrc-teal-dark);

            color:
                var(--white);

        }


        .btn-hero-secondary {

            background:
                var(--bmrc-navy);

            border-color:
                var(--bmrc-navy);

            color:
                var(--white);

            padding:
                13px 24px;

            border-radius: 8px;

            font-weight: 600;

        }


        .btn-hero-secondary:hover {

            background:
                var(--bmrc-navy-dark);

            border-color:
                var(--bmrc-navy-dark);

            color:
                var(--white);

        }



        /* =====================================================
           HERO CARD
        ====================================================== */

        .hero-card {

            position: relative;

            z-index: 2;

            background:
                rgba(
                    255,
                    255,
                    255,
                    .95
                );

            border-radius: 18px;

            padding: 32px;

            border:
                1px solid
                var(--bmrc-border);

            box-shadow:
                0 25px 60px
                rgba(
                    18,
                    59,
                    93,
                    .13
                );

        }


        .hero-card-top {

            display: flex;

            align-items: center;

            gap: 15px;

            margin-bottom: 22px;

        }


        .hero-card-icon {

            width: 62px;

            height: 62px;

            flex-shrink: 0;

            border-radius: 14px;

            background:
                rgba(
                    0,
                    140,
                    149,
                    .10
                );

            color:
                var(--bmrc-teal);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 28px;

        }


        .hero-card-label {

            color:
                var(--bmrc-gold);

            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .8px;

        }


        .hero-card h4 {

            font-weight: 700;

            color:
                var(--bmrc-navy);

            margin-bottom: 12px;

        }


        .hero-card p {

            color:
                var(--bmrc-muted);

            line-height: 1.7;

            font-size: 14px;

        }


        .hero-stat {

            border-top:
                1px solid
                var(--bmrc-border);

            padding-top: 18px;

            margin-top: 22px;

        }


        .hero-stat strong {

            font-size: 25px;

            color:
                var(--bmrc-navy);

        }


        .hero-stat small {

            color:
                var(--bmrc-muted);

        }



        /* =====================================================
           GENERAL SECTION
        ====================================================== */

        .section {

            padding:
                90px 0;

        }


        .section-title {

            text-align: center;

            margin-bottom: 55px;

        }


        .section-label {

            display: inline-block;

            color:
                var(--bmrc-teal);

            font-size: 12px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 1.5px;

            margin-bottom: 10px;

        }


        .section-title h2 {

            font-size: 36px;

            font-weight: 800;

            color:
                var(--bmrc-navy);

            margin-bottom: 14px;

        }


        .section-title p {

            color:
                var(--bmrc-muted);

            max-width: 680px;

            line-height: 1.7;

            margin: auto;

        }



        /* =====================================================
           SERVICES
        ====================================================== */

        .service-card {

            height: 100%;

            background:
                var(--white);

            border:
                1px solid
                var(--bmrc-border);

            border-radius: 16px;

            padding: 32px;

            transition:
                .25s ease;

            position: relative;

            overflow: hidden;

        }


        .service-card::before {

            content: "";

            position: absolute;

            left: 0;

            top: 0;

            width: 4px;

            height: 100%;

            background:
                var(--bmrc-teal);

            transform:
                scaleY(0);

            transition:
                .25s ease;

        }


        .service-card:hover {

            transform:
                translateY(-6px);

            box-shadow:
                0 18px 40px
                rgba(
                    18,
                    59,
                    93,
                    .10
                );

            border-color:
                #C9DCE2;

        }


        .service-card:hover::before {

            transform:
                scaleY(1);

        }


        .service-icon {

            width: 60px;

            height: 60px;

            border-radius: 14px;

            background:
                rgba(
                    0,
                    140,
                    149,
                    .09
                );

            color:
                var(--bmrc-teal);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 26px;

            margin-bottom: 22px;

        }


        .service-card h5 {

            color:
                var(--bmrc-navy);

            font-weight: 700;

            font-size: 20px;

            margin-bottom: 12px;

        }


        .service-card p {

            color:
                var(--bmrc-muted);

            line-height: 1.7;

            font-size: 14px;

        }


        .service-list {

            margin-top: 22px;

        }


        .service-list li {

            color: #526A76;

            font-size: 14px;

            margin-bottom: 10px;

        }


        .service-list i {

            color:
                var(--bmrc-teal);

        }


        .btn-service {

            background:
                var(--bmrc-navy);

            border-color:
                var(--bmrc-navy);

            color:
                var(--white);

            border-radius: 7px;

            padding:
                10px 18px;

            font-size: 13px;

            font-weight: 600;

        }


        .btn-service:hover {

            background:
                var(--bmrc-teal);

            border-color:
                var(--bmrc-teal);

            color:
                var(--white);

        }



        /* =====================================================
           WORKFLOW
        ====================================================== */

        .workflow-section {

            background:
                var(--bmrc-navy);

            color:
                var(--white);

        }


        .workflow-section .section-label {

            color:
                #DDB65A;

        }


        .workflow-section .section-title h2 {

            color:
                var(--white);

        }


        .workflow-section .section-title p {

            color:
                rgba(
                    255,
                    255,
                    255,
                    .68
                );

        }


        .workflow-step {

            text-align: center;

            position: relative;

        }


        .workflow-number {

            width: 62px;

            height: 62px;

            border-radius: 50%;

            background:
                var(--white);

            color:
                var(--bmrc-navy);

            display: flex;

            align-items: center;

            justify-content: center;

            margin:
                0 auto 20px;

            font-size: 17px;

            font-weight: 800;

            border:
                4px solid
                rgba(
                    201,
                    154,
                    46,
                    .45
                );

        }


        .workflow-step h5 {

            font-weight: 700;

            color:
                var(--white);

            margin-bottom: 10px;

        }


        .workflow-step p {

            color:
                rgba(
                    255,
                    255,
                    255,
                    .62
                );

            font-size: 13px;

            line-height: 1.7;

            max-width: 220px;

            margin: auto;

        }



        /* =====================================================
           ABOUT
        ====================================================== */

        .about-card {

            background:
                var(--white);

            border-radius: 18px;

            padding: 36px;

            border:
                1px solid
                var(--bmrc-border);

            box-shadow:
                0 12px 35px
                rgba(
                    18,
                    59,
                    93,
                    .06
                );

        }


        .about-card h2 {

            color:
                var(--bmrc-navy);

        }


        .about-list {

            list-style: none;

            padding: 0;

            margin:
                25px 0 0;

        }


        .about-list li {

            display: flex;

            gap: 12px;

            margin-bottom: 15px;

            color: #526A76;

            font-size: 14px;

        }


        .about-list i {

            color:
                var(--bmrc-teal);

            font-size: 18px;

        }


        .about-feature {

            background:
                var(--white);

            border:
                1px solid
                var(--bmrc-border);

            border-radius: 14px;

            padding: 24px;

            height: 100%;

            transition:
                .2s ease;

        }


        .about-feature:hover {

            box-shadow:
                0 10px 25px
                rgba(
                    18,
                    59,
                    93,
                    .07
                );

        }


        .about-feature i {

            font-size: 30px;

            color:
                var(--bmrc-teal);

        }


        .about-feature h5 {

            color:
                var(--bmrc-navy);

        }



        /* =====================================================
           CTA
        ====================================================== */

        .cta {

            padding:
                75px 0;

            background:

                linear-gradient(
                    135deg,
                    var(--bmrc-teal),
                    var(--bmrc-teal-dark)
                );

            color:
                var(--white);

            position: relative;

            overflow: hidden;

        }


        .cta::after {

            content: "";

            position: absolute;

            width: 350px;

            height: 350px;

            border-radius: 50%;

            border:
                60px solid
                rgba(
                    255,
                    255,
                    255,
                    .05
                );

            right: -120px;

            top: -160px;

        }


        .cta h2 {

            font-size: 38px;

            font-weight: 800;

        }


        .cta p {

            color:
                rgba(
                    255,
                    255,
                    255,
                    .82
                );

            max-width: 650px;

            line-height: 1.7;

            margin:
                15px auto 28px;

        }


        .cta .btn {

            color:
                var(--bmrc-navy);

            font-weight: 700;

            border-radius: 8px;

            padding:
                12px 24px;

        }



        /* =====================================================
           FOOTER
        ====================================================== */

        footer {

            background:
                var(--bmrc-navy-dark);

            color:
                rgba(
                    255,
                    255,
                    255,
                    .65
                );

            padding:
                50px 0 20px;

        }


        footer h5 {

            color:
                var(--white);

            font-weight: 700;

        }


        footer p {

            line-height: 1.7;

        }


        footer a {

            color:
                rgba(
                    255,
                    255,
                    255,
                    .65
                );

            text-decoration: none;

            transition:
                .2s ease;

        }


        footer a:hover {

            color:
                var(--white);

        }


        .footer-brand {

            display: flex;

            align-items: center;

            gap: 12px;

        }


        .footer-brand-icon {

            width: 42px;

            height: 42px;

            border-radius: 9px;

            background:
                var(--white);

            color:
                var(--bmrc-teal);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 21px;

        }


        .footer-bottom {

            border-top:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    .1
                );

            margin-top: 35px;

            padding-top: 20px;

            font-size: 12px;

            text-align: center;

        }


        .developer-credit {

            margin-top: 8px;

        }


        .developer-separator {

            margin:
                0 5px;

        }



        /* =====================================================
           TABLET / MOBILE NAVBAR
        ====================================================== */

        @media (
            max-width: 991.98px
        ) {

            .main-navbar {

                padding:
                    8px 0;

            }


            .main-navbar .container {

                max-width: 100%;

            }


            .main-navbar .navbar-collapse {

                margin-top: 10px;

                padding-top: 10px;

                padding-bottom: 12px;

                border-top:
                    1px solid
                    rgba(
                        255,
                        255,
                        255,
                        .12
                    );

            }


            .main-navbar .navbar-nav {

                align-items:
                    stretch !important;

                gap: 2px;

                max-height:
                    calc(
                        100vh - 95px
                    );

                overflow-y: auto;

                padding-right: 4px;

            }


            .main-navbar .nav-item {

                width: 100%;

            }


            .main-navbar .nav-link {

                width: 100%;

                padding:
                    11px 12px !important;

                margin: 0;

                border-radius: 7px;

                font-size: 14px;

            }


            .main-navbar button.nav-link {

                text-align: left;

            }


            .main-navbar .dropdown-menu {

                position:
                    static !important;

                transform:
                    none !important;

                float: none;

                width: 100%;

                min-width: 100%;

                margin:
                    3px 0 6px;

                padding: 6px;

                border: none;

                border-radius: 8px;

                box-shadow: none;

                background:
                    rgba(
                        255,
                        255,
                        255,
                        .97
                    );

            }


            .main-navbar
            .dropdown-menu.show {

                display: block;

            }


            .main-navbar .dropdown-item {

                padding:
                    10px 11px;

                font-size: 13px;

            }


            .navbar-action-item {

                margin-left:
                    0 !important;

                margin-top: 8px;

            }


            .btn-login {

                display: block;

                width: 100%;

                text-align: center;

                padding:
                    10px 15px !important;

            }


            .hero {

                padding:
                    85px 0;

            }


            .hero-card {

                margin-top: 25px;

            }

        }



        /* =====================================================
           MOBILE
        ====================================================== */

        @media (
            max-width: 575.98px
        ) {

            .main-navbar {

                padding:
                    7px 0;

            }


            .brand-logo {

                width: 40px;

                height: 40px;

                min-width: 40px;

                border-radius: 8px;

                margin-right: 9px;

            }


            .brand-logo i {

                font-size: 21px;

            }


            .brand-name {

                font-size: 15px;

            }


            .brand-subtitle {

                font-size: 8px;

                max-width: 175px;

                white-space: normal;

            }


            .main-navbar .navbar-toggler {

                padding:
                    3px 8px;

            }


            .main-navbar .nav-link {

                font-size: 13px;

                padding:
                    10px 11px !important;

            }


            .main-navbar .dropdown-item {

                font-size: 12.5px;

                padding:
                    9px 10px;

            }


            .section {

                padding:
                    65px 0;

            }


            .section-title {

                margin-bottom: 38px;

            }


            .section-title h2 {

                font-size: 29px;

            }


            .hero {

                min-height: auto;

                padding:
                    65px 0;

            }


            .hero h1 {

                font-size: 40px;

                letter-spacing: -1px;

            }


            .hero-description {

                font-size: 15px;

            }


            .hero-buttons {

                flex-direction: column;

            }


            .hero-buttons .btn {

                width: 100%;

            }


            .hero-card {

                padding: 25px;

            }


            .cta h2 {

                font-size: 30px;

            }

        }



        @media (
            max-width: 390px
        ) {

            .brand-subtitle {

                display: none;

            }


            .brand-name {

                font-size: 14px;

            }


            .brand-logo {

                width: 38px;

                height: 38px;

                min-width: 38px;

            }

        }

    </style>

</head>


<body>


{{-- =============================================================
     NAVIGATION DATA
============================================================= --}}

@php

    /*
    |--------------------------------------------------------------------------
    | Main CMS menu groups
    |--------------------------------------------------------------------------
    */

    $menuCollection =
        $journalMenus
        ?? collect();


    /*
    |--------------------------------------------------------------------------
    | About
    |--------------------------------------------------------------------------
    */

    $aboutPage =
        $menuCollection
            ->get(
                'about',
                collect()
            )
            ->first();


    /*
    |--------------------------------------------------------------------------
    | Editorial Board
    |--------------------------------------------------------------------------
    */

    $editorialBoardPage =
        $menuCollection
            ->get(
                'editorial_board',
                collect()
            )
            ->first();


    /*
    |--------------------------------------------------------------------------
    | Journal Pages
    |--------------------------------------------------------------------------
    */

    $journalNavPages =
        $menuCollection
            ->get(
                'journal',
                collect()
            );


    $aboutJournalPage =
        $journalNavPages
            ->firstWhere(
                'slug',
                'about-the-journal'
            );


    $publicationEthicsPage =
        $journalNavPages
            ->firstWhere(
                'slug',
                'publication-ethics'
            );


    /*
    |--------------------------------------------------------------------------
    | Author Pages
    |--------------------------------------------------------------------------
    */

    $authorPages =
        $menuCollection
            ->get(
                'authors',
                collect()
            );


    /*
    |--------------------------------------------------------------------------
    | Reviewer Pages
    |--------------------------------------------------------------------------
    */

    $reviewerPages =
        $menuCollection
            ->get(
                'reviewers',
                collect()
            );

@endphp



{{-- =============================================================
     PROFESSIONAL DYNAMIC JOURNAL NAVBAR
============================================================= --}}

<nav
    class="navbar navbar-expand-lg main-navbar"
    id="journalMainNavbar"
>

    <div class="container">


        {{-- =====================================================
             BRAND
        ====================================================== --}}

        <a
            class="navbar-brand d-flex align-items-center"
            href="{{ url('/') }}"
        >

            <div class="brand-logo">

                <i class="bi bi-journal-medical"></i>

            </div>


            <div>

                <span class="brand-name">

                    BMRC Journal

                </span>


                <span class="brand-subtitle">

                    Bangladesh Medical Research Council

                </span>

            </div>

        </a>



        {{-- =====================================================
             MOBILE TOGGLE
        ====================================================== --}}

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <i class="bi bi-list text-white fs-3"></i>

        </button>



        {{-- =====================================================
             NAVIGATION
        ====================================================== --}}

        <div
            class="collapse navbar-collapse"
            id="mainNavbar"
        >

            <ul
                class="navbar-nav
                       ms-auto
                       align-items-lg-center"
            >


                {{-- =================================================
                     HOME
                ================================================== --}}

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="{{ url('/') }}"
                    >

                        <i class="bi bi-house-door me-1"></i>

                        Home

                    </a>

                </li>



                {{-- =================================================
                     ABOUT
                ================================================== --}}

                @if($aboutPage)

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ route(
                                'journal.page',
                                $aboutPage->slug
                            ) }}"
                        >

                            About

                        </a>

                    </li>

                @endif



                {{-- =================================================
                     EDITORIAL BOARD
                ================================================== --}}

                @if($editorialBoardPage)

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="{{ route(
                                'journal.page',
                                $editorialBoardPage->slug
                            ) }}"
                        >

                            Editorial Board

                        </a>

                    </li>

                @endif


                {{-- =========================================================
                    JOURNAL DROPDOWN
                ========================================================= --}}

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | All published journal menu pages
                    |--------------------------------------------------------------------------
                    */

                    $journalPages =
                        ($journalMenus ?? collect())
                            ->get(
                                'journal',
                                collect()
                            );

                @endphp


                <li class="nav-item dropdown">

                    <button
                        class="nav-link
                            dropdown-toggle
                            border-0
                            bg-transparent"
                        type="button"
                        id="journalDropdown"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        Journal
                    </button>


                    <ul
                        class="dropdown-menu"
                        aria-labelledby="journalDropdown"
                    >


                        {{-- =====================================================
                            DYNAMIC JOURNAL CMS PAGES
                        ====================================================== --}}

                        @foreach($journalPages as $page)

                            <li>

                                <a
                                    class="dropdown-item"
                                    href="{{ route(
                                        'journal.page',
                                        $page->slug
                                    ) }}"
                                >

                                    <i
                                        class="bi
                                            bi-journal-text
                                            me-2"
                                    ></i>

                                    {{ $page->title }}

                                </a>

                            </li>

                        @endforeach



                        {{-- =====================================================
                            SEPARATOR
                        ====================================================== --}}

                        @if($journalPages->isNotEmpty())

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                        @endif



                        {{-- =====================================================
                            CURRENT ISSUE
                        ====================================================== --}}

                        <li>

                            <a
                                class="dropdown-item"
                                href="{{ route(
                                    'journal.current-issue'
                                ) }}"
                            >

                                <i
                                    class="bi
                                        bi-file-earmark-text
                                        me-2"
                                ></i>

                                Current Issue

                            </a>

                        </li>



                        {{-- =====================================================
                            JOURNAL ARCHIVE
                        ====================================================== --}}

                        <li>

                            <a
                                class="dropdown-item"
                                href="{{ route(
                                    'journal.archive'
                                ) }}"
                            >

                                <i
                                    class="bi
                                        bi-archive
                                        me-2"
                                ></i>

                                Journal Archive

                            </a>

                        </li>


                    </ul>

                </li>



                {{-- =================================================
                     AUTHORS DROPDOWN
                ================================================== --}}

                @if(
                    $authorPages
                        ->isNotEmpty()
                )

                    <li class="nav-item dropdown">


                        <button
                            class="nav-link
                                   dropdown-toggle
                                   border-0
                                   bg-transparent"
                            type="button"
                            id="authorDropdown"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="true"
                            aria-expanded="false"
                        >

                            Authors

                        </button>


                        <ul
                            class="dropdown-menu"
                            aria-labelledby="authorDropdown"
                        >


                            @foreach(
                                $authorPages
                                as $page
                            )

                                <li>

                                    <a
                                        class="dropdown-item"
                                        href="{{ route(
                                            'journal.page',
                                            $page->slug
                                        ) }}"
                                    >


                                        @switch($page->slug)


                                            @case(
                                                'author-guidelines'
                                            )

                                                <i
                                                    class="bi
                                                           bi-person-lines-fill
                                                           me-2"
                                                ></i>

                                                @break


                                            @case(
                                                'article-processing'
                                            )

                                                <i
                                                    class="bi
                                                           bi-cash-coin
                                                           me-2"
                                                ></i>

                                                @break


                                            @case(
                                                'submission-guidelines'
                                            )

                                                <i
                                                    class="bi
                                                           bi-file-earmark-arrow-up
                                                           me-2"
                                                ></i>

                                                @break


                                            @default

                                                <i
                                                    class="bi
                                                           bi-file-text
                                                           me-2"
                                                ></i>


                                        @endswitch


                                        {{ $page->title }}

                                    </a>

                                </li>

                            @endforeach


                        </ul>

                    </li>

                @endif



                {{-- =================================================
                     REVIEWERS DROPDOWN
                ================================================== --}}

                <li class="nav-item dropdown">


                    <button
                        class="nav-link
                               dropdown-toggle
                               border-0
                               bg-transparent"
                        type="button"
                        id="reviewerDropdown"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="true"
                        aria-expanded="false"
                    >

                        Reviewers

                    </button>


                    <ul
                        class="dropdown-menu
                               dropdown-menu-end"
                        aria-labelledby="reviewerDropdown"
                    >


                        {{-- REVIEWER CMS PAGES --}}

                        @foreach(
                            $reviewerPages
                            as $page
                        )

                            <li>

                                <a
                                    class="dropdown-item"
                                    href="{{ route(
                                        'journal.page',
                                        $page->slug
                                    ) }}"
                                >

                                    <i
                                        class="bi
                                               bi-person-check
                                               me-2"
                                    ></i>

                                    {{ $page->title }}

                                </a>

                            </li>

                        @endforeach



                        @if(
                            $reviewerPages
                                ->isNotEmpty()
                        )

                            <li>

                                <hr class="dropdown-divider">

                            </li>

                        @endif


                      {{-- Reviewer Directory --}}
                        
                        <li>

                            <a
                                class="dropdown-item"
                                href="{{ route(
                                    'journal.reviewers.directory'
                                ) }}"
                            >

                                <i
                                    class="bi
                                           bi-box-arrow-in-right
                                           me-2"
                                ></i>

                                Reviewer Directory

                            </a>

                        </li>


                        {{-- REVIEWER LOGIN --}}

                        <li>

                            <a
                                class="dropdown-item"
                                href="{{ route('reviewer.login') }}"
                            >

                                <i
                                    class="bi
                                           bi-box-arrow-in-right
                                           me-2"
                                ></i>

                                Reviewer Login

                            </a>

                        </li>



                        {{-- BECOME REVIEWER --}}

                        <li>

                            <a
                                class="dropdown-item"
                                href="{{ route(
                                    'reviewer.register'
                                ) }}"
                            >

                                <i
                                    class="bi
                                           bi-person-plus
                                           me-2"
                                ></i>

                                Become a Reviewer

                            </a>

                        </li>


                    </ul>

                </li>



                {{-- =================================================
                     SUBMISSION PROCESS
                ================================================== --}}

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="{{ url('/') }}#workflow"
                    >

                        Submission Process

                    </a>

                </li>



                {{-- =================================================
                     AUTHOR LOGIN
                ================================================== --}}

                <li
                    class="nav-item
                           ms-lg-2
                           navbar-action-item"
                >

                    <a
                        href="{{ route(
                            'author.login'
                        ) }}"
                        class="btn btn-login"
                    >

                        <i
                            class="bi
                                   bi-box-arrow-in-right
                                   me-1"
                        ></i>

                        Login

                    </a>

                </li>


            </ul>

        </div>

    </div>

</nav>



{{-- =============================================================
     HERO
============================================================= --}}

<section
    class="hero"
    id="home"
>

    <div class="container">

        <div
            class="row
                   align-items-center
                   g-5"
        >


            <div class="col-lg-7">

                <div class="hero-content">


                    <div class="hero-badge">

                        <i class="bi bi-patch-check-fill"></i>

                        BMRC Journal Online System

                    </div>


                    <h1>

                        Advancing Biomedical Research

                        <span>
                            Through Knowledge
                        </span>

                    </h1>


                    <p class="hero-description">

                        The BMRC Journal Online System is a secure
                        digital platform for scholarly manuscript
                        submission, peer review, editorial processing,
                        and publication.

                        It enables researchers to efficiently submit,
                        track, and manage manuscripts while supporting
                        a transparent and streamlined publication
                        process.

                    </p>


                    <div class="hero-buttons">


                        <a
                            href="{{ route(
                                'author.login'
                            ) }}"
                            class="btn btn-hero-primary"
                        >

                            <i
                                class="bi
                                       bi-file-earmark-plus
                                       me-2"
                            ></i>

                            Submit Your Article

                        </a>


                        <a
                            href="{{ route(
                                'reviewer.register'
                            ) }}"
                            class="btn btn-hero-secondary"
                        >

                            <i
                                class="bi
                                       bi-person-check
                                       me-2"
                            ></i>

                            Become a Reviewer

                        </a>


                    </div>

                </div>

            </div>



            <div class="col-lg-5">

                <div class="hero-card">


                    <div class="hero-card-top">


                        <div class="hero-card-icon">

                            <i class="bi bi-journal-richtext"></i>

                        </div>


                        <div>

                            <div class="hero-card-label">

                                Digital Journal Platform

                            </div>


                            <strong class="text-dark">

                                BMRC Journal

                            </strong>

                        </div>


                    </div>


                    <h4>

                        Online Article Submission

                    </h4>


                    <p>

                        Submit manuscripts electronically,
                        upload supporting documents and track
                        your article throughout the submission
                        and peer-review process.

                    </p>


                    <div class="hero-stat">

                        <div class="row">


                            <div class="col-6">

                                <strong>
                                    100%
                                </strong>

                                <small class="d-block">
                                    Digital Workflow
                                </small>

                            </div>


                            <div class="col-6">

                                <strong>
                                    Secure
                                </strong>

                                <small class="d-block">
                                    Online Platform
                                </small>

                            </div>


                        </div>

                    </div>

                </div>

            </div>


        </div>

    </div>

</section>



{{-- =============================================================
     SERVICES
============================================================= --}}

<section
    class="section"
    id="services"
>

    <div class="container">


        <div class="section-title">

            <span class="section-label">

                Online Services

            </span>


            <h2>

                BMRC Journal Services

            </h2>


            <p>

                Access services available to authors
                and reviewers through the BMRC Journal
                Online System.

            </p>

        </div>



        <div
            class="row
                   g-4
                   justify-content-center"
        >


            {{-- =================================================
                 ARTICLE SUBMISSION
            ================================================== --}}

            <div
                class="col-md-6
                       col-lg-5"
            >

                <div class="service-card">


                    <div class="service-icon">

                        <i class="bi bi-file-earmark-text"></i>

                    </div>


                    <h5>
                        Article Submission
                    </h5>


                    <p>

                        Submit your original research article
                        through the BMRC Journal Online System.

                        Upload your manuscript and supporting
                        documents, monitor submission status,
                        and receive updates online.

                    </p>


                    <ul
                        class="service-list
                               list-unstyled"
                    >


                        <li>

                            <i
                                class="bi
                                       bi-check-circle-fill
                                       me-2"
                            ></i>

                            Online manuscript submission

                        </li>


                        <li>

                            <i
                                class="bi
                                       bi-check-circle-fill
                                       me-2"
                            ></i>

                            Supporting document upload

                        </li>


                        <li>

                            <i
                                class="bi
                                       bi-check-circle-fill
                                       me-2"
                            ></i>

                            Submission status tracking

                        </li>


                        <li>

                            <i
                                class="bi
                                       bi-check-circle-fill
                                       me-2"
                            ></i>

                            Editorial communication

                        </li>


                    </ul>


                    <div class="mt-4">

                        <a
                            href="{{ route(
                                'author.login'
                            ) }}"
                            class="btn btn-service"
                        >

                            <i
                                class="bi
                                       bi-box-arrow-in-right
                                       me-2"
                            ></i>

                            Login to Submit

                        </a>

                    </div>


                </div>

            </div>



            {{-- =================================================
                 REVIEWER
            ================================================== --}}

            <div
                class="col-md-6
                       col-lg-5"
            >

                <div class="service-card">


                    <div class="service-icon">

                        <i class="bi bi-person-check"></i>

                    </div>


                    <h5>

                        Become a Reviewer

                    </h5>


                    <p>

                        Qualified professionals and researchers
                        interested in contributing to the BMRC
                        Journal peer-review process can submit
                        their reviewer application online.

                    </p>


                    <ul
                        class="service-list
                               list-unstyled"
                    >


                        <li>

                            <i
                                class="bi
                                       bi-check-circle-fill
                                       me-2"
                            ></i>

                            Online reviewer application

                        </li>


                        <li>

                            <i
                                class="bi
                                       bi-check-circle-fill
                                       me-2"
                            ></i>

                            Professional profile submission

                        </li>


                        <li>

                            <i
                                class="bi
                                       bi-check-circle-fill
                                       me-2"
                            ></i>

                            Areas of expertise

                        </li>


                        <li>

                            <i
                                class="bi
                                       bi-check-circle-fill
                                       me-2"
                            ></i>

                            Participate in peer review

                        </li>


                    </ul>


                    <div class="mt-4">

                        <a
                            href="{{ route(
                                'reviewer.register'
                            ) }}"
                            class="btn btn-service"
                        >

                            <i
                                class="bi
                                       bi-person-plus
                                       me-2"
                            ></i>

                            Apply as Reviewer

                        </a>

                    </div>


                </div>

            </div>


        </div>

    </div>

</section>



{{-- =============================================================
     WORKFLOW
============================================================= --}}

<section
    class="section workflow-section"
    id="workflow"
>

    <div class="container">


        <div class="section-title">

            <span class="section-label">

                Publication Journey

            </span>


            <h2>

                How Article Submission Works

            </h2>


            <p>

                A structured digital workflow designed
                to make manuscript submission simple,
                transparent and efficient.

            </p>

        </div>



        <div class="row g-5">


            <div class="col-md-3">

                <div class="workflow-step">


                    <div class="workflow-number">

                        01

                    </div>


                    <h5>
                        Register
                    </h5>


                    <p>

                        Create your author account
                        and complete your profile.

                    </p>


                </div>

            </div>



            <div class="col-md-3">

                <div class="workflow-step">


                    <div class="workflow-number">

                        02

                    </div>


                    <h5>
                        Submit
                    </h5>


                    <p>

                        Upload your manuscript and
                        required supporting documents.

                    </p>


                </div>

            </div>



            <div class="col-md-3">

                <div class="workflow-step">


                    <div class="workflow-number">

                        03

                    </div>


                    <h5>
                        Peer Review
                    </h5>


                    <p>

                        The manuscript goes through
                        the appropriate peer-review process.

                    </p>


                </div>

            </div>



            <div class="col-md-3">

                <div class="workflow-step">


                    <div class="workflow-number">

                        04

                    </div>


                    <h5>
                        Publication
                    </h5>


                    <p>

                        Accepted articles proceed through
                        final editorial processing.

                    </p>


                </div>

            </div>


        </div>

    </div>

</section>



{{-- =============================================================
     ABOUT
============================================================= --}}

<section
    class="section"
    id="about"
>

    <div class="container">

        <div
            class="row
                   align-items-center
                   g-5"
        >


            <div class="col-lg-6">

                <div class="about-card">


                    <span class="section-label">

                        About the Platform

                    </span>


                    <h2 class="fw-bold mb-3">

                        BMRC Journal Online System

                    </h2>


                    <p
                        class="text-muted"
                        style="line-height:1.8;"
                    >

                        The BMRC Journal Online System is a
                        digital platform designed to support
                        scholarly article submission and the
                        peer-review process of the Bangladesh
                        Medical Research Council.

                    </p>


                    <p
                        class="text-muted"
                        style="line-height:1.8;"
                    >

                        The system provides researchers with
                        a convenient and secure way to submit
                        manuscripts, upload documents, monitor
                        submission progress and communicate
                        during the publication process.

                    </p>


                    <ul class="about-list">


                        <li>

                            <i class="bi bi-check-circle-fill"></i>

                            Secure online manuscript submission

                        </li>


                        <li>

                            <i class="bi bi-check-circle-fill"></i>

                            Structured peer-review process

                        </li>


                        <li>

                            <i class="bi bi-check-circle-fill"></i>

                            Submission status tracking

                        </li>


                        <li>

                            <i class="bi bi-check-circle-fill"></i>

                            Digital communication

                        </li>


                    </ul>


                </div>

            </div>



            <div class="col-lg-6">


                <span class="section-label">

                    Research & Publication

                </span>


                <h2
                    class="fw-bold mb-4"
                    style="
                        color:
                            var(--bmrc-navy);
                    "
                >

                    Supporting Quality

                    <br>

                    Biomedical Research

                </h2>


                <p
                    class="text-muted"
                    style="line-height:1.8;"
                >

                    The platform is designed to provide a
                    professional environment for researchers
                    and reviewers while supporting an efficient
                    and transparent scholarly publication journey.

                </p>



                <div class="row g-3 mt-4">


                    <div class="col-6">

                        <div class="about-feature">


                            <i class="bi bi-file-earmark-text"></i>


                            <h5 class="fw-bold mt-3 mb-2">

                                Manuscripts

                            </h5>


                            <small class="text-muted">

                                Secure digital
                                submission management

                            </small>


                        </div>

                    </div>



                    <div class="col-6">

                        <div class="about-feature">


                            <i class="bi bi-people"></i>


                            <h5 class="fw-bold mt-3 mb-2">

                                Peer Review

                            </h5>


                            <small class="text-muted">

                                Structured reviewer
                                participation

                            </small>


                        </div>

                    </div>



                    <div class="col-6">

                        <div class="about-feature">


                            <i class="bi bi-clock-history"></i>


                            <h5 class="fw-bold mt-3 mb-2">

                                Tracking

                            </h5>


                            <small class="text-muted">

                                Monitor article
                                submission progress

                            </small>


                        </div>

                    </div>



                    <div class="col-6">

                        <div class="about-feature">


                            <i class="bi bi-shield-check"></i>


                            <h5 class="fw-bold mt-3 mb-2">

                                Secure

                            </h5>


                            <small class="text-muted">

                                Protected online
                                journal environment

                            </small>


                        </div>

                    </div>


                </div>


            </div>


        </div>

    </div>

</section>



{{-- =============================================================
     CTA
============================================================= --}}

<section class="cta text-center">

    <div class="container">


        <h2>

            Ready to Submit Your Research?

        </h2>


        <p>

            Access the BMRC Journal Online System
            and begin your article submission through
            our secure digital platform.

        </p>


        <a
            href="{{ route(
                'author.login'
            ) }}"
            class="btn
                   btn-light
                   btn-lg"
        >

            <i
                class="bi
                       bi-file-earmark-plus
                       me-2"
            ></i>

            Start Article Submission

        </a>


    </div>

</section>



{{-- =============================================================
     FOOTER
============================================================= --}}

<footer>

    <div class="container">

        <div class="row g-4">


            {{-- =================================================
                 BMRC JOURNAL
            ================================================== --}}

            <div class="col-lg-6">


                <div class="footer-brand">


                    <div class="footer-brand-icon">

                        <i class="bi bi-journal-medical"></i>

                    </div>


                    <div>

                        <h5 class="mb-0">

                            BMRC Journal

                        </h5>


                        <small>

                            Online Journal System

                        </small>

                    </div>


                </div>


                <p class="mt-3 mb-0">

                    Bangladesh Medical Research Council

                    <br>

                    BMRC Bhaban, Mohakhali, Dhaka

                </p>


            </div>



            {{-- =================================================
                 NAVIGATION
            ================================================== --}}

            <div class="col-6 col-lg-3">


                <h5>
                    Navigation
                </h5>


                <ul class="list-unstyled mt-3">


                    <li class="mb-2">

                        <a href="{{ url('/') }}">

                            Home

                        </a>

                    </li>


                    @if($aboutPage)

                        <li class="mb-2">

                            <a
                                href="{{ route(
                                    'journal.page',
                                    $aboutPage->slug
                                ) }}"
                            >

                                About

                            </a>

                        </li>

                    @endif


                    <li class="mb-2">

                        <a href="{{ url('/') }}#services">

                            Services

                        </a>

                    </li>


                    <li>

                        <a href="{{ url('/') }}#workflow">

                            Submission Process

                        </a>

                    </li>


                </ul>


            </div>



            {{-- =================================================
                 ACCESS
            ================================================== --}}

            <div class="col-6 col-lg-3">


                <h5>
                    Access
                </h5>


                <ul class="list-unstyled mt-3">


                    <li class="mb-2">

                        <a
                            href="{{ route(
                                'author.login'
                            ) }}"
                        >

                            <i
                                class="bi
                                       bi-file-earmark-text
                                       me-1"
                            ></i>

                            Author Login

                        </a>

                    </li>


                    <li class="mb-2">

                        <a
                            href="{{ route(
                                'reviewer.login'
                            ) }}"
                        >

                            <i
                                class="bi
                                       bi-person-check
                                       me-1"
                            ></i>

                            Reviewer Login

                        </a>

                    </li>


                    <li>

                        <a
                            href="{{ route(
                                'reviewer.register'
                            ) }}"
                        >

                            <i
                                class="bi
                                       bi-person-plus
                                       me-1"
                            ></i>

                            Become a Reviewer

                        </a>

                    </li>


                </ul>


            </div>


        </div>



        {{-- =====================================================
             FOOTER BOTTOM
        ====================================================== --}}

        <div class="footer-bottom text-center">


            <div class="footer-copyright">

                &copy;

                {{ date('Y') }}

                <strong>

                    Bangladesh Medical Research Council (BMRC)

                </strong>

                . All Rights Reserved.

            </div>


            <div class="footer-system-name">

                BMRC Journal Online System

            </div>


            <div class="developer-credit">

                Designed &amp; Developed by

                <a
                    href="https://web.facebook.com/tayzeer"
                    target="_blank"
                    rel="noopener noreferrer"
                    title="S M Sayadat Amin on Facebook"
                >

                    <i class="bi bi-facebook me-1"></i>

                    S M Sayadat Amin

                </a>


                <span class="developer-separator">

                    |

                </span>


                Scientific Officer, BMRC

            </div>


        </div>


    </div>

</footer>



{{-- =============================================================
     BOOTSTRAP JAVASCRIPT
============================================================= --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>



{{-- =============================================================
     NAVBAR JAVASCRIPT
============================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | Bootstrap Dropdown Initialization
        |--------------------------------------------------------------------------
        */

        const dropdownToggles =
            document.querySelectorAll(
                '.main-navbar [data-bs-toggle="dropdown"]'
            );


        dropdownToggles.forEach(
            function (toggle) {

                bootstrap.Dropdown
                    .getOrCreateInstance(
                        toggle
                    );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | Mobile Navbar
        |--------------------------------------------------------------------------
        */

        const navbarCollapse =
            document.getElementById(
                'mainNavbar'
            );


        if (!navbarCollapse) {

            return;

        }



        /*
        |--------------------------------------------------------------------------
        | Close Mobile Navbar After User Selects Link
        |--------------------------------------------------------------------------
        */

        const menuLinks =
            navbarCollapse
                .querySelectorAll(
                    'a.dropdown-item, a.nav-link:not(.dropdown-toggle), .btn-login'
                );


        menuLinks.forEach(
            function (link) {

                link.addEventListener(
                    'click',
                    function () {

                        /*
                        |--------------------------------------------------------------------------
                        | Desktop does not need collapse
                        |--------------------------------------------------------------------------
                        */

                        if (
                            window.innerWidth
                            >= 992
                        ) {

                            return;

                        }


                        const collapseInstance =
                            bootstrap.Collapse
                                .getOrCreateInstance(
                                    navbarCollapse,
                                    {
                                        toggle:
                                            false
                                    }
                                );


                        collapseInstance
                            .hide();

                    }
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | Reset Mobile Dropdowns When Main Menu Closes
        |--------------------------------------------------------------------------
        */

        navbarCollapse
            .addEventListener(
                'hidden.bs.collapse',
                function () {

                    dropdownToggles
                        .forEach(
                            function (toggle) {

                                const dropdown =
                                    bootstrap.Dropdown
                                        .getInstance(
                                            toggle
                                        );


                                if (dropdown) {

                                    dropdown.hide();

                                }

                            }
                        );

                }
            );

    }
);

</script>


</body>

</html>