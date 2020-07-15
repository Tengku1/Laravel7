<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Bootstrap4/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/font-awesome-4.7.0/css/font-awesome.min.css">
    @yield('linkCss')
    <title>{{$title ?? 'Crux'}}</title>
</head>

<body>
    @include('layouts.Navbar')
    <div class="container mb-4 py-4">
        @include('layouts.alert')
        @yield('content')
    </div>
    <script src="{{ asset('js/jquery_3_5_1.min.js')}}" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/popper.min.js')}}" crossorigin="anonymous">
    </script>
    <script src="{{ asset('Bootstrap4/js/bootstrap.min.js') }}"></script>
    <script src="/js/index.js"></script>
    @yield('script')
</body>

</html>