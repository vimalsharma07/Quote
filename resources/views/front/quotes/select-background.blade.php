@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Select Background Image</h2>
    <form action="{{ route('store.quote') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="quote-preview" style="position: relative; height: 50vh;">
                    <!-- Default background image here -->
                    <img id="quote-background-image" src="{{ asset('assets/quotes/background/default-background.png') }}" alt="Default Background" style="width: 100%; height: 100%; object-fit: cover;">
                    <div class="quote-text" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); text-align: center;">
                        {{ session('quote_text') }}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="background-selection" style="height: 50vh; overflow-y: auto;">
                    @foreach($backgroundQuotes as $background)
                        <div class="background-item" style="margin-bottom: 10px;">
                            <img src="{{ Storage::url($background->path) }}" alt="{{ $background->filename }}" style="width: 100%; cursor: pointer;" onclick="selectBackground('{{ Storage::url($background->path) }}', '{{ $background->id }}')">
                        </div>
                    @endforeach
                </div>
                <input type="hidden" id="selected_background" name="selected_background">
                <button type="submit" class="btn btn-primary mt-3">Create Quote</button>
            </div>
        </div>
    </form>
</div>

<script>
    function selectBackground(imageUrl, id) {
        document.getElementById('selected_background').value = id;
        document.getElementById('quote-background-image').src = imageUrl;
    }
</script>
@endsection
