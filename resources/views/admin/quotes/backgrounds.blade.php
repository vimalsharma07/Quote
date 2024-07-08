@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Quote Backgrounds</h1>
        <a href="{{ route('admin.quote.background.create') }}" class="btn btn-primary mb-3">Upload New Background</a>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="row">
            @foreach ($quoteBackgrounds as $background)
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <a href="{{ route('admin.quote.background.show', $background->id) }}">
                            <img src="{{ Storage::url($background->path) }}" class="card-img-top" alt="{{ $background->filename }}">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $background->filename }}</h5>
                            <form action="{{ route('admin.quote.background.destroy', $background->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
