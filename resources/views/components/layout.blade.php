<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Feladatok</title>

    @vite('resources/css/app.css')
</head>
<body>
    @if (session('succes'))
        <div id="flash" class="p-4 text-center gg-green-50 text-green-500 font-bold">
            {{session('succes')}}
        </div>
    @endif

    <header>
        <nav>
            <h1><a href="">Feladatok</a> </h1>

            @guest
                <a href="{{route('show.login')}}" class="btn">Bejelentkezés</a>
                <a href="{{route('show.register')}}" class="btn">Regisztráció</a>
            @endguest
        </nav>
    </header>

    <main class="container">
        {{$slot}}
    </main>
</body>
</html>