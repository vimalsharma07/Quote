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
                                <button class="btn btn-light btn-sm download-button" data-quote-id="{{ $quote->id }}">
                                    <i class="fas fa-download"></i> Download
                                </button>
                                <button class="btn btn-light btn-sm share-button" data-quote-id="{{ $quote->id }}">
                                    <i class="fas fa-share"></i> Share
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

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel">Share Quote</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <img id="capturedImage" src="" alt="Captured Quote" class="img-fluid mb-3"> --}}

                    <button class="btn btn-success share-option" data-option="whatsapp">Share on WhatsApp</button>
                    <button class="btn btn-primary share-option" data-option="facebook">Share on Facebook</button>
                    <button class="btn btn-danger share-option" data-option="instagram">Share on Instagram</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

<script  src="{{asset('assets/quotes/js/quote.js')}}"></script>
@endsection
