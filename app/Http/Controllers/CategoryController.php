<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Muestra una lista de las categorías del usuario.
     */
    public function index()
    {
        $categories = Auth::user()->categories()->latest()->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Guarda una nueva categoría en la base de datos.
     */
      public function store(Request $request)
    {
        // 1. Validar los datos del formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // 2. Crear una nueva instancia de Categoría
        // Asegúrate de que el campo sea 'name', no 'nombre'
        auth()->user()->categories()->create($validated);

        // 3. Redireccionar con un mensaje de éxito
        return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una categoría.
     */
    public function edit(Category $category)
    {
        if (Auth::id() !== $category->user_id) {
            abort(403);
        }
        
        return view('categories.edit', compact('category'));
    }

    /**
     * Actualiza una categoría en la base de datos.
     */
    public function update(Request $request, Category $category)
    {
        if (Auth::id() !== $category->user_id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Elimina una categoría de la base de datos.
     */
    public function destroy(Category $category)
    {
        if (Auth::id() !== $category->user_id) {
            abort(403);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoría eliminada exitosamente.');
    }
}