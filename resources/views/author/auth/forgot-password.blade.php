@extends('layouts.app')

@section('title', 'Forgot Password | BMRC Journal')

@section('content')

<style>

    .forgot-page {
        min-height: calc(100vh - 70px);
        background: #f4f7f9;
        display: flex;
        align-items: center;
        padding: 40px 15px;
    }

    .forgot-wrapper {
        width: 100%;
        max-width: 440px;
        margin: auto;
    }

    .forgot-card {
        background: #ffffff;
        border: 1px solid #e1e7ec;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(15, 50, 70, .10);
    }

    .forgot-header {
        background: #0d3b66;
        color: #ffffff;
        text-align: center;
        padding: 30px 25px 28px;
    }

    .forgot-logo {
        width: 78px;
        height: 78px;
        object-fit: contain;
        background: #ffffff;
        border-radius: 50%;
        padding: 7px;
        margin-bottom: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,.15);
    }

    .journal-name {
        font-size: 1.55rem;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .organization-name {
        font-size: .86rem;
        opacity: .92;
    }

    .forgot-body {
        padding: 30px;
    }

    .forgot-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(13, 59, 102, .08);
        color: #0d3b66;
        font-size: 1.55rem;
    }

    .forgot-title {
        text-align: center;
        color: #243447;
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .forgot-description {
        text-align: center;
        color: #667085;
        font-size: .86rem;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .form-label {
        color: #344054;
        font-size: .88rem;
        font-weight: 600;
    }

    .form-control {
        min-height: 45px;
    }

    .send-btn {
        min-height: 46px;
        background: #0d3b66;
        border-color: #0d3b66;
        font-weight: 600;
    }

    .send-btn:hover {
        background: #092f52;
        border-color: #092f52;
    }

    .back-link {
        color: #0d3b66;
        font-size: .83rem;
        font-weight: 600;
        text-decoration: none;
    }

</style>


<div class="forgot-page">

    <div class="forgot-wrapper">

        <div class="forgot-card">

            <div class="forgot-header">

                <img
                    src="{{ asset('favicon.png') }}"
                    alt="BMRC Logo"
                    class="forgot-logo"
                >

                <div class="journal-name">
                    BMRC Journal
                </div>

                <div class="organization-name">
                    Bangladesh Medical Research Council
                </div>

            </div>


            <div class="forgot-body">

                <div class="forgot-icon">
                    <i class="bi bi-key"></i>
                </div>


                <div class="forgot-title">
                    Forgot Your Password?
                </div>


                <div class="forgot-description">
                    Enter the email address registered with your
                    Author account. We will send you a secure
                    password reset link.
                </div>


                @if(session('status'))

                    <div class="alert alert-success small">

                        <i class="bi bi-check-circle me-2"></i>

                        {{ session('status') }}

                    </div>

                @endif


                @if($errors->any())

                    <div class="alert alert-danger small">

                        <ul class="mb-0 ps-3">

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
                    action="{{ route('author.password.email') }}"
                >

                    @csrf


                    <div class="mb-4">

                        <label
                            for="email"
                            class="form-label"
                        >
                            Registered Email Address
                            <span class="text-danger">*</span>
                        </label>


                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-envelope"></i>

                            </span>


                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                class="form-control"
                                placeholder="Enter your email address"
                                required
                                autofocus
                                autocomplete="email"
                            >

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="btn send-btn text-white w-100"
                    >

                        <i class="bi bi-send me-2"></i>

                        Send Password Reset Link

                    </button>

                </form>


                <div class="text-center mt-4">

                    <a
                        href="{{ route('author.login') }}"
                        class="back-link"
                    >

                        <i class="bi bi-arrow-left me-1"></i>

                        Back to Author Login

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection