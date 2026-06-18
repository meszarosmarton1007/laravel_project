<x-layout>
    <h1>Feladataim</h1> 
    <ul>
        @foreach ($tasks as $task)
        @if ($task->status != 'completed')
        @php
            $taskColor = match($task->status){
        \App\Enums\TaskStatus::Todo => 'border-gray-400 bg-gray-50 text-gray-800',
        \App\Enums\TaskStatus::InProgress => 'border-blue-500 bg-blue-50 text-blue-900',
        \App\Enums\TaskStatus::InReview => 'border-amber-500 bg-amber-50 text-amber-900',
        \App\Enums\TaskStatus::Completed => 'border-green-600 bg-green-50 text-green-900 opacity-75 line-trough',
        default => 'border-slate-300 bg-slate-50 text-slate-800'
    };

        $dueDateBadge = 'bg-white-100 text-gray-700';

        if ($task->due_date) {
        $now = now();
        $dueDate = $task->due_date;

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
            <li class="mb-4 list-none">
            <x-card href="{{route('tasks.show', $task->id)}}" class="border-l-4 p-4 rounded-lg shadow-sm {{$taskColor}}">
                <div>
                    <h3 class="text-xl font-bold mb-2">{{$task->title}}</h3>
                    <p class="mb-2">
                    <strong>Határidő: </strong>
                     <span class="text-medium px-2 py-1 rounded-full {{$dueDateBadge}}" >
                            {{$task->due_date?->format('Y-m-d H:i')}}
                        @if ($task->due_date?->isPast())
                            (Lejárt)
                        @endif
                    
                </span></p>

                   <p class="mb-1">
                    <strong>Státusz: </strong> <span class="text-medium px-2 py-1 rounded-full border bg-white/50 font-medium text-gray-900"> {{$task->status?->label() ?? 'Nincs beállítva'}}
           </span></p> 

                </div>
            </x-card>
        </li>
        @endif
        
            
        @endforeach
    </ul>
</x-layout>