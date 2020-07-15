
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Bootstrap4/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/font-awesome-4.7.0/css/font-awesome.min.css">
    @yield('linkCss')
    <title>{{$title ?? 'Laravel 7'}}</title>
</head>

<body>
    @if (Auth::user()->roles[0] == "Master")
        @include('layout.Masternavbar')
        <div class="container mb-4 py-4">
            @include('Master.alert')
            @yield('content')
        </div>
    @elseif(Auth::user()->roles[0] == "Admin")
        @include('layout.Adminnavbar')
        <div class="container mb-4 py-4">
            @include('Admin.alert')
            @yield('content')
        </div>
    @else 
        <p>a</p>
    @endif
    <script src="{{ asset('js/jquery_3_5_1.min.js')}}" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/popper.min.js')}}" crossorigin="anonymous">
    </script>
    <script src="{{ asset('Bootstrap4/js/bootstrap.min.js') }}"></script>
    <script src="/js/index.js"></script>
    @yield('script')
</body>

</html>
