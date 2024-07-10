<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quote</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .background-selector {
            height: 300px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .thumbnail {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .thumbnail:hover {
            transform: scale(1.05);
        }
        .quote-text {
            position: absolute;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
            font-size: 24px;
            max-width: 90%;
            cursor: move;
        }
        .hashtag-suggestions {
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            z-index: 1000;
            max-height: 150px;
            overflow-y: auto;
        }
        .hashtag-suggestion {
            padding: 8px;
            cursor: pointer;
        }
        .hashtag-suggestion:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Create a Quote</h2>
        <form method="POST" action="{{ route('quotes.store') }}">
            @csrf
            <!-- Step 1: Text Input -->
            <div id="text-step" class="mb-4">
                <div class="form-group">
                    <label for="quote-text">Enter your quote:</label>
                    <textarea id="quote-text" name="text" class="form-control" rows="4" placeholder="Write your quote here..." required></textarea>
                </div>
                <div class="form-group">
                    <label for="quote-description">Description (optional):</label>
                    <textarea id="quote-description" name="description" class="form-control" rows="2" placeholder="Add a description..."></textarea>
                </div>
                <div class="form-group">
                    <label for="quote-tags">Tags (optional):</label>
                    <input type="text" id="quote-tags" name="tags" class="form-control" placeholder="Add hashtags..." autocomplete="off">
                    <div id="hashtag-suggestions" class="hashtag-suggestions d-none"></div>
                </div>
                <button type="button" class="btn btn-primary" id="next-step">Next</button>
            </div>

            <!-- Step 2: Background Selection -->
            <div id="background-step" class="d-none mb-4">
                <div class="background-selector" id="selected-background">
                    <!-- Quote text will be inserted here dynamically -->
                    <div class="quote-text" id="quote-text-display" contenteditable="true"></div>
                </div>
                <div class="mt-3">
                    <label for="background-selector">Choose a background:</label>
                    <div class="row" id="background-thumbnails">
                        <!-- Background images will be dynamically loaded here -->
                    </div>
                </div>
                <input type="hidden" name="background_image" id="background-image">
                <input type="hidden" name="text_x" id="text-x-hidden">
                <input type="hidden" name="text_y" id="text-y-hidden">
                <input type="hidden" name="text_align" id="text-align-hidden" value="center">
                <button type="submit" class="btn btn-primary mt-3">Post</button>
                <a href="{{ url('/create-quote') }}" class="btn btn-secondary mt-3">Back</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch background images from the server
            $.ajax({
                url: '{{ route('backgrounds.index') }}',
                method: 'GET',
                success: function(data) {
                    let backgrounds = data.backgrounds;
                    let thumbnails = '';
                    backgrounds.forEach(background => {
                        // Remove 'public' from the path
                        let pathWithoutPublic = background.path.replace('public/', '');
                        thumbnails += `
                            <div class="col-md-3 mb-2">
                                <img src="{{ Storage::url('${pathWithoutPublic}') }}" class="thumbnail img-fluid" data-background="${pathWithoutPublic}">
                            </div>
                        `;
                    });
                    $('#background-thumbnails').html(thumbnails);
                },
                error: function() {
                    alert('Failed to fetch background images.');
                }
            });

            // Handle next step
            $('#next-step').click(function() {
                // Get the quote text from the textarea
                let quoteText = $('#quote-text').val();
                $('#quote-text-display').text(quoteText); // Set the quote text

                // Reset text position to center
                $('#quote-text-display').css({
                    left: '50%',
                    top: '50%',
                    transform: 'translate(-50%, -50%)'
                });

                // Clear hidden text position fields
                $('#text-x-hidden').val('0.50');
                $('#text-y-hidden').val('0.50');

                $('#text-step').addClass('d-none');
                $('#background-step').removeClass('d-none');
            });

            // Handle background image selection
            $(document).on('click', '.thumbnail', function() {
                let background = $(this).data('background');
                $('#selected-background').css('background-image', `url("{{ Storage::url('${background}') }}")`);
                $('#background-image').val(background);
            });

            // Draggable text functionality
            let isDragging = false;
            let startX, startY, initialLeft, initialTop;

            $('#quote-text-display').on('mousedown touchstart', function(e) {
                e.preventDefault();
                isDragging = true;
                let pos = getPosition(e);
                startX = pos.x;
                startY = pos.y;
                initialLeft = parseFloat($(this).css('left'));
                initialTop = parseFloat($(this).css('top'));
            });

            $(document).on('mousemove touchmove', function(e) {
                if (isDragging) {
                    let pos = getPosition(e);
                    let dx = pos.x - startX;
                    let dy = pos.y - startY;

                    $('#quote-text-display').css({
                        left: initialLeft + dx,
                        top: initialTop + dy
                    });

                    // Update hidden input fields with decimal values
                    $('#text-x-hidden').val(((initialLeft + dx) / $('#selected-background').width()).toFixed(2));
                    $('#text-y-hidden').val(((initialTop + dy) / $('#selected-background').height()).toFixed(2));
                }
            });

            $(document).on('mouseup touchend', function() {
                if (isDragging) {
                    isDragging = false;
                }
            });

            // Get position helper
            function getPosition(e) {
                let x, y;
                if (e.type.startsWith('touch')) {
                    x = e.originalEvent.touches[0].clientX;
                    y = e.originalEvent.touches[0].clientY;
                } else {
                    x = e.clientX;
                    y = e.clientY;
                }
                return {
                    x: x - $('#selected-background').offset().left,
                    y: y - $('#selected-background').offset().top
                };
            }

            // Handle hashtag suggestions
            $('#quote-tags').on('input', function() {
                let query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        url: '{{ route('tags.suggest') }}',
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            let suggestions = data.tags;
                            if (suggestions.length > 0) {
                                let suggestionList = '';
                                suggestions.forEach(tag => {
                                    suggestionList += `<div class="hashtag-suggestion">#${tag}</div>`;
                                });
                                $('#hashtag-suggestions').html(suggestionList).removeClass('d-none');
                            } else {
                                $('#hashtag-suggestions').addClass('d-none');
                            }
                        },
                        error: function() {
                            $('#hashtag-suggestions').addClass('d-none');
                        }
                    });
                } else {
                    $('#hashtag-suggestions').addClass('d-none');
                }
            });

            // Handle hashtag selection
            $(document).on('click', '.hashtag-suggestion', function() {
                let selectedTag = $(this).text();
                let currentTags = $('#quote-tags').val();
                $('#quote-tags').val(currentTags + ' ' + selectedTag).focus();
                $('#hashtag-suggestions').addClass('d-none');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#quote-tags').length && !$(e.target).closest('#hashtag-suggestions').length) {
                    $('#hashtag-suggestions').addClass('d-none');
                }
            });
        });
    </script>
</body>
</html>
