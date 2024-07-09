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
                <div class="quote-post mb-4">
                    <!-- Display the background image and quote text -->
                    <div class="background-selector" style="background-image: url('{{ $imagePath }}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
                        <div class="quote-text" style="left: {{ $quote->text_x*100 }}%; top: {{ $quote->text_y*100 }}%; text-align: {{ $quote->text_align }};">
                            {{ $quote->text }}
                        </div>
                    </div>

                    <!-- Post Details -->
                    <div class="post-details mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Like Button -->
                            <button class="btn btn-light btn-sm like-button {{ $quote->liked ? 'liked' : '' }}" data-quote-id="{{ $quote->id }}">
                                <i class="fa{{ $quote->liked ? 's' : 'r' }} fa-heart like-icon"></i>
                                <span class="like-count">{{ $quote->likes }}</span>
                            </button>
                            <!-- Share Button -->
                            <button class="btn btn-light btn-sm share-button" data-quote-id="{{ $quote->id }}">
                                <i class="fas fa-share"></i> Share
                            </button>
                            <!-- Comment Button -->
                            <button class="btn btn-light btn-sm comment-button" data-toggle="collapse" data-target="#comment-section-{{ $quote->id }}">
                                <i class="far fa-comment"></i> Comment
                            </button>
                            <!-- Download Button -->
                            <a href="{{ route('quotes.download', $quote->id) }}" class="btn btn-light btn-sm download-button">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>

                        <!-- Comment Section -->
                        <div id="comment-section-{{ $quote->id }}" class="collapse comment-section mt-2">
                            <form method="POST" action="{{ route('comments.store') }}">
                                @csrf
                                <input type="hidden" name="quote_id" value="{{ $quote->id }}">
                                <div class="form-group">
                                    <input type="text" name="comment" class="form-control" placeholder="Add a comment...">
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Post</button>
                            </form>
                            <?php 
                          $comments=   App\Models\Comment::where('quote_id', $quote->id)->get();
                             ?>
                            @foreach($comments as $comment)
                                <div class="comment mt-2">
                                    <strong>{{ $comment->user->name }}:</strong> {{ $comment->comment }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- <form action="http://127.0.0.1:8081/quotes/{{$quote->id}}/like" method="POST" style="display: inline;">
        <!-- Add CSRF token if necessary -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit">Like</button>
    </form> --}}
@endsection

<style>
    .background-selector {
        display: inline-block;
        width: 100%; /* Make sure the container takes the full width of the column */
        height: 300px; /* Adjust as necessary */
        margin-bottom: 20px;
        position: relative;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }

    .quote-text {
        position: absolute;
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        font-size: 24px; /* Adjust as necessary */
    }

    .post-details {
        padding: 10px;
    }

    .like-button, .share-button, .comment-button, .download-button {
        border: none;
        background: none;
        cursor: pointer;
    }

    .like-button {
        color: #e1306c; /* Instagram-like pink color for the heart */
    }

    .like-button.liked .like-icon {
        color: #e1306c; /* Change heart color to red when liked */
    }

    .like-count {
        margin-left: 5px;
    }

    .comment-button {
        color: #0095f6; /* Instagram-like blue color for the comment */
    }

    .share-button {
        color: #262626; /* Instagram-like dark color for share */
    }

    .download-button {
        color: #262626; /* Same dark color for download */
    }

    .comment-section {
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }

    .comment {
        border-bottom: 1px solid #ddd;
        padding: 5px 0;
    }

    .btn-light {
        background-color: #fafafa;
        color: #262626;
    }

    .btn-light:hover {
        background-color: #f0f0f0;
    }

    .quote-post {
        width: 290px;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }

    .post-details .d-flex {
        margin-bottom: 10px; /* Space between the buttons and comments */
    }

    .post-details .btn {
        margin-right: 10px; /* Space between the buttons */
    }

    .post-details .btn:last-child {
        margin-right: 0;
    }

    .like-icon {
        color: #e1306c; /* Default heart color */
    }

    .like-button.liked .like-icon {
        color: #e1306c; /* Change heart color to red when liked */
    }
</style>

<!-- jQuery for Like and Share Button -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Get CSRF Token from meta tag
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Like button
        $('.like-button').click(function() {
            const button = $(this);
            const quoteId = button.data('quote-id');
            $.ajax({
                url: `/quotes/${quoteId}/like`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: JSON.stringify({ quote_id: quoteId }),
                contentType: 'application/json',
                success: function(data) {
                    if (data.liked) {
                        button.addClass('liked');
                        button.find('.like-icon').removeClass('far').addClass('fas');
                    } else {
                        button.removeClass('liked');
                        button.find('.like-icon').removeClass('fas').addClass('far');
                    }
                    button.find('.like-count').text(data.likes);
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        });

        // Share button
        $('.share-button').click(function() {
            const quoteId = $(this).data('quote-id');
            alert(`Share this quote with ID: ${quoteId}`);
        });
    });
</script>
