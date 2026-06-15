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
            

            @guest
                <h1><a href="{{route('welcome')}}">Feladatok</a> </h1>
                <a href="{{route('show.login')}}" class="btn">Bejelentkezés</a>
                <a href="{{route('show.register')}}" class="btn">Regisztráció</a>
            @endguest

            @auth
                <h1><a href="{{route('tasks.index')}}">Feladatok</a> </h1>
                <span class="border-r-2 pr-2">
                    Szia {{Auth::user()->name}}
                </span>
                <a href="{{route('tasks.create')}}">Új feladat létrehozása</a>
                <form action="{{route('logout')}}" method="POST" class="m-0">
                    @csrf
                    <button class="btn">
                        Kijelentkezés
                    </button>
                </form>
            @endauth
        </nav>
    </header>

    <main class="container">
        {{$slot}}
    </main>
</body>
</html>