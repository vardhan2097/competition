@extends('layouts.app') <!-- or your master layout -->

@section('content')
<div class="container mt-4">
    <h2>Edit Organization Details</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('organization.details.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Spoofs the PUT request -->

        <div class="mb-3">
            <label for="name" class="form-label">Organization Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $organization->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Organizational zAddress</label>
            <textarea name="address" id="address" cols="30" rows="4" class="form-control">{{ old('address', $organization->address ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="is_active" class="form-label">Active Status</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{ old('is_active', $organization->is_active) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active', $organization->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="logo" class="form-label">Organization Logo</label>
            <input type="file" class="form-control" id="logo" name="logo">
        </div>

        <div class="mb-3">
            <!-- Thumbnail -->
            <img src="{{ asset('storage/' . $organization->logo) }}" alt="Organization Logo" style="max-width: 150px; height: auto; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#logoModal">

            <!-- Modal -->
            <div class="modal fade" id="logoModal" tabindex="-1" aria-labelledby="logoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $organization->logo) }}" alt="Full Logo" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Details</button>
    </form>
</div>
@endsection
