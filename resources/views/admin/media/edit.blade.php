@extends('layouts.admin') <!-- Assuming you have an admin layout -->

@section('content')
    <div class="container mt-5">
        <h2>Update Media Information</h2>
        <form method="POST" action="{{ route('admin-media-update') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="logo">Logo (Upload new logo file):</label>
                <input type="file" id="logo" name="logo" class="form-control-file">
                <input type="hidden" value="{{$media->id}}"  name="id">
                @if($media->logo)
                    <div class="mt-2">
                        <img src="{{ Storage::url($media->logo) }}" alt="Current Logo" style="max-height: 100px;">
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="instagram">Instagram URL:</label>
                <input type="text" id="instagram" name="instagram" class="form-control" value="{{ $media->instagram }}" placeholder="Enter Instagram URL">
            </div>

            <div class="form-group">
                <label for="facebook">Facebook URL:</label>
                <input type="text" id="facebook" name="facebook" class="form-control" value="{{ $media->facebook }}" placeholder="Enter Facebook URL">
            </div>

            <div class="form-group">
                <label for="twitter">Twitter URL:</label>
                <input type="text" id="twitter" name="twitter" class="form-control" value="{{ $media->twitter }}" placeholder="Enter Twitter URL">
            </div>

            <div class="form-group">
                <label for="whatsapp">WhatsApp Number:</label>
                <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="{{ $media->whatsapp }}" placeholder="Enter WhatsApp contact number">
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            {{-- <a href="{{ route('media.index') }}" class="btn btn-secondary">Back to List</a> --}}
        </form>
    </div>
@endsection
