@extends('admin.layouts.app')

@section('title', 'User Management')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row
                justify-content-between align-items-md-center
                gap-3 mb-4">

        <div>
            <h2 class="mb-1">
                User Management
            </h2>

            <p class="text-muted mb-0">
                Manage internal BMRC Journal users and their roles.
            </p>
        </div>

        <a href="{{ route('admin.users.create') }}"
           class="btn btn-primary">

            <i class="bi bi-person-plus me-1"></i>

            Create User

        </a>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Error Message --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Users Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                Internal Users
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover
                              align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                #
                            </th>

                            <th>
                                Name
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                User Type
                            </th>

                            <th>
                                Role
                            </th>

                            <th>
                                Created
                            </th>

                            <th class="text-end px-3">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($users as $user)

                            <tr>

                                <td class="px-3">
                                    {{ $users->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $user->name }}
                                    </strong>

                                </td>


                                <td>

                                    <span class="text-muted">
                                        {{ $user->email }}
                                    </span>

                                </td>


                                <td>

                                    @if($user->user_type === 'internal')

                                        <span class="badge text-bg-primary">
                                            Internal
                                        </span>

                                    @else

                                        <span class="badge text-bg-secondary">
                                            {{ ucfirst($user->user_type) }}
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @forelse($user->roles as $role)

                                        <span class="badge text-bg-dark mb-1">

                                            {{ $role->display_name ?? ucwords(str_replace('_', ' ', $role->name)) }}

                                        </span>

                                    @empty

                                        <span class="text-muted">
                                            No role
                                        </span>

                                    @endforelse

                                </td>


                                <td>

                                    <span class="text-muted">

                                        {{ $user->created_at?->format('d M Y') }}

                                    </span>

                                </td>


                                <td class="text-end px-3">

                                    <div class="btn-group">

                                        <a href="{{ route('admin.users.show', $user) }}"
                                           class="btn btn-sm btn-outline-secondary">

                                            <i class="bi bi-eye"></i>

                                        </a>


                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        @if(!$user->hasRole('system_administrator'))

                                            <form method="POST"
                                                  action="{{ route('admin.users.destroy', $user) }}"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this user?');">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-people fs-1 d-block mb-2"></i>

                                        No internal users found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($users->hasPages())

            <div class="card-footer bg-white">

                {{ $users->links() }}

            </div>

        @endif

    </div>

</div>

@endsection