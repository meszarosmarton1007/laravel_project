<x-layout>
    <h2>{{$task->tittle}}</h2>
    <div class="bg-blue-200 p-4 rounded">
        <p><strong>Határidő: </strong> {{$task->due_date}}</p>
        <p><strong>Státusz: </strong> {{$task->status?->label() ?? 'Nincs beállítva'}}</p>
        <strong>Leírás: </strong>
        <p>{{$task->description}}</p>
        <a href="{{route('tasks.edit', $task->id)}}" class="btn">A feladat szerkesztése</a>
        <form action="{{route('tasks.destroy', $task->id)}}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn my-4">Feladat törlése</button>
    </form>
    </div>
    
</x-layout>