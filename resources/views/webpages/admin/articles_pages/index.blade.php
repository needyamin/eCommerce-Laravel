@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

<div class="container-fluid p-0">
<h1 class="mb-4">Articles</h1>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

 <a href="{{ route('articles.create') }}" class="btn btn-primary mb-3">Create New Article</a>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Content</th>
                    <th scope="col">Journal</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                    <tr>
                        <td>{{ $article->title }}</td>
                        <td>{{ $article->abstract }}</td>
                        <td>{{ $article->journal_title }}</td>
                        <td>
                 <!-- Delete Form -->
                <form action="{{ route('articles.destroy', $article->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this article?')">Delete</button>
                </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

</div>
		

@endsection