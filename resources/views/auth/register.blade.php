@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h3 class="mb-4 text-center">Register</h3>

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">First Name</label>
          <input type="text" name="first_name" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
          <label class="form-label">Last Name</label>
          <input type="text" name="last_name" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
          <label class="form-label">Short Name</label>
          <input type="text" name="short_name" class="form-control" autofocus>
        </div>

        <div class="mb-3">
          <label class="form-label">Mobile Number</label>
          <input type="text" name="mobile_no" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Register</button>
      </form>
    </div>
  </div>
</div>
@endsection
