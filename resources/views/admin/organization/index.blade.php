@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Organizations</h2>

    @if ($organizations->isEmpty())
        <div class="alert alert-info">No organizations found.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Logo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($organizations as $index => $org)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $org->name }}</td>
                        <td>{{ $org->address }}</td>
                        <td>
                            @if ($org->logo)
                                <img src="{{ asset('storage/' . $org->logo) }}" alt="Logo" height="40">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $org->is_active ? 'Active' : 'Inactive' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
