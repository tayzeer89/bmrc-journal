@extends('admin.layouts.app')

@section('title', 'Edit Role')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-md-center
                gap-3 mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Edit Role
            </h2>

            <p class="text-muted mb-0">
                Update role information and permissions.
            </p>

        </div>

        <a href="{{ route('admin.roles.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left"></i>

            Back to Roles

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.roles.update', $role) }}">

        @csrf

        @method('PATCH')


        {{-- Role Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0 fw-bold">
                    Role Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    {{-- Role Name --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Role Name
                        </label>

                        <input type="text"
                               name="name"
                               value="{{ old('name', $role->name) }}"
                               class="form-control"
                               required>

                        <small class="text-muted">
                            Use lowercase letters, numbers, hyphens or underscores.
                        </small>

                    </div>


                    {{-- Display Name --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Display Name
                        </label>

                        <input type="text"
                               name="display_name"
                               value="{{ old(
                                   'display_name',
                                   $role->display_name
                                       ?? ucwords(
                                           str_replace(
                                               '_',
                                               ' ',
                                               $role->name
                                           )
                                       )
                               ) }}"
                               class="form-control"
                               required>

                    </div>


                    {{-- Description --}}
                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <textarea name="description"
                                  rows="4"
                                  class="form-control"
                                  placeholder="Describe the responsibility of this role...">{{ old('description', $role->description ?? '') }}</textarea>

                    </div>

                </div>

            </div>

        </div>


        {{-- Permissions --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white">

                <div class="d-flex flex-column flex-md-row
                            justify-content-between
                            align-items-md-center
                            gap-2">

                    <div>

                        <h5 class="mb-1 fw-bold">
                            Permissions
                        </h5>

                        <small class="text-muted">
                            Select the permissions assigned to this role.
                        </small>

                    </div>


                    <div class="form-check">

                        <input type="checkbox"
                               class="form-check-input"
                               id="selectAllPermissions">

                        <label class="form-check-label"
                               for="selectAllPermissions">

                            Select All

                        </label>

                    </div>

                </div>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    @forelse($permissions as $group => $groupPermissions)

                        <div class="col-12 col-md-6 col-xl-4">

                            <div class="border rounded p-3 h-100">

                                <h6 class="fw-bold text-capitalize mb-3">

                                    {{ str_replace('_', ' ', $group) }}

                                </h6>


                                @foreach($groupPermissions as $permission)

                                    <div class="form-check mb-2">

                                        <input type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->name }}"
                                               id="permission_{{ $permission->id }}"
                                               class="form-check-input permission-checkbox"

                                               @checked(
                                                   in_array(
                                                       $permission->name,
                                                       old(
                                                           'permissions',
                                                           $role->permissions
                                                               ->pluck('name')
                                                               ->toArray()
                                                       )
                                                   )
                                               )>

                                        <label class="form-check-label"
                                               for="permission_{{ $permission->id }}">

                                            {{ ucwords(
                                                str_replace(
                                                    ['.', '_'],
                                                    [' → ', ' '],
                                                    $permission->name
                                                )
                                            ) }}

                                        </label>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @empty

                        <div class="col-12">

                            <div class="alert alert-warning mb-0">

                                No permissions have been created yet.

                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Buttons --}}
        <div class="d-flex flex-column
                    flex-sm-row
                    justify-content-end
                    gap-2">

            <a href="{{ route('admin.roles.index') }}"
               class="btn btn-light border">

                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-circle"></i>

                Update Role

            </button>

        </div>

    </form>

</div>


{{-- Select All JavaScript --}}
@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const selectAll =
            document.getElementById(
                'selectAllPermissions'
            );

        const permissions =
            document.querySelectorAll(
                '.permission-checkbox'
            );


        function updateSelectAll()
        {
            const total =
                permissions.length;

            const checked =
                document.querySelectorAll(
                    '.permission-checkbox:checked'
                ).length;

            selectAll.checked =
                total > 0 &&
                total === checked;
        }


        selectAll.addEventListener(
            'change',
            function () {

                permissions.forEach(
                    function (checkbox) {

                        checkbox.checked =
                            selectAll.checked;

                    }
                );

            }
        );


        permissions.forEach(
            function (checkbox) {

                checkbox.addEventListener(
                    'change',
                    updateSelectAll
                );

            }
        );


        updateSelectAll();

    }
);

</script>

@endpush

@endsection
