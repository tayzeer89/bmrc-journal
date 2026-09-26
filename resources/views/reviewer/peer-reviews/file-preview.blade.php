@extends('reviewer.layouts.app')

@section(
    'title',
    ($displayName ?? 'Manuscript Preview') . ' | BMRC Journal'
)

@section('content')

<div class="container-fluid py-3">

    <div class="reviewer-card">

        {{-- Header --}}
        <div
            class="
                card-header
                d-flex
                flex-wrap
                justify-content-between
                align-items-center
                gap-2
            "
        >

            <div>
                <i class="bi bi-file-earmark-pdf me-2"></i>

                <strong>
                    {{ $displayName ?? 'Manuscript Preview' }}
                </strong>
            </div>


            <div class="d-flex gap-2 flex-wrap">

                {{-- Back --}}
                <a
                    href="{{ route(
                        'reviewer.invitations.show',
                        $invitation->id
                    ) }}"
                    class="btn btn-sm btn-outline-secondary"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Back
                </a>


                {{-- Download --}}
                <a
                    href="{{ route(
                        'reviewer.peer-reviews.files.download',
                        [
                            'invitation' => $invitation->id,
                            'file' => $file->id,
                        ]
                    ) }}"
                    class="btn btn-sm btn-outline-success"
                >
                    <i class="bi bi-download me-1"></i>
                    Download
                </a>

            </div>

        </div>


        {{-- Preview --}}
        <div class="card-body p-0">

            <iframe
                src="{{ route(
                    'reviewer.peer-reviews.files.preview-content',
                    [
                        'invitation' => $invitation->id,
                        'file' => $file->id,
                    ]
                ) }}"
                title="{{ $displayName ?? 'BMRC Manuscript Preview' }}"
                style="
                    display:block;
                    width:100%;
                    height:calc(100vh - 170px);
                    min-height:700px;
                    border:0;
                    background:#f5f5f5;
                "
            ></iframe>

        </div>

    </div>

</div>

@endsection