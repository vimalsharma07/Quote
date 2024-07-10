@extends('layouts.front')

@section('content')
    <div class="container">
        <h1>{{ $user->name }}'s Profile</h1>
        <div class="row">
            <div class="col-md-4">
                <img src="{{ $user->photo ? Storage::url('photos/' . $user->photo) : asset('images/default-profile.png') }}" alt="{{ $user->name }}" class="img-fluid rounded-circle profile-picture">
            </div>
            <div class="col-md-8">
                <h2>{{ $user->name }}</h2>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone:</strong> {{ $user->phone }}</p>
                <p><strong>Description:</strong> {{ $user->description }}</p>
                <p><strong>Quotes:</strong> {{ count(explode(',', $user->quotes)) }} </p>
                <p><strong>Liked Quotes:</strong> {{ count(explode(',', $user->like_quotes)) }} </p>
                <p><strong>Total Likes:</strong> {{ $user->total_like }}</p>
                <p><strong>Total Views:</strong> {{ $user->total_view }}</p>
                <p><strong>Profile Views:</strong> {{ $user->profile_view }}</p>
                <p><strong>Followers:</strong> {{ $user->followers }}</p>
                <p><strong>Following:</strong> {{ $user->following }}</p>

                <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
@endsection

<style>
    .profile-picture {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 3px solid #ddd;
    }

    .container {
        margin-top: 20px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }
</style>
