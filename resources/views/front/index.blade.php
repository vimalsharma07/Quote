@extends('layouts.front')

@section('content')
    <h1>Welcome to the Quote App</h1>
    <p>This is the home page content.</p>
    <div class="row justify-content-center">
        <div class="col-sm-8">
            @foreach($quotes as $quote)
                @php
                    $imagePath = Storage::url($quote->background_image);
                @endphp
                <div class="background-selector" style="background-image: url('{{ $imagePath }}'); background-size: cover; background-position: center;">
                    <div class="quote-text" style="left: {{ $quote->text_x*100 }}%; top: {{ $quote->text_y*100 }}%; text-align: {{ $quote->text_align }};">
                        {{ $quote->text }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

<style>
    .background-selector {
        height: 300px; /* Adjust as necessary */
        width: 100%;
        margin-bottom: 20px;
        position: relative;
    }

    .quote-text {
        position: absolute;
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        font-size: 24px; /* Adjust as necessary */
    }
</style>
