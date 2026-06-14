<?php

namespace App\Models;

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

    protected $casts = ['due_date' => 'datetime'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function subtasks(){
        return $this->hasMany(Task::class, 'parent_id');
    }
}
