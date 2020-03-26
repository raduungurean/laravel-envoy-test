<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head')
</head>
<body>
<div class="preloader">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
@include('includes.nav')
<div class="container">
    @yield('content')
</div>
@include('includes.footer')
</body>
</html>
