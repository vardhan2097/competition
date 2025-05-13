@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Organization and Admin User</h1>

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif


        <form action="{{ route('admin.organization.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h4>Organization Details</h4>
            <div class="mb-3">
                <label for="org_name" class="form-label">Organization Name</label>
                <input type="text" class="form-control @error('org_name') is-invalid @enderror" id="org_name" name="org_name" value="{{ old('org_name') }}" required>
                @error('org_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="org_address" class="form-label">Organization Address</label>
                <textarea class="form-control @error('org_address') is-invalid @enderror" id="org_address" name="org_address" rows="3" value="{{ old('org_address') }}" required></textarea>
                @error('org_address')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="org_logo" class="form-label">Organization Logo</label>
                <input type="file" class="form-control" name="org_logo" value="{{ old('org_logo') }}">
                @error('org_logo')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <h4>Admin User Details</h4>
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                @error('first_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                @error('last_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="mobile_no" class="form-label">Mobile Number</label>
                <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" required>
                @error('mobile_no')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password </label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Organization and Organization Admin User</button>
        </form>
    </div>
@endsection


<script>
    setTimeout(() => {
        const alert = document.querySelectorAll('.alert');
        alert.forEach(alert => {
            alert.classList.remove('show')
            alert.classList.add('fade')
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000); // 5 seconds
</script>
