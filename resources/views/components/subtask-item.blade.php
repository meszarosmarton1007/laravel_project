@props(['subtask'])

<div class="border-l-4 border-blue-500 pl-4 my-4 bg-gray-50 p-3 rounded shadow-s">
    <div class="flex justify-between items-center"></div>
    <div>
        <h4 class="font-bold text-lg text-gray-800">{{$subtask->title}}</h4>
        <p class="text-sm text-gray-600">{{$subtask->description}}</p>
        <p class="text-xs text-gray-500 mt-1">
            <strong>Határidő: </strong> {{$subtask->due_date}}
           <strong>Státusz: </strong> {{$subtask->status->label()}}
        </p>
    </div>
    <div class="flex space-x-2">
        <a href="{{route('tasks.edit', $subtask->id)}}" class="btn">Szerkesztés</a>

        <form action="{{route('tasks.destroy', $subtask->id)}}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn">Törlés</button>
        </form>
    </div>
    <details class="mt-2 text-xs">
        <summary class="cursor-pointer text-blue-600 hover:underline">Alfeladat hozzáadása</summary>
        <div class="mt-2 p-2 border rounded bg-white">
            @include('tasks.partials.subtask-form', ['parent_id' => $subtask->id])
        </div>
    </details>

    <!--Rekurzió-->
    @if ($subtask->subtasks->count() > 0)
    <div class="ml-6 mt-2">
        @foreach ($subtask->subtasks as $childSubtask)
            <x-subtask-item :subtask="$childSubtask"/>
        @endforeach
    </div>
        
    @endif
    


</div>