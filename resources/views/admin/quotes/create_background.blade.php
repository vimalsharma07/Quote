@extends('layouts.admin')
@section( 'content')
@php
use Illuminate\Support\Facades\Session;
@endphp
    @if ($message = Session::get('success'))
        <div>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <form action="{{ route('quote_backgrounds.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="background">Select Background Image:</label>
            <input type="file" name="background" id="background" required>
        </div>
        <button type="submit">Upload</button>
    </form>
@endsection
