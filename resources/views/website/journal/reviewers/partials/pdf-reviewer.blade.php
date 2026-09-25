@php

    $profile = $reviewer->profile;


    /*
    |--------------------------------------------------------------------------
    | Name
    |--------------------------------------------------------------------------
    */

    $name = trim(
        $profile?->display_name
        ?: $reviewer->name
    );


    /*
    |--------------------------------------------------------------------------
    | Separate Title
    |--------------------------------------------------------------------------
    */

    $title = trim(
        $profile?->title ?? ''
    );


    /*
    |--------------------------------------------------------------------------
    | Normalize spaces
    |--------------------------------------------------------------------------
    */

    $name = preg_replace(
        '/\s+/',
        ' ',
        $name
    );


    /*
    |--------------------------------------------------------------------------
    | Normalize Common Duplicate Titles
    |--------------------------------------------------------------------------
    */

    $name = preg_replace(
        '/^(?:Prof\.\s*Dr\.\s*){2,}/i',
        'Prof. Dr. ',
        $name
    );


    $name = preg_replace(
        '/^(?:Dr\.\s*){2,}/i',
        'Dr. ',
        $name
    );


    $name = preg_replace(
        '/^(?:Prof\.\s*){2,}/i',
        'Prof. ',
        $name
    );


    /*
    |--------------------------------------------------------------------------
    | Add Separate Title Only When Missing
    |--------------------------------------------------------------------------
    */

    if ($title !== '') {

        $escapedTitle =
            preg_quote(
                $title,
                '/'
            );


        if (
            !preg_match(
                '/^'
                . $escapedTitle
                . '(?:\s|$)/i',
                $name
            )
        ) {

            $name =
                $title
                . ' '
                . $name;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Final Duplicate Cleanup
    |--------------------------------------------------------------------------
    */

    $name = preg_replace(
        '/^(?:Prof\.\s*Dr\.\s*){2,}/i',
        'Prof. Dr. ',
        $name
    );


    $name = preg_replace(
        '/^(?:Dr\.\s*){2,}/i',
        'Dr. ',
        $name
    );


    $name = preg_replace(
        '/\s+/',
        ' ',
        trim($name)
    );


    /*
    |--------------------------------------------------------------------------
    | Other Fields
    |--------------------------------------------------------------------------
    */

    $designation = trim(
        $profile?->designation ?? ''
    );


    $department = trim(
        $profile?->department ?? ''
    );


    $institution = trim(
        $profile?->institution ?? ''
    );


    $country = trim(
        $profile?->country ?? ''
    );

@endphp



<div class="reviewer">


    {{-- NAME --}}

    <div class="reviewer-name">

        {{ $name }}

    </div>



    {{-- DESIGNATION --}}

    @if($designation !== '')

        <div class="reviewer-designation">

            {{ $designation }}

        </div>

    @endif



    {{-- AFFILIATION --}}

    @if(
        $department !== ''
        ||
        $institution !== ''
    )

        <div class="reviewer-affiliation">


            @if($department !== '')

                {{ $department }}

            @endif


            @if(
                $department !== ''
                &&
                $institution !== ''
            )

                ,

            @endif


            @if($institution !== '')

                {{ $institution }}

            @endif


        </div>

    @endif



    {{-- COUNTRY --}}

    @if($country !== '')

        <div class="reviewer-country">

            {{ $country }}

        </div>

    @endif


</div>