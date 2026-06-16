<x-layout>
    <h2>{{$task->title}}</h2>
    <div class="bg-blue-200 p-4 rounded">
        <p><strong>Határidő: </strong> {{$task->due_date}}</p>
        <p><strong>Státusz: </strong> {{$task->status?->label() ?? 'Nincs beállítva'}}</p>
        <strong>Leírás: </strong>
        <p>{{$task->description}}</p>
        <div class="flex space-x-2 items-center">
            <a href="{{route('tasks.edit', $task->id)}}" class="btn">A feladat szerkesztése</a>
                <form action="{{route('tasks.destroy', $task->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn my-4">Feladat törlése</button>
                </form>
        </div> 
    </div>
    <details class="my-6 text-sm">
        <summary class="cursor-pointer text-blue-600 hover:underline font-semibold">
            Alfeladat hozzáadása
        </summary>
        <div class="bg-blue-50 p-4 rounded border border-blue-200 my-6">
            <h3 class="text-x1 font-semibold text-blue-900 mb-3">Új alfeladat hozzáadása</h3>
            @include('tasks.partials.subtask-form', ['parent_id' => $task->id])
    </div>
    </details>

<div class="mt-6 max-w-2x1">
    <h3 class="text-x1 font-bold text-gray-800 mb-4">A feladathoz tartozó alfeladatok</h3>
    @if ($task->subtasks->count() > 0)
        <div class="space-y-4">
            @foreach ($task->subtasks as $subtask)
                <x-subtask-item :subtask="$subtask" />
            @endforeach
        </div>
    @else
        <p class="text-gray-500 italic">Ehhez a feladathoz még nem adtak hozzá alfeladatot</p>
    @endif
</div>

 @if ($errors->any())
      <ul class="px-4 py-2 bg-red-100">
        @foreach ($errors->all() as $error)
            <li class="my-2 text-red-500">
              {{$error}}
            </li>
        @endforeach
      </ul>
  @endif
    
</x-layout>