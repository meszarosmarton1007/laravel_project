<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * legfőbb szülő megkeresése
     */
    public function getAbsoluteParentId(Task $task){
        while ($task->parent_id !== null){
            $task = Task::find($task->parent_id);
        }

        return $task->id;
    }

    /**
     * Fő feladatok listázása.
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->whereNull('parent_id')->with('subtasks')->get();

        return view('tasks.index', ["tasks" => $tasks]);
    }

    /**
     * új feladat hozzáadásának oldalának betöltése
     */
    public function create()
    {
        $tasks = Task::all();

        return view('tasks.create', ['tasks' => $tasks]);
    }

    /**
     * Feladat hozzáadásának megvalósítása
     */
    public function store(Request $request)
    {
        //Ha van parent_id, űrlap dátumának összefűzése és ellenőrzés
        if($request->filled('parent_id') && $request->filled('due_date_day') && $request->filled('due_date_time')){
            $requestedDueDate = $request->input('due_date_day') . ' ' . $request->input('due_date_time');
            $request->merge(['full_requested_due_date' => $requestedDueDate]);
        }

        //Dinamikus szabályok
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000',
            'status' => 'nullable|string',
            'parent_id' => 'nullable|exists:tasks,id',
            'due_date_day' => 'required|date|after_or_equal:today',
            'due_date_time' => 'required|date_format:H:i'
        ];

        //alfeladat esetén további dátum szerinti szűrés
        $parentTask = null;
        if ($request->filled('parent_id')){
            $parentTask = Task::find($request->input('parent_id'));
            if ($parentTask && $parentTask->due_date){
                $rules['full_requested_due_date'] = 'required|date|before_or_equal:' . $parentTask->due_date->format('Y-m-d H:i:s');
            }
        }

        //validálás
        $validated = $request->validate($rules, ['full_requested_due_date.before_or_equal' => 'Az alfeladat határideje nem esehet kívül a szülő feladat idejénél']);

        //dátum és idő mező összefűzése
        $fullDate = $validated['due_date_day'] . ' ' . $validated['due_date_time']. ':00';

        //ezekkel az adatokkal kerül létrehozásra
        $taskData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'user_id' => Auth::id(),
            'due_date' => $fullDate
        ];

        //új feladat létrehozása
        $task = Task::create($taskData);

        //alfeladat esetén a fő feladat nézetoldalára való visszatérés
        if($task->parent_id !== null){
            $absoluteParentId = $this->getAbsoluteParentId($task);
            return redirect()->route('tasks.show', $absoluteParentId)->with('success', 'Feladat sikeresen hozzáadva');
        }

        //fő feladat esetén az index oldalra való visszatérés
        return redirect()->route('tasks.index')->with('success', 'Feladat sikeresen hozzáadva');

    }

    /**
     * Feladat listázása
     */
    public function show(Task $task)
    {

    //biztonsági ellenőrzés
       if($task->user_id !== Auth::id()){

        abort(403, 'Nincs jogosultságod ehhez');

       }

       //alfeladatok betöltése
        $task->load('subtasks');

        //feladatok részletk oldal betöltése
        return view('tasks.show', ["task" => $task]);
    }

    /**
     * Szerkesztő felület betöltése
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
     * Meglévő feladat szerkesztése
     */
    public function update(Request $request, Task $task)
    {
        //Biztonsági ellenőrzés
        if ($task->user_id !== Auth::id()){
            abort(403, "Nincs jogosultágod ennek a feladatnak a szerkesztéséhez");
        }

       //Ha van parent_id, űrlap dátumának összefűzése és ellenőrzése
        if($task->parent_id && $request->filled('due_date_day') && $request->filled('due_date_time')){
            $requestedDueDate = $request->input('due_date_day') . ' ' . $request->input('due_date_time') . ':00';
            $request->merge(['full_requested_due_date' => $requestedDueDate]);
        }

        //Dinamikus szabályok
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10|max:1000',
            'status' => 'nullable|string',
            'parent_id' => 'nullable|exists:tasks,id',
            'due_date_day' => 'required|date|after_or_equal:' . $task->created_at->toDateString(),
            'due_date_time' => 'required|date_format:H:i'
        ];

        //alfeladat esetén további dátum szerinti szűrés
        $parentTask = null;
        if ($task->parent_id){
            $parentTask = Task::find($task->parent_id);
            if ($parentTask && $parentTask->due_date){
                $rules['full_requested_due_date'] = 'required|date|before_or_equal:' . $parentTask->due_date->format('Y-m-d H:i:s');
            }
        }

        //validálás
        $validated = $request->validate($rules, ['full_requested_due_date.before_or_equal' => 'Az alfeladat határideje nem esehet kívül a szülő feladat idejénél']);
 
        //dátum és idő mező összefűzése
        $fullDate = $validated['due_date_day'] . ' ' . $validated['due_date_time'] . ':00';

        //frissítés
        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'] ?? null,
            'due_date' => $fullDate
        ]);

        //ha alfeladat akkor a fő feladatz részleteinek oldalára tér vissza
        if($task->parent_id){
             $absoluteParentId = $this->getAbsoluteParentId($task);
            return redirect()->route('tasks.show', $absoluteParentId)->with('success', 'A feladat sikeresen frissítve');
        }

        //fő feladat esetén is a részletek oldalra tér vissza
        return redirect()->route('tasks.show', $task->id)->with('success', 'A feladat sikeresen frissítve');
    }

    /**
     * Feladat törlése
     */
    public function destroy(Task $task)
    {
        //jogosultsági ellenőrzés
        if ($task->user_id !== Auth::id()){
            abort(403, 'Nincs jogosultságod');
        }

        $parent_id = $task->parent_id;

        //törlés
        $task->delete();

         //ha alfeladat akkor a fő feladatz részleteinek oldalára tér vissza
        if($parent_id){
            $absoluteParentId = $this->getAbsoluteParentId($task);
            return redirect()->route('tasks.show', $absoluteParentId)->with('success', 'A feladat sikeresen frissítve');
        }

        //fő feladat esetén az index oladra tér vissza
        return redirect()->route('tasks.index')->with('sussess', 'A feladat sikeresen törölve');
    
    }
}
