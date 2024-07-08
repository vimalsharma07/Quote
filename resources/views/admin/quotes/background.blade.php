@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Quote Background Details</h1>
        <img src="{{ Storage::url($quoteBackground->path) }}" class="img-fluid" alt="{{ $quoteBackground->filename }}">
        <p class="mt-3">Filename: {{ $quoteBackground->filename }}</p>
        <a href="{{ route('admin.quote.background.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection
