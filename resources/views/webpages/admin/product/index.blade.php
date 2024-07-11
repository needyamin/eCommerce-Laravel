@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')


<div class="container-fluid p-0">

        <h1 class="mb-4">Products</h1>
        <a href="{{ route('admin.dashboard.create') }}" class="btn btn-primary mb-3">Add New Product</a>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Vendor Name</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Sku</th>
                    <th scope="col">Actions</th>

                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->vendor_id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>
                            <form action="{{ route('admin.dashboard.destroy', $product->id) }}" method="POST">
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