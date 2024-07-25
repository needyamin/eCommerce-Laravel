@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')


<div class="container-fluid p-0">


<h1 class="mb-4">Create Journal</h1>
        <form action="{{ route('journals.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Slug</label>
                <input type="text" class="form-control" id="slug" name="slug_url" value="{{ old('slug') }}" required>
            </div>

            <div class="mb-3">
                <label for="issn" class="form-label">ISSN</label>
                <input type="text" class="form-control" id="issn" name="issn" required>
            </div>
            <div class="mb-3">
                <label for="impact_factor" class="form-label">Impact Factor</label>
                <input type="number" step="0.01" class="form-control" id="impact_factor" name="impact_factor" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Journal</button>
        </form>
    </div>


    <script>
        document.getElementById('title').addEventListener('input', function() {
            var title = this.value;
            var slug = title.split(/\s+/)
                            .map(word => word.charAt(0).toUpperCase())
                            .join('');
            document.getElementById('slug').value = slug;
        });
    </script>

@endsection