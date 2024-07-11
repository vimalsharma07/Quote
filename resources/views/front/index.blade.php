@extends('layouts.front')

@section('content')
    <div class="container mt-5">
        <div class="row">
            @foreach($quotes as $quote)
                @php
                    $imagePath = Storage::url($quote->background_image);
                    $user = $quote->user; // Assuming you have a relationship defined in your Quote model
                @endphp
                <div class="col-md-4 col-sm-6 col-12 mb-4">
                    <div class="quote-post">
                        <!-- User Details -->
                        <div class="user-details d-flex align-items-center mb-3">
                            <a href="{{url('profile/view/'.$user->id)}}">
                            <img src="{{ $user->photo ? Storage::url('photos/' . $user->photo) : asset('images/default-profile.png') }}" alt="{{ $user->name }}" class="profile-picture"></a>
                            <div class="ml-2">
                                <a href="{{url('profile/view/'.$user->id)}}">
                                <strong>{{ $user->name }}</strong>
                                </a>
                                <div class="text-muted">
                                    {{ $quote->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Display the background image and quote text -->
                        <div class="background-selector" style="background-image: url('{{ $imagePath }}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
                            <div class="quote-text" style="left: {{ $quote->text_x * 100 }}%; top: {{ $quote->text_y * 100 }}%; text-align: {{ $quote->text_align }};">
                                {{ $quote->text }}
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="description mt-3">
                            <p class="description-text">
                                {{ $quote->discription }}
                            </p>
                            @if(strlen($quote->discription) > 50)
                                <button class="btn btn-light btn-sm show-more-button">Show more</button>
                            @endif
                        </div>
                        
                        <!-- Tags -->
                        <div class="tags mb-3">
                            @foreach(explode(',', $quote->tags) as $tag)
                                <span class="badge badge-secondary">{{ $tag }}</span>
                            @endforeach
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
                                
                                <!-- Comment Button -->
                                <button class="btn btn-light btn-sm comment-button" data-toggle="collapse" data-target="#comment-section-{{ $quote->id }}">
                                    <i class="far fa-comment"></i> Comment
                                </button>
                                <!-- Download Button -->
                                <a href="{{ route('quotes.download', $quote->id) }}" class="btn btn-light btn-sm download-button">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <button class="btn btn-light btn-sm share-button" data-quote-id="{{ $quote->id }}">
                                    <i class="fas fa-share-alt"></i> Share
                                </button>
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
                                @php 
                                    $comments = App\Models\Comment::where('quote_id', $quote->id)->get();
                                @endphp
                                @foreach($comments as $comment)
                                    <div class="comment mt-2">
                                        <strong>{{ $comment->user->name }}:</strong> {{ $comment->comment }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

<!-- jQuery for Like, Comment, and Share Button -->
@section('scripts')
<script>
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Like Button
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

        // Show More Button
        $('.show-more-button').click(function() {
            const descriptionText = $(this).siblings('.description-text');
            if (descriptionText.hasClass('show-full')) {
                descriptionText.removeClass('show-full');
                $(this).text('Show more');
            } else {
                descriptionText.addClass('show-full');
                $(this).text('Show less');
            }
        });

        // Share Button
        $('.share-button').click(function() {
            const quoteId = $(this).data('quote-id');
            $('#shareModal').data('quote-id', quoteId).modal('show'); // Set quoteId for modal and show it
        });

        // Share Options
        $('.share-option').click(function() {
            const option = $(this).data('option');
            const quoteId = $('#shareModal').data('quote-id');
            switch (option) {
                case 'whatsapp':
                    const shareUrl = `https://wa.me/?text=Check%20out%20this%20quote!%20${window.location.origin}/quotes/${quoteId}`;
                    window.open(shareUrl, '_blank');
                    break;
                case 'facebook':
                    const fbShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${window.location.origin}/quotes/${quoteId}`;
                    window.open(fbShareUrl, '_blank');
                    break;
                case 'instagram':
                    alert('Sharing on Instagram is not supported through a direct link. Please use the Instagram app or website.');
                    break;
                default:
                    break;
            }
            $('#shareModal').modal('hide'); // Close the share modal
        });

        // Comment Button
        $('.comment-button').click(function() {
            // Toggle comment section visibility
            const target = $(this).data('target');
            $(target).collapse('toggle');
        });
    });
</script>
@endsection

<!-- Share Modal -->

<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share Quote</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Choose where you want to share this quote:</p>
                <div class="d-flex flex-column">
                    <button class="btn btn-light btn-sm share-option mb-2" data-option="whatsapp">
                        <i class="fab fa-whatsapp"></i> Share on WhatsApp
                    </button>
                    <button class="btn btn-light btn-sm share-option mb-2" data-option="facebook">
                        <i class="fab fa-facebook-f"></i> Share on Facebook
                    </button>
                    <button class="btn btn-light btn-sm share-option" data-option="instagram">
                        <i class="fab fa-instagram"></i> Share on Instagram
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add CSS for Share Modal -->

@section('styles')
<style>
    .share-whatsapp-button, .share-facebook-button, .share-instagram-button {
        margin-right: 5px;
    }

    .share-whatsapp-button {
        background-color: #25D366;
        color: #fff;
    }

    .share-whatsapp-button:hover {
        background-color: #1DA851;
    }

    .share-facebook-button {
        background-color: #3b5998;
        color: #fff;
    }

    .share-facebook-button:hover {
        background-color: #2d4373;
    }

    .share-instagram-button {
        background-color: #E4405F;
        color: #fff;
    }

    .share-instagram-button:hover {
        background-color: #c13584;
    }

    .fa-whatsapp, .fa-facebook-f, .fa-instagram {
        margin-right: 5px;
    }
</style>
@endsection
