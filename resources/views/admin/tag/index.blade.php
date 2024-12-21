@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card mt-5">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3>All Tags</h3>
                <a href="{{ route('admin-tags-create') }}" class="btn btn-light">Add New Tag</a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Tag Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tags as $tag)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $tag->name }}</td>
                                <td>
                                    <span class="badge badge-{{ $tag->status ? 'success' : 'secondary' }}">
                                        {{ $tag->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <!-- Change Status Button -->
                                    <form action="{{ route('admin-tags-create', $tag->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $tag->status ? 0 : 1 }}">
                                        <button type="submit" class="btn btn-sm btn-{{ $tag->status ? 'secondary' : 'success' }}">
                                            {{ $tag->status ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <!-- Delete Button -->
                                    <form action="{{ url('/admin/tag/'.$tag->id) }}" method="GET" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this tag?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No tags found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $tags->links() }} <!-- Pagination links -->
                </div>
            </div>
        </div>
    </div>
@endsection
