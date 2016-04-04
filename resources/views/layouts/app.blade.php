<!-- resources/views/layouts/app.blade.php -->

<!Doctype html>
<html>
<head>
    <title>Perfmon</title>
</head>

<link href="{{ asset('css/app.css') }}" media="all" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="{{ asset('js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/system.js') }}"></script>

<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#perfmon-navbar"
                    aria-expanded="false">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Perfmon</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/">Overview</a></li>
            </ul>
        </div>
    </div>
</nav>

@if(count($errors) > 0)
    <div class="panel panel-default">
        <div class="panel-body">
            @include('common.errors')
        </div>
    </div>
@endif

@yield('content')

</body>
</html>