<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> <!-- Scripts -->


        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
        @auth
    var userId = "{{ Auth::user()->id }}";
         setTimeout(() => {
        window.Echo.channel('testChannel').listen('SendMessage', (e) => {
            if(userId != e.userId){
                var message = `<h1 class="p-1 "><span class="font-bold">${e.name} : </span>${e.message} </h1>`;
                $('#messages').append(message);
                $("#messages").scrollTop($("#messages")[0].scrollHeight);
            }
        })
    }, 200);
        $(document).ready(function() {
            $("#messages").scrollTop($("#messages")[0].scrollHeight);

            $("#submitBtn").click(function(event) {
                event.preventDefault(); // Prevent the default form submission

                var formData = $("#myForm").serialize()
                var _token  = "{{ csrf_token() }}",
                
                formData = formData + "&_token=" + _token;
                $.ajax({
                    url: "/submit", // Specify your URL here
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        console.log("Form submitted successfully!");
                        var message = `<h1 class="p-1 text-right"><span class="font-bold">${response.name} : </span>${response.message} </h1>`;
                        $('#messages').append(message);
                        $('#message').val('');
                        $("#messages").scrollTop($("#messages")[0].scrollHeight);

                        // You can perform any further actions here
                    },
                    error: function(xhr, status, error) {
                        console.error("Form submission failed with status: " + xhr.status);
                    }
                });
            });
        });
        @endauth

    </script>
</body>

</html>
