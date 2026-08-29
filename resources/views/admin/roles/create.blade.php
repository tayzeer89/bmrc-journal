@extends('admin.layouts.app')

@section('title', 'Create Role')

@section('content')

<div class="container-fluid py-4">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold mb-1">
            Create Role
        </h2>

        <p class="text-muted mb-0">
            Create a new internal BMRC Journal role.
        </p>
    </div>

    <a href="{{ route('admin.roles.index') }}"
       class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i>
        Back to Roles
    </a>

</div>


@if ($errors->any())

    <div class="alert alert-danger">

        <strong>Please correct the following:</strong>

        <ul class="mb-0 mt-2">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


<div class="card shadow-sm border-0">

    <div class="card-body p-4">

        <form method="POST"
              action="{{ route('admin.roles.store') }}">

            @csrf


            <div class="row g-4">


                {{-- Role Name --}}

                <div class="col-md-6">

                    <label for="name"
                           class="form-label fw-semibold">

                        Role Name

                    </label>

                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="e.g. editorial_officer"
                           required>

                    <div class="form-text">
                        Use lowercase letters and underscores.
                    </div>

                    @error('name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Display Name --}}

                <div class="col-md-6">

                    <label for="display_name"
                           class="form-label fw-semibold">

                        Display Name

                    </label>

                    <input type="text"
                           name="display_name"
                           id="display_name"
                           value="{{ old('display_name') }}"
                           class="form-control @error('display_name') is-invalid @enderror"
                           placeholder="e.g. Editorial Officer / Journal Officer"
                           required>

                    @error('display_name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- User Type --}}

                <div class="col-md-6">

                    <label for="user_type"
                           class="form-label fw-semibold">

                        User Type

                    </label>

                    <select name="user_type"
                            id="user_type"
                            class="form-select @error('user_type') is-invalid @enderror"
                            required>

                        <option value="">
                            Select User Type
                        </option>

                        <option value="internal"
                            {{ old('user_type') === 'internal' ? 'selected' : '' }}>
                            Internal
                        </option>

                        <option value="external"
                            {{ old('user_type') === 'external' ? 'selected' : '' }}>
                            External
                        </option>

                    </select>

                    @error('user_type')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Status --}}

                <div class="col-md-6">

                    <label for="is_active"
                           class="form-label fw-semibold">

                        Status

                    </label>

                    <select name="is_active"
                            id="is_active"
                            class="form-select @error('is_active') is-invalid @enderror">

                        <option value="1"
                            {{ old('is_active', '1') == '1' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="0"
                            {{ old('is_active') == '0' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                    @error('is_active')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Description --}}

                <div class="col-12">

                    <label for="description"
                           class="form-label fw-semibold">

                        Description

                    </label>

                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              placeholder="Describe the responsibilities of this role...">{{ old('description') }}</textarea>

                    @error('description')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- System Role --}}

                <div class="col-12">

                    <div class="form-check">

                        <input type="checkbox"
                               name="is_system_role"
                               value="1"
                               id="is_system_role"
                               class="form-check-input"
                               {{ old('is_system_role') ? 'checked' : '' }}>

                        <label for="is_system_role"
                               class="form-check-label fw-semibold">

                            System Role

                        </label>

                    </div>

                    <div class="form-text">

                        System roles should normally be protected from deletion.

                    </div>

                </div>

            </div>


            <hr class="my-4">


            <div class="d-flex gap-2">

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>
                    Create Role

                </button>

                <a href="{{ route('admin.roles.index') }}"
                   class="btn btn-secondary">

                    Cancel

                </a>

            </div>

        </form>

    </div>

</div>

</div>

@endsection
