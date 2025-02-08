@extends('layouts.front')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>
                <div class="card-body">
                    <form id="loginForm" method="POST" action="{{ route('userlogin') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" class="form-control" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>

                    <div id="error-msg" class="alert alert-danger d-none"></div>
                </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $("#loginForm").submit(function (event) {
        event.preventDefault(); // Page reload hone se roko
        
        $.ajax({
            url: "{{ route('userlogin') }}", // Laravel route
            type: "POST",
            data: $(this).serialize(), // Form data serialize karo
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            success: function (response) {
                if (response.success) {
                    window.location.href = response.redirect; // Redirect on success
                } else {
                    $("#error-msg").removeClass("d-none").text(response.message);
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON;
                $("#error-msg").removeClass("d-none").text(errors.message || "Login failed!");
            }
        });
    });
});
</script>

@endsection
