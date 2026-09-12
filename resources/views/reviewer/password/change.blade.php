@extends('reviewer.layouts.app')

@section('title', 'Change Account Password | BMRC Journal')

@section('content')

<div class="container-fluid py-4">

```
<div class="row justify-content-center">

    <div class="col-xxl-7 col-xl-8 col-lg-9">

        {{-- ============================================================
            PAGE HEADER
        ============================================================ --}}

        <div class="d-flex align-items-start justify-content-between mb-4">

            <div>

                <div class="d-flex align-items-center gap-3">

                    <div
                        class="d-flex align-items-center justify-content-center
                               bg-primary bg-opacity-10 text-primary rounded-3"
                        style="width: 48px; height: 48px;"
                    >
                        <i class="bi bi-shield-lock fs-4"></i>
                    </div>

                    <div>

                        <h4 class="mb-1 fw-semibold">
                            Account Security
                        </h4>

                        <p class="text-muted mb-0">
                            Manage the password associated with your reviewer account.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
            MAIN CARD
        ============================================================ --}}

        <div class="card border-0 shadow-sm">

            {{-- Reviewer Account Summary --}}

            <div class="card-header bg-white border-bottom px-4 py-3">

                <div class="d-flex align-items-center">

                    <div
                        class="rounded-circle bg-light
                               d-flex align-items-center justify-content-center me-3"
                        style="width: 46px; height: 46px;"
                    >
                        <i class="bi bi-person text-secondary fs-5"></i>
                    </div>

                    <div class="flex-grow-1">

                        <div class="fw-semibold">
                            {{ $reviewer->name }}
                        </div>

                        <div class="small text-muted">
                            {{ $reviewer->email }}
                        </div>

                    </div>


                    @if($reviewer->status === 'approved')

                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                            <i class="bi bi-patch-check-fill me-1"></i>
                            Approved Reviewer
                        </span>

                    @endif

                </div>

            </div>


            <div class="card-body p-4 p-lg-5">

                {{-- ====================================================
                    SECTION HEADING
                ===================================================== --}}

                <div class="mb-4">

                    <h5 class="fw-semibold mb-1">
                        Change Account Password
                    </h5>

                    <p class="text-muted small mb-0">
                        Please verify your current password before creating a new one.
                    </p>

                </div>


                {{-- ====================================================
                    SUCCESS MESSAGE
                ===================================================== --}}

                @if(session('success'))

                    <div class="alert alert-success d-flex align-items-start">

                        <i class="bi bi-check-circle-fill me-2 mt-1"></i>

                        <div>
                            {{ session('success') }}
                        </div>

                    </div>

                @endif


                {{-- ====================================================
                    VALIDATION SUMMARY
                ===================================================== --}}

                @if($errors->any())

                    <div class="alert alert-danger">

                        <div class="d-flex">

                            <i class="bi bi-exclamation-circle-fill me-2 mt-1"></i>

                            <div>

                                <div class="fw-semibold mb-1">
                                    Unable to update password
                                </div>

                                <div class="small">
                                    Please review the highlighted information below.
                                </div>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ====================================================
                    PASSWORD FORM
                ===================================================== --}}

                <form
                    method="POST"
                    action="{{ route('reviewer.password.update') }}"
                    autocomplete="off"
                >

                    @csrf
                    @method('PATCH')


                    {{-- =================================================
                        CURRENT PASSWORD
                    ================================================== --}}

                    <div class="mb-4">

                        <label
                            for="current_password"
                            class="form-label fw-semibold"
                        >
                            Current Password

                            <span class="text-danger">*</span>
                        </label>


                        <div class="input-group">

                            <span class="input-group-text bg-light border-end-0">

                                <i class="bi bi-lock text-muted"></i>

                            </span>


                            <input
                                type="password"
                                name="current_password"
                                id="current_password"
                                class="form-control border-start-0
                                    @error('current_password')
                                        is-invalid
                                    @enderror"
                                placeholder="Enter your current password"
                                required
                                autofocus
                                autocomplete="current-password"
                            >


                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="togglePassword(
                                    'current_password',
                                    'currentPasswordIcon'
                                )"
                                title="Show or hide password"
                            >

                                <i
                                    class="bi bi-eye"
                                    id="currentPasswordIcon"
                                ></i>

                            </button>


                            @error('current_password')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    <div class="border-top my-4"></div>


                    {{-- =================================================
                        NEW PASSWORD
                    ================================================== --}}

                    <div class="mb-4">

                        <label
                            for="password"
                            class="form-label fw-semibold"
                        >
                            New Password

                            <span class="text-danger">*</span>
                        </label>


                        <div class="input-group">

                            <span class="input-group-text bg-light border-end-0">

                                <i class="bi bi-key text-muted"></i>

                            </span>


                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control border-start-0
                                    @error('password')
                                        is-invalid
                                    @enderror"
                                placeholder="Create a new password"
                                required
                                autocomplete="new-password"
                            >


                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="togglePassword(
                                    'password',
                                    'newPasswordIcon'
                                )"
                                title="Show or hide password"
                            >

                                <i
                                    class="bi bi-eye"
                                    id="newPasswordIcon"
                                ></i>

                            </button>


                            @error('password')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- =================================================
                        CONFIRM PASSWORD
                    ================================================== --}}

                    <div class="mb-4">

                        <label
                            for="password_confirmation"
                            class="form-label fw-semibold"
                        >
                            Confirm New Password

                            <span class="text-danger">*</span>
                        </label>


                        <div class="input-group">

                            <span class="input-group-text bg-light border-end-0">

                                <i class="bi bi-key-fill text-muted"></i>

                            </span>


                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control border-start-0"
                                placeholder="Re-enter your new password"
                                required
                                autocomplete="new-password"
                            >


                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="togglePassword(
                                    'password_confirmation',
                                    'confirmPasswordIcon'
                                )"
                                title="Show or hide password"
                            >

                                <i
                                    class="bi bi-eye"
                                    id="confirmPasswordIcon"
                                ></i>

                            </button>

                        </div>

                    </div>


                    {{-- =================================================
                        PASSWORD POLICY
                    ================================================== --}}

                    <div class="border rounded-3 bg-light p-3 mb-4">

                        <div class="d-flex align-items-start">

                            <i class="bi bi-shield-check text-primary me-3 mt-1"></i>

                            <div>

                                <div class="fw-semibold small mb-2">
                                    Password Requirements
                                </div>

                                <div class="row g-2 small text-muted">

                                    <div class="col-md-6">

                                        <i class="bi bi-check2 me-1"></i>

                                        Minimum 8 characters

                                    </div>


                                    <div class="col-md-6">

                                        <i class="bi bi-check2 me-1"></i>

                                        One uppercase letter

                                    </div>


                                    <div class="col-md-6">

                                        <i class="bi bi-check2 me-1"></i>

                                        One lowercase letter

                                    </div>


                                    <div class="col-md-6">

                                        <i class="bi bi-check2 me-1"></i>

                                        At least one number

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        SECURITY NOTICE
                    ================================================== --}}

                    <div class="small text-muted mb-4">

                        <i class="bi bi-info-circle me-1"></i>

                        For account security, your new password must be
                        different from your current password.

                    </div>


                    {{-- =================================================
                        ACTION BUTTONS
                    ================================================== --}}

                    <div
                        class="d-flex flex-column flex-sm-row
                               justify-content-between gap-2
                               pt-3 border-top"
                    >

                        <a
                            href="{{ route('reviewer.dashboard') }}"
                            class="btn btn-light border px-4"
                        >

                            <i class="bi bi-arrow-left me-2"></i>

                            Back to Dashboard

                        </a>


                        <button
                            type="submit"
                            class="btn btn-primary px-4"
                        >

                            <i class="bi bi-shield-check me-2"></i>

                            Update Password

                        </button>

                    </div>

                </form>

            </div>

        </div>


        {{-- ============================================================
            PASSWORD HISTORY
        ============================================================ --}}

        @if($reviewer->password_changed_at)

            <div
                class="d-flex align-items-center justify-content-center
                       text-muted small mt-3"
            >

                <i class="bi bi-clock-history me-2"></i>

                <span>
                    Password last updated on
                    <strong class="fw-medium">
                        {{ $reviewer->password_changed_at->format('d M Y') }}
                    </strong>
                    at
                    <strong class="fw-medium">
                        {{ $reviewer->password_changed_at->format('h:i A') }}
                    </strong>
                </span>

            </div>

        @endif

    </div>

</div>
```

</div>

{{-- ================================================================
PASSWORD VISIBILITY
================================================================ --}}

<script>

    function togglePassword(inputId, iconId)
    {
        const input = document.getElementById(inputId);

        const icon = document.getElementById(iconId);


        if (!input || !icon) {
            return;
        }


        if (input.type === 'password') {

            input.type = 'text';

            icon.classList.remove('bi-eye');

            icon.classList.add('bi-eye-slash');

        } else {

            input.type = 'password';

            icon.classList.remove('bi-eye-slash');

            icon.classList.add('bi-eye');

        }
    }

</script>

@endsection
