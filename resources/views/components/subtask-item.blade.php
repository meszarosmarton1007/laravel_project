@props(['subtask'])
@php
    $statusColor = match($subtask->status){
        \App\Enums\TaskStatus::Todo => 'border-gray-400 bg-gray-50 text-gray-800',
        \App\Enums\TaskStatus::InProgress => 'border-blue-500 bg-blue-50 text-blue-900',
        \App\Enums\TaskStatus::InReview => 'border-amber-500 bg-amber-50 text-amber-900',
        \App\Enums\TaskStatus::Completed => 'border-green-600 bg-green-50 text-green-900 opacity-75 line-trough',
        default => 'border-slate-300 bg-slate-50 text-slate-800'
    };

    $dueDateBadge = 'bg-white-100 text-gray-700';

    if ($subtask->due_date && $subtask->status !== \App\Enums\TaskStatus::Completed) {
        $now = now();
        $dueDate = $subtask->due_date;

        if ($dueDate->isPast() || $now->diffInHours($dueDate, false) < 24) {
            //24 órán belül lejár
            $dueDateBadge = 'bg-red-500 text-white font-bold animate-pulse';
        }elseif ($now->diffInDays($dueDate, false) < 4) {
            // 4 napnál kevesebb van hátra
            $dueDateBadge = 'bg-yellow-400 text-yellow-950 font-semibold';
        }else {
            //Több mint 4 nap
            $dueDateBadge = 'bg-green-100 text-green-800';
        }
    }
@endphp

<div class="border-l-4 pl-4 my-4 p-3 rounded shadow-sm {{$statusColor}}">
    <div class="flex justify-between items-center"></div>
    <div>
        <h4 class="font-bold text-lg">{{$subtask->title}}</h4>
        <p class="text-sm opacity-90">{{$subtask->description}}</p>
        <p class="text-xs mt-1">
            <strong>Státusz: </strong> 
            <span class="text-medium px-2 py-0.5 rounded-full border bg-white/50 font-medium">
                    {{ $subtask->status?->label() ?? 'Nincs beállítva' }}
                </span>
            @if ($subtask->due_date)
                <strong>Határidő: </strong> 
                <span class="text-medium px-2 py-0.5 rounded-full {{$dueDateBadge}}">{{$subtask->due_date->format('Y-m-d H:i')}}
                @if ($subtask !== \App\Enums\TaskStatus::Completed && $subtask->due_date->isPast())
                    (Lejárt)
                @endif
                </span>
            @endif
            
           
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