@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Invite User</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <form method="POST" action="{{ route('organization.users.invite.send') }}">
        @csrf

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" required>
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Role:</label>
            <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            @error('role_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Designation (optional):</label>
            <input type="text" name="designation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Send Invite</button>
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