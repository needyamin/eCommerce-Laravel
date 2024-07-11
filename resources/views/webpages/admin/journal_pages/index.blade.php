@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')


<div class="container-fluid p-0">

        <h1 class="mb-4">Journals</h1>
        <a href="{{ route('journals.create') }}" class="btn btn-primary mb-3">Create New Journal</a>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">ISSN</th>
                    <th scope="col">Impact Factor</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>

                </tr>
            </thead>
            <tbody>
                @foreach($journals as $journal)
                    <tr>
                        <td>{{ $journal->title }}</td>
                        <td>{{ $journal->issn }}</td>
                        <td>{{ $journal->impact_factor }}</td>
                        <td>{{ $journal->description }}</td>
                        <td>
                            <form action="{{ route('journals.destroy', $journal->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


</div>
		



@endsection