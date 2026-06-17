<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Emlékeztető feladat sátuszáról</title>
</head>
<body>
    <h2>Szia {{$task->user->name}}!</h2>
    <p>Ez egy autómatikus e-mail, mert az alábbi feladat(ok) határideje hamarosan lejár</p>

    <div>
        <h3>{{$task->title}}</h3>
        <p>{{$task->description}}</p>
        <p><strong>A feladat határideje:</strong> {{$task->due_date?->format('Y-m-d H:i')}} </p>
        <p><strong>A feladat aktuális állapota:</strong> {{$task->status?->label() ?? 'Nincs beállítva'}} </p>
    </div>
    <p>Üdvölettel: A weboldal</p>
</body>
</html>