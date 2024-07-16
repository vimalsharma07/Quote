$(document).ready(function () {
    // Set the main URL dynamically
    const mainUrl = window.location.origin;

    // Mobile and desktop notifications click event
    $('#navbarDropdownNotifications, #navbarDropdownNotificationsMobile').on('click', function () {
        // Fetch unread notifications via AJAX
        $.ajax({
            url: `${mainUrl}/notifications/mark-as-read`, // Set the correct URL using mainUrl
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content') // Fetch CSRF token from the meta tag
            },
            success: function () {
                setTimeout(() => {
                    $('#navbarDropdownNotifications').dropdown('hide');
                    $('#navbarDropdownNotificationsMobile').dropdown('hide');
                }, 5000);
            },
            error: function (xhr) {
                console.error('AJAX Error:', xhr.status, xhr.statusText);
            }
        });
    });

    // Initialize Bootstrap dropdown manually
    $('.dropdown-toggle').dropdown();
});
