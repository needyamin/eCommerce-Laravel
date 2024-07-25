@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

@section('summernote-header-tags')
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<link href="{{ url('template/admin/css/custom.css') }}" rel="stylesheet">
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

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.dashboard.store') }}" method="POST" enctype="multipart/form-data">
        @csrf


        <input type="hidden" class="form-control" name="vendor_id" value="1">
        <input type="hidden" class="form-control" name="stock_quantity" value="100">
        <input type="hidden" class="form-control" name="is_active" value="1">



        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="product_name" value="Test Title">
        </div>

        <div class="row">
            <!-- col: 1 -->
            <div class="col-2">
                <div class="mb-3">
                    <label for="title" class="form-label">Price</label>
                    <input type="number" class="form-control" name="price" value="100" />
                </div>
            </div>

            <!-- col: 2 -->
            <div class="col-2">
                <div class="mb-3">
                    <label for="title" class="form-label">Offer Percentage</label>
                    <input type="number" class="form-control" name="offer_percentage" value="100" />
                </div>
            </div>

            <!-- col: 3 -->
            <div class="col-2">
                <div class="mb-3">
                    <label for="title" class="form-label">Stock Quantity</label>
                    <input type="number" class="form-control" name="stock_quantity" value="100">
                </div>
            </div>

            <!-- col: 4 -->
            <div class="col-3">
                <div class="mb-3">
                    <label for="title" class="form-label">Category</label>
                    <select class="form-control" name="category_id" required>
                        <option selected disabled>Select One</option>
                        <option value="1" selected>Mobile</option>
                        <option value="2">Computer</option>
                    </select>
                </div>
            </div>

            <!-- col: 5 -->
            <div class="col-3">
                <div class="mb-3">
                    <label for="title" class="form-label">Brand</label>
                    <select class="form-control" name="brand" required>
                        <option selected disabled>Select One</option>
                        <option selected>Nokia</option>
                        <option>Apple</option>
                    </select>
                </div>
            </div>


            <!-- col: 3 -->
            <div class="col-12">
                <div class="mb-3">
                    <label for="title" class="form-label">SKU</label>
                    <input type="text" class="form-control" name="sku" value="100" />
                </div>
            </div>



        </div>


        <div class="mb-3">
            <div class="col-md-12">
                <label for="imageUpload" class="form-label">Upload Product Images</label>
                <input type="file" id="imageUpload" class="form-control" name="images[]" accept="image/*" multiple>
            </div>

            <div class="row mt-2">
                <div class="col-md-12">
                    <div id="previewContainer"></div>
                </div>
            </div>
        </div>




        <div class="mb-3">
            <label for="title" class="form-label">Product Description</label>
            <textarea type="text" class="form-control" id="summernote" name="product_detail">{{ old('product_detail', $product->product_detail ?? '') }}</textarea>
        </div>


        <div class="mb-3">
            <input type="submit" class="form-control" />
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

<script>
    let allFiles = [];
    document.getElementById('imageUpload').addEventListener('change', function(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('previewContainer');

        Array.from(files).forEach(file => {
            allFiles.push(file);
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'thumbnail';

                const img = document.createElement('img');
                img.src = e.target.result;
                imgContainer.appendChild(img);

                const removeButton = document.createElement('button');
                removeButton.className = 'remove-btn';
                removeButton.innerHTML = '&times;';
                removeButton.addEventListener('click', () => {
                    const index = allFiles.indexOf(file);
                    if (index > -1) {
                        allFiles.splice(index, 1);
                    }
                    imgContainer.remove();
                });
                imgContainer.appendChild(removeButton);

                previewContainer.appendChild(imgContainer);
            }
            reader.readAsDataURL(file);
        });

        // Clear the file input to allow re-upload of the same file if needed
        //event.target.value = '';
    });
</script>


@endsection