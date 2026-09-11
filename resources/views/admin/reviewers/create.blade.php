@extends('admin.layouts.app')

@section('title', 'Add Reviewer')

@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-8">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Add New Reviewer
                    </h5>

                </div>


                <div class="card-body">


                    @if(session('error'))

                        <div class="alert alert-danger">

                            {{ session('error') }}

                        </div>

                    @endif


                    @if($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{
                            route(
                                'admin.reviewers.store'
                            )
                        }}"
                    >

                        @csrf


                        <div class="mb-3">

                            <label
                                class="form-label"
                            >
                                Reviewer Name
                                <span class="text-danger">
                                    *
                                </span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control"
                                required
                            >

                        </div>


                        <div class="mb-3">

                            <label
                                class="form-label"
                            >
                                Email Address
                                <span class="text-danger">
                                    *
                                </span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control"
                                required
                            >

                            <small class="text-muted">

                                Reviewer login information
                                will be sent to this address.

                            </small>

                        </div>


                        <div class="alert alert-info">

                            <strong>
                                What happens next?
                            </strong>

                            <br>

                            The system will automatically:

                            <ul class="mb-0 mt-2">

                                <li>
                                    Create the reviewer account
                                </li>

                                <li>
                                    Generate a temporary password
                                </li>

                                <li>
                                    Send login information by email
                                </li>

                                <li>
                                    Ask the reviewer to change
                                    the password
                                </li>

                                <li>
                                    Ask the reviewer to complete
                                    the professional profile
                                </li>

                            </ul>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Create Reviewer
                        </button>


                        <a
                            href="{{
                                route(
                                    'admin.reviewers.index'
                                )
                            }}"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection