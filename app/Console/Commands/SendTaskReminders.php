<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Task;
use App\Mail\ReminderTaskStatus;
use App\Enums\TaskStatus;
use Illuminate\Support\Facades\Mail;



#[Signature('app:send-task-reminders')]
#[Description('Emlékeztető e-mail küld azoknak a felhasználóknak, akiknek van közeli lejáratú befejezetlen feladata')]
class SendTaskReminders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $twentyFourHoursFromNow = now()->addHours(24);

        $urgentTasks = Task::where(function ($querry) {
            $querry->where('status', '!=', TaskStatus::Completed)->orWhereNull('status');
        })->whereBetween('due_date', [now(), $twentyFourHoursFromNow])
            ->with('user')->get();

        if ($urgentTasks->isEmpty()){
            $this->info('Nem található 24 órán belül lejáró feladat');
            return Command::SUCCESS;
        }
        
        $count = 0;
        foreach($urgentTasks as $urgentTask){
            if($urgentTask->user && $urgentTask->user->email){
                Mail::to($urgentTask->user->email)->send(new ReminderTaskStatus($urgentTask));
                $count++;
            }
            
        }

        $this->info("Sikeresen kiküldve {$count} darab email.");

        return Command::SUCCESS;
    }
}
