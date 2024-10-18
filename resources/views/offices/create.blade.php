@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Create Office</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('offices.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="Office_Name">Office Name</label>
                <input type="text" name="Office_Name" id="Office_Name" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="Office_Pin">Office Pin</label>
                <input type="text" name="Office_Pin" id="Office_Pin" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Create</button>
        </form>
    </div>
@endsection
