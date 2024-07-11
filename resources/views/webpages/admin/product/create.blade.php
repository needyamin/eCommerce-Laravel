@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

@section('summernote-header-tags')
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endsection



<div class="container-fluid p-0">

<h1 class="mb-4">Add New Products</h1>
     @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

<form action="{{ url('journals.store') }}" method="POST">
            @csrf
            
   <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="product_title" name="product_title" required>
        </div>
        
<div class="row">
    <!-- col: 1 -->
    <div class="col-4"> 
<div class="mb-3">
    <label for="title" class="form-label">SKU</label> 
       <input type="text"  class="form-control"  name="sku"/>
    </div>
</div>

<!-- col: 2 -->
<div class="col-4"> 
    <div class="mb-3">
    <label for="title" class="form-label">SKU</label> 
       <input type="text"  class="form-control"  name="sku"/>
    </div>
</div>

<!-- col: 3 -->
<div class="col-4"> 
    <div class="mb-3">
    <label for="title" class="form-label">SKU</label> 
       <input type="text"  class="form-control"  name="sku"/>
    </div>

</div>
</div>


  <div class="mb-3">
    <label for="title" class="form-label">Product Description</label> 
       <input type="text"  class="form-control" id="summernote" name="product_description"/>
       </div>




    </form>


</div>
@endsection


@section('summernote-footer-tags')
    <script>
      $('#summernote').summernote({
        placeholder: 'Enter your product description',
        tabsize: 2,
        height: 100,
        toolbar: [
    // [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline', 'clear']],
    ['font', ['strikethrough', 'superscript', 'subscript']],
    ['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['height', ['height']]
  ]
      });
    </script>
@endsection
  