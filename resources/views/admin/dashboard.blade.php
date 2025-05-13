@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Welcome, {{ Auth::guard('admin')->user()->name ?? Auth::guard('admin')->user()->email }}</h1>
        <div>
            <a href="{{ route('admin.logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="btn btn-danger btn-sm">
                Logout
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="row">
        <!-- Card: Create Organization -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Create Organization</h5>
                    <p class="card-text">Register a new organization and its admin user.</p>
                    <a href="{{ route('admin.organization.create') }}" class="btn btn-primary">Create</a>
                </div>
            </div>
        </div>

        <!-- Card: View Organizations -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">View Organizations</h5>
                    <p class="card-text">See the list of all organizations.</p>
                    <a href="{{ route('admin.organization.index') }}" class="btn btn-info">View</a>
                </div>
            </div>
        </div>

        <!-- Card: Manage Organizations -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Organizations</h5>
                    <p class="card-text">Edit or deactivate organizations.</p>
                    <a href="#" class="btn btn-warning">Manage</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>


    setTimeout(() => {
        const alert = document.querySelectorAll('.alert-success');
        alert.forEach(alert => {
            alert.classList.remove('show')
            alert.classList.add('fade')
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000); // 5 seconds


</script>