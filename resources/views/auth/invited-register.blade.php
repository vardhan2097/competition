@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Complete Your Registration</h2>

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>

    <form method="POST" action="{{ route('invitation.register', $invitation->token) }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email (read-only)</label>
            <input type="email" class="form-control" value="{{ $invitation->email }}" disabled>
        </div>

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id ="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="short_name" class="form-label">Short Name (optional)</label>
            <input type="text" id="short_name" name="short_name" class="form-control @error('short_name') is-invalid" value="{{ old('short_name') }}">
        </div>

        <div class="mb-3">
            <label for="mobile_no" class="form-label">Mobile No</label>
            <input type="text" id = "mobile_no" name="mobile_no" class="form-control @error('mobile_no') is-invalid" value="{{ old('mobile_no') }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
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