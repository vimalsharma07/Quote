@extends('layouts.front')

@section('content')
    <div class="container">
        <h1>Edit Profile</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-photo">
                <img src="{{ $user->photo ? Storage::url('photos/' . $user->photo) : asset('images/default-profile.png') }}" alt="{{ $user->name }}" class="img-fluid rounded-circle current-profile-picture">
                <input type="file" name="photo" id="photo" class="form-control mt-2">
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $user->description) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
@endsection

<style>
    .container {
        margin-top: 20px;
    }

    .profile-photo {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
    }

    .current-profile-picture {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 3px solid #ddd;
    }

    .btn-primary {
        background-color: #0095f6;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-primary:hover {
        background-color: #007bb5;
    }

    .form-control {
        border-radius: 5px;
        box-shadow: none;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        border-color: #0095f6;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
    }

    textarea.form-control {
        height: 100px;
        resize: vertical;
    }

    .alert {
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .container {
            padding: 0 15px;
        }

        .current-profile-picture {
            width: 120px;
            height: 120px;
        }

        .btn-primary {
            width: 100%;
        }
    }
</style>
