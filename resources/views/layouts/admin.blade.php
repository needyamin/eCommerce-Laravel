<!DOCTYPE html>
<html lang="en">


<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="Journal">
	<meta name="keywords" content="Journal, article, website, blog">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'My Website')</title>
	<link href="{{ asset('template/admin/css/app.css')}}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

  <!-- SummerNote Tags -->
  @yield('summernote-header-tags')

</head>


<body>
	  <div class="wrapper">
      @include('re_usable_admin.aside')
    <div class="main">
      @include('re_usable_admin.header')
        <main class="content">
           @yield('content') 
        </main>
       @include('re_usable_admin.footer')
     </div>
    </div>

  <!-- SummerNote Tags -->
  @yield('summernote-footer-tags')

<script src="{{ asset('template/admin/js/app.js')}}"></script>
</body>
</html>


  



