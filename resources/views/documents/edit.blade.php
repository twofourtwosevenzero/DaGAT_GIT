<!-- resources/views/documents/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Document</h2>
    <form action="{{ route('documents.update', $document->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="description">Document Description</label>
            <input type="text" name="description" id="description" class="form-control" value="{{ $document->description }}" required>
        </div>
        <div class="form-group">
            <label for="signatories">Signatories</label>
            <select name="signatories[]" id="signatories" class="form-control" multiple required>
                @foreach($offices as $office)
                    <option value="{{ $office->id }}" {{ in_array($office->id, $document->signatories->pluck('office_id')->toArray()) ? 'selected' : '' }}>{{ $office->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
