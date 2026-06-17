<x-layout>
    @php
    $mainTaskColor = match($task->status){
        \App\Enums\TaskStatus::Todo => 'border-gray-400 bg-gray-50 text-gray-800',
        \App\Enums\TaskStatus::InProgress => 'border-blue-500 bg-blue-50 text-blue-900',
        \App\Enums\TaskStatus::InReview => 'border-amber-500 bg-amber-50 text-amber-900',
        \App\Enums\TaskStatus::Completed => 'border-green-600 bg-green-50 text-green-900 opacity-75 line-trough',
        default => 'border-slate-300 bg-slate-50 text-slate-800'
    };

    $mainDueDateBadge = 'bg-white-100 text-gray-700';

    if ($task->due_date && $task->status !== \App\Enums\TaskStatus::Completed) {
        $now = now();
        $dueDate = $task->due_date;

        if ($dueDate->isPast() || $now->diffInHours($dueDate, false) < 24) {
            //24 órán belül lejár
            $mainDueDateBadge = 'bg-red-500 text-white font-bold animate-pulse';
        }elseif ($now->diffInDays($dueDate, false) < 4) {
            // 4 napnál kevesebb van hátra
            $mainDueDateBadge = 'bg-yellow-400 text-yellow-950 font-semibold';
        }else {
            //Több mint 4 nap
            $mainDueDateBadge = 'bg-green-100 text-green-800';
        }
    }
@endphp
    <h2>{{$task->title}}</h2>
    <div class="border-1-4 p-4 rounded-lg shadow-sm {{$mainTaskColor}}">
        <p><strong>Határidő: </strong> 
            <span class="text-xs px-2 py-1 rounded-full {{$mainDueDateBadge}}">
            {{$task->due_date?->format('Y-m-d H:i')}}
            @if ($task->status !== \App\Enums\TaskStatus::Completed && $task->due_date?->isPast())
            (Lejárt)
                
            @endif
        
        </span>
    </p>
        <p class="mb-2"><strong>Státusz: </strong> 
           <span class="text-xs px-2 py-0.5 rounded-full border bg-white/50 font-medium text-grey-900"> {{$task->status?->label() ?? 'Nincs beállítva'}}
           </span>
           </p>
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

<!-- validációs hibák-->
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