@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Office Details</h1>
        @if($office)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $office->Office_Name }}</h5>
                    <p class="card-text"><strong>Pin:</strong> {{ $office->Office_Pin }}</p>
                    <a href="{{ route('offices.edit', $office->Office_ID) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('offices.destroy', $office->Office_ID) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    <a href="{{ route('offices.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        @else
            <div class="alert alert-danger">
                Office not found.
            </div>
        @endif
    </div>
@endsection
