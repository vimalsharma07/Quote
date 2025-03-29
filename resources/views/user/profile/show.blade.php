@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="profile-header">
            <div class="profile-picture-wrapper">
                <img src="{{ $user->photo ?? asset('images/default-profile.png') }}" 
                alt="{{ $user->name }}" 
                class="img-fluid rounded-circle profile-picture">
                       </div>
            <div class="profile-details">
                <h2>{{ $user->name }}</h2>
                <p class="profile-bio">{{ $user->description }}</p>
                @if(Auth::check() && Auth::user()->id != $user->id)
                    <form method="POST" action="{{ route('profile.' . ($isFollowing ? 'unfollow' : 'follow'), $user->id) }}" class="follow-form">
                        @csrf
                        <button type="submit" class="btn btn-follow">
                            {{ $isFollowing ? 'Following' : 'Follow' }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-primary mt-2">Edit Profile</a>
                @endif
            </div>
        </div>

        <div class="profile-stats mb-4">
            <div class="stat">
                <span class="number">{{ count(explode(',', $user->quotes)) }}</span>
                <span class="label">Quotes</span>
            </div>
            <div class="stat">
               
                <span class="number">{{ $user->followers }}</span>
                <span class="label">Followers</span>
            </div>
            <div class="stat">
                <span class="number">{{ $user->following }}</span>
                <span class="label">Following</span>
            </div>
        </div>

        <div class="profile-description mt-3">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Phone:</strong> {{ $user->phone }}</p>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('submit', '.follow-form', function(e) {
        e.preventDefault();
        var $form = $(this);
        var url = $form.attr('action');
        var $button = $form.find('.btn-follow');

        $.ajax({
            url: url,
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                if (response.success) {
                    if ($button.text().trim() === 'Follow') {
                        $button.text('Following');
                    } else {
                        $button.text('Follow');
                    }
                } else {
                    alert('Action failed. Please try again.');
                }
            },
            error: function(xhr) {
                alert(xhr.responseText);
            }
        });
    });
</script>
@endsection

<style>
    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #e6e6e6;
    }

    .profile-picture-wrapper {
        margin-right: 20px;
    }

    .profile-picture {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 3px solid #ddd;
    }

    .profile-details {
        flex-grow: 1;
    }

    .profile-bio {
        margin-top: 10px;
        font-size: 14px;
        color: #555;
    }

    .profile-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .stat {
        text-align: center;
        margin-bottom: 15px;
    }

    .stat .number {
        font-size: 24px;
        font-weight: bold;
    }

    .stat .label {
        font-size: 14px;
        color: #888;
    }

    .profile-description p {
        margin-bottom: 10px;
    }

    .btn-follow {
        background-color: #0095f6;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-follow:hover {
        background-color: #007bb5;
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

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .profile-picture-wrapper {
            margin-right: 0;
            margin-bottom: 20px;
        }

        .profile-details {
            margin-bottom: 20px;
        }
    }
</style>
