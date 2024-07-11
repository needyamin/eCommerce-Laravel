@extends('layouts.admin')
@section('title', 'Create Article')
@section('content')

<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<div class="container-fluid p-0">
    <h1 class="mb-4">Create New Article</h1>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Article creation form -->
    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
        </div>

    <div class="mb-3">
    <label for="slug" class="form-label">Slug</label>
      <input type="text" class="form-control" id="slug" name="slug_url" value="{{ old('slug') }}">
    </div>


        <div class="mb-3">
            <label for="abstract" class="form-label">Abstract</label>
            <textarea class="form-control" id="content" name="abstract" value="{{ old('abstract') }}" rows="5" required>{{ old('content') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="journal_id" class="form-label">Journal</label>
            <select class="form-select" id="journal_id" name="journal_id" required>
                @foreach($journals as $journal)
                    <option value="{{ $journal->id }}" {{ old('journal_id') == $journal->id ? 'selected' : '' }}>
                        {{ $journal->title }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="keywords" class="form-label">Keywords</label>
            <select class="form-select" id="keywords" name="keywords[]" multiple="multiple"></select>
        </div>

        <div class="mb-3">
            <label for="publish_date" class="form-label">Publish date</label>
            <input type="date" class="form-control" id="publish_date" name="publish_date" value="{{ old('publish_date', now()->format('Y-m-d')) }}" style="width:20%;" required>
            </div>

        <div id="fileUploadContainer">
        <div class="mb-3">
        <label for="fileUpload" class="form-label">Upload File (pdf, doc, docs)</label>
            <input type="file" class="form-control" name="upload_pdf[]" id="file" required>
        </div>
        </div>

        <button type="button" class="btn btn-primary btn-sm" id="addNewButton">Add New File</button>




        <div class="mb-3 mt-3">
            <input type="hidden" class="form-control" id="editorial_decision" name="editorial_decision" value="1" required>
        </div>
        <div class="mb-3 mt-3">
        <button type="submit" class="btn btn-primary">Create Article</button>
        <a href="{{ route('articles.index') }}" class="btn btn-secondary">Back to Articles</a>
        </div>
    </form>
</div>


<script>
$(document).ready(function() {
    $('#keywords').select2({
        placeholder: 'Select keywords',
        minimumInputLength: 1,
        ajax: {
            url: '{{ route("keywords.search") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        maximumSelectionLength: 5,
        tags: true, // Enable tagging to allow any input
        createTag: function(params) {
            return {
                id: params.term,
                text: params.term,
                newTag: true // This will make sure that new tags are distinguishable
            };
        }
    });
});




  // multipule times add add new file 
document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('addNewButton').addEventListener('click', function () {
                var newFileInput = document.createElement('div');
                newFileInput.className = 'mb-3 file-upload-group';
                newFileInput.innerHTML = `
                    <label for="editorial_decision" class="form-label">Upload File</label>
                    <input type="file" class="form-control" name="upload_pdf[]" id="file" required>
                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-btn">Remove</button>
                `;
                document.getElementById('fileUploadContainer').appendChild(newFileInput);
            });

            document.getElementById('fileUploadContainer').addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-btn')) {
                    e.target.closest('.file-upload-group').remove();
                }
            });
        });

  // auto slug generator
    document.getElementById('title').addEventListener('input', function() {
    var title = this.value;
    var slug = title.toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .trim() 
                        .replace(/\s+/g, '-') 
                        .replace(/-+/g, '-'); 
        document.getElementById('slug').value = slug;
    });
</script>


@endsection
