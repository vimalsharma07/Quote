@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card mt-5">
            <div class="card-header bg-primary text-white">
                <h3>Create a New Tag</h3>
            </div>
            <div class="card-body">
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
                <form action="{{ route('admin.tags.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Tag Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter tag name" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Tag</button>
                </form>
            </div>
        </div>
    </div>
@endsection
