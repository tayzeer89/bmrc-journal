@php

    /*
    |--------------------------------------------------------------------------
    | National Reviewers
    |--------------------------------------------------------------------------
    */

    $nationalReviewers =
        $specialityReviewers
            ->filter(function ($reviewer) {

                $country = strtolower(
                    trim(
                        $reviewer
                            ->profile
                            ?->country
                        ?? ''
                    )
                );

                return
                    $country === 'bangladesh';

            });


    /*
    |--------------------------------------------------------------------------
    | International Reviewers
    |--------------------------------------------------------------------------
    */

    $internationalReviewers =
        $specialityReviewers
            ->filter(function ($reviewer) {

                $country = strtolower(
                    trim(
                        $reviewer
                            ->profile
                            ?->country
                        ?? ''
                    )
                );

                return
                    $country !== ''
                    &&
                    $country !== 'bangladesh';

            });


    /*
    |--------------------------------------------------------------------------
    | Display Count
    |--------------------------------------------------------------------------
    */

    $displayCount =
        $showTotal
        ?? $specialityReviewers->count();

@endphp



<div
    class="
        speciality-block
        {{ $specialityReviewers->count() > 14 ? 'large-speciality' : '' }}
    "
>


    {{-- =================================================================
         SPECIALITY
    ================================================================== --}}

    <div class="speciality-heading">

        {{ $speciality }}

        <span class="speciality-count">

            {{ $displayCount }}
            reviewer(s)

        </span>

    </div>



    {{-- =================================================================
         NATIONAL
    ================================================================== --}}

    @if($nationalReviewers->isNotEmpty())


        <div class="reviewer-type">

            National

            <span class="type-count">

                {{ $nationalReviewers->count() }}

            </span>

        </div>


        @php

            $nationalByDesignation =
                $nationalReviewers
                    ->groupBy(function ($reviewer) {

                        $designation = trim(
                            $reviewer
                                ->profile
                                ?->designation
                            ?? ''
                        );


                        return
                            $designation !== ''
                                ? $designation
                                : 'Other';

                    })
                    ->sortBy(function (
                        $group,
                        $designation
                    ) use ($designationOrder) {

                        return
                            $designationOrder[
                                $designation
                            ]
                            ?? 99;

                    });

        @endphp



        @foreach(
            $nationalByDesignation
            as $designation
            => $designationReviewers
        )


            <div class="designation-group">


                <div class="designation-heading">

                    {{ $designation }}

                    <span class="designation-count">

                        {{ $designationReviewers->count() }}

                    </span>

                </div>



                @foreach(
                    $designationReviewers
                    as $reviewer
                )


                    @include(
                        'website.journal.reviewers.partials.pdf-reviewer',
                        [
                            'reviewer'
                                => $reviewer
                        ]
                    )


                @endforeach


            </div>


        @endforeach


    @endif



    {{-- =================================================================
         INTERNATIONAL
    ================================================================== --}}

    @if($internationalReviewers->isNotEmpty())


        <div class="reviewer-type international">

            International

            <span class="type-count">

                {{ $internationalReviewers->count() }}

            </span>

        </div>


        @php

            $internationalByDesignation =
                $internationalReviewers
                    ->groupBy(function ($reviewer) {

                        $designation = trim(
                            $reviewer
                                ->profile
                                ?->designation
                            ?? ''
                        );


                        return
                            $designation !== ''
                                ? $designation
                                : 'Other';

                    })
                    ->sortBy(function (
                        $group,
                        $designation
                    ) use ($designationOrder) {

                        return
                            $designationOrder[
                                $designation
                            ]
                            ?? 99;

                    });

        @endphp



        @foreach(
            $internationalByDesignation
            as $designation
            => $designationReviewers
        )


            <div class="designation-group">


                <div class="designation-heading">

                    {{ $designation }}

                    <span class="designation-count">

                        {{ $designationReviewers->count() }}

                    </span>

                </div>



                @foreach(
                    $designationReviewers
                    as $reviewer
                )


                    @include(
                        'website.journal.reviewers.partials.pdf-reviewer',
                        [
                            'reviewer'
                                => $reviewer
                        ]
                    )


                @endforeach


            </div>


        @endforeach


    @endif


</div>