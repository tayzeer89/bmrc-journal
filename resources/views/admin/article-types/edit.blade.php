@extends('layouts.app')

@section('content')

<div class="container">

    <div class="mb-4">

        <h2>Edit Article Type</h2>

        <a href="{{ route('admin.article-types.index') }}"
           class="btn btn-secondary">
            ← Back
        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <form action="{{ route('admin.article-types.update', $articleType) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Article Type Name
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $articleType->name) }}"
                           class="form-control @error('name') is-invalid @enderror">

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Code
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="code"
                           value="{{ old('code', $articleType->code) }}"
                           class="form-control @error('code') is-invalid @enderror">

                    @error('code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="description"
                              rows="4"
                              class="form-control">{{ old('description', $articleType->description) }}</textarea>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Sort Order
                    </label>

                    <input type="number"
                           name="sort_order"
                           value="{{ old('sort_order', $articleType->sort_order) }}"
                           min="0"
                           class="form-control">

                </div>

                <div class="form-check mb-4">

                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="form-check-input"
                           id="is_active"
                           {{ $articleType->is_active ? 'checked' : '' }}>

                    <label class="form-check-label"
                           for="is_active">

                        Active

                    </label>

                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Update Article Type

                </button>

                <a href="{{ route('admin.article-types.index') }}"
                   class="btn btn-secondary">

                    Cancel

                </a>

            </form>

        </div>

    </div>

</div>

@endsection