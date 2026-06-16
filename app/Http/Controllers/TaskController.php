<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->whereNull('parent_id')->with('subtasks')->get();

        return view('tasks.index', ["tasks" => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tasks = Task::all();

        return view('tasks.create', ['tasks' => $tasks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000',
            'status' => 'nullable|string',
            'parent_id' => 'nullable|exists:tasks,id',
            'due_date_day' => 'required|date',
            'due_date_time' => 'required|date_format:H:i'
        ]);


        $fullDate = $validated['due_date_day'] . ' ' . $validated['due_date_time']. ':00';

        $taskData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'user_id' => Auth::id(),
            'due_date' => $fullDate
        ];

        $task = Task::create($taskData);

        if($task->parent_id){
            return redirect()->route('tasks.show', $task->parent_id)->with('success', 'Feladat sikeresen hozzáadva');
        }

        return redirect()->route('tasks.index')->with('success', 'Feladat sikeresen hozzáadva');

    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
       if($task->user_id !== Auth::id()){

        abort(403, 'Nincs jogosultságod ehhez');

       }

        $task->load('subtasks');

        return view('tasks.show', ["task" => $task]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //Biztonsági ellenőrzés
        if ($task->user_id !== Auth::id()){
            abort(403, "Nincs jogosultágod ennek a feladatnak a szerkesztéséhez");
        }

        return view('tasks.edit', ["task" => $task]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //Biztonsági ellenőrzés
        if ($task->user_id !== Auth::id()){
            abort(403, "Nincs jogosultágod ennek a feladatnak a szerkesztéséhez");
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000',
            'status' => 'nullable|string',
            'parent_id' => 'nullable|exists:tasks,id',
            'due_date_day' => 'required|date',
            'due_date_time' => 'required|date_format:H:i'
        ]);

        $fullDate = $validated['due_date_day'] . ' ' . $validated['due_date_time'] . ':00';

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'] ?? null,
           // 'parent_id' => $validated['parent_id'] ?? null,
            'due_date' => $fullDate
        ]);

        if($task->parent_id){
            return redirect()->route('tasks.show', $task->parent_id)->with('success', 'A feladat sikeresen frissítve');
        }

        return redirect()->route('tasks.index')->with('success', 'A feladat sikeresen frissítve');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== Auth::id()){
            abort(403, 'Nincs jogosultságod');
        }

        $parent_id = $task->parent_id;

        $task->delete();

        if($parent_id){
            return redirect()->route('tasks.show', $parent_id)->with('success', 'A feladat sikeresen frissítve');
        }

        return redirect()->route('tasks.index')->with('sussess', 'A feladat sikeresen törölve');
    
    }
}
