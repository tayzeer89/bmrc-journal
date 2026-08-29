@extends('admin.layouts.app')

@section('title', 'Role Management')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row
                justify-content-between
                align-items-md-center
                gap-3 mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Role Management
            </h2>

            <p class="text-muted mb-0">
                Manage internal BMRC Journal roles and permissions.
            </p>

        </div>


        <a href="{{ route('admin.roles.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            Create Role

        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Roles --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Role
                            </th>

                            <th>
                                Display Name
                            </th>

                            <th>
                                Users
                            </th>

                            <th>
                                Permissions
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($roles as $role)

                            <tr>

                                <td>
                                    {{ $roles->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    <code>
                                        {{ $role->name }}
                                    </code>

                                </td>


                                <td>

                                    <strong>

                                        {{ $role->display_name
                                            ?? ucwords(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $role->name
                                                )
                                            )
                                        }}

                                    </strong>

                                </td>


                                <td>

                                    <span class="badge bg-secondary">

                                        {{ $role->users_count }}

                                    </span>

                                </td>


                                <td>

                                    <span class="badge bg-primary">

                                        {{ $role->permissions->count() }}

                                    </span>

                                </td>


                                <td class="text-end">

                                    <div class="btn-group">

                                        <a href="{{ route(
                                            'admin.roles.show',
                                            $role
                                        ) }}"
                                           class="btn btn-sm btn-outline-secondary">

                                            View

                                        </a>


                                        @if($role->name !== 'system_administrator')

                                            <a href="{{ route(
                                                'admin.roles.edit',
                                                $role
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary">

                                                Edit

                                            </a>


                                            @if($role->users_count == 0)

                                                <form method="POST"
                                                      action="{{ route(
                                                          'admin.roles.destroy',
                                                          $role
                                                      ) }}"
                                                      onsubmit="return confirm('Delete this role?');">

                                                    @csrf

                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger">

                                                        Delete

                                                    </button>

                                                </form>

                                            @endif

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-5">

                                    <span class="text-muted">

                                        No roles found.

                                    </span>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                {{ $roles->links() }}

            </div>

        </div>

    </div>

</div>

@endsection
