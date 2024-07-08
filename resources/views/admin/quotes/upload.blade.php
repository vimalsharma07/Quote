@extends('layouts.admin')

@section('content')
<h2>Quotes Background Images</h2>
<a href="{{ route('admin.quotes.uploadForm') }}" class="btn btn-primary mb-3">Upload New Image</a>
<div class="row">
    @foreach ($images as $image)
    <div class="col-md-3">
        <div class="card mb-3">
            <img src="{{ asset('assets/quotes/background/'.$image) }}" class="card-img-top" alt="...">
        </div>
    </div>
    @endforeach
</div>
@endsection
