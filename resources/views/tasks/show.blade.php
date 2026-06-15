<x-layout>
    <h2>{{$task->tittle}}</h2>
    <div class="bg-blue-200 p-4 rounded">
        <p><strong>Határidő: </strong> {{$task->due_date}}</p>
        <p><strong>Státusz: </strong> {{$task->status?->label() ?? 'Nincs beállítva'}}</p>
        <strong>Leírás: </strong>
        <p>{{$task->description}}</p>
        <form action="">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn my-4">Feladat törlése</button>
    </form>
    </div>
    
</x-layout>