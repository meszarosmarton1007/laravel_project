<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case InReview = 'in_review';
    case Completed = 'completed';

    public function label(){
        
        return match($this){
            self::Todo => 'Függőben',
            self::InProgress => 'Folyamatban',
            self::InReview => 'Ellenőrzés alatt',
            self::Completed => 'Befejezett'
        };
    }
}
