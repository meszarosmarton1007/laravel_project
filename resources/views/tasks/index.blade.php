<x-layout>
    <h1>Feladataim</h1> 
    <ul>
        @foreach ($tasks as $task)
        @if ($task->status != 'completed')
            <li>
            <x-card href="{{route('tasks.show', $task->id)}}" :highlight="$task['due_date']">
                <div>
                    <h3>{{$task->title}}</h3>
                    <p>{{$task->description}}</p>
                    <p>{{$task->due_date}}</p>
                    <p>{{$task->status?->label() ?? 'Nincs beállítva'}}</p>

                </div>
            </x-card>
        </li>
        @endif
        
            
        @endforeach
    </ul>
</x-layout>