<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{asset('bootstrap-5.0.0/css/bootstrap.min.css')}}" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

        <script src="{{asset('js/popper.min.js.js')}}"></script>
        <script src="{{asset('js/jquery-3.3.1.slim.min.js')}}" ></script>
        <script src="{{asset('js/bootstrap.bundle.min.js')}}" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

        <script src="{{asset('js/echarts.min.js')}}" ></script>
    
    
    </head>
    <body >
        <div class="row " style="background-color:#418fde;">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/database">Filter</a>
                </li>
               
                @if (request()->session()->get('authenticated')!=null)
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/import">Import</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/managezones">Manage crisis zones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/logout">Logout</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/accessimport">login</a>
                    </li>
                @endif
            </ul>
        </div>
        <div class="row">
            <div class="col">
                @yield('content')
            </div>
        </div>
    </body>
</html>
