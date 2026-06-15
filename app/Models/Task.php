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

    public function subtasks(){
        return $this->hasMany(Task::class, 'parent_id');
    }

 
}
