<!-- resources/views/organization/users/invite.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Complete Your Registration</h3>
    <form method="POST" action="{{ route('organization.invitation.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $invitation->token }}">
        <input type="hidden" name="email" value="{{ $invitation->email }}">

        <div class="mb-3">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value ="{{ old('first_name') }}" required>
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
            @error('last_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="short_name">Short Name</label>
            <input type="text" id="short_name" name="short_name" class="form-control @error('short_name') is-invalid @enderror" value="{{ old('short_name') }}">
            @error('short_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="mobile_no">Mobile No</label>
            <input type="text" id="mobile_no" name="mobile_no" class="form-control @error('mobile_no') is-invalid @enderror" value="{{ old('mobile_no')}}" required>
            @error('mobile_no')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role_name">Role</label>
            <input type="text" name="role_name" id="role_name" class="form-control" value="{{ $role->name }}" readonly>
        </div>

        <div class="mb-3">
            <label for="organization_id">Organization</label>
            <input type="text" id="organization_name" name="organization_name" class="form-control" value="{{ $organization->name }}" readonly>
        </div>

        <div class="mb-3">
            <label for="designation">Designation</label>
            <input type="text" id="designation" name="designation" class="form-control" value="{{ $invitation->designation }}" readonly>
            @error('designation')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Create Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>
@endsection
