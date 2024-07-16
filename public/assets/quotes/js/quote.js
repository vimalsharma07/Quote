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
            const quoteElement = $(this).closest('.quote-post').find('.background-selector')[0];

            domtoimage.toBlob(quoteElement)
                .then(function(blob) {
                    const formData = new FormData();
                    formData.append('image', blob);
                    formData.append('quote_id', quoteId);

                    $.ajax({
                        url: '/upload-captured-image',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // Ensure CSRF token is sent
                        },
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.success) {
                                $('#capturedImage').attr('src', response.image_url);
                                document.getElementById('shareModal').setAttribute('data-image-url', response.image_url);
                                document.getElementById('shareModal').setAttribute('data-quote-id', quoteId);
                                new bootstrap.Modal(document.getElementById('shareModal')).show();
                                                            } else {
                                alert('Failed to upload image');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                        }
                    });
                })
                .catch(function(error) {
                    console.error('dom-to-image error:', error);
                });
        });

        // Share Options
        $('.share-option').click(function() {
            const option = $(this).data('option');
            const imageUrl = $('#shareModal').data('image-url');
            let shareUrl = '';
            switch (option) {
                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=Check%20out%20this%20quote!%20${encodeURIComponent(imageUrl)}`;
                    window.open(shareUrl, '_blank');
                    break;
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(imageUrl)}`;
                    window.open(shareUrl, '_blank');
                    break;
                case 'instagram':
                    alert('Sharing on Instagram is not supported through a direct link. Please use the Instagram app or website.');
                    break;
                default:
                    break;
            }
            $('#shareModal').modal('hide');
        });

        // Download Button
        $('.download-button').click(function() {
            const quoteId = $(this).data('quote-id');
            const quoteElement = $(this).closest('.quote-post').find('.background-selector')[0];

            domtoimage.toBlob(quoteElement)
                .then(function(blob) {
                    const formData = new FormData();
                    formData.append('image', blob);
                    formData.append('quote_id', quoteId);

                    $.ajax({
                        url: '/download-captured-image',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // Ensure CSRF token is sent
                        },
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.success) {
                                const a = document.createElement('a');
                                a.href = response.download_url;
                                a.download = 'quote_image.png';
                                document.body.appendChild(a);
                                a.click();
                                a.remove();
                            } else {
                                alert('Failed to download image');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                        }
                    });
                })
                .catch(function(error) {
                    console.error('dom-to-image error:', error);
                });
        });
    });
