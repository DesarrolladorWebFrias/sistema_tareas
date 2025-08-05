<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Muestra una lista de todas las tareas del usuario autenticado.
     */
    public function index()
    {
        // Obtener las tareas del usuario actual y cargarlas con sus categorías
        $tasks = Auth::user()->tasks()->with('category')->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Muestra el formulario para crear una nueva tarea.
     */
    public function create()
    {
        $categories = Auth::user()->categories;
        return view('tasks.create', compact('categories'));
    }

    /**
     * Guarda una nueva tarea en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Auth::user()->tasks()->create($request->all());

        return redirect()->route('tasks.index')->with('success', 'Tarea creada exitosamente.');
    }

    /**
     * Muestra una tarea específica.
     */
    public function show(Task $task)
    {
        // Asegurarse de que el usuario autenticado sea el dueño de la tarea
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * Muestra el formulario para editar una tarea.
     */
    public function edit(Task $task)
    {
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        $categories = Auth::user()->categories;
        return view('tasks.edit', compact('task', 'categories'));
    }

    /**
     * Actualiza una tarea en la base de datos.
     */
    public function update(Request $request, Task $task)
    {
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'Tarea actualizada exitosamente.');
    }

    /**
     * Elimina una tarea de la base de datos.
     */
    public function destroy(Task $task)
    {
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tarea eliminada exitosamente.');
    }
}