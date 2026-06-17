<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'user_id',
        'parent_id'
    ];

    
    protected function casts() { 
       return [
        'due_date' => 'datetime',
        'status' => TaskStatus::class
        ];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    //autómatikus státuszfrissítés
    protected static function booted(){
        static::saved(function (Task $task){
            if($task->wasChanged('status')){
                $newStatus = $task->status;

                $subtasks = Task::where('parent_id', $task->id)->get();

                foreach ($subtasks as $subtask) {
                    Task::withoutEvents(function () use ($subtask, $newStatus){
                        $subtask->status = $newStatus;
                        $subtask->save();
                    });

                    static::triggerChildrenstatusUpdate($subtask, $newStatus);
                }
            }

            if($task->parent_id){
                $parent = Task::find($task->parent_id);

                if($parent){
                    $siblingTasks = Task::where('parent_id', $parent->id)->get();

                    $firstStatus = $siblingTasks->first()->status;

                    $allSame = $siblingTasks->every(function ($sub) use ($firstStatus){
                        return $sub->status !== null && $sub->status === $firstStatus;
                    });

                    if($allSame && $parent->status !== $firstStatus){
                        $parent->status = $firstStatus;

                        $parent->save();
                    }
                }
            }
        });
    }

    protected static function triggerChildrenstatusUpdate(Task $task, $newStatus){
        $subtasks = Task::where('parent_id', $task->id)->get();

        foreach ($subtasks as $subtask) {
            Task::withoutEvents(function () use ($subtask, $newStatus){
                $subtask->status = $newStatus;
                $subtask->save();
            });
            static::triggerChildrenstatusUpdate($subtask, $newStatus);
        }
    }


    public function subtasks(){
        return $this->hasMany(Task::class, 'parent_id');
    }

 
}
